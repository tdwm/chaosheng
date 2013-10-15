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
    'url' => array('create'),
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
        array('name'=>'nodeid','htmlOptions'=>array('width'=>'50px')),
        array('name'=>'name','header'=>'名称'),
        array('name'=>'lastdate','header'=>'最近采集'),
        array('name'=>'sign','header'=>'标识'),
        array('name'=>'frequency','header'=>'采集频率'),
        array(
            'class'=>'bootstrap.widgets.TbButtonColumn',
            'header' => '内容操作',
            'htmlOptions'=>array('width'=>'200px','target'=>'_blank'),
            'template' => '[{link}][{content}][{push}]',
            'buttons'=>array(
                'link'=>array('label'=>'采集网址', 'url'=>'Yii::app()->createUrl("/crawler/collectsurl/id/" . $data->nodeid)'),
                'content'=>array('label'=>'采集内容', 'url'=>'Yii::app()->createUrl("/crawler/content/?web_id=" . $data->nodeid)'),
                'push'=>array('label'=>'发布内容', 'url'=>'aaa'),
            ),
        ),
        array(
            'class'=>'bootstrap.widgets.TbButtonColumn',
            'header' => '操作',
            'htmlOptions'=>array('width'=>'80px','target'=>'_blank'),
            'template' => '{view} {update} {copy} {export}',
            'buttons' => array(
                'copy'=>array(
                    'label'=>'复制',
                    'icon'=>'icon-edit',
                    'url'=>'sss',
                ),    
                'export'=>array(
                    'label'=>'导出',
                    'icon'=>'icon-share-alt',
                    'url'=>'sss',
                ),    
                'view'=>array(
                    'label'=>'测试',
                    'url'=>'sss',
                ),    
                'update'=>array(
                    'label'=>'更新',
                    'url'=>'Yii::app()->createUrl("/crawler/collectsnode/create/id/" . $data->nodeid)',
                ),    
            ),
        ),
    )
));
?>
</div>
