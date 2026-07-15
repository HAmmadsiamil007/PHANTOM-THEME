# Task 2: Block_Registry

**Scope:** Create `class-block-registry.php` extending `Base_Registry` with 2 entries + 2 lookup methods.

**File to create:**
- `optix-core/includes/registry/class-block-registry.php`

**Contract:**
- Namespace: `OptixCore\Registry`
- Extends `Base_Registry`
- Singleton via inherited `get_instance()`

**define_entries() returns:**
```php
[
    'portfolio-grid' => [
        'component_id' => 'product_card',
        'description'   => __('Portfolio grid ACF block', 'optix-core'),
        'acf_block'     => 'portfolio-grid',
        'fields'        => [],
    ],
    'project-highlight' => [
        'component_id' => 'promo_box',
        'description'   => __('Project highlight ACF block', 'optix-core'),
        'acf_block'     => 'project-highlight',
        'fields'        => [],
    ],
]
```

**Extra methods:**
```php
public function get_component_id(string $block_name): ?string {
    // Iterates entries, returns component_id matching acf_block, or null
}

public function get_block_for_component(string $component_id): ?string {
    // Iterates entries, returns first acf_block matching component_id, or null
}
```

**Verification:**
- Write the file
- Run lint: `docker exec optix_wordpress php -l /var/www/html/wp-content/plugins/optix-core/includes/registry/class-block-registry.php`
- Report results

**Report file:** `C:\Users\hamma\Downloads\wordpress\.superpowers\sdd\task-2-report.md`
