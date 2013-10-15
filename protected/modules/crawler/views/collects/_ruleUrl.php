<?php $this->beginWidget('bootstrap.widgets.TbBox', array(
    'title' => '网址采集',
    'headerIcon' => 'icon-home',
)); 
?>
    <?php echo $form->radioButtonListInlineRow($model, 'sourcetype', array(
        '1'=>'序列网址','2'=>'多个网页','3'=>'单一网页','4'=>'Rss','5'=>'js字符串',
    )); ?>
    <div class="well sourcetypediv" id="sourcetype_1" >
        <?php echo $form->textFieldRow($model,'urlpage',array('class'=>'span12','name'=>'urlpage_1')); ?>
        <div class="row-fluid ">
            <div class="span4">
                <?php echo $form->textFieldRow($model,'pagesize_start',array('class'=>'span12')); ?>
            </div>
            <div class="span4">
                <?php echo $form->textFieldRow($model,'pagesize_end',array('class'=>'span12')); ?>
            </div>
            <div class="span4">
                <?php echo $form->textFieldRow($model,'par_num',array('class'=>'span12')); ?>
            </div>
        </div>
    </div>
    <div class="well sourcetypediv" id="sourcetype_2" >
        <?php echo $form->textAreaRow($model,'urlpage',array('class'=>'span12','rows'=>8,'name'=>'urlpage_2')); ?>
    </div>
    <div class="well sourcetypediv" id="sourcetype_3" >
        <?php echo $form->textFieldRow($model,'urlpage',array('class'=>'span12','name'=>'urlpage_3')); ?>
    </div>
    <div class="well sourcetypediv" id="sourcetype_4" >
        <?php echo $form->textFieldRow($model,'urlpage',array('class'=>'span12','name'=>'urlpage_4')); ?>
    </div>
    <div class="row-fluid ">
        <div class="span6">
            <?php echo $form->textFieldRow($model,'url_contain',array('class'=>'span12')); ?>
        </div>
        <div class="span6">
            <?php echo $form->textFieldRow($model,'url_except',array('class'=>'span12')); ?>
        </div>
    </div>
    <div class="row-fluid ">
        <div class="span4">
            <?php echo $form->radioButtonListInlineRow($model, 'ifstring', array(
                '0'=>'Html','1'=>'字符串'
            )); ?>
        </div>
        <div class="span4">
            <?php echo $form->textFieldRow($model,'string_url_rule',array('class'=>'span12')); ?>
        </div>
        <div class="span4">
            <?php echo $form->textFieldRow($model,'string_title_rule',array('class'=>'span12')); ?>
        </div>
    </div>
    <?php echo $form->textFieldRow($model,'page_base',array('class'=>'span12')); ?>
    <div class="row-fluid ">
        <div class="span6">
            <?php echo $form->textAreaRow($model,'url_start',array('class'=>'span12','rows'=>4)); ?>
        </div>
        <div class="span6">
            <?php echo $form->textAreaRow($model,'url_end',array('class'=>'span12','rows'=>4)); ?>
        </div>
    </div>
<?php $this->endWidget(); ?>
<script>
$(function(){
    $('.sourcetypediv:not(:first)').hide();
    $(":radio[name='CollectsNode[sourcetype]']").click(function(){
        $('.sourcetypediv:visible').hide();
        $('#sourcetype_'+$(this).attr('value')).show();
    });

});
</script>
