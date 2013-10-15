<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'collects-web-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldRow($model,'uid',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'api_url',array('class'=>'span5','maxlength'=>250)); ?>

	<?php echo $form->textFieldRow($model,'my_site',array('class'=>'span5','maxlength'=>20)); ?>

	<?php echo $form->textAreaRow($model,'my_category',array('rows'=>6, 'cols'=>50, 'class'=>'span8')); ?>

	<?php echo $form->textAreaRow($model,'my_media',array('rows'=>6, 'cols'=>50, 'class'=>'span8')); ?>

	<?php echo $form->textAreaRow($model,'my_params',array('rows'=>6, 'cols'=>50, 'class'=>'span8')); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
