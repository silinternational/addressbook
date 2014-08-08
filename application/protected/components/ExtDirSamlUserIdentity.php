<?php

/**
 * Class for managing user identity tasks for this Extended Directory, using
 * SAML as the authentication mechanism.
 */
class ExtDirSamlUserIdentity extends CUserIdentity
{
    /**
     * The SimpleSAML object for authentication tasks.
     * @var \SimpleSAML_Auth_Simple 
     */
    protected $auth;
    
    /**
     * The array of SAML config settings.
     * @var array
     */
    protected $config;
    
    private $_id;
    
    /**
     * A list of the user's groups as returned by SAML.
     * 
     * @var array
     */
    protected $groups;
    
    public function __construct() 
    {
        $this->config = Yii::app()->params['saml'];
        $this->auth = new \SimpleSAML_Auth_Simple($this->config['default-sp']);
    }
    
    /**
     * Authenticate the current user.
     * 
     * @return bool Returns TRUE if the user is authenticated and FALSE if not.
     */
    public function authenticate()
    {
        // If the user is NOT authenticated...
        if (!$this->auth->isAuthenticated()) {
            
            // Record that error code.
            $this->errorCode = self::ERROR_UNKNOWN_IDENTITY;
            
            // Indicate that the user is NOT authenticated.
            return FALSE;
        }
        // Otherwise (i.e. - the user IS authenticated)...
        else {
            
            // Record the user's identifier (in this case it's an email-like
            // string we get back from SAML).
            $attrs = $this->auth->getAttributes();
            $idField = $this->config['map']['idField'];
            $idFieldElement = $this->config['map']['idFieldElement'];
            $this->_id = $attrs[$idField][$idFieldElement];
            
            // Also record their groups.
            $groupsField = $this->config['map']['groupsField'];
            $this->groups = $attrs[$groupsField];
            
            // Record that there was no error.
            $this->errorCode = self::ERROR_NONE;
            
            // Indicate that the user IS authenticated.
            return TRUE;
        }
    }
    
    public function getId()
    {
        return $this->_id;
    }
    
    /**
     * Retrieve a URL that can be used to log the user in.
     * 
     * @param string|NULL $return The page the user should be returned to
     *     afterwards. If this parameter is NULL, the user will be returned to
     *     the current page.
     * @return string A URL which is suitable for use in link-elements.
     */
    public function getLoginUrl($return = null)
    {
        return $this->auth->getLoginURL($return);
    }
    
    /**
     *  Retrieve a URL that can be used to log the user out.
     * 
     * @param string|NULL $return The page the user should be returned to
     *     afterwards. If this parameter is NULL, the user will be returned to
     *     the current page.
     * @return string A URL which is suitable for use in link-elements.
     */
    public function getLogoutUrl($return = null)
    {
        return $this->auth->getLogoutURL($return);
    }
}
