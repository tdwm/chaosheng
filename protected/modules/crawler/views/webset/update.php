<?php
$this->breadcrumbs=array(
	'Collects Webs'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List CollectsWeb','url'=>array('index')),
	array('label'=>'Create CollectsWeb','url'=>array('create')),
	array('label'=>'View CollectsWeb','url'=>array('view','id'=>$model->id)),
	array('label'=>'Manage CollectsWeb','url'=>array('admin')),
);
?>

<h1>Update CollectsWeb <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>