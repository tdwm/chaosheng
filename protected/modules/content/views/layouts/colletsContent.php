<?php $this->beginContent('//layouts/dashboard'); ?>
    <div class="row-fluid">
        <div class="span2" style="margin-top:40px">
            <h5><?php echo $this->mysite['my_site']; ?></h5>
            <?php
            if(is_array($this->user_categories)) {
                $uitems = array();
                foreach($this->user_categories as $cat){
                    $uitems[] =  array(
                        'label'=>$cat['name'],
                        'url'=>$this->createUrl('index',array('site_id'=>$this->site_id,'slug'=>$cat['slug'])),
                        'active'=>$cat['slug'] == $this->slug,
                    );
                }

            }
            $this->widget('bootstrap.widgets.TbMenu', array(
                'type'=>'list',
                'items' =>  $uitems,
            ));
            ?>
        </div>
        <div class="span10">
        <?php echo $content; ?>
        </div>
    </div>
<?php $this->endContent(); ?>
