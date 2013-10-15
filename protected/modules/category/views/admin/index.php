<div class="row-fluid">
    <div class="span3" style="">
        <?php
            $this->widget('ext.ztree.zTree',array(
                'treeNodeNameKey'=>'name',
                'treeNodeKey'=>'id',
                'treeNodeParentKey'=>'parent_id',
                'id'=>'mytree',
                'options'=>array(
                    //'treeId'=>'category',
                    'expandSpeed'=>"",
                    'edit'=>array(
                        'enable'=>true,
                        'showRemoveBtn' => true,
                        'showRenameBtn' => true,
                        'removeTitle' => "删除",
                        'renameTitle' => "编辑",
                    ),
                    'view'=>array(
                        //'fontCss'=>array('font-size'=>'24px'),
                        'addDiyDom'=>'js:addDiyDom',
                        'showLine'=>false,
                        'showIcon'=>false,
                    ),
                    'callback'=>array(
                        'beforeRename'=>'js:renameItem',
                        'beforeRemove'=>'js:removeItem',
                        'onDrop'=>'js:moveItem',
                    ),
                ),
                'model'=>$model,
            ));
        ?>
    </div>
    <div class="span6" style="margin-top: -20px;">
        <?php
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
                        'id' => 'delAll',
                        'size' => 'small',
                        'label' => 'Delete Selected',
                        'click' => 'js:function(values) {
                            $.post("categories/deleteMany", values, function(data) {
                                values.each(function() {
                                    $(this).parent().parent().remove();
                                });
                            });
                            }'
                        )
                    ),
                    'checkBoxColumnConfig' => array(
                        'name' => 'id'
                    ),
                ),
                'columns' => array(
                    'id'=>array( 'htmlOptions'=>array( 'width'=>'40px'),'name'=>'id'),
                    'name',
                    'slug',
                    'path'=>array('filter'=>'','name'=>'path'),
                    'parent_id'=>array('filter'=>'','name'=>'parent_id'),
                    array(
                        'class'=>'bootstrap.widgets.TbToggleColumn',
                        'toggleAction'=>'/admin/categories/toggle',
                        'name' => 'flag',
                        'header' => '状态',
                        'filter'=>'',
                    ),
                    array(
                        'class'=>'bootstrap.widgets.TbButtonColumn',
                        'template'=>'{update}  {delete}',
                        'updateButtonUrl'=>'Yii::app()->createUrl("/category/admin/save",array("id"=>$data->id))',
                        'deleteButtonUrl'=>'Yii::app()->createUrl("/category/admin/delete",array("id"=>$data->id))',
                    ),
                ),
            ));
        ?>
    </div>
    <div class="span3 ">
        <?php $this->renderPartial('_form', array('model' => $model)); ?>
    </div>
</div>
<?php
    
$flaglink = $this->createUrl('toggle',array('attribute'=>'flag'));
Yii::app()->clientScript->registerScript(
    __CLASS__ . '#' . $this->getId(),
    "//toggleb标签
    function addDiyDom(treeId,treeNode){
        var aObj = $('#' + treeNode.tId + '_a');
        if(treeNode.flag == 1){
            var str = '<a class=\"flag_toggle fright\" style=\"float:left;\" title=\"Check\" rel=\"tooltip\" href=\"$flaglink/id/'+treeNode.id+'\"><i class=\"icon-ok-circle\"></i></a>';
           } else {
           var str = '<a class=\"flag_toggle fright\"  style=\"float:left;\"  title=\"Check\" rel=\"tooltip\" href=\"$flaglink/id/'+treeNode.id+'\"><i class=\"icon-remove-sign\"></i></a>';
        }
        aObj.append(str);
     }
    function renameItem(treeId, treeNode, newName, isCancel){
        if(newName!=treeNode.name){
            $.post('".$this->createUrl('editable')."',
                {name:'name','pk':treeNode.id,'value':newName},
                function(data){
                    $('#' + treeNode.tId).notify('修改成功', 'info');
                }
            );        
        }
    }
    function removeItem(treeId, treeNode) {
          $.get('".$this->createUrl('delete')."/id/'+treeNode.id+'?ajax=true',
                function(data){
                    if (data){
                        $('#'+treeNode.tId).remove();
                        $('#mytree').notify('删除成功', 'info');
                    } else{
                        $('#'+treeNode.tId).notify('此分类下有子分类或者文章，不能删除', 'warn');
                    }
                }
            );        
         return false;
    }
    function moveItem(event, treeId, treeNodes, targetNode, moveType) {
        alert(treeNodes.length + ',' + (targetNode ? (targetNode.tId + ', ' + targetNode.name) : 'isRoot' ));
        if(targetNode){
            _pid = targetNode.id;
        }else {
            _pid = 0;
        }
        $.each(treeNodes,function(key,treeNode){
            $.get('".$this->createUrl('changeparent')."/pid/'+_pid+'/id/'+treeNode.id+'?ajax=true',
                function(data){
                    if (data){
                        $('#mytree').notify('修改成功', 'info');
                    }
                }
            );        
        });
    };
    $(document).on('click','#mytree a.flag_toggle',
        function(evn) {
            th=this;
            evn.preventDefault();
            $.post($(this).attr('href'),function(data){
                    $('i',th).toggleClass('icon-remove-sign').toggleClass('icon-ok-circle');
            });
            return false;
    });
   ");
?>
