<div class="row-fluid">
<?php
        $placeholder = '--选择--';
        $default ='';
        //$data = myFunc::paramtext2array($item['value'],true);
        //$data = myFunc::paramtext2array($item['value']);
        $temp = myFunc::paramtext2array($item['value'],true);
        $data_select = $temp['data'];
        $data_default = $temp['default'];
        if(isset($item['default'])){
            $data_default   = $item['default'];
        }
        $_name = $_id = 'params_'.$item['name'];
    ?>
    <div class="control span3" > <?php echo CHtml::label($item['label'],''); ?></div>
    <div class="control span9" >
    <?php if($item['type']=='select'):?>
    <?php 
        echo CHtml::dropDownList($_name,$data_default, $data_select,array('empty'=>'--选择--','class'=>'span12')); 
        /*
        $this->widget('bootstrap.widgets.TbSelect2', array(
            'asDropDownList' => false,
            'id'=> $_id,
            'name' => $_name,
            'value'=>$default,
            'htmlOptions'=>array('class'=>'span12'),
            'options' => array(
                'data' =>$data,
                'placeholder' => $placeholder,
                'maximumSelectionSize'=> 1, 
                'multiple'=>false,
            )));    
         */
        Yii::app()->clientScript->registerScript('js_'.$item['name'], ' 
            $("#'.$_name.'").select2({
                placeholder: "--选择--",
                allowClear: true
             });     
        ');
    ?>
    <?php elseif($item['type']=='multiplesel'):?>
    <?php 
         echo CHtml::dropDownList($_name,$data_default, $data_select,array('empty'=>'--选择--','class'=>'span12','multiple'=>"multiple")); 
        Yii::app()->clientScript->registerScript('js_'.$item['name'], ' 
            $("#'.$_name.'").select2({
                placeholder: "--选择--",
                allowClear: true
                });     
        ');
    ?>
    <?php endif;?>
    <?php if($item['type']=='text'):?>
        <?php echo CHtml::textField('params_'.$item['name'],$default?$default:$item['value'],array('class'=>'span12')); ?>
    <?php endif;?>
    <?php if($item['type']=='hidden'):?>
        <?php echo CHtml::hiddenField('params_'.$item['name'],$item['value'],array('class'=>'span12')); ?>
    <?php endif;?>
    </div>
</div>
