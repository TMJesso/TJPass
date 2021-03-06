<?php
$errors = array();
defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);
if ($_SERVER["SERVER_NAME"] == "theraljessop.net" || $_SERVER["SERVER_NAME"] == "theraljessopnet.ipage.com") { 
	defined('SITE_ROOT') ? null : define('SITE_ROOT', $_SERVER["DOCUMENT_ROOT"] . DS . 'tjnet');
	defined('SITE_HTTP') ? null : define('SITE_HTTP', DS.'tjnet'.DS);
} else { 
	defined('SITE_ROOT') ? null : define('SITE_ROOT', $_SERVER["DOCUMENT_ROOT"] . DS . 'TJPass');
	defined('SITE_HTTP') ? null : define('SITE_HTTP', DS.'TJPass'.DS);
}

defined('LIB_PATH') 	? null : define('LIB_PATH', SITE_ROOT. DS . 'includes' . DS);
defined('PUBLIC_PATH')	? null : define('PUBLIC_PATH',  SITE_HTTP . 'public' . DS);
defined('ADMIN_PATH') 	? null : define('ADMIN_PATH',   PUBLIC_PATH . 'admin');
defined('CSS_PATH') 	? null : define('CSS_PATH', PUBLIC_PATH . 'css' . DS);
defined('JS_PATH') 		? null : define('JS_PATH', PUBLIC_PATH . 'lists' . DS);
defined('MEDIA') 		? null : define('MEDIA', PUBLIC_PATH . DS . 'media' . DS);
defined('LAYOUT') 		? null : define('LAYOUT', SITE_ROOT . DS . 'public' . DS . 'layouts' . DS);

require_once LIB_PATH . 'config.php';

require_once LIB_PATH . 'methods.php';

require_once LIB_PATH . 'common.php';
require_once LIB_PATH . 'database.php';
require_once LIB_PATH . 'log.php';
require_onCE LIB_PATH . 'session.php';
require_once LIB_PATH . 'user.php';
require_once LIB_PATH . 'workhorse.php';
require_once LIB_PATH . 'uservalues.php';
require_once LIB_PATH . 'useraccess.php';
require_once LIB_PATH . 'menu.php';
require_once LIB_PATH . 'submenu.php';
require_once LIB_PATH . 'subject.php';
require_once LIB_PATH . 'pages.php';
require_once LIB_PATH . 'photo.php';
require_once LIB_PATH . 'sc.php';

?>


