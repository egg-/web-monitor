<?php
require_once 'lib/func.php';
require_once 'lib/cache.php';

// DB PATH
define('DB_PATH', dirname(hz_env_server('SCRIPT_FILENAME')).'/var/');
define('DB_SUBSCRIBE', 'subscribe.db');
define('MAX_LIFETIME', 86400);	// 1 day
