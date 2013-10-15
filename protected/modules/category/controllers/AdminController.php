<?php

class AdminController extends ACiiController
{
	public function beforeAction($action)
	{
		return parent::beforeAction($action);
		
	}

    public function actions()
    {
        return array_merge(parent::actions(), array(
            'toggle' => array(
            'class'=>'bootstrap.actions.TbToggleAction',
            'modelName' => 'Categories',
            )
        ));
    }
	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionSave($id=NULL)
	{
        if ($id == NULL){
			$model = new Categories;
            $oldpath = '';
        } else{
			$model=$this->loadModel($id);
            $oldpath = $model->path;
        }
		
		// Uncomment the following line if AJAX validation is needed
		//$this->performAjaxValidation($model);

		if(isset($_POST['Categories']))
		{
			$model->attributes = Cii::get($_POST, 'Categories', array());
            $model->id = Cii::get($_POST['Categories'], 'id', NULL);
			if($model->save())
			{
                $id = $model->getPrimaryKey();
                if ($model->parent_id==0) {
                    $newpath = $id;
                } else {
                    $newpath = $model->getPath($model->parent_id).','.$id;
                }
                Categories::model()->updateByPk($id,array('path'=>$newpath));
               // categories::model()->updateAll(array('path'=>"REPLACE(path,'".$oldpath."','".$newpath."')"), " path like '".$oldpath."%'");
                $sql = "update categories set path=REPLACE(path,'".$oldpath."','".$newpath."') where path  like '".$oldpath."%'";
                Yii::app()->db->createCommand($sql)->query();
				Yii::app()->user->setFlash('success', '分类修改成功！');
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
			}
			Yii::app()->user->setFlash('error', '提交失败，请从新输入.');
		}
		
		$this->render('_form',array('model'=>$model));
	}

    public function actionChangeParent($id,$pid)
    {   
        $model = categories::model()->findByPk($id);
        $oldpath = $model->path;
        $pid_model = categories::model()->findByPk($pid);
        if ($pid==0) {
            $newpath = $id;
        } else {
            $newpath = $model->getPath($pid).','.$id;
        }
        Categories::model()->updateByPk($id,array('path'=>$newpath,'parent_id'=>$pid));
        $sql = "update categories set path=REPLACE(path,'".$oldpath."','".$newpath."') where path  like '".$oldpath."%'";
        echo $sql;
        Yii::app()->db->createCommand($sql)->query();
        echo true;
    }

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
    {
        if ($id === 1)
            throw new CHttpException(400, '不能删除父分类');
        // we only allow deletion via POST request
        $check = $this->checkDelete($id);
        if ( $check === true){
            $this->loadModel($id)->delete();
            Yii::app()->user->setFlash('success', '成功删除分类.');
        }

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if(!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
        else 
            echo $check;
    }

    public function actionEditable()
    {
        $r = Yii::app()->getRequest();
        if($r->getParam('name'))
        {
            $attribute = $r->getParam('name');
            $value = $r->getParam('value');
            categories::model()->updateByPk($r->getParam('pk'),array($attribute=>$value));
            echo $r->getParam('value');
            Yii::app()->end();
        }
    }

    public function checkDelete($id)
    {

        $subCat = $this->getSubCategories($id);
        if (count($subCat)){
            Yii::app()->user->setFlash('error', '此分类下有子分类不能删除.');
            return false;
        }

        if(Contents::model()->find('cid=:cid',array('cid'=>$id))) {
            Yii::app()->user->setFlash('error', '此分类下有文章不能删除.');
            return false;
        }
        $model=$this->loadModel($id);
        return true;
    }

    /**
     * Public function to delete many records from the content table
     * TODO, add verification notice on this
     */
    public function actionDeleteMany()
    {
        $key = key($_POST);
        if (count($_POST[$key]) == 0)
            throw new CHttpException(500, 'No records were supplied to delete');
        
        foreach ($_POST[$key] as $id)
        {
            if ($id != 1)
            {
                $command = Yii::app()->db
                          ->createCommand("DELETE FROM categories WHERE id = :id")
                          ->bindParam(":id", $id, PDO::PARAM_STR)
                          ->execute();
            }
        }
        
        Yii::app()->user->setFlash('success', 'Post has been deleted');
        
        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if(!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
    }
    
	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
        /*
		$model=new Categories();
        $v = $model->getParentCategories(6,true);
        var_dump($v);exit;
         */
        //exit;
		$model=new Categories('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Categories']))
			$model->attributes=$_GET['Categories'];

		$this->render('index',array(
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
		$model=Categories::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='categories-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

    /**
     * 获得子分类
     *
     */
    public function getSubCategories($id)
    {
        return   Yii::app()->db->createCommand("select * from categories where parent_id = '".$id."'")->queryAll();
    }
}
