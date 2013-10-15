<?php $this->beginWidget('bootstrap.widgets.TbBox', array(
    'title' => '内容匹配设置',
    'headerIcon' => 'icon-home',
)); 
?>
<div class="accordion" id="accordion1"> </div>
<div class="accordion-group">
    <div class="accordion-heading">
        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1" href="#collapse_title">
            标题规则
        </a>
    </div>
    <div id="collapse_title" class="accordion-body collapse in">
        <div class="accordion-inner">
            <div class="row-fluid ">
                <div class="span6">
                    <?php echo $form->textAreaRow($model,'title_rule',array('class'=>'span12','rows'=>4)); ?>
                </div>
                <div class="span6">
                    <?php echo $form->textAreaRow($model,'title_html_rule',array('class'=>'span12','rows'=>4)); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="accordion-group">
  <div class="accordion-heading">
      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1" href="#collapse_author">
        作者规则
      </a>
  </div>
  <div id="collapse_author" class="accordion-body collapse in">
      <div class="accordion-inner">
            <div class="row-fluid ">
                <div class="span6">
                    <?php echo $form->textAreaRow($model,'author_rule',array('class'=>'span12','rows'=>4)); ?>
                </div>
                <div class="span6">
                    <?php echo $form->textAreaRow($model,'author_html_rule',array('class'=>'span12','rows'=>4)); ?>
                </div>
            </div>
      </div>
  </div>
</div>
<div class="accordion-group">
  <div class="accordion-heading">
      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1" href="#collapse_media">
        来源规则
      </a>
  </div>
  <div id="collapse_media" class="accordion-body collapse in">
      <div class="accordion-inner">
            <div class="row-fluid ">
                <div class="span6">
                    <?php echo $form->textAreaRow($model,'media_rule',array('class'=>'span12','rows'=>4)); ?>
                </div>
                <div class="span6">
                    <?php echo $form->textAreaRow($model,'media_html_rule',array('class'=>'span12','rows'=>4)); ?>
                </div>
            </div>
      </div>
  </div>
</div>
<div class="accordion-group">
  <div class="accordion-heading">
      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1" href="#collapse_time">
        时间规则
      </a>
  </div>
  <div id="collapse_time" class="accordion-body collapse in">
      <div class="accordion-inner">
            <div class="row-fluid ">
                <div class="span6">
                    <?php echo $form->textAreaRow($model,'time_rule',array('class'=>'span12','rows'=>4)); ?>
                </div>
                <div class="span6">
                    <?php echo $form->textAreaRow($model,'time_html_rule',array('class'=>'span12','rows'=>4)); ?>
                </div>
            </div>
      </div>
  </div>
</div>
<div class="accordion-group">
  <div class="accordion-heading">
      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1" href="#collapse_keywords">
        关键字规则
      </a>
  </div>
  <div id="collapse_keywords" class="accordion-body collapse in">
      <div class="accordion-inner">
            <div class="row-fluid ">
                <div class="span6">
                    <?php echo $form->textAreaRow($model,'keywords_rule',array('class'=>'span12','rows'=>4)); ?>
                </div>
                <div class="span6">
                    <?php echo $form->textAreaRow($model,'keywords_html_rule',array('class'=>'span12','rows'=>4)); ?>
                </div>
            </div>
      </div>
  </div>
</div>
<div class="accordion-group">
  <div class="accordion-heading">
      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1" href="#collapse_content">
        内容规则
      </a>
  </div>
  <div id="collapse_content" class="accordion-body collapse in">
      <div class="accordion-inner">
            <div class="row-fluid ">
                <div class="span6">
                    <?php echo $form->textAreaRow($model,'content_rule',array('class'=>'span12','rows'=>4)); ?>
                </div>
                <div class="span6">
                    <?php echo $form->textAreaRow($model,'content_html_rule',array('class'=>'span12','rows'=>4)); ?>
                </div>
            </div>
      </div>
  </div>
</div>
<?php $this->endWidget(); ?>
<?php $this->beginWidget('bootstrap.widgets.TbBox', array(
    'title' => '内容分页规则',
    'headerIcon' => 'icon-home',
)); 
?>
<div class="row-fluid ">
    <div class="span6">
<?php echo $form->radioButtonListInlineRow($model, 'content_page_rule', array(
    '1'=>'全部列出模式','2'=>'上下页模式'
)); ?>
    </div>
    <div class="span6 contentnextpagediv" style="display:none">
       <?php echo $form->textFieldRow($model,'content_nextpage',array('class'=>'span12')); ?>
        请填写下一页超链接中间的代码。如：下一页，他的“下一页规则”为“下一页”。
    </div>
</div>
<div class="row-fluid ">
    <div class="span6">
        <?php echo $form->textAreaRow($model,'content_page_start',array('class'=>'span12','rows'=>4)); ?>
    </div>
    <div class="span6">
        <?php echo $form->textAreaRow($model,'content_page_end',array('class'=>'span12','rows'=>4)); ?>
    </div>
</div>
<?php $this->endWidget(); ?>
<script>
$(function(){
    $(":radio[name='CollectsNode[content_page_rule]']").click(function(){
        if ($(this).val()==2){
            $('.contentnextpagediv').show();
        }else{
            $('.contentnextpagediv').hide();
        }
    });
    if($(":radio[name='CollectsNode[content_page_rule]']:checked").val()==2){
        $('.contentnextpagediv').show();
    }
});
</script>
