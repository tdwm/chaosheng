<?php

class ContentController extends ACiiController
{
    public $catHtml;

	public function actionPush($id=NULL)
	{
	    // ContentVersionID
		$version = 0;
        $push_uid = Yii::app()->user->push_uid;
		$theme = Cii::getConfig('theme', 'default');
        $viewFiles = $this->getViewFiles($theme);
        $layouts   = $this->getLayouts($theme);
        $cache_noweditor_key = $push_uid.'|'.Yii::app()->session['admin_cslug'];
        $editnow = Yii::app()->cache->get($cache_noweditor_key);
        if (!is_array($editnow) || array_search($id, $editnow) === false) {
            $editnow[]=$id;
            Yii::app()->cache->set($cache_noweditor_key, $editnow, 300);
        }
        //var_dump($editnow);
        
        
         // Editor Preferences
        $preferMarkdown = Cii::getConfig('preferMarkdown',false);

        if ($preferMarkdown == NULL)
            $preferMarkdown = false;
        else
            $preferMarkdown = (bool)$preferMarkdown;
        
        // Determine what we're doing, new model or existing one
		if ($id == NULL)
		{
			$model = new Content;
			$version = 0;
		}
		else
		{
			$model=$this->loadModel($id);
            
			if ($model == NULL)
				throw new CHttpException(400,'We were unable to retrieve a post with that id. Please do not repeat this request again.');
            
            // Determine the version number based upon the count of existing rows
            // We do this manually to make sure we have the correct data
		//	$version = Content::model()->countByAttributes(array('id' => $id));
		}

		if(isset($_POST['Content']))
        {
            $model2 = new Content;

            $_POST['Content']['keywords'] = $this->SBC_DBC($_POST['Content']['keywords']);
            // For some reason this isn't setting with the other data
            $model2->id = $id;
            if($model2->pushData($_POST)) 
            {
                $pushmodel = new Pushed;
                $pushmodel->push_uid = Yii::app()->user->push_uid;
                $pushmodel->category_id = Yii::app()->session['admin_cslug_collect']->category_id;
                $pushmodel->collect_id = Yii::app()->session['admin_cslug_collect']->id;
                $pushmodel->content_id = $id;
                $pushmodel->user_id = Yii::app()->user->id;
                $pushmodel->save();
                Yii::app()->user->setFlash('success', '推送成功');
                if(isset($_POST['savenext'])) {
                    $editnow_key = array_search($id, $editnow);
                    if ($editnow_key){
                        unset($editnow[$editnow_key]);
                        Yii::app()->cache->set($cache_noweditor_key, $editnow,300);
                    }
                    $unpushed_id =  $model2->getUnPushed();
                    if ($unpushed_id == null) {
                        Yii::app()->user->setFlash('warning', '目前没有可推送，请关注新闻更新列表');
                        $this->redirect(array('list','slug'=>Yii::app()->session['admin_cslug']));
                    }
                    $this->redirect(array('push','id'=>$unpushed_id));
                } else {
                    $this->redirect(array('list','slug'=>Yii::app()->session['admin_cslug']));
                }

            }
            else
            {

                Yii::app()->user->setFlash('error', '推送失败');
                /*
                foreach($model->attributes as $k => $v){
                    if(array_key_exists($k, $_POST['Content'])){
                        $model->$k = $_POST['Content'][$k];
                    }
                }
                 */
                foreach( $_POST['Content'] as $k => $v){
                    $model->$k = $_POST['Content'][$k];
                }
            }
        }

		$attachmentCriteria = new CDbCriteria(array(
		    'condition' => "content_id = {$id} AND (t.key LIKE 'upload-%' OR t.key = 'blog-image')",
		    'order'     => 't.key ASC',
		    'group'     => 't.value'
		));
        
		$attachments = $id != NULL ? ContentMetadata::model()->findAll($attachmentCriteria) : NULL;
		
		$this->render('push',array(
			'model'          =>  $model,
			'id'             =>  $id,
			//'version'        =>  $version,
			'preferMarkdown' =>  $preferMarkdown,
			//'attachments' 	 =>  $attachments,
			'views'          =>  $viewFiles,
			'layouts'        =>  $layouts 
		));
	}

