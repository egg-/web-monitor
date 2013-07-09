<?php
/**
 * A client queries the subscribe resource set to get notifications
 *
 * GET: Getting notifications of the subscribed resource set
 */
require_once '_init.php';

// init cache data(db)
$cache = new Cache(DB_PATH);
$cache->path(DB_SUBSCRIBE, MAX_LIFETIME) or $cache->save(DB_SUBSCRIBE, array());

$subscribes = $cache->load(DB_SUBSCRIBE, MAX_LIFETIME);

$method = hz_env_server('REQUEST_METHOD');
switch (strtoupper($method)) {
	case 'GET':
		monitor();
		break;
	default:
		out_error('Not support.');
		break;
}


function monitor() {
	global $cache, $subscribes;

	$id = hz_env_param('id', '') or out_error('not exist id parameter.');

	$target = isset($subscribes[$id]) ? $subscribes[$id] : array();
	// filter valid uri
	$uris = array();
	foreach ($target as $item) {
		isset($item['uri']) and $uris[] = $item['uri'];
	}

	$request_time = time();
	set_time_limit(MAX_WAITTIME + 5);

	// waiting for data file is updated.
	$events = array();
	while ((time() - $request_time) < MAX_WAITTIME) {
		$events = check_events($uris, $request_time);
		if (empty($events) == false) {
			break;;
		}

		usleep(USLEEP_TIME);
	}

	hz_out(json_encode(array(
		'id'=>$id,
		'events'=>$events
	)), 'json');
}

function check_events($uris, $request_time) {
	global $cache;

	$events = array();

	clearstatcache();
	foreach ($uris as $uri) {
		$filename = md5($uri).'.db';
		$mtime = $cache->mtime($filename);
		
		if ($mtime > $request_time) {
			$events[] = array('uri'=>$uri, 'timestamp'=>date('c', $mtime));
		}
	}

	return $events;
}