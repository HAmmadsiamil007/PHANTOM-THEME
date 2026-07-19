# Phantom Core — Architecture

## Core Concept

Phantom Core is a **decoupled WordPress framework**. There is NO standard `wp-content/themes/` directory. The plugin IS the theme. WordPress is used only as a backend CMS — its template hierarchy is completely bypassed.

```
                    ┌──────────────────────────────────────────────────┐
                    │              WordPress Backend CMS               │
                    │  Users · Posts · Pages · Media · Comments · Roles│
                    │  Options API · Menus · Widgets · Permalinks      │
                    └──────────────────────┬───────────────────────────┘
                                           │
                    ┌──────────────────────▼───────────────────────────┐
                    │              Phantom Core Plugin                 │
                    │                                                  │
                    │  ┌────────────────┐ ┌────────────────────────┐  │
                    │  │ Settings_Reg   │ │ Rest_Controller        │  │
                    │  │ 555 settings   │ │ 34 routes phantom/v1   │  │
                    │  │ 44 sections    │ │ Settings CRUD + Auth   │  │
                    │  │ Options API    │ │ Products/Cart/Orders   │  │
                    │  └───────┬────────┘ │ Posts/Pages/Menus     │  │
                    │          │          │ Page-data (mega-endpt) │  │
                    │          │          └───────────┬────────────┘  │
                    │          │                      │              │
                    │  ┌───────▼──────────────────────▼──────────┐  │
                    │  │           Shell SPA Router              │  │
                    │  │  template_redirect (priority 0)         │  │
                    │  │  URL → slug → HTML file                 │  │
                    │  │  31 routes · SEO meta · CSS vars        │  │
                    │  │  Security headers · phantomData JS      │  │
                    │  └───────────────────┬──────────────────────┘  │
                    │                      │                         │
                    │  ┌───────────────────▼──────────────────────┐  │
                    │  │    Customizer (14 panels, 49 sections)   │  │
                    │  │    + Custom CSS Engine (8 modules)       │  │
                    │  │    + 13 Custom Controls                  │  │
                    │  │    + Global Color Palette (4 presets)    │  │
                    │  │    + Font System (Google + system + local)│  │
                    │  └──────────────────────────────────────────┘  │
                    └──────────────────────┬──────────────────────────┘
                                           │
                    ┌──────────────────────▼──────────────────────────┐
                    │               Frontend SPA                      │
                    │                                                  │
                    │  31 Static HTML Templates                       │
                    │  ┌──────────────────────────────────────────┐   │
                    │  │ index · shop · product-detail · cart     │   │
                    │  │ checkout · blog · single-blog · about    │   │
                    │  │ contact · faq · team · testimonials      │   │
                    │  │ login · register · coming-soon · 404     │   │
                    │  │ privacy · terms · cookie · thank-you     │   │
                    │  │ my-account · password-reset · search     │   │
                    │  │ services · + 8 layout variants           │   │
                    │  └──────────────────────────────────────────┘   │
                    │                                                  │
                    │  phantom-data.js (REST API consumer)            │
                    │  phantom-bridge.js (helper utilities)           │
                    │  Swup.js (SPA page transitions)                 │
                    │  jQuery (AJAX, DOM manipulation)                │
                    │  9 CSS files (Bootstrap + theme + vendor)       │
                    └──────────────────────────────────────────────────┘
```

---

## How the Three Systems Connect

This is the most important concept to understand:

```
SETTINGS → CSS VARS → FRONTEND (visual theming)
SETTINGS → REST API → phantom-data.js → [data-phantom] (content injection)
SETTINGS → Customizer → Live Preview (visual editing)
```

**Every setting flows through the same pipeline:**
1. Defined in `Settings_Registry::define_entries()` (555 entries)
2. Automatically available in Customizer, Admin Page, and REST API
3. When saved → stored as `wp_option` with key `phantom_{setting_key}`
4. On page load → Shell reads all options → builds CSS vars → serves HTML
5. On page load → phantom-data.js fetches `/page-data` → injects into `[data-phantom]` elements

