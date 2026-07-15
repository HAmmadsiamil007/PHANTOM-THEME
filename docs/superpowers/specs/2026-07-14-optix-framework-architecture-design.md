# Optix Framework — Architecture Design

> **Version:** 3.0.0
> **Date:** 2026-07-14
> **Status:** Final — Iteration 3

---

## 1. Architecture Overview

### 1.1 Core Principle

The framework is a **decoupled engine** for WordPress + WooCommerce. It provides all backend controls, settings, and data structures. The frontend is completely agnostic — it reads from registries and renders blindly, never knowing which industry or client it serves.

```
┌─────────────────────────────────────────────────────────────────────┐
│                       CORE FRAMEWORK (plugin)                       │
│               Never changes after stable. No frontend bias.         │
│                                                                     │
│  ┌─────────────────────────────────────────────────────────────┐   │
│  │                    REGISTRY LAYER (x15)                      │   │
│  │   Each registry: get_instance()->register()                  │   │
│  │   Singleton pattern throughout (matches existing codebase)   │   │
│  │                                                              │   │
│  │  Settings     Section      Component    Template             │   │
│  │  Block        Layout       WooCommerce  Animation            │   │
│  │  Typography   Color        Responsive   Hook                  │   │
│  │  Preset       Demo         Asset                              │   │
│  └─────────────────────────────────────────────────────────────┘   │
│                                                                     │
│  ┌─────────────────────────────────────────────────────────────┐   │
│  │                     ENGINE LAYER                             │   │
│  │  Options API → wraps Registry        Dynamic CSS Generator   │   │
│  │  ACF Sync (one-way)                  Mega Menu               │   │
│  │  Profile Router (template hooks)    3D Effects               │   │
│  │  CPT / Taxonomy Manager             Maintenance Mode         │   │
│  │  Cache Layer (3-tier)               Schema Migrator          │   │
│  │  REST API / WP-CLI                  Theme_API (bridge)       │   │
│  │  Head_Manager (SEO/OG/analytics)   Section_Manifest_Validator│   │
│  └─────────────────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────────────────┘
                               ▲
                               │ get(); set()
                    ┌──────────┴──────────┐
                    │   Profile Router    │
                    │   template_include  │  (99 priority)
                    │   get_template_part │  (10, 3)
                    │   wc_get_template   │  (10, 5)
                    │   stylesheet_uri    │
                    └──────────┬──────────┘
                               │
          ┌────────────────────┼────────────────────┐
          │                    │                    │
    ┌─────┴──────┐      ┌─────┴──────┐      ┌─────┴──────┐
    │ profile/   │      │ profile/   │      │ profile/   │
    │ shoes/     │      │ fashion/   │      │ furniture/ │
    │ (client A) │      │ (client B) │      │ (client C) │
    └────────────┘      └────────────┘      └────────────┘
```

### 1.2 Key Constraints

| Constraint | Value |
|------------|-------|
| PHP minimum | 8.1+ |
| WordPress minimum | 6.4+ |
| WooCommerce minimum | 8.0+ (optional) |
| Option prefix | `optix_` |
| Plugin namespace | `OptixCore` |
| Theme namespace | `Optix` |
| Singleton pattern | All classes use `get_instance()` — already established |
| ACF | Optional sync target — never source of truth |
| CSS selectors | All filterable via `optix/dynamic_css/selector/{key}` |
| Profile names | Lowercase, alphanumeric + hyphens via `sanitize_file_name()` |
| Emergency fallback | Every feature degrades gracefully if plugin is deactivated |

### 1.3 Existing Foundation (Current Codebase)

The following already exists and must be preserved/adapted:

```
Options_Manager         — 487 defaults, get/set with static cache, ACF fallback
Profile_Router          — template routing, profile validation, preview (?optix_preview_profile)
Theme_API               — asset_url(), img() bridge methods
Dynamic_CSS_Generator   — dynamic CSS generation
Settings_Page           — admin page
CPT_Manager             — Portfolio CPT
Taxonomy_Manager        — Portfolio Category taxonomy
ACF_Blocks              — Portfolio Grid, Project Highlight blocks
Mega_Menu               — mega menu builder
ThreeD_Effects          — 3D tilt effects
Maintenance_Mode        — coming soon page

inc/                     — 15 theme-side files to be migrated to plugin
  ajax-handlers.php
  class-optix-theme-options.php   (2371 lines, 18 ACF field groups)
  create-products.php
  defaults.php
  dynamic-css.php
  template-functions.php
  template-tags.php
  ...
```

### 1.4 Registries Know Nothing About Presentation

```php
// ✅ Registry entry — knows only data
[
    'key'      => 'hero_title',
    'type'     => 'string',
    'default'  => 'Welcome',
    'label'    => __('Hero Title', 'optix-core'),
    'sanitize' => 'sanitize_text_field',
    'validate' => fn($v) => strlen($v) <= 200,
]

// ❌ Never in a registry — belongs in profile template
// 'css_class' => 'hero-title text-white animate-fadeIn'
```

### 1.5 Design Decisions (ADRs)

**ADR-001: Individual Options vs Serialized Blob →** Individual `wp_options` rows with `optix_` prefix. Cacheable via alloptions. Partial updates don't require full deserialize. Each row can have different `autoload` setting.

**ADR-002: ACF as Sync Target Only →** Options API is always source of truth. ACF is optional. One-way sync: Options → ACF. Framework works without ACF.

**ADR-003: Singleton Pattern →** All registries and engines use `get_instance()` static accessor. Matches existing codebase conventions. No DI container needed.

**ADR-004: Profile Directory vs Database →** Filesystem directories. Version-controllable. Can contain CSS/JS/images alongside templates. Active profile is single autoloaded option.

**ADR-005: Static Cache vs Object Cache →** 3-tier: static runtime (fastest) → WP Object Cache (if available) → Options API (DB). Static cache is cleared on setting writes.

**ADR-006: Typed Getters Wrapping Generic Get →** `Settings_Registry::get_string('key')` casts to string. `get('key')` returns mixed. Typed getters add type safety for templates.

---

## 2. Singleton Registry Contract

All 15 registries follow this pattern:

