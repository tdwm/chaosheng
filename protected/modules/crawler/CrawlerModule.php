<?php

class CrawlerModule extends CWebModule
{
    public  $upload;
	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

        $this->layoutPath = Yii::getPathOfAlias('crawler.views.layouts');
		// import the module-level models and components
		$this->setImport(array(
			'crawler.models.*',
			'crawler.components.*',
			'crawler.collects.*',
		));

        $this->upload = array(
            'dir'=>dirname(Yii::app()->BasePath).'/uploads/',
            'url'=>Yii::app()->getBaseUrl(true).'/uploads/',
            'home'=>Yii::app()->HomeUrl,
        );
	}

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
			// this method is called before any module controller action is performed
			// you may place customized code here
			return true;
		}
		else
			return false;
	}

}
