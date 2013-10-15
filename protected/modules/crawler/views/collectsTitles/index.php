<?php
$this->breadcrumbs=array(
	'Collects Webs',
);
?>
<div class="row-fluid">
    <div class="span12" style="margin-top: 5px;">
        <div>
        <?php $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType' => 'link',
            'type'=>'primary',
            'label' => '添加',
            'url' => array('webset/create'),
            'htmlOptions' => array(
                'class' => 'pull-right'
            ),
        )); ?> 
        </div>
        <div class="clearfix"></div>
        <?php 
            $this->widget('bootstrap.widgets.TbExtendedGridView', array(
                'type' => 'striped bordered',
                'dataProvider' => $model->search(),
                'filter' => $model,
                'responsiveTable' => true,
                'columns' => array(
                    array('name'=>'id','htmlOptions'=>array('width'=>'50px')),
                    array('name'=>'title'),
                    array('name'=>'media'),
                    array('name'=>'catname'),
                    array('name'=>'created'),
                    array(
                        'class'=>'CLinkColumn',
                        'header'=>'原文',
                        'label'=>'查看',
                        'urlExpression'=>'$data->collect_url',
                        'linkHtmlOptions'=>array('target'=>'_blank')
                    ),
                    array('name'=>'slug','header'=>'标识'),
                    /*
                    array(
                        'class'=>'bootstrap.widgets.TbToggleColumn',
                        'toggleAction'=>'/crawler/webset/toggle',
                        'name' => 'status',
                        'header' => '状态',
                    ),
                     */
                    array(
                        'class'=>'bootstrap.widgets.TbButtonColumn',
                        'header' => '操作',
                        'template' => '{view}{update}{delete}',
                        'viewButtonUrl' => '$data->listurl',
                        'viewButtonOptions' => array('target'=>'_blank'),
                        'updateButtonUrl' => 'Yii::app()->createUrl("/crawler/webset/create/id/" . $data->id)',
                        'deleteButtonUrl'=>'Yii::app()->createUrl("/crawler/webset/delete/id/" . $data->id)',
                    ),
                )
            ));


        ?>
</div>

