<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;


/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Product */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="product-form">

<?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-sm-6">
            <div class="card" style = "padding: 10px; margin: 10px; border: 1px solid #bbbbbb;">
                <div class="card-body">
                    <h5 class="card-title">Product</h5>
                    <?= $form->field($model, 'name')->textInput()->label('Name')?>
                    <?= $form->field($model, 'category[id]')->textInput()->label('Categoty')?>
                </div>
            </div>
            <div class="card" style = "padding: 10px; margin: 10px; border: 1px solid #bbbbbb;">
                <div class="card-body">
                    <h5 class="card-title">Description</h5>
                    <?= $form->field($model, 'description[sections][0][items][0][type]')->dropDownList([
                                    'TEXT' => 'TEXT',
                                    'IMAGE' => 'IMAGE',
                                    ])->label('Type')?>
                    <?= $form->field($model, 'description[sections][0][items][0][content]')->textarea(['style' => 'vertical-align: top; height:10rem;', 'placeholder' => '<p>Your descrition with HTML tags</p>'])->label('Content')?>
                </div>
            </div>
            <div class="card" style = "padding: 10px; margin: 10px; border: 1px solid #bbbbbb;">
                <div class="card-body">
                    <h5 class="card-title">Location</h5>
                    <?= $form->field($model, 'location[countryCode]')->textInput()->label('Country Code')?>
                    <?= $form->field($model, 'location[postCode]')->textInput()->label('Post Code')?>
                    <?= $form->field($model, 'location[province]')->textInput()->label('Province')?>
                    <?= $form->field($model, 'location[city]')->textInput()->label('City')?>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="card" style = "padding: 10px; margin: 10px; border: 1px solid #bbbbbb;">
                <div class="card-body">
                    <h5 class="card-title">Price and Payments</h5>
                    <?= $form->field($model, 'sellingMode[price][amount]')->textInput()->label('Price')?>
                    <?= $form->field($model, 'sellingMode[price][currency]')->textInput()->label('Currency')?>
                    <?= $form->field($model, 'sellingMode[format]')->dropDownList([
                                    'BUY_NOW' => 'BUY_NOW',
                                    'AUCTION' => 'AUCTION',
                                    'ADVERTISEMENT' => 'ADVERTISEMENT',
                                    ])->label('Format')?>
                    <?= $form->field($model, 'payments[invoice]')->dropDownList([
                                    'NO_INVOICE' => 'NO_INVOICE',
                                    'VAT' => 'VAT',
                                    'VAT_MARGIN' => 'VAT_MARGIN',
                                    'WITHOUT_VAT' => 'WITHOUT_VAT',
                                    ])->label('Payment invoice')?>
                </div>
            </div>
            <div class="card" style = "padding: 10px; margin: 10px; border: 1px solid #bbbbbb;">
                <div class="card-body">
                    <h5 class="card-title">Stock</h5>
                    <?= $form->field($model, 'stock[available]')->textInput()->label('Quantity')?>
                    <?= $form->field($model, 'stock[unit]')->textInput()->label('Units')?>
                </div>
            </div>
            <div class="card" style = "padding: 10px; margin: 10px; border: 1px solid #bbbbbb;">
                <div class="card-body">
                    <h5 class="card-title">Delivery</h5>
                    <?= $form->field($model, 'delivery[additionalInfo]')->textInput()->label('Additional Info')?>
                    <?= $form->field($model, 'delivery[handlingTime]')->dropDownList([
                                    'PT0S' => 'Immediately', 'PT24H' => '24 hours', 'P2D' => '2 days',
                                    'P3D' => '3 days', 'P4D' => '4 days', 'P5D' => '5 days',
                                    'P7D' => '7 days', 'P10D' => '10 days', 'P14D' => '14 days',
                                    'P21D' => '21 days', 'P30D' => '30 days', 'P60D' => '60 days',
                                    ])->label('Handling Time')?>
                    <?= $form->field($model, 'delivery[shippingRates][id]')->textInput()->label('Shipping Rates ID')?>                                                   
                </div>
            </div>    
        </div>
    </div>
    <div class="row">
            <?php foreach ($params as $key => $value) {   
                echo '<div class="col-md-4">
                    <div class="card" style = "padding: 10px; margin: 10px; border: 1px solid #bbbbbb;">
                        <div class="card-body">
                            <h5 class="card-title">Parameter <strong>"'.$value['name'].'"</strong>   '.($value['required']?"<span style=\"color:red;\">(reqired)</span>":"").'</h5>';?>
                            <?=$form->field($model, 'parameters['.$key.'][id]')->textInput(['value' => $value['id']])->label('Parameter ID')?>
                            <?php if($value['type'] == 'dictionary') {
                                        $arr_dic = array();
                                        foreach ($value['dictionary'] as $value_d) {
                                                $arr_dic = $arr_dic + array($value_d['id'] => $value_d['value']);
                                            }?>
                                        <?=$form->field($model, 'parameters['.$key.'][valuesIds][0]')->dropDownList($arr_dic)->label('Parameter Value')?>
                            <?php } 
                            else { ?>
                                <?=$form->field($model, 'parameters['.$key.'][values][0]')->textInput()->label('Parameter Value')?>
                            <?php }
                    echo '</div>
                    </div>
                </div>';
            }?>
    </div>    
    </div>
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>