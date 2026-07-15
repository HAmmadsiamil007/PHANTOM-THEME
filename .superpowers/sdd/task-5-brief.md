# Task 5: Asset_Registry

**Scope:** Create `class-asset-registry.php` extending `Base_Registry` with 2 default entries + asset registration + init() enqueue hook.

**File to create:**
- `optix-core/includes/registry/class-asset-registry.php`

**define_entries()** returns 2 default assets:

```php
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
```

**Extra methods:**
```php
public function init(): void
    // add_action('wp_enqueue_scripts', [$this, 'enqueue_assets'], 10);

public function add_asset(string $handle, array $args): void
    // wp_parse_args with defaults: type/style, src/'', deps/[], version/OPTIX_CORE_VERSION, media/'all', in_footer/false, enqueue/true

public function get_asset(string $handle): ?array

public function remove_asset(string $handle): void

public function enqueue_assets(): void
    // Iterates entries, checks enqueue condition (callable, string function name, or bool)
    // Calls wp_enqueue_style() or wp_enqueue_script() accordingly

public function register_style(string $handle, string $src, array $deps = [], string $version = '', string $media = 'all', $enqueue = true): void

public function register_script(string $handle, string $src, array $deps = [], string $version = '', bool $in_footer = false, $enqueue = true): void
```

Note: `OPTIX_CORE_VERSION` is already defined in `optix-core.php` — no need to check.

**Verification:** Write file, run `php -l`, report.

**Report:** `C:\Users\hamma\Downloads\wordpress\.superpowers\sdd\task-5-report.md`
