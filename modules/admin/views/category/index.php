<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Категории';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?=Html::a('Главная', ['go-to-cat', 'id' => null]);?>
        <?php if(is_array($cat_path)) {
                foreach ($cat_path as $data) {
                        echo ' -> <a href = '.Url::to('@web/admin/category/go-to-cat?id='.$data['id']).'>'.$data['name'].'</a>';
                    }
                }?>
    </p>

    <?=Yii::$app->session->getFlash('success');?>
    <!--<p>
        Yii::$app->session->getFlash('error');
        Yii::$app->session->getFlash('success');
         $form = ActiveForm::begin();
        Html::submitButton('Получить корневые категории', ['class' => 'btn btn-info', 'name' => 'get_cat', 'value' => 'in','formaction' => Url::to('@web/admin/category','https'),]);
        ActiveForm::end();
    </p>-->
    <!--<p>
    $form = ActiveForm::begin();
    Html::submitButton('Раскрыть нераскрытые категории', ['class' => 'btn btn-info', 'name' => 'ext_cat', 'value' => 'ext','formaction' => Url::to('@web/admin/category','https'),]);
    ActiveForm::end();
    </p>-->
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'summary' => false,
        'columns' => [
            'id',
            'category_id',
            'parent_id',
            [
                'attribute' => 'name',
                'format' => 'raw',
                'value' => function ($data) {
                                if(!($data->leaf||($data->expanded == 'FAILED'))) { 
                                    return Html::a($data->name, ['go-to-cat', 'id' => $data->category_id]);
                                }
                                else {
                                    return $data->name.'   '.Html::a('Создать продукт', ['prod-create', 'id' => $data->category_id], ['class'=>'btn btn-info btn-sm']);
                                }
                          },
                        ],
            //'expanded',
            //'leaf'
            [
                'attribute' => 'params',
                'format' => 'raw',
                'value' => function ($data) {
                                if($data->params) { 
                                    return 'LOADED';
                                }
                                else {
                                    return 'NOT LOADED';
                                }
                          },
            ],
        ],
    ]); ?>
    

</div>
