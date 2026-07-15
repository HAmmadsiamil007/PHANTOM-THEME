# Phase 3 — Remaining 11 Registries Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use loop-engineering to implement each task with quality review at 100/100. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Implement the remaining 11 registries (Layout, WooCommerce, Typography, Color, Responsive, Animation, Hook, Block, Preset, Demo, Asset) following Base_Registry pattern, wire into plugin bootstrap.

**Architecture:** All 11 registries live in `optix-core/includes/registry/`, extend `abstract class Base_Registry`, use `get_instance()` singleton, register via `define_entries()`. 7 are pure data definitions. 3 add CRUD methods. 1 (Asset) adds `init()` for wp_enqueue_scripts hook. All get added to `$registry_files` array in `optix-core.php` and `init_registries()` in `class-core-plugin.php`.

**Tech Stack:** PHP 8.1+, WordPress 6.4+, WooCommerce 8.0+ (optional)

## Global Constraints

- Namespace: `OptixCore\Registry`
- All files: `declare(strict_types=1);` + `defined('ABSPATH') || exit;`
- Singleton: `get_instance()` per existing pattern
- File name: `class-{name}-registry.php`
- Option prefix: `optix_`
- All entries follow `['key' => 'value', 'type' => 'string', 'default' => '', ...]` format
- No HTML/CSS/animation knowledge in registries
- Must be added to `$registry_files` in `optix-core.php`
- Must have `register()` call in `class-core-plugin.php::init_registries()`
- Must have `use` import in `class-core-plugin.php`

---
## File Structure

### Create (11 files):
- `optix-core/includes/registry/class-layout-registry.php`
- `optix-core/includes/registry/class-woocommerce-registry.php`
- `optix-core/includes/registry/class-typography-registry.php`
- `optix-core/includes/registry/class-color-registry.php`
- `optix-core/includes/registry/class-responsive-registry.php`
- `optix-core/includes/registry/class-animation-registry.php`
- `optix-core/includes/registry/class-hook-registry.php`
- `optix-core/includes/registry/class-block-registry.php`
- `optix-core/includes/registry/class-preset-registry.php`
- `optix-core/includes/registry/class-demo-registry.php`
- `optix-core/includes/registry/class-asset-registry.php`

### Modify (2 files):
- `optix-core/optix-core.php` — add 11 entries to `$registry_files`
- `optix-core/includes/class-core-plugin.php` — add 11 `use` imports + `register()` calls

---

### Task 1: Create 7 Simple Data Registries

**Files:**
- Create: `optix-core/includes/registry/class-layout-registry.php`
- Create: `optix-core/includes/registry/class-woocommerce-registry.php`
- Create: `optix-core/includes/registry/class-typography-registry.php`
- Create: `optix-core/includes/registry/class-color-registry.php`
- Create: `optix-core/includes/registry/class-responsive-registry.php`
- Create: `optix-core/includes/registry/class-animation-registry.php`
- Create: `optix-core/includes/registry/class-hook-registry.php`

**Interfaces:**
- Consumes: `Base_Registry` (abstract class providing `get()`, `set()`, `has()`, `register()`)
- Produces: 7 `Base_Registry` subclasses — singletons with `define_entries()` returning entry arrays

**Step 1:** Create `class-layout-registry.php`

```php
<?php
declare(strict_types=1);

namespace OptixCore\Registry;

defined('ABSPATH') || exit;

class Layout_Registry extends Base_Registry {

    protected function define_entries(): array {
        return [
            'container_width' => [
                'type' => 'int',
                'default' => 1200,
                'label' => __('Container Width (px)', 'optix-core'),
                'sanitize' => 'absint',
            ],
            'content_width' => [
                'type' => 'int',
                'default' => 800,
                'label' => __('Content Width (px)', 'optix-core'),
                'sanitize' => 'absint',
            ],
            'sidebar_width' => [
                'type' => 'int',
                'default' => 380,
                'label' => __('Sidebar Width (px)', 'optix-core'),
                'sanitize' => 'absint',
            ],
            'gutter' => [
                'type' => 'int',
                'default' => 30,
                'label' => __('Grid Gutter (px)', 'optix-core'),
                'sanitize' => 'absint',
            ],
            'columns' => [
                'type' => 'int',
                'default' => 12,
                'label' => __('Grid Columns', 'optix-core'),
                'sanitize' => 'absint',
            ],
        ];
    }
}
```