---

## Core Components

### 1. Settings Registry (`Settings_Registry`)
**File:** `includes/class-settings-registry.php` — 5,555 lines

The master settings repository. 555 settings across 44 sections. Each entry has:

| Field | Type | Purpose |
|-------|------|---------|
| `key` | `string` | Unique ID (e.g., `primary_color`) |
| `type` | `string` | `string|bool|int|float|color|select|image|text|code|repeater|array|number|multiselect` |
| `default` | mixed | Default value |
| `sanitize` | callback | Sanitization function |
| `label` | `string` | Human-readable name |
| `section` | `string` | Group slug (e.g., `colors`, `header`, `typography`) |
| `transport` | `string` | `postMessage` (live preview) or `refresh` |
| `css_property` | `string` | CSS custom property name (e.g., `--primary--color`) |
| `css_selector` | `string` | CSS selector (default `:root`) |
| `dependencies` | `array` | Conditional visibility rules |
| `responsive` | `bool` | Supports desktop/tablet/mobile values |

**Type distribution:** string ~160, bool ~140, int ~95, color ~42, select ~25, text ~18, repeater 14, image 6, code 6, float 3, array 4, number 3, multiselect 1

**Key insight:** Adding a setting entry here = automatically available in Customizer + Admin Page + REST API. This is the single source of truth.

### 2. REST API (`Rest_Controller`)
**File:** `includes/class-rest-controller.php` — 2,286 lines

Namespace `phantom/v1`. **34 routes** (all register_rest_route calls):

| Endpoint | Method | Auth | Purpose |
|----------|--------|------|---------|
| `/settings` | GET/POST | admin | List/update all settings |
| `/settings/{key}` | GET/PUT/DELETE | admin | Single setting CRUD |
| `/schema` | GET | admin | Setting schemas with defaults |
| `/options` | GET | admin | Filtered design options |
| `/export` | POST | admin | Export all settings as JSON |
| `/import` | POST | admin | Import settings from JSON |
| `/cache/flush` | POST | admin | Flush transients |
| `/posts` | GET | public | Blog posts (paginated, filterable) |
| `/posts/{slug}` | GET | public | Single post by slug |
| `/pages/{slug}` | GET | public | Single page by slug |
| `/categories` | GET | public | Product + post categories |
| `/menus/{location}` | GET | public | Menu tree by location |
| `/products` | GET/POST | public/admin | Products |
| `/products/featured` | GET | public | Featured products |
| `/products/{id}` | GET/PUT/DELETE | public/admin | Single product |
| `/cart` | GET | public | Cart contents (from WC session) |
| `/contact` | POST | public | Contact form handler (wp_mail) |
| `/user/orders` | GET | logged-in | Current user's orders |
| `/user/profile` | GET | logged-in | Current user's profile |
| `/auth/login` | POST | public | User login |
| `/auth/register` | POST | public | User registration |
| `/auth/password-reset` | POST | public | Password reset |
| `/auth/logout` | POST | logged-in | User logout |
| `/page-data` | GET | public | **Mega-endpoint** — all data in one call |

### 3. Shell (SPA Router)
**File:** `templates/shell.php` — ~700 lines

The frontend rendering engine. Hooks `template_redirect` at priority 0 to intercept ALL frontend requests.

**Complete request flow:**
```
1. Browser requests /shop
2. WordPress: template_redirect hook fires (priority 0)
3. Shell::handle_request():
   a. Parse URL → slug = "shop"
   b. Bypass check: wp-json? wp-admin? wp-login? static file? → let WP handle
   c. Route table lookup: '/shop' → 'frontend/shop.html'
   d. Read template file from disk
   e. Inject SEO: <title>, <meta name="description">, OG tags, Twitter Card, JSON-LD
   f. Inject phantomData: <script>window.phantomData = { rest_url, settings, ... }</script>
   g. Inject CSS vars: <style id="phantom-customizer-css">:root { --primary--color: #... }</style>
   h. Set security headers: CSP, X-Frame-Options, Referrer-Policy, X-Content-Type-Options
   i. Inject scripts: jQuery, Swup, Bootstrap, phantom-data.js, vendor JS
   j. Output HTML + exit (WordPress never renders a theme)
4. Browser renders shop.html
5. phantom-data.js: DOMContentLoaded → fetches /page-data → injects content
6. Swup.js handles subsequent navigation via AJAX (no full page reload)
```

