<?php $this->beginWidget('bootstrap.widgets.TbBox', array(
    'title' => '基本设置',
    'headerIcon' => 'icon-home',
)); 
?>
    <?php echo $form->textFieldRow($model,'name',array('class'=>'span5','maxlength'=>20)); ?>
    <?php echo $form->textFieldRow($model,'frequency',array('class'=>'span5','maxlength'=>20)); ?>
    <?php echo $form->textFieldRow($model,'sign',array('class'=>'span5','maxlength'=>50)); ?>
    <?php echo $form->radioButtonListInlineRow($model, 'sourcecharset', array(
        'gbk'=>'GBK','utf-8'=>'UTF-8','big5'=>'BIG5'
    )); ?>
    <?php echo $form->radioButtonListInlineRow($model, 'urlcharset', array(
        'gbk'=>'GBK','utf-8'=>'UTF-8','big5'=>'BIG5'
    )); ?>
    <?php
       echo $form->toggleButtonRow($model,'urlcrawlbyfile'); 
       echo $form->toggleButtonRow($model,'contentcrawlbyfile'); 
    ?>
<?php $this->endWidget(); ?>
