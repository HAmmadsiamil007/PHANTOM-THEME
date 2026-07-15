# Optix Core Framework — Design Document (v2)

> A WordPress theme framework architecture that separates ALL controls/settings (plugin) from presentation (theme profiles), enabling fast front-end template swaps without breaking any customization.

## 1. Problem Statement

The Optix Kids Collection theme has:
- Extensive custom controls (maintenance mode, 3D effects, mega menu, ACF blocks, portfolio CPT)
- ~27 specialized template files under `templates/kids-collection/`
- Assets tightly coupled to the kids-collection design (`assets/kids-collection/`)
- Dynamic CSS with hardcoded selectors specific to the kids-collection layout

**Goal:** Convert this into a framework where any client template (shoe store, blog, real estate, LMS, corporate) can be delivered by changing only HTML/CSS/JS — with all controls surviving 100%.

## 2. Architecture Overview

```
/wp-content/plugins/optix-core/       ← ENGINE: All controls, settings, CPTs. NEVER changes.
/wp-content/themes/optix/             ← PRESENTATION: Templates, CSS, JS. Per-client files.
  └── profiles/
      ├── default/                    ← Fallback (current kids-collection migrated)
      ├── shoe-store/                 ← Client A
      ├── blog/                       ← Client B
      ├── real-estate/                ← Client C
      └── ...
```

**Core Principle:** Plugin stores ALL data via WordPress Options API. Theme renders ALL markup. Plugin talks to theme via WordPress hooks and filters. Zero hardcoded CSS selectors in the plugin. Every selector is filterable.

### Textdomain Strategy

- **Plugin**: Uses `optix-core` textdomain for all admin strings
- **Theme (profiles)**: Uses `optix` textdomain for all front-end strings
- Profile-specific template files call `__('text', 'optix')`
- Plugin admin pages call `__('text', 'optix-core')`

## 3. Plugin Layer (optix-core/)

### Structure

```
optix-core/
├── optix-core.php                          ← Plugin header, bootstrapper, activation hooks
├── includes/
│   ├── class-core-plugin.php               ← Main plugin class, loads all modules
│   ├── class-options-manager.php           ← Options API, defaults, sanitization, optix_get_option()
│   ├── class-cpt-manager.php               ← Portfolio CPT, future post types
│   ├── class-taxonomy-manager.php          ← Portfolio Category taxonomy
│   ├── class-maintenance-mode.php          ← template_redirect + maintenance template fallback
│   ├── class-dynamic-css-generator.php     ← Abstract CSS generation, ALL selectors filterable
│   ├── class-mega-menu.php                 ← Mega menu walker + JS enqueue logic
│   ├── class-3d-effects.php                ← 3D tilt effect logic + inline JS
│   ├── class-acf-blocks.php                ← ACF block type registration
│   ├── class-profile-router.php            ← Routes template_include, get_template_part, wc_get_template
│   └── class-theme-api.php                 ← Public API: optix_profile_template_part(), optix_asset_url()
├── admin/
│   └── class-settings-page.php             ← Settings → Optix Framework admin page
├── templates/                              ← Fallback templates (level 3)
│   └── maintenance.php
├── assets/                                 ← Plugin assets
│   └── block-icons/
└── languages/
```

### Data Storage Rules — Two Tier System

**Tier 1 — Options API (Source of Truth):**

All settings use the WordPress Options API. This survives ANY theme or plugin change:

```php
// These survive everything — theme switch, plugin deactivation, profile change
update_option('optix_effect_3d_enable', 1);
update_option('optix_maintenance_mode', 0);
update_option('optix_hero_background', '#ffffff');
update_option('optix_active_profile', 'default');
```

**Tier 2 — ACF Sync (Admin UI Only):**

If ACF PRO is active, the plugin syncs Options API data to ACF option pages for the rich admin UI:

```php
function optix_sync_to_acf(string $key, $value): void {
    if (function_exists('update_field')) {
        update_field($key, $value, 'option');
    }
}
```

