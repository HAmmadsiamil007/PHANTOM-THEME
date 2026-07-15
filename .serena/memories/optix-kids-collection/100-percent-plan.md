# Optix Kids Collection Theme — 100/100 Fix Plan

## Strategy
Execute fixes in dependency order, batching by target file to minimize context switches.

## Execution Order

### 1. theme.json — Fix broken font reference (C1)
- Remove `"fontFace"` block referencing deleted `monasansvf.woff2`
- File: `theme.json`

### 2. scripts-styles.php — Batch (C3, H1, H2, C2 JS support)
- **[C3]** Add Google Fonts enqueue (Archivo + Jost via Google Fonts CSS)
- **[H1]** Make Owl Carousel, counter.js, carousel.js, jquery.validate.js, contact-form.js conditional
  - Owl Carousel: only on pages with ".owl-carousel" in content or specific page templates
  - counter.js: only on pages with ".counter" class in content  
  - jquery.validate.js + contact-form.js: only on contact page
  - carousel.js: only on pages with carousels
- **[H2]** Add `integrity` + `crossorigin` to CDN links (Font Awesome, Popper, Magnific Popup)
- **[C2 support]** Add `wp_localize_script` for contact-form.js nonce

### 3. template-functions.php — Fix performance (C4)
- Add static cache array to `optix_get_option()` — after computing value, store in static cache
- Previous result from same key returns instantly without DB queries
- Fallback chain preserved for backward compatibility

### 4. contact.php + contact-form.js — Add nonce (C2)
- Add `wp_nonce_field('optix_contact_nonce', 'optix_contact_nonce')` to form
- contact-form.js: Add nonce to AJAX POST data
- If PHP handler exists, add `check_ajax_referer()`

### 5. page-kids-collection.php — Fix slug routing (H5)
- Add fallback to `wp_get_post_parent_id()` when slug matching fails
- Use `get_page_template()` + page ID as backup routing

### 6. WooCommerce overrides (H3)
- Create `/woocommerce/` directory with proper template hierarchy
- Route existing `templates/kids-collection/` WC content

### 7. languages/ (H4)
- Create `/languages/` directory
- Generate `.pot` file from PHP string extraction

### 8. Minor fixes (M1-M5)
- Hardcoded `/` → `esc_url(home_url('/'))`
- search.php → add Kids Collection support
- `loading="lazy"` on template images
- Remove `template-parts/blocks/.gitkeep`
- Clean Air-light comments from functions.php

## Verification Gates
1. `php -l` on ALL modified PHP files
2. Check theme.json is valid JSON
3. Verify no broken references remain
4. Run `phpcs --standard=WordPress` on modified files

## Risk Mitigation
- Static cache preserves exact fallback behavior — zero regression risk
- Conditional JS uses `has_shortcode()` + page detection — safe fallthrough
- SRI hashes added, not removed — no breaking change
