<?php
$this->breadcrumbs=array(
    'Collects Webs'=>array('index'),
    'Create',
);
?>
<div class="row-fluid">
<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
    'id'=>'collectSetting',
    'type'=>'horizontal',
    'action'=>array('programsave',"nodeid"=>$nodeid),
    'enableAjaxValidation'=>false,
)); ?>
 <div class="span12">

    <?php $this->beginWidget('bootstrap.widgets.TbBox', array(
        'title' => '发布内容设置',
        'id' => 'selfset',
        'headerIcon' => 'icon-home',
        'headerButtons' => array(
            array(
                'class' => 'bootstrap.widgets.TbButton',
                'buttonType'=>'submit',
                'type'=>'primary',
                'label'=>'提交' ,
                'htmlOptions' => array(
                    'style' => 'margin-right: 10px;',
                ),
            )
        )
    )); 
    ?>

        <?php echo $form->hiddenField($program,'id');?>
        <div class="row-fluid ">
            <div class="span6">
                <?php //echo $form->textFieldRow($program,'name',array('class'=>'span12')); ?>
                <?php echo $form->textFieldRow($program,'name'); ?>
            </div>
            <div class="span6">
                <label class="control-label" >分类：</label>
                 <div class="controls">
                <?php echo $form->hiddenField($program,'catid'); echo $category['name']; ?>
                 </div>
            </div>
        </div>
        <div class="row-fluid ">
            <div class="span6">
                <div class="control-group">
                    <label class="control-label" for="config[add_introduce]">自动提取摘要：</label>
                    <div class="controls">
                    <input type="checkbox" name="config[add_introduce]" value="1" <?php if($config['add_introduce']) echo "checked";?>> 
                          是否截取
                          <input type="text" placeholder="" name="config[introcude_length]" class="span3" value="<?php echo $config['introcude_length'];?>">
                        内容字符至内容摘要
                    </div>
                </div>
            </div>
        </div>
        <div class="row-fluid ">
            <div class="span2"> 字段 </div>
            <div class="span2"> 说明 </div>
            <div class="span4"> 标签字段（采集填充结果）</div>
            <div class="span4"> 处理函数 </div>
        </div>
        <?php foreach($attributes as $k => $v) { ?>
        <div class="row-fluid" style="margin-top:5px;padding:5px 0px;border-bottom:1px solid #ccc;">
            <div class="span2"> <?php echo $k; ?> </div>
            <div class="span2"> <?php echo $v; ?> </div>
            <div class="span4">
            <?php
                //采集字段
                $selected = "";
                if(isset($config['map'][$k])){
                    $selected = $config['map'][$k];
                }
                echo  CHtml::dropDownList("selconfig[$k]",$selected,$node_field,array('prompt'=>'--请选择--'));
            ?> 
            </div>
            <div class="span4">
            <?php 
                //处理函数
                $selected = "";
                if(isset($config['funs'][$k])){
                    $selected = $config['funs'][$k];
                }
                echo  CHtml::dropDownList("selfuns[$k]",$selected,$spider_funs,array('prompt'=>'--请选择--'));
            ?>
            </div>
        </div>
        <?php }//foreach attributes?>
      <?php $this->endWidget(); ?>
    </div>
  <?php $this->endWidget(); ?>
</div>
<div class="accordion" id="accordion2"> </div>
<script>
$(function(){

});
</script>
