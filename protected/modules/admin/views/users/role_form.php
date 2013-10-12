<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
    'id'=>'categories-form',
    'enableAjaxValidation'=>false,
    'action'=>Yii::app()->createUrl('/admin/users/roleupdate/id/' . $model->id)
)); ?>
    
    <?php $this->beginWidget('bootstrap.widgets.TbBox', array(
        'title' => 'Create a New Category',
        'headerIcon' => 'icon-plus',
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
    )); ?>
        <p class="help-block">Fields with <span class="required">*</span> are required.</p>
    
        <?php echo $form->errorSummary($model); ?>
        <?php echo $form->hiddenField($model, 'id'); ?>
        <?php echo $form->textFieldRow($model,'name',array('class'=>'span11','maxlength'=>150)); ?>
        <?php echo $form->textFieldRow($model,'description',array('class'=>'span11','maxlength'=>150)); ?>
    
    <?php $this->endWidget(); ?>
<?php $this->endWidget(); ?>
