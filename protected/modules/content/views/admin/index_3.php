<?php
    $this->widget('bootstrap.widgets.TbExtendedGridView', array(
        'type' => 'striped bordered',
        'dataProvider' => $model->search(),
        'filter' => $model,
        'id'=>'pushTable',
        'ajaxUpdate'=>false,
        'responsiveTable' => true,
        'columns' => array(
            'id'=>array(
                'name'=>'id',
                'htmlOptions'=>array('width'=>'50px'),
            ),
            'title',
            'media'=>array(
                'name'=>'media',
                'filter'=>'',
                'htmlOptions'=>array('width'=>'80px'),
            ),
            'catname'=>array(
                'name'=>'catname',
                'filter'=>'',
                'htmlOptions'=>array('width'=>'80px'),
            ),
            'ifpush'=>array(
                'name'=>'ifpush',
                'value'=>'array_search(Pushed::model()->checkPushed(Yii::app()->user->push_uid,'.$category_id.',$data->id),array_flip(Content::model()->ifpush))',
               // 'filter' => Content::model()->ifpush,
                'filter'=>'',
                'htmlOptions'=>array('class'=>'ifpushtxt','width'=>'40px'),
            ),
            array( 'name'=>'created',
                'filter'=>'',
                'htmlOptions'=>array('width'=>'140px'),
            ),
            array(
                'class'=>'bootstrap.widgets.TbButtonColumn',
                'template' => '{update}',
                //'viewButtonUrl'=>'Yii::app()->createUrl("/show/'.$categoryslug.'/".$data->slug)',
                'updateButtonUrl'=>'Yii::app()->createUrl("/admin/content/push/id/" . $data->id)',
            ),
        ),
    ));

Yii::app()->clientScript->registerScript('ifpushed',"
    $('.ifpushtxt').each(function(k,v){
        _text =  $(this).text();
        if(_text == '是') {
            $(this).parent().find('.button-column').html('');

        }
    });
    "); 
?>
