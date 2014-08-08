<?php
/* @var $this SiteController */

$this->pageTitle = Yii::app()->name . ' - Logged Out';
?>

<div class="generic-container">
    <h2>Logged Out</h2>

    <div>
        <p>You have successfully logged out.</p>
        <p>
            To log back in, 
            <a href="<?php echo $this->createUrl('site/index'); ?>">click here</a>.
        </p>
    </div>
</div>
