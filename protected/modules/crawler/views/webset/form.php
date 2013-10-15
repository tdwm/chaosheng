<?php
/* @var $this CollectsNodeController */
/* @var $model CollectsNode */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'collects-node-form-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name'); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'lastdate'); ?>
		<?php echo $form->textField($model,'lastdate'); ?>
		<?php echo $form->error($model,'lastdate'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'urlpage'); ?>
		<?php echo $form->textField($model,'urlpage'); ?>
		<?php echo $form->error($model,'urlpage'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'page_base'); ?>
		<?php echo $form->textField($model,'page_base'); ?>
		<?php echo $form->error($model,'page_base'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'url_contain'); ?>
		<?php echo $form->textField($model,'url_contain'); ?>
		<?php echo $form->error($model,'url_contain'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'url_except'); ?>
		<?php echo $form->textField($model,'url_except'); ?>
		<?php echo $form->error($model,'url_except'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'title_rule'); ?>
		<?php echo $form->textField($model,'title_rule'); ?>
		<?php echo $form->error($model,'title_rule'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'title_html_rule'); ?>
		<?php echo $form->textField($model,'title_html_rule'); ?>
		<?php echo $form->error($model,'title_html_rule'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'keywords_rule'); ?>
		<?php echo $form->textField($model,'keywords_rule'); ?>
		<?php echo $form->error($model,'keywords_rule'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'keywords_html_rule'); ?>
		<?php echo $form->textField($model,'keywords_html_rule'); ?>
		<?php echo $form->error($model,'keywords_html_rule'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'author_rule'); ?>
		<?php echo $form->textField($model,'author_rule'); ?>
		<?php echo $form->error($model,'author_rule'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'author_html_rule'); ?>
		<?php echo $form->textField($model,'author_html_rule'); ?>
		<?php echo $form->error($model,'author_html_rule'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'comeform_rule'); ?>
		<?php echo $form->textField($model,'comeform_rule'); ?>
		<?php echo $form->error($model,'comeform_rule'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'comeform_html_rule'); ?>
		<?php echo $form->textField($model,'comeform_html_rule'); ?>
		<?php echo $form->error($model,'comeform_html_rule'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'time_rule'); ?>
		<?php echo $form->textField($model,'time_rule'); ?>
		<?php echo $form->error($model,'time_rule'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'time_html_rule'); ?>
		<?php echo $form->textField($model,'time_html_rule'); ?>
		<?php echo $form->error($model,'time_html_rule'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'content_rule'); ?>
		<?php echo $form->textField($model,'content_rule'); ?>
		<?php echo $form->error($model,'content_rule'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'content_html_rule'); ?>
		<?php echo $form->textField($model,'content_html_rule'); ?>
		<?php echo $form->error($model,'content_html_rule'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'content_page_start'); ?>
		<?php echo $form->textField($model,'content_page_start'); ?>
		<?php echo $form->error($model,'content_page_start'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'content_page_end'); ?>
		<?php echo $form->textField($model,'content_page_end'); ?>
		<?php echo $form->error($model,'content_page_end'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'content_nextpage'); ?>
		<?php echo $form->textField($model,'content_nextpage'); ?>
		<?php echo $form->error($model,'content_nextpage'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'customize_config'); ?>
		<?php echo $form->textField($model,'customize_config'); ?>
		<?php echo $form->error($model,'customize_config'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'frequency'); ?>
		<?php echo $form->textField($model,'frequency'); ?>
		<?php echo $form->error($model,'frequency'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'sign'); ?>
		<?php echo $form->textField($model,'sign'); ?>
		<?php echo $form->error($model,'sign'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'sourcetype'); ?>
		<?php echo $form->textField($model,'sourcetype'); ?>
		<?php echo $form->error($model,'sourcetype'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'pagesize_start'); ?>
		<?php echo $form->textField($model,'pagesize_start'); ?>
		<?php echo $form->error($model,'pagesize_start'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'pagesize_end'); ?>
		<?php echo $form->textField($model,'pagesize_end'); ?>
		<?php echo $form->error($model,'pagesize_end'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'par_num'); ?>
		<?php echo $form->textField($model,'par_num'); ?>
		<?php echo $form->error($model,'par_num'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'content_page_rule'); ?>
		<?php echo $form->textField($model,'content_page_rule'); ?>
		<?php echo $form->error($model,'content_page_rule'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'content_page'); ?>
		<?php echo $form->textField($model,'content_page'); ?>
		<?php echo $form->error($model,'content_page'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'down_attachment'); ?>
		<?php echo $form->textField($model,'down_attachment'); ?>
		<?php echo $form->error($model,'down_attachment'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'watermark'); ?>
		<?php echo $form->textField($model,'watermark'); ?>
		<?php echo $form->error($model,'watermark'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'coll_order'); ?>
		<?php echo $form->textField($model,'coll_order'); ?>
		<?php echo $form->error($model,'coll_order'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'sourcecharset'); ?>
		<?php echo $form->textField($model,'sourcecharset'); ?>
		<?php echo $form->error($model,'sourcecharset'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'url_start'); ?>
		<?php echo $form->textField($model,'url_start'); ?>
		<?php echo $form->error($model,'url_start'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'url_end'); ?>
		<?php echo $form->textField($model,'url_end'); ?>
		<?php echo $form->error($model,'url_end'); ?>
	</div>


	<div class="row buttons">
		<?php echo CHtml::submitButton('Submit'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->