### 4. Customizer
**File:** `includes/class-customizer.php` — 524 lines

Bridges Settings Registry → WordPress Customizer. 14 panels, 49 sections.

**14 Panels:**
1. Branding — Logo, favicon, site identity
2. Header — Layout, topbar, navigation, announcement bar
3. Hero — Banner, home sections, collections
4. Products — Cards, shop page, product page
5. WooCommerce — Cart, checkout, my account
6. Blog — Archive, single post
7. Footer — Layout, widgets, copyright
8. Typography — Fonts, sizes, weights
9. Colors — Scheme, buttons, forms, spacing
10. Layout — Container, responsive, animations, 3D
11. Search — AJAX, suggestions
12. Performance & SEO — Cache, preload, meta
13. Accessibility — Contrast, focus, font size
14. Advanced — Integrations, custom code, import/export

### 5. Admin Settings Page (`Settings_Page`)
**File:** `admin/class-settings-page.php` — 753 lines

Full CRUD UI at `/wp-admin/themes.php?page=phantom-core-settings`. 15 tabs. All field types: text, textarea, number, checkbox, select, multiselect, color picker, image upload, code editor, repeater fields with sub-fields. Supports dependency (conditional) logic.

### 6. Frontend JavaScript (`phantom-data.js`)
**File:** `frontend/assets/js/phantom-data.js` — 1,040 lines, 28 functions

The bridge between REST API and HTML templates. Runs on every page.

**Injection order:**
```
DOMContentLoaded
  ├── injectSettings()    → [data-phantom="key"] elements
  ├── injectBanner()      → hero/banner sections
  ├── injectFooter()      → footer sections
  ├── injectSEO()         → meta tags
  ├── injectMenus()       → [data-phantom-menu="location"]
  ├── injectProducts()    → [data-phantom-products="type"]
  ├── injectPosts()       → [data-phantom-posts="type"]
  ├── injectCart()        → .shopping-cart-info
  ├── initWooCommerce()   → add-to-cart, quantity, remove, checkout
  ├── initAuth()          → login/register/reset forms
  ├── initSearch()        → live search
  ├── injectCategories()  → #category1
  ├── initAnimations()    → 3D tilt, scroll effects
  └── hidePreloader()     → remove loading screen
```

### 7. CSS Variable Architecture
**File:** Shared between `class-customizer.php` and `templates/shell.php`

65 CSS custom properties as design tokens. Injected as `<style id="phantom-customizer-css">` on every page.

**Naming convention:** Setting keys convert `_` to `--`: `primary_color` → `--primary--color`

**Categories:**
- Header (10): bg, text, padding, height, fullwidth, sticky, transparent, submenu
- Navigation (2): menu font-size, font-weight
- Footer (5): bg, text, padding, fullwidth, heading
- Typography (8): heading/body font, sizes, weights, line-height, letter-spacing, text-case
- Colors (12): primary, secondary, accent, bg, text, heading, link, link-hover, border, sale, header-bg, footer-bg
- Buttons (8): bg, text, hover-bg, hover-text, radius, padding, font-size
- Forms (2): input radius, height
- Spacing (6): section padding, gap, column/row gap
- Layout (5): container, boxed, content, sidebar, columns
- Responsive (4): mobile/tablet/desktop breakpoints
- Announcement (2): bg, text-color

### 8. Custom CSS Engine (8 modules)
**Files:** `includes/custom-css/`

Each module hooks `phantom_dynamic_css` filter at a priority:

