# Optix Core — Phase 1 Migration Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Extract all controls, settings, CPTs, and features from the Optix theme into a standalone `optix-core` plugin, then restructure the theme to support template profiles.

**Architecture:** Plugin handles ALL data storage (Options API, CPTs, taxonomies, ACF field groups, dynamic CSS generation). Theme becomes pure presentation — minimal functions.php, templates live under `profiles/{name}/`, router plugin hooks into `template_include`, `get_template_part`, and `wc_get_template` to load active profile files.

**Tech Stack:** WordPress 6.4+, PHP 8.1+, ACF (optional), WooCommerce (optional)

## Global Constraints

- PHP 8.1+, WordPress 6.4+
- All option keys use `optix_` prefix in wp_options table
- No theme mods used — all settings via Options API
- Plugin namespace: `OptixCore`. Theme namespace: `Optix` (unchanged)
- ACF is optional — Options API is always source of truth
- All dynamic CSS selectors must be filterable via `apply_filters()`
- Profile names: lowercase, alphanumeric + hyphens only, sanitized with `sanitize_file_name()`
- Plugin textdomain: `optix-core`. Theme textdomain: `optix` (unchanged)
- Every feature must have an emergency fallback if plugin is deactivated

---

## File Structure

### Plugin (new): `/wp-content/plugins/optix-core/`

```
optix-core/
├── optix-core.php                           ← Plugin header + bootstrapper
├── includes/
│   ├── class-core-plugin.php                ← Main plugin class
│   ├── class-options-manager.php            ← optix_get_option(), defaults, sanitization
│   ├── class-cpt-manager.php                ← Portfolio CPT registration
│   ├── class-taxonomy-manager.php           ← Portfolio Category taxonomy
│   ├── class-maintenance-mode.php           ← template_redirect + template fallback
│   ├── class-dynamic-css-generator.php      ← CSS output with filterable selectors
│   ├── class-mega-menu.php                  ← Mega menu walker + JS
│   ├── class-3d-effects.php                 ← 3D tilt logic + inline JS
│   ├── class-acf-blocks.php                 ← ACF block registration + fallback
│   ├── class-profile-router.php             ← Template routing (4 entry points)
│   └── class-theme-api.php                  ← Public template tags for profiles
├── admin/
│   ├── class-settings-page.php              ← Settings → Optix Framework admin page
│   └── partials/
│       └── settings-page.php                ← HTML for settings page
├── templates/
│   └── maintenance.php                      ← Maintenance mode fallback template
└── assets/
    └── block-icons/
        ├── portfolio-grid.svg
        └── project-highlight.svg
```

### Theme (modified): `/wp-content/themes/optix/`

```
optix/
├── style.css                                ← Unchanged (theme metadata)
├── functions.php                            ← MINIMAL: theme support + emergency fallback
├── index.php                                ← Simple dispatcher (loads profile header/footer)
├── profiles/
│   ├── default/
│   │   ├── header.php                       ← Moved from root
│   │   ├── footer.php                       ← Moved from root
│   │   ├── front-page.php                   ← Moved from root
│   │   ├── single.php                       ← Moved from root
│   │   ├── page.php                         ← Moved from root
│   │   ├── archive.php                      ← Moved from root
│   │   ├── search.php                       ← Moved from root
│   │   ├── 404.php                          ← Moved from root
│   │   ├── home.php                         ← Moved from root
│   │   ├── sidebar.php                      ← Moved from root
│   │   ├── comments.php                     ← Moved from root
│   │   ├── single-portfolio.php             ← Moved from root
│   │   ├── archive-portfolio.php            ← Moved from root
│   │   ├── woocommerce.php                  ← Moved from root
│   │   ├── woocommerce/                     ← Moved from root
│   │   ├── template-parts/
│   │   │   ├── header/                      ← Moved from root template-parts/header/
│   │   │   ├── kids-collection/             ← Moved from root template-parts/kids-collection/
│   │   │   └── blocks/                      ← Moved from root template-parts/blocks/
│   │   ├── functions.php                    ← NEW: profile-specific filter overrides
│   │   ├── assets/
│   │   │   ├── css/                         ← Moved from assets/kids-collection/css/
│   │   │   ├── js/                          ← Moved from assets/kids-collection/js/
│   │   │   └── images/                      ← Moved from assets/kids-collection/images/
│   │   └── screenshot.png
│   │
│   └── (future profiles here)
│
├── theme.json                               ← Unchanged
├── inc/                                     ← EMERGENCY FALLBACK ONLY
│   └── class-optix-theme-options.php
└── assets/                                  ← Only shared assets remain
    └── svg/block-icons/
```

---

### Task 1: Create Plugin Skeleton

**Files:**
- Create: `wp-content/plugins/optix-core/optix-core.php`
- Create: `wp-content/plugins/optix-core/includes/class-core-plugin.php`
- Modify: (none yet — first task)

**Interfaces:**
- Consumes: nothing (first task)
- Produces: `OptixCore\Plugin` main class with `init()` method; plugin activation/deactivation hooks; autoload pattern for `includes/` files

- [ ] **Step 1: Create plugin directory structure**

Run:
```bash
mkdir -p wp-content/plugins/optix-core/includes
mkdir -p wp-content/plugins/optix-core/admin/partials
mkdir -p wp-content/plugins/optix-core/templates
mkdir -p wp-content/plugins/optix-core/assets/block-icons
```

- [ ] **Step 2: Write plugin header file (`optix-core.php`)**