```php
<?php
namespace OptixCore\Registry;

abstract class Base_Registry {
    private static array $instances = [];
    private static array $runtime_cache = [];
    private static array $defaults_cache = [];
    private static array $schema_cache = [];
    protected array $entries = [];
    protected bool $registered = false;

    /**
     * Static get_instance() — all registries use this.
     */
    final public static function get_instance(): static {
        $class = static::class;
        if (!isset(self::$instances[$class])) {
            self::$instances[$class] = new static();
        }
        return self::$instances[$class];
    }

    /**
     * Must be called once during plugin bootstrap.
     */
    final public function register(): void {
        if ($this->registered) return;
        $this->entries = $this->define_entries();
        $this->registered = true;
    }

    /**
     * Subclasses define their entries here.
     * @return array<string, array>
     */
    abstract protected function define_entries(): array;

    /**
     * Get a single value by key.
     * Returns default if key not found — never throws.
     */
    public function get(string $key): mixed {
        if (isset(self::$runtime_cache[$key])) {
            return self::$runtime_cache[$key];
        }
        if (!isset($this->entries[$key])) {
            return null;
        }
        $value = $this->resolve_value($key);
        self::$runtime_cache[$key] = $value;
        return $value;
    }

    /**
     * Check if a key exists in schema.
     */
    public function has(string $key): bool {
        return isset($this->entries[$key]);
    }

    /**
     * Get multiple values in one call.
     */
    public function bulk_get(array $keys): array {
        $result = [];
        $db_keys = [];
        $option_names = [];

        foreach ($keys as $i => $key) {
            if (isset(self::$runtime_cache[$key])) {
                $result[$key] = self::$runtime_cache[$key];
                unset($keys[$i]);
            } elseif (isset($this->entries[$key])) {
                $option_names[] = 'optix_' . $key;
                $db_keys[$key] = 'optix_' . $key;
            }
        }

        if (!empty($db_keys)) {
            global $wpdb;
            $placeholders = implode(',', array_fill(0, count($option_names), '%s'));
            $db_results = $wpdb->get_results(
                $wpdb->prepare(
                    "SELECT option_name, option_value FROM {$wpdb->options}
                     WHERE option_name IN ($placeholders)",
                    $option_names
                )
            );
            $found = [];
            foreach ($db_results as $row) {
                $found[$row->option_name] = maybe_unserialize($row->option_value);
            }
            foreach ($db_keys as $key => $opt_name) {
                if (array_key_exists($opt_name, $found)) {
                    $result[$key] = $found[$opt_name];
                    self::$runtime_cache[$key] = $found[$opt_name];
                } else {
                    $default = $this->entries[$key]['default'] ?? null;
                    $result[$key] = $default;
                    self::$runtime_cache[$key] = $default;
                }
            }
        }

        return $result;
    }

    /**
     * Get all current values (respects DB storage, falls back to defaults).
     */
    public function get_all(): array {
        $all = [];
        foreach ($this->entries as $key => $entry) {
            $all[$key] = $this->get($key);
        }
        return $all;
    }

    /**
     * Get all default values (statically cached).
     */
    public function get_defaults(): array {
        $class = static::class;
        if (!isset(self::$defaults_cache[$class])) {
            $defaults = [];
            foreach ($this->entries as $key => $entry) {
                $defaults[$key] = $entry['default'] ?? null;
            }
            self::$defaults_cache[$class] = $defaults;
        }
        return self::$defaults_cache[$class];
    }

    /**
     * Get schema definition for a key.
     */
    public function get_schema(string $key): ?array {
        return $this->entries[$key] ?? null;
    }

    /**
     * Validate a value against its schema.
     */
    public function validate(string $key, mixed $value): bool|\WP_Error {
        if (!isset($this->entries[$key])) {
            return new \WP_Error('unknown_key', __('Registry key not found.', 'optix-core'));
        }
        $entry = $this->entries[$key];
        if (isset($entry['validate'])) {
            $result = $entry['validate']($value);
            if (true !== $result) {
                return new \WP_Error('validation_failed',
                    sprintf(__('Value for "%s" failed validation.', 'optix-core'), $key)
                );
            }
        }
        return true;
    }

    public function count(): int {
        return count($this->entries);
    }

    /**
     * Set a value and persist to DB.
     */
    public function set(string $key, mixed $value): bool|\WP_Error {
        if (!isset($this->entries[$key])) {
            return new \WP_Error('unknown_key', __('Registry key not found.', 'optix-core'));
        }
        $valid = $this->validate($key, $value);
        if (is_wp_error($valid)) {
            return $valid;
        }
        $sanitized = $this->sanitize($key, $value);
        $updated = update_option('optix_' . $key, $sanitized);
        if ($updated) {
            self::$runtime_cache[$key] = $sanitized;
        }
        return $updated;
    }

    /**
     * Sanitize a value using the schema's sanitize callback.
     */
    protected function sanitize(string $key, mixed $value): mixed {
        $entry = $this->entries[$key];
        $callback = $entry['sanitize'] ?? null;
        if ($callback) {
            if (is_callable($callback)) {
                return $callback($value);
            }
            if (function_exists($callback)) {
                return $callback($value);
            }
        }
        return $value;
    }

    /**
     * Resolve value from DB with fallback to default.
     */
    protected function resolve_value(string $key): mixed {
        $entry = $this->entries[$key];
        $stored = get_option('optix_' . $key);
        if (false === $stored) {
            return $entry['default'] ?? null;
        }
        return $stored;
    }

    /**
     * Flush runtime cache for testing/migration.
     */
    public static function flush_cache(): void {
        self::$runtime_cache = [];
    }
}
```

### 2.1 Error Handling Pattern

| Scenario | Behavior |
|----------|----------|
| Key not found in `get()` | Returns `null` — never throws |
| Key not found in `has()` | Returns `false` |
| Validation failure | `validate()` returns `WP_Error` with field-specific message |
| Unknown key in `set()` | Returns `WP_Error` — never silently creates orphan options |
| DB storage failure | `set()` returns `false` (from `update_option()`) |
| Registry not initialized | All methods return safe defaults/empty — no fatal errors |

---

## 3. Settings Registry

### 3.1 Setting Schema

```php
[
    'key'           => 'hero_title',                    // Unique identifier
    'section'       => 'hero',                          // Category
    'type'          => 'string',                        // string|bool|int|float|image|color|select|textarea|code|repeater|group
    'label'         => __('Hero Title', 'optix-core'),  // Translatable
    'description'   => __('Main heading.', 'optix-core'),
    'default'       => 'Welcome',
    'sanitize'      => 'sanitize_text_field',           // WP function name or callable
    'validate'      => fn($v) => strlen($v) <= 200,     // Optional, returns bool
    'options'       => [],                               // For 'select' type: [value => label]
    'placeholder'   => '',
    'transport'     => 'refresh',                        // refresh|postMessage
    'capability'    => 'manage_options',
    'autoload'      => true,                             // Load via alloptions cache
    'acf_sync'      => true,                             // Mirror to ACF if active
    'deprecated'    => false,
    'deprecated_since' => '',
    'migrate_to'    => null,                              // New key name
    'dependencies'  => [],                               // [ [ 'key' => 'parent', 'value' => 'expected' ] ]
    'css_property'  => '',                               // e.g. '--color-primary' or 'background-color'
    'css_selector'  => '',                               // e.g. ':root' or '.hero'
]
```

### 3.2 Typed Getters

```php
Settings_Registry::get_instance()->get_string('key');  // Returns string
Settings_Registry::get_instance()->get_int('key');     // Returns int
Settings_Registry::get_instance()->get_bool('key');    // Returns bool
Settings_Registry::get_instance()->get_image('key', 'full');  // Returns URL or attachment ID
Settings_Registry::get_instance()->get_color('key');   // Returns normalized hex string
Settings_Registry::get_instance()->get_array('key');   // Returns decoded array
Settings_Registry::get_instance()->get_option('key');  // Returns raw mixed value
```

Each typed getter calls `get()` then casts and applies `optix/settings/get/{key}` filter.

### 3.3 Storage Strategy

