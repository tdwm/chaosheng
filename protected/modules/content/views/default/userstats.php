<?php
Yii::app()->bootstrap->registerAssetJs('bootstrap-datepicker.min.js');
Yii::app()->bootstrap->registerAssetJs('locales/bootstrap-datepicker.zh-CN.js');
Yii::app()->bootstrap->registerAssetCss('bootstrap-datepicker.min.css');
?>
<div class="row-fluid">
    <div class="span3">
    <h4><?php echo $user->name;?></h4>
    <?php if(Yii::app()->user->role == 4): ?>
    <div class="label-inverse">
        <?php
            $this->widget('bootstrap.widgets.TbMenu', array(
                'type'=>'pills',
                'items' =>  $userlist,
            ));
        ?>
    </div>
    <?php endif ?>
    <div id="datepicker" class="datepicker" data-date="<?php echo $month;?>" data-date-format="yyyy-mm"> </div>
        <?php
            /*
            $this->widget('bootstrap.widgets.TbDatePicker',array(
                'id'=>'datepicker',
                'name'=>'month',
                'options' => array(
                    'format' => 'yyyy-mm',
                    'viewMode'=>'months',
                    'language'=> "zh-CN",
                    'minViewMode'=> "months",
                    'keyboardNavigation'=>false,
                ),
                'events'=>array(
                    'changeDate'=>'js:function(ev){
                        var date = $(this).datepicker("getDate"),
                            day  = date.getDate(),  
                            month = date.getMonth() + 1,              
                            year =  date.getFullYear();
                        alert(day + "-" + month + "-" + year);
                    }',
                ),
            )); 
            */
            Yii::app()->clientScript->registerScript(
                __CLASS__ . '#' . $this->getId(),
                "$('#datepicker').datepicker({
                        format: 'yyyy-mm',
                        startView: 1,
                        minViewMode: 1,
                        language: 'zh-CN',
                        forceParse: false,
                 }).on('changeDate',function(dateText, inst){
                     var date=$(this).datepicker('getDate'),
                         day=date.getDate(),
                         month=date.getMonth()+1,
                         year=date.getFullYear(); 
                         _ym = year+'-'+month;
                         location.href='".$this->createUrl('stats',array('site_id'=>$site_id,'user_id'=>$user->id))."/month/'+_ym;
                });
             ");
        ?>
    </div>
    <div class="span7">
    <?php
        $this->widget('bootstrap.widgets.TbGridView', array(
            'type' => 'striped bordered',
            'dataProvider' => $stats,
            'columns' => array(
                //'site_id',
                //'user_id',
                'mysite.my_site',
                'daytime'=>array(
                    'name'=>'daytime',
                ),
                'pnum',
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
</div>
