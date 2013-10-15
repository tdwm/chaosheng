<?php
$this->widget('bootstrap.widgets.TbBreadcrumbs', array(
    'links'=>array(
        '采集'=>Yii::app()->createUrl('/crawler/collects'),
        '采集管理'=>Yii::app()->createUrl('/crawler/collects/node'),
        '发布内容',
        '[方案管理]'=>Yii::app()->createUrl("/crawler/collects/pushprogram",array('id'=>$model->nodeid)),
    )
));
?>
<div class="row-fluid">
    <div class="span12" >
     <div class="clearfix"></div>
<?php 
//$this->widget('bootstrap.widgets.TbExtendedGridView', array(
$this->widget('bootstrap.widgets.TbExtendedGridView', array(
    'type' => 'striped',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'id'=>'contentlist',
    //'fixedHeader' => true,
    'bulkActions' => array(
        'actionButtons' => array(
            array(
                'buttonType' => 'button',
                'type' => 'primary',
                'id'=>'import',
                'size' => 'small',
                'label' => '导入',
                'click' => 'js:function(values){console.log(values);}'
            )
        ),
        // if grid doesn't have a checkbox column type, it will attach
        // one and this configuration will be part of it
        'checkBoxColumnConfig' => array(
            'name' => 'id'
        ),
    ),
    'columns' => array(
        array('name'=>'id','htmlOptions'=>array('width'=>'30px')),
        array('name'=>'status','header'=>'状态',
        'value'=>'array_search($data->status,array_flip(Yii::app()->controller->push_status))' ,
        'htmlOptions'=>array('width'=>'80px'),
        'filter'=>Yii::app()->controller->push_status,
        ),
        array('name'=>'title','header'=>'标题'),
        array('name'=>'created','header'=>'采集时间','filter'=>''),
        array('name'=>'nodeid','header'=>'来源','value'=>'myFunc::getNodeName($data->nodeid)',
        "filter"=>CHtml::listData(CollectsNode::model()->findAll(), "nodeid", "name"),
        ),
        array(
            'name'=>'showdata',
            'value'=>'CHtml::textArea("sd_$data->id","",array("style"=>"display:none"))',
            'type'=>'raw',
            'header'=>'',
            'filter'=>'',
            'htmlOptions'=>array('width'=>0),
        ),
        array(
            'class'=>'bootstrap.widgets.TbButtonColumn',
            'header' => '操作',
            'htmlOptions'=>array('target'=>'_blank','width'=>'80px'),
            'template' => '{view} {down} {delete} {web}',
            'buttons' => array(
                'web'=>array(
                    'label'=>'采集网址',
                    'url'=>'"$data->url"',
                    'options'=>array('target'=>'_blank'),
                    'icon'=>'globe',
                ),    
                'view'=>array(
                    'label'=>'查看内容',
                    'url'=>'"#v_$data->id"',
                    'click'=>'js:function(even){
                        even.preventDefault();
                        _id = $(this).attr("href").replace("#v_","");
                        showData(_id);
                    }',
                ),    
                'down'=>array(
                    'label'=>'从新采集',
                    'url'=>'"#v_$data->id"',
                    'icon'=>'download-alt',
                    'click'=>'js:function(even){
                        even.preventDefault();
                        _id = $(this).attr("href").replace("#v_","");
                        recolcontent(_id); 
                    }',
                ),    
                'delete'=>array(
                    'label'=>'删除',
                    'url'=>'Yii::app()->createUrl("/crawler/collects/coldelete/id/" . $data->id)',
                ),    
            ),
        ),
    )
));
?>
<?php $this->beginWidget('bootstrap.widgets.TbModal', array('id' => 'myModal')); ?>
    <div class="modal-header">
        <a class="close" data-dismiss="modal">&times;</a>
        <h4>采集数据</h4>
    </div>
    <div class="modal-body">
        <p>One fine body...</p>
    </div>
<?php $this->endWidget(); ?>
</div>
<script>
var recol = new Array()
    function recolcontent(id){
        _link1 = '<?php echo $this->createUrl("/crawler/collects/recolcontent/");?>';
        $.ajax({
            type:"get",
                url:_link1,
                data:{id:id},
                cache:true,
                success:function(data){
                    recol.push(id);
                    $.unique(recol);
                    $(".info").animate({opacity: 1.0}, 3000).fadeOut("slow");
                }
        });
    }
function showData(id){
    _id = "sd_"+id;
    index = $.inArray(id,recol);
    _link2 = '<?php echo $this->createUrl("/crawler/collects/getcolfield/");?>';
    if(index != -1 || $("#"+_id).val() == '') {
        $.ajax({
            type:"get",
                url:_link2,
                data:{id:id,'ajax':1,'field':'data'},
                cache:true,
                success:function(data){
                    $("#"+_id).val(data);
                    $(".modal-body","#myModal").html(data); 
                    $("#myModal").modal({"show":true});
                    recol.splice(index,1);
                }
        });
    } else {
        $(".modal-body","#myModal").html($("#"+_id).val()); 
        $("#myModal").modal({"show":true});
    }

}
</script>
