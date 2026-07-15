# Optix Core Framework — Developer Documentation

> **Status: COMPLETE ✅ — Never changes after stable**  
> **Version:** 1.0.0 | **WordPress:** 7.0.1 | **PHP:** 8.2.17 | **WooCommerce:** 9.8.1  
> **Last verified:** 2026-07-15 | **Tests:** 165 pass, 0 failures, 790 assertions

---

## Architecture Overview

```
                        ┌──────────────────────────────────────┐
                        │         ANY FRONTEND (Profile)        │
                        │  Kids Store | Shoes | Fashion | etc   │
                        └───────────────┬──────────────────────┘
                                        │
                        ┌───────────────▼──────────────────────┐
                        │       Profile_Router (Template)       │
                        │  profiles/kids-collection/*.php       │
                        └───────────────┬──────────────────────┘
                                        │
          ┌─────────────────────────────┼─────────────────────────────┐
          │                             │                             │
          ▼                             ▼                             ▼
┌─────────────────────┐   ┌─────────────────────────┐   ┌─────────────────────┐
│   Theme_API Bridge   │   │   Settings_Registry      │   │   Asset_Registry    │
│   (template helpers) │   │   (531 settings, 44 sec) │   │   (CSS/JS enqueue)  │
└─────────────────────┘   └─────────────────────────┘   └─────────────────────┘
          │                             │                             │
          └─────────────────────────────┼─────────────────────────────┘
                                        │
          ┌─────────────────────────────▼─────────────────────────────┐
          │                    CORE FRAMEWORK                         │
          │              (Never changes after stable)                 │
          │                                                           │
          │  ┌─────────────────────────────────────────────────────┐  │
          │  │              10 Core Engines + Plugins              │  │
          │  │                                                     │  │
          │  │  WordPress Core        Settings Registry            │  │
          │  │  WooCommerce           Theme Options                │  │
          │  │  Customizer            Import / Export              │  │
          │  │  Typography Engine     Color Engine                 │  │
          │  │  Layout Engine         Header Builder               │  │
          │  │  Footer Builder        Mega Menu                    │  │
          │  │  Product Engine        Blog Engine                  │  │
          │  │  Search Engine         Animation Settings           │  │
          │  │  Responsive Engine     Section Registry             │  │
          │  │  Presets               Performance                  │  │
          │  │  SEO                   Accessibility                │  │
          │  │  Integrations                                      │  │
          │  └─────────────────────────────────────────────────────┘  │
          │                                                           │
          │  ┌─────────────────────────────────────────────────────┐  │
          │  │          15 Registries (Data Layer)                 │  │
          │  │  Base_Registry → Settings | Section | Component     │  │
          │  │  Template | Layout | WooCommerce | Animation        │  │
          │  │  Typography | Color | Responsive | Hook | Preset    │  │
          │  │  Demo | Block | Asset                               │  │
          │  └─────────────────────────────────────────────────────┘  │
          │                                                           │
          │  ┌─────────────────────────────────────────────────────┐  │
          │  │          Infrastructure (12 classes)                │  │
          │  │  Cache | Schema_Migrator | Customizer               │  │
          │  │  Dynamic_CSS_Generator | Head_Manager               │  │
          │  │  Section_Manifest_Validator | Mega_Menu             │  │
          │  │  Theme_API | Profile_Router | Rest_Controller       │  │
          │  │  CLI_Commands | Setup_Wizard                        │  │
          │  └─────────────────────────────────────────────────────┘  │
          └─────────────────────────────────────────────────────────┘
```

---

## 1. WordPress Core ✅

The foundation. WordPress 7.0.1 provides the CMS backbone — posts, pages, users, roles, REST API, database layer.

**File:** N/A (WordPress itself)  
**Verification:** `wp core version` → `7.0.1` | DB: 61833  
**Never change:** Core WP files are never modified.

---

## 2. WooCommerce ✅

E-commerce engine. Currently active with 6 demo products.

