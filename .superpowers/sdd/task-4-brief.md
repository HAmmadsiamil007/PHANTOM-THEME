# Task 4: Demo_Registry

**Scope:** Create `class-demo-registry.php` extending `Base_Registry` with 2 demo packages + 6 getter methods.

**File to create:**
- `optix-core/includes/registry/class-demo-registry.php`

**define_entries() returns 2 packages:**

Package `default`:
```
title: 'Default Demo'
description: 'Full demo content with products, pages, and settings'
thumbnail: ''
steps: [
  ['id' => 'settings', 'label' => 'Import Settings'],
  ['id' => 'pages', 'label' => 'Create Pages'],
  ['id' => 'products', 'label' => 'Import Products', 'optional' => true],
  ['id' => 'images', 'label' => 'Download Images', 'optional' => true],
  ['id' => 'menus', 'label' => 'Configure Menus'],
]
settings: ['hero_title' => 'Welcome to Our Store', 'color_primary' => '#705b53']
pages: ['Home', 'Shop', 'About', 'Contact', 'Blog']
required_plugins: ['woocommerce']
```

Package `minimal`:
```
title: 'Minimal Setup'
description: 'Just the essential pages and settings, no products'
thumbnail: ''
steps: [
  ['id' => 'settings', 'label' => 'Import Settings'],
  ['id' => 'pages', 'label' => 'Create Pages'],
  ['id' => 'menus', 'label' => 'Configure Menus'],
]
settings: ['hero_title' => 'Welcome', 'color_primary' => '#705b53']
pages: ['Home', 'Shop', 'About']
required_plugins: []
```

**Extra methods:**
```php
public function list_packages(): array     // Returns [package_id => ['title','description','thumbnail','step_count','required_plugins']]
public function get_package(string $package_id): ?array         // Returns full entry or null
public function get_import_steps(string $package_id): ?array    // Returns steps or null
public function get_settings(string $package_id): array         // Returns settings or []
public function get_pages(string $package_id): array            // Returns pages or []
public function get_required_plugins(string $package_id): array // Returns required_plugins or []
```

All labels/descriptions must use `__('...', 'optix-core')` for translation.

**Verification:** Write file, run `php -l`, report.

**Report:** `C:\Users\hamma\Downloads\wordpress\.superpowers\sdd\task-4-report.md`
