/*
 * First Visit Popup jQuery Plugin version 1.2
 * Chris Cook - chris@chris-cook.co.uk
 */

(function ($) {



	'use strict';

	$.fn.firstVisitPopup = function (settings) {

		var $body = $('body');
		var $dialog = $(this);
		var $blackout;
		var setCookie = function (name, value) {
			var date = new Date(),
				expires = 'expires=';
			date.setTime(date.getTime() + 31536000000);
			expires += date.toGMTString();
			document.cookie = name + '=' + value + '; ' + expires + '; path=/';
		}
		var getCookie = function (name) {
			var allCookies = document.cookie.split(';'),
				cookieCounter = 0,
				currentCookie = '';
			for (cookieCounter = 0; cookieCounter < allCookies.length; cookieCounter++) {
				currentCookie = allCookies[cookieCounter];
				while (currentCookie.charAt(0) === ' ') {
					currentCookie = currentCookie.substring(1, currentCookie.length);
				}
				if (currentCookie.indexOf(name + '=') === 0) {
					return currentCookie.substring(name.length + 1, currentCookie.length);
				}
			}
			return false;
		}
		var showMessage = function () {
			$blackout.show();
			$dialog.show();
		}
		var hideMessage = function () {
			$blackout.hide();
			$dialog.hide();
			setCookie('nuno-sarmento-popup' + settings.cookieName, 'true');
		}

		$body.append('<div id="nuno-sarmento-popup-blackout"></div>');
		$dialog.append('<a id="nuno-sarmento-popup-close">&#10006;</a>');
		$blackout = $('#nuno-sarmento-popup-blackout');

		if (getCookie('nuno-sarmento-popup' + settings.cookieName)) {
			hideMessage();
		} else {
			showMessage();
		}

		$(settings.showAgainSelector).on('click', showMessage);
		$body.on('click', '#nuno-sarmento-popup-blackout, #nuno-sarmento-popup-close', hideMessage);

	};




	function PopupCenter(url, title, w, h) {
		// Fixes dual-screen position                         Most browsers      Firefox
		var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : screen.left;
		var dualScreenTop = window.screenTop != undefined ? window.screenTop : screen.top;

		width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
		height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

		var left = ((width / 2) - (w / 2)) + dualScreenLeft;
		var top = ((height / 2) - (h / 2)) + dualScreenTop;
		var newWindow = window.open(url, title, 'scrollbars=yes, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);

		// Puts focus on the newWindow
		if (window.focus) {
			newWindow.focus();
		}
	}



})(jQuery);