**Theme Mods are NOT used.** Theme mods (`get_theme_mod()`, `set_theme_mod()`) are per-theme and would reset if the theme were ever deactivated. All settings use `get_option()`/`update_option()`.

### Storage Key Convention

All option keys use the `optix_` prefix followed by the setting name. No nested arrays — each setting is its own option for maximum portability and minimal conflict risk.

### optix_get_option() Implementation (Plugin)

```php
function optix_get_option(string $key, $default = null) {
    static $cache = [];
    if (array_key_exists($key, $cache)) {
        return $cache[$key];
    }
    // Options API is source of truth
    $value = get_option('optix_' . $key, '__not_set__');
    if ('__not_set__' !== $value) {
        $cache[$key] = $value;
        return $value;
    }
    // Fall back to theme mod (migration support only)
    $value = get_theme_mod('optix_' . $key, $default);
    $cache[$key] = $value;
    return $value;
}
```

### Dynamic CSS Generator — All Selectors Filterable

```php
class Dynamic_CSS_Generator {
    public function get_styles(): string {
        $css = '';
        // Every selector is wrapped in apply_filters
        $selector = apply_filters('optix/css/hero', '.optix-hero');
        $bg = optix_get_option('hero_background', '#ffffff');
        $css .= sprintf('%s { background-color: %s; }', $selector, esc_attr($bg));
        
        $selector = apply_filters('optix/css/mega-menu', '.optix-mega-menu');
        $css .= sprintf('%s { ... }', $selector);
        
        $selector = apply_filters('optix/css/tilt-3d', '.optix-tilt-3d');
        if (optix_get_option('effect_3d_enable')) {
            $perspective = optix_get_option('effect_3d_perspective', 1000);
            $css .= sprintf('%s { perspective: %dpx; }', $selector, $perspective);
        }
        
        return $css;
    }
}
```

### Selector Hook Registry

Every filterable selector and its default value, documented in one place:

| Filter Hook | Default Value | Used By |
|------------|--------------|---------|
| `optix/css/hero` | `.optix-hero` | Hero section background |
| `optix/css/mega-menu` | `.optix-mega-menu` | Mega menu container |
| `optix/css/tilt-3d` | `.optix-tilt-3d` | 3D tilt effect container |
| `optix/css/tilt-card` | `.optix-tilt-card` | Individual tilt card/item |
| `optix/css/maintenance` | `.optix-maintenance` | Maintenance mode wrapper |

Profiles override selectors via `functions.php`:

```php
// In profiles/shoe-store/functions.php
add_filter('optix/css/hero', function() { return '.shoe-store-hero'; });
add_filter('optix/css/mega-menu', function() { return '.store-nav'; });
```

### Hook-Based Rendering

Plugin declares actions; profiles provide markup:

```php
// Plugin declares:
do_action('optix/render/maintenance');
do_action('optix/render/mega-menu', $menu_items, $args);

// Profile provides:
add_action('optix/render/maintenance', function() {
    optix_profile_template_part('template-parts', 'maintenance');
});
```

## 4. Theme Layer (optix/)

### Structure

```
optix/
├── style.css                               ← Theme metadata
├── functions.php                           ← Minimal: theme support, profile enqueue, emergency fallback
├── index.php                               ← Template dispatcher (routes to profile)
├── profiles/
│   ├── default/                            ← Migrated from current kids-collection
│   │   ├── front-page.php
│   │   ├── header.php
│   │   ├── footer.php
│   │   ├── single.php
│   │   ├── page.php
│   │   ├── archive.php
│   │   ├── single-portfolio.php
│   │   ├── archive-portfolio.php
│   │   ├── 404.php
│   │   ├── search.php
│   │   ├── home.php
│   │   ├── sidebar.php
│   │   ├── comments.php
│   │   ├── woocommerce.php
│   │   ├── woocommerce/                     ← WC template overrides
│   │   ├── template-parts/                  ← All partials
│   │   ├── functions.php                    ← Profile-specific hooks/filters
│   │   ├── assets/
│   │   │   ├── css/style.css
│   │   │   ├── css/responsive.css
│   │   │   ├── css/animate.css
│   │   │   ├── css/owl.carousel.min.css
│   │   │   ├── js/theme.js
│   │   │   ├── js/bootstrap.min.js
│   │   │   ├── js/owl.carousel.min.js
│   │   │   └── images/
│   │   └── screenshot.png
│   │
│   ├── shoe-store/                         ← Client A (same structure)
│   ├── blog/                               ← Client B
│   └── real-estate/                        ← Client C
│
├── theme.json                              ← Block editor settings (shared across profiles)
└── inc/                                    ← Emergency fallback ONLY
    └── class-optix-theme-options.php
```

