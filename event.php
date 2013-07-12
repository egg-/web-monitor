<?php
/**
 * update monitor file.
 */
require_once '_init.php';

// init cache data(db)
$cache = new Cache(DB_PATH);

$method = hz_env_server('REQUEST_METHOD');
switch (strtoupper($method)) {
	case 'PUT':
		update();
		break;
	default:
		out_error('Not support.');
		break;
}


function update() {
	global $cache;

	$data = file_get_contents("php://input");
	$data = json_decode($data, true) or out_error('invalid json parameter.');

	$pk = isset($data['id']) ? $data['id'] : (isset($data['uri']) ? $data['uri'] : '');

	$pk == '' and out_error('not exist key parameter.');
	empty($data['data']) and out_error('not exist data parameter.');

	$filename = md5($pk).'.db';

	$cache->save($filename, json_encode($data['data']), false);

	hz_out(json_encode(array(
		'timestamp'=>date('c', $cache->mtime($filename)),
		'data'=>json_decode($cache->load($filename, 0, false)),
	)), 'json');
}