```
Option name: optix_{key}
  e.g. optix_hero_title

Safety rule: If key exceeds 58 chars, truncate and append md5 prefix:
  optix_{trunc58}_{md5_prefix_4}

Autoload:
  true  → Frequently accessed (90% of settings)
  false → Large code fields (custom_css, custom_js, raw code)
```

### 3.4 Setting Categories (471 total, mapped from existing 487 defaults)

| Section | Scope | Count | Existing Keys To Migrate |
|---------|-------|-------|--------------------------|
| header | sticky, height, icons, search | 8 | header_logo, header_sticky, header_height, menu_font_size, ... |
| topbar | enable, text, bg, languages, currencies | 6 | topbar_enable, topbar_sale_text, topbar_bg, topbar_text, topbar_languages, topbar_currencies |
| announcement_bar | text, colors, scheduling | 4 | (new — not in current defaults) |
| navigation | menus, mega_menus, mobile | 6 | (new — generalize from mega_menu logic) |
| hero | title, subtitle, CTA, bg, overlays | 10 | home_banner_enable, home_banner_title, home_banner_description, ... |
| collections | layout, source, hover | 6 | home_categories_enable, home_categories, ... |
| home_sections | promotion, products, cta, testimonials, benefits, instagram | 30 | home_promotion_enable, home_products_enable, home_cta_enable, ... |
| product_cards | image_ratio, hover, quick_view, badges | 8 | (new — generalize from current single-product defaults) |
| shop_page | sidebar, filters, pagination, columns | 8 | shop_*, (existing) |
| product_page | gallery, tabs, sticky_atc, related | 15 | product_detail_*, product_related_*, (existing full mapping) |
| woocommerce | cart, checkout, mini_cart, account | 25 | cart_*, checkout_*, (existing full mapping) |
| blog | layout, sidebar, featured_image, author | 7 | blog_*, single_blog_* |
| footer | layout, widgets, newsletter, social | 12 | footer_*, newsletter_* |
| typography | heading_font, body_font, sizes, weights | 8 | typography_*, (existing) |
| colors | primary, secondary, accent, text, bg | 12 | color_*, (existing) |
| buttons | bg, text, hover, radius, padding | 8 | button_*, (existing) |
| forms | inputs, labels, validation | 6 | (new — generalize from checkout_* form fields) |
| spacing | containers, section_spacing, gutters | 6 | (new — currently hardcoded in CSS) |
| layout | boxed, full_width, containers | 4 | (new — currently hardcoded in CSS) |
| responsive | desktop, laptop, tablet, mobile | 4 | (new — currently hardcoded in CSS) |
| animations | enable, duration, easing, scroll | 5 | animations_enable, animations_duration, animations_delay |
| effects_3d | enable, perspective, rotate | 4 | effect_3d_* (existing) |
| search | ajax, live_results, suggestions | 4 | search_placeholder (existing, expanded) |
| performance | lazy_load, minify, defer, preload | 5 | performance_* (existing) |
| seo | breadcrumbs, schema, og_meta | 4 | (new — consolidate) |
| accessibility | keyboard_nav, skip_links, contrast | 4 | (new) |
| integrations | social, cookie, analytics | 8 | social_*, cookie_* (existing) |
| custom_code | custom_css, custom_js, head_scripts | 4 | custom_css, custom_js (existing) |
| import_export | export, import, reset | 3 | (new — admin only) |
| about_page | heading, title, team, mission | 10 | about_* (existing, page-specific) |
| contact_page | info, form, map | 10 | contact_* (existing, page-specific) |
| faq_page | enable, items, instagram, benefits | 4 | faq_* (existing, page-specific) |
| coming_soon | logo, subtitle, title, date | 4 | coming_soon_* (existing) |
| error_404 | title, description, btn | 3 | 404_* (existing) |
| login_page | title, btn, labels, links | 8 | login_* (existing) |
| register_page | title, btn, labels, referral | 8 | join_* (existing) |
| portfolio | title, posts_per_page, columns | 3 | portfolio_* (existing) |
| thank_you | title, text, btn, icon | 4 | thank_you_* (existing) |
| load_more | title, button, meta | 5 | load_more_* (existing) |
| privacy | title, content | 2 | privacy_* (existing) |
| terms | title, content | 2 | terms_* (existing) |
| about_inner | team, mission, categories, instagram | 4 | team_*, testimonials_* (existing) |

**Existing 487 defaults → 42 sections → 471 settings** (after deduplicating 16 identical pairs like footer_social/footer_social_links)

### 3.5 Per-Setting Dependencies

```php
// Overlay color only shown when overlay is enabled
[
    'key'    => 'hero_overlay_color',
    'type'   => 'color',
    'default' => '#000000',
    'dependencies' => [
        ['key' => 'hero_overlay_enable', 'value' => true],
    ],
]
```

---

## 4. Section Registry

### 4.1 Section Manifest

```php
Section_Registry::get_instance()->register('hero', [
    'id'          => 'hero',
    'label'       => __('Hero Banner', 'optix-core'),
    'description' => __('Full-width hero section.', 'optix-core'),
    'icon'        => 'cover-image',
    'category'    => 'homepage',
    'order'       => 10,
    'fields'      => [
        'enable'     => ['type' => 'bool',   'default' => true],
        'heading'    => ['type' => 'string', 'default' => 'Welcome'],
        'title'      => ['type' => 'string', 'default' => 'Big Smiles!'],
        'description'=> ['type' => 'string', 'default' => ''],
        'btn_text'   => ['type' => 'string', 'default' => 'Shop Now'],
        'btn_url'    => ['type' => 'string', 'default' => '/shop/'],
        'bg_image'   => ['type' => 'image',  'default' => ''],
        'overlay'    => ['type' => 'color',  'default' => ''],
        'animation'  => ['type' => 'string', 'default' => 'fade-up'],
        'height'     => ['type' => 'string', 'default' => '100vh'],
        'padding'    => ['type' => 'string', 'default' => '60px 0'],
    ],
    'dependencies' => [],
    'templates'    => ['front-page', 'page'],
    'supports'     => ['background_video', 'parallax', 'split_screen'],
]);
```

### 4.2 Rendering Flow

```
Template calls:
    <?php Section_Registry::get_instance()->render('hero'); ?>
        │
        ▼
    Loads: profiles/{active}/template-parts/hero.php
        │
        ▼
    hero.php reads settings via section->get():
        $section->get('title')   → Settings_Registry::get('hero_title')
        $section->get('bg_image')→ Settings_Registry::get('hero_bg_image')
        │
        ▼
    Renders pure HTML with dynamic values
```

---

## 5. Component Registry

### 5.1 Definition

```php
Component_Registry::get_instance()->register('product-card', [
    'id'      => 'product-card',
    'version' => '1.0.0',
    'label'   => __('Product Card', 'optix-core'),
    'settings' => [
        'style'        => ['type' => 'select', 'default' => 'default',
                           'options' => ['default', 'modern', 'classic', 'minimal']],
        'image_ratio'  => ['type' => 'select', 'default' => '1:1',
                           'options' => ['1:1', '3:4', '4:3', 'original']],
        'hover_effect' => ['type' => 'select', 'default' => 'zoom'],
        'show_rating'  => ['type' => 'bool',   'default' => true],
        'show_price'   => ['type' => 'bool',   'default' => true],
        'show_cart'    => ['type' => 'bool',   'default' => true],
    ],
    'dependencies' => [
        'woocommerce' => true,
        'assets'      => ['product-card.css', 'product-card.js'],
    ],
]);
```