### functions.php (Minimal)

```php
<?php
defined('ABSPATH') || exit;

// Emergency fallback — if plugin is ever deactivated
if (!class_exists('OptixCore\Plugin')) {
    require_once get_template_directory() . '/inc/class-optix-theme-options.php';
}

// 1. Theme supports
add_action('after_setup_theme', function() {
    add_theme_support('post-thumbnails');
    add_theme_support('title-tag');
    add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption']);
    add_theme_support('custom-logo');
    add_theme_support('align-wide');
    add_theme_support('responsive-embeds');
    load_theme_textdomain('optix', get_template_directory() . '/languages');
});

// 2. Enqueue active profile assets
add_action('wp_enqueue_scripts', function() {
    $profile = get_option('optix_active_profile', 'default');
    $profile_uri = get_template_directory_uri() . '/profiles/' . $profile;
    $profile_dir = get_template_directory() . '/profiles/' . $profile;
    $version = wp_get_theme()->get('Version');
    
    // Main stylesheet with cache busting
    $css_file = $profile_dir . '/assets/css/style.css';
    $css_version = file_exists($css_file) ? filemtime($css_file) : $version;
    wp_enqueue_style('optix-profile-style', $profile_uri . '/assets/css/style.css', [], $css_version);
    
    // Main script with cache busting
    $js_file = $profile_dir . '/assets/js/theme.js';
    $js_version = file_exists($js_file) ? filemtime($js_file) : $version;
    wp_enqueue_script('optix-profile-script', $profile_uri . '/assets/js/theme.js', ['jquery'], $js_version, true);
});

// 3. Load profile-specific functions.php if exists
$active_profile = get_option('optix_active_profile', 'default');
$profile_functions = get_template_directory() . '/profiles/' . $active_profile . '/functions.php';
if (file_exists($profile_functions)) {
    require_once $profile_functions;
}
```

## 5. Profile Router — Comprehensive Routing

### The router handles 4 template entry points:

| Entry Point | WordPress Hook | Purpose |
|------------|---------------|---------|
| Main template | `template_include` | Routes front-page.php, single.php, archive.php, etc. |
| Template parts | `get_template_part` filter | Routes get_template_part() calls into profiles |
| WooCommerce | `wc_get_template` filter | Routes WooCommerce templates into profiles |
| Assets | `stylesheet_uri` + `template_directory_uri` | Routes CSS/JS/images into profiles |

### Main Template Router

```php
class Profile_Router {
    
    public function init(): void {
        add_filter('template_include', [$this, 'route_template'], 99);
        add_filter('get_template_part', [$this, 'route_template_part'], 10, 3);
        add_filter('wc_get_template', [$this, 'route_wc_template'], 10, 5);
        add_filter('stylesheet_uri', [$this, 'route_stylesheet']);
        add_filter('template_directory_uri', [$this, 'route_directory_uri']);
    }
    
    private function get_active_profile(): string {
        $profile = get_option('optix_active_profile', false);
        if (!$profile || !is_string($profile)) {
            return 'default';
        }
        $profile = sanitize_file_name($profile);
        // Validate: profile directory must exist
        $profile_dir = get_template_directory() . '/profiles/' . $profile;
        if (!is_dir($profile_dir)) {
            $profile = 'default';
        }
        return $profile;
    }
    
    private function get_profile_path(): string {
        return get_template_directory() . '/profiles/' . $this->get_active_profile();
    }
    
    public function route_template(string $template): string {
        $profile_path = $this->get_profile_path();
        $template_name = basename($template); // WordPress already resolves this
        
        // Level 1: Active profile
        $candidate = $profile_path . '/' . $template_name;
        if (file_exists($candidate)) return $candidate;
        
        // Level 2: Default profile
        $candidate = get_template_directory() . '/profiles/default/' . $template_name;
        if (file_exists($candidate)) return $candidate;
        
        // Level 3: Plugin fallback
        $candidate = WP_PLUGIN_DIR . '/optix-core/templates/' . $template_name;
        if (file_exists($candidate)) return $candidate;
        
        return $template;
    }
}
```