| File | Priority | CSS Vars |
|------|----------|----------|
| `colors.php` | 10 | 12 |
| `typography.php` | 20 | 8 |
| `header.php` | 30 | 10 |
| `footer.php` | 40 | 5 |
| `layout.php` | 50 | 7 |
| `buttons.php` | 60 | 8 |
| `product.php` | 80 | 8 |
| `responsive.php` | 100 | 4 |

### 9. Custom Customizer Controls (13 files)
**Files:** `includes/custom-controls/`

| Control | Type |
|---------|------|
| `Control_Base` | Base class |
| `Color_Control` | ast-color |
| `Color_Group_Control` | ast-color-group |
| `Gradient_Control` | ast-gradient |
| `Border_Control` | ast-border |
| `Background_Control` | ast-background |
| `Typography_Control` | ast-typography |
| `Select_Control` | ast-select |
| `Toggle_Control` | ast-toggle |
| `Radio_Image_Control` | ast-radio-image |
| `Responsive_Slider_Control` | ast-responsive-slider |
| `Responsive_Spacing_Control` | ast-responsive-spacing |
| `Font_Families` | Static helper |

---

## Data Flow

### Settings Lifecycle
```
define_entries() in Settings_Registry (555 settings)
        │
        ├──→ Customizer::register() → WP Customizer panels/sections/controls
        ├──→ Settings_Page::init() → Admin tabs/fields CRUD
        ├──→ Rest_Controller → REST API endpoints (34 routes)
        └──→ Shell → Frontend CSS injection (65 CSS vars)

User changes setting (3 ways):
1. Admin page POST → Settings_Registry::set() → update_option('phantom_{key}')
2. Customizer save → WP save → options table
3. REST API PUT/POST → Settings_Registry::set() → update_option('phantom_{key}')

Frontend render (server):
get_option('phantom_{key}') → Shell::inject_customizer_css() → :root{--var:value}

Frontend render (client):
GET /wp-json/phantom/v1/page-data → phantom-data.js → [data-phantom] elements
```

### Plugin Initialization Order
```
phantom-core.php file scope:
  Rest_Controller::init() → rest_api_init hook
  Settings_Page::init() → admin_menu hook
  Engine\Cache::init() → registers wp_enqueue_scripts
  Shell::init() → template_redirect hook (priority 0)
  Phantom_Webfont_Loader::init() → wp_enqueue_scripts hook

plugins_loaded, priority 1:  load_plugin_textdomain()
plugins_loaded, priority 5:  Plugin::init() → Settings_Registry::register()
plugins_loaded, priority 10: Version_Compatibility::init()
plugins_loaded, priority 15: Customizer::init() → customize_register hook

wp_enqueue_scripts, priority 9:  phantom_enqueue_google_fonts()
wp_enqueue_scripts, priority 11: phantom_enqueue_dark_mode()
```

---

## Key Principles

1. **Plugin IS the theme** — No `wp-content/themes/`. The plugin handles everything.
2. **Static HTML SPA** — 31 static HTML files. No PHP templates. Data via REST API.
3. **Three-way settings** — Customizer (visual) + Admin (form) + REST API (programmatic).
4. **CSS Variable architecture** — 65 design tokens. Change one setting → updates everywhere.
5. **Attribute-based data binding** — `[data-phantom="key"]` on HTML drives JS injection.
6. **Decoupled frontend** — 100% replaceable without touching PHP. Swap HTML/CSS/JS freely.
7. **Swup SPA transitions** — Page changes via AJAX, no full reloads.
8. **WooCommerce via Store API** — Modern cart/checkout, not legacy templates.
9. **Security-first** — CSP headers, sanitization, URL validation, capability checks, nonces.
10. **Settings-first design** — Every visual element starts as a setting. Adding a setting = Customizer + Admin + REST API automatically.

## Performance Notes

- **Server:** ~50ms response for Shell (no DB query on cache hit). PHP processes lightweight.
- **Client:** Single `/page-data` call (cached 1hr in transient). All data in one request.
- **CSS:** 65 vars injected inline (<2KB). 8 CSS module files combine dynamically.
- **JS:** phantom-data.js is 1,040 lines. Minified via terser (~30KB).
