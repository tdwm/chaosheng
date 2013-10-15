<?php Yii::import('ext.redactor.*'); ?>
<div class="row-fluid">
    <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id'=>'horizontalForm',
    )); ?>
	    <div class="span8">
	        <?php $this->beginWidget('bootstrap.widgets.TbBox', array(
                'title' => '文章',
                'headerIcon' => 'icon-leaf',
            )); ?>
    	    	<?php echo $form->hiddenField($model, 'id'); ?>
    	    	<?php echo $form->hiddenField($model, 'collect_url'); ?>
    			<div class="control" style="margin-bottom: 20px;">
	    	    	<?php echo $form->textFieldRow($model, 'title', array('placeholder' => '标题', 'style' => 'width: 98%')); ?>
                    <?php echo $form->textFieldRow($model,'keywords', array('style' => 'width: 98%') ); ?>
	    	    </div>
    	        <?php if ($preferMarkdown): ?>
    	            <?php //echo $form->markdownEditorRow($model, 'content', array('height'=>'200px'));?>
    	            <?php echo $form->ckEditorRow($model, 'content', array('options'=>array('toolbar'=>'Basic','height'=>'300','width'=>'100%','resize_minWidth'=>'320')));?>
    	        <?php else: ?>
    	        	<?php $md = new CMarkdownParser(); ?>
    	        	<?php $model->content = $md->safeTransform($model->content); ?>
    	            <?php $this->widget('ImperaviRedactorWidget', array(
    	                    'model' => $model,
    	                    'attribute' => 'content',
    	                    'options' => array(
    	                        'focus' => true,
    	                        'autoresize' => false,
    	                        'autosave' => $this->createUrl('/admin/content/push/' . $model->id),
    	                        'interval' => 120,
    	                        'autosaveCallback' => 'saveCallback',
    	                        'minHeight' =>'200px'
    	                    )
    	                ));
    	            ?>
    	            <br />
    	        <?php endif; ?>
    	        
    			<div class="control" style="margin-top: 20px;">
    	        <?php echo $form->textAreaRow($model, 'extract', array('style' => 'width: 98%; height: 100px', 'placeholder' => '填写摘要')); ?>
	    	    </div>
	        <?php $this->endWidget(); ?>
	    </div>
	    <div class="span4">
	    	<?php $this->beginWidget('bootstrap.widgets.TbBox', array(
                    'title' => '其他信息',
                    'headerIcon' => 'icon-align-justify',
                    'headerButtons' => array(
		                array(
		                    'class' => 'bootstrap.widgets.TbButtonGroup',
		                    'buttons'=>array(
						         // array('label' => $model->comment_count, 'url'=>$this->createUrl('/admin/content/comments/id/' . $model->id), 'icon' => 'icon-comment', 'htmlOptions' => array('style' => 'padding: 4px 0px; padding-right: 8px;' . ($model->commentable == 1 ?: 'display:none;'))),
						        //array('label'=>'浏览', 'url' => Yii::app()->createUrl('/' . $model->slug)),
							    array('label'=>'推送',  'buttonType' => 'submit', 'htmlOptions'=>array('name'=>'save')),
							    array('label'=>'推送,编辑下一篇','buttonType' => 'submit', 'htmlOptions'=>array('name'=>'savenext')),
						    ),
		                )
		            )
                )); ?>
                <?php 

                        $media =  Collects::model()->changeSiteSetting(Yii::app()->session['admin_cslug_collect']->site_media);
                        if(empty($media)) {
                            echo $form->textFieldRow($model,'media'); 
                        } else {
                            echo $form->dropDownListRow($model, 'media',$media); 
                        }
                        $category =  Collects::model()->changeSiteSetting(Yii::app()->session['admin_cslug_collect']->site_categories);
                        if(empty($category)) {
                            echo $form->textFieldRow($model,'siteCategory'); 
                        } else {
                            echo $form->dropDownListRow($model, 'siteCategory',$category); 
                        }
                ?>
    			<?php echo $form->textFieldRow($model,'editor'); ?>
				<?php echo $form->hiddenField($model,'slug',array('class'=>'span12','maxlength'=>150, 'placeholder' => 'Slug')); ?>
		    <?php $this->endWidget(); ?>
			
	    	<?php if ($model->vid >= 1): ?>
	    		<?php $this->beginWidget('bootstrap.widgets.TbBox', array(
                    'title' => 'Tags',
                    'headerIcon' => 'icon-tags',
                )); ?>
                    <?php echo $form->textField($model, 'tagsFlat', array('id' => 'tags')); ?>
                <?php $this->endWidget(); ?>  
                 
		        <?php $this->beginWidget('bootstrap.widgets.TbBox', array(
                    'title' => 'Uploads',
                    'headerIcon' => 'icon-upload',
                    'htmlOptions' => array(
						'class' => 'contentSidebar'
					)
                )); ?>
                <?php $this->widget('ext.EAjaxUpload.EAjaxUpload',
								array(
								        'id'=>'uploadFile',
								        'config'=>array(
								           'debug'=>false,
							               'action'=>Yii::app()->createUrl('/admin/content/upload/id/'. $model->id),
							               'allowedExtensions'=>array('jpg', 'jpeg', 'png', 'gif', 'bmp'),
							               'sizeLimit'=>10*1024*1024,// maximum file size in bytes
							               'minSizeLimit'=>1,
							               'template'=>'<div id="uploadFile"><ul class="qq-upload-list" style="display:none;">
							               </ul><div class="qq-upload-drop-area" style="display:none;"></div><a class="btn btn-small btn-primary qq-upload-button right">Upload</a>
							               </div>',	
							               'onComplete' => "js:function(id, fileName, response) {
							               		if (response.success)
							               		{
							               			$('#new-attachment').before('<span class=\"thumb-container thumb-center\"><span class=\"thumb-inner\"><span class=\"thumb-img\"><img class=\"thumb\" href=\"'+ response.filepath +'\" src=\"'+ response.filepath +'\" style=\"left: 0px; top: 0px;\"></span><span class=\"thumb-strip\"></span><span class=\"thumb-icon\"></span></span></span>').after('<li id=\"new-attachment\" style=\"display:none;\">');
							               			$('.thumb').thumbs();
													$('.thumb').colorbox({rel:'thumb'});
													$('#new-attachment-img').show().attr('id', 'thumb');
							               		}
										   }"
								        )
								)); ?></h5>
					   	<div style="clear:both;"></div>
						<div class="image-holder ">
							<?php foreach ($attachments as $attachment): ?>
							    <div class="image-ctrl" id="<?php echo $attachment->key; ?>">
    								<?php echo CHtml::image($attachment->value, NULL, array('class'=> 'thumb', 'href' => $attachment->value, 'title' => $attachment->value)); ?>
                                    <span class="delete-button icon icon-remove" id="<?php echo $attachment->key; ?>"></span>
                                     <span class="star-button icon icon-star-empty" id="<?php echo $attachment->key; ?>"></span>
                                </div>
							<?php endforeach; ?>
							<li id="new-attachment" style="display:none;"></li>
						</div>
					<div class="clearfix"></div>
		         <?php $this->endWidget(); ?>
	        <?php endif; ?>
	    </div>
    <?php $this->endWidget(); ?>