```php
<?php
/**
 * Plugin Name:       Optix Core Framework
 * Plugin URI:        https://optix.test
 * Description:       Core engine for Optix theme — controls, settings, CPTs, and features. Required by Optix theme.
 * Version:           1.0.0
 * Requires at least: 6.4
 * Requires PHP:      8.1
 * Author:            Optix
 * Text Domain:       optix-core
 * Domain Path:       /languages
 *
 * @package OptixCore
 */

declare(strict_types=1);

namespace OptixCore;

defined('ABSPATH') || exit;

define('OPTIX_CORE_VERSION', '1.0.0');
define('OPTIX_CORE_FILE', __FILE__);
define('OPTIX_CORE_PATH', plugin_dir_path(__FILE__));
define('OPTIX_CORE_URL', plugin_dir_url(__FILE__));

// Autoload includes
$includes = [
    'class-core-plugin.php',
    'class-options-manager.php',
    'class-cpt-manager.php',
    'class-taxonomy-manager.php',
    'class-maintenance-mode.php',
    'class-dynamic-css-generator.php',
    'class-mega-menu.php',
    'class-3d-effects.php',
    'class-acf-blocks.php',
    'class-profile-router.php',
    'class-theme-api.php',
];

foreach ($includes as $file) {
    $path = OPTIX_CORE_PATH . 'includes/' . $file;
    if (file_exists($path)) {
        require_once $path;
    }
}

require_once OPTIX_CORE_PATH . 'admin/class-settings-page.php';

/**
 * Initialize the plugin
 */
function init(): void {
    $plugin = new Plugin();
    $plugin->init();
}
add_action('plugins_loaded', __NAMESPACE__ . '\\init');

/**
 * Activation hook
 */
register_activation_hook(__FILE__, function () {
    // Migrate existing theme options on first activation
    if (function_exists(__NAMESPACE__ . '\\Options_Manager')) {
        Options_Manager::migrate_from_theme();
    }
    flush_rewrite_rules();
});

/**
 * Deactivation hook
 */
register_deactivation_hook(__FILE__, function () {
    flush_rewrite_rules();
});
```

- [ ] **Step 3: Write main plugin class (`includes/class-core-plugin.php`)**

```php
<?php
declare(strict_types=1);

namespace OptixCore;

defined('ABSPATH') || exit;

class Plugin {

    private static ?Plugin $instance = null;

    public static function get_instance(): self {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function init(): void {
        // Initialize all modules in dependency order
        Options_Manager::get_instance()->init();
        Profile_Router::get_instance()->init();
        
        Cpt_Manager::get_instance()->init();
        Taxonomy_Manager::get_instance()->init();
        
        Maintenance_Mode::get_instance()->init();
        Mega_Menu::get_instance()->init();
        ThreeD_Effects::get_instance()->init();
        Acf_Blocks::get_instance()->init();
        Dynamic_CSS_Generator::get_instance()->init();
        
        Settings_Page::get_instance()->init();
        Theme_API::get_instance()->init();
    }
}
```

- [ ] **Step 4: Verify plugin loads without errors**

Run: `wp plugin list 2>&1` or visit `/wp-admin/plugins.php`
Expected: Plugin appears in list. Activate it. No PHP errors.

- [ ] **Step 5: Commit**

```bash
git add wp-content/plugins/optix-core/
git commit -m "feat: create optix-core plugin skeleton"
```

---

### Task 2: Build Options Manager

**Files:**
- Create: `wp-content/plugins/optix-core/includes/class-options-manager.php`
- Refer to: `wp-content/themes/optix/inc/template-functions.php` (existing `optix_get_option()`)
- Refer to: `wp-content/themes/optix/inc/defaults.php`

**Interfaces:**
- Consumes: nothing standalone
- Produces: `Options_Manager::get_instance()->init()`, `optix_get_option($key, $default)` public function, `optix_set_defaults()` activation handler

- [ ] **Step 1: Write `class-options-manager.php`**

```php
<?php
declare(strict_types=1);

namespace OptixCore;

defined('ABSPATH') || exit;

class Options_Manager {

    private static ?Options_Manager $instance = null;
    private static array $cache = [];

    public static function get_instance(): self {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function init(): void {
        // No hooks needed — called directly by other modules
    }

    /**
     * Get an option with caching and fallback chain.
     * 
     * Tier 1: WordPress Options API (source of truth)
     * Tier 2: ACF get_field() (if ACF active)
     * Tier 3: Legacy theme_mod (migration fallback)
     * Tier 4: Default value from defaults array
     */
    public static function get(string $key, $default = null) {
        if (array_key_exists($key, self::$cache)) {
            return self::$cache[$key];
        }

        // Tier 1: Options API (source of truth)
        $value = get_option('optix_' . $key, '__not_set__');
        if ('__not_set__' !== $value && '' !== $value && false !== $value) {
            self::$cache[$key] = $value;
            return $value;
        }

        // Tier 2: ACF (if active)
        if (function_exists('get_field')) {
            $acf_value = get_field($key, 'option');
            if (null !== $acf_value && '' !== $acf_value && false !== $acf_value) {
                self::$cache[$key] = $acf_value;
                return $acf_value;
            }
        }

        // Tier 3: Legacy theme_mod (migration fallback)
        $mod_value = get_theme_mod('optix_' . $key, '__not_set__');
        if ('__not_set__' !== $mod_value) {
            self::$cache[$key] = $mod_value;
            return $mod_value;
        }

        // Tier 4: Default
        $defaults = self::get_defaults();
        if (array_key_exists($key, $defaults)) {
            self::$cache[$key] = $defaults[$key];
            return $defaults[$key];
        }

        return $default;
    }

    /**
     * Set an option — always stores to Options API.
     */
    public static function set(string $key, $value): void {
        update_option('optix_' . $key, $value);
        self::$cache[$key] = $value;
        
        // Sync to ACF if active
        if (function_exists('update_field')) {
            update_field($key, $value, 'option');
        }
    }

    /**
     * Default values for all settings.
     */
    public static function get_defaults(): array {
        return [
            'effect_3d_enable'       => false,
            'effect_3d_perspective'  => 1000,
            'effect_3d_rotate_x'     => 5,
            'effect_3d_rotate_y'     => 5,
            'maintenance_mode_enable' => false,
            'active_profile'         => 'default',
            // Add all existing defaults from inc/defaults.php
        ];
    }

    /**
     * Migration: read existing settings from theme into plugin options.
     */
    public static function migrate_from_theme(): void {
        $existing = $GLOBALS['wpdb']->get_results(
            $GLOBALS['wpdb']->prepare(
                "SELECT option_name, option_value FROM {$GLOBALS['wpdb']->options} 
                 WHERE option_name LIKE %s",
                'optix_%'
            )
        );
        // Already stored correctly — just ensure cache is populated
        foreach ($existing as $row) {
            $key = str_replace('optix_', '', $row->option_name);
            self::$cache[$key] = maybe_unserialize($row->option_value);
        }
        
        // Convert theme mods to options
        $mods = get_theme_mods();
        if (is_array($mods)) {
            foreach ($mods as $key => $value) {
                if (strpos($key, 'optix_') === 0) {
                    $opt_key = str_replace('optix_', '', $key);
                    if (get_option('optix_' . $opt_key) === false) {
                        update_option('optix_' . $opt_key, $value);
                    }
                }
            }
        }
        
        update_option('optix_migration_complete', time());
    }
}

/**
 * Global helper function for easy access.
 */
function optix_get_option(string $key, $default = null) {
    return Options_Manager::get($key, $default);
}
```

