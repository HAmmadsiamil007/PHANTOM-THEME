# Phase 2B: Bridge Template Wiring

> **Version:** 1.0.0
> **Date:** 2026-07-14
> **Status:** Draft
> **Depends on:** Phase 1 (Registries) ✓, Phase 2A (Theme_API typed getters) ✓

---

## 1. Objective

Prove the bridge layer works end-to-end by:
1. Adding `optix_get_option()` compatibility shim in the plugin
2. Wiring `Theme_API::init()` into plugin bootstrap
3. Migrating the 3 most-loaded profile templates to use `optix_option()`
4. Adding PHPUnit integration tests for all bridge methods

## 2. Compatibility Shim

Add to `class-theme-api.php` global namespace block:

```php
if ( ! function_exists( 'optix_get_option' ) ) {
    function optix_get_option( string $key, mixed $default = null ): mixed {
        return \OptixCore\Theme_API::option( $key, $default );
    }
}
```

**Effect:** Any code (plugins, child themes, mu-plugins) calling `\optix_get_option()` hits the bridge. Theme's own guard prevents double-definition. The shim is on the plugin side rather than theme because plugins load first.

## 3. Bootstrap Wiring

In `class-core-plugin.php::init()`:

```php
// After registry init:
Theme_API::get_instance()->init();
```

The `init()` method body is a no-op — reserved for future bridge features (asset registration, hook binding).

## 4. Template Migration

### Files to modify (profiles/default/)
| File | Namespace | `optix_get_option` calls | Strategy |
|------|-----------|--------------------------|----------|
| `header.php` | `Optix` | ~15 | Replace with `optix_option()` |
| `footer.php` | `Optix` | ~5 | Replace with `optix_option()` |
| `front-page.php` | `Optix` | ~8 | Replace with `optix_option()` |

### Why it works
PHP's namespace fallback chain: inside `namespace Optix`, unqualified `optix_option()` first looks for `\Optix\optix_option()` (no match), then falls back to global `\optix_option()` (defined by plugin). No `use` statement needed.

### Verification
After each template edit, load the front page and check:
- PHP debug.log — 0 errors from our code
- Browser renders correctly (visual check)
- No change in console errors (pre-existing 404s unchanged)

## 5. Integration Tests

### Structure
```
tests/phpunit/
├── bootstrap.php          — WP test bootstrap, plugin loading
└── api/
    └── class-theme-api-test.php
```

### Test Coverage
| Test | What it verifies |
|------|-----------------|
| `test_option_registry_first` | `option()` reads from `Settings_Registry` when key exists |
| `test_option_fallback` | `option()` falls back to `Options_Manager` for unknown keys |
| `test_option_filter` | `optix/settings/get/{key}` filter fires and can override |
| `test_string` | Casts to string, null returns default |
| `test_int` | Casts to int |
| `test_bool` | Casts to bool |
| `test_color_sanitization` | Valid hex preserved, invalid returns default |
| `test_color_3digit_expansion` | `#abc` → `#aabbcc` |
| `test_image_attachment` | Attachment ID returns URL |
| `test_image_filepath` | File path returns `img()` resolved URL |
| `test_global_functions_exist` | All 8 global functions are callable |
| `test_img_profile_first` | Profile assets resolve before theme fallback |

### Test Infrastructure (to be created)
```
phpunit.xml.dist
tests/
├── bootstrap.php       — WP test bootstrap
└── api/
    └── class-theme-api-test.php
```
- No existing test infrastructure — bootstrap + phpunit.xml.dist created fresh
- WP test suite required: `wp tests install` or manual WP test library bootstrap
- Run via: `vendor/bin/phpunit` in optix-core root

## 6. Success Criteria

- [ ] `optix_get_option()` shim exists and returns bridge values
- [ ] `Theme_API::init()` called during plugin bootstrap
- [ ] header.php renders with `optix_option()` — 0 PHP errors
- [ ] footer.php renders with `optix_option()` — 0 PHP errors  
- [ ] front-page.php renders with `optix_option()` — 0 PHP errors
- [ ] Integration tests pass for all 10+ test methods
- [ ] No regressions on frontend rendering (same console errors as before)

## 7. Constraints

- `function_exists()` guards on all global function definitions
- Template files only change function names — no structural changes
- All changes are additive or purely internal (no ACF changes, no DB schema changes)
- Tests use WP test environment, not mock objects
