<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Product */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Товары', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="product-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
    <?= Html::a('<- К товарам', ['index'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Изменить', ['update', 'id' => $model->sku], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->sku], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить товар?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'sku',
            ['attribute' => 'image',
                'format' => 'html',    
                'value' => function ($data) {
                    return Html::img(Yii::getAlias('@web').'/images/products/small/'. $data['image']);
                }],
            'name',
            'quantity',
            'waretype',
        ],
    ]) ?>

</div>
