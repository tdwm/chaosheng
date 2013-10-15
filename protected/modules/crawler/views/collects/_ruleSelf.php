<?php $this->beginWidget('bootstrap.widgets.TbBox', array(
    'title' => '内容匹配设置',
    'id' => 'selfset',
    'headerIcon' => 'icon-home',
    'headerButtons' => array(
        array(
            'class' => 'bootstrap.widgets.TbButton',
            'buttonType'=>'button',
            'type'=>'primary',
            'label'=>'添加自定义' ,
            'htmlOptions' => array(
                'style' => 'margin-right: 10px;',
                'id'=>'addSelfItem',
            ),
        )
    )
)); 
?>
<?php $this->endWidget(); ?>
<div class="accordion" id="accordion2"> </div>
<script id="selfItem" type="text/x-jquery-tmpl">
    <div class="row-fluid ">
        <div class="span6">
            <div class="control-group">
              <label class="control-label" for="input01">规则名称</label>
              <div class="controls">
                <input type="text" placeholder="规则名称" class="input-xlarge"  name="customize_config[name][]" value="${name}">
              </div>
            </div>
        </div>
        <div class="span6">
            <div class="control-group">
              <label class="control-label" for="input01">规则英文名称</label>
              <div class="controls">
                <input type="text" placeholder="规则英文名称" class="input-xlarge"  name="customize_config[en_name][]" value="${en_name}">
              </div>
            </div>
        </div>
    </div>
    <div class="row-fluid ">
        <div class="span6">
            <div class="control-group">
              <label class="control-label" for="input01">匹配规则</label>
              <div class="controls">
                <textarea rows="4" class="input-xlarge"  name="customize_config[rule][]">${rule}</textarea>
              </div>
            </div>
        </div>
        <div class="span6">
            <div class="control-group">
              <label class="control-label" for="input01">过滤选项</label>
              <div class="controls">
                <textarea rows="4" class="input-xlarge"  name="customize_config[html_rule][]">${html_rule}</textarea>
              </div>
            </div>
        </div>
    </div>
    <hr>
</script>
<script>
$(function(){
    $('#addSelfItem').click(function(){
        $('#selfItem').tmpl().appendTo('#selfset');
    });
    var selfdata = <?php echo $json_customize?$json_customize:"''"?>;
    if(selfdata) {
        $('#selfItem').tmpl(selfdata).appendTo('#selfset');
    }

});
</script>
