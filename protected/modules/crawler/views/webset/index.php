<?php
$this->breadcrumbs=array(
	'Collects Webs',
);
?>
<div class="row-fluid">
    <div class="span12" style="margin-top: 5px;">
        <div>
        <?php $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType' => 'link',
            'type'=>'primary',
            'label' => '添加',
            'url' => array('webset/create'),
            'htmlOptions' => array(
                'class' => 'pull-right'
            ),
        )); ?> 
        </div>
        <div class="clearfix"></div>
        <?php 
            $this->widget('bootstrap.widgets.TbExtendedGridView', array(
                'type' => 'striped bordered',
                'dataProvider' => $model->usersearch(),
                'filter' => $model,
                'responsiveTable' => true,
                'columns' => array(
                    array('name'=>'id','htmlOptions'=>array('width'=>'50px')),
                    array('name'=>'my_site','header'=>'站点名称'),
                    array(
                        'class'=>'CLinkColumn',
                        'header'=>'推送地址',
                        'labelExpression'=>'$data->api_url',
                        'urlExpression'=>'$data->api_url',
                        'linkHtmlOptions'=>array('target'=>'_blank')
                    ),
                    array(
                        'class'=>'bootstrap.widgets.TbButtonColumn',
                        'header' => '操作',
                        'template' => '{view} {update}  ',
                        'buttons'=>array(
                            'view'=>array(
                                'label'=>'查看分配情况',
                                'url'=>'$data->id',
                                'click'=>'js:function(even){
                                    even.preventDefault();
                                    _sid = $(this).attr("href");
                                    $.post("crawler/webset/categorybysiteid",{"site_id":_sid,"ajax":"get"},function(html){
                                        bootbox.modal(html, "已绑定资源分类");
                                    });
                                 }',
                            ),
                            'update'=>array(
                                'url'=>'Yii::app()->createUrl("/crawler/webset/create",array("id"=>$data->id))'
                            )
                        ),
                    ),
                )
            ));

        ?>
</div>
