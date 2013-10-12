<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'collects-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldRow($model,'user_id',array('class'=>'span5')); ?>

    <?php echo $form->dropDownListRow($model,'category_id',CHtml::listdata(Categories::model()->findAll(), 'id', 'name'), array('class'=>'span12')); ?>        
    <?php echo $form->dropDownListRow($model,'type_id',CHtml::listdata(Collects::type), array('class'=>'span12')); ?>        

	<?php echo $form->textFieldRow($model,'all_num',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'day_num',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'send_num',array('class'=>'span5')); ?>
    <?php 
    $this->widget('zii.widgets.jui.CJuiDatePicker',array(
        'attribute'=>'start_time',
        'language'=>'zh_cn',
        'model'=>$model,
        'name'=>$model->start_time,
        'options'=>array(
            'showAnim'=>'fold',
            'showOn'=>'both',
            'buttonImage'=>Yii::app()->request->baseUrl.'/images/calendar.gif',
            'buttonImageOnly'=>true,
            'minDate'=>'new Date()',
            'dateFormat'=>'yy-mm-dd',
        ),
        'htmlOptions'=>array(
            'style'=>'height:18px',
        ),
    ));
    ?>


	<?php echo $form->textFieldRow($model,'start_time',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'end_time',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'period_time',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'created',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'updated',array('class'=>'span5')); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
