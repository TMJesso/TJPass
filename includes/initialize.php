<?php
defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);
defined('SITE_ROOT') ? null : define('SITE_ROOT', $_SERVER["DOCUMENT_ROOT"] . DS . 'TJPass');
defined('SITE_HTTP') ? null : define('SITE_HTTP', DS.'Fair'.DS);

defined('LIB_PATH') 	? null : define('LIB_PATH', SITE_ROOT.DS.'includes');
defined('PUBLIC_PATH')	? null : define('PUBLIC_PATH',  SITE_HTTP . 'public' . DS);
defined('ADMIN_PATH') 	? null : define('ADMIN_PATH',   PUBLIC_PATH . 'admin');
defined('CSS_PATH') 	? null : define('CSS_PATH', PUBLIC_PATH . DS . 'css' . DS);
defined('JS_PATH') 		? null : define('JS_PATH', PUBLIC_PATH . DS . 'lists' . DS);
defined('MEDIA') 		? null : define('MEDIA', PUBLIC_PATH . DS . 'media' . DS);

