<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Modal;
use app\models\User;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\ProjectSearch */
/* @var $form yii\widgets\ActiveForm */
/* @var $searchModel yii\widgets\ProjectSearch */
?>

<?php
    Modal::begin([
        'header' => '<h2>Add Staff</h2>',
        'toggleButton' => false,
        'id' => 'modal-opened',
        'size' => 'modal-lg'
    ]);


    echo Html::activeCheckboxList($model, 'userId',
        ArrayHelper::map(User::find()->where(['role' => 3])->all(), 'id', 'username'),
        array('prompt'=>'---Select---','class'=>'form-control','id' => 'userId','name' =>'iduser'));
    echo Html::submitButton(
        'ADD',
        [
            'class' => 'btn btn-success',
            'id' => 'btn_add'
        ]
    );

    Modal::end();
?>
