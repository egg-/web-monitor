<?php
require_once 'lib/func.php';
require_once 'lib/cache.php';

// DB PATH
define('DB_PATH', dirname(hz_env_server('SCRIPT_FILENAME')).'/var/');
define('DB_SUBSCRIBE', 'subscribe.db');
define('MAX_LIFETIME', 86400);	// 1 day

define('MAX_WAITTIME', 30);	// 30 sec
define('USLEEP_TIME', 500000);	// 0.5 sec


function out_error($message, $code = 400)
{
	hz_out_error(json_encode(array('message'=>$message)), 'json', $code);
}