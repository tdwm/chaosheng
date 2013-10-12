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
    <div class="span4">
        <?php $this->beginWidget('bootstrap.widgets.TbBox', array(
            'title' => 'Application Data',
            'headerIcon' => 'icon-cog',
        )); ?>
            <p><small>Any application data that is stored with this user is displayed here. Don't remove this data unless you know what you are doing.</small></p>
            <table class="detail-view table table-striped table-condensed" id="yw3">
                <tbody>
                    <?php $i = 0; foreach ($model->metadata as $meta): $i++; ?>
                    <tr>
                        <th class="<?php echo $i % 2 == 0 ? 'even' : 'odd'; ?>" style="width: auto; text-align:left;"><?php echo $meta->key; ?></th>
                        <td><?php echo $meta->value; ?><i id="<?php echo $meta->key; ?>" class="icon-remove" style="float: right;"></i></td>
                        
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php $this->endWidget(); ?>
    </div>
    <!--
    <div class="span3">
        <?php $this->beginWidget('bootstrap.widgets.TbBox', array(
            'title' => 'Security Events',
            'headerIcon' => 'icon-user',
        )); ?>
            <p><small>Any security events attached to this user will be displayed here.</small></p>
        <?php $this->endWidget(); ?>
    </div>
    -->
</div>

<?php Yii::app()->clientScript->registerScript('delete_meta', '
    $(".icon-remove").click(function() {
        $(this).parent().parent().fadeOut();
       $.post("../../removeMeta", { key : $(this).attr("id"), user_id : ' . $model->id. '}); 
    });
'); ?>