### Template Part Router (get_template_part)

This is critical — WordPress `get_template_part()` calls within profile templates must stay within the profile. The `get_template_part` filter receives the slug and name before loading:

```php
public function route_template_part(string $template, string $slug, string $name = null): string {
    $profile_path = $this->get_profile_path();
    
    // Build candidate paths matching get_template_part() logic
    $candidates = [];
    if ($name) {
        $candidates[] = $profile_path . '/' . $slug . '-' . $name . '.php';
    }
    $candidates[] = $profile_path . '/' . $slug . '.php';
    
    // Default profile fallback
    if ($name) {
        $candidates[] = get_template_directory() . '/profiles/default/' . $slug . '-' . $name . '.php';
    }
    $candidates[] = get_template_directory() . '/profiles/default/' . $slug . '.php';
    
    foreach ($candidates as $candidate) {
        if (file_exists($candidate)) {
            return $candidate;
        }
    }
    
    return $template; // Fall back to normal get_template_part resolution
}
```

### WooCommerce Template Router

WooCommerce uses its own template system. Must intercept separately:

```php
public function route_wc_template(string $template, string $template_name, array $args, string $template_path, string $default_path): string {
    $profile_path = $this->get_profile_path() . '/woocommerce/';
    $candidate = $profile_path . $template_name;
    
    if (file_exists($candidate)) return $candidate;
    
    // Default profile fallback for WC
    $candidate = get_template_directory() . '/profiles/default/woocommerce/' . $template_name;
    if (file_exists($candidate)) return $candidate;
    
    return $template;
}
```

### Profile Template Part Helper (Public API)

```php
function optix_profile_template_part(string $slug, string $name = null): void {
    $router = OptixCore\Profile_Router::get_instance();
    $template = $router->route_template_part('', $slug, $name);
    if ($template && file_exists($template)) {
        load_template($template, false);
    }
}
```

## 6. Profile Selector Admin UI

```
WordPress Admin → Settings → Optix Framework
┌────────────────────────────────────────────────────┐
│  Active Profile: [dropdown: default | shoe-store ] │
│                                                   │
│  ☐ Enable Preview Mode                             │
│  ☐ Enable Maintenance Mode                         │
│                                                   │
│  [Save Changes]  [Preview Active Profile]          │
│                                                   │
│  ──── Current Profiles ────                        │
│  default      (active)  [Preview] [Export]         │
│  shoe-store            [Activate] [Preview] [Export]│
│  blog                  [Activate] [Preview] [Export]│
└────────────────────────────────────────────────────┘
```

Preview mode: Adds `?optix_preview_profile={slug}` parameter — admin users can preview any profile without activating it.

Profile validation on save: The router checks the directory exists before switching. If missing, switches to `default` with an admin notice.

## 7. Fallback Chain Summary

When any template or asset is requested, the resolution order is:

```
requested file
  → profiles/{active}/file.php          (Level 1: Active profile)
  → profiles/default/file.php           (Level 2: Default fallback)
  → wp-content/plugins/optix-core/...   (Level 3: Plugin fallback)
  → WordPress default resolution        (Level 4: WP native)
```

## 8. New Profile Creation Workflow (OS-Agnostic)

When a new client requests a different design:

