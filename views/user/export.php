<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;
/* @var $this yii\web\View */
/* @var $model app\models\User */


?>
<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>


    <?= $form->field($model, 'sokuhouId')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'yearMonth')->widget(\yii\jui\DatePicker::classname(), [
        'dateFormat' => 'yyyy-MM',
        'options' => ['class' => 'form-control']
    ]) ?>
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>