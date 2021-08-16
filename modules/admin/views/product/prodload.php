<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

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
        <?= Html::a('Изменить', ['prod-create', 'id' => $model->category['id']], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Выгрузить', ['prodload'], ['class' => 'btn btn-success']) ?>

    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            [
            'label' => 'Category ID',
            'value' => $model->category['id'],
            ],
            [
                'label' => 'Quantity',
                'value' => $model->stock['available'].' ('.$model->stock['unit'].')',
            ],
            [
                'label' => 'Country Code',
                'value' => $model->location['countryCode'],
            ],
            [
                'label' => 'Post Code',
                'value' => $model->location['postCode'],
            ],
            [
                'label' => 'Province',
                'value' => $model->location['province'],
            ],
            [
                'label' => 'City',
                'value' => $model->location['city'],
            ],
            [
                'label' => 'Description Type',
                'value' => $model->description['sections'][0]['items'][0]['type'],
            ],
            [
                'label' => 'Description Content',
                'value' => $model->description['sections'][0]['items'][0]['content'],
            ],
            [
                'label' => 'Price',
                'value' => $model->sellingMode['price']['amount'].' '.$model->sellingMode['price']['currency'],
            ],
            [
                'label' => 'Selling Format',
                'value' => $model->sellingMode['format'],
            ],
            [
                'label' => 'Payments',
                'value' => $model->payments['invoice'],
            ],
            [
                'label' => 'Delivery Info',
                'value' => $model->delivery['additionalInfo'],
            ],
            [
                'label' => 'Delivery Handling Time',
                'value' => $model->delivery['handlingTime'],
            ],
            [
                'label' => 'Delivery Shipping Rates',
                'value' => $model->delivery['shippingRates']['id'],
            ],
        ],
    ]) ?>

    <?php if(isset($ok['id'])){
        echo '<h2 style = "color:green">Offer draft id: '.$ok['id'].' is uploaded!</h2>';
    }?>
    <?php if(!empty($ok['validation']['errors'])){?>
        <h3 style = "color:red">Errors</h3>
            <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Error type</th>
                        <th scope="col">Error message</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($ok['validation']['errors'] as $key => $value) {
                        echo '<tr>
                        <th scope="row">'.($key+1).'</th>
                        <td>'.$value['code'].'</td>
                        <td>'.$value['userMessage'].'</td>       
                        </tr>';
                    }?>
                    </tbody>
                </table>
        <?php }?>
        <?php if(!empty($ok['validation']['warnings'])){?>
                    <h3 style = "color:darkgoldenrod">Warnings</h3>
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Warning type</th>
                            <th scope="col">Warning message</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($ok['validation']['warnings'] as $key => $value) {
                            echo '<tr>
                        <th scope="row">'.($key+1).'</th>
                        <td>'.$value['code'].'</td>
                        <td>'.$value['userMessage'].'</td>       
                        </tr>';
                        }?>
                        </tbody>
                    </table>
    <?php }?>

    <?php if(empty($ok['validation']['errors'])&&isset($ok['id'])){
        echo '<h3>The offer draft id: '.$ok['id'].' uploaded to Allegro with no errors</h3>';?>
    <?php }?>

</div>