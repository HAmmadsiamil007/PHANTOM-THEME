# Optix Kids Collection Theme — Forensic Audit (2026-07-13)

## Project Summary
Classic PHP WordPress theme based on Air-light starter. Uses ACF Pro for admin options, falls back to `wp_options` + `defaults.php`. Bootstrap 5.3, Owl Carousel, Magnific Popup. E-commerce kids clothing store.

## Phase Accomplished
- Customizer rewrite: 18 panels, 34 sections, 124+ controls
- Defaults expansion: 230 → 364 keys
- Air-light boilerplate deleted: 8 dirs, 33 files
- Code review: all C1-C5, I2, I6 fixed
- Transport fixed: all helpers `$cfg['transport'] ?? 'refresh'`
- Cross-reference: 249 template keys all have defaults
- PHP syntax: all 26 files pass `php -l`

## 🔴 CRITICAL

### C1: Broken Font — theme.json line 117
- `theme.json` references `assets/fonts/monasansvf.woff2` which was **deleted** during Air-light cleanup
- Causes **404 on EVERY page load**
- Fix: Remove the font reference from `theme.json` or replace with real font URL

### C2: No CSRF Protection — Contact Form
- `contact.php` has a form but **no `wp_nonce_field()`** call
- `contact-form.js` posts AJAX but **no nonce in the request**, **no `check_ajax_referer()`** in PHP handler
- Vulnerability: CSRF / XSS
- Fix: Add `wp_nonce_field()`, JS nonce variable via `wp_localize_script()`, verify in handler

### C3: Missing Google Fonts Enqueue
- `defaults.php` lists Archivo + Jost as font families
- **Never enqueued** via `wp_enqueue_style()` — fonts won't render
- Fix: Add `wp_enqueue_style('optix-google-fonts', ...)` in `scripts-styles.php`

### C4: Inefficient Option Fallback — 14× DB Queries
- `optix_get_option()` in `template-functions.php` runs up to **14 `get_option()` calls** sequentially
- A page with 100 option calls = **1400 DB queries**
- Fix: Cache fallback results or flatten to single `get_option('optix_all')`

## 🟡 HIGH

### H1: Unconditional JS Loading
- Owl Carousel JS/CSS, counter.js, carousel.js, jquery.validate.js, contact-form.js loaded on **every page**
- Most pages don't use carousels or contact forms → wasted bytes + DOM parsing
- Fix: Use `wp_script_is()`, page detection, or body classes to conditionally enqueue

### H2: CDN Assets Without SRI
- Font Awesome, Popper.js, Magnific Popup loaded from CDN with **NO integrity attribute**
- Supply chain risk
- Fix: Add `integrity="sha384-..." crossorigin="anonymous"` to each CDN `<link>`/`<script>`

### H3: Dead WooCommerce Template Overrides
- `templates/kids-collection/` contains `cart.php`, `checkout.php`, `my-account.php` etc.
- Root `woocommerce.php` just calls `woocommerce_content()` — **overrides are never used**
- WooCommerce looks in `/woocommerce/` subdirectory, not `templates/kids-collection/`
- Fix: Move overrides to `/woocommerce/` directory or adjust `wc_get_template` filter

### H4: Missing languages/ Directory
- `theme.json` includes `"translation-ready": true`
- `functions.php` calls `load_theme_textdomain()`
- **No `languages/` directory** — no `.mo`/`.pot` files exist
- Fix: Create `languages/` directory + generate `.pot` file

### H5: Fragile Slug Routing — page-kids-collection.php
- Uses `$post->post_name` to match templates: `if ($slug === 'contact')` etc.
- **Breaks silently** if page slug changes in WordPress admin
- Fix: Use `wp_get_post_parent_id()`, page ID comparison, or template hierarchy

## 🔵 MODERATE

### M1: Hardcoded URLs
- `contact.php:19` uses `<a href="/">Home</a>` instead of `<a href="<?php echo esc_url(home_url('/')); ?>">Home</a>`

### M2: No Kids Collection in search.php
- `search.php` exists but renders default template — no Kids Collection integration
- Search results don't use kids-collection header/footer or layout

### M3: No lazy-loading on images
- Templates use `<img>` tags without `loading="lazy"` attribute

### M4: Dead template-parts/blocks/.gitkeep
- Empty placeholder from Air-light — should be removed

### M5: Air-light comments in functions.php
- `functions.php` still has "Air-light" references and a debug function
- Minor, but confusing

## Files Referenced
- `theme.json` — font reference, translation-ready claim
- `inc/template-functions.php` — optix_get_option() fallback loop
- `inc/hooks/scripts-styles.php` — JS/CSS enqueues, CDN assets
- `inc/hooks/customizer.php` — customizer controls (rewritten)
- `inc/defaults.php` — defaults (expanded)
- `page-kids-collection.php` — slug routing
- `contact.php` — form without nonce
- `assets/kids-collection/js/contact-form.js` — AJAX without nonce
- `woocommerce.php` — just woocommerce_content()
- `templates/kids-collection/` — Cart, Checkout, My Account overrides (dead)