**Step 2:** Create `class-woocommerce-registry.php`

```php
<?php
declare(strict_types=1);

namespace OptixCore\Registry;

defined('ABSPATH') || exit;

class WooCommerce_Registry extends Base_Registry {

    protected function define_entries(): array {
        return [
            'shop_layout' => [
                'type' => 'select',
                'default' => 'sidebar-left',
                'options' => ['full-width', 'sidebar-left', 'sidebar-right'],
                'label' => __('Shop Layout', 'optix-core'),
                'sanitize' => 'sanitize_text_field',
            ],
            'columns' => [
                'type' => 'int',
                'default' => 4,
                'label' => __('Product Columns', 'optix-core'),
                'sanitize' => 'absint',
            ],
            'pagination' => [
                'type' => 'select',
                'default' => 'numbered',
                'options' => ['numbered', 'load-more', 'infinite-scroll'],
                'label' => __('Pagination Style', 'optix-core'),
                'sanitize' => 'sanitize_text_field',
            ],
            'product_card_style' => [
                'type' => 'select',
                'default' => 'default',
                'options' => ['default', 'modern', 'classic', 'minimal'],
                'label' => __('Product Card Style', 'optix-core'),
                'sanitize' => 'sanitize_text_field',
            ],
            'gallery' => [
                'type' => 'select',
                'default' => 'zoom',
                'options' => ['zoom', 'lightbox', 'slider', 'none'],
                'label' => __('Gallery Style', 'optix-core'),
                'sanitize' => 'sanitize_text_field',
            ],
            'related_count' => [
                'type' => 'int',
                'default' => 4,
                'label' => __('Related Products Count', 'optix-core'),
                'sanitize' => 'absint',
            ],
        ];
    }
}
```

**Step 3:** Create `class-typography-registry.php`

```php
<?php
declare(strict_types=1);

namespace OptixCore\Registry;

defined('ABSPATH') || exit;

class Typography_Registry extends Base_Registry {

    protected function define_entries(): array {
        return [
            'heading_font' => [
                'type' => 'string',
                'default' => 'Archivo',
                'label' => __('Heading Font Family', 'optix-core'),
                'sanitize' => 'sanitize_text_field',
            ],
            'body_font' => [
                'type' => 'string',
                'default' => 'Jost',
                'label' => __('Body Font Family', 'optix-core'),
                'sanitize' => 'sanitize_text_field',
            ],
            'h1_size' => [
                'type' => 'int',
                'default' => 48,
                'label' => __('H1 Font Size (px)', 'optix-core'),
                'sanitize' => 'absint',
            ],
            'h2_size' => [
                'type' => 'int',
                'default' => 36,
                'label' => __('H2 Font Size (px)', 'optix-core'),
                'sanitize' => 'absint',
            ],
            'h3_size' => [
                'type' => 'int',
                'default' => 24,
                'label' => __('H3 Font Size (px)', 'optix-core'),
                'sanitize' => 'absint',
            ],
            'body_size' => [
                'type' => 'int',
                'default' => 16,
                'label' => __('Body Font Size (px)', 'optix-core'),
                'sanitize' => 'absint',
            ],
            'heading_weight' => [
                'type' => 'string',
                'default' => '700',
                'label' => __('Heading Font Weight', 'optix-core'),
                'sanitize' => 'sanitize_text_field',
            ],
            'body_weight' => [
                'type' => 'string',
                'default' => '400',
                'label' => __('Body Font Weight', 'optix-core'),
                'sanitize' => 'sanitize_text_field',
            ],
            'line_height' => [
                'type' => 'float',
                'default' => 1.6,
                'label' => __('Base Line Height', 'optix-core'),
                'sanitize' => 'floatval',
            ],
        ];
    }
}
```

**Step 4:** Create `class-color-registry.php`

