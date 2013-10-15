<?php
class ApiController extends CiiController
{

    //站点
    public $site;
    public $datas;
    public $limit = 50;
    public $pageinfo ;
    public $type = 'json';
    public $error_msg = '';
    public $error_no = 0;
    private $message = array(
        0 => 'success', 
        50 => '访问频率过快，请稍后访问',
        100 => '参数数错误',
        101 => 'site_id 参数数错误',
        102 => '密钥验证错误',
        103 => '',
    );
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout='/layouts/apiwraper.php';

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array(
                'allow',  
                'actions'=>array('index'),
                'expression'=>array($this,'isSiteUser'),
            ),
            array(
                'deny',  
                'users'=>array('*'),
            ),
        );
    }


    /**
     *判断用户
     */
    protected function isSiteUser()
    {
        $site_id = Yii::app()->request->getQuery('uid', 0);
        $key = Yii::app()->request->getQuery('key', '');
        $type = strtolower(Yii::app()->request->getQuery('type', 'json'));
        if(!in_array($type,array('json','xml'))){
            $this->error_no = 100;
            $this->outPut();
        } else {
            $this->type = $type;
        }
        if($site_id == 0 || $key == '') {
            $this->error_no = 100;
            $this->outPut();
        }
            
        $delay_time = Yii::app()->cache->get('api_'.$site_id);
        if($delay_time) {
            $this->error_no = 50;
            $this->outPut();
        }else {
            //Yii::app()->cache->add('api_'.$site_id,true,300);
            Yii::app()->cache->set('api_'.$site_id,true,300);
        }

        $this->site = CollectsWeb::model()->findByPk($site_id);
        if ($this->site == null) 
            $this->error_no = 101;
        else if($this->site->secretkey != $key)
            $this->error_no = 102;
        if($this->error_no > 0 ) {
            $this->outPut();
        }
        return true;
    }

    public function actionIndex()
    {
        $col_id = Yii::app()->request->getQuery('id', '0');
        $cids = Yii::app()->request->getQuery('category', '0');
        $p = Yii::app()->request->getQuery('p', '1')-1;
        $criteria = new CDbCriteria;
        $criteria->select = 'col_id,col_title,col_category,col_time,col_media,col_keywords,col_description,col_content,cid';
        if($col_id){
            $criteria->addCondition(' col_id > '.intval($col_id));
        }
        if($col_id == 0) {
            //$criteria->addCondition(" created > '".date("Y-m-d H:00:00",time()-3600)."'");
        }
        $cids = $this->checkCids($cids);
        if (!empty($cids)){
            $criteria->addInCondition(' cid ',$cids);
        }

        //获取数据
        $this->pageinfo['number'] = $this->limit;
        $this->pageinfo['total'] = $count = Contents::model()->count($criteria);
        $this->pageinfo['page'] = ceil($count/$this->limit);

        $criteria->limit = $this->limit;
        $criteria->offset = $this->limit*$p;
        $result = Contents::model()->findAll($criteria);
        if($result){
            $this->datas = array_map(function($record) { return $record->attributes; },$result);
        }

        $this->outPut();
    }

    /**
     * 检查分类ids是否存在
     */
    public function checkCids($cids)
    {
        //提取用户所分配的分类
        $criteria = new CDbCriteria;
        $criteria->addCondition('site_id = '.$this->site->id);
        $criteria->addCondition('status = 1');
        $result = CollectsAllot::model()->findAll($criteria);
        $category = array_map(function($record) { return $record->attributes; },$result);

        //检查用户所分配的分类
        $cid_in = array();
        if($cids){
            $cid_array = explode(',',$cids);
            foreach($cid_array as $cid) {
                $temp = Categories::getCatArray($cid);
                $temp = myFunc::fetchArray($category,'id',$cid);
                if($temp == false){
                    continue; 
                } 
                $cid_in[] = $cid;
            }
        }
        return $cid_in;
    }

    protected function outPut()
    {
        $type_function = 'make'.$this->type;
        echo $this->$type_function();
        Yii::app()->end();
    }

    protected function makejson()
    {
        if($this->error_no)
        {
            $datas = array(
                'error_no' => $this->error_no,
                'error_msg' => $this->message[$this->error_no],
            );
            return json_encode($datas);
        }
        $datas = array(
            'pages'=>$this->pageinfo,
            'data'=>$this->datas,
        );
        return json_encode($datas);
    }

    protected function makexml()
    {
        Yii::import('application.extensions.xmlgenerator.*');
        header ("Content-Type:text/xml");
        $xml=new XmlGenerator();
        // retrieve the latest 20 posts
        if($this->error_no) 
        {
            $xml->push('error');
            $xml->element('error_no', $this->error_no);
            $xml->element('error_msg', $this->message[$this->error_no]);
            $xml->pop();
            return $xml->getXml();
        }
        $xml->push('crawlers');
        $xml->push('pages');
        $xml->element('total', $this->pageinfo['total']);
        $xml->element('number',$this->pageinfo['number']);
        $xml->element('page',$this->pageinfo['page']);
        $xml->pop();
        $xml->push('data');
        if(!empty($this->datas)) foreach($this->datas as $k=> $item)
        {	        
            //$xml->push('item', array('n' => $item['col_id']));
            $xml->push('item', array('n' => ++$k));
            //$xml->element('col_url', $item['col_url']);
            $xml->element('col_id', $item['col_id']);
            $xml->element('col_title', $item['col_title']);
            $xml->element('col_time', $item['col_time']);
            $xml->element('col_media', $item['col_media']);
            $xml->element('col_category', $item['col_category']);
            $xml->element('col_keywords', $item['col_keywords']);
            $xml->element('col_description', $item['col_description']);
            $xml->element('col_content', $item['col_content']);
            $xml->pop();
        }
        $xml->pop();
        $xml->pop();
        return $xml->getXml();
    }
}
