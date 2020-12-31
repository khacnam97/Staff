<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\SignupForm */

$this->title = 'Forget';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'form-forget']); ?>
            <?= $form->field($model, 'email_forget') ?>
            <div class="form-group">
                <?= Html::submitButton('Send mail', ['class' => 'btn btn-primary', 'name' => 'signup-button', 'id' => 'btnsign']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
