<?php
class myFunc {
    /**
     * 将字符串转换为数组
     *
     * @param string $data 字符串
     * @return array 返回数组格式，如果，data为空，则返回空数组
     */
    public static function string2array($data) {
        if($data == '') return array();
        //return json_decode($data);
        @eval("\$array = $data;");
        return $array;
    }
    /**
     * 将数组转换为字符串
     *
     * @param array $data 数组
     * @param bool $isformdata 如果为0，则不使用new_stripslashes处理，可选参数，默认为1
     * @return string 返回字符串，如果，data为空，则返回空
     */
    public static function array2string($data, $isformdata = 1) {
        if($data == '') return '';
        //return json_encode($data);
        if($isformdata) $data = self::new_stripslashes($data);
        return var_export($data, TRUE);
        return addslashes(var_export($data, TRUE));
    }

    public static function paramtext2array($params, $flag=false){
        $resutl = $default = array();
        $array = explode("\n",$params);    
        if($flag) {
            foreach($array as $v){
                $temp = explode(',',$v);
                //$resutl[$temp[0]] = $temp[1];
                $resutl[$temp[0]] = $temp[1];
                if(isset($temp[2])&&$temp[2]==1){
                    $default[] = $temp[0];           
                }
            }

        } else {
            foreach($array as $v){
                $temp = explode(',',$v);
                //$resutl[$temp[0]] = $temp[1];
                $resutl[] = array('id'=>$temp[0],'text'=>$temp[1]);
                if(isset($temp[2])&&$temp[2]==1){
                    $default[] = $temp[0];           
                }
            }
        }
        return array('data'=>$resutl, 'default'=>$default);
    }
    /**
     * 返回经stripslashes处理过的字符串或数组
     * @param $string 需要处理的字符串或数组
     * @return mixed
     */
    public static function new_stripslashes($string) {
        if(!is_array($string)) return stripslashes($string);
        foreach($string as $key => $val) $string[$key] = self::new_stripslashes($val);
        return $string;
    }

    public static function fileext($filename) { 
        return strtolower(trim(substr(strrchr($filename, '.'), 1, 10))); 
    }  

    public static function fetchArray($array = array(),$key,$value = ''){
        if(!is_array($array) || $key == '') return array();
        if ($value){
            foreach($array as $item){
                if(array_key_exists($key,$item) && $item[$key] == $value){
                    return $item;
                } 
            } 
            return false;
        }else{
            $temp = array();
            foreach($array as $item){
                if(array_key_exists($key,$item) ){
                    $temp[] = $item[$key];
                } 
            }
            return $temp;
        }
    }
    public static function getNodeName($id)
    {
        $node = CollectsNode::getNodeArray($id);
        if (empty($node)) return '';
        return $node['name'];
    }

    public static function getCatName($id)
    {
        $catgory = Categories::getCatArray($id);
        if (empty($catgory)) return '';
        return $category['name'];
    }

    public static function pushData($push)
    {

        if (strstr($push['api_url'],'?')){
            $url = $push['api_url']."&t=".time();
        } else {
            $url = $push['api_url']."?t=".time();
        }
        //$url = 'http://127.0.0.1/test/api.php'."?t=".time();

        if ($push['charset'] == '2'){
            if (!empty($push['data'])){
                $push['data'] = myFunc::UTF82GBK($push['data']);
            }
        } 
        //var_dump($push['data']);exit;
        Yii::import('ext.HttpClient');
        $bits = parse_url($url);
        $host = $bits['host'];
        $port = isset($bits['port']) ? $bits['port'] : 80;
        $path = isset($bits['path']) ? $bits['path'] : '/';
        $query = isset($bits['query']) ? $bits['query'] : '';

        $client = new HttpClient($host, $port);
        if (!$client->post($path.'?'.$query, $push['data'])) {
            $output =  '0'.$client->getError();
        } else {
            $output = $client->getContent();
        }

        $result = json_decode($output,true);
        //查看错误
        if(is_array($result))
            return self::pushReturnJson($result);
        else{
            return self::pushReturnStr($output);
        }
    }


    public static function pushData_new($push)
    {

        if (strstr($push['api_url'],'?')){
            $url = $push['api_url']."&t=".time();
        } else {
            $url = $push['api_url']."?t=".time();
        }
        //$url = 'http://127.0.0.1/test/api.php'."?t=".time();

        if ($push['charset'] == '2'){
            if (!empty($push['data'])){
                $push['data'] = myFunc::UTF82GBK($push['data']);
            }
        } 
        //var_dump($push['data']);exit;
        Yii::import('ext.HttpClient');
        $bits = parse_url($url);
        $host = $bits['host'];
        $port = isset($bits['port']) ? $bits['port'] : 80;
        $path = isset($bits['path']) ? $bits['path'] : '/';
        $query = isset($bits['query']) ? $bits['query'] : '';

        $client = new HttpClient($host, $port);
        //if (!$client->post($path, $poststr)) {
        if (!$client->post($path.'?'.$query, $push['data'])) {
            $output =  '0'.$client->getError();
        } else {
            $output = $client->getContent();
        }

        $result = json_decode($output,true);
        return  self::pushReturnJson($result);
    }

    public static function pushReturnJson($result)
    {
        
        if ($result == false) {
            Yii::app()->user->setFlash('error','推送失败: 返回数据错误');
            return false;
        }
        if(isset($result['error'])){
            Yii::app()->user->setFlash('error',"推送失败: ".$result['error']);
            return false;
        }

        if(!isset($result['links']) || !is_array($result['links']) || count($result['links']) == 0){
            Yii::app()->user->setFlash('info','推送成功: 没有返回链接');
            return true;
        }
        $message = '';
        foreach($result['links'] as $link){
            $message .="<a href='{$link[link]}' target='_blank'>{$link[label]}</a> ";
        }
        Yii::app()->user->setFlash('info','推送成功: '.$message);
        return true;
    }

    public static function pushReturnStr($output)
    {
        //查看错误
        $restatus = intval(substr($output,0,1));
        $retext = substr($output,1,strlen($output));

        $retext =  preg_replace('/(http:\/\/.*)$/','<a href="$1" target="_blank">$1</a> ',$retext);
        Yii::app()->user->setFlash('warning', $retext );

        if( $restatus == 0) {
            return false;
        }

        if( $restatus == 1) {
            return true;
        }

    }

    public static function catchHtml($url){

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
        return $html;
    }

    public static function UTF82GBK($data)
    {
        if(is_array($data)){
            foreach($data as &$v){
                $v = myFunc::UTF82GBK($v); 
            }
        }else {
            $data = iconv("UTF-8","GB2312//IGNORE",$data);
        }

        return $data;
    }
    
   public static function DeleteHtml($str) 
   { 
       $str = trim($str); 
       $str = strip_tags($str); 
       $str = str_replace("\r\n","",$str);
      // $str = preg_replace("/[\s\r\n\t]/si", '', $str);
       $str = preg_replace("/\t/is","",$str); //使用正则表达式匹配需要替换的内容，如：空格，换行，并将替换为空。
       $str = preg_replace("/\r\n/is","",$str); 
       $str = preg_replace("/\r/is","",$str); 
       $str = preg_replace("/\n/is","",$str); 
       //$str = preg_replace("/ /is","",$str);
       $str = preg_replace("/　　/","",$str);
       //$str = preg_replace("/\s/is","",$str);
       $str = preg_replace("/&nbsp;/","",$str);  //匹配html中的空格
       return trim($str); 
   }
} 
?>
