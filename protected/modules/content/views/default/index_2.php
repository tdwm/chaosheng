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
                    $.post("content/deleteMany", values, function(data) {
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
            'id'=>array(
                'name'=>'id',
                'value'=>'$data->id [$data->col_id]',
                'htmlOptions'=>array('width'=>'50px'),
            ),
            'col_title',
            'media'=>array(
                'name'=>'col_media',
                'filter'=>'',
                'htmlOptions'=>array('width'=>'80px'),
            ),
            'catname'=>array(
                'name'=>'col_category',
                'filter'=>'',
                'htmlOptions'=>array('width'=>'80px'),
            ),
            'time'=>array(
                'name'=>'col_time',
                'filter'=>'',
                'htmlOptions'=>array('width'=>'80px'),
            ),

            array(
                'class'=>'bootstrap.widgets.TbButtonColumn',
                'viewButtonUrl'=>'Yii::app()->createUrl($data->slug)',
                'updateButtonUrl'=>'Yii::app()->createUrl("/admin/content/save/id/" . $data->id)',
                'deleteButtonUrl'=>'Yii::app()->createUrl("/admin/content/delete/id/" . $data->id)',
            ),
        ),
));
?>
