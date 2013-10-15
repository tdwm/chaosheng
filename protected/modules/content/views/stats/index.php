<?php
//Yii::app()->bootstrap->registerAssetJs('bootstrap-datepicker.min.js');
//Yii::app()->bootstrap->registerAssetJs('locales/bootstrap-datepicker.zh-CN.js');
//Yii::app()->bootstrap->registerAssetCss('bootstrap-datepicker.min.css');
?>
<div class="row-fluid">
    <div class="span2">
            <?php
                $this->widget('bootstrap.widgets.TbMenu', array(
                    'type'=>'list',
                    'items' =>  $items,
                ));
            ?>
    </div>
    <div class="span6">
    <?php
    if($model):
    ?>
    <div class="row-fluid">
        <div class="span6"> <?php echo "<h4>$site->my_site  </h4>"; ?> </div>
        <div class="span4" style="padding-top:10px">
            <?php
                echo "推送总量:";
                $this->widget('bootstrap.widgets.TbBadge', array(
                    'type'=>'inverse', 
                    'label'=>$total,
                )); 
            ?>
        </div>
    </div>
    <div class="row-fluid">
    <?php
            $this->widget('bootstrap.widgets.TbExtendedGridView', array(
                'type' => 'striped bordered',
                'dataProvider' => $model,
                'template' => "{items}{pager}",
                'columns' => array(
                    //'user_id',
                    //'mysite.my_site',
                    'daytime'=>array(
                        'name'=>'daytime',
                        'htmlOptions'=>array('width'=>'100px'),
                    ),
                    'pnum'=>array(
                        'name'=>'pnum',
                    ),
                    //'status',
                    array(
                        'class'=>'bootstrap.widgets.TbButtonColumn',
                        'template'=>' {view}',
                        'buttons'=>array(
                            'view'=>array(
                                'url'=>'Yii::app()->createUrl("/content/admin/show/" )',
                            ),
                        ),
                    ),
                ),
        ));
    ?>  
    </div>
    <?php endif; ?>
    </div>
    <div class="span3" >
    </div>
</div>
