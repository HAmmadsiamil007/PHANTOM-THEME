## Phase 2B Spec — Bridge Template Wiring

### Status: Spec approved, plan pending

### Scope
4 sections:
1. **Compatibility Shim** — Add `optix_get_option()` global alias for `optix_option()` in plugin
2. **Bootstrap Wiring** — Call `Theme_API::get_instance()->init()` in `class-core-plugin.php::init_registries()`
3. **Template Migration (3 files)** — `header.php`, `footer.php`, `front-page.php` — replace `optix_get_option(` → `optix_option(`
4. **Integration Tests** — Create `tests/phpunit/` with bootstrap + 10+ test methods

### Files to modify/create
- Modify: `optix-core/includes/class-theme-api.php` — add optix_get_option shim
- Modify: `optix-core/includes/class-core-plugin.php` — wire Theme_API::init()
- Modify: `optix-main/optix-main/profiles/default/header.php` — migrate to optix_option()
- Modify: `optix-main/optix-main/profiles/default/footer.php` — migrate to optix_option()
- Modify: `optix-main/optix-main/profiles/default/front-page.php` — migrate to optix_option()
- Create: `optix-core/tests/phpunit/bootstrap.php`
- Create: `optix-core/tests/phpunit/api/class-theme-api-test.php`
- Create: `optix-core/phpunit.xml.dist`

### Template namespace behavior
Templates use `namespace Optix`. Unqualified `optix_option()` falls back to global `\optix_option()` (plugin-defined) because PHP's namespace resolution looks in current ns then global ns for functions.

### Key constraints
- `function_exists()` guards on all global function definitions
- Template files only change function names — no structural changes
- Verify site renders after each template edit
- Target 100/100 quality via loop-engineering
