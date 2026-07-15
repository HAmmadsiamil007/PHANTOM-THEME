## Phase 2A Complete: Theme_API Bridge Layer

### Implementation

`optix-core/includes/class-theme-api.php` expanded with:
- **`option()`** — Registry-first bridge reads from `Settings_Registry::get()` with null guard, falls back to `Options_Manager::get()`. Filter `optix/settings/get/{$key}` wraps every read.
- **`string()`** — Casts to string, null returns default
- **`int()`** — Casts to int
- **`bool()`** — Casts to bool
- **`color()`** — Sanitizes via `sanitize_hex_color()`, expands 3-digit hex shorthands (#abc → #aabbcc)
- **`image()`** — Attachment ID → `wp_get_attachment_image_url()`, file path → `self::img()`
- **Global functions:** `optix_option()`, `optix_string()`, `optix_int()`, `optix_bool()`, `optix_color()`, `optix_image()`, `optix_img()`, `optix_asset_url()` — all in `namespace {}` block with `function_exists()` guards
- **File structure:** Bracketed namespace syntax (`namespace OptixCore { ... } namespace { ... }`)
- **Updated `img()`** — Now uses `self::option()` instead of theme's `optix_get_option()`

### Code review outcomes (all non-blocking, fixed)
- Added `function_exists()` guards on all global functions
- Fixed `string()` null edge case (null now returns default, not `''`)
- Added `expand_hex_shorthand()` for 3-digit hex color support
- Left `init()` empty for now — can wire to plugin bootstrap later

### Verified
- `php -l` passes
- All 7 global functions exist at runtime
- `optix_option('404_title')` → correct string
- `optix_string('color_primary')` → "#705b53"
- `optix_int('header_height')` → 80
- `optix_bool('home_banner_enable')` → true
- `optix_color('color_secondary')` → "#c19a6b"
- `optix_int('nonexistent_key', 42)` → 42 (fallback)
- Site renders with 0 PHP errors from bridge code

### Next Phase (2B)
- Wire `Theme_API::init()` into plugin bootstrap
- Add integration test coverage for bridge methods
- Update profile templates to use `optix_option()` instead of `optix_get_option()`
