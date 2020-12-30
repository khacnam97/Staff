<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProjectSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $staff  */
$this->title = 'Projects';
$this->params['breadcrumbs'][] = $this->title;
//var_dump($staff);
?>
<div class="project-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php if(Yii::$app->user->identity->role !=3) { ?>
            <?=  Html::a('Create Project', ['create'], ['class' => 'btn btn-success']) ?>
        <?php }?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'username',
            'description',
            'createDate',
            'updateDate',

            [
                'format' => 'raw',
//                'format' => 'html',
                'class' => 'yii\grid\DataColumn',
                'attribute'=>'Staff',
                'value' => function ($data) use ($staff){
                    foreach ($staff as $staffs) {

                       if($staffs['projectId'] == $data->id){
                           return $staffs['nameStaff'];
                       }
                    }
                    return '<a href="" title="" ></a>';
                },
            ],
            [
                'format' => 'raw',
//                'format' => 'html',
                'class' => 'yii\grid\DataColumn',
                'attribute'=>'Add Staff',
                'value' => function ($data) {
                    return '<a href="" title="" >Add staff</a>';
                },
            ],
            ['class' => 'yii\grid\ActionColumn',
                'buttons'=>[
                    'update'=>function ($url, $model) {
                        if(Yii::$app->user->identity->role != 3){
                            return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['project/update', 'id' => $model->id], ['class' => 'profile-link']);

                        }
                    },
                    'delete'=>function ($url, $model) {
                        if(Yii::$app->user->identity->role != 3){
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['project/delete', 'id' => $model->id], ['class' => 'profile-link',  'title' => Yii::t('app', 'Delete'),'data-confirm' => Yii::t('yii', 'Are you sure you want to delete?'),
                                'data-method' => 'post', 'data-pjax' => '0']);

                        }
                    },

                ],
            ],

//            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
