/**
 * Vanilla JS Offcanvas Handler
 * Replaces Bootstrap's offcanvas JavaScript for navbar drawers and blog tag panel.
 *
 * Handles:
 * - Click on [aria-controls^="offcanvas-"] or [href^="#offcanvas-"] triggers
 * - Animation classes (offcanvas--opening, offcanvas--closing, is-open)
 * - Backdrop overlay with click-to-close
 * - Escape key to close
 * - Close buttons inside offcanvas (btn-close, [data-offcanvas-dismiss])
 * - Focus trap while open
 * - Body scroll lock
 * - Cart drawer delegation to theme-drawer web component
 */

(function () {
  'use strict';

  // ─── State ────────────────────────────────────────────────────────────────

  /** @type {HTMLElement|null} */
  var currentOffcanvas = null;
  /** @type {HTMLElement|null} */
  var backdrop = null;
  /** @type {HTMLElement|null} */
  var lastTrigger = null;
  /** @type {boolean} */
  var isTransitioning = false;

  // ─── Backdrop ─────────────────────────────────────────────────────────────

  function createBackdrop() {
    var isMobile = window.innerWidth <= 749;
    var duration = isMobile ? '0.2s' : '0.3s';
    var el = document.createElement('div');
    el.className = 'offcanvas-backdrop';
    el.style.cssText =
      'position:fixed;inset:0;z-index:1040;background:rgba(0,0,0,0.5);opacity:0;transition:opacity ' + duration + ' ease;';
    document.body.appendChild(el);

    // Trigger reflow then fade in
    el.offsetHeight; // eslint-disable-line no-unused-expressions
    el.style.opacity = '1';

    el.addEventListener('click', function () {
      closeOffcanvas(currentOffcanvas);
    });

    return el;
  }

  function removeBackdrop() {
    if (!backdrop) return;
    backdrop.style.opacity = '0';
    backdrop.addEventListener('transitionend', function handler() {
      backdrop.removeEventListener('transitionend', handler);
      if (backdrop && backdrop.parentNode) {
        backdrop.parentNode.removeChild(backdrop);
      }
      backdrop = null;
    });
  }

  // ─── Lock / Unlock scroll ─────────────────────────────────────────────────

  function lockScroll() {
    var scrollY = window.scrollY;
    document.documentElement.style.setProperty('--offcanvas-scroll-y', scrollY + 'px');
    document.body.classList.add('offcanvas-lock');
    document.body.dataset.offcanvasOpen = 'true';
  }

  function unlockScroll() {
    document.body.classList.remove('offcanvas-lock');
    delete document.body.dataset.offcanvasOpen;
  }

  // ─── Focus trap ───────────────────────────────────────────────────────────

  function trapFocus(el) {
    var focusable = el.querySelectorAll(
      'a[href], button:not([disabled]), textarea:not([disabled]), input:not([disabled]), select:not([disabled]), [tabindex]:not([tabindex="-1"])'
    );
    if (focusable.length === 0) return;

    var first = focusable[0];
    var last = focusable[focusable.length - 1];

    function handleTab(e) {
      if (e.key !== 'Tab') return;

      if (e.shiftKey) {
        if (document.activeElement === first) {
          e.preventDefault();
          last.focus();
        }
      } else {
        if (document.activeElement === last) {
          e.preventDefault();
          first.focus();
        }
      }
    }

    el.addEventListener('keydown', handleTab);
    // Store reference so we can clean up
    el._offcanvasFocusHandler = handleTab;
    first.focus();
  }

  function releaseFocus(el) {
    if (el._offcanvasFocusHandler) {
      el.removeEventListener('keydown', el._offcanvasFocusHandler);
      delete el._offcanvasFocusHandler;
    }
  }

  // ─── Open / Close ─────────────────────────────────────────────────────────

  function returnFocus() {
    if (lastTrigger) {
      lastTrigger.focus();
      lastTrigger = null;
    }
  }

  function openOffcanvas(el, trigger) {
    if (isTransitioning || !el) return;

    // Store trigger for focus return
    lastTrigger = trigger || null;

    // Close any currently open offcanvas first
    if (currentOffcanvas && currentOffcanvas !== el) {
      closeImmediately(currentOffcanvas);
    }

    isTransitioning = true;
    currentOffcanvas = el;

    // Create backdrop
    if (!backdrop) {
      backdrop = createBackdrop();
    }

    lockScroll();

    // Apply opening state
    el.classList.remove('offcanvas--closing');
    el.classList.add('offcanvas--opening');
    el.style.display = 'block';
    el.style.visibility = 'visible';
    el.setAttribute('aria-hidden', 'false');

    // Attach swipe-to-close on mobile BEFORE transition so drag handle works immediately
    attachSwipeToClose(el);

    // Trigger reflow then transition in
    el.offsetHeight; // eslint-disable-line no-unused-expressions
    el.classList.add('is-open');
    el.classList.remove('offcanvas--opening');

    isTransitioning = false;

    // Trap focus
    trapFocus(el);

    // Dispatch custom event
    el.dispatchEvent(new CustomEvent('offcanvas:open', { bubbles: true }));
  }

  function closeOffcanvas(el) {
    if (isTransitioning || !el) return;
    if (currentOffcanvas !== el) return;

    isTransitioning = true;

    el.classList.add('offcanvas--closing');
    el.classList.remove('is-open');

    releaseFocus(el);
    unlockScroll();

    // Return focus to trigger element
    returnFocus();

    function onTransitionEnd() {
      el.removeEventListener('transitionend', onTransitionEnd);
      el.style.display = '';
      el.style.visibility = '';
      el.setAttribute('aria-hidden', 'true');
      el.classList.remove('offcanvas--closing');
      isTransitioning = false;
      currentOffcanvas = null;

      // Detach swipe-to-close
      detachSwipeToClose();

      // Remove backdrop after transition
      removeBackdrop();

      // Dispatch custom event
      el.dispatchEvent(new CustomEvent('offcanvas:close', { bubbles: true }));
    }

    el.addEventListener('transitionend', onTransitionEnd);

    // Fallback: if no transition occurs, clean up after a short delay
    setTimeout(function () {
      if (currentOffcanvas === el) {
        el.removeEventListener('transitionend', onTransitionEnd);
        el.style.display = '';
        el.style.visibility = '';
        el.setAttribute('aria-hidden', 'true');
        el.classList.remove('offcanvas--closing');
        isTransitioning = false;
        currentOffcanvas = null;

        // Detach swipe-to-close
        detachSwipeToClose();

        removeBackdrop();
        el.dispatchEvent(new CustomEvent('offcanvas:close', { bubbles: true }));
      }
    }, 400);
  }

  function closeImmediately(el) {
    if (!el) return;
    el.classList.remove('is-open', 'offcanvas--opening', 'offcanvas--closing');
    el.style.display = '';
    el.style.visibility = '';
    el.setAttribute('aria-hidden', 'true');
    releaseFocus(el);
  }

  // ─── Cart drawer delegation ───────────────────────────────────────────────

  function openCartDrawer() {
    var cartDrawer = document.querySelector('#cart-drawer theme-drawer');
    if (cartDrawer) {
      cartDrawer.dispatchEvent(new CustomEvent('theme-drawer:open', { bubbles: true }));
    }
  }

  // ─── Swipe-to-close (mobile) ────────────────────────────────────────────

  /** @type {PhantomTouch.SwipeDetector|null} */
  var swipeDetector = null;

  function attachSwipeToClose(el) {
    if (!window.PhantomTouch || !window.PhantomTouch.SwipeDetector) return;
    if (swipeDetector) swipeDetector.destroy();

    var isBottom = el.classList.contains('offcanvas-bottom');

    if (isBottom) {
      // Bottom offcanvas: swipe down to close
      swipeDetector = new window.PhantomTouch.SwipeDetector(el, {
        onSwipeDown: function () {
          closeOffcanvas(currentOffcanvas);
        },
        threshold: 80,
        preventScroll: true,
        onDragMove: function (dx, dy) {
          // Visual drag resistance: translate the offcanvas downward
          if (dy > 0) {
            el.style.transform = 'translateY(' + Math.min(dy * 0.6, 150) + 'px)';
          }
        },
        onDragEnd: function (dx, dy) {
          // Reset position
          el.style.transform = '';
        },
      });
    } else {
      // Side offcanvas: swipe right to close
      swipeDetector = new window.PhantomTouch.SwipeDetector(el, {
        onSwipeRight: function () {
          closeOffcanvas(currentOffcanvas);
        },
        threshold: 60,
        preventScroll: false,
        onDragMove: function (dx) {
          // Visual drag resistance for side panel
          if (dx > 0) {
            el.style.transform = 'translateX(' + Math.min(dx * 0.5, 120) + 'px)';
          }
        },
        onDragEnd: function () {
          el.style.transform = '';
        },
      });
    }
  }

  }

  // ─── Toggle from trigger element ──────────────────────────────────────────

  function getOffcanvasId(trigger) {
    var href = trigger.getAttribute('href');
    if (href && href.startsWith('#')) return href.slice(1);

    var controls = trigger.getAttribute('aria-controls');
    if (controls) return controls;

    return null;
  }

  function toggleOffcanvas(trigger) {
    var id = getOffcanvasId(trigger);

    // Special case: cart drawer routes to theme-drawer
    if (id === 'offcanvas-cart') {
      openCartDrawer();
      return;
    }

    if (!id) return;

    var target = document.getElementById(id);
    if (!target) return;

    var isOpen = target.classList.contains('is-open');

    if (isOpen) {
      closeOffcanvas(target);
    } else {
      openOffcanvas(target, trigger);
    }
  }

  // ─── Collapse Toggle (for offcanvas-menu submenus) ─────────────────────────

  function toggleCollapse(trigger) {
    var targetId = trigger.getAttribute('href');
    if (!targetId || !targetId.startsWith('#')) {
      targetId = trigger.getAttribute('aria-controls');
    }
    if (!targetId) return;

    var target = document.getElementById(targetId.replace(/^#/, ''));
    if (!target) return;

    target.classList.toggle('show');

    // Update aria-expanded
    var expanded = target.classList.contains('show');
    trigger.setAttribute('aria-expanded', expanded ? 'true' : 'false');
  }

  // ─── Event listeners ──────────────────────────────────────────────────────

  document.addEventListener('click', function (e) {
    var trigger = e.target.closest(
      '[aria-controls^="offcanvas-"], a[href^="#offcanvas-"]'
    );
    if (!trigger) return;

    // Skip if it's an actual navigation link (has href but not role="button")
    var href = trigger.getAttribute('href');
    if (href && href.startsWith('#') && trigger.getAttribute('role') !== 'button') {
      // This is a normal anchor link, let it navigate
      return;
    }

    e.preventDefault();
    toggleOffcanvas(trigger);
  });

  // Escape key handler
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && currentOffcanvas) {
      closeOffcanvas(currentOffcanvas);
    }
  });

  // Collapse toggle (submenu dropdowns in offcanvas-menu)
  document.addEventListener('click', function (e) {
    var toggle = e.target.closest('[data-collapse-toggle]');
    if (!toggle) return;
    if (currentOffcanvas && !currentOffcanvas.contains(toggle)) return;

    e.preventDefault();
    toggleCollapse(toggle);
  });

  // Close buttons inside offcanvas panels
  document.addEventListener('click', function (e) {
    var closeBtn = e.target.closest('.btn-close, [data-offcanvas-dismiss]');
    if (!closeBtn || !currentOffcanvas) return;
    if (!currentOffcanvas.contains(closeBtn)) return;

    e.preventDefault();
    closeOffcanvas(currentOffcanvas);
  });

  // ─── Expose for design mode / programmatic use ────────────────────────────

  window.HorizonOffcanvas = {
    open: openOffcanvas,
    close: closeOffcanvas,
    toggle: toggleOffcanvas,
  };
})();
