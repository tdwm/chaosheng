<?php
foreach($haveAllot as $item){
    $this->widget('bootstrap.widgets.TbButton',array(
        'label' => $item->category->name,
        'type' => $item->status == 1 ? 'primary':'danger',
        'url' => Yii::app()->createUrl("/content/default/",array('site_id'=>$site_id,'slug'=>$item->category->slug)),
        'htmlOptions'=>array('style'=>'margin:5px 10px'),
    ));
}
?>
