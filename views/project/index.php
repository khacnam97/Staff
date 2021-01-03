<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;
use app\models\User;
/* @var $this yii\web\View */
/* @var $searchModel app\models\ProjectSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $staff  */
/* @var $model app\models\ProjectStaff */
$this->title = 'Projects';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="project-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php if(Yii::$app->user->identity->role !=3) { ?>
            <?=  Html::a('Create Project', ['create'], ['class' => 'btn btn-success']) ?>
        <?php }?>
    </p>

    <?= $this->render('_modal', [
        'model' => $model,
    ]) ?>
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

                    return '<a  id="modal-btn" title="" class="btn btn-success" ><span class="glyphicon glyphicon-plus"></span></a>'. Html::input(
                            'hidden',
                            'title',
                            $data->id,
                            [
                                'class' => 'form-control',
                                'id' => 'rowId'
                            ]
                        );
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

        ],
    ]); ?>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script>
    $('#modal-btn').on('click', function (event) {
        $a= $("#rowId").val()
        $("#projectId").val($a);
        $('#modal-opened').modal('show');
    });
    $('#btn_add').on('click', function (event) {
        var $arrId = [];
        $.each($("input[name='iduser[]']:checked"), function(){
            $arrId.push($(this).val());
        });
        console.log($arrId);
        $.ajax({
            url: '<?php echo Yii::$app->request->baseUrl. '/project/create' ?>',
            type: 'post',
            data: {
                userId: $arrId,
                projectId :$("#projectId").val() ,
                _csrf : '<?=Yii::$app->request->getCsrfToken()?>'
            },
            success: function (data) {
                $('#modal-opened').modal('hide');
            }
        });
    });


</script>