```php
<?php
declare(strict_types=1);

namespace OptixCore\Registry;

defined('ABSPATH') || exit;

class Color_Registry extends Base_Registry {

    protected function define_entries(): array {
        return [
            'primary' => [
                'type' => 'color',
                'default' => '#705b53',
                'label' => __('Primary Color', 'optix-core'),
                'sanitize' => 'sanitize_hex_color',
            ],
            'secondary' => [
                'type' => 'color',
                'default' => '#c19a6b',
                'label' => __('Secondary Color', 'optix-core'),
                'sanitize' => 'sanitize_hex_color',
            ],
            'accent' => [
                'type' => 'color',
                'default' => '#d4a373',
                'label' => __('Accent Color', 'optix-core'),
                'sanitize' => 'sanitize_hex_color',
            ],
            'text' => [
                'type' => 'color',
                'default' => '#666666',
                'label' => __('Text Color', 'optix-core'),
                'sanitize' => 'sanitize_hex_color',
            ],
            'heading' => [
                'type' => 'color',
                'default' => '#222222',
                'label' => __('Heading Color', 'optix-core'),
                'sanitize' => 'sanitize_hex_color',
            ],
            'background' => [
                'type' => 'color',
                'default' => '#ffffff',
                'label' => __('Background Color', 'optix-core'),
                'sanitize' => 'sanitize_hex_color',
            ],
            'dark_mode_enable' => [
                'type' => 'bool',
                'default' => false,
                'label' => __('Enable Dark Mode', 'optix-core'),
                'sanitize' => 'absint',
            ],
            'dark_mode_bg' => [
                'type' => 'color',
                'default' => '#1a1a1a',
                'label' => __('Dark Mode Background', 'optix-core'),
                'sanitize' => 'sanitize_hex_color',
            ],
        ];
    }
}
```

**Step 5:** Create `class-responsive-registry.php`

```php
<?php
declare(strict_types=1);

namespace OptixCore\Registry;

defined('ABSPATH') || exit;

class Responsive_Registry extends Base_Registry {

    protected function define_entries(): array {
        return [
            'xl' => [
                'type' => 'int',
                'default' => 1200,
                'label' => __('XL Breakpoint (px)', 'optix-core'),
                'sanitize' => 'absint',
            ],
            'lg' => [
                'type' => 'int',
                'default' => 992,
                'label' => __('LG Breakpoint (px)', 'optix-core'),
                'sanitize' => 'absint',
            ],
            'md' => [
                'type' => 'int',
                'default' => 768,
                'label' => __('MD Breakpoint (px)', 'optix-core'),
                'sanitize' => 'absint',
            ],
            'sm' => [
                'type' => 'int',
                'default' => 576,
                'label' => __('SM Breakpoint (px)', 'optix-core'),
                'sanitize' => 'absint',
            ],
            'xs' => [
                'type' => 'int',
                'default' => 0,
                'label' => __('XS Breakpoint (px)', 'optix-core'),
                'sanitize' => 'absint',
            ],
        ];
    }
}
```

**Step 6:** Create `class-animation-registry.php`

```php
<?php
declare(strict_types=1);

namespace OptixCore\Registry;

defined('ABSPATH') || exit;

class Animation_Registry extends Base_Registry {

    protected function define_entries(): array {
        return [
            'enable' => [
                'type' => 'bool',
                'default' => true,
                'label' => __('Enable Animations', 'optix-core'),
                'sanitize' => 'absint',
            ],
            'duration' => [
                'type' => 'string',
                'default' => '0.3s',
                'label' => __('Default Duration', 'optix-core'),
                'sanitize' => 'sanitize_text_field',
            ],
            'easing' => [
                'type' => 'select',
                'default' => 'ease-out',
                'options' => ['linear', 'ease', 'ease-in', 'ease-out', 'ease-in-out'],
                'label' => __('Default Easing', 'optix-core'),
                'sanitize' => 'sanitize_text_field',
            ],
            'scroll_animations' => [
                'type' => 'bool',
                'default' => true,
                'label' => __('Enable Scroll Animations', 'optix-core'),
                'sanitize' => 'absint',
            ],
            'parallax_enable' => [
                'type' => 'bool',
                'default' => true,
                'label' => __('Enable Parallax', 'optix-core'),
                'sanitize' => 'absint',
            ],
            'hover_effects' => [
                'type' => 'bool',
                'default' => true,
                'label' => __('Enable Hover Effects', 'optix-core'),
                'sanitize' => 'absint',
            ],
        ];
    }
}
```

**Step 7:** Create `class-hook-registry.php`

