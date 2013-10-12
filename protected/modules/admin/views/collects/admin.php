<?php
$this->breadcrumbs=array(
	'Collects'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Collects','url'=>array('index')),
	array('label'=>'Create Collects','url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('collects-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Collects</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button btn')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'collects-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'user_id',
		'category_id',
		'type_id',
		'all_num',
		'day_num',
		/*
		'send_num',
		'start_time',
		'end_time',
		'period_time',
		'created',
		'updated',
		*/
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
		),
	),
)); ?>
