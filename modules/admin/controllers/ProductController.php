<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\helpers\Url;
use app\modules\admin\models\Product;
use yii\data\ActiveDataProvider;
use app\modules\admin\controllers\AdminController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use app\modules\admin\models\PostSearch;
use app\modules\admin\models\Allegro;

/**
 * ProductController implements the CRUD actions for Product model.
 */
class ProductController extends AdminController
{

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'multi-delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Product models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Product::find(),
        ]);

        $searchModel = new PostSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->post());

        $colummVisible =  Yii::$app->request->post('colummVisible');   
        
        $allcode = Yii::$app->request->get('code');

        $allanswer = null;
        $ok = null;
        //$_SESSION['allanswer'] = $allanswer;
        
        if($allcode){
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, "https://allegro.pl.allegrosandbox.pl/auth/oauth/token");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=authorization_code&code=".$allcode."&redirect_uri=".Url::to('@web/admin/product','https'));
            curl_setopt($ch, CURLOPT_HEADER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Basic ".base64_encode(Yii::$app->params['allegroCID'].':'.Yii::$app->params['allegroCS'])));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            $resp = curl_exec($ch);

            curl_close($ch);

            $allanswer = Allegro::array_json($resp);

            session_start();
            $_SESSION['allanswer'] = $allanswer;

        }

        if(Yii::$app->request->post('output_toall')=='out') {
            
            $prodlist = Allegro::prod_json();
            $alleg = $_SESSION['allanswer']; 
            $ok = Allegro::output_prod($prodlist, $alleg['access_token']);
            //$ok = $prodlist;
        }

            return $this->render('index', [
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
                'colummVisible' => $colummVisible,
                'allanswer' => $allanswer,
                'ok' => $ok,
            ]);

    }

    public function actionGetOrders(){

        $alleg = $_SESSION['allanswer'];
        $ok = Allegro::order_list($alleg['access_token']);
        $ok = Allegro::array_json($ok);

        return $this->render('index', [
            'ok' => $ok,
        ]);

    }

    /**
     * Displays a single Product model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Product model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Product();

        /*if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->sku]);
        }*/

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            // ?????????????????? ?????????????????????? ?? ?????????????????? resize ?????????????????? ??????????????????????
            $model->upload = UploadedFile::getInstance($model, 'image');
            if ($name = $model->uploadImage()) { // ???????? ?????????????????????? ???????? ??????????????????
                // ?????????????????? ?? ???? ?????? ?????????? ??????????????????????
                $model->image = $name;
            }
            $model->save();
            return $this->redirect(['view', 'id' => $model->sku]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Product model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        /*if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->sku]);
        }*/

        $old = $model->image;
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            // ???????? ?????????????? checkbox ???????????????? ????????????????????????
            if ($model->remove) {
                // ?????????????? ???????????? ??????????????????????
                if (!empty($old)) {
                    $model::removeImage($old);
                }
                // ?????????????????? ?? ???? ???????????? ??????
                $model->image = '';
                // ?????????? ???????????????? ???? ??????????????
                $old = '';
            } else { // ?????????????????? ???????????? ??????????????????????
                $model->image = $old;
            }
            // ?????????????????? ?????????????????????? ?? ?????????????????? resize ?????????????????? ??????????????????????
            $model->upload = UploadedFile::getInstance($model, 'image');
            if ($new = $model->uploadImage()) { // ???????? ?????????????????????? ???????? ??????????????????
                // ?????????????? ???????????? ??????????????????????
                if (!empty($old)) {
                    $model::removeImage($old);
                }
                // ?????????????????? ?? ???? ?????????? ??????
                $model->image = $new;
            }
            $model->save();
            return $this->redirect(['view', 'id' => $model->sku]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }



    /**
     * Deletes an existing Product model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionMultiDelete()
    {
        $selection=(array)Yii::$app->request->post('selection');
        foreach($selection as $id){
            $this->findModel($id)->delete();
        }

        return $this->redirect(['index']);
    }


    /**
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
