<?php if (Yii::app()->user->role != 5): ?>
<?php
    $user_id = Yii::app()->user->id;
    $today = date('Y-m-d');
    $sql = "select count(col_id) as num, site_id,'$today' as today, user_id from collects_push 
            Where user_id= '$user_id' and date(created)='$today' and status = 2 group by site_id
        ";
    $command = Yii::app()->db->createCommand($sql);
    $rows = $command->queryAll();
    $sites = Yii::app()->session['mysites'];
    if(count($rows)) foreach($rows as $key=> $row){
        if($site = myFunc::fetchArray($sites,'id', $row['site_id']))
        {
            $rows[$key]['site_name'] = $site['my_site'];
        }
    }
    $stat = new CArrayDataProvider($rows);
?>
<div class="well span6 card">
<h4>今日[<?php echo $today; ?>]统计</h4>
        <?php
        $this->widget('bootstrap.widgets.TbGridView', array(
            'dataProvider' => $stat,
            'template' => '{items}',
            'columns' => array(
                array('name'=>'site_name','header'=>'站点'),
                array('name'=>'num','header'=>'数量'),
                )
            ));

        ?>
</div>
<?php endif; ?> 
