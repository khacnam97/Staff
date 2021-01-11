<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $model app\models\Project */
/* @var $form yii\widgets\ActiveForm */
/* @var $staffs */
/* @var $projectManager */
/* @var $staffId */
?>

<div class="project-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

    <?php if(Yii::$app->user->can('manager')) { ?>
        <div class="form-group">
            <label class="control-label">Staff</label>
            <?=  Html::activeCheckboxList($model, 'staff',
                ArrayHelper::map($staffs, 'id', 'username'), array('class'=>'form-control'))
            ?>
        </div>
    <?php } if(Yii::$app->user->can('admin')){ ?>
    <div class="form-group">
        <label class="control-label">Project Manager</label>
            <?=  Html::activeDropDownList($model, 'project_manager',
                ArrayHelper::map($projectManager, 'id', 'username'), array('class'=>'form-control') )
            ?>
    </div>
    <?php }?>


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
