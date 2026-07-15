(function ($) {
  var $style = $('#optix-dynamic-css');
  if (!$style.length) {
    $style = $('<style id="optix-dynamic-css"></style>').appendTo('head');
  }

  // ── Color Variables ─────────────────────────────────────────
  var colorMap = {
    color_primary:      '--primary--color',
    color_secondary:    '--secondary--color',
    color_accent:       '--accent--color',
    color_text:         '--text--color',
    color_heading:      '--text--color2',
    color_background:   '--text--color3',
    color_header_bg:    '--header--bg--color',
    color_footer_bg:    '--footer--bg--color',
    color_link:         '--link--color',
    color_link_hover:   '--link--hover--color',
    color_border:       '--border--color',
    color_sale:         '--sale--color',
    button_bg:          '--button--bg',
    button_text:        '--button--text',
    button_bg_hover:    '--button--bg--hover',
    button_text_hover:  '--button--text--hover',
    topbar_bg:          '--topbar--bg',
    topbar_text:        '--topbar--text',
    footer_text:        '--footer--text',
    footer_heading_text: '--footer--heading--text',
    footer_link:        '--footer--link',
  };

  $.each(colorMap, function (settingKey, cssVar) {
    wp.customize(settingKey, function (value) {
      value.bind(function (newVal) {
        document.documentElement.style.setProperty(cssVar, newVal);
      });
    });
  });

  // ── Numeric CSS property bindings ───────────────────────────
  wp.customize('button_radius', function (value) {
    value.bind(function (newVal) {
      document.documentElement.style.setProperty('--button--radius', newVal + 'px');
    });
  });
  wp.customize('button_padding_y', function (value) {
    value.bind(function (newVal) {
      document.documentElement.style.setProperty('--button--padding--y', newVal + 'px');
    });
  });
  wp.customize('button_padding_x', function (value) {
    value.bind(function (newVal) {
      document.documentElement.style.setProperty('--button--padding--x', newVal + 'px');
    });
  });
  wp.customize('header_height', function (value) {
    value.bind(function (newVal) {
      document.documentElement.style.setProperty('--header--height', newVal + 'px');
    });
  });

  // ── Typography ─────────────────────────────────────────────
  wp.customize('typography_heading_font', function (value) {
    value.bind(function (newVal) {
      if (newVal && newVal !== 'System Default') {
        $('h1, h2, h3, h4, h5, h6').css('font-family', "'" + newVal + "', sans-serif");
      } else {
        $('h1, h2, h3, h4, h5, h6').css('font-family', '');
      }
    });
  });

  wp.customize('typography_body_font', function (value) {
    value.bind(function (newVal) {
      if (newVal && newVal !== 'System Default') {
        $('body').css('font-family', "'" + newVal + "', sans-serif");
      } else {
        $('body').css('font-family', '');
      }
    });
  });

  wp.customize('typography_base_size', function (value) {
    value.bind(function (newVal) {
      $('body').css('font-size', newVal + 'px');
    });
  });

  wp.customize('typography_body_weight', function (value) {
    value.bind(function (newVal) {
      $('body').css('font-weight', newVal);
    });
  });

  wp.customize('typography_heading_weight', function (value) {
    value.bind(function (newVal) {
      $('h1, h2, h3, h4, h5, h6').css('font-weight', newVal);
    });
  });

  wp.customize('typography_line_height', function (value) {
    value.bind(function (newVal) {
      $('body').css('line-height', newVal);
    });
  });

  wp.customize('typography_letter_spacing', function (value) {
    value.bind(function (newVal) {
      $('body').css('letter-spacing', newVal + 'px');
    });
  });
})(jQuery);
