<?php

use yii\helpers\Html;
use yii\grid\GridView;
/* @var $this yii\web\View
 * @var $dataProvider
 * @var $ok
 */
?>
    <h1>Offers</h1>

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
            ],
            [
                'attribute' => 'price',
            ],
            [
                'attribute' => 'format',
            ],
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function ($data) {
                        if($data->status != 'ACTIVE') {
                            return $data->status.'  '.Html::a('Activate?', ['activate', 'id' => $data->offer_id], ['class'=>'btn btn-info btn-sm']);
                        }
                        else{
                            return $data->status;
                        }
                },

            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{delete}',
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
        echo '<h3>The offer id: '.$_GET['id'].' is activated</h3>';?>
    <?php }?>


<pre><?php print_r($ok);?></pre>


