# Task 3: Preset_Registry

**Scope:** Create `class-preset-registry.php` extending `Base_Registry` with 3 built-in presets + CRUD methods.

**File to create:**
- `optix-core/includes/registry/class-preset-registry.php`

**define_entries() returns 3 built-in presets:**
- `default` — label: 'Default Settings', description: 'Factory default settings snapshot', built_in: true
- `dark` — label: 'Dark Mode', description: 'Dark color scheme preset', built_in: true
- `light` — label: 'Light & Airy', description: 'Light color scheme preset', built_in: true

**Extra methods:**
```php
public function save(string $name, array $data): bool
    // Stores under wp_option 'optix_preset_' . sanitize_key($name)

public function load(string $name): ?array
    // Retrieves from 'optix_preset_' . sanitize_key($name)
    // Returns null if not found, array otherwise

public function delete(string $name): bool
    // Deletes 'optix_preset_' . sanitize_key($name)

public function list(): array
    // SELECT all options LIKE 'optix_preset_%' via $wpdb
    // Returns [name => data] pairs

public function apply(string $name): bool|\WP_Error
    // Loads preset, iterates entries, calls Settings_Registry::get_instance()->set($key, $value) for each
    // Returns WP_Error if preset not found
    // Fires do_action('optix/preset/load/' . $name, $name, $data) after applying
```

**Verification:** Write file, run `php -l`, report.

**Report:** `C:\Users\hamma\Downloads\wordpress\.superpowers\sdd\task-3-report.md`
