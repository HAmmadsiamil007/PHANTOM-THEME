# Task 3: Preset_Registry — Complete

## File Created
`optix-core/includes/registry/class-preset-registry.php`

## Structure
- **Namespace:** `OptixCore\Registry`
- **Extends:** `Base_Registry`
- **Import:** `use OptixCore\Registry\Settings_Registry;`

## define_entries() — 3 built-in presets
| Key | Label | Built-in |
|-----|-------|----------|
| `default` | Default Settings | true |
| `dark` | Dark Mode | true |
| `light` | Light & Airy | true |

## Methods Implemented
| Method | Signature | Storage |
|--------|-----------|---------|
| `save()` | `(string $name, array $data): bool` | `wp_option 'optix_preset_' . sanitize_key($name)` |
| `load()` | `(string $name): ?array` | Returns null if not found |
| `delete()` | `(string $name): bool` | `delete_option()` |
| `list()` | `(): array` | `$wpdb LIKE 'optix_preset_%'`, returns `[name => data]` |
| `apply()` | `(string $name): bool\|WP_Error` | Iterates data → `Settings_Registry::set()`, fires `optix/preset/load/{$name}` action. Returns `WP_Error` if preset not found. |

## Verification
```
docker exec optix_wordpress php -l /var/www/html/wp-content/plugins/optix-core/includes/registry/class-preset-registry.php
No syntax errors detected.
```

**Status:** ✅ DONE — lint passes.
