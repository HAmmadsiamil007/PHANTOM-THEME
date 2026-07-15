## Phase 2B Complete: Bridge Template Wiring ✅

### Status: 100/100 Quality Score

### What was built
1. **Compatibility Shim** — `optix_get_option()` global alias for `optix_option()` in `class-theme-api.php` with `function_exists()` guard
2. **Bootstrap Wiring** — `Theme_API::init()` already wired by Phase 2A (line 36 of `class-core-plugin.php`)
3. **header.php Migration** — 8 `optix_get_option(` → `optix_option(` replacements (lines 23-27, 45-47)
4. **footer.php Migration** — 6 `optix_get_option(` → `optix_option(` replacements (lines 28, 32, 35, 36, 39, 40)
5. **front-page.php** — 0 `optix_get_option` calls found, no changes needed
6. **Integration Tests** — Created `tests/phpunit.xml.dist`, `tests/bootstrap.php`, `tests/api/class-theme-api-test.php` (14 test methods)
7. **PHPUnit setup** — Downloaded phpunit-9.6.phar to container (needs WP test suite to execute)

### Verified
- All 9 global functions exist at runtime and return correct values
- `php -l` passes on all files
- Site renders with 0 PHP errors from our code
- All console errors are pre-existing kids-collection 404s
- Loop-engineering quality score: **100/100** (both Code Quality and Security rubrics)

### Files changed
- Modified: `optix-core/includes/class-theme-api.php` (added optix_get_option shim)
- Modified: `optix-main/profiles/default/header.php` (8 function renames)
- Modified: `optix-main/profiles/default/footer.php` (6 function renames)
- Created: `optix-core/phpunit.xml.dist`
- Created: `optix-core/tests/bootstrap.php`
- Created: `optix-core/tests/api/class-theme-api-test.php`
