<?php $this->beginContent('/layouts/dashboard'); ?>
    <div class="row-fluid">
        <div class="span2" style="margin-top:40px">
        <?php echo $this->catHtml;?>
        <script>
        $(function(){
            $('.tree-toggle>i,.tree-toggle>a>i').click(function (event) {
                if($(this).parent().is('a')) {
                    $(this).parent().parent().children('ul.tree').toggle(200);
                } else {
                    $(this).parent().children('ul.tree').toggle(200);
                }
                $(this).toggleClass('icon-minus').toggleClass('icon-plus');
                event.preventDefault();
            });
        });
        </script>
        </div>
        <div class="span10">
            <div>
         <?php $this->widget('bootstrap.widgets.TbButtonGroup', array(
             'htmlOptions' => array(
                'class' => 'pull-right'
             ),
            'buttons'=>array(
                array('label'=>'', 'url'=> $this->createUrl('/admin/content/perspective?id=1'), 'icon' => 'th-large', 'htmlOptions' => array('class'=>Yii::app()->session['admin_perspective'] == 1 ? 'active' : NULL)),
                array('label'=>'', 'url'=>$this->createUrl('/admin/content/perspective?id=2'), 'icon' => 'th-list', 'htmlOptions' => array('class'=>Yii::app()->session['admin_perspective'] == 2 ? 'active' : 'hidden-phone')),
                array('label' => 'New Post', 'url' => $this->createUrl('/admin/content/save'), 'type'=>'primary'),
            ),
        )); ?>
        </div>
        <div class="clearfix"></div>
        <?php echo $content; ?>
        </div>
    </div>
<?php $this->endContent(); ?>
