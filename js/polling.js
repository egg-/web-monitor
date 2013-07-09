/**
 * $polling
 * https://github.com/egg-
 *
 * @version 0.90
 *
 * polling module based jQuery
 */
var $polling = (function($) {
	var module = {};
	var maps = {};
	var timer = {};
	var settings = {
		longpolling: false,
		timeout: 30000,	// 30 sec
		interval: 3000,	// 3 sec
	};

	/**
	 * update global setting
	 * @param {object} opt
	 */
	module.setting = function(opt) {
		settings = $.extend(settings, opt);
	};

	/**
	 * start polling
	 * @param {string} uid user defined unique id for distinct polling request.
	 * @param {object} opt
	 */
	module.start = function(uid, opt) {
		var origin_complete = $.isFunction(opt.complete) ? opt.complete : function() {};

		// stop previous polling
		this.stop(uid);
		
		// reset
		maps[uid] = true;
		opt = $.extend(settings, opt);

		// @ref
		// http://techoctave.com/c7/posts/60-simple-long-polling-example-with-javascript-and-jquery
		(function poll() {
			// check whether to continue polling
			if (maps[uid] == null) {
				return false;
			}

			maps[uid] = $.ajax($.extend(opt, {
				complete: function(xhr, status) {
					origin_complete(xhr, status);
					
					if (status == 'abort' || status == 'error') {
						return true;
					}

					if (opt.longpolling) {
						poll();
					} else {
						timer[uid] && clearTimeout(timer[uid]);
						timer[uid] = setTimeout(function() {
							poll();
						}, opt.interval);
					}
				}
			}));
		})();

		return maps[uid];
	};

	/**
	 * stop polling
	 * @param {string} uid user defined unique id for distinct polling request.
	 */
	module.stop = function(uid) {
		maps[uid] && maps[uid].abort();
		maps[uid] = null;

		timer[uid] && clearTimeout(timer[uid]);
		timer[uid] = null;

		return true;
	};

	return module;
}(jQuery));