### 5.2 Rendering + Lookup Order

```php
// In any template:
Component_Registry::get_instance()->render('product-card', ['product' => $product]);

// Lookup order:
// 1. profiles/{active}/components/product-card.php
// 2. profiles/default/components/product-card.php
// 3. optix-core/templates/components/product-card.php
```

---

## 6. Remaining Registries (Abbreviated)

Each follows the same `Base_Registry` + `get_instance() + register()` pattern.

### Template Registry
```php
Template_Registry::get_instance()->register('front-page', [
    'default'  => 'profiles/{active}/front-page.php',
    'fallback' => 'profiles/default/front-page.php',
    'plugin'   => 'optix-core/templates/front-page.php',
]);
```

### Layout Registry
```php
Layout_Registry::get_instance()->register('default', [
    'container_width' => 1200, 'content_width' => 800,
    'sidebar_width'   => 380, 'gutter'        => 30,
    'columns'         => 12,
]);
```

### WooCommerce Registry
```php
WooCommerce_Registry::get_instance()->register('shop', [
    'layout'    => ['type' => 'select', 'default' => 'sidebar-left',
                    'options' => ['full-width', 'sidebar-left', 'sidebar-right']],
    'columns'   => ['type' => 'int', 'default' => 4],
    'pagination'=> ['type' => 'select', 'default' => 'numbered',
                    'options' => ['numbered', 'load-more', 'infinite-scroll']],
]);
```

### Animation Registry — defines keyframes, durations, easings
### Typography Registry — system fonts, Google Fonts, per-section overrides
### Color Registry — palettes, dark mode, per-section overrides
### Responsive Registry — breakpoints (xl:1200, lg:992, md:768, sm:576, xs:0)
### Hook Registry — documentation only, registers all optix/* hooks with descriptions
### Preset Registry — save/load full snapshots via `save('name')` / `load('name')`
### Demo Registry — defines demo packages with import steps (products, pages, images, settings)
### Block Registry — maps ACF block definitions to Component_Registry
### Asset Registry — registers CSS/JS with conditional enqueue callbacks

---

## 7. Theme_API — The Bridge Layer

Theme_API is the **public API** that profile templates call. It bridges registries and the frontend.

```php
<?php
// optix-core/includes/class-theme-api.php
namespace OptixCore;

class Theme_API {
    private static ?Theme_API $instance = null;

    public static function get_instance(): self { /* ... */ }

    public function init(): void {}

    /**
     * Get a URL for an asset in the active profile.
     */
    public static function asset_url(string $relative_path = ''): string {
        $base = get_template_directory_uri() . '/profiles/'
                . Profile_Router::get_instance()->get_active_profile() . '/assets';
        return $relative_path
            ? $base . '/' . ltrim($relative_path, '/')
            : $base;
    }

    /**
     * Get an image path with fallback support.
     */
    public static function img(string $path = '', string $fallback = ''): string {
        if (empty($path)) return $fallback;
        $profile_base = Profile_Router::get_instance()->get_profile_path() . '/assets';
        if (file_exists($profile_base . $path)) {
            return self::asset_url($path);
        }
        $theme_base = get_template_directory() . '/assets';
        if (file_exists($theme_base . $path)) {
            return get_template_directory_uri() . '/assets' . $path;
        }
        return $fallback;
    }

    /**
     * Convenience getter for template use.
     */
    public static function option(string $key, mixed $default = null): mixed {
        return Registry\Settings_Registry::get_instance()->get($key) ?? $default;
    }

    /** Typed convenience getters */
    public static function string(string $key, string $default = ''): string {
        $val = self::option($key, $default);
        return is_string($val) ? $val : (string) $val;
    }
    public static function int(string $key, int $default = 0): int {
        return (int) self::option($key, $default);
    }
    public static function bool(string $key, bool $default = false): bool {
        return (bool) self::option($key, $default);
    }
    public static function image(string $key, string $size = 'full'): string {
        return Registry\Settings_Registry::get_instance()->get_image($key, $size);
    }
    public static function color(string $key, string $default = '#000000'): string {
        $val = self::option($key, $default);
        return is_string($val) ? sanitize_hex_color($val) ?: $default : $default;
    }
}
```

### Global Functions (theme-side)

```php
// In optix-core/includes/class-theme-api.php (bottom):

function optix_option(string $key, mixed $default = null): mixed {
    return Theme_API::option($key, $default);
}
function optix_string(string $key, string $default = ''): string {
    return Theme_API::string($key, $default);
}
function optix_int(string $key, int $default = 0): int {
    return Theme_API::int($key, $default);
}
function optix_bool(string $key, bool $default = false): bool {
    return Theme_API::bool($key, $default);
}
function optix_img(string $path = '', string $fallback = ''): string {
    return Theme_API::img($path, $fallback);
}
function optix_asset_url(string $relative_path = ''): string {
    return Theme_API::asset_url($relative_path);
}
```

---

## 8. Head_Manager — Centralized Head/Frontend Service

Head_Manager is the **sole source of truth** for SEO, analytics, schema, and cookie consent. All profiles get these automatically from the plugin — profile headers/footers are stripped of this code.

### 8.1 Responsibilities

| Service | Method | Hook |
|---------|--------|------|
| SEO meta tags | `render_seo_meta()` | `wp_head` (priority 1) |
| Open Graph tags | `render_open_graph()` | `wp_head` (priority 2) |
| JSON-LD schema (`@graph`) | `render_json_ld_schema()` | `wp_head` (priority 3) |
| GA/GTM/FB Pixel scripts | `render_analytics_scripts()` | `wp_head` (priority 4) |
| GTM noscript iframe | `render_gtm_noscript()` | `wp_body_open` (priority 1) |
| Cookie consent bar | `render_cookie_consent()` | `wp_footer` (priority 1) |

### 8.2 JSON-LD Schema

Generates an `@graph` with:
- `Organization` (name, logo, URL, social profiles)
- `WebSite` (name, URL, description)
- `SearchAction` (target + query-input)

All data pulled from Settings_Registry; no hardcoded schema.

### 8.3 Cookie Consent

Renders a minimal cookie bar with:
- Text from Settings_Registry (`cookie_notice_text`)
- Accept button with JS dismiss
- Stored in `localStorage` for 365 days
- Auto-hides on subsequent visits

### 8.4 File

```php
// optix-core/includes/class-head-manager.php
namespace OptixCore;

class Head_Manager {
    private static ?Head_Manager $instance = null;

    public static function get_instance(): self { /* ... */ }

    public function init(): void {
        add_action('wp_head', [$this, 'render_seo_meta'], 1);
        add_action('wp_head', [$this, 'render_open_graph'], 2);
        add_action('wp_head', [$this, 'render_json_ld_schema'], 3);
        add_action('wp_head', [$this, 'render_analytics_scripts'], 4);
        add_action('wp_body_open', [$this, 'render_gtm_noscript'], 1);
        add_action('wp_footer', [$this, 'render_cookie_consent'], 1);
    }
}
```

