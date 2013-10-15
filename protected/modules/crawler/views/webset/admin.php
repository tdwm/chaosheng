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
                'dataProvider' => $model->search(),
                'filter' => $model,
                'responsiveTable' => true,
                'columns' => array(
                    array('name'=>'id','htmlOptions'=>array('width'=>'50px')),
                    array('name'=>'uid','value'=>'$data->user->displayName'),
                    array('name'=>'my_site','header'=>'站点名称'),
                   // array('name'=>'api_url', 'header'=>'推送地址',),
                    array(
                        'class'=>'bootstrap.widgets.TbButtonColumn',
                        'header' => '操作',
                        'template' => '{allot} {update} {stat}  &nbsp; &nbsp; &nbsp;&nbsp; {delete}' ,
                        'htmlOptions'=>array('class'=>'span2'),
                        'buttons'=>array(
                            'allot'=>array(
                                'label'=>'分配',
                                'icon'=>'icon-random',
                                'url'=>'Yii::app()->createUrl("/crawler/webset/allotedit",array("site_id"=>$data->id))'
                            ),
                            'update'=>array(
                                'url'=>'Yii::app()->createUrl("/crawler/webset/create",array("id"=>$data->id))'
                            ),
                            'stat'=>array(
                                'label'=>'统计',
                                'icon'=>'icon-stats',
                                'url'=>'Yii::app()->createUrl("/crawler/webset/stat",array("site_id"=>$data->id))'
                            ),
                        ),
                    ),
                )
            ));

        ?>
</div>