</div>
<?php $asset=Yii::app()->assetManager->publish(dirname(__FILE__).'/../../assets'); ?>
<?php Yii::app()->clientScript->registerCssFile($asset . '/css/jquery.tags.css'); ?>
<?php Yii::app()->clientScript->registerCssFile($asset . '/css/colorbox.css'); ?>
<?php Yii::app()->clientScript->registerCssFile($asset . '/css/jquery.thumbs.css'); ?>
<?php Yii::app()->clientScript->registerScriptFile($asset . '/js/jquery.tags.min.js'); ?>
<?php Yii::app()->clientScript->registerScriptFile($asset . '/js/jquery.thumbs.min.js'); ?>
<?php Yii::app()->clientScript->registerScriptFile($asset . '/js/jquery.colorbox.min.js'); ?>
<?php Yii::app()->clientScript->registerScriptFile($asset . '/js/jquery.gridster.js'); ?>
<?php Yii::app()->clientScript->registerScript('admin_promoted_image', 'setTimeout(function() { $("img.thumb").css("left", 0).css("right", 0).css("top", 0); $("#blog-image").find(".star-button").removeClass("icon-star-empty").addClass("icon-star"); $("#blog-image").find(".thumb-container").addClass("transition"); }, 500);'); ?>
<?php if (!$model->isNewRecord): ?>
	<?php Yii::app()->clientScript->registerScript('autosave', '
		// Autosave the document every 1 minute
		// Because I am tired of getting timeout errors while editing a post!
		setInterval(function() { $.post(window.location.pathname, { data :$("form").serialize() }); }, 60000);
	'); ?>
	<?php Yii::app()->clientScript->registerScript('admin_tags', '
	$("#tags").tagsInput({
			defaultText : "Add a Tag",
		    width: "99%",
		    height : "40px",
		    onRemoveTag : function(e) {
		    	$.post("../../removeTag", { id : ' . $model->id . ', keyword : e });
		    },
		    onAddTag : function(e) {
		    	$.post("../../addTag", { id : ' . $model->id . ', keyword : e });
		    }
		});
	'); ?>
	<?php Yii::app()->clientScript->registerScript('admin_thumbs', '$(".thumb").thumbs();'); ?>
	<?php Yii::app()->clientScript->registerScript('admin_colorbox', '$(".thumb").colorbox({rel:"thumb"});'); ?>
	<?php Yii::app()->clientScript->registerScript('admin_promote', '$(".star-button").click(function() { 
		var id = $(this).attr("id");
		$.post("../../promoteImage", { id : ' . $model->id . ', key : id }, function() {
	        $(".image-ctrl").find(".thumb-container").css("border-color", "").removeClass("transition");
	        $("div[id*=\'" + id + "\']").find(".thumb-container").addClass("transition");
	        $(".star-button").addClass("icon-star-empty").removeClass("icon-star");
	        $("div[id*=\'" + id + "\']").find(".star-button").removeClass("icon-star-empty").addClass("icon-star");
	    });
	});'); ?>
	<?php Yii::app()->clientScript->registerScript('admin_delete', '$(".delete-button").click(function() {
	    var element = $(this);
	    $.post("../../removeImage", { id : ' . $model->id . ', key : $(this).attr("id") }, function () {
	        element.parent().fadeOut();
	    });
	})'); ?>
<?php endif; ?>
<?php Yii::app()->clientScript->registerScript('wmd-panel', 'setTimeout(function() { 
	$(".wmd-panel").first().parent().css("margin-left", 0); 
	$(".wmd-panel").first().parent().parent().find(".control-label").remove()
});');
