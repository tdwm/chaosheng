
<div class="row-fluid">
    <div class="span8" style="margin-top: -20px;">
        <?php
            $this->widget('bootstrap.widgets.TbExtendedGridView', array(
                'type' => 'striped bordered',
                'id'=>'role-grid',
                'dataProvider' => $model->search(),
                'responsiveTable' => true,
                'columns' => array(
                    'id',
                    array(
                        'class' => 'bootstrap.widgets.TbEditableColumn',
                        'name' => 'name',
                        'sortable'=>true,
                        'editable' => array(
                            'url' => $this->createUrl('/admin/users/roleEditable'),
                            'placement' => 'right',
                            'inputclass' => 'span10'
                        )
                    ),
                    array(
                        'class' => 'bootstrap.widgets.TbEditableColumn',
                        'name' => 'description',
                        'sortable'=>true,
                        'editable' => array(
                            'url' => $this->createUrl('/admin/users/roleEditable'),
                            'placement' => 'right',
                            'inputclass' => 'span10'
                        )
                    ),
                    array(
                        'class'=>'bootstrap.widgets.TbButtonColumn',
                        'template'=>'{update}{delete}',
                        'buttons'=>array(
                            'update'=>array(
                                'click'=>"function(){
                                    _url = $(this).attr('href');
                                    $('#roleForm').load(_url);
                                    return false;
                                }",
                                'id'=>'up_{$data->id}',
                                'url'=>'Yii::app()->createUrl("/admin/users/roleupdate/id/" . $data->id)',
                            ),
                            'delete'=>array(
                                'url'=>   'Yii::app()->createUrl("/admin/users/roledelete/id/" . $data->id)',
                            )
                        )
                    ),
                ),
            ));
        ?>
    </div>
    <div class="span4" id="roleForm">
        <?php $this->renderPartial('role_form', array('model' => $model)); ?>
    </div>
</div>

