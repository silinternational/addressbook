<?php

class AuthController extends Controller {
    
    /**
     * Define access rules here. This is based on CAccessControlFilter. See
     * docs here: 
     * http://www.yiiframework.com/doc/api/1.1/CAccessControlFilter
     * 
     * Role can be aliased using:
     *  *: any user, including both anonymous and authenticated users.
     *  ?: anonymous users.
     *  @: authenticated users.
     * 
     * @return array
     */
    public function accessRules() {
        return array(
            // Allow anyone to access the login/logout functionality.
            array('allow'),
        );
    }
    
    public function actionLogin()
    {
        $identity = new ExtDirSamlUserIdentity();
        if ($identity->authenticate()) {
            Yii::app()->user->login($identity);
            $this->redirect(Yii::app()->user->getReturnUrl());
        } else {
            $this->redirect($identity->getLoginUrl());
        }
    }
    
    public function actionLogout()
    {
//        $identity = new ExtDirSamlUserIdentity();
//        Yii::app()->user->clearStates();
//        Yii::app()->user->logout(true);
//        $this->redirect($identity->getLogoutUrl('/auth/loggedout'));
        $auth = new SimpleSAML_Auth_Simple('default-sp');
        $spConf = $auth->getAuthSource();
        $spMeta = $spConf->getMetadata();
        $idp = $spConf->getIdPMetadata($spMeta->getValue('idp'));
        $logoutUrl = $idp->getValue('SingleLogoutService').'?ReturnTo='.Yii::app()->createAbsoluteUrl('/auth/loggedout');
        Yii::app()->user->clearStates();
        Yii::app()->user->logout(true);
        //$this->redirect($identity->getLogoutUrl('/'));
        $this->redirect($logoutUrl);
    }
    
    public function actionLoggedOut()
    {
        // If the user is still logged in for some reason...
        if (!Yii::app()->user->isGuest) {
            
            // Redirect them to the home page.
            $this->redirect('/');
        }
        // Otherwise...
        else {
            
            // Show them the successfully-logged-out screen.
            $this->render('loggedout');
        }
    }
}
