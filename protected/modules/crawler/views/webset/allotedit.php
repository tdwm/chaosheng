<div class="row-fluid">
    <div class="span8">
    <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
        'id'=>'users-form',
        'enableAjaxValidation'=>false,
    )); ?>
    <?php $this->beginWidget('bootstrap.widgets.TbBox', array(
        'title' => $site_name,
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
    <p class="help-block">标记 <span class="required">*</span>必须填写</p>
    <?php echo $form->hiddenField($model,'site_id');  ?>        
    <?php echo $form->errorSummary($model); ?>
         <div class="row-fluid ">
            <div class="span4" >
                <?php echo $form->dropDownListRow(
                                $model,
                                'category_id',
                                CHtml::listData(
                                    Categories::model()->findAll(array('order'=>'path')), 'id', 'optionName'),
                                array('class'=>'span11','encode'=>false,'prompt'=>'--根目录--')
                            ); 
                ?>
            </div>
            <div class="span2" >
                <?php echo $form->dropDownListRow($model,'type_id',$this->allot_type, array('class'=>'span12')); ?>        
            </div>
            <div class="span3" >
                <?php echo $form->textFieldRow($model,'all_num',array('class'=>'span12')); ?>
            </div>
            <div class="span3" >
                <?php echo $form->dropDownListRow($model,'status',CollectsAllot::model()->status_type, array('class'=>'span12')); ?>
            </div>
         </div>
         <div class="row-fluid ">
            <div class="span4" >
                <?php 
               echo $form->labelEx($model, 'start_time');
               echo $this->widget('bootstrap.widgets.TbDatePicker', array(
                    'model'=>$model, 
                    'attribute'=>'start_time', 
                    'htmlOptions' => array(
                        'id' => 'start_time',
                    ),
                    'options'=>array(
                        'showAnim'=>'fold',
                        'format' => 'yyyy-mm-dd',
                        'autoclose'=> true,
                        'language' => 'zh-CN',
                    ),
                ),true);
                ?>
            </div>
            <div class="span4" >
                <?php 
               echo $form->labelEx($model, 'end_time');
               echo $this->widget('bootstrap.widgets.TbDatePicker', array(
                   'model'=>$model, 
                   'attribute'=>'end_time', 
                   'htmlOptions' => array(
                       'id' => 'end_time',
                   ),
                   'options'=>array(
                       'showAnim'=>'fold',
                       'format' => 'yyyy-mm-dd',
                       'autoclose'=> true,
                       'language' => 'zh-CN',
                   ),
               ),true) ;

                ?>
            </div>
            <div class="span4" >
                <?php echo $form->textFieldRow($model,'period_time',array('class'=>'span5')); ?>
            </div>
         </div>
    <?php $this->endWidget(); ?>
    <!--form-->
 <?php $this->endWidget(); ?>
    </div>
    <div class="span4">
<?php $this->beginWidget('bootstrap.widgets.TbBox', array(
    'title' => '已经分配资源',
    'headerIcon' => 'icon-cog',
)); ?>
        <?php 
        $this->renderPartial('_ajax_category',array('haveAllot'=>$haveAllot,'site_id'=>$site_id));
        ?>
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
    $.post("../../removeMeta", { key : $(this).attr("id"), user_id : ' . ($model->id?$model->id:0). '}); 
    });
'); ?>
<?php
Yii::app()->clientScript->registerScript('re-install-date-picker', "
function reinstallDatePicker(id, data) {
    $('#start_time').datepicker();
    $('#end_time').datepicker();
}");
?>
