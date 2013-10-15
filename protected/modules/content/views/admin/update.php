<?php Yii::import('ext.redactor.*'); ?>
<div class="row-fluid">
    <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id'=>'horizontalForm',
    )); ?>
	    <div class="span12">
                <?php $this->beginWidget('bootstrap.widgets.TbBox', array(
                    'title' => '文章',
                    'headerIcon' => 'icon-leaf',
                    'headerButtons' => array(
                        array(
                            'class' => 'bootstrap.widgets.TbButtonGroup',
                            'buttons'=>array(
                                array('label'=>'保存','type'=>'primary','buttonType'=>'submit','htmlOptions'=>array('name'=>'save')),
                                array('label'=>'采集地址','url'=>$Contents->col_url, 'htmlOptions'=>array('target'=>'_blank')),
                                array('label'=>'删除','type'=>'danger','url'=>$this->createUrl("admin/delete/" ,array("col_id"=>$Contents->col_id) ), 'htmlOptions'=>array('target'=>'_blank')),
                            )
                        )
                    )
                )); ?>
    	    	<?php echo $form->hiddenField($Contents, 'col_id'); ?>
    	    	<?php echo $form->hiddenField($Contents, 'col_url'); ?>
    			<div class="control" style="margin-bottom: 20px;">
	    	    	<?php echo $form->textFieldRow($Contents, 'col_title', array('placeholder' => '标题', 'style' => 'width: 98%')); ?>
	    	    </div>
            <div class="row-fluid">
    			<div class="control span5" style="margin-bottom: 20px;">
                    <?php echo $form->textFieldRow($Contents,'col_keywords', array('style' => 'width: 98%') ); ?>
	    	    </div>
    			<div class="control span3" style="margin-bottom: 20px;">
                    <?php echo $form->textFieldRow($Contents,'col_media', array('style' => 'width: 98%') ); ?>
	    	    </div>
    			<div class="control span3" style="margin-bottom: 20px;">
                    <?php echo $form->textFieldRow($Contents,'col_time', array('style' => 'width: 98%') ); ?>
	    	    </div>
	    	 </div>
    			<div class="control " style="margin-bottom: 20px;">
    	        <?php if ($preferMarkdown): ?>
                    <?php 
                        $this->widget('ext.ueditor.UEditor',
                            array(
                                'id'=>'editor',
                                'model'=>$Contents,
                                'attribute'=>'col_content',
                                'htmlOptions'=>array('width'=>'98%'),
                                'UEDITOR_CONFIG'=>array(
                                    'UEDITOR_HOME_URL'=>Yii::app()->baseUrl.'/ueditor/',
                                    'initialContent'=>'欢迎',
                                    'autoFloatEnabled'=>true,
                                    'topOffset'=>'40',
                                    'imageUrl'=>Yii::app()->baseUrl.'/ueditor/php/imageUp.php',
                                    'imagePath'=>Yii::app()->baseUrl.'/ueditor/php/',
                                    'emotionLocalization'=>true,
                                    'pageBreakTag'=>'[page]',
                                ),
                            ));
                    ?>
    	        <?php else: ?>
    	        	<?php $md = new CMarkdownParser(); ?>
    	        	<?php $Contents->col_content = $md->safeTransform($Contents->col_content); ?>
    	            <?php $this->widget('ImperaviRedactorWidget', array(
    	                    'model' => $Contents,
    	                    'attribute' => 'col_content',
    	                    'options' => array(
    	                        'focus' => true,
                                'lang' => 'zh_cn',
                                'buttons'=>array('html','|','formatting','|','bold','italic','deleted','|','unorderedlist','orderedlist','outdent','indent','|','table','link','|','alignment','|','horizontalrule'),
    	                        'autoresize' => true,
    	                        'interval' => 120,
                                'plugins' => array(
                                    'clean_text' => array(
                                        'js' => array('clean_text.js',),
                                    ),
                                    'fullscreen' => array(
                                        'js' => array('fullscreen.js',),
                                    ),
                                ),
    	                        //'autosaveCallback' => 'saveCallback',
    	                        'minHeight' =>'200px'
    	                    )
    	                ));
    	            ?>
    	            <br />
    	        <?php endif; ?>
	    	    </div>
    			<div class="control" style="margin-top: 20px;">
    	        <?php //echo $form->textAreaRow($Contents, 'col_description', array('style' => 'width: 98%; height: 100px', 'placeholder' => '填写摘要')); ?>
	    	    </div>
	        <?php $this->endWidget(); ?>
	    </div>
    <?php $this->endWidget(); ?>
</div>
