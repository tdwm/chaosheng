<div class="row-fluid">
    <div class="span12" style="margin-top: 5px;">
        <div>
        <?php $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType' => 'link',
            'type'=>'primary',
            'label' => '添加',
            'url' => Yii::app()->createUrl("admin/collects/create"),
            'htmlOptions' => array(
                'class' => 'pull-right'
            ),
        )); ?> 
        </div>
        <div class="clearfix"></div>
        <?php
            $this->widget('bootstrap.widgets.TbExtendedGridView', array(
                'type' => 'striped bordered',
                'dataProvider' => $model->search(),
                'filter' => $model,
                'responsiveTable' => true,
                'afterAjaxUpdate'=>"function(){
                  $('#start_time').bdatepicker({
                            'format':'yyyy-mm-dd',
                            'autoclose':true,
                            'language':'zh-CN',
                            'htmlOptions': {
                                'id' : 'start_time',
                            }
                    });
                   $('#end_time').bdatepicker({
                            'format':'yyyy-mm-dd',
                            'autoclose':true,
                            'language':'zh-CN',
                            'htmlOptions': {
                                'id' : 'end_time',
                            }
                    });
                }",
                'bulkActions' => array(
                    'actionButtons' => array(
                        array(
                            'buttonType' => 'button',
                            'type' => 'danger',
                            'size' => 'small',
                            'id' => 'delAll',
                            'label' => '删除',
                            'click' => 'js:function(values) {
                                $.post("collects/deleteMany", values, function(data) {
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
                'columns' => array(
                    array(
                        'name'=>'user_id',
                        'filter' => CHtml::listData(Users::model()->getUsersByRole(4),'id','displayName'),
                        'value'=>'$data->user->displayName'
                    ),
                    array('name'=>'category_id','value'=>'$data->category->name'),
                    array(
                        'name'=>'type_id',
                        'filter' => Collects::model()->type,
                        'value'=>'array_search($data->type_id,array_flip(Collects::model()->type))'
                    ),
                    array('name'=>'all_num', 'htmlOptions'=>array('width'=>'80px')),
                    array('name'=>'day_num', 'htmlOptions'=>array('width'=>'60px')),
                    array('name'=>'send_num', 'htmlOptions'=>array('width'=>'60px')),
                    array(
                        'name'=>'start_time',
                        'type' => 'raw',
                        'value'=>'substr($data->start_time,0,10)',
                        'filter'=>$this->widget('bootstrap.widgets.TbDatePicker', array(
                            'model'=>$model, 
                            'attribute'=>'start_time', 
                            'htmlOptions' => array(
                                'id' => 'start_time',
                            ),
                            'options'=>array(
                                'showAnim'=>'fold',
                                'format' => 'yyyy-mm-dd',
                                'autoclose'=> true,
                                'language' => 'zh-CN',
                            ),
                        ),true)
                    ),
                    array(
                        'name'=>'end_time',
                        'value'=>'substr($data->end_time,0,10)',
                        'type' => 'raw',
                        'filter'=>$this->widget('bootstrap.widgets.TbDatePicker', array(
                            'model'=>$model, 
                            'attribute'=>'end_time', 
                            'htmlOptions' => array(
                                'id' => 'end_time',
                            ),
                            'options'=>array(
                                'showAnim'=>'fold',
                                'format' => 'yyyy-mm-dd',
                                'autoclose'=> true,
                                'language' => 'zh-CN',
                            ),
                        ),true)
                    ),
                    array('name'=>'period_time', 'htmlOptions'=>array('width'=>'40px')),
                    array(
                        'class'=>'bootstrap.widgets.TbButtonColumn',
                        'header' => '操作',
                        'template' => '{update}{delete}',
                        'updateButtonUrl' => 'Yii::app()->createUrl("/admin/collects/update/id/" . $data->id)',
                        'deleteButtonUrl'=>'Yii::app()->createUrl("/admin/collects/delete/id/" . $data->id)',
                    ),
                ),
            ));
        ?>
    </div>
</div>
