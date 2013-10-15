<?php
// $ifpush = array(0=>'未推送',1=>'正在推送',2=>'已推送',3=>'不推送');
class DefaultController extends ACiiController
{
    public $catHtml;
    public $user_categories;
    public $site_id;
    public $mysite;
    public $slug;
    public $pushstatus = 1;

    public $defaultAction = 'index';

	public function actionPush($col_id)
    {
        //网站session_id
        $site_id = Yii::app()->session['site_id'];
        $sites = $this->getMySites(1);
        $mysite = myFunc::fetchArray($sites,'id',$site_id);
        if(empty($mysite)){
            Yii::app()->user->setFlash('warning', ' 参数错误 ');
            $this->redirect(Yii::app()->request->urlReferrer );
        }
        //站点参数
        $my_params = myFunc::string2array($mysite['my_params']);

        //已经pushed信息
        $edit_key = "edit_".$push_uid;
        $editnow = Yii::app()->cache->get($edit_key);
        $pushed = Yii::app()->cache->get("pushed_".$this->push_uid);

        if (!is_array($editnow) || array_search($id, $editnow) === false) {
            $editnow[]=$id;
            Yii::app()->cache->set($edit_key, $editnow, 300);
        }
        //var_dump($editnow);

        //model
        $content_model = $this->getContentModel();

    

        //获取数据
        $content = $content_model->findByPk($col_id);
        if($content == null) {
            Yii::app()->user->setFlash('warning', ' 参数错误 ');
            $this->redirect(Yii::app()->request->urlReferrer );
        }

        //设定pushed 参数
        $pushed_base = array(
            'col_id'=>$content->col_id,
            'site_id'=>$site_id,
        );
        //查找是否已经有记录
        $pushed = $this->getPushedInDB($pushed_base);

        $pushed_other = array(
            'status'=> $this->pushstatus,
            'user_id'=> Yii::app()->user->id,
            'category_id'=>$content->cid,
        );

        if ($pushed == null) {
            $attributes = array_merge($pushed_base, $pushed_other);
            $pushed = new CollectsPush;
            $pushed->attributes = $attributes;
            $pushed->save();
        }

        if(isset($_POST['Contents']))
        {
            //整理数据
            $push['api_url']  =  $mysite['api_url'];
            $push['charset']  =  $mysite['my_charset'];
            $push['data'] = array(
                'site_id'  =>  $site_id,
                'col_id'  =>  $content->col_id,
                'col_title' =>  $_POST['Contents']['col_title'],
                'col_media' =>  $_POST['Contents']['col_media'],
                'col_keywords'    =>  $this->SBC_DBC($_POST['Contents']['col_keywords']),
                'col_description' =>  $_POST['Contents']['col_description'],
                'col_content'     =>  $_POST['Contents']['col_content'],
            );
            foreach($_POST as $k => $v ){
                if(strstr($k,'params_')){
                    $key = substr($k,7);
                    if(is_array($v)) $v = implode(',',$v);
                    $push['data'][$key] = $v;
                }
            }
            //发送数据 
            if(myFunc::pushData($push)) 
            {
                $pushed->status = 2;
                if($pushed->save() == false )
                    Yii::app()->user->setFlash('error', '记录失败');

                if(isset($_POST['pushnext'])){
                    $next = $this->getNext($content->cid);
                    if($next == null) {
                       $this->redirect(Yii::app()->user->getReturnUrl());
                    } else {
                       $this->redirect($this->createUrl('push',array('col_id'=>$next->col_id)));
                    }
                }else {
                    $this->redirect(Yii::app()->user->getReturnUrl());
                }
            }
            else
            {
                foreach( $_POST['Contents'] as $k => $v){
                    $content->$k = $v;
                }
                foreach($my_params as $k => $v ){
                    $key = 'params_'.$v['name'];
                    if(array_key_exists($key,$_POST)){
                        $my_params[$k]['default']  = $_POST[$key];
                    }
                }
            }
        }
        $this->render('push',array(
            'Contents'       =>  $content,
            'col_id'             =>  $col_id,
            'my_params'      =>  $my_params,
        ));
    }

