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

### subscribe.php

subscribe an interesting resource

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

### notify.php

to get notification

```js
GET /notify.php?id=4mqyg HTTP/1.1
Host: dev.monitor
Cache-Control: no-cache

{"id":"2rTW1","events":[{"uri":"\/device\/37aa385a+7XCA3K5OC4PRQ+ec652a5a36c2a15c0884554f1052f09f71dc1b88\/power"},{"uri":"\/account\/tester\/logout"}]}
```

### event.php

update data an interesting resource

```js
PUT /event.php HTTP/1.1
Host: dev.monitor
Cache-Control: no-cache

{ "uri" : "/devices", "data": [ { "uri" : "/devices"}, { "uri" : "/accounts"} ] 
```

```js
PUT /event.php HTTP/1.1
Host: dev.monitor
Cache-Control: no-cache

{ "id" : "2rTW1", "data": [ { "uri" : "/device/37aa385a+7XCA3K5OC4PRQ+ec652a5a36c2a15c0884554f1052f09f71dc1b88/power" }, { "uri" : "/account/tester/logout" } ] }
```


## postman
 - postman.json
 - http://www.getpostman.com/collections/691c1a7f2fb128823477