```
1. Copy profiles/default to profiles/{client-name}
   Windows:  xcopy /E profiles\default profiles\{client-name}
   Linux/Mac: cp -r profiles/default profiles/{client-name}
   
2. Edit template files in profiles/{client-name}/:
   - header.php       → Change HTML header structure
   - footer.php       → Change HTML footer structure  
   - front-page.php   → Change homepage layout
   - single.php       → Change single post layout
   - page.php         → Change page layout
   - archive.php      → Change archive/list layout
   
3. Write new CSS in profiles/{client-name}/assets/css/style.css
   
4. Write new JS in profiles/{client-name}/assets/js/theme.js
   - Use optixProfileData global (localized by functions.php) for settings
   
5. Replace images in profiles/{client-name}/assets/images/
   
6. (Optional) Override CSS selectors in profile's functions.php:
   add_filter('optix/css/hero', function() { return '.client-hero'; });
   
7. Activate in Settings → Optix Framework
```

**Estimated delivery time:** 2-4 hours per profile (front-end only, after framework setup).

## 9. Migration Path (Current Theme → Framework)

### Phase 0: Pre-Migration Audit

1. Document all current option keys in the database
2. Document all ACF field groups registered by the theme
3. Identify all template files that call get_template_part()
4. List all hardcoded CSS selectors in inc/dynamic-css.php
5. Snapshot current settings via WP CLI

### Phase 1: Plugin Creation

