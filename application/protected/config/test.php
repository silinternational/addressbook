<?php

return CMap::mergeArray(
    require(dirname(__FILE__) . '/main.php'),
    array(
        'params' => array(
            "apiBaseUrl" => "http://demo7011044.mockable.io/search",
            "apiKey" => "testkey",
            "apiSecret" => "testsecret",
        ),
    )
);
