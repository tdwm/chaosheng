<?php
class statsController extends ACiiController
{

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
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('index'),
				//'users'=>array('admin'),
                'expression'=>'Yii::app()->user->role == 5',
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex($site_id)
	{
        $type = isset($_GET['type']) ?$_GET['type']:'';
        $menu = array(
            array(
                'label' => "站点统计",
                'url' => $this->createUrl('index',array('type'=>'site')),
                'active' => 'site'==$type,
            ),
            array(
                'label' => "站点统计",
                'url' => $this->createUrl('index',array('type'=>'site')),
                'active' => 'site'==$type,
            ),
        );

        $day = date("Y-m-d");

		$this->render('index',array(
			'model'=>$model,
		));
	}

    public function actionStat()
    {
    
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

    public function siteToday($site_id, $day)
    {
        $sql = "SELECT COUNT( id ) AS post_num, site_id, DATE_FORMAT( created,  '%Y-%m-%d' ) AS 
            DAY FROM collects_push
            WHERE site_id =  '$site_id'
            AND DATE_FORMAT( created,  '%Y-%m-%d' ) =  '$day' ";

    }

    /**
     *  站点月份所有天的统计
     */
    public function siteByMonth($site_id,$month)
    {
        $sql = " SELECT COUNT(id) AS post_num, DATE_FORMAT(created,'%Y-%m-%d') AS 
            DAY ,site_id
            FROM collects_push
            WHERE site_id =  '$site_id' and DATE_FORMAT(created,'%Y-%m') = '$month'
            GROUP BY DATE_FORMAT(created,'%Y-%m-%d')";
    }

    /**
     *  站点某天的所有用户的统计
     */
    public function siteDayOfUsers($site_id, $day)
    {
        $sql = "SELECT COUNT( id ) AS post_num, user_id, DATE_FORMAT( created,  '%Y-%m-%d' ) AS 
            DAY FROM collects_push
            WHERE site_id =  '$site_id' AND DATE_FORMAT( created,  '%Y-%m-%d' ) =  '$day'
            GROUP BY user_id ";
    }

    /**
     *  站点用户的某天统计
     */
    public function userDay($user_id, $day)
    {
        $sql = "SELECT COUNT( id ) AS post_num, user_id, DATE_FORMAT( created,  '%Y-%m-%d' ) AS 
            DAY FROM collects_push
            WHERE user_id =  '$user_id'
            AND DATE_FORMAT( created,  '%Y-%m-%d' ) =  '$day'
            GROUP BY site_id ";
    }

}