```php
<?php
declare(strict_types=1);

namespace OptixCore\Registry;

defined('ABSPATH') || exit;

class Hook_Registry extends Base_Registry {

    protected function define_entries(): array {
        return [
            'optix_core/init' => [
                'type' => 'action',
                'description' => __('Fired after all registries and engines are initialized', 'optix-core'),
                'params' => [],
            ],
            'optix/settings/get/{key}' => [
                'type' => 'filter',
                'description' => __('Filter the value of any setting before returning', 'optix-core'),
                'params' => ['value', 'key'],
            ],
            'optix/front_page/sections' => [
                'type' => 'filter',
                'description' => __('Modify the ordered list of sections rendered on front-page', 'optix-core'),
                'params' => ['sections'],
            ],
            'optix/section/{id}/before' => [
                'type' => 'action',
                'description' => __('Fired before a section is rendered', 'optix-core'),
                'params' => ['section_id'],
            ],
            'optix/section/{id}/after' => [
                'type' => 'action',
                'description' => __('Fired after a section is rendered', 'optix-core'),
                'params' => ['section_id'],
            ],
            'optix/component/{id}/settings' => [
                'type' => 'filter',
                'description' => __('Filter component settings before rendering', 'optix-core'),
                'params' => ['settings', 'component_id'],
            ],
            'optix/component/{id}/before' => [
                'type' => 'action',
                'description' => __('Fired before a component is rendered', 'optix-core'),
                'params' => ['component_id'],
            ],
            'optix/component/{id}/after' => [
                'type' => 'action',
                'description' => __('Fired after a component is rendered', 'optix-core'),
                'params' => ['component_id'],
            ],
            'optix/dynamic_css' => [
                'type' => 'filter',
                'description' => __('Filter the generated dynamic CSS before output', 'optix-core'),
                'params' => ['css'],
            ],
            'optix/dynamic_css/selector/{key}' => [
                'type' => 'filter',
                'description' => __('Filter CSS selector for a specific setting', 'optix-core'),
                'params' => ['selector', 'key'],
            ],
            'optix/profile/switch' => [
                'type' => 'action',
                'description' => __('Fired when the active profile is switched', 'optix-core'),
                'params' => ['new_profile', 'old_profile'],
            ],
            'optix/preset/save/{name}' => [
                'type' => 'action',
                'description' => __('Fired after a preset is saved', 'optix-core'),
                'params' => ['name', 'data'],
            ],
            'optix/preset/load/{name}' => [
                'type' => 'action',
                'description' => __('Fired after a preset is loaded', 'optix-core'),
                'params' => ['name', 'data'],
            ],
            'optix/cache/flush' => [
                'type' => 'action',
                'description' => __('Fired when all caches are flushed', 'optix-core'),
                'params' => [],
            ],
            'optix/rest/response/{endpoint}' => [
                'type' => 'filter',
                'description' => __('Filter REST API response data', 'optix-core'),
                'params' => ['response', 'endpoint'],
            ],
        ];
    }
}
```

**Step 8:** Verify all 7 files exist and parse

Run: `docker exec optix_wordpress php -l /var/www/html/wp-content/plugins/optix-core/includes/registry/class-layout-registry.php`
Run: `docker exec optix_wordpress php -l /var/www/html/wp-content/plugins/optix-core/includes/registry/class-woocommerce-registry.php`
Run: `docker exec optix_wordpress php -l /var/www/html/wp-content/plugins/optix-core/includes/registry/class-typography-registry.php`
Run: `docker exec optix_wordpress php -l /var/www/html/wp-content/plugins/optix-core/includes/registry/class-color-registry.php`
Run: `docker exec optix_wordpress php -l /var/www/html/wp-content/plugins/optix-core/includes/registry/class-responsive-registry.php`
Run: `docker exec optix_wordpress php -l /var/www/html/wp-content/plugins/optix-core/includes/registry/class-animation-registry.php`
Run: `docker exec optix_wordpress php -l /var/www/html/wp-content/plugins/optix-core/includes/registry/class-hook-registry.php`

Expected: `No syntax errors detected` for each.

---

### Task 2: Create Block_Registry

**Files:**
- Create: `optix-core/includes/registry/class-block-registry.php`

**Interfaces:**
- Consumes: `Base_Registry`
- Produces: `Block_Registry` with `get_component_id(string $block_name): ?string` mapping ACF block slugs to component IDs

**Step 1:** Create `class-block-registry.php`

