<?php

// change the following paths if necessary
$yiit=dirname(__FILE__).'/../../vendor/yiisoft/yii/framework/yiit.php';

require_once($yiit);

$config = require __DIR__.'/../config/test.php';

Yii::createWebApplication($config);
