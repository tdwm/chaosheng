<?php
$this->breadcrumbs=array(
	'Collects Nodes',
);

$this->menu=array(
	array('label'=>'Create CollectsNode','url'=>array('create')),
	array('label'=>'Manage CollectsNode','url'=>array('admin')),
);
?>

<h1>Collects Nodes</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
