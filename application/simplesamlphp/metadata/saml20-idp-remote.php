<?php

use Sil\PhpEnv\Env;

$IDP = Env::get('SAML_IDP', 'https://openidp.feide.no');
$SSO_URL = Env::get('SAML_SSO_URL', 'https://openidp.feide.no/simplesaml/saml2/idp/SSOService.php');
$SLO_URL = Env::get('SAML_SLO_URL', 'https://openidp.feide.no/simplesaml/saml2/idp/SingleLogoutService.php');
$CERT_FINGERPRINT = Env::get('SAML_CERT_FINGERPRINT', 'c9ed4dfb07caf13fc21e0fec1572047eb8a7a4cb');
$ORG_NAME = Env::get('SAML_ORG_NAME', 'OpenIdP');
$ORG_URL = Env::get('SAML_ORG_URL', 'https://openidp.feide.no');
$ASSERTION_ENCRYPTION = Env::get('ASSERTION_ENCRYPTION', true);

/**
 * SAML 2.0 remote IdP metadata for simpleSAMLphp.
 *
 * Remember to remove the IdPs you don't use from this file.
 *
 * See: https://rnd.feide.no/content/idp-remote-metadata-reference
 */
 

 
$metadata[$IDP] = array(
    'metadata-set' => 'saml20-idp-remote',
    'entityid' => $IDP,
    'SingleSignOnService' => array(
        0 => array(
            'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
            'Location' => $SSO_URL,
        ),
    ),
    'SingleLogoutService' => $SLO_URL,
    'certFingerprint' => $CERT_FINGERPRINT,
    'assertion.encryption' => $ASSERTION_ENCRYPTION,
    'sign.authnrequest' => true,
    'sign.logout' => true,
    'redirect.sign' => true,
    'NameIDFormat' => 'urn:oasis:names:tc:SAML:2.0:nameid-format:persistent',
    'OrganizationName' => $ORG_NAME,
    'OrganizationDisplayName' => $ORG_NAME,
    'OrganizationURL' => $ORG_URL,
    'authproc' => array(
        50 => array(
            'class' => 'core:AttributeMap',
            'oid2name',
        ),
    ),
);
