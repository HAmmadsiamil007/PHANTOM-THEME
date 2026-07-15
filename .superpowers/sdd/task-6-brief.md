# Task 6: Bootstrap Wiring

**Scope:** Modify 2 files to wire all 11 new registries into the plugin bootstrap.

**Files to modify:**
1. `optix-core/optix-core.php` — add 10 file requires to `$registry_files`
2. `optix-core/includes/class-core-plugin.php` — add 11 use imports + 11 register() calls + 1 init() call

## Current state of optix-core.php line 41-48:

```php
$registry_files = [
    'registry/class-base-registry.php',
    'registry/class-settings-registry.php',
    'registry/class-section-registry.php',
    'registry/class-component-registry.php',
    'registry/class-asset-registry.php',
    'registry/class-template-registry.php',
];
```

Note: `class-asset-registry.php` is ALREADY in the array. Do NOT add it again.

## Required change to optix-core.php:

Add these 10 entries to `$registry_files` (alphabetically ordered, after class-component-registry.php, before class-settings-registry.php):

```
    'registry/class-animation-registry.php',
    'registry/class-block-registry.php',
    'registry/class-color-registry.php',
    'registry/class-demo-registry.php',
    'registry/class-hook-registry.php',
    'registry/class-layout-registry.php',
    'registry/class-preset-registry.php',
    'registry/class-responsive-registry.php',
    'registry/class-typography-registry.php',
    'registry/class-woocommerce-registry.php',
```

Final array should be:
1. base-registry
2. settings-registry
3. section-registry
4. component-registry
5. animation-registry (NEW)
6. block-registry (NEW)
7. color-registry (NEW)
8. demo-registry (NEW)
9. hook-registry (NEW)
10. layout-registry (NEW)
11. preset-registry (NEW)
12. responsive-registry (NEW)
13. typography-registry (NEW)
14. woocommerce-registry (NEW)
15. asset-registry (existing)
16. template-registry (existing)

## Current state of class-core-plugin.php:

```php
use OptixCore\Registry\Settings_Registry;
use OptixCore\Registry\Section_Registry;
use OptixCore\Registry\Component_Registry;
use OptixCore\Registry\Template_Registry;
```

```php
private function init_registries(): void {
    Settings_Registry::get_instance()->register();
    Section_Registry::get_instance()->register();
    Component_Registry::get_instance()->register();
    Template_Registry::get_instance()->register();
}
```

```php
public function init(): void {
    $this->init_registries();
    // ...
    Theme_API::get_instance()->init();
    do_action( 'optix_core/init' );
}
```

## Required changes to class-core-plugin.php:

1. Add these use imports after line 9 (Template_Registry):
```php
use OptixCore\Registry\Animation_Registry;
use OptixCore\Registry\Asset_Registry;
use OptixCore\Registry\Block_Registry;
use OptixCore\Registry\Color_Registry;
use OptixCore\Registry\Demo_Registry;
use OptixCore\Registry\Hook_Registry;
use OptixCore\Registry\Layout_Registry;
use OptixCore\Registry\Preset_Registry;
use OptixCore\Registry\Responsive_Registry;
use OptixCore\Registry\Typography_Registry;
use OptixCore\Registry\WooCommerce_Registry;
```

2. Add these register() calls after line 44 (Template_Registry line):
```php
        Animation_Registry::get_instance()->register();
        Asset_Registry::get_instance()->register();
        Block_Registry::get_instance()->register();
        Color_Registry::get_instance()->register();
        Demo_Registry::get_instance()->register();
        Hook_Registry::get_instance()->register();
        Layout_Registry::get_instance()->register();
        Preset_Registry::get_instance()->register();
        Responsive_Registry::get_instance()->register();
        Typography_Registry::get_instance()->register();
        WooCommerce_Registry::get_instance()->register();
```

3. Add this line after `Theme_API::get_instance()->init();`:
```php
        Asset_Registry::get_instance()->init();
```

## Verification:
- `docker exec optix_wordpress php -l /var/www/html/wp-content/plugins/optix-core/optix-core.php`
- `docker exec optix_wordpress php -l /var/www/html/wp-content/plugins/optix-core/includes/class-core-plugin.php`
- Both should report "No syntax errors detected"

## Report:
Write to `C:\Users\hamma\Downloads\wordpress\.superpowers\sdd\task-6-report.md`
