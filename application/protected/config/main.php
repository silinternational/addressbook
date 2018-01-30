<?php

$API_BASE_URL = getenv('API_BASE_URL') ?: null;
$API_KEY = getenv('API_KEY') ?: null;
$API_SECRET = getenv('API_SECRET') ?: null;
$GA_ENABLED = getenv('GA_ENABLED') ?: false;
$GA_TRACKING_ID = getenv('GA_TRACKING_ID') ?: null;
$WARNING_HTML = getenv('WARNING_HTML') ?: null;

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
        "apiBaseUrl" => $API_BASE_URL,
        "apiKey" => $API_KEY,
        "apiSecret" => $API_SECRET,
        "google_analytics" => array(
            "enabled" => $GA_ENABLED,
            "tracking_id" => $GA_TRACKING_ID,
        ),
        'warningHtml' => $WARNING_HTML,
    ),
);