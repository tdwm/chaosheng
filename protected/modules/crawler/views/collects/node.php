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
            'htmlOptions'=>array('width'=>'280px','target'=>'_blank'),
            'template' => '[{link}] [{content}] [{pushcontent}] [{pushprogram}]',
            'buttons'=>array(
                'link'=>array('label'=>'采集网址', 'url'=>'Yii::app()->createUrl("/crawler/collects/colurllist/id/" . $data->nodeid)'),
                'content'=>array('label'=>'采集内容', 'url'=>'Yii::app()->createUrl("/crawler/collects/colcontent/id/" . $data->nodeid)'),
                'pushcontent'=>array('label'=>'发布内容', 'url'=>'Yii::app()->createUrl("/crawler/collects/pushcontent/id/" . $data->nodeid)'),
                'pushprogram'=>array('label'=>'发布方案', 'url'=>'Yii::app()->createUrl("/crawler/collects/pushprogram/id/" . $data->nodeid)'),
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
                    'url'=>'Yii::app()->createUrl("/crawler/collects/testcolurl/id/".$data->nodeid)',
                ),    
                'update'=>array(
                    'label'=>'更新',
                    'url'=>'Yii::app()->createUrl("/crawler/collects/create/id/" . $data->nodeid)',
                ),    
            ),
        ),
    )
));
?>
</div>

