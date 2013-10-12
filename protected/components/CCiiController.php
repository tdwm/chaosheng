<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class CCiiController extends CiiController
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
	
	public function beforeAction($action)
	{
        $role = Yii::app()->user->role;
        $this->role = Yii::app()->user->role;
        $this->push_uid = Yii::app()->user->push_uid;
        $this->main_menu = array(
            array(
                'label'=>'内容', 
                'icon'=>'book', 
                'url'=>$this->createUrl('/content/default'), 
                'active'=>$this->id == 'content' ? true : false,
                'visible'=>$role<5,
            ),
            array(
                'label'=>'用户', 
                'icon'=>'user', 
                'url'=>$this->createUrl('/admin/users/'), 
                'active'=>$this->id == 'users' ? true : false,
                'visible'=>$role==4,
            ),
            array(
                'label'=>'设置', 
                'icon'=>'cog', 
                'url'=>$this->createUrl('/webset/'), 
                'active'=>$this->id == 'webset'?true : false,
                'visible'=>$role==4,
            ),
        );
        return true;
    }

    public function isManager(){
        return Yii::app()->user->role == 4;
    }

    public function pushUid()
    {
        return Yii::app()->user->push_uid;
    }
	
	public $params = array();
    
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='dashboard';
	
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

    public function getCatTitleModel($catid)
    {
       return new PubTitle(); 
    }

    public function getCatContentModel($catid)
    {
       return new PubContent(); 
    }
}