```php
<?php
declare(strict_types=1);

namespace OptixCore\Registry;

defined('ABSPATH') || exit;

class Block_Registry extends Base_Registry {

    protected function define_entries(): array {
        return [
            'portfolio-grid' => [
                'component_id' => 'product_card',
                'description' => __('Portfolio grid ACF block', 'optix-core'),
                'acf_block' => 'portfolio-grid',
                'fields' => [],
            ],
            'project-highlight' => [
                'component_id' => 'promo_box',
                'description' => __('Project highlight ACF block', 'optix-core'),
                'acf_block' => 'project-highlight',
                'fields' => [],
            ],
        ];
    }

    public function get_component_id(string $block_name): ?string {
        foreach ($this->entries as $key => $entry) {
            if ($entry['acf_block'] === $block_name) {
                return $entry['component_id'] ?? null;
            }
        }
        return null;
    }

    public function get_block_for_component(string $component_id): ?string {
        foreach ($this->entries as $key => $entry) {
            if ($entry['component_id'] === $component_id) {
                return $entry['acf_block'] ?? null;
            }
        }
        return null;
    }
}
```

**Step 2:** Verify syntax

Run: `docker exec optix_wordpress php -l /var/www/html/wp-content/plugins/optix-core/includes/registry/class-block-registry.php`
Expected: `No syntax errors detected`

---

### Task 3: Create Preset_Registry

**Files:**
- Create: `optix-core/includes/registry/class-preset-registry.php`

**Interfaces:**
- Produces: `save(string $name, array $data): bool`, `load(string $name): ?array`, `delete(string $name): bool`, `list(): array`

**Step 1:** Create `class-preset-registry.php`

```php
<?php
declare(strict_types=1);

namespace OptixCore\Registry;

defined('ABSPATH') || exit;

class Preset_Registry extends Base_Registry {

    protected function define_entries(): array {
        return [
            'default' => [
                'label' => __('Default Settings', 'optix-core'),
                'description' => __('Factory default settings snapshot', 'optix-core'),
                'built_in' => true,
            ],
            'dark' => [
                'label' => __('Dark Mode', 'optix-core'),
                'description' => __('Dark color scheme preset', 'optix-core'),
                'built_in' => true,
            ],
            'light' => [
                'label' => __('Light & Airy', 'optix-core'),
                'description' => __('Light color scheme preset', 'optix-core'),
                'built_in' => true,
            ],
        ];
    }

    public function save(string $name, array $data): bool {
        $key = 'optix_preset_' . sanitize_key($name);
        return update_option($key, $data, false);
    }

    public function load(string $name): ?array {
        $key = 'optix_preset_' . sanitize_key($name);
        $data = get_option($key, null);
        if (null === $data) {
            return null;
        }
        return is_array($data) ? $data : [];
    }

    public function delete(string $name): bool {
        $key = 'optix_preset_' . sanitize_key($name);
        return delete_option($key);
    }

    public function list(): array {
        global $wpdb;
        $results = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT option_name, option_value FROM {$wpdb->options}
                 WHERE option_name LIKE %s",
                'optix_preset_%'
            )
        );
        $presets = [];
        foreach ($results as $row) {
            $name = str_replace('optix_preset_', '', $row->option_name);
            $data = maybe_unserialize($row->option_value);
            $presets[$name] = is_array($data) ? $data : [];
        }
        return $presets;
    }

    public function apply(string $name): bool|\WP_Error {
        $data = $this->load($name);
        if (null === $data || empty($data)) {
            return new \WP_Error(
                'preset_not_found',
                sprintf(__('Preset "%s" not found.', 'optix-core'), $name)
            );
        }

        foreach ($data as $key => $value) {
            $registry = Settings_Registry::get_instance();
            if ($registry->has($key)) {
                $registry->set($key, $value);
            }
        }

        do_action('optix/preset/load/' . $name, $name, $data);
        return true;
    }
}
```

**Step 2:** Verify syntax

Run: `docker exec optix_wordpress php -l /var/www/html/wp-content/plugins/optix-core/includes/registry/class-preset-registry.php`
Expected: `No syntax errors detected`

---

### Task 4: Create Demo_Registry

**Files:**
- Create: `optix-core/includes/registry/class-demo-registry.php`

**Interfaces:**
- Produces: `get_import_steps(string $package_id): ?array`, `list_packages(): array`, `get_package(string $package_id): ?array`

**Step 1:** Create `class-demo-registry.php`

