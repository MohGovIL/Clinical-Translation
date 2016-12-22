<?php
/**
 * Synchronize all custom languages constants with all languages constants.
 * This script add hebrew constants that not exist in openemr system
 */

$_POST = array('synchronize' => 'synchronize');
$GLOBALS['vendor_dir'] = dirname(__FILE__).'/openemr/vendor';


//'lang_manage' file is depended in  translation.inc.php file;
require_once(dirname(__FILE__).'/openemr/library/translation.inc.php');
// make synchronize
require_once(dirname(__FILE__).'/openemr/interface/language/lang_manage.php');

echo "synchronizing custom languages was completed" . PHP_EOL;



