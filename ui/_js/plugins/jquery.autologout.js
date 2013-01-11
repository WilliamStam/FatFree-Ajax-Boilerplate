/* 
 *	autoLogout: a jQuery plugin, version: 0.1.1 (2010-08-23)
 *
 *
 *	thanks mekwall (on #jQuery) for the tips :D
 *
 *
 *	Licensed under the MIT:
 *	http://www.opensource.org/licenses/mit-license.php
 *
 *	Copyright (c) 2010, William Stam (awstam -[at]- gmail [*dot*] com)

 -------- Change log ---------

 25 Septs 2010	-	added {m} to format options
 */
(function ($) {
	// --------------------------------------plugins methods--------------------------------------------------
	var methods = {
		init      :function (options) {
			return this.each(function () {
				var $this = $(this);
				$this.data("settings", options).data("timer",0);
				doTimer($this);
			});
		},
		logout    :function () {
			return this.each(function () {
				var $this = $(this);
				$this.data("forceLogout", "force");
				$this.data("remainingSeconds", 0);
				$this.data('countShow', "0");
				doLogout($this);
			});
		},
		resetTimer:function (e) {
			return this.each(function () {
				var $this = $(this);
				doResetTimer($this)
			});
		}
	};
	// ----------------------------------------------------------------------------------------------------

	// ------------------------------------plugins functions-----------------------------------------------
	// the counter plugin
	var doTimer = function (e) {

		function timedCount() {
			var t, options = e.data("settings"), timer = e.data("timer")||0;
			clearTimeout(t);
			 timer++;

			e.data("timer", timer);
			options.onTimerSecond.call(e, timer);


			if (e.data("timer")== options.LogoutTime) {
				options.onLogout.call(e, timer);
			} else {
				t = setTimeout(timedCount, 1000);
			}




		}

		timedCount();
	};
	// reset the timer function
	var doResetTimer = function (e) {
		var options = e.data("settings"), timer = e.data("timer");
		//e.data("timer", 0);
		//doTimer(e);

		//console.log(timer)
		//console.log(options.LogoutTime)

		e.data("timer", 0);
		if (timer>=options.LogoutTime){
			doTimer(e);
		}

	};
	// the function that does the logging out
	var doLogout = function (e) {
		var $this = e, options = $this.data("settings");


	};
	// ----------------------------------------------------------------------------------------------------
	$.fn.autoLogout = function (method) {
		var options = {
			LogoutTime          :'30',
			ShowLogoutCountdown :'5',
			onLogoutCountdown: function(){},
			onResetTimer: function(){},
			onTimerSecond: function(){},
			keepAliveSelector   :"",
			logout              :"none",
			keepAlive           :function () {
			},
			countingDownLook    :"",
			countingDownLookShow:""
		};
		if (method) {
			var settings = arguments[1];
			if (typeof method === "object") {
				var settings = arguments[0];
			}
			var options = $.extend({}, options, settings);
		}
		options = $.makeArray(options);
		if (!method || method == 'remainingSeconds') {
			return $(this).data("remainingSeconds");
		} else if (methods[method]) {
			var old = $(this).data("settings");
			if (old) {
				options = $.extend({}, old, settings);
				options = $.makeArray(options);
			}
			return methods[method].apply(this, options);
		} else if (typeof method === 'object') {
			var old = $(this).data("settings");
			if (old) {
				options = $.extend({}, old, settings);
				options = $.makeArray(options);
			}

			return methods.init.apply(this, options); // if theres options passed to the plugin create the timer
		} else {
			$.error('Method ' + method + ' does not exist on jQuery.autoLogout');
		}

	};
})(jQuery);



// a