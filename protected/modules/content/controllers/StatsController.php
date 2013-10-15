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
	public function actionIndex($site_id = null)
	{
        $_site = null;
        $sites = CollectsWeb::getSites();

        foreach($sites as $site){
            $items[] = array(
                'label'=>$site['my_site'],
                'url'=>$this->createUrl('index',array('site_id'=>$site['id'])),
                'active' => $site['id'] == $site_id ? true:false,
            );  
            if($site['id'] == $site_id){
                $_site = $site;
            }
        }

        if($site_id){
            $criteria = new CDbCriteria;
            $criteria->addCondition('site_id='.$site_id);
            //$criteria->with = array('mysite'=>array('select'=>'my_site'));
            $model = new CActiveDataProvider('StatSite', array(
                'criteria'=>$criteria,
                'pagination' => array(
                    'pageSize' => 20
                )
            ));
            $var_sum = StatSite::model()->findBySql("select SUM(`pnum`) as `pnum` from stat_site where site_id='$site_id'");
            $total = $var_sum->pnum;
        }
		$this->render('index',array(
			'model'=>$model,
			'site'=>$_site,
			'total'=>$total,
            'items'=>$items,
		));
	}

    function actionStatsUser($site_id,$user_id = null) 
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
