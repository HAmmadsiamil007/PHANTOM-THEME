(function ($) {
  var $body = $(document.body);

  // Mini-cart toggle on header cart icon
  $('.header-con .cart').on('click', function (e) {
    if ($(this).find('.mini-cart-dropdown').length) {
      e.preventDefault();
      $(this).find('.mini-cart-dropdown').toggleClass('is-visible');
    }
  });

  // Close mini-cart when clicking outside
  $(document).on('click', function (e) {
    if (!$(e.target).closest('.header-con .cart').length) {
      $('.mini-cart-dropdown').removeClass('is-visible');
    }
  });

  // Re-bind mini-cart toggle after WC fragment refresh
  $body.on('wc_fragments_refreshed wc_fragments_loaded', function () {
    $('.mini-cart-dropdown').removeClass('is-visible');
  });
})(jQuery);
