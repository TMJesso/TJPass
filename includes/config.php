<?php
// set up database variables
if ($_SERVER["SERVER_NAME"] == "localhost") {
	defined('DB_SERVER')	? null : define('DB_SERVER', 'localhost');
	defined('DB_USER')		? null : define('DB_USER', 'db_1242_access_priv');
	defined('DB_PASS')		? null : define('DB_PASS', '7jp7miXZ88ucQPYg');
	defined('DB_NAME')		? null : define('DB_NAME', 'db_1242_tjpass');
	defined('DB_PORT')		? null : define('DB_PORT', 3306);
	defined('DB_SOCKET')	? null : define('DB_SOCKET', null);
} elseif ($_SERVER["SERVER_NAME"] == "theraljessop.net") {
	defined('DB_SERVER')	? null : define('DB_SERVER', 'theraljessopnet.ipagemysql.com');
	defined('DB_USER')		? null : define('DB_USER', 'buffalo101');
	defined('DB_PASS')		? null : define('DB_PASS', '2CFh2UVyqwVXWZ36');
	defined('DB_NAME')		? null : define('DB_NAME', 'my_userking');
	defined('DB_PORT')		? null : define('DB_PORT', 3306);
	defined('DB_SOCKET')	? null : define('DB_SOCKET', null);
}


// set up common variables

