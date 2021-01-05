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
    <?php \yii\widgets\Pjax::begin(['id' => 'table-project']); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'id' => 'table-project',
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
                'visible' => Yii::$app->user->can('manager'),
                'value' => function ($data) {
                    if (Yii::$app->user->identity->role != 3){
                        // return '<a   title="" class="btn btn-success modal-btn" ><span class="glyphicon glyphicon-plus"></span></a>';
                        return Html::a('<span class="glyphicon glyphicon-plus"></span>','#', [
                            'id' => 'activity-view-link',
                            'class' => 'btn btn-success modal-btn',
                            'data-toggle' => 'modal',
                            'data-target' => '#activity-modal',
                            'pjax-container' => 'table-project',
                            'data-pjax' => '0',
     
                        ]);
                    }

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
    <?php \yii\widgets\Pjax::end(); ?>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script>
    var projectId ;
        $("#table-project").on('click', ".modal-btn", function(e){
            projectId =$(this).parents('tr').data('key');
            $('#modal-opened').modal('show');
        });
   
    $('#btn_add').on('click', function (event) {
        var $arrId = [];
        $.each($("input[name='iduser[]']:checked"), function(){
            $arrId.push($(this).val());
        });
        $.ajax({
            url: '<?php echo Yii::$app->request->baseUrl. '/project/create' ?>',
            type: 'post',
            data: {
                userId: $arrId,
                projectId :projectId ,
                _csrf : '<?=Yii::$app->request->getCsrfToken()?>'
            },
            success: function (data) {
                $('#modal-opened').modal('hide');
                $.pjax.reload({container:'#table-project', timeout: false});
            }
        });
    });

</script>
