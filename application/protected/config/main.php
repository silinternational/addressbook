<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'Address Book',
    
    // Preload the 'log' component.
    'preload' => array('log'),
    
    // Autoload the model and component classes.
    'import' => array(
        'application.models.*',
        'application.components.*',
    ),
    'modules' => array(
    // uncomment the following to enable the Gii tool
    /*
      'gii'=>array(
      'class'=>'system.gii.GiiModule',
      'password'=>'Enter Your Password Here',
      // If removed, Gii defaults to localhost only. Edit carefully to taste.
      'ipFilters'=>array('127.0.0.1','::1'),
      ),
     */
    ),
    
    // Application components:
    'components' => array(
        'user' => array(
            'allowAutoLogin' => false,
            //'class' => 'WebUser',
            'loginUrl' => '/auth/login',
        ),
        'urlManager' => array(
            'urlFormat' => 'path',
            'class' => 'UrlManager',
            'rules' => array(
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ),
            'showScriptName' => FALSE,
        ),
        'errorHandler' => array(
            // Use 'site/error' action to display errors.
            'errorAction' => 'site/error',
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning',
                    //'levels' => 'error, warning, profile, info, trace',
                ),
                // uncomment the following to show log messages on web pages
                /*
                array(
                    'class'=>'CWebLogRoute',
                ),
                */
            ),
        ),
        'request' => array(
            'enableCsrfValidation' => true,
            'enableCookieValidation'=>true,
        ),
        'assetManager' => array(
            'newFileMode' => 0644,
            'newDirMode' => 0755,
        ),
    ),
    
    // Application-level parameters that can be accessed
    // using ```Yii::app()->params['paramName']```:
    'params' => array(
        'saml' => array(
            'default-sp' => 'default-sp',
            'map' => array(
                'idField' => 'eduPersonPrincipalName',
                'idFieldElement' => 0,
                'groupsField' => 'groups'
            ),
        ),
        'copyAttributes' => array(
            'first', 'last', 'email', 'phone', 'entity', 'spouse', 'manager',
        ),
    ),
);