<div class="row-fluid">
    <div class="span8">
    <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
        'id'=>'users-form',
        'enableAjaxValidation'=>false,
    )); ?>
    <?php $this->beginWidget('bootstrap.widgets.TbBox', array(
        'title' => $model->isNewRecord ? '创建采集设置' : '修改采集设置',
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
    <?php echo $form->errorSummary($model); ?>
    <?php echo $form->hiddenField($model,'site_id'); ?>
    <?php echo $form->dropDownListRow($model,'category_id',CHtml::listdata(Categories::model()->findAll(), 'id', 'name'), array('class'=>'span12')); ?>        
    <?php echo $form->dropDownListRow($model,'type_id',$model->type, array('class'=>'span12')); ?>        
    <?php echo $form->dropDownListRow($model,'encoding',$model->encoding_arr, array('class'=>'span12')); ?>        
    <?php echo $form->textFieldRow($model,'all_num',array('class'=>'span5')); ?>
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
    <?php echo $form->textFieldRow($model,'period_time',array('class'=>'span5')); ?>

    <?php echo $form->textAreaRow($model, 'site_categories', array('style' => 'width: 98%; height: 100px', 'placeholder' => '填写对应网站分类')); ?>
    <?php echo $form->textAreaRow($model, 'site_media', array('style' => 'width: 98%; height: 100px', 'placeholder' => '填写对应网站媒体')); ?>
    <?php $this->endWidget(); ?>
    <!--form-->
    <?php $this->beginWidget('bootstrap.widgets.TbButton', array(
                'buttonType'=>'submit',
                'type'=>'primary',
                'label'=>$model->isNewRecord ? '添加' : '保存',
                'htmlOptions' => array(
                    'style' => 'margin-right: 10px;'
                )
            )
        ) ; ?>
    <?php $this->endWidget(); ?>
    <!--button-->
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
