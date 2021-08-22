<?php

namespace app\modules\admin\controllers;

use Yii;
use app\modules\admin\models\Category;
use yii\data\ActiveDataProvider;
use app\modules\admin\models\Allegro;
use app\modules\admin\models\Offer;
use yii\web\NotFoundHttpException;

class OfferController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Offer::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionGetOffers()
    {

        Offer::deleteAll();

        $alleg = $_SESSION['allanswer'];
        $ok = Allegro::get_offers(0, $alleg['access_token']);
        $ok = Allegro::array_json($ok);

        if (!empty($ok['offers'])){
            foreach ($ok['offers'] as $data){
                $model = new Offer();
                $model->offer_id = $data['id'];
                $model->name = $data['name'];
                $model->category_id = $data['category']['id'];
                $category = Category::findOne(['category_id' => $data['category']['id']]);
                $model->category = $category->name;
                $model->available = $data['stock']['available'];
                $model->price = $data['sellingMode']['price']['amount'];
                $model->format = $data['sellingMode']['format'];
                $model->status = $data['publication']['status'];
                $model->structure = $data;
                $model->save();
            }
        }

        $dataProvider = new ActiveDataProvider([
            'query' => Offer::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'ok' => $ok
        ]);
    }

    public function actionPublicate($id, $stat)
    {
        $pub_arr = ['publication' => ['status' => $stat]];

        $pub_json = json_encode($pub_arr);

        $alleg = $_SESSION['allanswer'];
        Allegro::public_offer($id, $pub_json, $alleg['access_token']);

        usleep(500000);
        $get_offer = Allegro::get_offers($id,$alleg['access_token']);
        $get_offer = Allegro::array_json($get_offer);

        if($get_offer['publication']['status'] == $stat){
            $model = Offer::find()->where(['offer_id' => $id,])->one();
            $model->status = $stat;
            $model->save();
        }

        $dataProvider = new ActiveDataProvider([
            'query' => Offer::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'ok' => $get_offer
        ]);

    }

    /**
     * Updates an existing Offer model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        return $this->render('update', [
            'model' => $model,
        ]);
    }
    /**
     * Deletes an existing Offer model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Offer::find(),
        ]);

        $this->findModel($id)->delete();

        $alleg = $_SESSION['allanswer'];
        $ok = Allegro::del_offer($id,$alleg['access_token']);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'ok' => $ok,
        ]);
    }

    public function actionOfferEdit($id, $edit)
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Offer::find(),
        ]);

        $hidden = 'block';
        $edit_arr = array();
        $model = Offer::find()->where(['offer_id' => $id,])->one();

        if($post = Yii::$app->request->post()) {

            $hidden = 'none';

            if(Yii::$app->request->post('cancel') != 'ok') {

                if ($edit == 'quant') {
                    $edit_arr = ['stock' => ['available' => (integer)$post['Offer']['available']]];

                }
                if ($edit == 'price') {
                    $edit_arr = ['sellingMode' => ['price' => ['amount' => $post['Offer']['price']]]];
                }

                $edit_json = json_encode($edit_arr);
                $alleg = $_SESSION['allanswer'];
                $ok = Allegro::public_offer($id, $edit_json, $alleg['access_token']);

            }

        }

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'model' => $model,
            'ok' => $edit_json,
            'hidden' => $hidden,
            'edit' => $edit,
        ]);
    }


    /**
     * Finds the Offer model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Offer the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Offer::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
