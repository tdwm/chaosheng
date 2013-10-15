<?php
class WebSetController extends ACiiController
{
    public $ptype = array('text'=>'输入框','multiplesel'=>'多选列表','select'=>'单选列表');
    public $charset = array('2'=>'GBK','1'=>'UTF8');
    public $allot_type = array('1'=>'数量','2'=>'时间');

    public function actions()
    {
        return array_merge(parent::actions(), array(
            'toggle' => array(
            'class'=>'bootstrap.actions.TbToggleAction',
            'modelName' => 'CollectsAllot',
            )
        ));
    }
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	//public $layout='//layouts/column2';

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
				'actions'=>array('create','update','paramitem','categorybysiteid'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete','allot','allotedit','allotdelete','toggle','secretkey'),
				//'users'=>array('admin'),
                'expression'=>'Yii::app()->user->role == 5',
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
	public function actionCreate($id = NULL)
    {
        if ($id == null) {
            $model=new CollectsWeb;
        } else {
            $model=$this->loadModel($id);

        }
        
        //检查权限
        $this->checkByOwnSite($id);
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['CollectsWeb']))
        {
            $model->attributes=$_POST['CollectsWeb'];
            $model->my_params = $data = array();
            if (!$this->isManager()){
                $model->uid = $this->push_uid;
            }
            $plabels = $_POST['plabels'];
            if (is_array($plabels)) foreach ($plabels as $k => $v) {
                if (empty($v) || empty($_POST['pnames'][$k])) continue;
                $data[] = array('name'=>$_POST['pnames'][$k], 'label'=>$v, 'type'=>$_POST['ptypes'][$k], 'value'=>$_POST['pvalues'][$k]);
            }
            $model->my_params = myFunc::array2string($data);
            if($model->save()){
                Yii::app()->user->setFlash('success','添加修改成功');
                unset(Yii::app()->session['mysites']);
                $this->redirect(array('/webset'));
            } else {
                Yii::app()->user->setFlash('error','添加修改失败');
            }
        }
        $model->my_params = myFunc::string2array($model->my_params);

        $this->render('create',array(
            'model'=>$model,
            'params'=>$model->my_params,
        ));
    }

    public function actionParamitem(){
        $item = $_POST;
        $item['id'] = time();
        $item = (object)$item;

		$this->renderPartial('_paramitem',array(
			'item'=>$item,
		));
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


		$this->render('create',array(
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
        $model=new CollectsWeb('usersearch');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['CollectsWeb']))
			$model->attributes=$_GET['CollectsWeb'];

		$this->render('index',array(
			'model'=>$model,
		));
	}
	public function actionAdmin()
	{
        $model=new CollectsWeb('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['CollectsWeb']))
			$model->attributes=$_GET['CollectsWeb'];

		$this->render('admin',array(
			'model'=>$model,
		));
    }

	/**
	 * Manages all models.
	 */
	public function actionAllot()
	{
        $model=new CollectsAllot('search');
		$model->unsetAttributes();  // clear any default values
        if(isset($_GET['CollectsAllot']))
            $model->attributes=$_GET['CollectsAllot'];
		$this->render('allot',array(
			'model'=>$model,
            'allot_type'=>$this->allot_type ,
		));
	}

    public function actionCategoryBySiteId()
    {
        $site_id = $_POST['site_id'];
        //检查权限
        $this->checkByOwnSite($site_id);

        $haveAllot = $this->haveAllot($site_id);
        $this->renderPartial('_ajax_category',array('haveAllot'=>$haveAllot,'site_id'=>$site_id));
    }

	public function actionAllotEdit($id = null)
	{
        if ($id == null) {
            $site_id = $_GET['site_id'];
            $model = new CollectsAllot();
            $site_name = CollectsWeb::model()->findByPk($site_id)->my_site;
            $model->site_id = $site_id;
        } else {
            $model=CollectsAllot::model()->with('site')->findByPk($id);
            $site_name = $model->site->my_site;
            $site_id = $model->site->id;
        }
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		if(isset($_POST['CollectsAllot']))
		{
			$model->attributes=$_POST['CollectsAllot'];
            if($model->save()){
				Yii::app()->user->setFlash('success', '修改成功.');
				$this->redirect(array('allot'));
            }
		}

        $haveAllot = $this->haveAllot($site_id,true);

		$this->render('allotedit',array(
			'model'=>$model,
            'site_name'=>$site_name,
            'haveAllot'=>$haveAllot,
		));
	}

    //已经分配的分类资源
    public function haveAllot($site_id, $status = false)
    {
        $criteria = new CDbCriteria;
        $criteria->addCondition('site_id = '.$site_id);
        if ($status == false)
            $criteria->addCondition('status = 1');
        $criteria->with = array('category'=>array('select'=>'name,slug'));
        return CollectsAllot::model()->findAll($criteria);
    }

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=CollectsWeb::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='collects-web-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

    public function actionSecretkey($id)
    {
        $key = uniqid();
        CollectsWeb::model()->updateByPk($id,array('secretkey'=>$key));
        echo $key;
        Yii::app()->end();
    }
}
