<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\SignupForm */

$this->title = 'Signup';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">
    <h1><?= Html::encode($this->title)  ?></h1>

    <p>Please fill out the following fields to signup:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
            <?= $form->field($model, 'username') ?>
            <?= $form->field($model, 'email') ?>
            <?= $form->field($model, 'password')->passwordInput() ?>
            <div class="form-group">
                <label class="control-label">Role</label>
                <?=  Html::activeDropDownList($model, 'role',
                    ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'name'), array('class'=>'form-control'))
                ?>
            </div>
            <div class="form-group">
                <?= Html::submitButton('Signup', ['class' => 'btn btn-primary dddd', 'name' => 'signup-button', 'id' => 'btnsign']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script>
    // $('#btnsign').on('click', function (event) {
    //     $('input[name="nannnnn"]:checked').each(function () {
    //         console.log(this.value);
    //     });
    // });

</script>