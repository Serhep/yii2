<?php

namespace app\modules\admin\controllers;

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
        $dataProvider = new ActiveDataProvider([
            'query' => Offer::find(),
        ]);

        Offer::deleteAll();

        $alleg = $_SESSION['allanswer'];
        $ok = Allegro::get_offers($alleg['access_token']);
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


        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionActivate($id)
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Offer::find(),
        ]);

        $pub_arr = ['publication' => ['status' => 'ACTIVE']];

        $pub_json = json_encode($pub_arr);

        $alleg = $_SESSION['allanswer'];
        $ok = Allegro::active_offer($id, $pub_json, $alleg['access_token']);
        $ok = Allegro::array_json($ok);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'ok' => $ok,
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