1. Create `wp-content/plugins/optix-core/` directory
2. Move all inc/ classes to plugin, namespaced under `OptixCore\`
3. Replace all `get_theme_mod()` calls with `get_option('optix_')` calls
4. Register all CPTs and taxonomies via plugin
5. Add filter hooks to every CSS selector in dynamic CSS
6. Build Profile Router with all 4 routing hooks

### Phase 2: Theme Restructure

1. Create `profiles/default/` directory
2. Move root template files into profiles/default/
3. Create root-level index.php as a simple dispatcher
4. Move assets/kids-collection to profiles/default/assets/
5. Move templates/kids-collection/* into profiles/default/
6. Create minimal root-level functions.php

### Phase 3: Data Migration

1. Run `wp option get optix_*` (WP-CLI) to snapshot current settings
2. Plugin activation hook runs migration script:
```php
// In plugin activation
function optix_migrate_existing_settings(): void {
    $legacy_options = $wpdb->get_results(
        "SELECT option_name, option_value FROM {$wpdb->options} 
         WHERE option_name LIKE 'optix_%'"
    );
    foreach ($legacy_options as $option) {
        // Re-save with plugin's option key convention
        // Options already stored as optix_* — just ensure they are accessible
        maybe_unserialize($option->option_value);
    }
    // Convert theme_mods to options
    $theme_mods = get_theme_mods();
    if (!empty($theme_mods)) {
        foreach ($theme_mods as $key => $value) {
            if (strpos($key, 'optix_') === 0) {
                $option_key = str_replace('optix_', '', $key);
                if (get_option('optix_' . $option_key) === false) {
                    update_option('optix_' . $option_key, $value);
                }
            }
        }
    }
    update_option('optix_migration_complete', time());
}
```
3. Create `?_optix_migrate=1` endpoint for one-click migration
4. Verify all settings survive the migration with `wp option list --search='optix_*'`

### Phase 4: Testing

1. Test with current kids-collection profile (100% visual match)
2. Create a second test profile (e.g., "clean-blog") to verify profile switching
3. Test plugin deactivation → theme fallback works
4. Test ACF active vs inactive behavior
5. Test WooCommerce template routing
6. Test maintenance mode with different profiles

## 10. Risk Register (Expanded)

| # | Risk | Probability | Impact | Score (1-10) | Mitigation |
|---|------|-----------|--------|-------------|-----------|
| R1 | Plugin deactivation removes all controls | Low | Critical | 9 | Emergency fallback in theme functions.php loads inc/ directory |
| R2 | CSS selector mismatch between plugin CSS and profile HTML | Medium | High | 7 | All selectors filterable via `apply_filters`; documented registry |
| R3 | get_template_part() loads from wrong directory | Low | High | 7 | `get_template_part` filter + `optix_profile_template_part()` helper |
| R4 | WooCommerce templates not found in active profile | Medium | Medium | 6 | `wc_get_template` filter + default profile WC fallback |
| R5 | ACF PRO admin pages break when ACF inactive | Medium | Medium | 6 | Admin UI built with Options API + Settings API; ACF is bonus layer |
| R6 | Settings lost during migration from current theme | Low | Critical | 9 | Activation hook reads existing options; `optix_get_option()` fallback to theme_mod |
| R7 | Profile directory deleted/renamed causes 404 | Low | Medium | 5 | Router validates directory exists; falls back to default with admin notice |
| R8 | Browser/CDN cache shows old CSS after profile switch | Low | Low | 3 | `filemtime()` cache busting on all profile assets |
| R9 | Plugin update breaks profile API compatibility | Low | Medium | 5 | Semantic versioning; deprecation notices in hooks; stable release branches |
| R10 | Theme mods lost if user had customizer settings | Medium | High | 7 | Migration script reads theme_mods and converts to options on plugin activation |
| R11 | Block editor theme.json conflicts with profile CSS | Low | Low | 3 | theme.json in theme root applies globally; profiles can filter block styles |
| R12 | Plugin security audit needed (profile router reads file paths) | Low | Medium | 4 | `sanitize_file_name()` on profile value; `wp_normalize_path()` on paths |
| R13 | Multisite compatibility (profile settings per site) | Low | Low | 2 | `get_option()` is site-specific by default; use `get_site_option()` if network-wide needed |

## 11. Comparison with Other Approaches

| Criteria | Plugin + Profiles (This) | Child Themes | FSE Block Themes | Page Builder |
|----------|------------------------|-------------|-----------------|--------------|
| Controls survive any switch | ✅ 100% | ✅ 100% | ❌ Reset on theme switch | ✅ 100% |
| Front-end unique per client | ✅ Unlimited | ✅ Unlimited | ❌ Block constraints | ❌ Drag-drop limits |
| Performance | ⚡ Fast (no builder) | ⚡ Fast | 🟡 Medium | 🐢 Slow (heavy JS) |
| Learning curve for dev | 🟢 Medium | 🟢 Low | 🟡 Medium | 🟢 Low |
| Client delivery time | 2-4 hours | 4-8 hours | 8-16 hours | 1-2 hours |
| Version control friendly | ✅ Yes | ✅ Yes | ✅ Yes | ❌ DB-stored |
| Multi-client maintenance | ✅ Single codebase | ❌ Many themes | ❌ Many themes | ✅ Single builder |
| Migration from existing | 🟡 2-3 days | ✅ Instant | ❌ Rebuild | ❌ Rebuild |

## 12. Phase Boundaries

### Phase 1 (Current — Build Framework)
- Create `optix-core` plugin with all existing features moved in
- Create `profiles/default/` from current theme restructure
- Router handles: template_include, get_template_part, wc_get_template
- All CSS selectors filterable
- Admin UI for profile selection
- Migration script for existing settings

### Phase 2 (Next — Multiple Profiles)
- Create 3 demo profiles: blog, business, real-estate
- Profile template starter kit (scaffold command)
- Profile export/import (zip)
- Documentation for profile creation

### Phase 3 (Future — Advanced)
- Preview mode with query string
- Profile CSS/JS minification on the fly
- Child profiles (override a parent profile)
- Block editor theme.json per profile

## 13. Constraints

- Classic PHP template architecture (NOT FSE block templates)
- PHP 8.1+, WordPress 6.4+
- ACF is optional (settings fall back to Options API)
- All option keys use `optix_` prefix
- No theme mods used (all settings via Options API)
- WooCommerce support requires profile to include woocommerce/ overrides
- A profile must provide: header.php, footer.php, index.php, front-page.php, single.php, page.php, archive.php
- Profile names: lowercase, alphanumeric, hyphens only (sanitized with `sanitize_file_name()`)
- Missing files gracefully fall back through 4-level chain
- Router validates profile exists before activating

## 14. Out of Scope

- FSE block theme profiles
- Drag-and-drop profile builder
- Profile marketplace / public sharing
- Auto-migration of third-party child themes into profiles
- CSS/JS minification (use WP plugin for that)
- CDN integration for profile assets
