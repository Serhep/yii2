<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Товар';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="product-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать товар', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Категории', ['./category'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Offers', ['./offer'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Войти в Allegro', 'https://allegro.pl.allegrosandbox.pl/auth/oauth/authorize?response_type=code&client_id='.Yii::$app->params['allegroCID'].'&redirect_uri='.Url::to('@web','https'), ['class' => 'btn btn-success']) ?>
        <?php
        $tok = $_SESSION['allanswer'];
        if($tok['access_token']) echo 'Авторизован на Allegro!'; else echo 'Не авторизован на Allegro!';?> 


    </p>
    <p>
    <?php ActiveForm::begin(); ?>
        <?=Html::checkboxList('colummVisible',$colummVisible,
        ['0'=>'CheckboxColumn','1'=>'SerialColumn','2'=>'Изоображение','3'=>'SKU',
        '4'=>'Название','5'=>'Кол-во на складе','6'=>'Тип товара','7'=>'ActionColumn'],['class'=>'form-control float-right']);?>
        <?=Html::submitButton('Скрыть выбранные', ['class' => 'btn btn-default']);?>
        <?php ActiveForm::end(); ?>
    </p>
    <p>
        <?php $form = ActiveForm::begin(); ?>
        <?=$form->field($searchModel, 'searchstring', [
        'template' => '<div class="input-group">{input}<span class="input-group-btn">' .
        Html::submitButton('Найти', ['class' => 'btn btn-default']) .
        '</span></div>',])->textInput(['placeholder' => 'Название или SKU']);?>
        <?php ActiveForm::end(); ?>
    </p>


    <?=Html::beginForm(['multi-delete'],'post');?>

    <?=Html::submitButton('Удалить выбранное', ['class' => 'btn btn-danger',]);?>
    <?=Html::submitButton('Выгрузить на Allegro', ['class' => 'btn btn-info', 'name' => 'output_toall', 'value' => 'out','formaction' => Url::to('@web/admin/product','https'),]);?> 
    <?=Html::submitButton('Заказы на Allegro', ['class' => 'btn btn-info', 'name' => 'orders', 'value' => 'get','formaction' => Url::to('@web/admin/product','https'),]);?>
    <?php echo '<br/>'; print_r($ok);?>       
    <p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'class' => 'yii\grid\CheckboxColumn',
                'visible' => isset($colummVisible)?!(in_array('0',$colummVisible)):'1',
            ],
            [
                'class' => 'yii\grid\SerialColumn',
                'visible' => isset($colummVisible)?!(in_array('1',$colummVisible)):'1',
            ],
            [
                'attribute' => 'Image',
                'format' => 'html',    
                'value' => function ($data) {
                    return Html::img(Yii::getAlias('@web').'/images/products/small/'. $data['image']);
                },
                'visible' => isset($colummVisible)?!(in_array('2',$colummVisible)):'1',
            ],
            [   
                'attribute' => 'sku',
                'visible' => isset($colummVisible)?!(in_array('3',$colummVisible)):'1',
            ],
            [   
                'attribute' => 'name',
                'visible' => isset($colummVisible)?!(in_array('4',$colummVisible)):'1',
            ],
            [   
                'attribute' => 'quantity',
                'visible' => isset($colummVisible)?!(in_array('5',$colummVisible)):'1',
            ],
            [   
                'attribute' => 'price',
                'visible' => isset($colummVisible)?!(in_array('6',$colummVisible)):'1',
            ],
            [   
                'attribute' => 'waretype',
                'visible' => isset($colummVisible)?!(in_array('6',$colummVisible)):'1',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'visible' => isset($colummVisible)?!(in_array('7',$colummVisible)):'1',
            ],
        ],
    ]); ?>
    </p>
 <?= Html::endForm();?> 


</div>
