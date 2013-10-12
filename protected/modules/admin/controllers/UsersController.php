<?php

class UsersController extends ACiiController
{
	
    public function actions()
    {
        return array_merge(parent::actions(), array(
            'toggle' => array(
            'class'=>'bootstrap.actions.TbToggleAction',
            'modelName' => 'Users',
            )
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

        $this->checkByOwnUser($user_id,$model);
		if (Cii::get($_POST, 'Users', NULL) !== NULL)
		{
			// Load the bcrypt hashing tools if the user is running a version of PHP < 5.5.x
			if (!function_exists('password_hash'))
				require_once YiiBase::getPathOfAlias('ext.bcrypt.bcrypt').'.php';

			$cost = Cii::getBcryptCost();

			if ($_POST['Users']['password'] != '')
				$_POST['Users']['password'] = password_hash(Users::model()->encryptHash($_POST['Users']['email'], $_POST['Users']['password'], Yii::app()->params['encryptionKey']), PASSWORD_BCRYPT, array('cost' => $cost));
			else
				unset($_POST['Users']['password']);
				
			$model->attributes=$_POST['Users'];
			
			if($model->save()) 
			{
				Yii::app()->user->setFlash('success', 'User has been updated.');
				$this->redirect(array('index','id'=>$model->id));
			}
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}


    public function actionCreate(){
        $model=new Users;
        
		if (Cii::get($_POST, 'Users', NULL) !== NULL)
		{
			// Load the bcrypt hashing tools if the user is running a version of PHP < 5.5.x
			if (!function_exists('password_hash'))
				require_once YiiBase::getPathOfAlias('ext.bcrypt.bcrypt').'.php';

			$cost = Cii::getBcryptCost();

			if ($_POST['Users']['password'] != '')
				$_POST['Users']['password'] = password_hash(Users::model()->encryptHash($_POST['Users']['email'], $_POST['Users']['password'], Yii::app()->params['encryptionKey']), PASSWORD_BCRYPT, array('cost' => $cost));
			else
				unset($_POST['Users']['password']);
				
			$model->attributes=$_POST['Users'];
            $model->attributes['parent_id ']= Yii::app()->user->id;

			if($model->save()) 
			{
				Yii::app()->user->setFlash('success', '创建成功.');
				$this->redirect(array('index','id'=>$model->id));
			}
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
		// we only allow deletion via POST request
		$model = $this->loadModel($id);

        if ($model->id != Yii::app()->user->id && $id != 1)
        {
        	// Cascade deletion
        	Yii::app()->db->createCommand("DELETE FROM user_metadata WHERE user_id = :id")
        				  ->bindParam(":id", $id)
        				  ->execute();

            $model->delete();
            
		    Yii::app()->user->setFlash('success', 'User has been deleted');
        }
        else
        {
            throw new CHttpException(403, 'This user cannoot be deleted');
            Yii::app()->user->setFlash('warning', 'This user cannot be deleted');
        }
        
		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
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
            // Prevent deleting root and self
            if ($id != 1 && $id != Yii::app()->user->id)
            {
                $command = Yii::app()->db
                          ->createCommand("DELETE FROM users WHERE id = :id")
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
     * Removes the metadata attribute from a user
     */
    public function actionRemoveMeta()
    {
        $id = Cii::get($_POST, 'key');
        $user = Cii::get($_POST, 'user_id');
        
        $model = UserMetadata::model()->findByAttributes(array('user_id' => $user, 'key' => $id));
        if ($model == NULL)
            throw new CHttpException(403, 'Cannot delete attribute that does not exist');
        
        return $model->delete();
    }
    
	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$model=new Users('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Users']))
			$model->attributes=$_GET['Users'];

		$this->render('index',array(
			'model'=>$model,
		));
	}

    public function actionRole(){
        
		$model=new UserRoles('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['UserRoles']))
			$model->attributes=$_GET['UserRoles'];
		$this->render('role',array(
			'model'=>$model,
		));
    
    }


    public function actionRoleEditable(){
        $id = Cii::get($_POST, 'pk', 0); 
        $rolemodel = UserRoles::model()->findByPk($id);
        if($_POST){
            $rolemodel->{$_POST['name']}=$_POST['value'];
            $rolemodel->id=$id;
            if($rolemodel->save()){
                Yii::app()->user->setFlash('success', '修改成功');
            }else{
                Yii::app()->user->setFlash('error', '修改失败');
            }      
        }
        echo $_POST['value'];

    }

    public function actionRoleUpdate($id){
        $rolemodel = UserRoles::model()->findByPk($id);
		if (Cii::get($_POST, 'UserRoles', NULL) !== NULL)
        {
           // $rolemodel->unsetAttributes();  // clear any default values
            $rolemodel->name=$_POST['UserRoles']['name'];
            $rolemodel->description=$_POST['UserRoles']['description'];
            if($rolemodel->save()){
				Yii::app()->user->setFlash('success', '修改成功');
            }else{
				Yii::app()->user->setFlash('error', '修改失败');
            }      
            $this->redirect(array('role'));
        } 
		$this->renderPartial('role_form',array(
			'model'=>$rolemodel,
		));
    }
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Users::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='users-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
