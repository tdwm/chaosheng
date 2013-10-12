<!DOCTYPE html>
<html lang="en">
	<head>
		<meta name="viewport" content="initial-scale=1.0">
	    <meta charset="UTF-8" />
	    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
	    <?php $asset=Yii::app()->assetManager->publish(YiiBase::getPathOfAlias('webroot.themes.default.assets')); ?>
	    <?php Yii::app()->clientScript->registerMetaTag('text/html; charset=UTF-8', 'Content-Type', 'Content-Type', array(), 'Content-Type')
                                      ->registerMetaTag($this->keywords, 'keywords', 'keywords', array(), 'keywords')
                                      ->registerMetaTag(strip_tags($this->params['data']['extract']), 'description', 'description', array(), 'description')
                                      ->registerCssFile($asset .'/css/main.css')
		                              ->registerCoreScript('jquery')
								      ->registerScriptFile($asset .'/js/script.js'); ?>
		<!--[if lt IE 9]>
            <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
	</head>
	<body>
		<header>
		    <div class="header-top-bar"></div>
		</header>
		
		<main class="main">
            <!-- 
		    <div class="container image-container">
            <div class="row-fluid image-viewport"> </div> 
		   	</div>-->
		   	<div class="container main-container">
                <div class="row-fluid main-body">
                    <?php echo $content; ?>
                </div>
            </div>
		</main>
		
		
		<?php if (!YII_DEBUG):
			if (Cii::getConfig('piwikId') !== NULL):
				$this->widget('ext.analytics.EPiwikAnalyticsWidget', 
					array(
						'id' 		=> Cii::getConfig('piwikId'),
						'baseUrl' 	=> Cii::getConfig('piwikBaseUrl')
					)
				); 
			endif;
			
			if (Cii::getConfig('gaAccount') !== NULL):
				$this->widget('ext.analytics.EGoogleAnalyticsWidget', 
					array(
						'account'=>Cii::getConfig('gaAccount'), 
						'addThis'=>Cii::getConfig('gaAddThis'), 
						'addThisSocial'=>Cii::getConfig('gaAddThisSocial'),
					)
				);
			endif; 
		endif; ?>
	</body>
</html>
