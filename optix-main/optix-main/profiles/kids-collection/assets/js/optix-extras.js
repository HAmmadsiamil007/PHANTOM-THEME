/**
 * Optix Extras — Mega Menu, 3D Tilt, and enhancements.
 */
(function($) {
  'use strict';

  // ── Mega Menu Toggle ───────────────────────────────────────────
  function initMegaMenu() {
    $('.menu-item-has-children.mega-menu > a').on('click', function(e) {
      if ($(window).width() < 992) {
        e.preventDefault();
        $(this).parent().toggleClass('mega-open');
      }
    });
  }

  // ── 3D Tilt Effect ─────────────────────────────────────────────
  function initTilt3D() {
    $('.tilt-3d').each(function() {
      var $el = $(this);
      $el.on('mousemove', function(e) {
        var rect = this.getBoundingClientRect();
        var x = e.clientX - rect.left;
        var y = e.clientY - rect.top;
        var midX = rect.width / 2;
        var midY = rect.height / 2;
        var rotateY = ((x - midX) / midX) * 5;
        var rotateX = ((midY - y) / midY) * 5;
        $el.css('transform', 'rotateX(' + rotateX + 'deg) rotateY(' + rotateY + 'deg)');
      });
      $el.on('mouseleave', function() {
        $el.css('transform', 'rotateX(0deg) rotateY(0deg)');
      });
    });
  }

  // ── Initialize ─────────────────────────────────────────────────
  $(document).ready(function() {
    initMegaMenu();
    initTilt3D();
  });

})(jQuery);
