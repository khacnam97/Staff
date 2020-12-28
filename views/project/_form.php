<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $model app\models\Project */
/* @var $form yii\widgets\ActiveForm */
/* @var $staff */
?>

<div class="project-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

    <?=  Html::activeCheckboxList($model, 'staff',
        ArrayHelper::map($staff, 'id', 'username'), array('class'=>'form-control'))
    ?>

    <?= $form->field($model, 'createDate')->widget(

        DateTimePicker::className(), [

        'options' => ['placeholder' => 'Select rendering time ...'],

        'convertFormat' => true,

        'pluginOptions' => [

            'format' => 'yyyy/dd/mm hh:i:ss',

            'startDate' => '01-Mar-2014 12:00 AM',

            'todayHighlight' => true

        ]
    ]); ?>
    <?= $form->field($model, 'updateDate')->widget(

        DateTimePicker::className(), [

        'options' => ['placeholder' => 'Select rendering time ...'],

        'convertFormat' => true,

        'pluginOptions' => [

            'format' => 'yyyy/dd/mm hh:i:ss',

            'startDate' => '01-Jul-2017 12:00 AM',

            'todayHighlight' => true

        ]
    ]); ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script>
    $('.checkbox').prop("checked", true);
</script>
