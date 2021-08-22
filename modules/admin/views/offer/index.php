<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View
 * @var $dataProvider
 * @var $ok
 * @var $hidden = 'none'
 * @var $model
 * @var $edit
 */
?>
    <h1>Offers</h1>

    <?php if(!empty($model)) {
    echo '<div style = "display:'.($hidden == "block" ? $hidden : "none").'; position:absolute; z-index: 10; background:white; left:25%; top:25%;">';
        echo '<div class="card" style = "padding: 10px; margin: 10px; border: 1px solid #bbbbbb;">
            <div class="card-body">
                <h4 class="card-title">Edit '.($edit=='quant'?"Quantity":"Price").'</h4>';
                $form = ActiveForm::begin(['action' => 'offer-edit?id='.$model->offer_id.'&edit='.$edit]);?>
                    <?= $form->field($model, $edit=='quant'?'available':'price')->textInput()->label($edit=='quant'?"Quantity":"Price")?>
                    <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
                    <?= Html::submitButton('Cancel', ['class' => 'btn btn-info', 'name' => 'cancel', 'value' => 'ok','formaction' => 'offer-edit?id='.$model->offer_id.'&edit='.$edit,]); ?>
                <?php ActiveForm::end();
        echo    '</div>
        </div>
    </div>';
    }?>

    <?=Html::a('Get offers', ['get-offers'], ['class' => 'btn btn-success'])?>
    <p></p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'class' => 'yii\grid\CheckboxColumn',
            ],
            [
                'class' => 'yii\grid\SerialColumn',
            ],
            [
                'attribute' => 'offer_id',
            ],
            [
                'attribute' => 'name',
            ],
            [
                'attribute' => 'category_id',
            ],
            [
                'attribute' => 'category',
            ],
            [
                'attribute' => 'available',
                'format' => 'raw',
                'value' => function ($data) {
                    return Html::a($data->available, ['offer-edit', 'id' => $data->offer_id, 'edit' => 'quant'] );
                }
            ],
            [
                'attribute' => 'price',
                'format' => 'raw',
                'value' => function ($data) {
                    return Html::a($data->price, ['offer-edit', 'id' => $data->offer_id, 'edit' => 'price'] );
                }
            ],
            [
                'attribute' => 'format',
            ],
            [
                'attribute' => 'status',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
                'buttons' => [
                        'update' => function ($url, $model) {
                            if($model->status == 'ACTIVE'){
                                return Html::a(' END', ['publicate', 'id' => $model->offer_id, 'stat' => 'ENDED'], ['class' => 'glyphicon glyphicon-stop btn btn-primary btn-xs']);
                                }
                            if($model->status == 'INACTIVE'||$model->status == 'ENDED'){
                                return Html::a(' ACTIVATE', ['publicate', 'id' => $model->offer_id, 'stat' => 'ACTIVE'], ['class' => 'glyphicon glyphicon-ok btn btn-success btn-xs']);
                                }
                        },
                        'delete' => function ($url, $model) {
                            return $model->status == 'INACTIVE' ? Html::a(' REMOVE', ['delete', 'id' => $model->offer_id], ['class' => 'glyphicon glyphicon-trash btn btn-danger btn-xs']) : '';
                        },
                ]
            ],
        ],
    ]);

  if(!empty($ok['errors'])){?>
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
                    <?php foreach ($ok['errors'] as $key => $value) {
                        echo '<tr>
                        <th scope="row">'.($key+1).'</th>
                        <td>'.$value['code'].'</td>
                        <td>'.$value['userMessage'].'</td>       
                        </tr>';
                    }?>
                    </tbody>
                </table>
        <?php }?>
        <?php if(!empty($ok['warnings'])){?>
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
                        <?php foreach ($ok['warnings'] as $key => $value) {
                            echo '<tr>
                        <th scope="row">'.($key+1).'</th>
                        <td>'.$value['code'].'</td>
                        <td>'.$value['userMessage'].'</td>       
                        </tr>';
                        }?>
                        </tbody>
                    </table>
    <?php }?>

    <?php if(empty($ok['errors'])&&isset($ok['id'])){
        echo '<h3>The offer id: '.$_GET['id'].' status changed!</h3>';?>
    <?php }?>

<p><?php //print_r($ok);?></p>


