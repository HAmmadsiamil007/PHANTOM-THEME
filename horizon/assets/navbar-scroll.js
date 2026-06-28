/**
 * Navbar Scroll — auto-hide/show mobile navbar based on scroll
 * direction, plus a scroll progress indicator bar beneath the navbar.
 *
 * Progress bar measures how far the user has scrolled through the
 * page content: 0% at top, 100% at the very bottom (document height
 * minus viewport height).
 *
 * Uses IntersectionObserver on a 1px sentinel element placed just before
 * the navbar. When the sentinel is NOT intersecting (scrolled past it),
 * scroll direction is tracked:
 *   - Scrolling down → navbar slides up/hides
 *   - Scrolling up   → navbar slides back into view
 * At the top of the page (sentinel intersecting), navbar is always visible.
 *
 * Requires: a <div id="navbar-sentinel"></div> placed before <navbar-wrapper>.
 */
(function () {
  'use strict';

  var navbar = document.getElementById('navbar-mobile');
  var sentinel = document.getElementById('navbar-sentinel');
  var progressBar = document.querySelector('.scroll-progress-bar');

  // Exit if mobile navbar or sentinel doesn't exist
  if (!navbar || !sentinel) return;

  var lastScrollY = window.scrollY;
  var hasScrolledPast = false;
  var ticking = false;

  // ─── CSS classes ────────────────────────────────────────────

  var HIDDEN_CLASS = 'navbar-mobile-hidden';
  var REVEAL_CLASS = 'navbar-mobile-reveal';
  var SCROLLED_CLASS = 'navbar-mobile-scrolled';

  // ─── Scroll progress calculation ────────────────────────────

  function updateProgress() {
    if (!progressBar) return;

    var scrollTop = window.scrollY;
    var docHeight = document.documentElement.scrollHeight;
    var winHeight = window.innerHeight;
    var scrollable = docHeight - winHeight;

    // Guard against division by zero (content shorter than viewport)
    if (scrollable <= 0) {
      progressBar.style.width = '0%';
      progressBar.setAttribute('aria-valuenow', '0');
      return;
    }

    var percent = Math.min((scrollTop / scrollable) * 100, 100);
    progressBar.style.width = percent.toFixed(1) + '%';
    progressBar.setAttribute('aria-valuenow', Math.round(percent));
  }

  // ─── Scroll direction handler ───────────────────────────────

  function handleScroll() {
    var currentScrollY = window.scrollY;

    // Always update progress bar regardless of scroll state
    updateProgress();

    // Don't run auto-hide if we haven't scrolled past the sentinel
    if (!hasScrolledPast) {
      lastScrollY = currentScrollY;
      ticking = false;
      return;
    }

    var delta = currentScrollY - lastScrollY;

    // Scrolling down — hide
    if (delta > 5) {
      navbar.classList.add(HIDDEN_CLASS);
      navbar.classList.remove(REVEAL_CLASS);
    }
    // Scrolling up — show
    else if (delta < -5) {
      navbar.classList.remove(HIDDEN_CLASS);
      navbar.classList.add(REVEAL_CLASS);
    }

    lastScrollY = currentScrollY;
    ticking = false;
  }

  function requestTick() {
    if (!ticking) {
      window.requestAnimationFrame(handleScroll);
      ticking = true;
    }
  }

  // ─── IntersectionObserver on sentinel ───────────────────────

  var observer = new IntersectionObserver(function (entries) {
    entries.forEach(function (entry) {
      if (entry.isIntersecting) {
        // At top of page — always show navbar, no shadow
        hasScrolledPast = false;
        navbar.classList.remove(HIDDEN_CLASS, REVEAL_CLASS, SCROLLED_CLASS);
      } else {
        // Scrolled past the sentinel — add elevation shadow
        hasScrolledPast = true;
        lastScrollY = window.scrollY;
        navbar.classList.add(SCROLLED_CLASS);
      }
    });
  }, {
    rootMargin: '0px',
    threshold: 0,
  });

  observer.observe(sentinel);

  // ─── Initialize progress bar on load ────────────────────────
  updateProgress();

  // ─── Recalculate on resize (content height may change) ──────
  window.addEventListener('resize', updateProgress, { passive: true });

  // ─── Throttled scroll listener for direction tracking ───────

  window.addEventListener('scroll', requestTick, { passive: true });

  // ─── Ensure navbar is visible on pages with minimal content ──
  // If the sentinel never leaves the viewport, navbar stays visible.
})();
