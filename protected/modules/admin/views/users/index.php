<div class="row-fluid">
    <div class="span12" style="margin-top: 5px;">
        <div>
        <?php $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType' => 'link',
            'id'=>'link_id',
            'type'=>'primary',
            'label' => '添加',
            'url' => 'users/create',
            'htmlOptions' => array(
                'class' => 'pull-right',
                'id'=>'link_id',
            ),
        )); ?> 
        </div>
        <div class="clearfix"></div>

        <?php
            $columns= array(
                /*
                array(
                    'class' => 'bootstrap.widgets.TbImageColumn',
                    'imagePathExpression'=> '$data->gravatarImage(40)',
                    'htmlOptions' => array(
                        'style' => 'width: 40px'
                    )
                ),
                 */
                array('name'=>'id','htmlOptions'=>array('width'=>'50px')),
                array('name'=>'name','header'=>'姓名'),
                'displayName',
                array(
                    'name'=>'user_role',
                    'filter' => CHtml::listData(UserRoles::model()->findAll(), 'id', 'name'),
                    'value' => '$data->role->name',
                ),
                array(
                    'name'=>'parent_id',
                    //'filter' => CHtml::listData(UserRoles::model()->findAll(), 'id', 'name'),
                    'value' => '$data->parent->displayName',
                ),
                array('name'=>'email','header'=>'邮箱[登录账号]'),
                array(
                    'class'=>'bootstrap.widgets.TbToggleColumn',
                    'toggleAction'=>'/admin/users/toggle',
                    'name' => 'status',
                    'checkedIcon'=>'icon-ok',
                    'uncheckedIcon'=>'icon-remove',
                    'header' => '状态',
                ),
                array(
                    'class'=>'bootstrap.widgets.TbButtonColumn',
                    'id'=>'options',
                    'header' => '操作',
                    'template' => '{update}{delete}',
                    'updateButtonUrl' => 'Yii::app()->createUrl("/admin/users/update/id/" . $data->id)',
                    'deleteButtonUrl'=>'Yii::app()->createUrl("/admin/users/delete/id/" . $data->id)',
                ),
            );
            
            if (Yii::app()->user->role == 4) {
                //去掉角色
                unset($columns[3]);
                //去掉所属
                unset($columns[4]);
                //去掉状态
                unset($columns[6]);
            }

            $this->widget('bootstrap.widgets.TbExtendedGridView', array(
                'type' => 'striped bordered',
                'dataProvider' => $model->search(),
                'filter' => $model,
                'responsiveTable' => true,
                'bulkActions' => array(
                    'actionButtons' => array(
                        array(
                            'buttonType' => 'button',
                            'type' => 'danger',
                            'id'=>'delAll',
                            'size' => 'small',
                            'label' => '删除',
                            'click' => 'js:function(values) {
                                $.post("users/deleteMany", values, function(data) {
                                    values.each(function() {
                                        $(this).parent().parent().remove();
                                });
                            });
                            }'
                            ),
                        ),
                    'checkBoxColumnConfig' => array(
                        'name' => 'id'
                    ),
                ),
                'columns' => $columns
            ));
        ?>
    </div>
</div>