---

## 9. Section_Manifest_Validator — Dev-Time Guard

Section_Manifest_Validator validates that every section's manifest (its declared required fields) exists in Settings_Registry. Runs only when `WP_DEBUG` is true.

### 9.1 Behavior

| Condition | Action |
|-----------|--------|
| `WP_DEBUG` disabled | Silent return — no overhead |
| All manifest fields found in registry | Silent pass |
| Missing field detected | `trigger_error()` with section key + missing field details |
| Multiple sections with issues | Reports all missing fields in one pass |

### 9.2 Purpose

Prevents drift between section definitions and the registry. If a developer adds a field to a section manifest but forgets to register it in Settings_Registry, the validator catches it immediately on admin page load.

### 9.3 File

```php
// optix-core/includes/class-section-manifest-validator.php
namespace OptixCore;

class Section_Manifest_Validator {
    private static ?Section_Manifest_Validator $instance = null;

    public static function get_instance(): self { /* ... */ }

    public function init(): void {
        if (!defined('WP_DEBUG') || !WP_DEBUG) {
            return;
        }
        add_action('admin_init', [$this, 'validate_all_sections'], 99);
    }
}
```

---

## 10. Theme Structure

### 10.1 style.css

```css
/*
Theme Name: Optix
Theme URI: https://optix.test
Author: Optix
Description: Optix Theme Framework — profile-based, registry-driven.
  Requires the Optix Core Framework plugin.
Version: 2.0.0
Requires at least: 6.4
Requires PHP: 8.1
Text Domain: optix
Tags: one-column, accessibility-ready, translation-ready, full-site-editing
*/
```

### 10.2 functions.php (Minimal Bootstrap)

```php
<?php
namespace Optix;

define('OPTIX_VERSION', '2.0.0');

// 1. Textdomain
add_action('after_setup_theme', function() {
    load_theme_textdomain('optix', get_template_directory() . '/languages');
}, 5);

// 2. Emergency fallback — if plugin inactive, load minimal router
if (!defined('OPTIX_CORE_VERSION')) {
    require_once get_template_directory() . '/inc/class-profile-router.php';
    require_once get_template_directory() . '/inc/template-functions.php';
    Profile_Router::get_instance()->init();
    return; // Everything below requires the plugin
}

// 3. Theme supports
add_action('after_setup_theme', function() {
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo', [
        'height'      => 100,
        'width'       => 400,
        'flex-height' => true,
        'flex-width'  => true,
    ]);
    add_theme_support('customize-selective-refresh-widgets');
    add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script']);
    add_theme_support('align-wide');
    add_theme_support('responsive-embeds');
    add_theme_support('woocommerce');

    register_nav_menus([
        'primary' => __('Primary Menu', 'optix'),
        'footer'  => __('Footer Menu', 'optix'),
    ]);

    // Active profile's functions.php — all overrides in one place
    $profile_path = get_template_directory() . '/profiles/'
        . \OptixCore\Profile_Router::get_instance()->get_active_profile();
    $profile_init = $profile_path . '/functions.php';
    if (file_exists($profile_init)) {
        require_once $profile_init;
    }
}, 10);
```

### 10.3 Profile `functions.php` — Override Central

```php
<?php
// profiles/shoes/functions.php
// This file is automatically loaded by theme's functions.php
// All industry-specific overrides live here, not in the theme.

// Override specific settings
add_filter('optix/settings/get/hero_title', fn() => 'Step into Style');
add_filter('optix/settings/get/color_primary', fn() => '#8B4513');
add_filter('optix/settings/get/color_secondary', fn() => '#D2B48C');

// Override component settings
add_filter('optix/component/product-card/settings', function($settings) {
    $settings['style'] = 'minimal';
    $settings['hover_effect'] = 'slide';
    return $settings;
});

// Register profile-specific assets
add_action('wp_enqueue_scripts', function() {
    wp_enqueue_style('optix-shoes',
        optix_asset_url('css/shoes.css'),
        ['optix-main'],
        OPTIX_VERSION
    );
}, 20);
```

### 10.4 Profile Template Example — `front-page.php`

```php
<?php
// profiles/default/front-page.php
// Dumb template — no business logic, only reads settings.
get_header();

$sections = apply_filters('optix/front_page/sections', ['hero', 'product-grid', 'features', 'testimonials', 'newsletter']);
foreach ($sections as $section_id):
    Section_Registry::get_instance()->render($section_id);
endforeach;

get_footer();
```

---

## 11. Profile System

### 11.1 Directory Layout

```
wp-content/themes/optix/
├── style.css                    ← Theme metadata
├── functions.php                ← Bootstrap + fallback
├── index.php                    ← Dispatcher
├── screenshot.png
├── inc/                         ← Emergency fallback (plugin inactive)
│   ├── class-profile-router.php ← Minimal copy of plugin's router
│   └── template-functions.php   ← Minimal template helpers
├── profiles/
│   ├── default/                 ← Fallback for all profiles
│   │   ├── functions.php        ← Can be empty
│   │   ├── header.php
│   │   ├── footer.php
│   │   ├── index.php
│   │   ├── front-page.php
│   │   ├── page.php
│   │   ├── single.php
│   │   ├── archive.php
│   │   ├── 404.php
│   │   ├── search.php
│   │   ├── woocommerce/
│   │   │   ├── single-product.php
│   │   │   ├── archive-product.php
│   │   │   ├── cart.php
│   │   │   ├── checkout.php
│   │   │   └── my-account.php
│   │   ├── template-parts/
│   │   │   ├── hero.php
│   │   │   ├── product-grid.php
│   │   │   ├── features.php
│   │   │   ├── testimonials.php
│   │   │   ├── newsletter.php
│   │   │   └── footer-content.php
│   │   ├── components/
│   │   │   ├── product-card.php
│   │   │   ├── button.php
│   │   │   ├── pagination.php
│   │   │   └── breadcrumb.php
│   │   └── assets/
│   │       ├── css/
│   │       │   ├── main.css
│   │       │   └── woocommerce.css
│   │       ├── js/
│   │       │   ├── main.js
│   │       │   └── navigation.js
│   │       └── images/
│   │           └── placeholder.png
│   ├── shoes/                   ← Example: only overrides what differs
│   │   ├── functions.php
│   │   ├── header.php
│   │   ├── woocommerce/single-product.php
│   │   ├── assets/css/shoes.css
│   │   ├── assets/images/
│   │   └── screenshot.png
│   └── fashion/
│       └── ... (partial overrides only)
```

### 11.2 Cascade

```
1. profiles/{active}/{file}        ← Industry-specific override
2. profiles/default/{file}         ← Framework default
3. optix-core/templates/{file}     ← Plugin fallback (if plugin active)
4. WordPress default hierarchy     ← WP standard
```

### 11.3 Profile Selection

