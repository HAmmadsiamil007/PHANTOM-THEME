/**
 * Customer Auth — Login / Recover Password toggle
 *
 * Handles:
 * - #recover hash detection on page load (auto-show recover form)
 * - Click on [data-recover-toggle] to show recover form
 * - Click on [data-cancel-recover] to return to login form
 */

(function () {
  'use strict';

  var loginForm = document.querySelector('[data-login-form]');
  var recoverForm = document.querySelector('[data-recover-form]');

  if (!loginForm || !recoverForm) return;

  function showLogin() {
    loginForm.classList.remove('d-none');
    recoverForm.classList.add('d-none');
    history.pushState(null, '', window.location.pathname);
  }

  function showRecover() {
    loginForm.classList.add('d-none');
    recoverForm.classList.remove('d-none');
    history.pushState(null, '', '#recover');
    recoverForm.querySelector('input')?.focus();
  }

  // Auto-show recover if URL hash is #recover
  if (window.location.hash === '#recover') {
    showRecover();
  }

  // Toggle to recover form
  document.querySelector('[data-recover-toggle]')?.addEventListener('click', function (e) {
    e.preventDefault();
    showRecover();
  });

  // Return to login form
  document.querySelector('[data-cancel-recover]')?.addEventListener('click', function () {
    showLogin();
  });
})();