**Version:** 9.8.1  
**Verification:** `wp plugin get woocommerce --field=status` → `active`  
**Products:** 6 (Wooden Building Blocks, Space T-Shirt, Unicorn Backpack, Art Set 120, Rainbow Sneakers, Dinosaur Puzzle)  
**Connection to frontend:** Product Engine (engine class) reads WC settings and exposes data to templates via Theme_API.

---

## 3. Settings Registry ✅

The central data store. All settings live here — 531 entries across 44 sections.

**File:** `optix-core/includes/registry/class-settings-registry.php`  
**Pattern:** Singleton + `define_entries()` + typed getters  
**How frontend reads settings:**

```php
// In any template file (profile or theme):
optix_get_option('general_site_title');   // returns string
optix_get_option('colors_primary');        // returns '#705b53'
optix_get_option('header_layout');         // returns array
```

**Settings sections:** General, Header, Top Bar, Footer, Typography, Colors, Home Page, About Page, Blog, Contact, Shop, Product Detail, Cart & Checkout, Other Pages, Animations, Advanced, Import/Export

**Verification:** `wp optix status` → `Settings Registered: 531`

---

## 4. Theme Options ✅

Admin UI for all settings. 19 tabbed sub-pages.

**File:** `optix-core/admin/class-settings-page.php`  
**Location:** WordPress Admin → `Optix Options` sidebar menu  
**Tabs:** 19 tabs matching all settings sections  
**How to access:** `http://localhost:8080/wp-admin/admin.php?page=optix-theme-options`

---

## 5. Customizer ✅

Live preview for visual settings. 25+ sections mapped to Settings_Registry.

**File:** `optix-core/includes/engine/class-customizer.php`  
**Access:** Appearance → Customize → Optix Theme Panel  
**Live preview:** 52 CSS variables update in real-time via customizer.js  
**Transport:** postMessage for CSS vars, refresh for structural changes

---

## 6. Import / Export ✅

Settings backup and restore.

**File:** `optix-core/api/class-rest-controller.php` (routes)  
**REST endpoints:**
- `POST /optix/v1/export` — Export all settings as JSON
- `POST /optix/v1/import` — Import settings from JSON
**Admin UI:** Tools → Optix Import/Export

---

## 7. Typography Engine ✅

Google Fonts + CSS custom properties for typography.

**File:** `optix-core/includes/engine/class-typography-engine.php`  
**Outputs:**
- Google Fonts `<link>` tag in `<head>`
- CSS vars: `--font-heading`, `--font-body`, `--font-size-base`, `--line-height`
- `font-display: swap` for performance

| CSS Variable | Default |
|-------------|---------|
| `--font-heading` | Archivo (700) |
| `--font-body` | Jost (400) |
| `--font-size-base` | 16px |
| `--line-height` | 1.6 |

---

## 8. Color Engine ✅

CSS color variables + WCAG contrast checking.

**File:** `optix-core/includes/engine/class-color-engine.php`  
**Outputs:**
- CSS custom properties: `--color-primary`, `--color-secondary`, etc.
- Dark mode body class
- WCAG AA/AAA contrast ratio calculator

| CSS Variable | Default |
|-------------|---------|
| `--color-primary` | #705b53 |
| `--color-secondary` | #c19a6b |
| `--color-accent` | #d4a373 |
| `--color-text` | #666666 |
| `--color-heading` | #222222 |
| `--color-background` | #ffffff |
| `--color-link` | #705b53 |
| `--color-link-hover` | #c19a6b |
| `--color-sale` | #e74c3c |
| `--button-bg` | #705b53 |
| `--button-text` | #ffffff |

---

## 9. Layout Engine ✅

Container widths + sidebar configuration.

**File:** `optix-core/includes/engine/class-layout-engine.php`  
**Outputs:** CSS custom properties + `add_theme_support('align-wide')`

