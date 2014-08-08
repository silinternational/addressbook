<?php

class Controller extends CController {

    /**
     * @return array
     */
    public function filters() {
        return array(
            'accessControl',
        );
    }

    /**
     * Apply access rules as defined in $this->accessRules()
     */
    public function filterAccessControl($filterChain) {
        $filter = new CAccessControlFilter;
        $filter->setRules($this->accessRules());
        $filter->filter($filterChain);
    }

    /**
     * Define access rules here that should be the default access rules for
     * controllers in this website. To apply different access rules to a
     * particular controller, override this function, but be sure to use
     * array('deny') as the last entry so that anything you don't explicitly
     * allow will be defined.
     * 
     * This is based on CAccessControlFilter. See docs here: 
     * http://www.yiiframework.com/doc/api/1.1/CAccessControlFilter
     * 
     * @return array
     */
    public function accessRules() {
        return array(
            // By default, deny access to everything.
            array('deny'),
        );
    }
}
