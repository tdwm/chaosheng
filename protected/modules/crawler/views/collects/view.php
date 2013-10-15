<?php
$this->breadcrumbs=array(
	'Collects Nodes'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List CollectsNode','url'=>array('index')),
	array('label'=>'Create CollectsNode','url'=>array('create')),
	array('label'=>'Update CollectsNode','url'=>array('update','id'=>$model->nodeid)),
	array('label'=>'Delete CollectsNode','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->nodeid),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage CollectsNode','url'=>array('admin')),
);
?>

<h1>View CollectsNode #<?php echo $model->nodeid; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'nodeid',
		'name',
		'lastdate',
		'sourcecharset',
		'sourcetype',
		'urlpage',
		'pagesize_start',
		'pagesize_end',
		'page_base',
		'par_num',
		'url_contain',
		'url_except',
		'url_start',
		'url_end',
		'title_rule',
		'title_html_rule',
		'keywords_rule',
		'keywords_html_rule',
		'author_rule',
		'author_html_rule',
		'comeform_rule',
		'comeform_html_rule',
		'time_rule',
		'time_html_rule',
		'content_rule',
		'content_html_rule',
		'content_page_start',
		'content_page_end',
		'content_page_rule',
		'content_page',
		'content_nextpage',
		'down_attachment',
		'watermark',
		'coll_order',
		'customize_config',
		'frequency',
		'sign',
	),
)); ?>
