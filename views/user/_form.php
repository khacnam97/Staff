<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
    <?php if($model['id'] =='')  {?>
        <?=  $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>
    <?php }?>
    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?=  Html::activeDropDownList($model, 'role',
            array(1 => 'Admin', 2 => 'Project Manager', 3 => 'Staff'), ['class'=>'form-control'])
        ?>
    </div>
    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
