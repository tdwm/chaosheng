<div class="row-fluid" id="param_<?php echo $item->id; ?>" >
    <div class="span2"> 
         <?php echo  CHtml::textField("plabels[]",$item->label,array('placeholder'=>'参数名称','class'=>"span10")); ?>
    </div>
    <div class="span2"> 
         <?php echo  CHtml::textField("pnames[]",$item->name,array('placeholder'=>'参数名称','class'=>"span10")); ?>
    </div>
    <div class="span2"> 
         <?php echo  CHtml::hiddenField("ptypes[]",$item->type); echo $this->ptype[$item->type]; ?>
    </div>
    <div class="span5"> 
         <?php
            if ($item->type == 'select' || $item->type == 'multiplesel') {
              echo CHtml::textArea("pvalues[]",$item->value,array('placeholder'=>'默认值,没有默认值，就不用填写','class'=>"span10"));
            }else{
              echo CHtml::textField("pvalues[]",$item->value,array('placeholder'=>'默认值,没有默认值，就不用填写','class'=>"span10"));
            }
        ?>
    </div>
    <div class="span1"> 
         <?php echo  CHtml::button("删除",array("class"=>"btn-danger","data-id"=>"param_$item->id"));  ?>
    </div>
</div>