- **Admin UI:** Settings → Optix Framework → Profile → dropdown of discovered profiles
- **Storage:** `wp_options` → `optix_active_profile` (autoloaded)
- **Preview:** `?optix_preview_profile=shoes` (admin only, session-scoped, 10 min transient with nonce)
- **Code:** `update_option('optix_active_profile', 'shoes')`
- **Multisite:** Per-site via standard options (each site selects independently)

### 11.4 Error Handling — Missing Profile

```php
// In Profile_Router::get_active_profile():
$profile = get_option('optix_active_profile', 'default');
if (!is_dir(get_template_directory() . '/profiles/' . $profile)) {
    // Log error once per day
    if (!get_transient('optix_missing_profile_logged')) {
        error_log("Optix: Profile '$profile' not found. Falling back to 'default'.");
        set_transient('optix_missing_profile_logged', true, DAY_IN_SECONDS);
    }
    $profile = 'default';
}
// If 'default' also missing, fallback to theme root files
```

---

## 12. Admin Settings Page

### 12.1 Tab Structure

```
Optix Framework (menu page)
├── General          → branding, header, topbar, announcement_bar, navigation, footer
├── Homepage         → hero, collections, home_sections (promotion, products, cta, testimonials, benefits, instagram)
├── WooCommerce      → product_cards, shop_page, product_page, woocommerce (cart/checkout)
├── Blog             → blog, single blog settings
├── Pages            → about_page, contact_page, faq_page, coming_soon, error_404, login_page, register_page, thank_you, portfolio
├── Typography       → heading_font, body_font, sizes, weights, line_height
├── Colors           → primary, secondary, accent, text, background, buttons, header, footer
├── Layout           → spacing, layout, responsive, buttons, forms
├── Effects          → animations, effects_3d
├── Performance      → lazy_load, minify, defer, preload, search
├── SEO & Integrations → seo, integrations (social, cookie, analytics)
├── Code             → custom_css, custom_js, head_scripts, footer_scripts
├── Profile          → profile selector, import/export, presets
└── Tools            → system status, cache flush, WP-CLI reference
```

Each tab section maps to registry sections. Fields are generated from schema.

### 12.2 Settings Page Rendering

```php
// Simplified — generates fields from schema
foreach ($sections as $section_key => $settings) {
    echo '<div class="optix-section">';
    echo '<h2>' . esc_html($section_key) . '</h2>';
    foreach ($settings as $key => $schema) {
        $current = Settings_Registry::get_instance()->get($key);
        // Render form field based on $schema['type']
        // Apply $schema['dependencies'] as data-attributes for JS show/hide
    }
    echo '</div>';
}
```

### 12.3 Profile Selector UI

```
Active Profile: [shoes ▼]    Preview: ?optix_preview_profile=shoes

Available profiles:
  ✓ default   (fallback)
  ► shoes     (active)
    fashion   (3 overrides: header.php, functions.php, shoes.css)
    furniture (partial — no header.php, inherits from default)
```

---

## 13. Customizer Integration

Settings_Registry acts as the backend for the Customizer:

```php
// In plugin bootstrap:
add_action('customize_register', function($wp_customize) {
    // Expose key Settings_Registry entries to Customizer
    $customizable_sections = ['branding', 'colors', 'typography', 'header', 'footer', 'layout'];

    foreach ($customizable_sections as $section) {
        $settings = Settings_Registry::get_instance()->get_section($section);
        foreach ($settings as $key => $schema) {
            $wp_customize->add_setting('optix_' . $key, [
                'default'    => $schema['default'],
                'transport'  => $schema['transport'] ?? 'refresh',
                'sanitize_callback' => $schema['sanitize'] ?? 'wp_filter_nohtml_kses',
            ]);
            // ... add_control based on type
        }
    }
});

// 'postMessage' transport settings regenerate CSS via JS
// 'refresh' transport settings trigger full page reload
// CSS variables update in real-time for 'postMessage' enabled settings
```

---

## 14. Dynamic CSS

### 14.1 CSS Generation Flow

```
Setting updated → Dynamic_CSS_Generator::regenerate()
    │
    ├── Scans all settings with css_property + css_selector defined
    ├── Generates: :root { --color-primary: #0070f3; }
    │              .hero { background-color: #ffffff; }
    ├── Applies filter: apply_filters('optix/dynamic_css', $css)
    ├── Saves to: update_option('optix_dynamic_css', $css)
    └── Enqueued via: wp_add_inline_style('optix-main', $css)
```

### 14.2 CSS Variable Strategy

```css
/* Generated from Colors + Typography + Spacing registries */
:root {
    --color-primary: #0070f3;
    --color-secondary: #7928ca;
    --color-text: #111;
    --color-bg: #fff;
    --font-heading: 'Archivo', sans-serif;
    --font-body: 'Jost', sans-serif;
    --container-width: 1200px;
    --gutter: 30px;
}

/* Profile override via functions.php */
/* add_filter('optix/dynamic_css', fn($css) => ":root { --color-primary: #8B4513; } $css"); */
```

All selectors are filterable:
```php
add_filter('optix/dynamic_css/selector/hero_bg_color', fn($s) => '.custom-hero');
```

---

## 15. ACF Sync

### 15.1 Direction

```
Settings_Registry::set('hero_title', 'New Value')
    │
    ├── 1. update_option('optix_hero_title', 'New Value')
    │
    ├── 2. If schema['acf_sync'] === true && function_exists('update_field'):
    │       → update_field('hero_title', 'New Value', 'option')
    │
    └── 3. Return result
```

Reads always go through `get_option('optix_*')` — never `get_field()` for framework decisions.

### 15.2 ACF Field Group Management

- ACF exports stored as JSON in `optix-core/acf-json/`
- PHP fallback in class `Acf_Blocks` for when ACF is not active
- 18 existing field groups (2371 lines in `class-optix-theme-options.php`) → consolidated into schema definitions

---

## 16. Schema Versioning & Migration

### 16.1 Version

```php
define('OPTIX_SCHEMA_VERSION', '2.0.0');
// Stored as: wp_optix_schema_version
```

### 16.2 Migration Flow

```
Plugin upgrade detected
    │
    ├── Compare stored version vs OPTIX_SCHEMA_VERSION
    ├── If different:
    │   → Run optix-core/migrations/v1_0_0.php (rename/transform keys)
    │   → Run optix-core/migrations/v1_5_0.php
    │   → Update stored version
    ├── Regenerate dynamic CSS
    ├── Flush all cache layers
    └── Done
```

### 16.3 Migration File Format

```php
<?php
// optix-core/migrations/v1_0_0.php
namespace OptixCore\Schema;

class Migration_v1_0_0 {
    public function up(): array {
        return [
            'rename' => [
                'home_banner_title'       => 'hero_title',
                'home_banner_description' => 'hero_description',
                'home_banner_btn_text'    => 'hero_btn_text',
                // ...
            ],
            'add' => [
                'hero_overlay_color' => '#000000',
                // ...
            ],
            'remove' => [
                'old_header_style',
                // ...
            ],
        ];
    }
}
```

### 16.4 Deprecation

