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
		    <div class="row-fluid header-middle-bar">
			    	<?php $this->widget('bootstrap.widgets.TbNavbar', array(
						'brand' => Yii::app()->name,
						'fixed' => false,
						'items' => array(
							array(
								'class' => 'bootstrap.widgets.TbMenu',
								'items' => $this->getCiiMenu()
							)
						)
					)); ?>
		    </div>		    
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
		
		<footer>
		    <div class="footer-top-block">
		        <div class="container"></div>
		    </div>
		    <div class="footer-main-block">
		        <div class="row-fluid">
		            <div class="inner-container">
                        <div class="span3 well" id="eChrip">
                            <?php // $this->widget('ext.echirp.EChirp', array('options' => array('user' => Cii::getConfig('twitter_username')))); ?>
                        </div>
		                <div class="span3">
                            <h5>Categories</h5>
                            <?php $this->widget('bootstrap.widgets.TbMenu', array(
                                'items' => $this->getCategories()
                            )); ?>
                        </div>
                        <div class="span3">
                            <h5>Recent Posts</h5>
                            <?php $this->widget('bootstrap.widgets.TbMenu', array(
                                'items' => $this->getRecentPosts()
                            )); ?>
                        </div>
                        <div class="span3">
                            <h5>Search</h5>
                            <p>Looking for something on the blog?</p>
                            <?php echo CHtml::beginForm($this->createUrl('/search'), 'get', array('id' => 'search')); ?>
                                <div class="input-append">
                                    <?php echo CHtml::textField('q', Cii::get($_GET, 'q', ''), array('type' => 'text', 'style' => 'width: 75%', 'placeholder' => 'Search...')); ?>
                                </div>
                            <?php echo CHtml::endForm(); ?>
                        </div>
		            </div>
		        </div>
		    </div>
		    <div class="footer-bottom-block">
		        <div class="container">
                        <div class="pull-left">Copyright &copy <?php echo date('Y'); ?> <?php echo Yii::app()->name; ?></div>
                        <div class="pull-right cii-menu"><?php $this->widget('cii.widgets.CiiMenu', array('items' => $this->getCiiMenu(), 'htmlOptions' => array('class' => 'footer-nav'))); ?></div>
		        </div>
		    </div>
		</footer>
		
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
