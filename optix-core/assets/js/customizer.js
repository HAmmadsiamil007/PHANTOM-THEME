(function ($) {
	'use strict';

	if (typeof optixCustomizer === 'undefined') {
		return;
	}

	var cssVarSettings = optixCustomizer.cssVarSettings || [];
	var regenerateSettings = optixCustomizer.regenerateSettings || [];

	$.each(cssVarSettings, function (i, config) {
		wp.customize('optix_' + config.key, function (value) {
			value.bind(function (newValue) {
				var selector = config.selector || ':root';
				var property = config.property;
				var $el = selector === ':root' ? $('html') : $(selector);
				$el.css(property, newValue);
			});
		});
	});

	$.each(regenerateSettings, function (i, key) {
		wp.customize('optix_' + key, function (value) {
			value.bind(function () {
				wp.customize.previewer.refresh();
			});
		});
	});

})(jQuery);