    private function SBC_DBC($str) { 
        $str = trim(iconv('utf-8', 'gbk', $str));
        $str = preg_replace('/\xa3([\xa1-\xfe])/e', 'chr(ord(\1)-0x80)', $str);
        $str = trim(iconv( 'gbk', 'utf-8', $str)); 
        return $str; 
    }
	

	
	/**
	 * 忽略内容
	 */

    function actionIgnore($col_id, $category_id = 0)
    {
        $site_id = Yii::app()->session['site_id'];
        $pushed_base = array(
            'col_id'=>$col_id,
            'site_id'=>$site_id,
        );
        //查找是否已经有记录
        $pushed = $this->getPushedInDB($pushed_base);

        $pushed->status = 3;
        $pushed->category_id = $category_id;
        $pushed->user_id = Yii::app()->user->id;
        $pushed->save();

        $next = $this->getNext($category_id);
        
        if($next == null) {
            $this->redirect(Yii::app()->user->getReturnUrl());
            //$this->redirect($this->createUrl('index',array('site_id'=>$site_id,'col_id'=>$next->col_id)));
        } else {
            $this->redirect($this->createUrl('push',array('id'=>$next->id,'col_id'=>$next->col_id)));
        }
    }



    /**
     * 获取下一个推送
     */
    private function getNext($category_id = 0)
    {
        
        $site_id = Yii::app()->session['site_id'];
        $contents = $this->getContentModel();
        $tableName = $contents->tableName();
        $sql = "
            SELECT t.cid,t.col_id 
            FROM  (select cid,col_id from `$tableName` where cid='$category_id' order by col_id desc) AS t
            LEFT JOIN (select col_id FROM collects_push where site_id = '$site_id') AS p ON t.col_id = p.col_id 
            where  p.col_id IS NULL 
            limit 1 ";
        return CollectsPush::model()->findBySql($sql);
    }
	/**
	 * Displays a CMarkDownParser preview of the content to be displayed
	 */
	public function actionPreview()
	{
		$md = new CMarkdownParser();
		$this->renderPartial('preview', array('md'=>$md, 'data'=>$_POST));
	}
    
	/**
	 * Default management page
     * Display all items in a CListView for easy editing
	 */
	public function actionIndex($site_id)
	{
        $this->user_categories = array();

        $session_site = Yii::app()->session['site_id'];
        if($session_site != $site_id){
            Yii::app()->session['site_id']=$site_id;
        }
        $this->site_id = $site_id; 
        $sites = $this->getMySites(1);
        $this->mysite = myFunc::fetchArray($sites,'id',$site_id);
        //检查是否属于自己的站点
        if ($this->mysite == null){
            Yii::app()->user->setFlash('error', '参数错误，请回到你自己的站点');
            $this->render('index');
            exit;
        }
        //检查是否分配资源
        $allots = $this->getAllotsBySiteId($site_id);
        if($allots == null){
            Yii::app()->user->setFlash('info', '你没有分配任何资源');
            $this->render('index');
            exit;
        }
        $this->layout = "colletsContent";
        $this->user_categories = $this->getCategoriesByAllots($allots);


		$criteria=new CDbCriteria;

        if(isset($_GET['slug']) && $slug = $_GET['slug'] ){
            $this->slug = $slug;
            $category = Categories::findBySlug($slug);
            if($category == null) {
                Yii::app()->user->setFlash('info', '你没有分配任何资源');
                $this->render('index');
                exit;
            }
            //检查此分类是否还可以使用
            if($this->checkAllotCategory($site_id, $category['id']) == false)
            {
                $this->render('index');
                exit;
            }
            $criteria->addCondition('cid ='.$category['id']);
        }else{
            $catids = array();
            foreach($allots as $allots){
                $catids[] = $allot['category_id'];
            }
            $criteria->addCondition('cid ', $catids);
        }

        $model = $this->getContentModel();
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Contents']))
			$model->attributes=$_GET['Contents'];
		$criteria->select = 'col_id,col_title,col_category,col_time,col_media';
		$criteria->compare('col_title',$model->col_title,true);
		$criteria->compare('col_media',$model->col_media,true);
		$criteria->compare('status',$model->status);
		$criteria->order = 'col_id desc';

		$titles = new CActiveDataProvider($model, array(
			'criteria'=>$criteria,
            'pagination' => array(
                'pageSize' => 20
            )
		));
        //$this->catHtml = Categories::model()->getTreeHtml($userCategories['cats'],0 );
        //var_dump($this->catHtml);exit;
        $viewFile = 'index_4';

        $nurl = Yii::app()->request->requestUri;
        Yii::app()->user->setReturnUrl($nurl);

        //var_dump(Yii::app()->request);
        //echo $nurl;

		$this->render($viewFile, array(
			'model'=>$model,
			'site_id'=>$site_id,
			'titles'=>$titles,
			'user_categories'=>$this->user_categories,
            'categoryslug'=>Yii::app()->session['admin_cslug'],
		));
	}

