# Task 1: Create 7 Simple Data Registries

**Scope:** Create 7 registry files, each extending `Base_Registry` with `define_entries()` only.

**Files to create:**
1. `optix-core/includes/registry/class-layout-registry.php`
2. `optix-core/includes/registry/class-woocommerce-registry.php`
3. `optix-core/includes/registry/class-typography-registry.php`
4. `optix-core/includes/registry/class-color-registry.php`
5. `optix-core/includes/registry/class-responsive-registry.php`
6. `optix-core/includes/registry/class-animation-registry.php`
7. `optix-core/includes/registry/class-hook-registry.php`

**Contracts:**
- Each file is a subclass of `OptixCore\Registry\Base_Registry`
- Namespace: `OptixCore\Registry`
- Singleton via `get_instance()` (inherited from Base_Registry)
- No extra methods beyond `define_entries()`

**Global constraints (copy verbatim):**
- Namespace: `OptixCore\Registry`
- All files: `declare(strict_types=1);` + `defined('ABSPATH') || exit;`
- Singleton: `get_instance()` per existing pattern (inherited)
- File name: `class-{name}-registry.php`
- All entries include `'type'`, `'default'`, `'label'` (with `__('...', 'optix-core')`), `'sanitize'`
- No HTML/CSS/animation knowledge in registries
- PHP 8.1+ compatible

## Registry entry details

### Layout_Registry (5 entries)
| Key | Type | Default | Sanitize | Label |
|---|---|---|---|---|
| container_width | int | 1200 | absint | Container Width (px) |
| content_width | int | 800 | absint | Content Width (px) |
| sidebar_width | int | 380 | absint | Sidebar Width (px) |
| gutter | int | 30 | absint | Grid Gutter (px) |
| columns | int | 12 | absint | Grid Columns |

### WooCommerce_Registry (6 entries)
| Key | Type | Default | Options | Sanitize | Label |
|---|---|---|---|---|---|
| shop_layout | select | sidebar-left | [full-width, sidebar-left, sidebar-right] | sanitize_text_field | Shop Layout |
| columns | int | 4 | - | absint | Product Columns |
| pagination | select | numbered | [numbered, load-more, infinite-scroll] | sanitize_text_field | Pagination Style |
| product_card_style | select | default | [default, modern, classic, minimal] | sanitize_text_field | Product Card Style |
| gallery | select | zoom | [zoom, lightbox, slider, none] | sanitize_text_field | Gallery Style |
| related_count | int | 4 | - | absint | Related Products Count |

### Typography_Registry (9 entries)
| Key | Type | Default | Sanitize | Label |
|---|---|---|---|---|
| heading_font | string | Archivo | sanitize_text_field | Heading Font Family |
| body_font | string | Jost | sanitize_text_field | Body Font Family |
| h1_size | int | 48 | absint | H1 Font Size (px) |
| h2_size | int | 36 | absint | H2 Font Size (px) |
| h3_size | int | 24 | absint | H3 Font Size (px) |
| body_size | int | 16 | absint | Body Font Size (px) |
| heading_weight | string | 700 | sanitize_text_field | Heading Font Weight |
| body_weight | string | 400 | sanitize_text_field | Body Font Weight |
| line_height | float | 1.6 | floatval | Base Line Height |

### Color_Registry (8 entries)
| Key | Type | Default | Sanitize | Label |
|---|---|---|---|---|
| primary | color | #705b53 | sanitize_hex_color | Primary Color |
| secondary | color | #c19a6b | sanitize_hex_color | Secondary Color |
| accent | color | #d4a373 | sanitize_hex_color | Accent Color |
| text | color | #666666 | sanitize_hex_color | Text Color |
| heading | color | #222222 | sanitize_hex_color | Heading Color |
| background | color | #ffffff | sanitize_hex_color | Background Color |
| dark_mode_enable | bool | false | absint | Enable Dark Mode |
| dark_mode_bg | color | #1a1a1a | sanitize_hex_color | Dark Mode Background |

### Responsive_Registry (5 entries)
| Key | Type | Default | Sanitize | Label |
|---|---|---|---|---|
| xl | int | 1200 | absint | XL Breakpoint (px) |
| lg | int | 992 | absint | LG Breakpoint (px) |
| md | int | 768 | absint | MD Breakpoint (px) |
| sm | int | 576 | absint | SM Breakpoint (px) |
| xs | int | 0 | absint | XS Breakpoint (px) |

### Animation_Registry (6 entries)
| Key | Type | Default | Options | Sanitize | Label |
|---|---|---|---|---|---|
| enable | bool | true | - | absint | Enable Animations |
| duration | string | 0.3s | - | sanitize_text_field | Default Duration |
| easing | select | ease-out | [linear, ease, ease-in, ease-out, ease-in-out] | sanitize_text_field | Default Easing |
| scroll_animations | bool | true | - | absint | Enable Scroll Animations |
| parallax_enable | bool | true | - | absint | Enable Parallax |
| hover_effects | bool | true | - | absint | Enable Hover Effects |

### Hook_Registry (15 entries)
Documentation-only. Each entry has: `'type' => 'action'|'filter'`, `'description'` (translated), `'params' => []`.

| Key | Type | Description |
|---|---|---|
| optix_core/init | action | Fired after all registries and engines are initialized |
| optix/settings/get/{key} | filter | Filter the value of any setting before returning |
| optix/front_page/sections | filter | Modify the ordered list of sections rendered on front-page |
| optix/section/{id}/before | action | Fired before a section is rendered |
| optix/section/{id}/after | action | Fired after a section is rendered |
| optix/component/{id}/settings | filter | Filter component settings before rendering |
| optix/component/{id}/before | action | Fired before a component is rendered |
| optix/component/{id}/after | action | Fired after a component is rendered |
| optix/dynamic_css | filter | Filter the generated dynamic CSS before output |
| optix/dynamic_css/selector/{key} | filter | Filter CSS selector for a specific setting |
| optix/profile/switch | action | Fired when the active profile is switched |
| optix/preset/save/{name} | action | Fired after a preset is saved |
| optix/preset/load/{name} | action | Fired after a preset is loaded |
| optix/cache/flush | action | Fired when all caches are flushed |
| optix/rest/response/{endpoint} | filter | Filter REST API response data |

## Reference file

Pattern: `C:\Users\hamma\Downloads\wordpress\optix-core\includes\registry\class-template-registry.php` — extends Base_Registry, same structure.

## Verification
- Write all 7 files
- Run `docker exec optix_wordpress php -l /var/www/html/wp-content/plugins/optix-core/includes/registry/class-{name}-registry.php` for each
- Report the results

## Report
Write results to `C:\Users\hamma\Downloads\wordpress\.superpowers\sdd\task-1-report.md` with:
- List of files created
- PHP lint results for each
- Any issues encountered
