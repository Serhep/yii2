<?php

namespace app\modules\admin\controllers;

use Yii;
use app\modules\admin\models\Category;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use app\modules\admin\controllers\AdminController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\admin\models\Allegro;
use yii\base\DynamicModel;

/**
 * CategoryController implements the CRUD actions for Category model.
 */
class CategoryController extends AdminController
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
                ],
            ],
        ];
    }

    /**
     * Lists all Category models.
     * @return mixed
     */
    public function actionIndex()
    {
        $query = Category::find();
        $query->where(['parent_id' => null,])->all();

        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);

        $ok = null;
        $fail_cat = array();

        if(Yii::$app->request->post('get_cat')=='in') { //get root categories
            
            $alleg = $_SESSION['allanswer'];
            $got = Allegro::get_cat(null, $alleg['access_token']);
            $ok = Allegro::array_json($got);
            foreach($ok['categories'] as $data) {
                $model = new Category();
                $model->category_id = $data['id'];
                $model->parent_id = $data['parent']['id'];
                $model->name = $data['name'];
                $model->leaf = $data['leaf'];
                $model->save();
            }
        }

        if(Yii::$app->request->post('ext_cat')=='ext') { //expand not expanded
            
            $alleg = $_SESSION['allanswer'];
            $arr_cat  = Category::find()->where(['leaf' => 0, 'expanded' => 'NOT EXPANDED'])
                                        ->asArray()
                                        ->all();
            if($arr_cat) {
                foreach($arr_cat as $key => $value){

                $cat = $arr_cat[$key]['category_id'];
                $got = Allegro::get_cat($cat, $alleg['access_token']);
                $ok = Allegro::array_json($got);

                    if(is_array($ok['categories'])){
                    foreach($ok['categories'] as $data) {
                        $model = new Category();
                        $model->category_id = $data['id'];
                        $model->parent_id = $data['parent']['id'];
                        $model->name = $data['name'];
                        $model->leaf = $data['leaf'];
                        $model->save();
                        }
                    $expand = Category::findOne(['category_id' => $cat,]);
                    $expand->expanded = 'EXPANDED';
                    $expand->save();
                    }
                    else {
                        array_push($fail_cat, $cat);
                        $expand = Category::findOne(['category_id' => $cat,]);
                        $expand->expanded = 'FAILED';
                        $expand->save();
                        Yii::$app->session->setFlash('error', "Не раскрыты категории: ".implode(',',$fail_cat));
                        }
                    }
                }
            else {
                Yii::$app->session->setFlash('success', "Все возможные категории раскрыты!");
                }   
            //}
        }

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'ok' => $ok,
            'arr_cat' => $arr_cat,
        ]);
    }

    /**
     * Displays a single Category model.
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
     * Creates a new Category model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Category();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Category model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Category model.
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

    /**
     * Finds the Category model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Category the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Category::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionGoToCat()
    {
        $cat_path = array();
        $id=Yii::$app->request->get('id');

            $cur_cat = Category::find()->where(['parent_id' => $id,])->one();

            while($cur_cat->parent_id){
                $cur_cat = Category::find()->where(['category_id' => $cur_cat->parent_id,])->one();
                array_unshift($cat_path, ['id' => $cur_cat->category_id, 'name' => $cur_cat->name]);
            }

            $query = Category::find();
            $query->where(['parent_id' => $id,])->all();

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
            ]);

            return $this->render('index', [
                'dataProvider' => $dataProvider,
                'cat_path' => $cat_path
            ]);

    }

    public function actionProdCreate($id)
    {
        $model = Category::find()->where(['category_id' => $id,])->one();
        $params = $model->params;

        if(!$params){
            $alleg = $_SESSION['allanswer'];
            $got = Allegro::get_params($id, $alleg['access_token']);
            $ok = Allegro::array_json($got);
            $model->params = $ok['parameters'];
            $model->save();
            $model = Category::find()->where(['category_id' => $id,])->one();
            $params = $model->params;
        }



        $prod_model = new DynamicModel(['name' => 'Ware', 
                                        'category' => ['id' => $id], 
                                        'stock' => ['available' => '1', 'unit' => 'UNIT'],
                                        'payments' => ['invoice' => 'NO_INVOICE'],
                                        'location' => ['city' => 'NN',
                                                        'countryCode'=> 'RU',
                                                        'postCode' => '123456',
                                                        'province' => 'NN'],
                                        'description' => ['sections' => [['items' => [['type' => '', 'content' => '<p>Good</p>']]]]],
                                        'sellingMode' => ['format' => 'BUY_NOW',
                                                           'price' => ['amount' => '100', 'currency' => 'PLN'],
                                                            'startingPrice' => null,
                                                            'minimalPrice' => null],
                                        'delivery' => ['additionalInfo' => 'Delivery',
                                                        'handlingTime' => '',
                                                        'shippingRates' => ['id' => 'fb3ac660-8afb-4fa9-880e-8460142e199f'],                                       
                                                        'shipmentDate' => null,
                                                        ],
                                        'parameters' => [['id' => '', 'valuesIds' => [''], 'values' => ['']]],                                                            
                                    ]);
        $prod_model -> addRule(['name'], 'string', ['max' => 64])              
                    -> addRule(['category', 'stock', 'payments', 'location'], 'each', ['rule' => ['string', 'max' => 64]])
                    -> addRule(['name', 'category', 'stock', 'payments', 'location', 'description', 'sellingMode', 'delivery'], 'required')
                    -> addRule(['description', 'sellingMode', 'delivery', 'parameters'], 'each', ['rule' => ['safe']]);

        if( $prod_model->load(Yii::$app->request->post()) && $prod_model->validate()){
                //$ok =  $prod_model->toArray();
                session_start();
                $_SESSION['prod_model'] = $prod_model;
                return $this->render('../product/prodload', ['model' => $prod_model]);
        }
        return $this->render('../product/create', ['model' => $prod_model, 'params' => $params]);
    }

    public function actionProdload()
    {
        $model = $_SESSION['prod_model'];
        $prodlist =  json_encode($model->toArray());      
        $alleg = $_SESSION['allanswer']; 

        $ok = Allegro::output_prod($prodlist, $alleg['access_token']);

        $err_str = substr($ok, strpos($ok, '{'));
        $err_arr = json_decode($err_str,true);
 
        return $this->render('../product/prodload', ['model' => $model, 'ok' => $err_arr]);
    }    

}
