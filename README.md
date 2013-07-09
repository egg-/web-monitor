# Web/Monitor

Project to test a simple Push mechanism using the Long Polling.

## $monitor

```js
// update api urls
$monitor.apis({
	subscribe: '/subscribe.php',
	notify: '/notify.php'
});

// change the default settings.
$monitor.setting({
	longpolling: true,
	timeout: 30000,	// 30 sec
	interval: 3000,	// 3 sec (use if longpoling is false)
});

// start monitoring
$monitor.start([{ uri: '/devices' }, { uri: '/accounts' }], {
	success: function(res, status, xhr) {
		// ...
	},
	complete: function(xhr, status) {
		// ...
	}
});

// stop monitor
$monitor.stop();
```

## API

- subscribe.php: subscribe an interesting resource
  - create
```js
POST /subscribe.php HTTP/1.1
Host: dev.monitor
Cache-Control: no-cache

{ "id" : "", "uris": [ { "uri" : "/devices"}, { "uri" : "/accounts"} ] }
```
 - delete
```js
DELETE /subscribe.php?id=4mqyg HTTP/1.1
Host: dev.monitor
Cache-Control: no-cache
```
 - retrieve
```js
GET /subscribe.php?id=4mqyg HTTP/1.1
Host: dev.monitor
Cache-Control: no-cache
```
 - update
```js
PUT /subscribe.php HTTP/1.1
Host: dev.monitor
Cache-Control: no-cache

{ "id" : "4mqyg", "uris": [ { "uri" : "/devices"}, { "uri" : "/accounts"} ] }
```
- notify.php: to get notification
```js
GET /notify.php?id=4mqyg HTTP/1.1
Host: dev.monitor
Cache-Control: no-cache
```
- event.php: update data an interesting resource
```js
PUT /event.php HTTP/1.1
Host: dev.monitor
Cache-Control: no-cache

{ "uri" : "/devices", "data": [ { "uri" : "/devices"}, { "uri" : "/accounts"} ] 
```

## postman
 - http://www.getpostman.com/collections/85fe08948dd82861c123