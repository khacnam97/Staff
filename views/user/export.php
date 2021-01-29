<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
/* @var $this yii\web\View */
/* @var $model app\models\User */



?>
<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>


    <?= $form->field($model, 'sokuhouId')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'yearMonth')->widget(DatePicker::className(), [
                        'options' => ['placeholder' => 'Select month year ...', 'autocomplete' => 'off'],
                        'pluginOptions' => [
                            'format' => 'yyyy-mm',
                            'todayHighlight' => true,
                            'minViewMode'=>'months',
                        ]
    ])->label('Month year');?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>