```php
<?php
declare(strict_types=1);

namespace OptixCore\Registry;

defined('ABSPATH') || exit;

class Demo_Registry extends Base_Registry {

    protected function define_entries(): array {
        return [
            'default' => [
                'title' => __('Default Demo', 'optix-core'),
                'description' => __('Full demo content with products, pages, and settings', 'optix-core'),
                'thumbnail' => '',
                'steps' => [
                    ['id' => 'settings', 'label' => __('Import Settings', 'optix-core')],
                    ['id' => 'pages', 'label' => __('Create Pages', 'optix-core')],
                    ['id' => 'products', 'label' => __('Import Products', 'optix-core'), 'optional' => true],
                    ['id' => 'images', 'label' => __('Download Images', 'optix-core'), 'optional' => true],
                    ['id' => 'menus', 'label' => __('Configure Menus', 'optix-core')],
                ],
                'settings' => [
                    'hero_title' => 'Welcome to Our Store',
                    'color_primary' => '#705b53',
                ],
                'pages' => ['Home', 'Shop', 'About', 'Contact', 'Blog'],
                'required_plugins' => ['woocommerce'],
            ],
            'minimal' => [
                'title' => __('Minimal Setup', 'optix-core'),
                'description' => __('Just the essential pages and settings, no products', 'optix-core'),
                'thumbnail' => '',
                'steps' => [
                    ['id' => 'settings', 'label' => __('Import Settings', 'optix-core')],
                    ['id' => 'pages', 'label' => __('Create Pages', 'optix-core')],
                    ['id' => 'menus', 'label' => __('Configure Menus', 'optix-core')],
                ],
                'settings' => [
                    'hero_title' => 'Welcome',
                    'color_primary' => '#705b53',
                ],
                'pages' => ['Home', 'Shop', 'About'],
                'required_plugins' => [],
            ],
        ];
    }

    public function list_packages(): array {
        $packages = [];
        foreach ($this->entries as $key => $entry) {
            $packages[$key] = [
                'title' => $entry['title'],
                'description' => $entry['description'],
                'thumbnail' => $entry['thumbnail'] ?? '',
                'step_count' => count($entry['steps'] ?? []),
                'required_plugins' => $entry['required_plugins'] ?? [],
            ];
        }
        return $packages;
    }

    public function get_package(string $package_id): ?array {
        return $this->entries[$package_id] ?? null;
    }

    public function get_import_steps(string $package_id): ?array {
        $entry = $this->entries[$package_id] ?? null;
        if (null === $entry) {
            return null;
        }
        return $entry['steps'] ?? [];
    }

    public function get_settings(string $package_id): array {
        $entry = $this->entries[$package_id] ?? null;
        if (null === $entry) {
            return [];
        }
        return $entry['settings'] ?? [];
    }

    public function get_pages(string $package_id): array {
        $entry = $this->entries[$package_id] ?? null;
        if (null === $entry) {
            return [];
        }
        return $entry['pages'] ?? [];
    }

    public function get_required_plugins(string $package_id): array {
        $entry = $this->entries[$package_id] ?? null;
        if (null === $entry) {
            return [];
        }
        return $entry['required_plugins'] ?? [];
    }
}
```

**Step 2:** Verify syntax

Run: `docker exec optix_wordpress php -l /var/www/html/wp-content/plugins/optix-core/includes/registry/class-demo-registry.php`
Expected: `No syntax errors detected`

---

### Task 5: Create Asset_Registry

**Files:**
- Create: `optix-core/includes/registry/class-asset-registry.php`

**Interfaces:**
- Produces: `init()` hooks into `wp_enqueue_scripts`, `add_asset(string $handle, array $args)`, `get_asset(string $handle): ?array`

**Step 1:** Create `class-asset-registry.php`

