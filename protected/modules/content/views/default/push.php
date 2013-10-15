<?php Yii::import('ext.redactor.*'); ?>
<?php
    Yii::app()->bootstrap->registerAssetCss('select2.css');
    Yii::app()->bootstrap->registerAssetJs('select2.js');
?>
<div class="row-fluid">
    <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id'=>'horizontalForm',
    )); ?>
	    <div class="span9">
                <?php $this->beginWidget('bootstrap.widgets.TbBox', array(
                    'title' => '文章',
                    'headerIcon' => 'icon-leaf',
                    'headerButtons' => array(
                        array(
                            'class' => 'bootstrap.widgets.TbButton',
                            'type'=>'primary',
                            'label'=> '采集地址',
                            'url'=>$Contents->col_url,
                            'htmlOptions' => array(
                                'style' => 'margin-right: 10px;',
                                'target'=>'_blank'
                            )
                        )
                    )
                )); ?>
    	    	<?php echo $form->hiddenField($Contents, 'col_id'); ?>
    	    	<?php echo $form->hiddenField($Contents, 'col_url'); ?>
    			<div class="control" style="margin-bottom: 20px;">
	    	    	<?php echo $form->textFieldRow($Contents, 'col_title', array('placeholder' => '标题', 'style' => 'width: 98%','class'=>'ruler')); ?>
	    	    </div>
                <div class="row-fluid">
                    <div class="control span4" style="margin-bottom: 20px;">
                        <?php echo $form->textFieldRow($Contents,'col_keywords', array('style' => 'width: 98%') ); ?>
                    </div>
                    <div class="control span4" style="margin-bottom: 20px;">
                        <?php echo $form->textFieldRow($Contents,'col_media', array('style' => 'width: 98%') ); ?>
                    </div>
                    <div class="control span4" style="margin-bottom: 20px;">
                        <label for="Contents_col_time">采集时间</label>
                        <?php echo $Contents->col_time; ?>
                    </div>
                 </div>
    			<div class="control " style="margin-bottom: 20px;">
                    <?php $this->widget('ext.ueditor.UEditor',
                        array(
                            'id'=>'editor',
                            'model'=>$Contents,
                            'attribute'=>'col_content',
                            'UEDITOR_CONFIG'=>array(
                                'UEDITOR_HOME_URL'=>Yii::app()->baseUrl.'/ueditor/',
                                'initialContent'=>'',
                                'htmlOptions'=>array('width'=>'98%'),
                                'topOffset'=>'40',
                                'wordCount'=>false,
                                'elementPathEnabled'=>false,
                                'imageUrl'=>Yii::app()->baseUrl.'/ueditor/php/imageUp.php',
                                'imagePath'=>Yii::app()->baseUrl.'/ueditor/php/',
                                'pageBreakTag'=>'[page]',
                                'toolbars'=>array(
                                    array(
                                        'source','fullscreen','selectall','|',
                                        'indent','searchreplace', 'removeformat', 'formatmatch','autotypeset', '|',
                                        'link', 'unlink','|' , 'undo', 'redo', '|',
                                        'justifyleft', 'justifycenter', 'justifyright', 'justifyjustify',
                                        '|', 'imagenone', 'imageleft', 'imageright',
                                    ),
                                    array(
                                        'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist', '|',
                                        'customstyle', 'fontfamily', 'fontsize', '|',
                                        'bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', '|',
                                        'horizontal', 'date', 'time',
                                    )
                                ),              //All the function button on the toolbar and drop-down box
                            ),

                        ));
                    ?>
	    	    </div>
    			<div class="control" style="margin-top: 20px;">
    	        <?php echo $form->textAreaRow($Contents, 'col_description', array('style' => 'width: 98%; height: 100px', 'placeholder' => '填写摘要')); ?>
	    	    </div>
            <?php $this->endWidget(); ?>
	    </div>
	    <div class="span3">
            <?php $this->beginWidget('bootstrap.widgets.TbBox', array(
                'title' => '我的参数',
                'headerIcon' => 'icon-align-justify',
            )); 
            ?>
               <?php foreach($my_params as $item):?>
               <?php $this->renderPartial('_myparams',array( 'item'=>$item,'Contents'=>$Contents,'form'=>$form)); ?>
               <?php endforeach;?> 
            <?php $this->endWidget(); ?>
            <div data-spy="affix" data-offset-bottom="20" >
            <?php
            $this->widget('bootstrap.widgets.TbButton',array(
                'label' => '推送',
                'buttonType'=>'submit',
                'htmlOptions'=>array(
                    'style'=>'margin-left:3px',
                    'name'=>'pushone',
                )
            ));
            $this->widget('bootstrap.widgets.TbButton',array(
                'label' => '推送&下一个',
                'type' => 'primary',
                'buttonType'=>'submit',
                'htmlOptions'=>array(
                    'name'=>'pushnext',
                    'value'=>'',
                    'style'=>'margin-left:3px',
                )
            ));
            $this->widget('bootstrap.widgets.TbButton',array(
                'label' => '忽略',
                'type' => 'danger',
                'htmlOptions'=>array(
                    'name'=>'ignor',
                    'style'=>'margin-left:3px',
                ),
                'url'=>$this->createUrl('ignore',array('col_id'=>$Contents->col_id,'category_id'=>$Contents->cid)),
            ));
            ?>
            </div>
	</div>
   <?php $this->endWidget(); ?>
</div>
