/**
 * WooCommerce Products Carousel – Frontend Script
 * Initializes Splide sliders with dynamic settings from admin panel
 */

(function ($) {
	'use strict';

	$(document).ready(function () {
		$('.splide-normal').each(function () {
			new Splide(this, {
				type: wpcSplideSettings.loop ? 'loop' : 'slide',
				perPage: parseInt(wpcSplideSettings.perPage) || 4,
				autoplay: !!wpcSplideSettings.autoplay,
				arrows: !!wpcSplideSettings.arrows,
				pagination: !!wpcSplideSettings.pagination,
				gap: wpcSplideSettings.gap || '1rem',
				breakpoints: {
					1024: { perPage: 3 },
					768: { perPage: 2 },
					480: { perPage: 1 },
				},
			}).mount();
		});
	});
})(jQuery);


