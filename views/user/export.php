<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;
/* @var $this yii\web\View */
/* @var $model app\models\User */


?>
<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>


    <?= $form->field($model, 'sokuhou_id')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'trn_date')->widget(\yii\jui\DatePicker::classname(), [
        //'language' => 'ru',
        'dateFormat' => 'yyyy-MM',
    ]) ?>
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>