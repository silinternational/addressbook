<?php

class UrlManager extends CUrlManager
{
    public function createUrl($route, $params = array(), $ampersand = '&')
    {
        $route = preg_replace_callback('/(?<![A-Z])[A-Z]/', function($matches) {
            return '-' . lcfirst($matches[0]);
        }, $route);
        return parent::createUrl($route, $params, $ampersand);
    }
 
    public function parseUrl($request)
    {
        $route = parent::parseUrl($request);
        return lcfirst(str_replace(' ', '', ucwords(str_replace('-', ' ', $route))));
    }
}
