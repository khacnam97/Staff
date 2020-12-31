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
    echo '<div class="form-group col-md-11">';
    echo Html::input(
        'hidden',
        'title',
        '',
        [
            'class' => 'form-control',
            'id' => 'projectId'
        ]
    );
    echo '</div>';
    echo Html::submitButton(
        '<span class="glyphicon glyphicon-plus"></span>',
        [
            'class' => 'btn btn-success',
            'id' => 'btn_add'
        ]
    );

    Modal::end();
?>
