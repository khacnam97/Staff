<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create User', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'username',
            'email:email',
            'role',
            //'description',

            ['class' => 'yii\grid\ActionColumn',
                'buttons'=>[
                    'delete'=>function ($url, $model) {
                        if(Yii::$app->user->identity->id != $model->id){
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['project/delete', 'id' => $model->id], ['class' => 'profile-link',  'title' => Yii::t('app', 'Delete'),'data-confirm' => Yii::t('yii', 'Are you sure you want to delete?'),
                                'data-method' => 'post', 'data-pjax' => '0']);

                        }
                    },
                ],
            ],
        ],
    ]); ?>


</div>
