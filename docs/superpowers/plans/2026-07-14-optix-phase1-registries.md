# Phase 1: Registry Layer Implementation Plan

> **For agentic workers:** Tasks execute sequentially. Each task ends with a verification step.
> **Goal:** Create the 15-registry architecture's foundation (Base_Registry + 4 core registries)
> **Architecture:** Plugin owns registries. Theme is thin shell. All additive changes — zero risk of breaking existing rendering.

## Global Constraints
- Singleton pattern: `get_instance()` on every class
- Namespace: `OptixCore\Registry`
- Option prefix: `optix_{key}`
- All registry classes extend `Base_Registry`
- Existing code must NOT be modified in any way that changes its behavior
- Verify site renders after each task

---

### Task 1: Create registry directory + Base_Registry

**Files:**
- Create: `optix-core\includes\registry\class-base-registry.php`

**What it does:** Shared base class for all 15 registries. Handles:
- Singleton pattern (get_instance)
- Entry registration lifecycle (register/define_entries)
- Value resolution (get/has/set/bulk_get)
- Defaults caching (static)
- Validation and sanitization delegation
- Cache flushing

- [ ] **Step 1: Create directory and Base_Registry class**
  - Verify `optix-core\includes\registry\` doesn't exist yet
  - Create directory
  - Write class with full implementation

- [ ] **Step 2: Verify PHP syntax**
  Run: `php -l includes/registry/class-base-registry.php` in optix-core dir

---

### Task 2: Create Settings_Registry with all defaults

**Files:**
- Create: `optix-core\includes\registry\class-settings-registry.php`
- Reference: `optix-core\includes\class-options-manager.php` (487 existing defaults)
- Reference: `optix-main\optix-main\inc\defaults.php` (567 lines, duplicate defaults)

**What it does:** Defines all ~471 settings across 42 sections. Typed getters (get_string, get_int, get_bool, get_image, get_color, get_array).

- [ ] **Step 1: Create Settings_Registry class**
  - Extends Base_Registry
  - define_entries() returns ALL settings mapped from existing 487 defaults
  - Organizes into 42 sections
  - Adds typed getter methods

- [ ] **Step 2: Verify PHP syntax**
  Run: `php -l includes/registry/class-settings-registry.php`

---

### Task 3: Update class-core-plugin.php to initialize registries

**Files:**
- Modify: `optix-core\includes\class-core-plugin.php`

**What it does:** Registers registry classes into the plugin initialization flow. Registers before existing engine classes so they're available when those initialize.

- [ ] **Step 1: Add registry path loading and initialization**
  - Add require_once for registry files
  - Add register() calls in init() method
  - Keep existing init() calls in same order (append after)

- [ ] **Step 2: Verify syntax**
  Run: `php -l includes/class-core-plugin.php`

---

### Task 4: Create Section_Registry

**Files:**
- Create: `optix-core\includes\registry\class-section-registry.php`

**What it does:** Manages section manifests. Each section declares required fields, dependencies, category, template support. Includes render() method that loads the appropriate template file.

- [ ] **Step 1: Create Section_Registry**
  - Extends Base_Registry
  - define_entries() registers all sections (hero, product-grid, features, testimonials, newsletter, footer)
  - add render() method that resolves template via Profile_Router cascade

- [ ] **Step 2: Verify syntax**
  Run: `php -l includes/registry/class-section-registry.php`

---

### Task 5: Create Component_Registry

**Files:**
- Create: `optix-core\includes\registry\class-component-registry.php`

**What it does:** Manages component definitions with settings, dependencies, asset requirements. Render method with lookup cascade (profile → default → plugin).

- [ ] **Step 1: Create Component_Registry**
  - Extends Base_Registry
  - define_entries() registers product-card, button, pagination, breadcrumb
  - Implements render() with cascade lookup

- [ ] **Step 2: Verify syntax**
  Run: `php -l includes/registry/class-component-registry.php`

---

### Task 6: Create Template_Registry + update Profile_Router

**Files:**
- Create: `optix-core\includes\registry\class-template-registry.php`
- Modify: `optix-core\includes\class-profile-router.php` (surgical — integrate Template_Registry)

**What it does:** Template_Registry stores template → file path mappings. Profile_Router uses it as an additional lookup layer in its cascade.

- [ ] **Step 1: Create Template_Registry**
  - Extends Base_Registry
  - Template definitions with default, fallback, plugin paths
  - register_mapping() method

- [ ] **Step 2: Update Profile_Router to optionally check Template_Registry**
  - Add Template_Registry check in route_template() BEFORE the file_exists checks
  - Only if class exists (no hard dependency)

- [ ] **Step 3: Verify syntax**
  Run: `php -l includes/registry/class-template-registry.php`
  Run: `php -l includes/class-profile-router.php`

---

### Task 7: Add registry requires to optix-core.php bootstrap

**Files:**
- Modify: `optix-core\optix-core.php`

- [ ] **Step 1: Add registry directory requires**
  - Add glob-based require for all registry files
  - Keep existing includes array

- [ ] **Step 2: Verify syntax**
  Run: `php -l optix-core.php`

---

### Task 8: Site verification

- [ ] **Step 1: Verify site loads**
  - Access http://localhost:8080/
  - Check for HTTP 200

- [ ] **Step 2: Check PHP debug.log for errors**
  - Check `docker exec` or look at debug log for any new errors
  - Verify 0 errors from registry code

- [ ] **Step 3: Verify existing functionality**
  - Check that the front page renders the same as before
  - Verify admin pages load