- [ ] **Step 2: Verify options work**

Run the activation, then check: `wp option get optix_migration_complete`
Expected: returns a timestamp if migration ran

- [ ] **Step 3: Commit**

```bash
git add wp-content/plugins/optix-core/includes/class-options-manager.php
git commit -m "feat: add Options_Manager with get/set/migrate"
```

---

### Task 3: Build Profile Router

**Files:**
- Create: `wp-content/plugins/optix-core/includes/class-profile-router.php`

**Interfaces:**
- Consumes: `Options_Manager::get('active_profile')`, `Options_Manager::get('maintenance_mode_enable')`
- Produces: hooks into `template_include`, `get_template_part`, `wc_get_template`, `stylesheet_uri`, `template_directory_uri`

- [ ] **Step 1: Write `class-profile-router.php`**

```php
<?php
declare(strict_types=1);

namespace OptixCore;

defined('ABSPATH') || exit;

class Profile_Router {

    private static ?Profile_Router $instance = null;
    private ?string $active_profile = null;
    private ?string $profile_path = null;

    public static function get_instance(): self {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function init(): void {
        add_filter('template_include', [$this, 'route_template'], 99);
        add_filter('get_template_part', [$this, 'route_template_part'], 10, 3);
        add_filter('wc_get_template', [$this, 'route_wc_template'], 10, 5);
        add_filter('stylesheet_uri', [$this, 'route_stylesheet']);
        add_filter('template_directory_uri', [$this, 'route_directory_uri']);
    }

    public function get_active_profile(): string {
        if (null !== $this->active_profile) {
            return $this->active_profile;
        }
        
        // Check preview mode first
        if (is_admin() && isset($_GET['optix_preview_profile'])) {
            $preview = sanitize_file_name(wp_unslash($_GET['optix_preview_profile']));
            if ($this->is_valid_profile($preview)) {
                $this->active_profile = $preview;
                return $preview;
            }
        }
        
        $profile = get_option('optix_active_profile', 'default');
        if (!is_string($profile) || empty($profile)) {
            $profile = 'default';
        }
        $profile = sanitize_file_name($profile);
        
        if (!$this->is_valid_profile($profile)) {
            $profile = 'default';
        }
        
        $this->active_profile = $profile;
        return $profile;
    }

    private function is_valid_profile(string $profile): bool {
        return is_dir(get_template_directory() . '/profiles/' . $profile);
    }

    public function get_profile_path(): string {
        if (null !== $this->profile_path) {
            return $this->profile_path;
        }
        $this->profile_path = get_template_directory() . '/profiles/' . $this->get_active_profile();
        return $this->profile_path;
    }

    public function route_template(string $template): string {
        // Maintenance mode takes priority
        if (optix_get_option('maintenance_mode_enable') && !is_user_logged_in()) {
            $maintenance = $this->get_profile_path() . '/maintenance.php';
            if (file_exists($maintenance)) return $maintenance;
            $maintenance = get_template_directory() . '/profiles/default/maintenance.php';
            if (file_exists($maintenance)) return $maintenance;
            $maintenance = OPTIX_CORE_PATH . 'templates/maintenance.php';
            if (file_exists($maintenance)) return $maintenance;
        }

        $template_name = basename($template);
        if (!$template_name) return $template;

        // Level 1: Active profile
        $candidate = $this->get_profile_path() . '/' . $template_name;
        if (file_exists($candidate)) return $candidate;

        // Level 2: Default profile
        $candidate = get_template_directory() . '/profiles/default/' . $template_name;
        if (file_exists($candidate)) return $candidate;

        // Level 3: Plugin fallback
        $candidate = OPTIX_CORE_PATH . 'templates/' . $template_name;
        if (file_exists($candidate)) return $candidate;

        return $template;
    }

    public function route_template_part(string $template, string $slug, string $name = null): string {
        $profile_path = $this->get_profile_path();

        $candidates = [];
        if ($name) {
            $candidates[] = $profile_path . '/' . $slug . '-' . $name . '.php';
        }
        $candidates[] = $profile_path . '/' . $slug . '.php';

        // Default profile fallback
        $default_path = get_template_directory() . '/profiles/default';
        if ($name) {
            $candidates[] = $default_path . '/' . $slug . '-' . $name . '.php';
        }
        $candidates[] = $default_path . '/' . $slug . '.php';

        foreach ($candidates as $candidate) {
            if (file_exists($candidate)) {
                return $candidate;
            }
        }

        return $template;
    }

    public function route_wc_template(string $template, string $template_name, array $args, string $template_path, string $default_path): string {
        $profile_wc = $this->get_profile_path() . '/woocommerce/' . $template_name;
        if (file_exists($profile_wc)) return $profile_wc;

        $default_wc = get_template_directory() . '/profiles/default/woocommerce/' . $template_name;
        if (file_exists($default_wc)) return $default_wc;

        return $template;
    }

    public function route_stylesheet(string $uri): string {
        $profile_css = get_template_directory_uri() . '/profiles/' . $this->get_active_profile() . '/assets/css/style.css';
        $profile_css_path = get_template_directory() . '/profiles/' . $this->get_active_profile() . '/assets/css/style.css';
        if (file_exists($profile_css_path)) {
            return $profile_css;
        }
        return $uri;
    }

    public function route_directory_uri(string $uri): string {
        return get_template_directory_uri() . '/profiles/' . $this->get_active_profile();
    }
}
```

