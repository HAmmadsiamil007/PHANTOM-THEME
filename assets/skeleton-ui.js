/**
 * Skeleton UI — Content loading placeholders with shimmer animation.
 *
 * Usage:
 *   <div data-skeleton-wrapper>
 *     <div data-skeleton-content>
 *       ...real content (visible by default)...
 *     </div>
 *     <div data-skeleton-placeholder>
 *       {% render 'skeleton-ui', type: 'product-card', count: 4 %}
 *     </div>
 *   </div>
 *
 * Server-rendered content is visible by default (no flash of skeleton).
 * Add `skeleton-loading` class to the wrapper to activate skeletons
 * during async content loads.
 */
(function () {
  'use strict';

  var WRAPPER_ATTR = 'data-skeleton-wrapper';
  var LOADING_CLASS = 'skeleton-loading';
  var SKELETON_TIMEOUT_MS = 400;

  /**
   * Show skeleton placeholder, hide real content.
   */
  function showSkeleton(wrapper) {
    wrapper.classList.add(LOADING_CLASS);
  }

  /**
   * Hide skeleton placeholder, show real content.
   */
  function hideSkeleton(wrapper) {
    wrapper.classList.remove(LOADING_CLASS);
  }

  /**
   * Find skeleton wrappers scoped to a specific section, or all if no sectionId.
   */
  function getWrappers(sectionId) {
    if (sectionId) {
      var section = document.getElementById(sectionId);
      if (!section) return [];
      return Array.prototype.slice.call(
        section.querySelectorAll('[' + WRAPPER_ATTR + ']')
      );
    }
    return Array.prototype.slice.call(
      document.querySelectorAll('[' + WRAPPER_ATTR + ']')
    );
  }

  /**
   * On initial load: server-rendered content is already present.
   * Just ensure no loading state is active.
   */
  function initSkeletons() {
    var wrappers = getWrappers();
    wrappers.forEach(function (w) {
      hideSkeleton(w);
    });
  }

  /**
   * When a Shopify section is loaded dynamically, briefly show
   * skeletons within that section while the browser paints.
   */
  function onSectionLoad(e) {
    var sectionId = e.detail ? e.detail.id : null;
    var wrappers = getWrappers(sectionId);
    var timer = setTimeout(function () {
      wrappers.forEach(function (w) {
        hideSkeleton(w);
      });
    }, SKELETON_TIMEOUT_MS);
    wrappers.forEach(function (w) {
      showSkeleton(w);
    });
  }

  /**
   * Listen for Shopify Section Rendering API events.
   */
  function listenToShopifyEvents() {
    document.addEventListener('shopify:section:load', onSectionLoad);
    document.addEventListener('shopify:section:reorder', onSectionLoad);
    document.addEventListener('shopify:section:select', onSectionLoad);
    document.addEventListener('shopify:block:select', onSectionLoad);
    document.addEventListener('shopify:section:render', onSectionLoad);

    // Cart drawer skeleton is handled via the section:load event.
    // The skeleton wrapper is inside cart-drawer.liquid and activates
    // when the Section Rendering API replaces cart content.
    // Note: `cart-lines-update` is intentionally NOT used because
    // SRA destroys the old wrapper DOM nodes before the skeleton
    // would render — making it ineffective for storefront cart updates.
  }

  // ─── Init ──────────────────────────────────────────────────
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function () {
      initSkeletons();
      listenToShopifyEvents();
    });
  } else {
    initSkeletons();
    listenToShopifyEvents();
  }
})();