    private function SBC_DBC($str) { 
        $str = trim(iconv('utf-8', 'gbk', $str));
        $str = preg_replace('/\xa3([\xa1-\xfe])/e', 'chr(ord(\1)-0x80)', $str);
        $str = trim(iconv( 'gbk', 'utf-8', $str)); 
        return $str; 
    }
	/**
	 * Handles the creation and editing of Content models.
     * If no id is provided, a new model will be created. Otherwise attempt to edit
     * @param int $id   The ContentId of the model we want to manipulate
	 */
	public function actionSave($id=NULL)
	{
	    // ContentVersionID
		$version = 0;
		$theme = Cii::getConfig('theme', 'default');
        $viewFiles = $this->getViewFiles($theme);
        $layouts   = $this->getLayouts($theme);
        
         // Editor Preferences
        $preferMarkdown = Cii::getConfig('preferMarkdown',false);

        if ($preferMarkdown == NULL)
            $preferMarkdown = false;
        else
            $preferMarkdown = (bool)$preferMarkdown;
        
        // Determine what we're doing, new model or existing one
		if ($id == NULL)
		{
			$model = new Content;
			$version = 0;
		}
		else
		{
			$model=$this->loadModel($id);
            
			if ($model == NULL)
				throw new CHttpException(400,'We were unable to retrieve a post with that id. Please do not repeat this request again.');
            
            // Determine the version number based upon the count of existing rows
            // We do this manually to make sure we have the correct data
			$version = Content::model()->countByAttributes(array('id' => $id));
		}

		if(isset($_POST['Content']))
		{
			$model2 = new Content;
			$model2->attributes=$_POST['Content'];
            if ($_POST['Content']['password'] != "")
            	$model2->password = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5(Yii::app()->params['encryptionKey']), $_POST['Content']['password'], MCRYPT_MODE_CBC, md5(md5(Yii::app()->params['encryptionKey']))));

			// For some reason this isn't setting with the other data
			$model2->extract = $_POST['Content']['extract'];
			$model2->id = $id;
			$model2->vid = $model->vid+1;
			$model2->viewFile = $_POST['Content']['view'];
            $model2->layoutFile = $_POST['Content']['layout'];

