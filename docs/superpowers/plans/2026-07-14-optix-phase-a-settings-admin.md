# Phase A: Settings Registry 471 Entries + Admin Settings Page

> **For agentic workers:** Use subagent-driven-development to implement task-by-task.

**Goal:** Complete Settings_Registry to 471 entries across 43 sections and build the 14-tab admin settings page with schema-driven field rendering.

**Architecture:** Settings_Registry already has 43 section methods with 303 entries. Need ~168 more entries to reach spec target. Admin page at `admin/class-settings-page.php` currently only has profile selector — needs full tabbed interface.

**Tech Stack:** PHP 8.1, WordPress 6.4+, optix-core plugin

## Global Constraints
- All entries go through `define_entries()` → `array_merge()` pattern
- Each entry has: key, section, type, default, sanitize, label (translatable via `__()`)
- Types: string, bool, int, color, image, select, textarea, code, array
- Sanitize: use WordPress functions (sanitize_text_field, wp_kses_post, etc.) or callables
- Option prefix: `optix_` (handled by Base_Registry)
- All text in `'optix-core'` text domain
- Admin page uses `manage_options` capability
- Nonce verification on form submissions

---

### Task A1: Audit + Fill Settings_Registry to 471

**Files:**
- Modify: `optix-core/includes/registry/class-settings-registry.php`

**Interfaces:**
- Consumes: Existing 43 section methods, spec section 3.4 for target counts
- Produces: Settings_Registry with 471 entries

**Approach:** Read each of the 43 existing section methods. For each section, compare current count to spec target. Add missing entries using existing patterns:
- `announcement_bar`: 0 → 4 entries (enable, text, bg_color, scheduled)
- `navigation`: check current → add missing (menu_style, mobile_breakpoint, mega_menu_enable, sticky)
- `hero`: check current → verify has all 10 entries from spec
- `colors`: check current → verify has all 12 entries from spec (include button_colors, header_colors, footer_colors)
- `typography`: check current → verify all 8 font entries
- `spacing`: check → add container_padding, section_gap
- `responsive`: check → verify 4 breakpoints
- `animations`: check → verify 5 entries
- `performance`: check → verify 5 entries
- `seo`: check → add og_image, twitter_handle
- `accessibility`: check → add all 4 entries
- `custom_code`: check → verify all 4 entries
- `import_export`: check → ensure 3 entries
- Page sections (about, contact, faq, etc.): cross-check against spec's existing key mappings

- [ ] **Step 1: Read and catalog all existing section methods**

```bash
docker exec optix_wordpress php -r '
$r = OptixCore\Registry\Settings_Registry::get_instance();
$all = $r->get_all();
echo "Total: " . count($all) . "\n";
$sections = [];
foreach ($all as $key => $val) {
    $schema = $r->get_schema($key);
    $sec = $schema["section"] ?? "unknown";
    $sections[$sec][] = $key;
}
foreach ($sections as $sec => $keys) {
    echo "$sec: " . count($keys) . " keys\n";
}
' --allow-root
```

This shows current count per section.

- [ ] **Step 2: Create new entries for each section to hit spec targets**

For each section needing more entries, add new keys following existing patterns. Use the spec section 3.4 table for target counts.

- [ ] **Step 3: Add `announcement_bar` section** (missing section entirely)

```php
private function section_announcement_bar(): array {
    return [
        'announcement_bar_enable' => ['section' => 'announcement_bar', 'type' => 'bool', 'default' => false, 'sanitize' => 'rest_sanitize_boolean', 'label' => __('Enable Announcement Bar', 'optix-core')],
        'announcement_bar_text' => ['section' => 'announcement_bar', 'type' => 'string', 'default' => '', 'sanitize' => 'sanitize_text_field', 'label' => __('Announcement Text', 'optix-core')],
        'announcement_bar_bg_color' => ['section' => 'announcement_bar', 'type' => 'color', 'default' => '#000000', 'sanitize' => 'sanitize_hex_color', 'label' => __('Background Color', 'optix-core')],
        'announcement_bar_text_color' => ['section' => 'announcement_bar', 'type' => 'color', 'default' => '#ffffff', 'sanitize' => 'sanitize_hex_color', 'label' => __('Text Color', 'optix-core')],
    ];
}
```

- [ ] **Step 4: Add merged sections spec doesn't list separately** like `about_inner` items (team, testimonials are separate sections in reality)

- [ ] **Step 5: Verify total count ≥ 471**

```bash
docker exec optix_wordpress php -r 'echo "Settings entries: " . count(OptixCore\Registry\Settings_Registry::get_instance()->get_all()) . "\n";' --allow-root
```

- [ ] **Step 6: Lint check**

