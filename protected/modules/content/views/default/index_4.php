<?php
    $this->widget('bootstrap.widgets.TbExtendedGridView', array(
        'type' => 'striped bordered',
        'dataProvider' => $titles,
      //  'filter' => $model,
       // 'responsiveTable' => true,
        'columns' => array(
            'col_id'=>array(
                'name'=>'col_id',
                'value'=>'$data->col_id',
                'htmlOptions'=>array('width'=>'50px'),
            ),
            'col_title',
            'media'=>array(
                'name'=>'col_media',
                'header'=>'媒体',
            ),
            'catname'=>array(
                'name'=>'col_category',
                'filter'=>'',
            ),
            'time'=>array(
                'name'=>'col_time',
                'filter'=>'',
                'header'=>'发布时间',
            ),
            'ifpush'=>array(
                'name'=>'ifpush',
                'value'=>'CollectsPush::model()->checkPushed($data->col_id,'.$site_id.')',
               // 'filter' => Content::model()->ifpush,
                'filter'=>'',
                'header'=>'推送',
            ),
            array(
                'class'=>'bootstrap.widgets.TbButtonColumn',
                'template'=>'{update} {view}',
                'buttons'=>array(
                    'view'=>array(
                        'url'=>'Yii::app()->createUrl("/content/admin/show/" ,array("col_id"=>$data->col_id) )',
                        'options'=>array(
                            "data-toggle"=>"modal",
                            "data-target"=>"#myModal",
                        ),
                    ),
                ),
                'updateButtonUrl'=>'Yii::app()->createUrl("/content/default/push/" ,array("col_id"=>$data->col_id))',
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
