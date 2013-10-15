<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id),array('view','id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('uid')); ?>:</b>
	<?php echo CHtml::encode($data->uid); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('api_url')); ?>:</b>
	<?php echo CHtml::encode($data->api_url); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('my_site')); ?>:</b>
	<?php echo CHtml::encode($data->my_site); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('my_category')); ?>:</b>
	<?php echo CHtml::encode($data->my_category); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('my_media')); ?>:</b>
	<?php echo CHtml::encode($data->my_media); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('my_params')); ?>:</b>
	<?php echo CHtml::encode($data->my_params); ?>
	<br />


</div>