<?php
error_reporting(E_ALL & ~E_NOTICE);
Yii::import('application.modules.crawler.components.*');
class statsCommand extends CConsoleCommand {

    public function actionUserPush($day=null)
    {
        if($day == null) $day = date('Y-m-d' , strtotime('-1 day'));
        $sql = "
            Insert into stat_userpush(pnum,site_id,user_id,daytime)
            SELECT COUNT( col_id ) AS num, site_id, user_id, date(created)
            FROM collects_push
            WHERE DATE( created ) =  '".$day."'
            AND STATUS =2
            GROUP BY site_id,user_id;
        ";
        $connection=Yii::app()->db;   
        $command=$connection->createCommand($sql);
        $rowCount=$command->execute();
    }

    public function actionSiteDayPush($day=null)
    {
        if($day == null) $day = date('Y-m-d' , strtotime('-1 day'));
        $sql = "
            Insert into stat_site(pnum,site_id,daytime)
            SELECT COUNT( col_id ) AS pnum, site_id, date(created)
            FROM collects_push
            WHERE DATE( created ) =  '".$day."'
            AND STATUS =2
            GROUP BY site_id;
        ";
        $connection=Yii::app()->db;   
        $command=$connection->createCommand($sql);
        $rowCount=$command->execute();
    }

    public function getContentModel($catid)
    {
       return new Contents(); 
    }
}
