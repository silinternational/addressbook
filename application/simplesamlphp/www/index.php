<?php
require __DIR__ . '/../../vendor/autoload.php';

require_once('_include.php');


SimpleSAML_Utilities::redirectTrustedURL(SimpleSAML_Module::getModuleURL('core/frontpage_welcome.php'));
