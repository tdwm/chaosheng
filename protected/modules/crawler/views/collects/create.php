<?php
$this->breadcrumbs=array(
    'Collects Webs'=>array('index'),
    'Create',
);
Yii::app()->getClientScript()->registerScript(
    'toSave',
    'var toSave;
     var saveFileContent = function(){
        _data = codeMirrorcontentEditor.getValue();
        $.post("'.$this->createUrl('/crawler/collects/savecrawlerfile',array('sign'=>$model->sign,'file'=>'content')).'",
            {code:_data},
            function(_r){
                if(_r > 0){
                    $.notify("自动保存成功","success");
                } else {
                    $.notify("自动保存失败");
                }
            }
        );
    }
', CClientScript::POS_END
);
?>
<div class="row-fluid">
<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
    'id'=>'collectSetting',
    'type'=>'horizontal',
    'enableAjaxValidation'=>false,
)); ?>
    <div class="span12">
<?php $this->beginWidget('bootstrap.widgets.TbBox', array(
    'title' => $model->isNewRecord ? '添加采集' : '修改采集-'.$model->name,
    'headerIcon' => 'icon-user',
    'headerButtons' => array(
        array(
            'class' => 'bootstrap.widgets.TbButton',
            'buttonType'=>'submit',
            'type'=>'primary',
            'label'=>$model->isNewRecord ? '添加' : '保存',
            'htmlOptions' => array(
                'style' => 'margin-right: 10px;'
            )
        )
    )
)); 
?>
            <p class="help-block">标记 <span class="required">*</span> 必须填写.  不修改密码请留空。</p>
            <?php echo $form->errorSummary($model); ?>
            <ul class="nav nav-tabs" id="myTab">
              <li class="active"><a href="#ruleBase" data-toggle="tab" >基本设置</a></li>
              <li><a href="#ruleUrl" data-toggle="tab" >网址规则</a></li>
              <li><a href="#ruleContent" data-toggle="tab" >内容规则</a></li>
              <li><a href="#ruleFile" data-toggle="tab" >文件内容规则</a></li>
              <li><a href="#ruleSelf" data-toggle="tab" >自定义规则</a></li>
              <li><a href="#ruleAdvance" data-toggle="tab" >高级配置</a></li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="ruleBase">
                <?php include('_ruleBase.php'); ?>
              </div>
              <div class="tab-pane" id="ruleUrl">
                <?php include('_ruleUrl.php'); ?>
              </div>
              <div class="tab-pane" id="ruleContent" style="min-height:600px">
                <?php include('_ruleContent.php'); ?>
              </div>
              <div class="tab-pane" id="ruleFile" style="min-height:100%">
                <?php include('_ruleFileContent.php'); ?>
              </div>
              <div class="tab-pane" id="ruleSelf">
                <?php include('_ruleSelf.php'); ?>
              </div>
              <div class="tab-pane" id="ruleAdvance">
                <?php include('_ruleAdvanced.php'); ?>
              </div>
            </div>
            <?php $this->endWidget(); ?>
    </div>
<?php $this->endWidget(); ?>
</div>
<script>
$(function(){
    $('#collectSetting').submit(function(){
        _sourcetype = $(":radio[name='CollectsNode[sourcetype]']:checked").val();
        //$('#urlpage_'+_sourcetype).attr('name','CollectsNode[urlpage]');       
    });
});
</script>
