/**
 * Kids Collection Theme JavaScript
 * Preserving all original template functionality
 */
(function($) {
  'use strict';

  // Back to top button
  function initBackToTop() {
    var btn = $('#button');
    $(window).scroll(function() {
      if ($(window).scrollTop() > 300) {
        btn.addClass('show');
      } else {
        btn.removeClass('show');
      }
    });
    btn.on('click', function(e) {
      e.preventDefault();
      $('html, body').animate({scrollTop: 0}, 300);
    });
  }

  // Preloader (called from window.load handler below, so execute directly)
  function initPreloader() {
    $('.loader-mask').fadeOut();
  }

  // Search toggle
  function initSearch() {
    $('a[href="#search"]').on('click', function(event) {
      event.preventDefault();
      $('#search').addClass('open');
      $('#search > form > input[type="search"]').focus();
    });
    $('#search, #search .close').on('click keyup', function(event) {
      if (event.target == this || event.target.className == 'close' || event.keyCode == 27) {
        $(this).removeClass('open');
      }
    });
  }

  // Language/Currency dropdowns
  function initDropdowns() {
    $('.country-selector .caption, .currency-selector .caption').on('click', function() {
      $(this).siblings('.list').slideToggle();
    });
    $(document).on('click', function(e) {
      if (!$(e.target).closest('.lang-dropdown, .curr-dropdown').length) {
        $('.lang-dropdown .list, .curr-dropdown .list').slideUp();
      }
    });
  }

  // Product quantity
  function initQuantity() {
    $(document).on('click', '.decrease-button', function() {
      var num = $(this).siblings('.number');
      var val = parseInt(num.text());
      if (val > 1) num.text(val - 1);
    });
    $(document).on('click', '.increase-button', function() {
      var num = $(this).siblings('.number');
      num.text(parseInt(num.text()) + 1);
    });
  }

  // Remove product from cart
  function initRemoveProduct() {
    $(document).on('click', '.remove-product', function() {
      $(this).closest('.product').fadeOut(300, function() { $(this).remove(); });
    });
  }

  // Price range filter
  function initPriceRange() {
    var lowerSlider = document.querySelector('#lower');
    var upperSlider = document.querySelector('#upper');
    if (lowerSlider && upperSlider) {
      lowerSlider.oninput = function() {
        document.querySelector('#one').value = this.value;
        if (parseInt(upperSlider.value) < parseInt(this.value)) this.value = upperSlider.value;
      };
      upperSlider.oninput = function() {
        document.querySelector('#two').value = this.value;
        if (parseInt(this.value) < parseInt(lowerSlider.value)) this.value = lowerSlider.value;
      };
    }
  }

  // WOW animations
  function initWOW() {
    if (typeof WOW !== 'undefined') {
      new WOW({ mobile: false }).init();
    }
  }

  // Initialize all
  $(document).ready(function() {
    initBackToTop();
    initSearch();
    initDropdowns();
    initQuantity();
    initRemoveProduct();
    initPriceRange();
  });

  $(window).on('load', function() {
    initWOW();
    initPreloader();
  });

})(jQuery);
