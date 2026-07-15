(function () {
  'use strict';
  var deferredStyles = document.querySelectorAll('link[data-defer-style]');
  function loadDeferredStyles() {
    deferredStyles.forEach(function (link) {
      link.setAttribute('rel', 'stylesheet');
      link.removeAttribute('data-defer-style');
    });
    document.removeEventListener('scroll', loadDeferredStyles);
    document.removeEventListener('mousemove', loadDeferredStyles);
    document.removeEventListener('touchstart', loadDeferredStyles);
  }
  document.addEventListener('scroll', loadDeferredStyles);
  document.addEventListener('mousemove', loadDeferredStyles);
  document.addEventListener('touchstart', loadDeferredStyles);
})();
