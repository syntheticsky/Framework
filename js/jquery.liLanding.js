/*
 * jQuery liLanding v 1.0
 *
 * Copyright 2013, Linnik Yura | LI MASS CODE | http://masscode.ru
 * Free to use
 *
 * 03.12.2013
 */
(function ($) {
	var methods = {
		init: function (options) {
			var p = {
				show: function (linkEl, landingItem) {}, 
				hide: function (linkEl, landingItem) {}
			};
			if (options) {
				$.extend(p, options);
			}
			return this.each(function () {
				var el = $(this);
				var elPos = el.offset().top;
				var wHalf = $(window).height()/2
				var scrollId = function(){};
				
				//assign events only links with anchors


				$(window).on('scroll',function(e){
					clearTimeout(scrollId);
					var windowPos = $(window).scrollTop();
					if(windowPos > elPos){
						el.addClass('landingFix');
						$('.header').stop(true);
						$('.header').animate({'height': '200px'});	
						$('.header_content').animate({'height': '200px' }, {queue:false});
					}else {
						el.removeClass('landingFix');
						$('.header').stop(true);
						$('.header').animate({'height': '223px' });
						$('.header_content').animate({'height': '223px' }, {queue:false});
					}
					
				})
			});
		}
	};
	$.fn.liLanding = function (method) {
		if (methods[method]) {
			return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
		} else if (typeof method === 'object' || !method) {
			return methods.init.apply(this, arguments);
		} else {
			$.error('Метод ' + method + ' в jQuery.liLanding не существует');
		}
	};
})(jQuery);