```bash
docker exec optix_wordpress php -l /var/www/html/wp-content/plugins/optix-core/includes/registry/class-settings-registry.php
```

- [ ] **Step 7: Run Theme_API test to verify no regressions**

```bash
docker exec optix_wordpress php /var/www/html/wp-content/plugins/optix-core/vendor/bin/phpunit --configuration /var/www/html/wp-content/plugins/optix-core/phpunit.xml.dist 2>&1 || echo "Unit tests may need bootstrap setup"
```

---

### Task A2: 14-Tab Admin Settings Page

**Files:**
- Modify: `optix-core/admin/class-settings-page.php`
- Create: `optix-core/admin/partials/tab-general.php` (plus 13 more tab partials)
- Create: `optix-core/admin/css/admin.css`
- Create: `optix-core/admin/js/admin.js`

**Interfaces:**
- Consumes: Settings_Registry sections, schema entries
- Produces: Full 14-tab admin page at Settings → Optix Framework

**Approach:** Transform the existing single-dropdown page into a 14-tab interface. Each tab renders fields from one or more Settings_Registry sections. Fields are generated from schema — no hardcoded HTML for each field.

- [ ] **Step 1: Create tab navigation + routing in render_page()**

```php
private function get_tabs(): array {
    return [
        'general'    => __('General', 'optix-core'),
        'homepage'   => __('Homepage', 'optix-core'),
        'woocommerce'=> __('WooCommerce', 'optix-core'),
        'blog'       => __('Blog', 'optix-core'),
        'pages'      => __('Pages', 'optix-core'),
        'typography' => __('Typography', 'optix-core'),
        'colors'     => __('Colors', 'optix-core'),
        'layout'     => __('Layout', 'optix-core'),
        'effects'    => __('Effects', 'optix-core'),
        'performance'=> __('Performance', 'optix-core'),
        'seo'        => __('SEO & Integrations', 'optix-core'),
        'code'       => __('Code', 'optix-core'),
        'profile'    => __('Profile', 'optix-core'),
        'tools'      => __('Tools', 'optix-core'),
    ];
}
```

- [ ] **Step 2: Create tab → section mapping**

```php
private function get_tab_sections(): array {
    return [
        'general'     => ['branding', 'header', 'topbar', 'announcement_bar', 'navigation', 'footer'],
        'homepage'    => ['hero', 'collections', 'home_sections'],
        'woocommerce' => ['product_cards', 'shop_page', 'product_page', 'woocommerce'],
        'blog'        => ['blog'],
        'pages'       => ['about_page', 'contact_page', 'faq_page', 'coming_soon', 'error_404', 'login_page', 'register_page', 'thank_you', 'portfolio', 'privacy', 'terms'],
        'typography'  => ['typography'],
        'colors'      => ['colors', 'buttons'],
        'layout'      => ['layout', 'spacing', 'responsive', 'forms'],
        'effects'     => ['animations', 'effects_3d'],
        'performance' => ['performance', 'search'],
        'seo'         => ['seo', 'integrations', 'accessibility'],
        'code'        => ['custom_code', 'import_export'],
        'profile'     => [], // handled separately
        'tools'       => [], // system info, cache flush
    ];
}
```

- [ ] **Step 3: Build field rendering helper**

Create private method `render_field($key, $schema)` that:
- Switches on `$schema['type']`
- Renders: text inputs, textareas, selects (with options), checkboxes, color pickers, image upload
- Reads current value: `Settings_Registry::get_instance()->get($key)`
- Wraps in `<tr><th><label>` pattern matching WP admin style

- [ ] **Step 4: Build section rendering helper**

Create `render_section($section_key)` that:
- Gets all entries for that section via Settings_Registry
- Groups by section internally
- Renders each field
- Adds `data-depends-on` attributes for dependency JS

- [ ] **Step 5: Build save logic**

- Hook into `admin_init` with `register_setting` for each tab or use one settings group
- Save each field value via `Settings_Registry::get_instance()->set($key, $value)`
- Nonce verification: `check_admin_referer('optix_save_settings')`

- [ ] **Step 6: Add admin CSS**

Light styling for tab navigation, color picker integration, dependency show/hide

- [ ] **Step 7: Add admin JS**

Tab switching (URL hash or JS toggle), dependency show/hide, color picker init, image uploader

- [ ] **Step 8: Verify page loads in WordPress admin**

Navigate to Settings → Optix Framework, confirm all 14 tabs render with correct fields

- [ ] **Step 9: Verify save works**

Change a setting, save, reload, confirm persisted

- [ ] **Step 10: Lint + security check**

```bash
docker exec optix_wordpress php -l /var/www/html/wp-content/plugins/optix-core/admin/class-settings-page.php
```

Verify: nonce check, capability check, escaping on all output, sanitization on all input
