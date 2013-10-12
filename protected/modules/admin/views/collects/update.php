<?php
$this->breadcrumbs=array(
	'Collects'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Collects','url'=>array('index')),
	array('label'=>'Create Collects','url'=>array('create')),
	array('label'=>'View Collects','url'=>array('view','id'=>$model->id)),
	array('label'=>'Manage Collects','url'=>array('admin')),
);
?>

<h1>Update Collects <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>