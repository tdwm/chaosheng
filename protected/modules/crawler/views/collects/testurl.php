<?php
$this->widget('bootstrap.widgets.TbBreadcrumbs', array(
    'links'=>array(
        '采集'=>Yii::app()->createUrl('/crawler/collects'),
        '采集管理'=>Yii::app()->createUrl('/crawler/collects/node'),
        '测试内容',
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
    'dataProvider' => $providerData,
    'fixedHeader' => true,
    'responsiveTable' => true,
    'columns' => array(
        array('name'=>'title','header'=>'标题'),
        array('name'=>'url','header'=>'网址'),
        array(
            'class'=>'bootstrap.widgets.TbButtonColumn',
            'header' => '操作',
            'htmlOptions'=>array('width'=>'80px'),
            'template' => '{view}',
            'buttons' => array(
                'view'=>array(
                    'label'=>'查看内容',
                    'url'=>'$data["url"]',
                    'type'=>'raw',
                    'value'=>'$data["id"]',
                    'click'=>'js:function(even){
                        even.preventDefault();
                        _t = $(this);
                        testcolcontent($(this).attr("href")); 
                    }',
                ),    
            ),
        ),
    )
));
?>
<script>
   function testcolcontent(url){
       _link = '<?php echo $this->createUrl("/crawler/collects/testcolcontent/");?>';
       $.ajax({
           type:"post",
               url:_link,
               data:{url:url,id:<?php echo $nodeid;?>},
               cache:true,
               success:function(data){
                   $(".modal-body","#myModal").html(data);
                   $("#myModal").modal({"show":true});
               }
       });
       /*
       $(".modal-body","#myModal").load(_link,{'url':url},function(){
           $("#myModal").modal({"show":true});
       });
        */
   }
</script>
<?php $this->beginWidget('bootstrap.widgets.TbModal', array('id' => 'myModal')); ?>

    <div class="modal-header">
        <a class="close" data-dismiss="modal">&times;</a>
        <h4>采集数据</h4>
    </div>
    <div class="modal-body">
    </div>

<?php $this->endWidget(); ?>
</div>
