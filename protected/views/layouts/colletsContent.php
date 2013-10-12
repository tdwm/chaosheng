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
        <?php echo $content; ?>
        </div>
    </div>
<?php $this->endContent(); ?>
