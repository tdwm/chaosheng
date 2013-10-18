<div class="row-fluid form-inline" >
<div class="span6" >
<?php 
echo CHtml::textField('testLink', '', array(
    'id'=>'testLink',
    'placeholder'=>'填写测试网址',
    'prepend'=>"<li class='world' ></li>",
    'class'=>"span8 pull-left",
));
$this->widget('bootstrap.widgets.TbButton',
    array('label' => '测试', 'htmlOptions' => array(
        'onclick'=>"js:
          var  _link = $('#testLink').val();
if(_link == '') {
    $('#testLink').notify('请填写测试网址');
} else {

    $('.modal-body p','#myModal').load('".$this->createUrl('testcolcontent')."',{'filetest':1,'id':".$model->nodeid.",'url':_link},function(r){
      $('#myModal').modal({'show':true});
});
}
        ",
    ))
);
?>
</div>
<div class="span6" >
<?php
$this->widget(
    'bootstrap.widgets.TbButtonGroup',
    array(
        'buttons' => array(
            array('label' => '全屏', 'type'=>'primary','htmlOptions' => array(
                'onclick'=>"js:codeMirrorcontentEditor.setOption('fullScreen',true); $('.CodeMirror-fullscreen').css('top','40px'); ",
            )),
            array('label'=>'保存', 'type'=>'success', 'htmlOptions'=>array(
                'onclick'=>"js: saveFileContent(); ",
            )),
        ),
        'htmlOptions'=>array(
            'class'=>'pull-right',
        )
    )
);
?>
</div>
</div>
<div class="row-fluid" style="margin-top:40px" >
<?php
$this->widget('ext.codemirror.Codemirror',array(
    'id'=>'contentEditor',
    'name'=>'crawlerContent',
    'value'=>$crawlerContent,
    //'showTheme' => true,
    'options'=>array(
        'lineNumbers'=> true,
        'matchBrackets'=> true,
        'showCursorWhenSelecting'=> true,
        'mode'=> 'text/x-php',
        'keyMap'=>'vim',
        'vimMode'=> true,
        'theme'=>'solarized dark',
        'indentUnit'=> 4,
        'indentWithTabs'=> true,
        'enterMode'=> "keep",
        'tabMode'=> "shift",
        'height'=>'100%',
        'autoMatchParens'=>true,
        'lineWrapping'=>true,
        //'viewportMargin'=> 'Infinity',
        'extraKeys'=>array(
            "Esc" => 'js:function(cm) { if (cm.getOption("fullScreen")) cm.setOption("fullScreen", false);$(".CodeMirror").css("top","0px"); }',
        ),
    ),
    'htmlOptions'=>array(
        'id'=>'contentEditor',
    ),
    'events'=>array(
        'change'=>'function(){ clearTimeout(toSave); toSave = setTimeout(saveFileContent, 15000); }',
    )
));
?>
</div>
<script>
$(function(){
    $(".CodeMirror").css({"height":"60%"});
});
</script>
<?php $this->beginWidget( 'bootstrap.widgets.TbModal', array('id' => 'myModal')); ?>
    <div class="modal-header">
        <a class="close" data-dismiss="modal">&times;</a>
        <h4>测试</h4>
    </div>
 
    <div class="modal-body">
        <p></p>
    </div>
<?php $this->endWidget(); ?>