```php
<?php
declare(strict_types=1);

namespace OptixCore\Registry;

defined('ABSPATH') || exit;

class Asset_Registry extends Base_Registry {

    protected function define_entries(): array {
        return [
            'optix-main' => [
                'type' => 'style',
                'src' => '',
                'deps' => [],
                'version' => OPTIX_CORE_VERSION,
                'media' => 'all',
                'enqueue' => true,
                'description' => __('Main theme stylesheet', 'optix-core'),
            ],
            'optix-woocommerce' => [
                'type' => 'style',
                'src' => '',
                'deps' => ['optix-main'],
                'version' => OPTIX_CORE_VERSION,
                'media' => 'all',
                'enqueue' => 'is_woocommerce',
                'description' => __('WooCommerce-specific styles', 'optix-core'),
            ],
        ];
    }

    public function init(): void {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets'], 10);
    }

    public function add_asset(string $handle, array $args): void {
        $this->entries[$handle] = wp_parse_args($args, [
            'type' => 'style',
            'src' => '',
            'deps' => [],
            'version' => OPTIX_CORE_VERSION,
            'media' => 'all',
            'in_footer' => false,
            'enqueue' => true,
        ]);
    }

    public function get_asset(string $handle): ?array {
        return $this->entries[$handle] ?? null;
    }

    public function remove_asset(string $handle): void {
        unset($this->entries[$handle]);
    }

    public function enqueue_assets(): void {
        foreach ($this->entries as $handle => $asset) {
            $should_enqueue = $asset['enqueue'];
            if (is_callable($should_enqueue)) {
                if (!$should_enqueue()) {
                    continue;
                }
            } elseif (is_string($should_enqueue) && function_exists($should_enqueue)) {
                if (!$should_enqueue()) {
                    continue;
                }
            } elseif (!$should_enqueue) {
                continue;
            }

            if ('script' === $asset['type']) {
                wp_enqueue_script(
                    $handle,
                    $asset['src'],
                    $asset['deps'],
                    $asset['version'],
                    $asset['in_footer'] ?? false
                );
            } else {
                wp_enqueue_style(
                    $handle,
                    $asset['src'],
                    $asset['deps'],
                    $asset['version'],
                    $asset['media']
                );
            }
        }
    }

    public function register_style(string $handle, string $src, array $deps = [], string $version = '', string $media = 'all', $enqueue = true): void {
        $this->add_asset($handle, [
            'type' => 'style',
            'src' => $src,
            'deps' => $deps,
            'version' => $version ?: OPTIX_CORE_VERSION,
            'media' => $media,
            'enqueue' => $enqueue,
        ]);
    }

    public function register_script(string $handle, string $src, array $deps = [], string $version = '', bool $in_footer = false, $enqueue = true): void {
        $this->add_asset($handle, [
            'type' => 'script',
            'src' => $src,
            'deps' => $deps,
            'version' => $version ?: OPTIX_CORE_VERSION,
            'in_footer' => $in_footer,
            'enqueue' => $enqueue,
        ]);
    }
}
```

**Step 2:** Verify syntax

Run: `docker exec optix_wordpress php -l /var/www/html/wp-content/plugins/optix-core/includes/registry/class-asset-registry.php`
Expected: `No syntax errors detected`

---

### Task 6: Wire into Bootstrap

**Files:**
- Modify: `optix-core/optix-core.php` (add 11 lines to `$registry_files`)
- Modify: `optix-core/includes/class-core-plugin.php` (add 11 `use` imports + 11 `register()` calls + Asset_Registry init)

**Step 1:** Add file requires to `optix-core.php`

Edit `$registry_files` array (line 41-47) to include all 11 new files:

```php
$registry_files = [
    'registry/class-base-registry.php',
    'registry/class-settings-registry.php',
    'registry/class-section-registry.php',
    'registry/class-component-registry.php',
    'registry/class-template-registry.php',
    'registry/class-layout-registry.php',
    'registry/class-woocommerce-registry.php',
    'registry/class-typography-registry.php',
    'registry/class-color-registry.php',
    'registry/class-responsive-registry.php',
    'registry/class-animation-registry.php',
    'registry/class-hook-registry.php',
    'registry/class-block-registry.php',
    'registry/class-preset-registry.php',
    'registry/class-demo-registry.php',
    'registry/class-asset-registry.php',
];
```

**Step 2:** Add use imports to `class-core-plugin.php`

Add to the existing `use` block (after line 9):

```php
use OptixCore\Registry\Layout_Registry;
use OptixCore\Registry\WooCommerce_Registry;
use OptixCore\Registry\Typography_Registry;
use OptixCore\Registry\Color_Registry;
use OptixCore\Registry\Responsive_Registry;
use OptixCore\Registry\Animation_Registry;
use OptixCore\Registry\Hook_Registry;
use OptixCore\Registry\Block_Registry;
use OptixCore\Registry\Preset_Registry;
use OptixCore\Registry\Demo_Registry;
use OptixCore\Registry\Asset_Registry;
```

**Step 3:** Add register() calls to `init_registries()`

Add after line 44 `Template_Registry::get_instance()->register();`:

