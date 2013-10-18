<?php
class CollectsController extends ACiiController
{
    public  $spider_funs = array(
        'trim'=>'过滤空格',
        'spider_photos'=>'处理为组图',
        'spider_downurls'=>'处理为下载列表',
        'spider_keywords'=>'获取关键字',
    );
    public  $node_field = array(
        'title'=>'标题', 
        'author'=>'作者',
        'keywords'=>'关键字',
        'media'=>'媒体',
        'time'=>'时间',
        'content'=>'内容'
    );
    public $push_status = array(1=>"未导入",2=>"已导入",4=>'采集中...',5=>'采集错误',6=>'入库错误',0=>"未采集");
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */

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
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','node','colurllist','colcontent','pushcontent','pushprogram','programcreate','programsave','programdelete','testcolurl','testcolcontent','importcontent','list','recolcontent','getcolfield','coldelete','savecrawlerfile'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate($id = null)
    {
        if ($id == null) {
            $model = new CollectsNode;
        } else {
            $model=$this->loadModel($id);
        }


        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['CollectsNode']))
        {
            $data = $_POST['CollectsNode'];
            $data['urlpage'] = isset($_POST['urlpage_'.$data['sourcetype']]) ? $_POST['urlpage_'.$data['sourcetype']] :'';
            $data['customize_config'] = array();
            $customize_config = $_POST['customize_config'];
            if (is_array($customize_config)) foreach ($customize_config['en_name'] as $k => $v) {
                if (empty($v) || empty($customize_config['name'][$k])) continue;
                $data['customize_config'][] = array('name'=>$customize_config['name'][$k], 'en_name'=>$v, 'rule'=>$customize_config['rule'][$k], 'html_rule'=>$customize_config['html_rule'][$k]);
            }
            if($data['urlcrawlbyfile'] || $data['contentcrawlbyfile']){
                $model->makeCrawlerFile($data['sign']);
            }
            $data['customize_config'] = myFunc::array2string($data['customize_config'],0);
            $model->attributes=$data;
            if($model->save()){
                Yii::app()->user->setFlash('success','添加修改成功');
                $this->redirect(array('node'));
            } else {
                Yii::app()->user->setFlash('error','添加修改失败');
            }
        } 

        if($id == null) {
            $model->coll_order = 1;
            $model->down_attachment = 1;
        }
        $model->customize_config = myFunc::string2array($model->customize_config );
        $json_customize = json_encode($model->customize_config) ;

        $crawlerContent = $model->getCrawlerFile($model->sign,'content');
        $crawlerUrl = $model->getCrawlerFile($model->sign,'url');
        $this->render('create',array(
            'model'=>$model,
            'crawlerContent'=>$crawlerContent,
            'crawlerUrl'=>$crawlerUrl,
            'json_customize'=>$json_customize,
        ));
    }

    public function actionMakeCrawlerFile($sign)
    {

    
    }

    //预览采集网址
    public function actionTestcolurl($id){
        $data = $this->loadModel($id);
        $urls = collection::url_list($data);
        $url = array_pop($urls);
        $urllist = collection::get_url_lists($url, $data);
        foreach($urllist as $k=>$v){
            $urllist[$k]['id'] = $k+1;
        }
        $gridDataProvider = new CArrayDataProvider($urllist,array('pagination'=>array( 'pageSize'=>20)));
        $this->render('testurl',array(
            'providerData'=>$gridDataProvider,
            'nodeid'=>$id,
        ));
    }
    //获取data
    public function actionGetColField($id,$field = null){
        $result = '';
        $content = CollectsContent::model()->findByPk($id);
        if (!is_array($field)) {
            $field = array($field);
        }
        foreach($field as $f) {
            if ($f == 'data') {
                $data = myFunc::string2array($content->data);
                if(!is_array($data)) {
                    $result .= $data;
                    continue;
                }
                foreach($data as $k=> $v){
                    $result.= "<div><h5>$k</h5>$v</div>";
                }
            }else{
                $result.= "<div><h5>$f</h5>{$content->$f}</div>";
            }

        }
        if($_GET['ajax']) {
            echo $result;
        }else {
            return $result;
        }
    }

    //采集url
    public function actionColurllist($id){
        $data = $this->loadModel($id);
        if($data == null)
            throw new CHttpException(400,'参数错误.');
        $urls = collection::url_list($data);
        $total_page = count($urls);
        if ($total_page > 0) {
            $url = collection::get_url_lists($urls, $data);
            $total = count($url);
            $re = 0;
            if (is_array($url) && !empty($url)) foreach ($url as $v) {
                if (empty($v['url']) || empty($v['title'])) continue;
                $v['title'] = strip_tags($v['title']);
                //判断
                if($this->redundance($v,$id)) {
                    $re++;
                    continue;
                }
                //入库
                $transaction = Yii::app()->db->beginTransaction();
                try{
                    $signurlmode = new CollectsSignUrl();
                    $signurlmode->md5 = md5($v['url']);
                    $signurlmode->nodeid = $id;
                    $signurlmode->save();

                    $signtitlemode = new CollectsSignTitle();
                    $signtitlemode->md5 = md5($v['title']);
                    $signtitlemode->nodeid = $id;
                    $signtitlemode->save();

                    $contentmode = new CollectsContent();
                    $contentmode->url = $v['url'];
                    $contentmode->title = $v['title'];
                    $contentmode->nodeid = $id;
                    $contentmode->save();

                    $transaction->commit();
                    //exit;
                } catch(Exception $e){
                    $transaction->rollback(); 
                   // throw new CHttpException(400,'采集入库失败.');
                }
            }
            $show_header = $show_dialog = true;
            if ($total_page <= $page) {
                $data->lastdate = new CDbExpression('NOW()');
                $data->save();
            }
            Yii::app()->user->setFlash('success',"采集成功,共采集网址{$total}个,重复{$re}个,入库".($total-$re)."个");
            $this->redirect(array('node'));
        } else {
            throw new CHttpException(400,'没有采集.');
        }
    }


    //获取文章内容
    function actionColcontent($id){
        $data = $this->loadModel($id);
        //更新附件状态
        $attach_status = false;
        /*
        if(pc_base::load_config('system','attachment_stat')) {
            $this->attachment_db = pc_base::load_model('attachment_model');
            $attach_status = true;
        }
         */
        $size = 2;
        $page = isset($_GET['page']) ? intval($_GET['page']) : 0;
        $total = isset($_GET['total']) ? intval($_GET['total']) : 0;
        if (empty($total)) $total = CollectsContent::model()->count('nodeid=:nodeid and status=:status',array(':nodeid'=>$id, ':status'=>0));
        $total_page = ceil($total/$size);
        $criteria = new CDbCriteria;
        $criteria->select = 'id,url';
        $criteria->addCondition("nodeid=".$id);
        $criteria->addCondition("status=0");
        $criteria->limit = $size;
        $list = CollectsContent::model()->findAll($criteria);
        //echo $total;
        //echo (2+(2-1)*2)/$total*100;exit;
        //$list = array();
       // $list[] = array('url'=>'http://fashion.sina.com.cn/s/ce/2013-08-05/065918681.shtml','id'=>1);
        $i = 0;
        if (!empty($list) && is_array($list)) {
            foreach ($list as $v) {
                $GLOBALS['downloadfiles'] = array();
                if ($data->contentcrawlbyfile) {
                    $html = collection::get_content_file($v['url'], $data);
                } else {
                    $html = collection::get_content($v['url'], $data);
                }
                //更新附件状态
                if($attach_status) {
                    //$this->attachment_db->api_update($GLOBALS['downloadfiles'],'cj-'.$v['id'],1);
                }
                $contentModel=CollectsContent::model()->findByPk($v['id']);
                $contentModel->data = myFunc::array2string($html);
                $contentModel->status = 1;
                $contentModel->save();
                $i++;
            }
        } else {
            Yii::app()->user->setFlash('success',"采集成功,共采集内容{$total}个");
            $this->redirect(array('node'));
        }
        if ($total_page > $page) {
            $this->render('progress',array(
                'model'=>$data,
                'percent'=>($i+($page-1)*2)/$total*100,
                'colnum'=>$i+($page-1)*2,
                'page'=>$page+1,
                'total'=>$total,
                'url'=>Yii::app()->createUrl("/crawler/collects/colcontent/id/" . $data->nodeid),
            ));
        } else {
            $data->updateByPk($id,array('lastdate' => new CDbExpression('NOW()'))); 
        }
    }

    //预览采集内容
    public function actionTestColContent(){
        $url = $_REQUEST['url'];
        $id = $_REQUEST['id'];
        $data = $this->loadModel($id);
        if ($data->contentcrawlbyfile || $_REQUEST['filetest']==1) {
            $html = collection::get_content_file($url, $data);
        } else {
            $html = collection::get_content($url, $data);
        }
        foreach($html as $k=> $v){
            echo "<div><h5>$k</h5>$v</div>";
        }
    }
    //采集单个内容
    function actionReColContent($id, $node = null){
        $content = CollectsContent::model()->findByPk($id);
        if ($node == null) {
            $node = $this->loadModel($content->nodeid);
        }
        if ($node->contentcrawlbyfile) {
            $html = collection::get_content_file($content->url, $node);
        } else {
            $html = collection::get_content($content->url, $node);
        }
        //var_dump($html);exit;
        $content->status = 1;
        if($html['content']=='' || $html['title']=='') $content->status = 5;
        //更新附件状态
        $content->data = myFunc::array2string($html);

        try{
            if ($content->save()){
                Yii::app()->user->setFlash('success','采集成功');
                echo true;
            }else {
                Yii::app()->user->setFlash('error','采集失败');
                echo false;
            }
        }catch(Exception $e) {
            Yii::app()->user->setFlash('error','采集失败');
            echo false;
        }
    }


    function actionList(){
		$model=new CollectsContent('search');
		$model->unsetAttributes();  // clear any default values
        if(isset($_GET['CollectsContent'])){
			$model->attributes=$_GET['CollectsContent'];
        }
        $model->pageSize =20;
            
		$this->render('push',array(
			'model'=>$model,
            'push_status'=>$this->push_status,
		));
    }

    function actionPushcontent($id){
        $page = isset($_GET['page'])?$_GET['page']:1;
		$model=new CollectsContent('search');
        $model->pageSize =20;
		$model->unsetAttributes();  // clear any default values
        if(isset($_GET['CollectsContent'])){
			$model->attributes=$_GET['CollectsContent'];
        }
            $model->nodeid = $id;
            
		$this->render('push',array(
			'model'=>$model,
            'push_status'=>$this->push_status,
		));
    }

    function actionpushprogram($id)
    {

        $page = isset($_GET['page'])?$_GET['page']:1;
        $model = new CollectsProgram();
		if(isset($_GET['CollectsProgram']))
			$model->attributes=$_GET['CollectsProgram'];

        $criteria = new CDbCriteria;
        $criteria->select = 'id,name,nodeid,catid';
        $criteria->addCondition("nodeid=".$id);
        $criteria->with = array('category'=>array('select'=>'name'));
		$criteria->compare('id',$model->id,true);
		$criteria->compare('t.name',$model->name,true);
        $criteria->limit = 20;
        $criteria->offset = 20*($page-1);

        $data =new CActiveDataProvider($model,array(
            'criteria'=>$criteria,
            'pagination'=>array(
                'pageSize'=>20,
            ),
        ));
        $program = new CollectsProgram();

		$this->render('program',array(
			'programdata'=>$data,
			'model'=>$model,
			'program'=>$program,
            'nodeid'=>$id,
		));
    }

    function actionProgramCreate($nodeid=null, $pid=null)
    {
        if ($pid ) {
            //获取默认配置
            $program = CollectsProgram::model()->findByPk($pid);
            $config = myFunc::string2array($program->config);
            $catid = $program->catid;
            $nodeid = $program->nodeid;
        } else {
            if (!isset($_GET['CollectsProgram']) || $_GET['CollectsProgram']['catid']==0 || empty($_GET['CollectsProgram']['name'])){
                Yii::app()->user->setFlash('error','请添加名称或者选择分类');
                $this->redirect(Yii::app()->request->urlReferrer);
            }
            $catid = $_GET['CollectsProgram']['catid'];
            //获取默认配置
            $program = new CollectsProgram();
            $config = $program->init_config;
            $program->name = $_GET['CollectsProgram']['name'];
            $program->catid = $catid;
        }

        //获取采集字段
        $node = CollectsNode::model()->findByPk($nodeid);
        if ($node->contentcrawlbyfile) {
            $node_field = CollectsNode::getCrawlerFileds($node->sign); 
        } else  {
            $customize_config = myFunc::string2array($node->customize_config);
            $node_field = $this->node_field;
            if (is_array($customize_config)) foreach ($customize_config as $k=>$v) {
                if (empty($v['en_name']) || empty($v['name'])) continue;
                $node_field[$v['en_name']] = $v['name'];
            }
        }


        //获取分类名称
        $category = Categories::getCatArray($catid);

        //获取此分类数据库结构
        $contents = $this->getContentModel($catid);
        $attributes = $contents->canpush();
        /*
        $attributes = array_merge($catTitle->attributeLabels(), $catContent->attributeLabels());
        foreach($attributes as $k=>$v) {
            if(strpos($k,'col_')===false || $k == 'col_id') unset($attributes[$k]);
        }
         */

		$this->render('programcreate',array(
			'node'=>$node,
			'nodeid'=>$nodeid,
			'program'=>$program,
			'config'=>$config,
			'category'=>$category,
			'attributes'=>$attributes,
			'node_field'=>$node_field,
			'spider_funs'=>$this->spider_funs,
		));
    }

    public function actionProgramSave($nodeid)
    {
        $config = $funs = $map = array();
        $data = $_POST['CollectsProgram'];
        $selmap = $_POST['selconfig'];
        $selfuns = $_POST['selfuns'];
        $config = $_POST['config'];

        foreach($selmap as $k=>$v){
            if(empty($v)) continue; 
            $map[$k] = $v;
        }
        if(empty($map)){
            Yii::app()->user->setFlash('error','你没有填写任何匹配规则');
            $this->redirect(Yii::app()->request->urlReferrer);
        }
        $config['map'] = $map;
        if(!isset($config['add_introduce'])){
            $config['add_introduce'] = 0;
        }
        $config['introcude_length'] = intval($config['introcude_length']);
        foreach($selfuns as $k=>$v){
            if(!isset($map[$k]) || empty($v)) continue; 
            $funs[$k] = $v; 
        }
        if(!empty($funs)){
            $config['funs'] = $funs;
        }
        if ($data['id']>0){
            $program = CollectsProgram::model()->findByPk($data['id']);
        }else{
            $program = new CollectsProgram();
        }
        unset($program->attributes);
        $program->name = $data['name'];
        $program->catid = $data['catid'];
        $program->nodeid = $nodeid;
        $program->config = myFunc::array2string($config);
        if( $program->save()){
            Yii::app()->user->setFlash('success','操作成功');
            $this->redirect(array('/crawler/collects/pushprogram/','id'=>$nodeid));
        }
    }

    function actionProgramDelete($id)
    {
        if(Yii::app()->request->isPostRequest)
        {
            // we only allow deletion via POST request
            $program = CollectsProgram::model()->findByPk($id)->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if(!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        }
    }

    function actionColDelete($id)
    {
        if(Yii::app()->request->isPostRequest)
        {
            // we only allow deletion via POST request
            $deletecontent = CollectsContent::model()->findByPk($id)->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if(!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        }
        
    }

    function actionImportContent($pid)
    {
        $program = CollectsProgram::model()->findByPk($pid);
        if(!isset($program->config['map'])) {
            return false;
        }
        $config = myFunc::string2array($program->config);
        $node = CollectsNode::model()->findByPk($program->nodeid);

        $contentmodel = $this->getContentModel($program->catid);

        $push = $contentmodel->canpush();

        //$colcontent = CollectsContent::model()->findByAttributes(array('nodeid'=>$program->nodeid,'status'=>1));
        $colcontents = CollectsContent::model()->findAll("nodeid = :nodeid and status = :status",array(
            ':nodeid'=>$program->nodeid,':status'=>1
        ));
        foreach($colcontents as $k => $colcontent) {
            //测试
            if($k == 5) break;
            $data = myFunc::string2array($colcontent['data']);

            $contentmodel->cid = $program->catid;
            $contentmodel->col_id = $colcontent->id;
            $contentmodel->col_url = $colcontent->url;
            $contentmodel->slug = md5($colcontent->url);
            foreach($config['map']  as $field=>$ck){
                //以后处理ck
                $value = trim($data[$ck]);
                if(array_key_exists($field,$push)) {
                    $contentmodel->$field = $value;
                }
                if($config['add_introduce'] == 1 && $ck == 'content'){
                    $contentmodel->col_description = mb_substr(strip_tags($value),0,$config['introcude_length']*2,'UTF-8');
                }
            }

            if($contentmodel->save() ){
                echo "yes:". $colcontent->id."<br>\n";
                $colcontent->status = 2;
                $colcontent->save(); 
            }else{
                echo "no:". $colcontent->id."<br>\n";
                $contentmodel->delete(array('col_id'=>$colcontent->id));
            }
        }
    }

    //判读重复
    function redundance($data,$id){
        if(isset($data['url'])){
            $have = CollectsSignUrl::model()->findByAttributes(array('md5'=>md5($data['url']),'nodeid'=>$id)); 
            if ($have) return true;
        } 
        if(isset($data['title'])){
            $have = CollectsSignTitle::model()->findByAttributes(array('md5'=>md5($data['title']))); 
            if ($have) return true;
        }
        if(isset($data['content'])){
            // $have = CollectsSignTitle::model()->findByAttributes('md5'=>md5($data['url'])); 
            // if ($have) return true;
        }
        return false;
    }
    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model=$this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['CollectsNode']))
        {
            $model->attributes=$_POST['CollectsNode'];
            if($model->save())
                $this->redirect(array('view','id'=>$model->nodeid));
        }

        $this->render('update',array(
            'model'=>$model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        if(Yii::app()->request->isPostRequest)
        {
            // we only allow deletion via POST request
            $this->loadModel($id)->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if(!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        }
        else
            throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $dataProvider=new CActiveDataProvider('CollectsNode');
        $this->render('index',array(
            'dataProvider'=>$dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionNode()
    {
        $model=new CollectsNode('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['CollectsNode']))
            $model->attributes=$_GET['CollectsNode'];

        $this->render('node',array(
            'model'=>$model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id)
    {
        $model=CollectsNode::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if(isset($_POST['ajax']) && $_POST['ajax']==='collects-node-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionSaveCrawlerFile()
    {
        $code = trim($_POST['code']);
        $sign = $_GET['sign'];
        $file = $_GET['file'];
        $model = new CollectsNode;
        echo $model->saveCrawlerFile($sign,$file,$code);
    }
}