| CSS Variable | Default |
|-------------|---------|
| `--container-width` | 1200px |
| `--boxed-width` | 1440px |
| `--content-width` | 800px |
| `--sidebar-width` | 380px |
| `--section-padding-y` | 80px |
| `--container-gutter` | 30px |
| `--content-gap` | 30px |

---

## 10. Header Builder ✅

3-row configurable header (topbar/main/bottom).

**File:** `optix-core/includes/engine/class-header-builder.php`  
**Render API:** `Header_Builder::get_instance()->render()`  
**Item types:** logo, navigation, search, cart, social, text  
**Rows:** topbar (promo bar + language/currency), main (logo + nav + icons), bottom (optional navigation)

**In frontend:** Called from `header.php`:
```php
Header_Builder::get_instance()->render();
```

---

## 11. Footer Builder ✅

Configurable multi-column footer.

**File:** `optix-core/includes/engine/class-footer-builder.php`  
**Render API:** `Footer_Builder::get_instance()->render()`  
**Columns:** 1-4 configurable via settings  
**Features:** Widget areas, copyright bar, social links, newsletter

**In frontend:** Called from `footer.php`:
```php
Footer_Builder::get_instance()->render();
```

---

## 12. Mega Menu ✅

Full Walker_Nav_Menu with dropdown support.

**File:** `optix-core/includes/engine/class-mega-menu.php`  
**Features:**
- `aria-haspopup`, `aria-expanded` attributes
- Per-menu-item meta (enabled/columns)
- `.mega-menu` CSS class injection

---

## 13. Product Engine ✅

WooCommerce shop configuration.

**File:** `optix-core/includes/engine/class-product-engine.php`  
**Features (WC-guarded):**
- Shop column count / per-page override
- Grid/list view toolbar
- Quick view data helper
- Product card loop customization

---

## 14. Blog Engine ✅

Blog archive and single post layout.

**File:** `optix-core/includes/engine/class-blog-engine.php`  
**Features:**
- Archive/single layout classes
- Excerpt length control
- Reading time calculator
- Related posts query

---

## 15. Search Engine ✅

Live search AJAX + pre_get_posts filter.

**File:** `optix-core/includes/engine/class-search-engine.php`  
**Endpoints:** AJAX live search (nonce-protected)  
**Filter:** `pre_get_posts` for search query modification  
**Config:** Post type config (posts, products, pages)

---

## 16. Animation Settings ✅

Scroll and hover animations.

**File:** `optix-core/admin/class-settings-page.php` (settings tab)  
**Libraries:** WOW.js + animate.css  
**Effects:** fadeInLeft, fadeInRight, bounceIn, fadeInUp (2s duration)

---

## 17. Responsive Engine ✅

Breakpoint CSS vars + device detection.

**File:** `optix-core/includes/engine/class-responsive-engine.php`  
**Outputs:**

| Breakpoint | CSS Variable |
|------------|-------------|
| xl (1200px) | `--breakpoint-xl` |
| lg (992px) | `--breakpoint-lg` |
| md (768px) | `--breakpoint-md` |
| sm (576px) | `--breakpoint-sm` |

**Body class:** `optix-device-desktop` or `optix-device-mobile`

---

## 18. Section Registry ✅

Template part routing engine.

**File:** `optix-core/includes/registry/class-section-registry.php`  
**Render API:** `Section_Registry::get_instance()->render('hero')`  
**Cascade:** Active profile → Default profile → Plugin templates directory

---

## 19. Presets ✅

Pre-configured design presets.

**File:** `optix-core/includes/registry/class-preset-registry.php`  
**REST:** `GET /optix/v1/presets`, `POST /optix/v1/presets`  
**Admin:** Import/Export page includes Presets section

---

## 20. Performance ✅

Caching + lazy loading + deferred styles.

**Files:**
- `optix-core/includes/engine/class-cache.php` (3-tier cache)
- `optix-main/inc/class-web-vitals.php` (LCP, CLS, font optimization)
- `optix-main/assets/js/performance.js` (deferred style loader)

