<?php
$this->breadcrumbs=array(
	'Collects Titles'=>array('index'),
	$model->title=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List CollectsTitles','url'=>array('index')),
	array('label'=>'Create CollectsTitles','url'=>array('create')),
	array('label'=>'View CollectsTitles','url'=>array('view','id'=>$model->id)),
	array('label'=>'Manage CollectsTitles','url'=>array('admin')),
);
?>

<h1>Update CollectsTitles <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>