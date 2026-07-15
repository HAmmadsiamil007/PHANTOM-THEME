# QA Sweep Complete — July 15, 2026

## Issues Found & Fixed

### CRITICAL — CSS 404 Errors
- **Missing CSS files**: `bootstrap.min.css`, `responsive.css`, `blog.css`, `shop.css`, `woocommerce.css`, `animate.css`, `owl.carousel.min.css`, `owl.theme.default.min.css` were missing from `profiles/kids-collection/assets/css/`. Only `style.css` existed.
- **Root cause**: Only `style.css` was present in the profile. The `scripts-styles.php` enqueues 8 more CSS files from `profiles/kids-collection/assets/css/`.
- **Fix**: Copied all 8 missing CSS files from `profiles/default/assets/css/`.
- **Result**: 0 CSS 404 errors (was 2).

### MEDIUM — Nested `assets/assets/` Directory
- **Issue**: The `profiles/kids-collection/assets/` directory had a nested `assets/` subdirectory (duplicated contents). Paths like `assets/assets/css/style.css` had files.
- **Fix**: Removed nested `assets/assets/` directory.
- **Result**: Clean directory structure: `assets/{css,images,js}/`.

### MEDIUM — Google Fonts Preload Crossorigin Warning
- **Issue**: Preload link had bare `crossorigin` attribute but the enqueued stylesheet lacked it. Browser couldn't match the preload cache.
- **Fix**: 
  - `class-web-vitals.php:65`: Changed `crossorigin>` → `crossorigin="anonymous">`
  - Added `style_loader_tag` filter in `scripts-styles.php` to add `crossorigin="anonymous"` to the `kc-google-fonts` enqueued style tag.
- **Result**: Google Fonts crossorigin warning eliminated.

### LOW — Incorrect Function Name in Web_Vitals
- **Issue**: `class-web-vitals.php:52-53` called `optix_option()` which doesn't exist. Should be `optix_get_option()`.
- **Fix**: Changed `optix_option()` → `optix_get_option()`. The `function_exists()` guard prevented a fatal error, but fonts were never loaded.
- **Result**: Google Fonts now properly loaded via the font_optimization preload.

### MEDIUM — Setup Wizard Blocking Admin Pages
- **Issue**: `optix_setup_complete` option was not set, causing `Setup_Wizard::maybe_redirect()` to redirect all admin page loads to the setup wizard. Theme Options returned 403 because the menu page wasn't registered yet.
- **Fix**: Set `optix_setup_complete = 1`.
- **Result**: All admin pages accessible.

### MEDIUM — ACF Guard Blocking Theme Options Without ACF
- **Issue**: `hooks.php:75` had `if ( class_exists( 'ACF' ) )` wrapping the `Theme_Options` class load. Since ACF is not installed, the class was never loaded, preventing the non-ACF fallback admin pages from registering.
- **Fix**: Removed the ACF guard so `Theme_Options` loads regardless. The class already has internal ACF detection and falls back to `register_admin_pages()` when ACF is absent.
- **Result**: Theme Options admin pages now work without ACF Pro.

## All Fixes Synced to Local
- `optix-main/inc/hooks.php` — ACF guard removed
- `optix-main/inc/hooks/scripts-styles.php` — crossorigin filter for kc-google-fonts
- `optix-main/inc/class-web-vitals.php` — `optix_option` -> `optix_get_option`, crossorigin fix
- `optix-main/profiles/kids-collection/assets/css/` — 9 CSS files copied

## Docker State
- WordPress 6.4.3 + PHP 8.2.17
- Plugin: optix-core active
- Theme: optix-main active
- Profile: kids-collection

## Tests
- PHPUnit: 165 tests, 790 assertions, 0 failures, 25 skipped (expected stub-based)
- Frontend: 15/15 pages render with 0 JS errors
- Admin: 14 optix admin pages accessible
- Customizer: 32 panels/sections all visible
- CSS vars: All engines (Typography, Color, Layout, Responsive) inject CSS custom properties correctly
- PHP syntax: All 4 modified files pass `php -l`

## Remaining Known Issues (unchanged)
- 25 skipped tests (REST API, WP_CLI, WooCommerce, ACF — stub environment)
- 2 H1 tags on home page (should ideally be 1)
- Footer uses `<div>` not `<footer>` element
- Font CSS vars show HTML-entity encoding for apostrophes (`&#039;`) — fonts render correctly
- Privacy Policy slug `privacy-policy-2`
- WooCommerce/ACF not active in Docker (no fatal errors — graceful degradation)
