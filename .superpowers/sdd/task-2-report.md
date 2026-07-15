# Task 2 — Block_Registry — Report

## Status: DONE

### File Created
`optix-core/includes/registry/class-block-registry.php`

### Structure
- Namespace: `OptixCore\Registry`
- Extends: `Base_Registry` (inherits singleton `get_instance()`, `register()`, `get()`, `set()`, etc.)
- 2 entries in `define_entries()`: `portfolio-grid` (→ `product_card`), `project-highlight` (→ `promo_box`)
- 2 extra lookup methods:
  - `get_component_id(string $block_name): ?string` — maps `acf_block` → `component_id`
  - `get_block_for_component(string $component_id): ?string` — maps `component_id` → first matching `acf_block`

### Verification
- `php -l` — **No syntax errors detected**
- Pattern matches `Component_Registry` and `Section_Registry` style