```php
[
    'key'             => 'old_header_style',
    'deprecated'      => true,
    'deprecated_since' => '2.0.0',
    'migrate_to'      => 'header_layout',
    // Readable via get() but hidden from admin UI
    // If value is empty, returns migrate_to's value
]
```

---

## 17. REST API

| Method | Route | Purpose | Auth |
|--------|-------|---------|------|
| GET | `/optix/v1/settings` | List all settings | manage_options |
| GET | `/optix/v1/settings/{key}` | Get single setting | manage_options |
| PUT | `/optix/v1/settings/{key}` | Update single setting | manage_options |
| POST | `/optix/v1/settings/bulk` | Update multiple settings | manage_options |
| GET | `/optix/v1/settings/schema` | Get full schema | manage_options |
| GET | `/optix/v1/profile` | Get active profile | none |
| PUT | `/optix/v1/profile` | Switch profile | manage_options |
| GET | `/optix/v1/profiles` | List available profiles | none |
| POST | `/optix/v1/presets` | Save preset | manage_options |
| GET | `/optix/v1/presets/{id}` | Load preset | manage_options |
| POST | `/optix/v1/cache/flush` | Flush caches | manage_options |

---

## 18. WP-CLI

```bash
wp optix setting get <key>
wp optix setting set <key> <value>
wp optix setting list [--section=<section>]
wp optix setting reset [--section=<section>]

wp optix profile list
wp optix profile activate <name>

wp optix preset save <name>
wp optix preset load <name>

wp optix cache flush

wp optix demo import <demo-name>

wp optix status
wp optix doctor
```

---

## 19. Asset Enqueuing

### 19.1 Profile Assets

```php
// In theme's functions.php or profile's functions.php:
Asset_Registry::get_instance()->register('main-style', [
    'src'     => optix_asset_url('css/main.css'),
    'deps'    => [],
    'version' => OPTIX_VERSION,
    'media'   => 'all',
    'enqueue' => true,  // Always enqueue
]);

Asset_Registry::get_instance()->register('product-card', [
    'src'     => optix_asset_url('css/product-card.css'),
    'deps'    => ['main-style'],
    'version' => OPTIX_VERSION,
    'enqueue' => function(): bool {
        return is_shop() || is_product() || is_product_category();
    },
]);
```

### 19.2 Enqueue Hook

```php
// In Asset_Registry::init():
add_action('wp_enqueue_scripts', function() {
    foreach ($this->entries as $handle => $asset) {
        if (is_callable($asset['enqueue']) ? $asset['enqueue']() : $asset['enqueue']) {
            wp_enqueue_style($handle, $asset['src'], $asset['deps'], $asset['version'], $asset['media']);
        }
    }
});
```

---

## 20. Emergency Fallback (Plugin Inactive)

When `optix-core` plugin is deactivated, the theme's `functions.php` detects `!defined('OPTIX_CORE_VERSION')` and loads minimal fallback:

```
optix/inc/
├── class-profile-router.php    ← Minimal router (same interface, fewer features)
└── template-functions.php      ← optix_option() returns hardcoded defaults

Fallback behavior:
- Profiles still work (file-based routing)
- Options cannot be changed (no admin page)
- All settings return hardcoded defaults
- Dynamic CSS is disabled
- WooCommerce overrides are disabled
- Theme still renders as a functional WordPress theme
```

---

## 21. Setup Wizard (First-Run)

### 21.1 Trigger

- Runs once on plugin activation
- Accessible via `?page=optix-setup` for re-run
- Checks `get_option('optix_setup_complete')` before showing

### 21.2 Steps

```
Step 1: Welcome
    → Framework info, requirements check (PHP 8.1+, WP 6.4+, WooCommerce?)
Step 2: Profile Selection
    → Choose from discovered profiles, each with screenshot
    → Preview link opens in new tab
Step 3: Quick Settings
    → Branding: logo, site title
    → Colors: pick a preset or customize
    → Typography: heading + body font pair
Step 4: WooCommerce (if active)
    → Shop layout, columns, products per page
Step 5: Demo Import (optional)
    → Choose demo content package
    → Progress bar with step-by-step import
Step 6: Done
    → View site, open settings, or import another profile
```

---

## 22. Child Theme Support

The framework supports child themes on top of profiles:

```
wp-content/themes/
├── optix/                         ← Framework (parent)
│   └── profiles/
│       ├── default/
│       └── shoes/
│
└── optix-child-shoes/             ← Child theme
    ├── style.css                   ← Template: optix
    └── profiles/
        └── shoes/                  ← Overrides parent's shoes profile
            └── assets/css/custom.css
```

The Profile_Router checks child theme directory first before parent.

---

## 23. Web Vitals Strategy

| Concern | Strategy |
|---------|----------|
| LCP | Dynamic CSS inlined in `<head>` (avoid render-blocking external CSS for critical styles) |
| | Hero image preloaded via `<link rel="preload">` |
| | Profile assets loaded with `media="print" onload="this.media='all'"` pattern for non-critical CSS |
| CLS | Container heights set as CSS variables (no layout shift on font load) |
| | Images default to `aspect-ratio` from Settings_Registry |
| | Google Fonts loaded with `display=swap` + preconnect |
| FID/INP | Non-critical JS deferred with `type="module"` or `defer` |
| | Event handlers delegated, not per-element |
| Fonts | System font stack as default, Google Fonts with `swap` |
| Images | WebP with JPEG fallback, lazy load below-fold, explicit dimensions |

---

## 24. Security Model

| Operation | Capability | Nonce |
|-----------|-----------|-------|
| View settings | `manage_options` | — |
| Edit settings | `manage_options` | `check_admin_referer('optix_save_settings')` |
| Switch profile | `switch_themes` | `wp_verify_nonce()` via REST |
| Import/export | `manage_options` | `check_admin_referer('optix_import_export')` |
| REST read | `manage_options` | X-WP-Nonce header |
| REST write | `manage_options` | X-WP-Nonce header |
| Profile preview | `read` (any logged-in user, nonce-validated transient) | URL parameter with transient key |

### Output Escaping

| Context | Function |
|---------|----------|
| HTML body | `esc_html()` |
| HTML attribute | `esc_attr()` |
| URL | `esc_url()` |
| Textarea | `esc_textarea()` |
| JavaScript | `wp_json_encode()` + `wp_localize_script()` |
| Dynamic CSS | filter + `wp_strip_all_tags()` for values, property whitelist |
| SVG | custom sanitizer |

---

## 25. Performance Strategy

### 25.1 Option Loading

- 90% of settings autoloaded → alloptions cache (single DB query for all)
- Large settings (custom CSS, JS) NOT autoloaded → lazy-loaded on first call
- Bulk_get uses single `SELECT ... WHERE option_name IN (...)` query

### 25.2 Cache Layers

```
Layer 1: Static (self::$runtime_cache)
    → Per-request, cleared on setting write
    → Zero overhead, fastest

Layer 2: WP Object Cache (if Redis/Memcached available)
    → wp_cache_set('optix_registry_{name}', $data, 'optix', 3600)
    → Invalidated on any optix_ option update

Layer 3: Options API
    → get_option() with alloptions cache
    → Permanent until option is updated
```