```php
        Layout_Registry::get_instance()->register();
        WooCommerce_Registry::get_instance()->register();
        Typography_Registry::get_instance()->register();
        Color_Registry::get_instance()->register();
        Responsive_Registry::get_instance()->register();
        Animation_Registry::get_instance()->register();
        Hook_Registry::get_instance()->register();
        Block_Registry::get_instance()->register();
        Preset_Registry::get_instance()->register();
        Demo_Registry::get_instance()->register();
        Asset_Registry::get_instance()->register();
```

**Step 4:** Add Asset_Registry::init() call in the `init()` method

Add after `Theme_API::get_instance()->init();` (line 36):

```php
        Asset_Registry::get_instance()->init();
```

**Step 5:** Verify syntax of both modified files

Run: `docker exec optix_wordpress php -l /var/www/html/wp-content/plugins/optix-core/optix-core.php`
Run: `docker exec optix_wordpress php -l /var/www/html/wp-content/plugins/optix-core/includes/class-core-plugin.php`
Expected: `No syntax errors detected` for both.

---

### Task 7: Full Integration Verification

**Step 1:** Check Docker containers are running

Run: `docker ps`
Expected: Both `optix_wordpress` and `optix_db` containers showing STATUS `Up`.

**Step 2:** Activate plugin (if needed) and check for PHP errors

Run: `docker exec optix_wordpress wp plugin activate optix-core --allow-root`
Expected: `Success: Plugin 'optix-core' activated.`

Run: `docker exec optix_wordpress wp eval 'var_dump(defined("OPTIX_CORE_VERSION"));' --allow-root`
Expected: `bool(true)`

**Step 3:** Verify all registries are registered

```php
docker exec optix_wordpress wp eval '
$classes = [
    "OptixCore\Registry\Layout_Registry",
    "OptixCore\Registry\WooCommerce_Registry",
    "OptixCore\Registry\Typography_Registry",
    "OptixCore\Registry\Color_Registry",
    "OptixCore\Registry\Responsive_Registry",
    "OptixCore\Registry\Animation_Registry",
    "OptixCore\Registry\Hook_Registry",
    "OptixCore\Registry\Block_Registry",
    "OptixCore\Registry\Preset_Registry",
    "OptixCore\Registry\Demo_Registry",
    "OptixCore\Registry\Asset_Registry",
];
foreach ($classes as $c) {
    $loaded = class_exists($c);
    echo ($loaded ? "✓" : "✗") . " " . $c . ($loaded ? " (entries: " . $c::get_instance()->count() . ")" : "") . "\n";
}
' --allow-root
```

Expected: All 11 classes show `✓` with correct entry counts.

**Step 4:** Check Asset_Registry init fires

Run: `docker exec optix_wordpress wp eval 'var_dump(has_action("wp_enqueue_scripts", "OptixCore\Registry\Asset_Registry->enqueue_assets()"));' --allow-root`
Expected: `int(10)` (priority)

**Step 5:** Frontend smoke test — curl the home page

Run: `docker exec optix_wordpress curl -s -o /dev/null -w "%{http_code}" http://localhost/`
Expected: `200`

**Step 6:** Check no PHP errors in log

Run: `docker exec optix_wordpress wp eval 'var_dump(error_get_last());' --allow-root`
Expected: `NULL` (or an error not related to optix-core code)

---

## Self-Review

**1. Spec coverage:**
- Section 6.2 (Layout): ✓ Task 1
- Section 6.3 (WooCommerce): ✓ Task 1
- Section 6.4 (Typography): ✓ Task 1
- Section 6.5 (Color): ✓ Task 1
- Section 6.6 (Responsive): ✓ Task 1
- Section 6.7 (Animation): ✓ Task 1
- Section 6.8 (Hook): ✓ Task 1
- Section 6.9 (Block): ✓ Task 2
- Section 6.10 (Preset): ✓ Task 3
- Section 6.11 (Demo): ✓ Task 4
- Section 6.12 (Asset): ✓ Task 5
- Section 17 (Asset enqueuing): ✓ Task 5
- Section 10 (Admin tabs): Implicit — data structures feed future settings page

**2. Placeholder scan:** No TBD, TODO, or placeholder patterns found.

**3. Type consistency:** All registries return same entry format. `get_component_id()` returns `?string`. `save()` returns `bool`. `load()` returns `?array`. `init()` is void. All consistent.
