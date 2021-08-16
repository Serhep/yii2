<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Category */

$this->title = 'Параметры категории id:'.$id;
?>
<div class="category-params">

    <h1><?= Html::encode($this->title) ?></h1>
    <p>
    <?= Html::a('Категории', ['./category'], ['class' => 'btn btn-success']) ?>
    </p>
    <pre><?php print_r($ok);?></pre>

</div>