			if($model2->save()) 
			{
				Yii::app()->user->setFlash('success', 'Content has been updated');
				$this->redirect(array('save','id'=>$model2->id));
			}
			else
			{
				$model->attributes = $model2->attributes;
				$model->vid = $model2->vid-1;
				Yii::app()->user->setFlash('error', 'There was an error saving your content. Please try again');
			}
		}

		$attachmentCriteria = new CDbCriteria(array(
		    'condition' => "content_id = {$id} AND (t.key LIKE 'upload-%' OR t.key = 'blog-image')",
		    'order'     => 't.key ASC',
		    'group'     => 't.value'
		));
        
		$attachments = $id != NULL ? ContentMetadata::model()->findAll($attachmentCriteria) : NULL;
		
		$this->render('save',array(
			'model'          =>  $model,
			'id'             =>  $id,
			'version'        =>  $version,
			'preferMarkdown' =>  $preferMarkdown,
			'attachments' 	 =>  $attachments,
			'views'          =>  $viewFiles,
			'layouts'        =>  $layouts 
		));
	}

	/**
	 * Action for handling comments for a given post
	 */
	public function actionComments($id)
	{
	    if ($id == NULL)
            throw new CHttpException(400, 'Content ID is required before proceeding');
            
	    $content = Content::model()->findByPk($id);
        if ($content === NULL)
            throw new CHttpException(400, 'Comments for this content do not exists');
            
		$comments = $content->comments;
		$md = new CMarkdownParser();
		$this->render('comments', array('comments' => $comments, 'content' => $content, 'md' => $md));
	}
	
	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		// we only allow deletion via POST request
		// and we delete /everything/
		$command = Yii::app()->db
		              ->createCommand("DELETE FROM content WHERE id = :id")
		              ->bindParam(":id", $id, PDO::PARAM_STR)
		              ->execute();

		Yii::app()->user->setFlash('success', 'Post has been deleted');
		
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
                      ->createCommand("DELETE FROM content WHERE id = :id")
                      ->bindParam(":id", $id, PDO::PARAM_STR)
                      ->execute();
        }
        
        Yii::app()->user->setFlash('success', 'Post has been deleted');
        
        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if(!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
    }

	/**
	 * Public action to add a tag to the particular model
	 * @return bool		If the insert was successful or not
	 */
	public function actionAddTag()
	{
		$id = Cii::get($_POST, 'id', NULL);
		$model = Content::model()->findByPk($id);
		if ($model == NULL)
			throw new CHttpException(400, 'Your request is invalid');
		
		return $model->addTag(Cii::get($_POST, 'keyword'));
	}
	
	/**
	 * Public action to add a tag to the particular model
	 * @return bool		If the insert was successful or not
	 */
	public function actionRemoveTag()
	{
		$id = Cii::get($_POST, 'id', NULL);
		$model = Content::model()->findByPk($id);
		if ($model == NULL)
			throw new CHttpException(400, 'Your request is invalid');
		
		return $model->removeTag(Cii::get($_POST, 'keyword'));
	}
	
    /**
     * Removes an image from a given post
     */
    public function actionRemoveImage()
    {
        $id     = Cii::get($_POST, 'id');
        $key    = Cii::get($_POST, 'key');
        
        // Only proceed if we have valid date
        if ($id == NULL || $key == NULL)
            throw new CHttpException(403, 'Insufficient data provided. Invalid request');
        
        $model = ContentMetadata::model()->findByAttributes(array('content_id' => $id, 'key' => $key));
        if ($model === NULL)
            throw new CHttpException(403, 'Cannot delete attribute that does not exist');
        
        return $model->delete();
    }
    
    /**
     * Promotes an image to blog-image
     */
    public function actionPromoteImage()
    {
        $id          = Cii::get($_POST, 'id');
        $key         = Cii::get($_POST, 'key');
        $promotedKey = 'blog-image';
        // Only proceed if we have valid date
        if ($id == NULL || $key == NULL)
            return false;
        
        $model = ContentMetadata::model()->findByAttributes(array('content_id' => $id, 'key' => $key));
        
        // If the current model is already blog-image, return true (consider it a successful promotion, even though we didn't do anything)
        if ($model->key == $promotedKey)
            return true;
        
        $model2 = ContentMetadata::model()->findByAttributes(array('content_id' => $id, 'key' => $promotedKey));
        if ($model2 === NULL)
        {
            $model2 = new ContentMetadata;
            $model2->content_id = $id;
            $model2->key = $promotedKey;
        }
        
        $model2->value = $model->value;
        
        if (!$model2->save())
            throw new CHttpException(403, 'Unable to promote image');
        
        return true;
    }
	/**
	 * Handles file uploading for the controller
	 */
	public function actionUpload($id)
	{
		if (Yii::app()->request->isPostRequest)
		{
			Yii::import("ext.EAjaxUpload.qqFileUploader");
			$path = '/';
	        $folder=Yii::app()->getBasePath() .'/../uploads' . $path;// folder for uploaded files
	        $allowedExtensions = array('jpg', 'jpeg', 'png', 'gif', 'bmp');//array("jpg","jpeg","gif","exe","mov" and etc...
	        $sizeLimit = 10 * 1024 * 1024;// maximum file size in bytes
	        $uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
	        $result = $uploader->handleUpload($folder);
			
			if ($result['success'] = true)
			{
				$meta = ContentMetadata::model()->findbyAttributes(array('content_id' => $id, 'key' => $result['filename']));
				if ($meta == NULL)
					$meta = new ContentMetadata;
				$meta->content_id = $id;
				$meta->key = $result['filename'];
				$meta->value = '/uploads' . $path . $result['filename'];
				$meta->save();
				$result['filepath'] = '/uploads/' . $result['filename'];
			}
	        $return = htmlspecialchars(json_encode($result), ENT_NOQUOTES);
 
        echo $return;
		}	
		Yii::app()->end();	
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
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionMetaDelete($id, $key)
	{
		// we only allow deletion via POST request
		// and we delete /everything/
		ContentMetadata::model()->findByAttributes(array('content_id'=>$id, 'key'=>$key))->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
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

    protected function getTitleModel($class = __CLASS__)
    {
       return new PubTitle($class); 
    }
    
    protected function getContentModel($class = __CLASS__)
    {
       return new PubContent($class); 
    }
	/**
	 * Default management page
     * Display all items in a CListView for easy editing
	 */
	public function actionIndex()
	{
        $model = $this->getTitleModel('seach');

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
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
     * 
     * @return Content $model
	 */
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