**Features:**
- Static cache → WP Object Cache → Options API
- `loading="lazy"` on all images
- `font-display: swap` on Google Fonts
- Deferred non-critical CSS

---

## 21. SEO ✅

Head meta tags + Open Graph + JSON-LD schema.

**File:** `optix-core/includes/class-head-manager.php`  
**Outputs via `wp_head`:**
- Meta generator tag
- OG tags (title, description, image)
- JSON-LD `@graph` schema (Organization + WebSite + SearchAction)
- Google Analytics / GTM / FB Pixel (optional)

---

## 22. Accessibility ✅

WCAG-compliant markup.

**File:** `optix-core/includes/engine/class-accessibility-engine.php`  
**Features:**
- 2 skip links (`#content`, `#main`)
- ARIA labels on navigation, social icons, carousel, forms
- Focus management JavaScript
- High contrast body class
- Font size controls

---

## 23. Integrations ✅

REST API, CPT, WP-CLI, ACF, Cookie Consent.

**Files:**
- `optix-core/api/class-rest-controller.php` — 12 REST routes under `optix/v1`
- `optix-core/cli/class-commands.php` — 18 WP-CLI commands under `wp optix`
- `optix-core/includes/acf/class-acf-sync.php` — Settings → ACF sync
- `optix-core/includes/class-head-manager.php` — Cookie consent bar

**REST Routes:**
```
/optix/v1/settings
/optix/v1/settings/{key}
/optix/v1/settings/batch
/optix/v1/schema
/optix/v1/profile
/optix/v1/export
/optix/v1/import
/optix/v1/profiles
/optix/v1/presets
/optix/v1/presets/{id}
/optix/v1/cache/flush
```

**WP-CLI Commands:**
```
wp optix setting get|list|update|delete|export|import
wp optix profile get|set|list
wp optix preset save|load
wp optix cache flush|status
wp optix schema|migrate|status|doctor|demo_import
```

---

## Profile System — How Frontend Connects to Core

### Architecture

```
optix-main/
├── profiles/
│   └── kids-collection/       ← ACTIVE PROFILE
│       ├── header.php          ← Rendered by Header_Builder
│       ├── footer.php          ← Rendered by Footer_Builder
│       ├── functions.php       ← Profile-specific functions
│       ├── style.css
│       ├── assets/
│       │   ├── css/            ← Profile-specific styles
│       │   ├── js/             ← Profile-specific scripts
│       │   └── images/         ← Profile-specific assets
│       └── screenshot.png
├── templates/
│   └── kids-collection/        ← Page templates
│       ├── front-page.php      ← Homepage template
│       ├── page-about.php      ← About page template
│       ├── page-blog.php       ← Blog page template
│       ├── page-shop.php       ← Shop page template
│       ├── single.php          ← Single post template
│       ├── single-product.php  ← Single product template
│       ├── archive.php         ← Archive template
│       ├── 404.php             ← 404 template
│       └── ...
├── template-parts/
│   └── kids-collection/        ← Reusable template parts
│       ├── content-hero.php    ← Hero section
│       ├── content-promo.php   ← Promotion section
│       ├── content-categories.php
│       ├── content-testimonials.php
│       ├── content-blog-grid.php
│       └── ...
└── inc/                        ← Fallback + Web Vitals
    ├── class-fallback-router.php
    ├── fallback-functions.php
    └── class-web-vitals.php
```

### How Theme_API Connects Frontend to Core

Any template file can read settings with these global functions:

```php
// String settings
optix_get_option('general_site_title');        // "Claudia Kids Collection"
optix_get_option('header_sticky');              // "yes" or "no"

// Color settings (return hex)
optix_get_option('colors_primary');             // "#705b53"
optix_get_option('colors_secondary');           // "#c19a6b"

// Integer/boolean settings
optix_get_option('blog_excerpt_length');        // 55
optix_get_option('shop_per_page');              // 12

// Image settings (return URL)
optix_get_option('general_logo');               // "http://..."
optix_get_option('home_hero_image');            // "http://..."

// Array settings
optix_get_option('header_layout');              // array with rows/items
optix_get_option('footer_columns');             // array with column config
```

