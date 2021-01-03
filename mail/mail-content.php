<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\SignupForm */
/* @var $token */

?>
<div class="site-signup">
    <div class="row">
        <div class="col-lg-5">
            <div> <p> Please use this link to reset your password : <?= Url::to(["/site/reset",'token' => $token], TRUE); ?> </p>
        </div>
    </div>
</div>