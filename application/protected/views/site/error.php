<?php
/* @var $this SiteController */
/* @var $code mixed */
/* @var $message string */

$this->pageTitle = Yii::app()->name . ' - Error';
?>

<div class="generic-container">
    <h2>Error <?php echo CHtml::encode($code); ?></h2>

    <div class="error">
        <p><?php echo CHtml::encode($message); ?></p>
    </div>
</div>
