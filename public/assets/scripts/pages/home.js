/**
 * home.js - Home Page
 */

define([], function () {

	var fn = {

    testimonial: {
      interval: 5000,
      $list: null,
      slide: function () {
        setInterval(function () {
          var $active = fn.testimonial.$list.filter('.active'),
            $next = $active.next();

          if (!$next.length) {
            $next = fn.testimonial.$list.eq(0);
          }

          $active.removeClass('active');
          $next.addClass('active');

        }, fn.testimonial.interval);
      },
      init: function () {
        fn.testimonial.$list = $('.testimonials > ul > li');
        fn.testimonial.slide();
      },
    },
		init: function () {

			fn.testimonial.init();
		}
	};

	return fn;
});