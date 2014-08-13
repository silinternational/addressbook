<?php

return CMap::mergeArray(
    require(dirname(__FILE__) . '/main.php'),
    array(
        'components' => array(
            /*
            'fixture' => array(
                'class' => 'system.test.CDbFixtureManager',
            ),
            */
            
            /* Uncomment the following to provide test database connection:
            'db'=>array(
                'connectionString'=>'DSN for test database',
            ),
            */
        ),
        'params' => array(
            "apiBaseUrl" => "http://demo7011044.mockable.io/search",
            "apiKey" => "testkey",
            "apiSecret" => "testsecret",
        ),
    )
);
