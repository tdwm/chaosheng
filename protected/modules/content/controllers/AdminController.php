<?php
class AdminController extends ACiiController
{
    public $catHtml;

    function actionUpdate($col_id)
    {
        // Editor Preferences
        $preferMarkdown = Cii::getConfig('preferMarkdown',false);

        if ($preferMarkdown == NULL)
            $preferMarkdown = false;
        else
            $preferMarkdown = (bool)$preferMarkdown;

        //model
        $contents = $this->getContentModel();

        //获取数据
        $content = $contents->findByPk($col_id);
        if($content == null) {
            Yii::app()->user->setFlash('warning', ' 参数错误 ');
            $this->redirect(Yii::app()->request->urlReferrer );
        }

        if(isset($_POST['Contents']))
        {
            unset($content->attributes);
            $content->attributes = $_POST['Contents'];
            if( $content->save())
            {
                $this->redirect(Yii::app()->user->getReturnUrl());
            }
            else
            {
                Yii::app()->user->setFlash('error', '推送失败');
                foreach( $_POST['Contents'] as $k => $v){
                    $title->$k = $v;
                }
            }
        }
        $this->render('update',array(
            'Contents'       =>  $content,
            'col_id'             =>  $col_id,
            'preferMarkdown' =>  $preferMarkdown,
        ));
    }
	
	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($col_id)
	{
		// we only allow deletion via POST request
		// and we delete /everything/

        $title_model = $this->getContentModel();
        //$content_model = $this->getCatContentModel();
        $title_model->deleteAll('col_id=:col_id',array(':col_id'=>$col_id));
        //$content_model->deleteAll('col_id=:col_id',array(':col_id'=>$col_id));

		Yii::app()->user->setFlash('success', '删除成功');
		
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
            $command = Yii::app()->db
                ->createCommand("DELETE FROM pub_title WHERE id = :id")
                ->bindParam(":id", $id, PDO::PARAM_STR)
                ->execute();
        }

        Yii::app()->user->setFlash('success', '删除成功');

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if(!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
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
     * Sets the dashboard perspective from the list view to the card view
     */
    public function actionPerspective()
    {
        $perspective = Cii::get($_GET, 'id', 1);
        $perspective = in_array($perspective, array(1,2)) ? $perspective : 1;
        Yii::app()->session['admin_perspective'] = $perspective;
        $this->redirect($this->createUrl('/admin/content'));
    }

    public function getContentModel($class = __CLASS__)
    {
       return new Contents($class); 
    }
    
	/**
	 * Default management page
     * Display all items in a CListView for easy editing
	 */
	public function actionIndex()
	{
        $model = $this->getContentModel('seach');
        $model->pageSize = 20;

		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Contents']))
			$model->attributes=$_GET['Contents'];

        
        $viewFile = 'index_2';
        
        $nurl = Yii::app()->request->requestUri;
        Yii::app()->user->setReturnUrl($nurl);

		$this->render($viewFile, array(
			'model'=>$model,
            'categoryslug'=>Yii::app()->session['admin_cslug'],
		));
	}
	public function actionIndexUser()
	{
        $slug = Cii::get($_GET, 'slug', 'content');
        if (!isset(Yii::app()->session['admin_cslug']))
            Yii::app()->session['admin_cslug'] = 'content';
        //获取此分类下的设置
        if (Yii::app()->session['admin_cslug'] != $slug || !isset(Yii::app()->session['admin_cslug_collect'])) {
            Yii::app()->session['admin_cslug'] = $slug;
            $catinfo = Categories::model()->findByAttributes(array('slug'=>$slug));
        }

        $model=new Content('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Content']))
			$model->attributes=$_GET['Content'];
        if (!isset(Yii::app()->session['admin_perspective']))
            Yii::app()->session['admin_perspective'] = 1;
        if(Yii::app()->session['admin_perspective'] == 2)
            $model->pageSize = 20;

        $this->setLayout('colletsContent');
        
        $userCategories = Collects::model()->getCategories();
        //var_dump($userCategories);exit;
        $this->catHtml = Categories::model()->getTreeHtml($userCategories['cats'],0 );
        //var_dump($this->catHtml);exit;

        $viewFile = 'index_0';
        
