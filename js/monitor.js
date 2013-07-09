/**
 * $monitor
 * https://github.com/egg-
 *
 * @version 0.90
 *
 * subscribe and notify manager
 * @dep $polling
 */
var $monitor = (function($, $polling) {
	var module = {};
	var subscribe_id = null;
	var apis = {
		subscribe: '/subscribe',
		notify: '/notify'
	};
	var xhr = null;

	/**
	 * update api urls
	 * @param {object} urls
	 */
	module.apis = function(urls) {
		apis = $.extend(apis, urls);
	};

	/**
	 * update global setting
	 * @param {object} opt
	 */
	module.setting = function(opt) {
		$polling.setting(opt);
	};

	/**
	 * start monitoring
	 * @param {array} uris uri list to be monitoredd
	 * @param {object} opt ajax option
	 */
	module.start = function(uris, opt) {
		var datastr = JSON.stringify({
			id: subscribe_id ? subscribe_id : '',
			uris: uris
		});

		xhr = $.ajax({
			url: apis.subscribe,
			type: subscribe_id ? 'PUT' : 'POST',
			data: datastr,
			dataType: opt.dataType || 'json',
			success: function(res, status, xhr) {
				subscribe_id = res.id;

				opt.url = apis.notify;
				opt.data = $.extend({
					id: subscribe_id
				}, opt.data || {});

				$polling.start(subscribe_id, opt);
			},
			complete: function() {
				xhr = null;
			}
		});

		return xhr;
	};

	/**
	 * stop monitoring
	 */
	module.stop = function() {
		$polling.stop(subscribe_id);

		xhr && xhr.abort();
		xhr = null;

		// delete subscribe
		subscribe_id && $.ajax({
			url: apis.subscribe + '?id=' + subscribe_id,
			type: 'DELETE',
			dataType: 'json'
		});
		subscribe_id = null;

		return true;
	};

	return module;
}(jQuery, $polling));