<?php
    $this->widget('bootstrap.widgets.TbExtendedGridView', array(
        'type' => 'striped bordered',
        'dataProvider' => $model->search(),
        'filter' => $model,
        'responsiveTable' => true,
        'bulkActions' => array(
        'actionButtons' => array(
            array(
                'buttonType' => 'button',
                'type' => 'danger',
                'id' => 'delAll',
                'size' => 'small',
                'label' => 'Delete Selected',
                'click' => 'js:function(values) {
                    $.post("admin/deleteMany", values, function(data) {
                        values.each(function() {
                            $(this).parent().parent().remove();
                        });
                    });
                    }'
                )
            ),
            'checkBoxColumnConfig' => array(
                'name' => 'id'
            ),
        ),
        'columns' => array(
            'col_id'=>array(
                'header'=>'col_id',
                'name'=>'col_id',
                'value'=>'$data->col_id',
                'htmlOptions'=>array('width'=>'60px'),
            ),
            'col_title',
            'cid'=>array(
                'name'=>'cid',
                "filter"=>CHtml::listData(Categories::model()->findAll(array("order"=>"path")), "id", "name",'parent_id'),
            ),
            'media'=>array(
                'name'=>'col_media',
                'filter'=>'',
            ),
            'catname'=>array(
                'name'=>'col_category',
                'filter'=>'',
            ),
            
            'time'=>array(
                'name'=>'col_time',
                'filter'=>'',
            ),
            array(
                'class'=>'bootstrap.widgets.TbButtonColumn',
                'buttons'=>array(
                    'view'=>array(
                        'url'=>'Yii::app()->createUrl("/content/admin/show/" ,array("col_id"=>$data->col_id) )',
                        'options'=>array(
                            "data-toggle"=>"modal",
                            "data-target"=>"#myModal",
                        ),
                    ),
                ),
                'updateButtonUrl'=>'Yii::app()->createUrl("/content/admin/update/" ,array("col_id"=>$data->col_id) )',
                'deleteButtonUrl'=>'Yii::app()->createUrl("/content/admin/delete/" ,array("col_id"=>$data->col_id) )',
            ),
        ),
));
?>
<script>
$(document).on('click','a[data-toggle=modal]', function() {
    event.preventDefault();
    var $modal=$($(this).data('target'));
    $('.modal-body',$modal).empty();
    $modal.show();
    $('.modal-body',$modal).load($(this).attr('href'));
});
</script>
<?php $this->beginWidget('bootstrap.widgets.TbModal', array('id' => 'myModal')); ?>
    <div class="modal-header">
        <a class="close" data-dismiss="modal">&times;</a>
        <h4>内容</h4>
    </div>
    <div class="modal-body"> </div>
<?php $this->endWidget(); ?>
