<?php
$this->breadcrumbs=array(
	'Collects'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Collects','url'=>array('index')),
	array('label'=>'Create Collects','url'=>array('create')),
	array('label'=>'Update Collects','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete Collects','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Collects','url'=>array('admin')),
);
?>

<h1>View Collects #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'user_id',
		'category_id',
		'type_id',
		'all_num',
		'day_num',
		'send_num',
		'start_time',
		'end_time',
		'period_time',
		'created',
		'updated',
	),
)); ?>