### Frontend Engine Rendering

```php
// In header.php — renders the full header (topbar + main nav + bottom)
Header_Builder::get_instance()->render();

// In footer.php — renders the full footer
Footer_Builder::get_instance()->render();

// In template parts — renders dynamic sections
Section_Registry::get_instance()->render('hero');
Section_Registry::get_instance()->render('promotion');
Section_Registry::get_instance()->render('testimonials');

// In shop/product templates — renders WC product grid
Product_Engine::get_instance()->init();
```

---

## How to Edit the Frontend Design (Without Breaking Core)

### ✅ SAFE — Edit Profile Templates Only

All profile templates are in `profiles/kids-collection/`. These are **safe to edit**:

| File | What to Edit |
|------|-------------|
| `profiles/kids-collection/header.php` | Header HTML structure (logo, nav wrapping) |
| `profiles/kids-collection/footer.php` | Footer HTML structure |
| `profiles/kids-collection/functions.php` | Profile-specific WP hooks, image sizes, theme support |
| `profiles/kids-collection/assets/css/style.css` | Profile visual styles (colors, spacing, fonts) |
| `profiles/kids-collection/assets/js/theme.js` | Profile-specific JavaScript |
| `profiles/kids-collection/assets/images/` | Replace images (keep same filenames) |

### ✅ SAFE — Edit Theme Template Files

| File | What to Edit |
|------|-------------|
| `templates/kids-collection/*.php` | Page layout, HTML structure |
| `template-parts/kids-collection/*.php` | Section content, markup |

### ✅ SAFE — Use Theme Options Admin

Change colors, fonts, layout settings through:
- **Customizer** (Appearance → Customize) — live preview
- **Optix Options** (sidebar menu) — all settings

### ❌ NEVER EDIT — Core Framework Files

These files are **never to be modified** after stabilization:

```
optix-core/includes/engine/           ← ALL 13 engine files
optix-core/includes/registry/         ← ALL 15 registry files
optix-core/includes/api/              ← REST controller
optix-core/includes/cli/              ← WP-CLI commands
optix-core/includes/acf/              ← ACF sync
optix-core/admin/                     ← Settings page, setup wizard
optix-core/optix-core.php             ← Main plugin bootstrap
optix-main/inc/                       ← Fallback + Web Vitals
```

### How to Replace the Entire Frontend (Create a New Profile)

1. **Copy the profile directory:**
   ```bash
   cp -r profiles/kids-collection profiles/my-new-store
   ```

2. **Edit header.php** — Keep the `Header_Builder::get_instance()->render()` call or replace with custom HTML

3. **Edit footer.php** — Keep the `Footer_Builder::get_instance()->render()` call or replace with custom HTML

4. **Edit template files** in `templates/my-new-store/` — Use `optix_get_option()` calls to read settings

5. **Switch profile:**
   ```bash
   wp optix profile set my-new-store
   ```

6. **Done.** The core framework continues to work unchanged.

### Completely Custom Frontend (No Profile)

If you want a fully custom frontend that ignores profile templates:

1. Create a child theme of optix-main
2. Override `front-page.php`, `header.php`, `footer.php` in the child theme
3. Call `optix_get_option()` for any settings you need
4. The core framework API works the same regardless of frontend

---

## Verification Checklist

Run these commands to verify the Core Framework is healthy:

```bash
# WordPress
wp core version                           # Should be 7.0.1
wp core update-db                         # Should complete silently

# Plugins
wp plugin get optix-core --field=status   # active
wp plugin get woocommerce --field=status  # active

# Settings
wp optix status                           # Shows all key metrics
wp optix setting list --format=table      # 531+ settings

# REST API
curl http://localhost:8080/wp-json/optix/v1

# WP-CLI
wp optix doctor                           # Health check

# PHPUnit
cd wp-content/plugins/optix-core && phpunit

# Site Health
# Navigate to: /wp-admin/site-health.php
# Expected: 1 critical (REST loopback — Docker only) | 4 recommended
```