- [ ] **Step 2: Commit**

```bash
git add wp-content/plugins/optix-core/includes/class-profile-router.php
git commit -m "feat: add Profile_Router with 4 routing hooks"
```

---

### Task 4: Build Admin Settings Page

**Files:**
- Create: `wp-content/plugins/optix-core/admin/class-settings-page.php`
- Create: `wp-content/plugins/optix-core/admin/partials/settings-page.php`

**Interfaces:**
- Consumes: `Profile_Router::get_active_profile()`, `Options_Manager::set()`
- Produces: Settings → Optix Framework admin page with profile selector dropdown

- [ ] **Step 1: Write `class-settings-page.php`**

```php
<?php
declare(strict_types=1);

namespace OptixCore;

defined('ABSPATH') || exit;

class Settings_Page {

    private static ?Settings_Page $instance = null;

    public static function get_instance(): self {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function init(): void {
        add_action('admin_menu', [$this, 'add_admin_menu']);
        add_action('admin_init', [$this, 'register_settings']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_styles']);
    }

    public function add_admin_menu(): void {
        add_options_page(
            __('Optix Framework', 'optix-core'),
            __('Optix Framework', 'optix-core'),
            'manage_options',
            'optix-framework',
            [$this, 'render_page']
        );
    }

    public function register_settings(): void {
        register_setting('optix_framework', 'optix_active_profile', [
            'type' => 'string',
            'sanitize_callback' => [$this, 'sanitize_profile'],
            'default' => 'default',
        ]);
    }

    public function sanitize_profile($value): string {
        $profile = sanitize_file_name($value);
        $profile_dir = get_template_directory() . '/profiles/' . $profile;
        if (!is_dir($profile_dir)) {
            add_settings_error(
                'optix_framework',
                'invalid_profile',
                __('Selected profile directory does not exist. Reverted to default.', 'optix-core'),
                'error'
            );
            return 'default';
        }
        return $profile;
    }

    public function render_page(): void {
        if (!current_user_can('manage_options')) return;
        $profiles = $this->get_available_profiles();
        $active = Profile_Router::get_instance()->get_active_profile();
        ?>
        <div class="wrap">
            <h1><?php echo esc_html__('Optix Framework Settings', 'optix-core'); ?></h1>
            <form action="options.php" method="post">
                <?php settings_fields('optix_framework'); ?>
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php esc_html_e('Active Profile', 'optix-core'); ?></th>
                        <td>
                            <select name="optix_active_profile">
                                <?php foreach ($profiles as $profile): ?>
                                    <option value="<?php echo esc_attr($profile); ?>" 
                                        <?php selected($active, $profile); ?>>
                                        <?php echo esc_html($profile); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <p class="description">
                                <?php esc_html_e('Select which template profile is active on the front-end.', 'optix-core'); ?>
                            </p>
                        </td>
                    </tr>
                </table>
                <?php submit_button(__('Save Profile', 'optix-core')); ?>
            </form>
            <hr>
            <h2><?php esc_html_e('Available Profiles', 'optix-core'); ?></h2>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th><?php esc_html_e('Profile', 'optix-core'); ?></th>
                        <th><?php esc_html_e('Status', 'optix-core'); ?></th>
                        <th><?php esc_html_e('Preview', 'optix-core'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($profiles as $profile): ?>
                        <tr>
                            <td><?php echo esc_html($profile); ?></td>
                            <td><?php echo $profile === $active ? esc_html__('Active', 'optix-core') : esc_html__('Inactive', 'optix-core'); ?></td>
                            <td>
                                <a href="<?php echo esc_url(add_query_arg('optix_preview_profile', $profile, home_url('/'))); ?>" 
                                   class="button button-small" target="_blank">
                                    <?php esc_html_e('Preview', 'optix-core'); ?>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php
    }

    private function get_available_profiles(): array {
        $profiles_dir = get_template_directory() . '/profiles';
        if (!is_dir($profiles_dir)) return ['default'];
        
        $dirs = glob($profiles_dir . '/*', GLOB_ONLYDIR);
        $profiles = [];
        foreach ($dirs as $dir) {
            $profiles[] = basename($dir);
        }
        return $profiles;
    }

    public function enqueue_admin_styles(string $hook): void {
        if ('settings_page_optix-framework' !== $hook) return;
        echo '<style>.form-table th { width: 200px; }</style>';
    }
}
```

- [ ] **Step 2: Test settings page**

Visit `/wp-admin/options-general.php?page=optix-framework`
Expected: Page renders with profile dropdown listing "default"

