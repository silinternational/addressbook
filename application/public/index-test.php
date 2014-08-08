<?php
/**
 * This is the bootstrap file for test application.
 * This file should be REMOVED when the application is deployed for production.
 */

// Change the following paths if necessary.
$yii = dirname(__FILE__).'/../vendor/yiisoft/yii/framework/yii.php';

// Register the simpleSAMLphp classes with the autoloader.
if (file_exists(__DIR__ . '/../simplesamlphp/lib/_autoload.php')) {
    $loader = include_once __DIR__ . '/../simplesamlphp/lib/_autoload.php';
}

// Include Composer's autoloading code.
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    $loader = include_once __DIR__ . '/../vendor/autoload.php';
}

// Turn on Yii's debug mode.
defined('YII_DEBUG') or define('YII_DEBUG', true);

// Specify how many levels of call stack should be shown in each log message.
//defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

// Bring in the necessary Yii file.
require_once($yii);

// Load and merge the config data.
$configMain = require __DIR__.'/../protected/config/main.php';
$configEnv = require __DIR__.'/../protected/config/local.php';
$config = CMap::mergeArray($configMain, $configEnv);

Yii::createWebApplication($config)->run();
