<div class="row-fluid">
    <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id'=>'collectSetting',
        'enableAjaxValidation'=>false,
    )); ?>
	        <?php $this->beginWidget('bootstrap.widgets.TbBox', array(
                'title' => 'Content',
                'headerIcon' => 'icon-leaf',
                'headerButtons' => array(
                    array(
                        'class' => 'bootstrap.widgets.TbButtonGroup',
                        'buttons'=>array(
                            array('label'=>'保存', 'buttonType' => 'submit')
                        ),
                    )
                )
            )); ?>
    	    	<?php echo $form->hiddenField($model, 'id'); ?>
    			<?php echo $form->hiddenField($model,'uid',array('value'=>Yii::app()->user->id,)); ?>
                <div class="row-fluid ">
                    <div class="span3" >
                        <?php 
                            echo $form->textFieldRow($model, 'my_site', array('placeholder' => '站点标题','hint'=>'', 'style' => 'width: 98%')); 
                        ?>
                    </div>
                    <div class="span3" >
                        <?php 
                            echo $form->dropDownListRow($model, 'my_charset',$this->charset, array('placeholder' => '编码', 'style' => 'width: 98%')); 
                        ?>
                    </div>
                    
                    <div class="span6" >
                        <?php echo $form->textFieldRow($model, 'api_url', array('style' => 'width: 98%; ', 'placeholder' => 'api地址')); ?>
                    
                    </div>
                </div>
	        <?php $this->endWidget(); ?>
	    	<?php $this->beginWidget('bootstrap.widgets.TbBox', array(
                    'title' => '参数列表',
                    'id'=>'paramlist',
                    'headerIcon' => 'icon-align-justify',
                )); 

                ?>
                <div class="row-fluid " id="insertparam">
                    <div class="span2">
                        <div class="control-group">
                          <label class="control-label" for="input01">参数说明</label>
                          <div class="controls">
                                <?php echo  CHtml::textField("plabel",'',array('placeholder'=>'参数说明','class'=>"span10")); ?>
                          </div>
                        </div>
                    </div>
                    <div class="span2">
                        <div class="control-group">
                          <label class="control-label" for="input01">参数名称</label>
                          <div class="controls">
                             <?php echo  CHtml::textField("pname",'',array('placeholder'=>'参数名称','class'=>"span10")); ?>
                          </div>
                        </div>
                    </div>
                    <div class="span2">
                        <div class="control-group">
                          <label class="control-label" for="input01">参数类型</label>
                          <div class="controls">
                    <?php echo  CHtml::dropDownList("ptype",'',$this->ptype,array('placeholder'=>'说明','class'=>'span10','empty'=>'--参数类型--')); ?>
                         </div>
                        </div>
                    </div>
                    <div class="span5">
                        <div class="control-group">
                          <label class="control-label" for="input01">默认值</label>
                          <div class="controls">
                            <div class="pvalue_text" style="display:none">
                            <?php echo  CHtml::textField("pvalue_text",'',array('placeholder'=>'默认值,没有默认值，就不用填写 ','style'=>'width:98%')); ?>
                            </div>
                            <div class="pvalue_select" style="display:none">
                            <?php echo  CHtml::textArea("pvalue_select",'',array('placeholder'=>'选项,一个选项一行，格式是:value,name 。选项值,选项名称
','style'=>'width:98%;height:100px')); ?>
                            </div>
                          </div>
                        </div>
                    </div>
                    <div class="span1" style="margin-top: 20px;">
                        <?php $this->widget('bootstrap.widgets.TbButton',array(
                                'label' => '添加',
                                'type' => 'primary',
                                'size' => 'small',
                                'htmlOptions'=>array('id'=>'addparam')
                            ));
                        ?>
                    </div>
                  </div>
                  <!--insert params-->
                <?php 
                    foreach($params  as $k=>$v){
                        $v['id'] = $k;
                        $item = (object)$v;
                        $this->renderPartial('_paramitem',array(
                            'item'=>$item,
                        ));
                    }
               ?>
            </div>
		 <?php $this->endWidget(); ?>
    <?php $this->endWidget(); ?>
</div>
<?php Yii::app()->clientScript->registerScript('webparam_edit', '
$("#ptype").change(function(){
   _v = $(this).val();
   _class = "pvalue_"+_v;
   $("div[class^=pvalue]").hide();
   $("."+_class).show();
});
$(".btn-danger").click(function(){ 
    if(confirm("你确定删除么？")){
        _id = $(this).attr("data-id");
        $("#"+_id).remove();
    }
 });
$("#addparam").click(function(){
   var _ptype = $("#ptype").val();
   var _pname = $("#pname").val();
   var _plabel = $("#plabel").val();
   var _pvalue = $("#pvalue_"+_ptype).val();
   if($.trim(_ptype) == ""){
        alert("请选择参数类型");
        return;
   }
   if($.trim(_pname) == ""){
        alert("请填写参数名称");
        return;
   }
   if($.trim(_plabel) == ""){
        alert("请填写参数说明");
        return;
   }
   if($.trim(_ptype) == "select" && $.trim(_pvalue)==""){
        alert("请填写参数值");
        return;
   }
    $.post(
        "'.Yii::app()->createUrl('/crawler/webset/paramitem').'",
        { "name":_pname, "label":_plabel, "type":_ptype, "value":_pvalue },
        function(_r){
             $("#insertparam").after(_r);
        },
        "html"
    );
});
'); ?>