- [ ] **Step 3: Commit**

```bash
git add wp-content/plugins/optix-core/admin/
git commit -m "feat: add Optix Framework settings page with profile selector"
```

---

### Task 5: Migrate CPTs and Taxonomies to Plugin

**Files:**
- Create: `wp-content/plugins/optix-core/includes/class-cpt-manager.php`
- Create: `wp-content/plugins/optix-core/includes/class-taxonomy-manager.php`
- Copy: `wp-content/themes/optix/inc/includes/post-type.php` → plugin (add `OptixCore\` alias)
- Copy: `wp-content/themes/optix/inc/includes/taxonomy.php` → plugin
- Copy: `wp-content/themes/optix/inc/post-types/portfolio.php` → plugin
- Copy: `wp-content/themes/optix/inc/taxonomies/portfolio-category.php` → plugin
- Refer to: `wp-content/themes/optix/inc/includes/theme-setup.php` (build_taxonomies, build_post_types)

**Interfaces:**
- Consumes: base classes `Post_Type` and `Taxonomy` (moved to plugin with namespaced versions)
- Produces: portfolio CPT and portfolio_category taxonomy registered via plugin init

- [ ] **Step 1: Copy base classes into plugin with Dual Namespace support**

Create `wp-content/plugins/optix-core/includes/post-type.php`:
```php
<?php
declare(strict_types=1);

namespace OptixCore;

abstract class Post_Type {
    public $post_type = null;
    public $slug;
    public $translations = [];

    public function __construct(string $slug) {
        $this->slug = $slug;
    }

    abstract protected function register(): void;

    public function register_wp_post_type(string $slug, array $args) {
        if (!empty($args['pll_translatable']) && false === ($args['public'] ?? true)) {
            add_filter('pll_get_post_types', function($cpts) use ($slug) {
                $cpts[$slug] = $slug;
                return $cpts;
            }, 9, 2);
        }
        $this->register_translations();
        return register_post_type($slug, $args);
    }

    private function register_translations(): void {
        $translations = $this->translations;
        add_filter('optix_translations', function($strings) use ($translations) {
            return array_merge($translations, (array) $strings);
        }, 10, 2);
    }
}
```

- [ ] **Step 2: Write `class-cpt-manager.php`**

```php
<?php
declare(strict_types=1);

namespace OptixCore;

defined('ABSPATH') || exit;

class Cpt_Manager {

    private static ?Cpt_Manager $instance = null;

    public static function get_instance(): self {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function init(): void {
        add_action('init', [$this, 'register_post_types'], 5);
    }

    public function register_post_types(): void {
        $post_types = [
            'Portfolio',
        ];

        foreach ($post_types as $name) {
            $slug = strtolower($name);
            $classname = __NAMESPACE__ . '\\' . $name;
            $file_path = OPTIX_CORE_PATH . 'includes/post-types/' . str_replace('_', '-', $slug) . '.php';

            if (!file_exists($file_path)) continue;
            if (!class_exists($classname)) {
                require_once $file_path;
            }
            if (!class_exists($classname)) continue;

            $instance = new $classname($slug);
            $instance->register();
        }
    }
}
```

- [ ] **Step 3: Copy portfolio.php and portfolio-category.php into plugin's `includes/post-types/` and `includes/taxonomies/`**

Create directories:
```bash
mkdir -p wp-content/plugins/optix-core/includes/post-types
mkdir -p wp-content/plugins/optix-core/includes/taxonomies
```

Copy the files, changing namespace from `Optix` to `OptixCore`:
```bash
cp wp-content/themes/optix/inc/post-types/portfolio.php wp-content/plugins/optix-core/includes/post-types/portfolio.php
cp wp-content/themes/optix/inc/taxonomies/portfolio-category.php wp-content/plugins/optix-core/includes/taxonomies/portfolio-category.php
```

Edit the copied files to change `namespace Optix;` to `namespace OptixCore;`

- [ ] **Step 4: Verify CPTs register**

Activate plugin, visit `/wp-admin/edit.php?post_type=portfolio`
Expected: Portfolio admin page renders. "Add New Project" button visible.

- [ ] **Step 5: Commit**

```bash
git add wp-content/plugins/optix-core/includes/class-cpt-manager.php
git add wp-content/plugins/optix-core/includes/class-taxonomy-manager.php
git add wp-content/plugins/optix-core/includes/post-types/
git add wp-content/plugins/optix-core/includes/taxonomies/
git commit -m "feat: migrate CPTs and taxonomies to plugin"
```

---

### Task 6: Migrate Dynamic CSS Generator

**Files:**
- Create: `wp-content/plugins/optix-core/includes/class-dynamic-css-generator.php`
- Refer to: `wp-content/themes/optix/inc/dynamic-css.php` (current CSS generation)

**Interfaces:**
- Consumes: `Options_Manager::get()` for all settings
- Produces: `Dynamic_CSS_Generator::generate()` output added to `wp_head`; all selectors filterable via `apply_filters()`

- [ ] **Step 1: Write `class-dynamic-css-generator.php`**

```php
<?php
declare(strict_types=1);

namespace OptixCore;

defined('ABSPATH') || exit;

class Dynamic_CSS_Generator {

    private static ?Dynamic_CSS_Generator $instance = null;

    public static function get_instance(): self {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function init(): void {
        add_action('wp_head', [$this, 'output_styles'], 99);
    }

    public function output_styles(): void {
        echo '<style id="optix-dynamic-css">' . "\n";
        echo $this->generate() . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput
        echo '</style>' . "\n";
    }

    public function generate(): string {
        $css = '';

        // Mega menu styles
        $css .= $this->get_mega_menu_styles();

        // 3D tilt effects
        $css .= $this->get_tilt_3d_styles();

        // Theme customization styles
        $css .= $this->get_theme_styles();

        return trim($css);
    }

    private function get_mega_menu_styles(): string {
        $selector = apply_filters('optix/css/mega-menu', '.optix-mega-menu');
        $enabled = optix_get_option('mega_menu_enable');
        if (!$enabled) return '';

        return sprintf('%s { position: relative; }', $selector) . "\n"
             . sprintf('%s .sub-menu { display: none; position: absolute; }', $selector) . "\n"
             . sprintf('%s:hover .sub-menu { display: block; }', $selector) . "\n";
    }

    private function get_tilt_3d_styles(): string {
        $selector = apply_filters('optix/css/tilt-3d', '.optix-tilt-3d');
        $enabled = optix_get_option('effect_3d_enable');
        if (!$enabled) return '';

        $perspective = (int) optix_get_option('effect_3d_perspective', 1000);
        $rotate_x = (int) optix_get_option('effect_3d_rotate_x', 5);
        $rotate_y = (int) optix_get_option('effect_3d_rotate_y', 5);

        return sprintf('%s { perspective: %dpx; transform-style: preserve-3d; }', $selector, $perspective) . "\n"
             . sprintf('%s:hover { transform: rotateX(%ddeg) rotateY(%ddeg); transition: transform 0.3s ease; }', $selector, $rotate_x, $rotate_y) . "\n";
    }

    private function get_theme_styles(): string {
        $output = '';
        $output = apply_filters('optix/css/theme-styles', $output);
        return $output;
    }
}
```

- [ ] **Step 2: Verify CSS output**

Visit any front-end page. View source. Check for `<style id="optix-dynamic-css">`.
Expected: Styles are output with the `optix-` prefixed selectors.

- [ ] **Step 3: Commit**

```bash
git add wp-content/plugins/optix-core/includes/class-dynamic-css-generator.php
git commit -m "feat: migrate dynamic CSS generator with filterable selectors"
```

---

### Task 7: Migrate Feature Modules (Maintenance, Mega Menu, 3D, ACF)

**Files:**
- Create: `wp-content/plugins/optix-core/includes/class-maintenance-mode.php`
- Create: `wp-content/plugins/optix-core/includes/class-mega-menu.php`
- Create: `wp-content/plugins/optix-core/includes/class-3d-effects.php`
- Create: `wp-content/plugins/optix-core/includes/class-acf-blocks.php`
- Copy: block icon SVGs to plugin

**Interfaces:**
- All consume: `Options_Manager::get()`
- All produce: WordPress hooks and actions that profiles can use

- [ ] **Step 1: Write `class-maintenance-mode.php`**

```php
<?php
declare(strict_types=1);

namespace OptixCore;

defined('ABSPATH') || exit;

class Maintenance_Mode {

    private static ?Maintenance_Mode $instance = null;

    public static function get_instance(): self {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function init(): void {
        add_action('template_redirect', [$this, 'handle_maintenance'], 1);
        add_action('optix/render/maintenance', [$this, 'render_default_maintenance']);
    }

    public function handle_maintenance(): void {
        if (!optix_get_option('maintenance_mode_enable')) return;
        if (is_user_logged_in()) return;

        do_action('optix/render/maintenance');

        // Fallback: show simple maintenance message
        if (did_action('optix/render/maintenance') < 2) {
            $this->render_default_maintenance();
        }
        exit;
    }

    public function render_default_maintenance(): void {
        wp_die(
            esc_html__('Site is under maintenance. Please check back soon.', 'optix-core'),
            esc_html__('Maintenance Mode', 'optix-core'),
            ['response' => 503]
        );
    }
}
```

- [ ] **Step 2: Write `class-3d-effects.php`**

```php
<?php
declare(strict_types=1);

namespace OptixCore;

defined('ABSPATH') || exit;

class ThreeD_Effects {

    private static ?ThreeD_Effects $instance = null;

    public static function get_instance(): self {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function init(): void {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts'], 20);
    }

    public function enqueue_scripts(): void {
        if (!optix_get_option('effect_3d_enable')) return;

        wp_add_inline_script('optix-profile-script', '
            document.addEventListener("DOMContentLoaded", function() {
                var tiltElements = document.querySelectorAll(".optix-tilt-3d");
                tiltElements.forEach(function(el) {
                    el.addEventListener("mousemove", function(e) {
                        var rect = el.getBoundingClientRect();
                        var x = (e.clientX - rect.left) / rect.width - 0.5;
                        var y = (e.clientY - rect.top) / rect.height - 0.5;
                        el.style.transform = "rotateX(" + (-y * 10) + "deg) rotateY(" + (x * 10) + "deg)";
                    });
                    el.addEventListener("mouseleave", function() {
                        el.style.transform = "rotateX(0deg) rotateY(0deg)";
                    });
                });
            });
        ');
    }
}
```

- [ ] **Step 3: Write `class-acf-blocks.php`**

```php
<?php
declare(strict_types=1);

namespace OptixCore;

defined('ABSPATH') || exit;

class Acf_Blocks {

    private static ?Acf_Blocks $instance = null;

    public static function get_instance(): self {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function init(): void {
        add_action('acf/init', [$this, 'register_acf_blocks']);
        add_action('init', [$this, 'register_native_blocks'], 20);
    }

    public function register_acf_blocks(): void {
        if (!function_exists('acf_register_block_type')) return;

        $blocks = [
            [
                'name' => 'portfolio-grid',
                'title' => 'Portfolio Grid',
                'icon' => 'portfolio',
                'render_callback' => [$this, 'render_block'],
            ],
            [
                'name' => 'project-highlight',
                'title' => 'Project Highlight',
                'icon' => 'star-filled',
                'render_callback' => [$this, 'render_block'],
            ],
        ];

        foreach ($blocks as $block) {
            acf_register_block_type(wp_parse_args($block, [
                'category' => 'optix',
                'mode' => 'preview',
                'align' => 'full',
                'post_types' => ['page', 'portfolio'],
                'supports' => ['align' => false, 'anchor' => true],
            ]));
        }
    }

    public function register_native_blocks(): void {
        // Native fallback for non-ACF blocks
        // (extend as needed)
    }

    public function render_block(array $block, string $content = '', bool $is_preview = false, int $post_id = 0): void {
        optix_profile_template_part('template-parts/blocks', $block['name']);
    }
}
```

- [ ] **Step 4: Copy block icon SVGs**

```bash
cp wp-content/themes/optix/assets/svg/block-icons/* wp-content/plugins/optix-core/assets/block-icons/
```

- [ ] **Step 5: Commit**

```bash
git add wp-content/plugins/optix-core/includes/class-maintenance-mode.php
git add wp-content/plugins/optix-core/includes/class-mega-menu.php
git add wp-content/plugins/optix-core/includes/class-3d-effects.php
git add wp-content/plugins/optix-core/includes/class-acf-blocks.php
git add wp-content/plugins/optix-core/assets/
git commit -m "feat: migrate maintenance, 3D, mega menu, ACF blocks to plugin"
```

---

### Task 8: Restructure Theme for Profiles

**Files:**
- Create: `profiles/default/` directory with all templates moved in
- Create: `profiles/default/functions.php`
- Modify: root `functions.php` (make minimal)
- Modify: root `index.php` (dispatcher)
- Modify: root `header.php`, `footer.php` → remove from root, profile handles them
- Move: `assets/kids-collection/` → `profiles/default/assets/`
- Move: `template-parts/header/`, `template-parts/kids-collection/`, `template-parts/blocks/` → `profiles/default/template-parts/`
- Modify: `inc/` → keep as emergency fallback only
- Remove: old root-level template files that now live in profiles/default/

**Interfaces:**
- Consumes: `Profile_Router` routing all template_include and get_template_part calls
- Produces: working theme that routes through `profiles/default/` when plugin is active

- [ ] **Step 1: Create profiles/default directory and move all template files**

```bash
mkdir -p wp-content/themes/optix/profiles/default
mkdir -p wp-content/themes/optix/profiles/default/assets
mkdir -p wp-content/themes/optix/profiles/default/template-parts
mkdir -p wp-content/themes/optix/profiles/default/woocommerce

# Move root template files (excluding functions.php, style.css, theme.json, inc/)
mv wp-content/themes/optix/header.php wp-content/themes/optix/profiles/default/
mv wp-content/themes/optix/footer.php wp-content/themes/optix/profiles/default/
mv wp-content/themes/optix/front-page.php wp-content/themes/optix/profiles/default/
mv wp-content/themes/optix/single.php wp-content/themes/optix/profiles/default/
mv wp-content/themes/optix/page.php wp-content/themes/optix/profiles/default/
mv wp-content/themes/optix/archive.php wp-content/themes/optix/profiles/default/
mv wp-content/themes/optix/404.php wp-content/themes/optix/profiles/default/
mv wp-content/themes/optix/search.php wp-content/themes/optix/profiles/default/
mv wp-content/themes/optix/home.php wp-content/themes/optix/profiles/default/
mv wp-content/themes/optix/sidebar.php wp-content/themes/optix/profiles/default/
mv wp-content/themes/optix/comments.php wp-content/themes/optix/profiles/default/
mv wp-content/themes/optix/single-portfolio.php wp-content/themes/optix/profiles/default/
mv wp-content/themes/optix/archive-portfolio.php wp-content/themes/optix/profiles/default/
mv wp-content/themes/optix/woocommerce.php wp-content/themes/optix/profiles/default/
mv wp-content/themes/optix/maintenance.php wp-content/themes/optix/profiles/default/

# Move woocommerce directory
mv wp-content/themes/optix/woocommerce/* wp-content/themes/optix/profiles/default/woocommerce/
rmdir wp-content/themes/optix/woocommerce

# Move template-parts
mv wp-content/themes/optix/template-parts/* wp-content/themes/optix/profiles/default/template-parts/
rmdir wp-content/themes/optix/template-parts

# Move assets
mv wp-content/themes/optix/assets/kids-collection/* wp-content/themes/optix/profiles/default/assets/
rm -rf wp-content/themes/optix/assets/kids-collection
```

- [ ] **Step 2: Delete old templates/kids-collection directory (if it exists)**

```bash
# These are duplicate examples from the original theme build — now unnecessary
rm -rf wp-content/themes/optix/templates/kids-collection
rmdir wp-content/themes/optix/templates 2>/dev/null
```

- [ ] **Step 3: Create minimal root `index.php`**

```php
<?php
/**
 * Template dispatcher — routes to active profile.
 *
 * @package optix
 */

// If the core plugin is active, it handles routing.
// If not, fallback to default profile.
if (!class_exists('OptixCore\Profile_Router')) {
    $profile = get_option('optix_active_profile', 'default');
    $profile_path = get_template_directory() . '/profiles/' . $profile;
    
    if (!is_dir($profile_path)) {
        $profile_path = get_template_directory() . '/profiles/default';
    }
    
    // Simple template routing
    $template = $profile_path . '/index.php';
    if (file_exists($template)) {
        require $template;
        return;
    }
}

// With plugin active, all routing is handled by Profile_Router
// This file is a safety fallback
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<p><?php esc_html_e('Optix theme is active. Please activate the Optix Core plugin.', 'optix'); ?></p>
<?php wp_footer(); ?>
</body>
</html>
```

- [ ] **Step 4: Create minimal root `functions.php`**

```php
<?php
/**
 * Minimal theme functions — emergency fallback + basic supports.
 *
 * @package optix
 */

namespace Optix;

defined('ABSPATH') || exit;

// Emergency fallback: if plugin is deactivated, load inc/ directly
if (!class_exists('OptixCore\Plugin')) {
    $fallback_files = [
        '/inc/includes/taxonomy.php',
        '/inc/includes/post-type.php',
        '/inc/template-functions.php',
        '/inc/dynamic-css.php',
        '/inc/defaults.php',
        '/inc/hooks.php',
        '/inc/includes.php',
        '/inc/template-tags.php',
        '/inc/class-optix-theme-options.php',
    ];
    foreach ($fallback_files as $file) {
        $path = get_template_directory() . $file;
        if (file_exists($path)) {
            require_once $path;
        }
    }
}

// Theme supports — always needed
add_action('after_setup_theme', function() {
    add_theme_support('post-thumbnails');
    add_theme_support('title-tag');
    add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'script', 'style']);
    add_theme_support('custom-logo');
    add_theme_support('align-wide');
    add_theme_support('responsive-embeds');
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
    load_theme_textdomain('optix', get_template_directory() . '/languages');
});

// Enqueue profile assets
add_action('wp_enqueue_scripts', function() {
    $profile = get_option('optix_active_profile', 'default');
    if (!is_string($profile) || empty($profile)) $profile = 'default';
    $profile = sanitize_file_name($profile);
    
    $profile_uri = get_template_directory_uri() . '/profiles/' . $profile;
    $profile_dir = get_template_directory() . '/profiles/' . $profile;
    $version = wp_get_theme()->get('Version');
    
    // Main style
    $css_file = $profile_dir . '/assets/css/style.css';
    $css_version = file_exists($css_file) ? filemtime($css_file) : $version;
    wp_enqueue_style('optix-profile-style', $profile_uri . '/assets/css/style.css', [], $css_version);
    
    // Theme JS
    $js_file = $profile_dir . '/assets/js/theme.js';
    $js_version = file_exists($js_file) ? filemtime($js_file) : $version;
    wp_enqueue_script('optix-profile-script', $profile_uri . '/assets/js/theme.js', ['jquery'], $js_version, true);
});

// Load profile-specific functions.php
$active_profile = get_option('optix_active_profile', 'default');
if (is_string($active_profile) && !empty($active_profile)) {
    $profile_functions = get_template_directory() . '/profiles/' . sanitize_file_name($active_profile) . '/functions.php';
    if (file_exists($profile_functions)) {
        require_once $profile_functions;
    }
}
```

- [ ] **Step 5: Update `style.css` header** (add Template line if needed — not needed since this is the main theme)

- [ ] **Step 6: Create `profiles/default/functions.php`** — profile-specific overrides

```php
<?php
/**
 * Default profile functions — CSS selector overrides.
 *
 * @package optix
 */

// Map plugin CSS selectors to default profile's HTML classes
add_filter('optix/css/mega-menu', function() { return '.navigation-wrapper'; });
add_filter('optix/css/tilt-3d', function() { return '.product-card, .project-card'; });
add_filter('optix/css/hero', function() { return '.hero-section'; });
```

- [ ] **Step 7: Test the restructured theme**

1. Activate both plugin and theme
2. Visit front-end — should load profiles/default/front-page.php
3. View source — verify CSS and JS load from profiles/default/assets/
4. Check `#optix-dynamic-css` is output

- [ ] **Step 8: Commit**

```bash
git add wp-content/themes/optix/profiles/
git add wp-content/themes/optix/index.php
git add wp-content/themes/optix/functions.php
git add -A wp-content/themes/optix/
git commit -m "refactor: restructure theme for profile-based routing"
```

---

### Task 9: Verify Everything Works

**Files:** (testing only)

- [ ] **Step 1: Verify default profile loads correctly**

Visit home page. Expected: Site renders with same design as before migration.

- [ ] **Step 2: Verify plugin deactivation fallback**

Deactivate `optix-core` plugin. Expected: Site still works (theme fallback loads inc/).

- [ ] **Step 3: Verify all options survive**

Run: `wp option list --search='optix_*'`
Expected: All option keys present. Count matches pre-migration.

- [ ] **Step 4: Verify CPTs and admin pages**

Visit `/wp-admin/edit.php?post_type=portfolio` and `/wp-admin/admin.php?page=optix-theme-options`
Expected: Both work.

- [ ] **Step 5: Test profile selector**

Visit Settings → Optix Framework. Change profile to "default". Save.
Expected: No errors, profile switches.

- [ ] **Step 6: Create a test profile for validation**

```bash
mkdir -p wp-content/themes/optix/profiles/test-profile
cp wp-content/themes/optix/profiles/default/index.php wp-content/themes/optix/profiles/test-profile/
cp wp-content/themes/optix/profiles/default/style.css wp-content/themes/optix/profiles/test-profile/
```

Verify it appears in the dropdown. Activate it. Verify site loads (even if basic).

- [ ] **Step 7: Commit**

```bash
git add -A
git commit -m "chore: migration phase 1 complete — plugin + profile architecture operational"
```

---

## Spec Coverage Check

| Spec Section | Covered By |
|-------------|-----------|
| Plugin structure | Task 1 |
| Options Manager / optix_get_option | Task 2 |
| Profile Router (all 4 hooks) | Task 3 |
| Admin settings page | Task 4 |
| CPTs and taxonomies | Task 5 |
| Dynamic CSS with filterable selectors | Task 6 |
| Feature modules (maintenance, 3D, etc.) | Task 7 |
| Theme restructure + profiles | Task 8 |
| Verification | Task 9 |
| Migration script | Task 2 (Options_Manager::migrate_from_theme) |
| Emergency fallback | Task 8 (functions.php) |
