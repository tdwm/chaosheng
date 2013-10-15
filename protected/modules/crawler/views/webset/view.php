<?php
$this->breadcrumbs=array(
	'Collects Webs'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List CollectsWeb','url'=>array('index')),
	array('label'=>'Create CollectsWeb','url'=>array('create')),
	array('label'=>'Update CollectsWeb','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete CollectsWeb','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage CollectsWeb','url'=>array('admin')),
);
?>

<h1>View CollectsWeb #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'uid',
		'api_url',
		'my_site',
		'my_category',
		'my_media',
		'my_params',
	),
)); ?>
