<?php
/**
 * A Client can subscribe an interesting resource.
 * Resources are the resource which can be called by clients.
 *
 * ex) /devices, /devices/0/power, ...
 *
 * GET: Read subscribed resource set
 * PUT: Changes subscribed resource set
 * POST: Subscribes new resource set
 * DELETE: Deletes subscribed resource set
 */
require_once '_init.php';


// init cache data(db)
$cache = new Cache(DB_PATH);
$cache->path(DB_SUBSCRIBE, MAX_LIFETIME) or $cache->save(DB_SUBSCRIBE, array());

$subscribes = $cache->load(DB_SUBSCRIBE, MAX_LIFETIME);

$method = hz_env_server('REQUEST_METHOD');
switch (strtoupper($method)) {
	case 'GET':
		retrieve_subscribe();
		break;

	case 'PUT':
		update_subscribe();
		break;

	case 'POST':
		create_subscribe();
		break;

	case 'DELETE':
		delete_subscribe();
		break;
}







function retrieve_subscribe() {
	global $cache, $subscribes;

	$id = hz_env_param('id', '') or out_error('not exist id parameter.');
	isset($subscribes[$id]) or $subscribes[$id] = array();

	return hz_out(json_encode(array(
		'id'=>$id,
		'uris'=>$subscribes[$id],
	)), 'json');


}

function update_subscribe() {
	global $cache, $subscribes;

	$data = file_get_contents("php://input");
	$data = json_decode($data, true) or out_error('invalid json parameter.');
	$uris = array();
	
	$id = hz_env_param('id', '');
	isset($data['id']) and $id = $data['id'];

	$id or out_error('not exist id parameter.');

	if ($data && isset($data['uris'])) {
		$uris = $data['uris'];

		$subscribes[$id] = $uris;
		$cache->save(DB_SUBSCRIBE, $subscribes);
	}

	return hz_out(json_encode(array(
		'id'=>$id,
		'uris'=>$uris
	)), 'json');
}

function create_subscribe() {
	global $cache, $subscribes;

	$data = file_get_contents("php://input");
	$data = json_decode($data, true) or out_error('invalid json parameter.');
	$uris = array();

	$id = generate_subscribe_id();
	if ($data && isset($data['uris'])) {
		$uris = $data['uris'];

		$subscribes[$id] = $uris;
		$cache->save(DB_SUBSCRIBE, $subscribes);
	}

	return hz_out(json_encode(array(
		'id'=>$id,
		'uris'=>$uris
	)), 'json');
}

function delete_subscribe() {
	global $cache, $subscribes;

	$id = hz_env_param('id', '') or out_error('not exist id parameter.');

	unset($subscribes[$id]);
	$cache->save(DB_SUBSCRIBE, $subscribes);

	return hz_out('', 'json');
}



function generate_subscribe_id($len = 5) {
	global $subscribes;

    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randstring = '';

    for ($i = 0; $i < $len; $i++) {
        $randstring .= $characters[rand(0, strlen($characters))];
    }

    return isset($subscribes[$randstring]) ?
    	generate_subscribe_id($len) : $randstring;
}