(function($) {
  'use strict';

  // country selector dropdown
  $(document).on('click', '.lang-dropdown', function (e) {
    e.stopPropagation();
    var $dropdown = $('#lang-dropdown');
    if ($dropdown.css('display') === 'block') {
      closeDropDown('lang-dropdown');
    } else {
      upDropDown('lang-dropdown');
    }
  });

  // Handle click outside the dropdown
  var $win = $(window);
  var $box = $('#lang-dropdown');
  $win.on('click.Bst', function (event) {
    if (
      !$box.is(event.target) &&
      $box.has(event.target).length === 0
    ) {
      closeDropDown('lang-dropdown');
    }
  });

  function upDropDown(id) {
    $('#' + id).slideDown(600, function () {
      $(this).css('display', 'block');
    });
  }

  function closeDropDown(id) {
    $('#' + id).slideUp(600, function () {
      $(this).css('display', 'none');
    });
  }

  $(document).on('click', '#lang-dropdown .item a', function (e) {
    e.preventDefault();
    var $selected = $(this).closest('.item');
    var $flagImg = $selected.find('img');
    var flag = $flagImg.attr('src');
    var country = $(this).text();

    $('.lang-dropdown .caption').html(
      '<img src="' + escapeAttr(flag) + '" alt="' + escapeAttr(country) + '"> ' + escapeHtml(country) + ' <img src="assets/images/header-dropdown.png" alt="dropdown">'
    );

    closeDropDown('lang-dropdown');
  });

  // shop page
  $(document).ready(function () {
    $(".size-option").click(function () {
      $(".size-option").removeClass("selected");
      $(this).addClass("selected");
    });
  });

  // Currency selector dropdown toggle
  $(document).on('click', '.curr-dropdown', function (e) {
    e.stopPropagation();
    var $dropdown = $('#curr-dropdown');
    if ($dropdown.css('display') === 'block') {
      closeDropDown('curr-dropdown');
    } else {
      upDropDown('curr-dropdown');
    }
  });

  var $win = $(window);
  var $boxCurr = $('#curr-dropdown');
  $win.on('click.Curr', function (event) {
    if (
      !$boxCurr.is(event.target) &&
      $boxCurr.has(event.target).length === 0
    ) {
      closeDropDown('curr-dropdown');
    }
  });

  $(document).on('click', '#curr-dropdown .item a', function (e) {
    e.preventDefault();
    var currency = $(this).text();
    $('.curr-dropdown .caption').html(escapeHtml(currency) + ' <img src="assets/images/header-dropdown.png" alt="currency dropdown">');
    closeDropDown('curr-dropdown');
  });

  function escapeHtml(str) {
    return String(str).replace(/[&<>"']/g, function(m) {
      if (m === '&') return '&amp;';
      if (m === '<') return '&lt;';
      if (m === '>') return '&gt;';
      if (m === '"') return '&quot;';
      if (m === "'") return '&#39;';
      return m;
    });
  }

  function escapeAttr(str) {
    return String(str).replace(/[&"'<>]/g, function(m) {
      if (m === '&') return '&amp;';
      if (m === '"') return '&quot;';
      if (m === "'") return '&#39;';
      if (m === '<') return '&lt;';
      if (m === '>') return '&gt;';
      return m;
    });
  }
})(jQuery);
