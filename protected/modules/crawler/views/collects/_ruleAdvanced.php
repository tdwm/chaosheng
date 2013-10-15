<?php $this->beginWidget('bootstrap.widgets.TbBox', array(
    'title' => '高级设置',
    'headerIcon' => 'icon-home',
)); 
?>
<?php echo $form->radioButtonListInlineRow($model, 'down_attachment', array(
    '1'=>'下载图片','0'=>'不下载'
)); ?>
<?php echo $form->radioButtonListInlineRow($model, 'watermark', array(
    '0'=>'不打水印','1'=>'打水印'
)); ?>
<?php echo $form->radioButtonListInlineRow($model, 'content_page', array(
    '1'=>'按原文分页','0'=>'不分页'
)); ?>
<?php echo $form->radioButtonListInlineRow($model, 'coll_order', array(
    '1'=>'与目标站点相同','0'=>'与目标站点相反'
)); ?>
<?php $this->endWidget(); ?>
