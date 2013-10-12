<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class ACiiController extends CiiController
{
    public $role;
    public $push_uid;
	public $main_menu = array();
	public $sidebarMenu = array();
	
	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl'
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
			array('allow',  // allow authenticated admins to perform any action
				'users'=>array('@'),
				//'expression'=>'Yii::app()->user->role>=2'
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	
    public function admin_menu()
    {
    
        $this->main_menu = array(
            array(
                'label'=>'我的面板', 
                'icon'=>'home', 
                'url'=>$this->createUrl('/admin/'),
                'active'=>(Yii::app()->controller->uniqueID == 'admin/default' ? true : false)
            ),
            array(
                'label'=>'内容', 
                'icon'=>'book', 
                'url'=>$this->createUrl('/content/admin/'),
                'active'=>Yii::app()->controller->uniqueID == 'content/admin' ? true : false,
                //'items'=>$this->getTopCategories(),
            ),
            array(
                'label'=>'分类', 
                'icon'=>'th-list',
                'url'=>$this->createUrl('/category/admin'),
                'active'=>Yii::app()->controller->uniqueID == 'category/admin' ? true : false,
            ),
            array(
                'label'=>'采集', 
                'icon'=>'download-alt',
                'url'=>$this->createUrl('/crawler/collects'),
                'items'=>array(
                    array('label'=>'采集管理', 'url'=>array('/crawler/collects/node')),
                    array('label'=>'文章列表', 'url'=>array('/crawler/collects/list')),
                    array('label'=>'采集统计', 'url'=>array('/crawler/collects/states')),
                ),
                'active'=>$this->id == 'collects' ? true : false,
            ),
            array(
                'label'=>'用户管理', 
                'icon'=>'user', 
                'url'=>$this->createUrl('/admin/users'), 
                'active'=>$this->id == 'users' ? true : false,
            ),
            array(
                'label'=>'统计', 
                'icon'=>'stats', 
                'url'=>$this->createUrl('/content/stats'), 
                'active'=>$this->checkPathActive('content/stats'),
            ),
            array(
                'label'=>'站点', 
                'icon'=>'star', 
                'url'=>$this->createUrl('webset/admin'), 
                'active'=>$this->id == 'webset'?true : false,
                'items'=>array(
                    array('label'=>'站点列表', 'url'=>array('/crawler/webset/admin/')),
                    array('label'=>'分配列表', 'url'=>array('/crawler/webset/allot/')),
                ),
            ),
            array(
                'label'=>'设置', 
                'icon'=>'cog', 
                'url'=>$this->createUrl('/admin/settings/'), 
                'active'=>$this->id == 'settings' ? true : false,
            ),
        );
    }
    public function user_menu()
    {
        $role = $this->role;
        $this->main_menu = array(
            array(
                'label'=>'我的面板', 
                'icon'=>'home', 
                'url'=>$this->createUrl('/admin/default'),
                'active'=>(Yii::app()->controller->uniqueID == 'admin/default' ? true : false),
            ),
            array(
                'label'=>'推送', 
                'icon'=>'book', 
                'url'=>$this->createUrl('/content/default'), 
                //'active'=>(Yii::app()->controller->uniqueID == 'content/default' ? true : false),
                'active'=>$this->checkPathActive('content/default/'),
                'items'=>$this->getMySites(),
            ),
            array(
                'label'=>'设置', 
                'icon'=>'cog', 
                'url'=>$this->createUrl('/profile/edit/'), 
                'active'=>$this->checkPathActive('/profile/edit/'),
            ),
            array(
                'label'=>'统计', 
                'icon'=>'stats', 
                'url'=>$this->createUrl('content/default/stats/'), 
                'active'=>$this->checkPathActive('content/default/stats/'),
                'items'=>$this->getMySites(0,'content/default/stats/'),
            ),
            array(
                'label'=>'用户管理', 
                'icon'=>'user', 
                'url'=>$this->createUrl('/admin/users/'), 
                'active'=>$this->id == 'users' ? true : false,
                'visible'=>$role==4,
            ),
            array(
                'label'=>'网站', 
                'icon'=>'star', 
                'url'=>$this->createUrl('/webset/'), 
                'active'=>$this->id == 'ss'?true : false,
                'visible'=>$role==4,
            ),
        );
    }

    /**
     *  判读路径active
     */
    protected function checkPathActive($path)
    {
        $path = preg_replace("|/$|",'',$path);
        $n = substr_count($path,'/');
        $m = $this->module->id;
        $c = Yii::app()->controller->id;
        $a = Yii::app()->controller->getAction()->id;
        
        if($n == 0)
            $path .= '/default/index';
        if($n == 1)
            $path .= '/index';

        $path = str_replace("//","/",$path);
        $p = $m.'/'.$c.'/'.$a;
        return $path==$p;
    }

	public function beforeAction($action)
    {   
        $this->role = Yii::app()->user->role;
        $this->push_uid = isset(Yii::app()->user->push_uid)?Yii::app()->user->push_uid:0;
        if($this->role == 5){
            $this->admin_menu();
        }else{
            $this->user_menu();
        }
        return true;
    }

    public function isManager(){
        return (Yii::app()->user->role == 5);
    }
	
	public $params = array();
    
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	//public $layout='//dashboard';
    public $layout = 'application.views.layouts.dashboard';
	
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();

    public function getTopCategories()
    {

        $menu = array();
        $model = new Categories();
        $Categories = $model->getTopParents(); 
        foreach($Categories as $row){
            $menu[] = array(
                'label'=>$row['name'], 
                'icon'=>'th-list',
                'url'=>Yii::app()->createUrl('/content/admin/',array('slug'=>$row['slug'])),
            );
        }//foreach
        return $menu;
    }

    //检查user 权限
    protected function checkByOwnUser($user_id, $model = null)
    {
        if (Yii::app()->user->role == 5) return true;
        $check = true;
        if($model){
            $check = $model->parent_id == Yii::app()->user->id; 
        }else {
           $check = Boolean(Users::model()->findByAttributes(array('user_id'=>$user_id,'parent_id'=>Yii::app()->user->id))); 
        }

        if ($check == false){
			throw new CHttpException(404,'参数错误，此用户不是你的编辑用户');
        }
    }
    //检查site 权限
    protected function checkByOwnSite($site_id)
    {
        if (Yii::app()->user->role == 5) return true;
        $sites = $this->getMySites(1);
        $mysite = myFunc::fetchArray($sites,'id',$site_id);
        if ($mysite == null){
            //Yii::app()->user->setFlash('error', '参数错误，请回到你自己的站点');
			throw new CHttpException(404,'参数错误，请回到你自己的站点');
            exit;
        }
    }
    /**
     * type: 0,menu; 1,object
     */
    public function getMySites($type=0, $link = '/content/default/')
    {
        
        $mysites = array();
        if(isset(Yii::app()->session['mysites'])){
            $mysites = Yii::app()->session['mysites'];
        }else{
            $sites = CollectsWeb::getSites();
            if(count($sites)) foreach($sites as $site){
                if($site['uid'] != $this->push_uid) continue;
                $mysites[$site->id] = $site->attributes;
            }
            Yii::app()->session['mysites']=$mysites;
        }
        if($type == 0){
            $items = array();
            foreach($mysites as $site){
                $items[] = array(
                    'label'=>$site['my_site'],
                    'url'=>Yii::app()->createUrl($link,array('site_id'=>$site['id'])),
                );  
            }
            return $items;
        }else {
            return $mysites;
        }
    }

    /*
    public function getMySites($type=0)
    {
        $mysites = Yii::app()->cache->get('mysites');
        if($mysites == null){
            $sites = CollectsWeb::getSites();
            if(count($sites)) foreach($sites as $site){
                if($site['uid'] != $this->push_uid) continue;
                $mysites[$site->id] = $site->attributes;
            }
            Yii::app()->cache->set('mysites',$mysites);
        }
        if($type == 0){
            $items = array();
            foreach($mysites as $site){
                $items[] = array(
                    'label'=>$site['my_site'],
                    'url'=>Yii::app()->createUrl('/content/default/',array('site_id'=>$site['id'])),
                );  
            }
            return $items;
        }else {
            return $mysites;
        }
    }
     */
    public function getAllotsBySiteId($site_id)
    {
        $myallots = array();
        $Allots = CollectsAllot::getAllots();
        foreach($Allots as $obj){
                $allot = $obj->attributes;
                if($allot['site_id'] != $site_id) continue;
                $myallots[] = $allot;
        }
        return $myallots;
    }

    //检查网站分类资源是否可用
    public function checkAllotCategory($site_id,$category_id)
    {
        $info = CollectsAllot::model()->findByAttributes(array('site_id'=>$site_id,'category_id'=>$category_id));

        if($info->status == 0){
            Yii::app()->user->setFlash('info', '此分配资源不可再用，请跟管理员联系');
            return false;
        }
        return true;
    }


    public function getCatTitleModel($catid = null)
    {
       return new PubTitle(); 
    }

    public function getCatContentModel($catid = null)
    {
       return new PubContent(); 
    }

    public function getContentModel($catid = null)
    {
       return new Contents(); 
    }
}