---

## File Structure Summary

```
optix-core/ (72 PHP files)
├── optix-core.php                         ← Main plugin bootstrap
├── includes/
│   ├── engine/                            ← 13 engine classes
│   │   ├── class-typography-engine.php
│   │   ├── class-color-engine.php
│   │   ├── class-layout-engine.php
│   │   ├── class-responsive-engine.php
│   │   ├── class-header-builder.php
│   │   ├── class-footer-builder.php
│   │   ├── class-blog-engine.php
│   │   ├── class-search-engine.php
│   │   ├── class-product-engine.php
│   │   ├── class-accessibility-engine.php
│   │   ├── class-cache.php
│   │   ├── class-customizer.php
│   │   └── class-schema-migrator.php
│   ├── registry/                          ← 15 registry classes
│   ├── api/                               ← REST controller
│   ├── cli/                               ← WP-CLI commands
│   ├── acf/                               ← ACF sync
│   └── class-*.php                        ← Infrastructure
├── admin/                                 ← Settings page, wizard
├── tests/                                 ← 14 test files
└── templates/                             ← Plugin fallback templates

optix-main/ (167 PHP files)
├── functions.php                          ← Theme bootstrap
├── style.css                              ← Theme metadata
├── profiles/
│   └── kids-collection/                   ← Active profile
│       ├── header.php                     ← Frontend header
│       ├── footer.php                     ← Frontend footer
│       ├── functions.php                  ← Profile functions
│       ├── style.css                      ← Profile styles
│       └── assets/                        ← CSS, JS, images
├── templates/
│   └── kids-collection/                   ← Page templates
├── template-parts/
│   └── kids-collection/                   ← Template parts
└── inc/                                   ← Fallback, Web Vitals
```

---

## Final Status

| Component | Status | Tests |
|-----------|--------|-------|
| WordPress Core | ✅ 7.0.1 | `wp core version` |
| WooCommerce | ✅ 9.8.1 Active | 6 products, cart OK |
| Settings Registry | ✅ 531 entries | `wp optix status` |
| Theme Options | ✅ 19 admin tabs | Browser verified |
| Customizer | ✅ 25+ sections | Live preview OK |
| Import / Export | ✅ REST + Admin | Routes verified |
| Typography Engine | ✅ Fonts + CSS vars | Frontend verified |
| Color Engine | ✅ CSS vars + WCAG | Frontend verified |
| Layout Engine | ✅ Container widths | CSS vars verified |
| Header Builder | ✅ 3-row layout | Frontend verified |
| Footer Builder | ✅ 4-column layout | Frontend verified |
| Mega Menu | ✅ Walker nav + ARIA | Frontend verified |
| Product Engine | ✅ WC-guarded | Shop verified |
| Blog Engine | ✅ Posts + excerpt | Blog verified |
| Search Engine | ✅ AJAX + filter | Frontend verified |
| Animation Settings | ✅ WOW.js + animate.css | Frontend verified |
| Responsive Engine | ✅ Breakpoints | CSS vars verified |
| Section Registry | ✅ 11 sections | Frontend verified |
| Presets | ✅ REST routes | Admin verified |
| Performance | ✅ Cache + lazy load | Code verified |
| SEO | ✅ OG + JSON-LD | Head verified |
| Accessibility | ✅ Skip links + ARIA | Frontend verified |
| Integrations | ✅ REST + CLI + ACF | All verified |
| **PHPUnit** | **165 tests / 790 assertions** | **0 failures** |

**Core Framework: ✅ COMPLETE — NEVER CHANGES AFTER STABLE**

> *Generated: 2026-07-15 | Optix Framework v3.1.0 | Tests verified by Playwright + WP-CLI + PHPUnit*
