# Optix Kids Collection — Master Implementation Plan

## Architecture
`optix_get_option()` fallback: ACF field → `defaults.php` → caller default → ''  
Two containers: `optix_wordpress` (CLI) vs `docker-wordpress-1` (live, port 8083)  
**All CLI commands must target `docker-wordpress-1`** using `php wp-cli.phar`

## Phase 1 — Foundation (Plugins + Core)
Activate ACF + WooCommerce in live DB, create WooCommerce pages, clean up.  
*Zero visible changes — safe to run.*

## Phase 2 — Blog Architecture
Redirect posts page to kids-collection blog template.  
Add categories matching blog tabs (Advices, Announcements, News, etc.)

## Phase 3 — ACF Field Groups
Register Options Page + ~12 field groups matching all `defaults.php` keys.  
Use ACF JSON sync for version control.  
Admin can edit all content (header, hero, about, footer, etc.) from wp-admin.

## Phase 4 — Theme Customizer
Register panels: Colors, Typography, Layout, Animations, Custom CSS.  
Real-time live preview for design settings.

## Phase 5 — WooCommerce Integration
Import products + categories, override templates, style cart/checkout/my-account.  
Full purchase flow matching original HTML design.

## Phase 6 — Polish & Loop Engineering
Audit every page, fix notices, verify animations, check mobile, WPCS, performance.  
Iterate until 100/100.

## Constraints
- Zero structure change (preserve all classes, IDs, data-attrs)
- Plugin changes via CLI on `docker-wordpress-1` only
- ACF JSON in theme root for portability
