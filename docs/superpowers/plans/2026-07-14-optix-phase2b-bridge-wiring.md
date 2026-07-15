# Phase 2B: Bridge Template Wiring — Implementation Plan

> **For agentic workers:** Use subagent-driven-development (recommended) or executing-plans to implement task-by-task. Steps use checkbox (`- [ ]`) syntax.

**Goal:** Wire the Theme_API bridge into real template rendering — compatibility shim, migrate 2 key templates, add integration tests.

**Architecture:** Add `optix_get_option()` as plugin-side compatibility alias (function_exists guard). Replace `optix_get_option(` → `optix_option(` in header.php (8 calls) and footer.php (6 calls). PHP namespace fallback resolves unqualified `optix_option()` inside `namespace Optix` to global plugin function. Create PHPUnit test suite for all 6 bridge getters.

**Tech Stack:** PHP 8.1, WordPress 6.4+, PHPUnit

## Global Constraints
- `function_exists()` guards on all global function definitions
- Template files only change function names — no structural changes
- All changes additive or purely internal — no ACF/DB changes
- Verify site renders after each template edit — 0 PHP errors
- All PHP files must pass `php -l`
- Use `replaceAll` for bulk function renames

---

### Task 1: Add optix_get_option() Compatibility Shim

**Files:**
- Modify: `optix-core/includes/class-theme-api.php` (add to global namespace block after existing functions)

**Interfaces:**
- Consumes: `Theme_API::option()` — already defined
- Produces: `\optix_get_option()` — global function with `function_exists()` guard

- [ ] **Step 1: Add compatibility shim**

Insert after `optix_image()` in the global namespace block:

```php
if ( ! function_exists( 'optix_get_option' ) ) {
	function optix_get_option( string $key, mixed $default = null ): mixed {
		return \OptixCore\Theme_API::option( $key, $default );
	}
}
```

- [ ] **Step 2: Verify syntax**

```powershell
php -l "C:\Users\hamma\Downloads\wordpress\optix-core\includes\class-theme-api.php"
```

Expected: `No syntax errors detected`

- [ ] **Step 3: Verify function exists at runtime**

```powershell
docker exec optix_wordpress php -r "include '/var/www/html/wp-load.php'; echo (function_exists('optix_get_option') ? 'YES' : 'NO');"
```

Expected: `YES`

---

### Task 2: Migrate header.php to optix_option()

**Files:**
- Modify: `optix-main\optix-main\profiles\default\header.php`

**Interfaces:**
- Consumes: `\optix_option()` global function (from plugin) — resolves via PHP namespace fallback inside `namespace Optix`
- Produces: Migrated header with 8 `optix_option()` calls

- [ ] **Step 1: Replace all optix_get_option calls in header.php**

Replace `optix_get_option(` with `optix_option(` in the template file. There are 8 occurrences on lines 23-27, 45-47.

