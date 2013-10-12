<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
        <?php $asset=Yii::app()->assetManager->publish(dirname(__FILE__).'/../assets'); ?>
        <?php Yii::app()->clientScript->registerCssFile($asset.'/css/main.css'); ?>
        <?php Yii::app()->clientScript->registerCssFile($asset.'/css/glyphicons.css'); ?>
        <?php Yii::app()->clientScript->registerScriptFile($asset.'/js/jquery.tmpl.min.js'); ?>
        <script src="<?php echo $asset.'/js/select2.js'; ?>"></script>
        <script src="<?php echo $asset.'/js/notify.min.js'; ?>"></script>
    </head>
    <body>
        <?php $this->widget('bootstrap.widgets.TbNavbar', array(
              'fixed'=>'top',
              'collapse' => true,
              'brand'=>'管理',
              'brandUrl' => Yii::app()->getBaseUrl(true),
              'items'=>array(
                  array(
                      'class'=>'bootstrap.widgets.TbMenu',
                      'items'=>array_merge($this->main_menu, $this->menu),
                  ),
                  array(
                      'class' => 'bootstrap.widgets.TbMenu',
                      'htmlOptions' => array('class' => 'pull-right'),
                      'items' => array(
                          array(
                            'label' => "退出(".Yii::app()->user->displayName.")",
                            'icon' => false,
                            'url' => Yii::app()->createUrl('/logout'),
                            'active' => false,
                          )
                      )
                  )
              ),
          )); ?>
          
          <div class="visible-desktop" style="margin-top:60px;"></div>
          <div class="container-fluid">
                <div class="row-fluid">
                      <div class="span12">
                          <?php $this->widget('bootstrap.widgets.TbAlert', array(
                              'block'=>true,
                              'fade'=>true,
                              'closeText'=>'×',
                          ));?>
                          <?php echo $content; ?>
                      </div>
                </div>
          </div>
    </body>
</html>
