<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Project */
/* @var $staffs app\models\Project */
/* @var $projectManager */

$this->title = 'Create Project';
$this->params['breadcrumbs'][] = ['label' => 'Projects', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="project-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'staffs' => $staffs,
        'projectManager' => $projectManager
    ]) ?>

</div>
