<?php

// Set the internal encoding used by the mb_* functions.
mb_internal_encoding("UTF-8");
setlocale(LC_CTYPE, 'es_US.utf8');

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

// Bring in the necessary Yii file.
require_once($yii);

// Load and merge the config data.
$configMain = require __DIR__.'/../protected/config/main.php';
$configEnv = require __DIR__.'/../protected/config/local.php';
$config = CMap::mergeArray($configMain, $configEnv);

Yii::createWebApplication($config)->run();