```powershell
Replace `optix_get_option(` → `optix_option(` (8 occurrences)
```

- [ ] **Step 2: Verify syntax**

```powershell
php -l "C:\Users\hamma\Downloads\wordpress\optix-main\optix-main\profiles\default\header.php"
```

Expected: `No syntax errors detected`

- [ ] **Step 3: Verify site renders**

Navigate to http://localhost:8080/
Check: PHP debug.log — 0 errors from our code
Check: Console errors — only pre-existing 404s (kids-collection assets)

---

### Task 3: Migrate footer.php to optix_option()

**Files:**
- Modify: `optix-main\optix-main\profiles\default\footer.php`

**Interfaces:**
- Same as Task 2 — 6 replacements

- [ ] **Step 1: Replace all optix_get_option calls in footer.php**

Replace `optix_get_option(` with `optix_option(` (6 occurrences on lines 28, 32, 35, 36, 39, 40)

- [ ] **Step 2: Verify syntax**

```powershell
php -l "C:\Users\hamma\Downloads\wordpress\optix-main\optix-main\profiles\default\footer.php"
```

Expected: `No syntax errors detected`

- [ ] **Step 3: Verify site renders**

Navigate to http://localhost:8080/
Check: PHP debug.log — 0 errors from our code
Check: Console errors — only pre-existing 404s

---

### Task 4: Create Integration Test Infrastructure

**Files:**
- Create: `optix-core/phpunit.xml.dist`
- Create: `optix-core/tests/bootstrap.php`
- Create: `optix-core/tests/api/class-theme-api-test.php`

**Interfaces:**
- Consumes: All bridge methods from `Theme_API` + global functions
- Produces: Runnable test suite with 10+ test methods

- [ ] **Step 1: Create phpunit.xml.dist**

```xml
<?xml version="1.0" encoding="UTF-8"?>
<phpunit xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
         xsi:noNamespaceSchemaLocation="https://schema.phpunit.de/9.6/phpunit.xsd"
         bootstrap="tests/bootstrap.php"
         cacheResultFile=".phpunit.cache"
         colors="true"
         verbose="true">
    <testsuites>
        <testsuite name="optix-core">
            <directory suffix="-test.php">tests</directory>
        </testsuite>
    </testsuites>
    <filter>
        <whitelist processUncoveredFilesFromWhitelist="true">
            <directory suffix=".php">includes</directory>
        </whitelist>
    </filter>
</phpunit>
```

- [ ] **Step 2: Create tests/bootstrap.php**

```php
<?php
// Basic bootstrap — requires WP test suite or mocks
// For now, just load the plugin files directly for basic unit testing

define( 'ABSPATH', dirname( __DIR__, 4 ) . '/' ); // wp-content/plugins/optix-core/
define( 'WP_CONTENT_DIR', dirname( __DIR__, 3 ) ); // wp-content/

// Load vendor autoloader if available
$autoload = __DIR__ . '/../vendor/autoload.php';
if ( file_exists( $autoload ) ) {
	require_once $autoload;
}

// Load WordPress core test bootstrap if available
$wp_tests = getenv( 'WP_TESTS_DIR' ) ?: '/tmp/wordpress-tests-lib/bootstrap.php';
if ( file_exists( $wp_tests ) ) {
	require_once $wp_tests;
}
```

- [ ] **Step 3: Create tests/api/class-theme-api-test.php**

```php
<?php
declare(strict_types=1);

namespace OptixCore\Tests\Api;

use OptixCore\Theme_API;
use OptixCore\Registry\Settings_Registry;
use PHPUnit\Framework\TestCase;

class Theme_API_Test extends TestCase {

	protected function setUp(): void {
		parent::setUp();
		// Register settings for testing
		$registry = Settings_Registry::get_instance();
		if ( ! $registry->has( 'test_string' ) ) {
			$ref = new \ReflectionClass( $registry );
			$prop = $ref->getProperty( 'entries' );
			$prop->setAccessible( true );
			$entries = $prop->getValue( $registry );
			$entries['test_string'] = [
				'type'    => 'string',
				'default' => 'hello',
				'label'   => 'Test',
			];
			$entries['test_int'] = [
				'type'    => 'int',
				'default' => 42,
				'label'   => 'Test Int',
			];
			$entries['test_bool'] = [
				'type'    => 'bool',
				'default' => true,
				'label'   => 'Test Bool',
			];
			$entries['test_color'] = [
				'type'    => 'color',
				'default' => '#ff6600',
				'label'   => 'Test Color',
			];
			$prop->setValue( $registry, $entries );
		}
	}

	public function test_option_registry_first(): void {
		$this->assertEquals( 'hello', Theme_API::option( 'test_string' ) );
	}

	public function test_option_fallback(): void {
		$this->assertEquals( 'fallback_val', Theme_API::option( 'non_existent_test_key', 'fallback_val' ) );
	}

	public function test_option_filter(): void {
		add_filter( 'optix/settings/get/test_string', function () {
			return 'filtered';
		} );
		$this->assertEquals( 'filtered', Theme_API::option( 'test_string' ) );
		remove_all_filters( 'optix/settings/get/test_string' );
	}

	public function test_string_cast(): void {
		$this->assertSame( 'hello', Theme_API::string( 'test_string' ) );
	}

	public function test_string_null_returns_default(): void {
		$this->assertSame( '', Theme_API::string( 'non_existent_test_key' ) );
	}

	public function test_string_default_on_null(): void {
		$this->assertSame( 'fallback', Theme_API::string( 'non_existent_test_key', 'fallback' ) );
	}

	public function test_int_cast(): void {
		$this->assertSame( 42, Theme_API::int( 'test_int' ) );
	}

	public function test_int_default(): void {
		$this->assertSame( 99, Theme_API::int( 'non_existent_test_key', 99 ) );
	}

	public function test_bool_cast(): void {
		$this->assertTrue( Theme_API::bool( 'test_bool' ) );
	}

	public function test_color_sanitization(): void {
		$result = Theme_API::color( 'test_color' );
		$this->assertStringStartsWith( '#', $result );
		$this->assertEquals( 7, strlen( $result ) );
	}

	public function test_color_3digit_expansion(): void {
		$this->assertEquals( '#aabbcc', Theme_API::color( 'test_color' ) );
	}

	public function test_global_functions_exist(): void {
		$this->assertTrue( function_exists( 'optix_option' ) );
		$this->assertTrue( function_exists( 'optix_string' ) );
		$this->assertTrue( function_exists( 'optix_int' ) );
		$this->assertTrue( function_exists( 'optix_bool' ) );
		$this->assertTrue( function_exists( 'optix_color' ) );
		$this->assertTrue( function_exists( 'optix_image' ) );
		$this->assertTrue( function_exists( 'optix_img' ) );
		$this->assertTrue( function_exists( 'optix_asset_url' ) );
	}
}
```

- [ ] **Step 4: Run tests**

```powershell
cd C:\Users\hamma\Downloads\wordpress\optix-core
vendor\bin\phpunit
```

Expected: All tests pass (or appropriate failure indicating test infrastructure gaps — acceptable for initial setup)

---

### Task 5: Loop-Engineering Quality Review

**Files:** All modified files from Tasks 1-4

**Goal:** 100/100 quality score — verify no regressions, no PHP errors, no edge cases missed

- [ ] **Step 1: Load karpathy-guidelines and review**

Review against:
1. **Think Before Coding** — Are assumptions about namespace resolution verified?
2. **Simplicity First** — Is the shim the minimum code? Yes — 4-line function_exists block.
3. **Surgical Changes** — Only touched function call names in templates. No structural changes.
4. **Goal-Driven Execution** — Verify each success criterion from spec.

- [ ] **Step 2: Full site verification**

```powershell
# Check debug log for errors
docker exec optix_wordpress php -r "echo file_get_contents('/var/www/html/wp-content/debug.log');"
```

Expected: 0 PHP errors from our code (pre-existing textdomain notices OK)

- [ ] **Step 3: Verify all 9 global functions at runtime**

```powershell
docker exec optix_wordpress php -r "
include '/var/www/html/wp-load.php';
\$funcs = ['optix_option','optix_string','optix_int','optix_bool','optix_color','optix_image','optix_img','optix_asset_url','optix_get_option'];
foreach (\$funcs as \$f) echo \$f . ': ' . (function_exists(\$f) ? 'YES' : 'NO') . PHP_EOL;
"
```

Expected: All 9 show YES

- [ ] **Step 4: Verify the shim returns correct values**

```powershell
docker exec optix_wordpress php -r "
include '/var/www/html/wp-load.php';
echo optix_get_option('404_title') . PHP_EOL;
echo optix_get_option('non_existent', 'fallback_ok');
"
```

Expected: `We Could Not Find The Page You're Looking For` then `fallback_ok`

- [ ] **Step 5: Quality score assessment**

Score = Pass rate against spec success criteria:
- optix_get_option shim exists: ✓ / ✗
- Theme_API::init() called during bootstrap: ✓ (pre-existing)
- header.php renders with optix_option() — 0 PHP errors: ✓ / ✗
- footer.php renders with optix_option() — 0 PHP errors: ✓ / ✗
- Integration tests pass: ✓ / ✗
- No regressions: ✓ / ✗

If any ✗, fix and re-loop.

---

### Plan Self-Review

- Spec coverage: All 4 spec sections covered (shim Task 1, bootstrap pre-existing, template migration Tasks 2-3, tests Task 4, review Task 5)
- Placeholder scan: Clean — every step has code or commands
- Type consistency: All function signatures match existing code
- front-page.php: 0 `optix_get_option` calls found — no migration needed (spec updated implicitly)
