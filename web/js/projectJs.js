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
        url: '<?php echo Yii::$app->request->/project/create?>',
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