    function actionStats($site_id,$user_id = null) 
    {
        $userlist = array();

        $sites = $this->getMySites(1);
        $this->mysite = myFunc::fetchArray($sites,'id',$site_id);
        //检查是否属于自己的站点
        if ($this->mysite == null){
            Yii::app()->user->setFlash('error', '参数错误，请回到你自己的站点');
            $this->render('index');
            exit;
        }


        if(Yii::app()->user->role == 1 || empty($user_id) ){
            $user_id = Yii::app()->user->id ;
        }
        $user = Users::model()->findByPk($user_id);
        
        if(Yii::app()->user->role == 4){
            $items = Users::model()->findAllByAttributes(array('parent_id'=>Yii::app()->user->push_uid));
            foreach ($items as $item){
                $userlist[] = array(
                    'label'=>$item->displayName,
                    'url'=>$this->createUrl('stats',array('site_id'=>$site_id,'user_id'=>$item->id)),
                    'active' => $item->id == $user_id ? true : false,
                );
            }
        }


        $month = date('Y-m');
        if($_GET['month']) $month = $_GET['month'];
        $month = date('Y-m',strtotime($month));
        
        $criteria = new CDbCriteria;
        $criteria->addCondition("user_id='$user_id'");
        $criteria->addCondition("date_format(`daytime`,'%Y-%m')='".$month."'");
        $criteria->addCondition("site_id='$site_id'");
        $criteria->addCondition("status=2");
        $criteria->with = array('mysite'=>array('select'=>'my_site'));
        
		$stats = new CActiveDataProvider('StatUserpush', array(
			'criteria'=>$criteria,
            'pagination' => array(
                'pageSize' => 31,
            )
		));

		$this->render('userstats', array(
			'stats'=>$stats,
			'site_id'=>$site_id,
			'month'=>$month,
            'user'=>$user,
            'userlist'=>$userlist,
		));

    }


    
    function getCategoriesByAllots($allots)
    {
        if(!is_array($allots)|| empty($allots)) return array();
        foreach($allots as $allot)
        {
                $catids[] = $allot['category_id'];
        }
        $categories = Categories::getCategoryCaches();
        $menus = array();
        foreach($categories as $category){
            if(in_array($category['id'],$catids)){
                $mycategories[] = $category;
               // $menus[] = Categories::getDeepById($category['id']);
                //$menus = array_merge(Categories::getDeepById($category['id']),$menus);
            }
        }
        return $mycategories;
    }


	public function loadModel($id)
	{
		$model=Content::model()->findByAttributes(array('id'=>$id));
		//$model=Content::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='Content-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

    //
    // $ifpush = array(0=>'未推送',1=>'正在推送',2=>'已推送',3=>'不推送');
    //
    protected function makePushed($id,$attributes)
    {
        $pushed = new CollectsPush;
        unset($pushed->attributes);
        $pushed->attributes = $attributes;
        if( $pushed->save()){
            return $pushed->attributes['id'];
        } else {
            return 0;
        }
    }

    /*
     * 根据条件获取pushed
     */
    protected function getPushedInDB($attr)
    {
        return CollectsPush::model()->findByAttributes($attr);
    }
    


}