### 25.3 Performance Budgets

| Metric | Target |
|--------|--------|
| Settings page load (admin) | < 500ms |
| Frontend TTFB (with object cache) | < 200ms |
| Dynamic CSS generation | < 100ms |
| Profile switch | < 50ms |
| Bulk get (20+ keys) | < 10ms |
| Settings page memory | < 10MB |

---

## 26. Unit Testing Strategy

```
tests/phpunit/
├── bootstrap.php
├── fixtures/
│   └── sample-settings.php
├── registry/
│   ├── Settings_Registry_Test.php
│   ├── Section_Registry_Test.php
│   └── Base_Registry_Test.php
├── api/
│   ├── REST_Test.php
│   └── CLI_Test.php
└── integration/
    ├── ACF_Sync_Test.php
    ├── Profile_Router_Test.php
    └── Migration_Test.php
```

Key test scenarios:
- Register → get → matches default
- Set → get → matches new value
- Bulk_get returns all requested
- Validate passes/fails correctly
- Missing key returns null, not error
- Migration renames keys correctly
- Profile cascade resolves correct files

---

## 27. Migration Path (Current → Target)

### Phase 1: Foundation
- Implement `Base_Registry` + `Settings_Registry` with all 471 defaults
- Migrate existing 487 defaults into new section structure (42 sections)
- Build Section_Registry, Component_Registry, Template_Registry
- Restructure theme: new `functions.php`, `profiles/default/` directory
- Move all existing templates into `profiles/default/`
- No regressions — existing frontend renders identically

### Phase 2: Consolidation
- Move ACF field groups from theme to plugin (acf-json export + PHP fallback)
- Move CPTs/taxonomies entirely to plugin
- Move dynamic CSS generator to plugin
- Build remaining registries: Layout, WooCommerce, Block, Responsive, Asset
- Expand Theme_API with typed getters

### Phase 3: Advanced Features
- Preset Registry with save/load UI
- Demo Registry with one-click import
- REST API endpoints + WP-CLI commands
- Schema versioning + migration
- Typography + Color + Animation registries
- Admin settings page with all 14 tabs

### Phase 4: Polish
- 3 example profiles: shoes, fashion, furniture
- Setup wizard
- Performance audit
- Unit tests

---

## 28. File Structure (Target)

### Plugin: `optix-core/`

```
optix-core/
├── optix-core.php                           ← Bootstrap
├── includes/
│   ├── class-core-plugin.php                ← Main plugin class
│   ├── class-options-manager.php            ← Options API (keep for BC)
│   ├── class-theme-api.php                  ← Public template bridge
│   ├── class-profile-router.php             ← Template routing
│   ├── class-cpt-manager.php                ← CPT registration
│   ├── class-taxonomy-manager.php           ← Taxonomy registration
│   ├── class-mega-menu.php                  ← Mega menu
│   ├── class-3d-effects.php                 ← 3D tilt
│   ├── class-maintenance-mode.php           ← Maintenance
│   ├── class-acf-blocks.php                 ← ACF block registration
│   ├── class-dynamic-css-generator.php      ← Dynamic CSS
│   ├── class-section-manifest-validator.php ← Dev-time manifest validation
│   ├── class-cookie-consent.php             ← Cookie notice
│   ├── class-head-manager.php               ← SEO/OG/analytics/schema/cookie
│   ├── registry/
│   │   ├── class-base-registry.php          ← Base + interface
│   │   ├── class-settings-registry.php      ← All 471 settings
│   │   ├── class-section-registry.php       ← Section manifests
│   │   ├── class-component-registry.php     ← Component definitions
│   │   ├── class-template-registry.php      ← Template mappings
│   │   ├── class-block-registry.php         ← Block definitions
│   │   ├── class-layout-registry.php        ← Layout configs
│   │   ├── class-woocommerce-registry.php   ← WC settings
│   │   ├── class-animation-registry.php     ← Animation presets
│   │   ├── class-typography-registry.php    ← Font system
│   │   ├── class-color-registry.php         ← Color system
│   │   ├── class-responsive-registry.php    ← Responsive settings
│   │   ├── class-hook-registry.php          ← Hook docs
│   │   ├── class-preset-registry.php        ← Design presets
│   │   ├── class-demo-registry.php          ← Demo content
│   │   └── class-asset-registry.php         ← Asset management
│   ├── engine/
│   │   ├── class-cache.php                  ← 3-tier cache
│   │   └── class-schema-migrator.php        ← Version/migration
│   ├── api/
│   │   ├── class-rest-controller.php        ← REST API
│   │   └── class-cli-commands.php           ← WP-CLI
│   └── admin/
│       ├── class-settings-page.php          ← Admin page
│       └── class-setup-wizard.php           ← First-run wizard
├── includes-old/                            ← BC shims (deprecated)
│   ├── class-options-manager.php            ← Remains as BC wrapper
│   └── class-theme-api.php                  ← Remains as BC wrapper
├── acf-json/                                ← ACF field exports
├── migrations/
│   └── v1_5_0.php
├── templates/
│   └── maintenance.php
├── assets/
│   ├── css/admin.css
│   └── js/admin.js
└── tests/
    └── phpunit/
```

### Theme: `optix/`

```
optix/
├── style.css
├── functions.php                            ← Minimal bootstrap + fallback
├── index.php                                ← Dispatcher
├── screenshot.png
├── inc/                                     ← Emergency fallback
│   ├── class-profile-router.php
│   └── template-functions.php
├── profiles/
│   ├── default/
│   │   ├── functions.php
│   │   ├── header.php
│   │   ├── footer.php
│   │   ├── index.php
│   │   ├── front-page.php
│   │   ├── page.php
│   │   ├── single.php
│   │   ├── archive.php
│   │   ├── 404.php
│   │   ├── search.php
│   │   ├── woocommerce/
│   │   ├── template-parts/
│   │   ├── components/
│   │   └── assets/
│   ├── shoes/
│   └── fashion/
└── docs/
    └── profile-creator-guide.md
```

---

## 29. Spec Self-Review

- **Placeholders:** None.
- **Consistency:** All registries use `Base_Registry` + `get_instance()` + `register()`. All methods documented with return types. All namespacing consistent (`OptixCore\Registry\*`).
- **Scope covered:** Architecture (1), Registry contract (2), all 15 registries (3-6), Theme_API bridge (7), Head_Manager (8), Section_Manifest_Validator (9), Theme structure (10), Profiles (11), Admin UI (12), Customizer (13), Dynamic CSS (14), ACF sync (15), Schema versioning (16), REST API (17), WP-CLI (18), Asset enqueuing (19), Fallback (20), Setup wizard (21), Child themes (22), Web Vitals (23), Security (24), Performance (25), Testing (26), Migration path (27), File structure (28), Spec self-review (29).
- **Edge cases:** Missing key returns null, missing profile falls back to default, plugin inactive → emergency fallback, option name truncation for long keys, deprecation chain, multisite isolation, cache invalidation.
- **Existing code alignment:** Singleton pattern matches current code, Theme_API bridge extends existing class, Profile_Router builds on existing router, Options_Manager kept as BC layer.
