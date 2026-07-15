# Task 5: Asset_Registry — Complete

## File Created
- `optix-core/includes/registry/class-asset-registry.php`

## File Modified
- `optix-core/optix-core.php` — added `registry/class-asset-registry.php` to `$registry_files`

## Implementation
- **Namespace:** `OptixCore\Registry`, extends `Base_Registry`
- **`define_entries()`:** Returns 2 default assets — `optix-main` (style, enqueued always) and `optix-woocommerce` (style, depends on `optix-main`, enqueued conditionally via `is_woocommerce` function)
- **`init():**** Calls `register()` then hooks `enqueue_assets` into `wp_enqueue_scripts` at priority 10
- **`add_asset()`:** Adds entry with `wp_parse_args` defaults (`type/style`, `src/''`, `deps/[]`, `version/OPTIX_CORE_VERSION`, `media/'all'`, `in_footer/false`, `enqueue/true`)
- **`get_asset()`:** Returns `?array` for given handle
- **`remove_asset()`:** Unsets the entry
- **`enqueue_assets()`:** Iterates all entries, resolves enqueue condition (callable, string function name, or bool), registers (if not already) and enqueues via `wp_enqueue_style()` or `wp_enqueue_script()`
- **`register_style()` / `register_script()`:** Convenience methods that store an asset entry with typed parameters

## Verification
```
docker exec optix_wordpress php -l /var/www/html/wp-content/plugins/optix-core/includes/registry/class-asset-registry.php
No syntax errors detected.
```

**Status: DONE**
