/**
 * PhantomTouch — Mobile swipe detection and touch helpers.
 *
 * Provides a SwipeDetector class that can be attached to any element
 * to detect horizontal/vertical swipe gestures. Used by:
 *   - offcanvas.js        → swipe-to-close
 *   - announcement-bar    → swipe between slides
 *   - hero-carousel       → swipe between slides
 *   - layered-slideshow   → swipe between tabs (mobile)
 *
 * Usage:
 *   var sd = new PhantomTouch.SwipeDetector(element, {
 *     onSwipeLeft:  function () { /* next slide *\/ },
 *     onSwipeRight: function () { /* prev slide *\/ },
 *     threshold:    50,        // px, default 50
 *     preventScroll: false,    // set true for horizontal-only contexts
 *   });
 *   sd.destroy();  // cleanup
 */
(function () {
  'use strict';

  var SwipeDetector = function (el, opts) {
    if (!el) return;
    this.el = el;
    this.opts = opts || {};
    this.threshold = this.opts.threshold || 50;
    this.preventScroll = this.opts.preventScroll || false;
    this._onSwipeLeft = this.opts.onSwipeLeft || null;
    this._onSwipeRight = this.opts.onSwipeRight || null;
    this._onSwipeUp = this.opts.onSwipeUp || null;
    this._onSwipeDown = this.opts.onSwipeDown || null;

    this._x0 = 0;
    this._y0 = 0;
    this._x = 0;
    this._y = 0;
    this._dragging = false;

    this._handleStart = this._onStart.bind(this);
    this._handleMove = this._onMove.bind(this);
    this._handleEnd = this._onEnd.bind(this);

    el.addEventListener('touchstart', this._handleStart, { passive: true });
    el.addEventListener('touchmove', this._handleMove, { passive: !this.preventScroll });
    el.addEventListener('touchend', this._handleEnd, { passive: true });
    el.addEventListener('touchcancel', this._handleEnd, { passive: true });
  };

  SwipeDetector.prototype._onStart = function (e) {
    var t = e.touches[0];
    this._x0 = t.clientX;
    this._y0 = t.clientY;
    this._x = t.clientX;
    this._y = t.clientY;
    this._dragging = true;
    this._moved = false;
  };

  SwipeDetector.prototype._onMove = function (e) {
    if (!this._dragging) return;
    var t = e.touches[0];
    this._x = t.clientX;
    this._y = t.clientY;
    var dx = this._x - this._x0;
    var dy = this._y - this._y0;

    if (!this._moved) {
      this._moved = true;
      if (this.opts.onDragStart) this.opts.onDragStart(e);
    }

    if (this.opts.onDragMove) this.opts.onDragMove(dx, dy, e);

    // If preventScroll and horizontal drag exceeds vertical, prevent default
    if (this.preventScroll && Math.abs(dx) > Math.abs(dy) && Math.abs(dx) > 10) {
      e.preventDefault();
    }
  };

  SwipeDetector.prototype._onEnd = function (e) {
    if (!this._dragging) return;
    this._dragging = false;

    var dx = this._x - this._x0;
    var dy = this._y - this._y0;
    var absDx = Math.abs(dx);
    var absDy = Math.abs(dy);

    if (this.opts.onDragEnd) this.opts.onDragEnd(dx, dy, e);

    // Determine if this was a swipe (exceeded threshold)
    if (absDx < this.threshold && absDy < this.threshold) return;

    // Horizontal swipe
    if (absDx > absDy) {
      if (dx > 0 && this._onSwipeRight) this._onSwipeRight(e);
      else if (dx < 0 && this._onSwipeLeft) this._onSwipeLeft(e);
    }
    // Vertical swipe
    else {
      if (dy > 0 && this._onSwipeDown) this._onSwipeDown(e);
      else if (dy < 0 && this._onSwipeUp) this._onSwipeUp(e);
    }
  };

  SwipeDetector.prototype.destroy = function () {
    if (!this.el) return;
    this.el.removeEventListener('touchstart', this._handleStart);
    this.el.removeEventListener('touchmove', this._handleMove);
    this.el.removeEventListener('touchend', this._handleEnd);
    this.el.removeEventListener('touchcancel', this._handleEnd);
    this.el = null;
  };

  // Expose globally
  window.PhantomTouch = window.PhantomTouch || {};
  window.PhantomTouch.SwipeDetector = SwipeDetector;
})();
