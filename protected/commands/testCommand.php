<?php
error_reporting(E_ALL & ~E_NOTICE);
Yii::import('application.modules.crawler.components.*');
class testCommand extends CConsoleCommand {

    public function actionTP($id=null)
    {

        $url ='http://kn2013.hunantv.com/klns.php?itemid=2819';
        $bits = parse_url($url);  
        $query = isset($bits['query']) ? $bits['query'] : '';  
        $path = isset($bits['path']) ? $bits['path'] : '/';  
        $query = isset($bits['query'])? $bits['query']:'';

        $client = new HttpClient($bits['host']);
        //$client->setDebug(true);
        if (!$client->get($path.'?'.$query)) {
            throw new CHttpException(400,'URL:'.$l.' ERROR:' . $client->getError());
        }
        if ($client->getStatus() != '200') {
            throw new CHttpException(400,'URL:'.$l.' ERROR: not 200 status ' . $client->getError());
        }
        $html = $client->getContent();

        echo $html;
    }
    public function actionCget()
    {

        $ch=curl_init();
        curl_setopt($ch,CURLOPT_URL,'http://kn2013.hunantv.com/klns.php?itemid=2819');
        curl_setopt($ch,CURLOPT_HTTPHEADER, array('X-FORWARDED-FOR:167.160.229.003', 'CLIENT-IP:167.160.229.001'));  //构造IP
        curl_setopt($ch,CURLOPT_REFERER,'http://www.hunantv.com/v/2013/2013superboy/vsll/');
        curl_setopt($ch,CURLOPT_HEADER,0);
        $out=curl_exec($ch);
        print $out;
        curl_close($ch);
    }

}