        if(isset($_GET['slug']) && $_GET['slug'] != ''){
            $this->setLayout('contentWrapper');
            $cats = $userCategories['cats'];
            $viewFile = 'index_2';
        }
		$this->render($viewFile, array(
			'model'=>$model,
            'categoryslug'=>Yii::app()->session['admin_cslug'],
		));
	}
    /**
     * 根据slug选择不同的数据库进行
     *
     */
    public function actionList()
    {
        // 判断权限

        //列表
        $slug = Cii::get($_GET, 'slug', 'content');
        
        if (!isset(Yii::app()->session['admin_cslug']))
            Yii::app()->session['admin_cslug'] = 'content';
        //获取此分类下的设置
        if (Yii::app()->session['admin_cslug'] != $slug || !isset(Yii::app()->session['admin_cslug_collect'])) {
            Yii::app()->session['admin_cslug'] = $slug;
            $catinfo = Categories::model()->findByAttributes(array('slug'=>$slug));
            if($catinfo){
                $collect = Collects::model()->findByAttributes(array('user_id'=>Yii::app()->user->push_uid, 'category_id'=>$catinfo->id));
                Yii::app()->session['admin_cslug_collect'] = $collect;
            }
        }
        $model=new Content('search');
        $model->unsetAttributes();  // clear any default values
        $model->pageSize = 20;
        if(isset($_GET['Content']))
            $model->attributes=$_GET['Content'];
        $this->setLayout('colletsContent');

        $userCategories = Collects::model()->getCategories();
        $cats = $userCategories['collects'];

        $this->catHtml = Categories::model()->getTreeHtml($cats );
        $viewFile = 'index_0';
        if(isset($_GET['slug']) && $_GET['slug'] != ''){
            $viewFile = 'index_3';
        }
        $this->render($viewFile, array(
            'model'=>$model,
            'categoryslug'=>$slug,
            'category_id'=>Yii::app()->session['admin_cslug_collect']->category_id,
        ));
    }
    /**
     *
     *
     */

    public function actionShow($col_id)
    {
        $model = $this->getContentModel();
        $content = $model->findByAttributes(array('col_id'=>$col_id));
        $this->renderPartial('show', array(
            'content'=>$content,
        ));
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
    
    /**
     * Retrieves the available view files under the current theme
     * @return array    A list of files by name
     */
    private function getViewFiles($theme='default')
    {
    	$returnFiles = array();

    	if (!file_exists(YiiBase::getPathOfAlias('webroot.themes.' . $theme)))
    		$theme = 'default';

        $files = Yii::app()->cache->get($theme.'-available-views');

        if ($files == NULL)
        {
            $fileHelper = new CFileHelper;
            $files = $fileHelper->findFiles(YiiBase::getPathOfAlias('webroot.themes.' . $theme .'.views.content'), array('fileTypes'=>array('php'), 'level'=>0));
            Yii::app()->cache->set($theme.'-available-view', $files);
        }

        foreach ($files as $file)
        {
            $f = str_replace('content', '', str_replace('/', '', str_replace('.php', '', substr( $file, strrpos( $file, '/' ) + 1 ))));
            
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
            	$f = trim(substr($f, strrpos($f, '\\') + 1));

            if (!in_array($f, array('all', 'password', '_post')))
                $returnFiles[$f] = $f;
        }
        
        return $returnFiles;
    }
    
    /**
     * Retrieves the available layouts under the current theme
     * @return array    A list of files by name
     */
    private function getLayouts($theme='default')
    {
        $returnFiles = array();

    	if (!file_exists(YiiBase::getPathOfAlias('webroot.themes.' . $theme)))
    		$theme = 'default';

        $files = Yii::app()->cache->get($theme.'-available-layouts');

        if ($files == NULL)
        {
            $fileHelper = new CFileHelper;
            $files = $fileHelper->findFiles(YiiBase::getPathOfAlias('webroot.themes.' . $theme .'.views.layouts'), array('fileTypes'=>array('php'), 'level'=>0));
            Yii::app()->cache->set($theme.'-available-layouts', $files);
        }

        foreach ($files as $file)
        {
            $f = str_replace('layout', '', str_replace('/', '', str_replace('.php', '', substr( $file, strrpos( $file, '/' ) + 1 ))));

            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
            	$f = trim(substr($f, strrpos($f, '\\') + 1));

            if (!in_array($f, array('main', 'default', 'password')))
                $returnFiles[$f] = $f;
        }
        
        return $returnFiles;
    }
}
