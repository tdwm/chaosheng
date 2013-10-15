<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<?php echo $form->textFieldRow($model,'nodeid',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'name',array('class'=>'span5','maxlength'=>20)); ?>

	<?php echo $form->textFieldRow($model,'lastdate',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'sourcecharset',array('class'=>'span5','maxlength'=>5)); ?>

	<?php echo $form->textFieldRow($model,'sourcetype',array('class'=>'span5')); ?>

	<?php echo $form->textAreaRow($model,'urlpage',array('rows'=>6, 'cols'=>50, 'class'=>'span8')); ?>

	<?php echo $form->textFieldRow($model,'pagesize_start',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'pagesize_end',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'page_base',array('class'=>'span5','maxlength'=>255)); ?>

	<?php echo $form->textFieldRow($model,'par_num',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'url_contain',array('class'=>'span5','maxlength'=>100)); ?>

	<?php echo $form->textFieldRow($model,'url_except',array('class'=>'span5','maxlength'=>100)); ?>

	<?php echo $form->textFieldRow($model,'url_start',array('class'=>'span5','maxlength'=>100)); ?>

	<?php echo $form->textFieldRow($model,'url_end',array('class'=>'span5','maxlength'=>100)); ?>

	<?php echo $form->textFieldRow($model,'title_rule',array('class'=>'span5','maxlength'=>100)); ?>

	<?php echo $form->textAreaRow($model,'title_html_rule',array('rows'=>6, 'cols'=>50, 'class'=>'span8')); ?>

	<?php echo $form->textFieldRow($model,'keywords_rule',array('class'=>'span5','maxlength'=>100)); ?>

	<?php echo $form->textAreaRow($model,'keywords_html_rule',array('rows'=>6, 'cols'=>50, 'class'=>'span8')); ?>

	<?php echo $form->textFieldRow($model,'author_rule',array('class'=>'span5','maxlength'=>100)); ?>

	<?php echo $form->textAreaRow($model,'author_html_rule',array('rows'=>6, 'cols'=>50, 'class'=>'span8')); ?>

	<?php echo $form->textFieldRow($model,'comeform_rule',array('class'=>'span5','maxlength'=>100)); ?>

	<?php echo $form->textAreaRow($model,'comeform_html_rule',array('rows'=>6, 'cols'=>50, 'class'=>'span8')); ?>

	<?php echo $form->textFieldRow($model,'time_rule',array('class'=>'span5','maxlength'=>100)); ?>

	<?php echo $form->textAreaRow($model,'time_html_rule',array('rows'=>6, 'cols'=>50, 'class'=>'span8')); ?>

	<?php echo $form->textFieldRow($model,'content_rule',array('class'=>'span5','maxlength'=>100)); ?>

	<?php echo $form->textAreaRow($model,'content_html_rule',array('rows'=>6, 'cols'=>50, 'class'=>'span8')); ?>

	<?php echo $form->textFieldRow($model,'content_page_start',array('class'=>'span5','maxlength'=>100)); ?>

	<?php echo $form->textFieldRow($model,'content_page_end',array('class'=>'span5','maxlength'=>100)); ?>

	<?php echo $form->textFieldRow($model,'content_page_rule',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'content_page',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'content_nextpage',array('class'=>'span5','maxlength'=>100)); ?>

	<?php echo $form->textFieldRow($model,'down_attachment',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'watermark',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'coll_order',array('class'=>'span5')); ?>

	<?php echo $form->textAreaRow($model,'customize_config',array('rows'=>6, 'cols'=>50, 'class'=>'span8')); ?>

	<?php echo $form->textFieldRow($model,'frequency',array('class'=>'span5','maxlength'=>20)); ?>

	<?php echo $form->textFieldRow($model,'sign',array('class'=>'span5','maxlength'=>50)); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType' => 'submit',
			'type'=>'primary',
			'label'=>'Search',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
