<?php
$this->breadcrumbs=array(
    'Collects Webs',
);
?>
<div class="row-fluid">
    <div class="span12" style="margin-top: 5px;">
        <div>
        <?php $this->beginWidget('bootstrap.widgets.TbBox', array(
            'title' =>  '内容采集 '.$colnum."/".$total,
            'headerIcon' => 'icon-home',
        )); 
        $this->widget('bootstrap.widgets.TbProgress', array(
            'type'=>'success',
            'htmlOptions'=>array('title'=>$colnum."/".$total),
            'percent' => $percent,
        ));
        ?>
        <?php $this->endWidget(); ?>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
        <script>
        $(function(){
            location.href='<?php echo $url.'/page/'.$page.'/total/'.$total?>';
        });
        </script>
