<div class="row-fluid">
    <div class="span8">
            <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
                'id'=>'users-form',
                'enableAjaxValidation'=>false,
                'type'=>'horizontal',
            )); ?>
            <?php $this->beginWidget('bootstrap.widgets.TbBox', array(
                'title' => $model->isNewRecord ? '创建用户信息' : '修改用户信息',
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
            )); ?>
                
                <p class="help-block">标记 <span class="required">*</span> 必须填写. 
                    不修改密码请留空。</p>
                    
                <?php echo $form->errorSummary($model); ?>
            
                <div class="span5">
                <?php echo $form->textFieldRow($model,'email',array('maxlength'=>255)); ?>        
                <?php echo $form->passwordFieldRow($model,'password',array('value'=>'', 'maxlength'=>64, 'placeholder' => '更改密码，不更改请留空.')); ?>        
                <?php echo $form->textFieldRow($model,'displayName',array('maxlength'=>255)); ?>        
                <?php echo $form->textFieldRow($model,'firstName',array('maxlength'=>255)); ?>        
                <?php echo $form->textFieldRow($model,'lastName',array('maxlength'=>255)); ?>        
                </div>
            
                <div class="span5">
                <?php 
                    if (Yii::app()->user->role == 5){
                echo $form->dropDownListRow($model,'user_role',CHtml::listdata(UserRoles::model()->findAll(), 'id', 'name'), array('class'=>'span12')); 
                echo $form->dropDownListRow($model,'status', array('1'=>'Active', '0'=>'Inactive'), array('class'=>'span12'));
                    } else {
                    
                    }
                ?>        
                </div>
                <div class="span5">
                <?php //echo $form->textAreaRow($model, 'about', array('class' => 'span12')); ?>
                </div>
            <?php $this->endWidget(); ?>
        <?php $this->endWidget(); ?>
    </div>
</div>
