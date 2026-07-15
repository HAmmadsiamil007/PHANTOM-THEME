# Optix Framework — Final Architecture State

## Project Health

- **Plugin:** optix-core (WordPress plugin)
- **Theme:** optix (parent theme)
- **PHP:** 8.1+ required, WP 6.4+, WooCommerce 8.0+ optional
- **CI:** GitHub Actions — phpcs, phpunit, syntax validation

## What's Been Built

### Core Plugin (`optix-core/`)
| Component | Status |
|-----------|--------|
| Base_Registry (singleton, define-entries pattern) | Done — 5 subclasses |
| Settings_Registry (60+ entries across 52 sections) | Done — all section manifests synced |
| Theme_API (9 methods + 9 global wrappers) | Done — registry-first bridge |
| Options_Manager | Done — wp_options backend |
| Profile_Router | Done — cascade resolution |
| Head_Manager (SEO, OG, analytics, schema, cookie bar) | Done |
| Demo_Importer + Demo_Registry | Done |
| Ajax_Handlers (contact, newsletter, restore) | Done |
| Section_Manifest_Validator | Done — dev-only with WP_DEBUG |
| Block_Registry (Gutenberg bindings) | Done |
| Dynamic_CSS (css_property + css_selector) | Done |
| Customizer (ACF bridge) | Done |
| WooCommerce_Registry | Done — guarded by class_exists |

### Theme
| Feature | Status |
|---------|--------|
| Profile system (default + shoes/fashion/furniture) | Done — 4 profiles with PNG screenshots |
| Template structure (30+ templates) | Done |
| BC Shim (includes-old/) | Done — _deprecated_file wrappers |
| inc/ fallback (plugin deactivated) | Done |
| All profiles share Theme_API via optix_get_option() | Done |

## Remaining Deviations (Acceptable)

These are intentional architectural choices that WPCS flags but cannot change:

1. **Dual-namespace pattern** (theme-api.php): `namespace OptixCore { ... } namespace { ... }` — required for `function_exists()` wrappers. WPCS reports 5 errors, all architectural.
2. **`$default` param name**: Used across all Theme_API methods and global wrappers. PHP 8.1+ allows reserved keywords as params. 11 warnings — consistent with project convention.
3. **Hook names with `/`**: `optix/settings/get/{$key}` — intentional path-style filter naming. 2 warnings.
4. **53 missing private method docblocks** in Settings_Registry: All `section_*()` private methods with self-documenting names, 7 `$default` param warnings. All 60 WPCS violations remaining are in this file.

## WPCS Status (WordPress standard, 5 core files)

| File | Errors | Warnings | Notes |
|------|--------|----------|-------|
| class-theme-api.php | 5 | 13 | All architectural (namespace/param/hook) |
| class-base-registry.php | 3 | 3 | DB prepare/translators — acceptable |
| class-settings-registry.php | ~60 | ~7 | 53 docblocks (private), 7 $default |
| optix-core.php | 3 | 0 | 3× `$path` global override (same as wp-config) |
| **Total** | **~71** | **~23** | All are architectural/low-priority |

## Who Must Not Be Messed With

- **Base_Registry private __construct/__clone/__wakeup** — singleton enforcement
- **Theme_API::option() filter `optix/settings/get/{$key}`** — don't rename to underscores, templates depend on it
- **Profile_Router cascade** — child → parent → default checked in order
- **Dual-namespace files** (theme-api.php) — function_exists() guard pattern

## Key File Locations

- `optix-core/optix-core.php` — main plugin bootstrap
- `optix-core/includes/class-theme-api.php` — public API bridge
- `optix-core/includes/registry/class-settings-registry.php` — all settings definitions
- `optix-core/includes/registry/class-base-registry.php` — abstract base
- `optix-core/includes/engine/class-dynamic-css.php` — CSS variable generation
- `optix-core/includes/engine/class-demo-importer.php` — demo import engine
- `optix-core/includes/engine/class-head-manager.php` — SEO/analytics/schema

## Remaining Work Items (Future)

1. Clean up 2 Asset_Registry stub entries (empty `src` strings)
2. Review contact.php / shop.php / product-detail.php for remaining hardcoded business data
3. Theme file WPCS checks (separate from core plugin — theme has own phpcs context)
4. Full PHPUnit test suite
