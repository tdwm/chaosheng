<?php
$this->widget('bootstrap.widgets.TbBreadcrumbs', array(
    'links'=>array(
        '采集'=>Yii::app()->createUrl('/crawler/collects'),
        '采集管理'=>Yii::app()->createUrl('/crawler/collects/node'),
        '方案管理',
    )
));
?>
<div class="row-fluid">
<div class="span9">
<?php 
$this->widget('bootstrap.widgets.TbExtendedGridView', array(
    'type' => 'striped bordered',
    'dataProvider' => $programdata,
    'filter' => $model,
    'responsiveTable' => true,
    'columns' => array(
        array('name'=>'id','htmlOptions'=>array('width'=>'50px'),'filter'=>false),
        array('name'=>'nodeid','htmlOptions'=>array('width'=>'50px'),'filter'=>false),
        array('name'=>'name','header'=>'名称'),
        array('name'=>'category.name','header'=>'分类'),
        array(
            'class'=>'bootstrap.widgets.TbButtonColumn',
            'header' => '操作',
            'htmlOptions'=>array('width'=>'80px','target'=>'_blank'),
            'template' => '{delete} {update} ',
            'buttons' => array(
                'update'=>array(
                    'label'=>'更新',
                    'url'=>'Yii::app()->createUrl("/crawler/collects/programcreate/pid/".$data->id)',
                ),    
                'delete'=>array(
                    'label'=>'删除',
                    'url'=>'Yii::app()->createUrl("/crawler/collects/programdelete/id/".$data->id)',
                ),    
            ),
        ),
    )
));
?>
</div>
<div class="span3" style="margin-top:15px">
<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
    'id'=>'collectSetting',
 //   'type'=>'horizontal',
    'action' => array('programcreate',"nodeid"=>$nodeid),
    'method'=>'get',
    'enableAjaxValidation'=>false,
)); ?>
        <?php $this->beginWidget('bootstrap.widgets.TbBox', array(
            'title' => '发布内容设置',
            'headerIcon' => 'icon-home',
            'htmlOptions'=>array('style'=>'margin-top:30px'),
            'headerButtons' => array(
                array(
                    'class' => 'bootstrap.widgets.TbButton',
                    'buttonType'=>'submit',
                    'type'=>'primary',
                    'label'=>'添加' ,
                    'htmlOptions' => array(
                        'style' => 'margin-right: 10px;',
                    ),
                )
            )
        )); 
        ?>
        <?php echo $form->textFieldRow($program,'name',array('class'=>'span12')); ?>
        <?php echo $form->dropDownListRow($program,'catid',CHtml::listData(Categories::model()->findAll(array('order'=>'path')), 'id', 'optionName'), array('class'=>'span11','encode'=>false,'prompt'=>'--根目录--')); ?>
    <?php $this->endWidget(); ?>
<?php $this->endWidget(); ?>
</div>
</div>
