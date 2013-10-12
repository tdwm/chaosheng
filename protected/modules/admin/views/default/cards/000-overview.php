<?php if (Yii::app()->user->role == 5): ?>
<?php
    // Posts Criteria
    $postsCriteria = new CDbCriteria;
    //$postsCriteria->addCondition("id=(SELECT MAX(id) FROM pub_title)");
    //$postsCriteria->addCondition('type_id=2');
    
    // Pages Criteria
    $pagesCriteria = new CDbCriteria;
    $pagesCriteria->addCondition("id=(SELECT MAX(id) FROM pub_title)");
    //$pagesCriteria->addCondition('type_id=1');
     
    // Categories Criteria
    $categoriesCriteria = new CDbCriteria;
    $categoriesCriteria->addCondition('parent_id!=0');
    
    // Needing Approval Comments
    $approval = new CDbCriteria;
    $approval->addCondition('approved=0');
    
    // Needing Approval Comments
    $flagged = new CDbCriteria;
    $flagged->addCondition('approved=-1');
?>
<div class="well span6 card">
    <h4>文章统计信息</h4>
    <div class="left span6">
        <ul class="nav nav-list">
            <li class="nav-header">Content</li>
            <li><span class="bold red"><?php echo Contents::model()->count($postsCriteria); ?></span> Posts</li>
            <li><span class="bold green"><?php echo Categories::model()->count($categoriesCriteria); ?></span> Categories</li>
            <li><span class="bold blue"><?php echo Users::model()->count(); ?></span> Users</li>
        </ul>
    </div>
    <div class="right span5">
        <ul class="nav nav-list">
            <li class="nav-header">Comments</li>
            <li><span class="bold purple"><?php //echo Comments::model()->count(); ?></span> Comments</li>
            <li><span class="bold blue"><?php //echo Comments::model()->count($approval); ?></span> Needing Approval</li>
            <li><span class="bold orange"><?php //echo Comments::model()->count($flagged); ?></span> Flagged</li>
        </ul>
    </div>
</div>
<?php endif; ?> 
