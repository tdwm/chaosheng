<?php
$this->breadcrumbs=array(
	'Collects Nodes'=>array('index'),
	$model->name=>array('view','id'=>$model->nodeid),
	'Update',
);

$this->menu=array(
	array('label'=>'List CollectsNode','url'=>array('index')),
	array('label'=>'Create CollectsNode','url'=>array('create')),
	array('label'=>'View CollectsNode','url'=>array('view','id'=>$model->nodeid)),
	array('label'=>'Manage CollectsNode','url'=>array('admin')),
);
?>

<h1>Update CollectsNode <?php echo $model->nodeid; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>