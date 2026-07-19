# Phantom Core — Frontend Development Guide

## Architecture

The frontend is completely decoupled from WordPress. It consists of:

```
frontend/
├── *.html              # 31 static HTML page templates
├── assets/
│   ├── css/            # 9 CSS files (Bootstrap, theme, vendor)
│   ├── js/             # 24 JS files (phantom-data.js + vendor libs)
│   └── images/         # Static images (logos, products, icons)
```

**No PHP templates. No server-side rendering.** All dynamic data injected client-side via REST API.

---

## How Data Binding Works

### The Core Concept

Your HTML declares what data it needs via `data-phantom` attributes. `phantom-data.js` reads these attributes and injects values from the REST API.

```html
<!-- Text content: sets innerText -->
<span data-phantom="site_title">Loading...</span>

<!-- Image src: sets src attribute -->
<img data-phantom="general_site_logo" src="placeholder.png">

<!-- Link href: sets href attribute -->
<a data-phantom="hero_button_url" href="#">Shop Now</a>

<!-- Background image: sets CSS background-image -->
<div data-phantom-bg="hero_bg_image"></div>
```

### Complete Data Flow

```
1. Browser requests /shop
2. Shell (PHP) serves frontend/shop.html with SEO + CSS vars injected
3. phantom-data.js runs on DOMContentLoaded
4. Fetches /wp-json/phantom/v1/page-data (mega-endpoint)
5. Injects each section in order:
   ┌───────────────────────────────────────────────────┐
   │ injectSettings()  → [data-phantom="key"] elements │
   │ injectBanner()    → hero sections                 │
   │ injectFooter()    → footer sections               │
   │ injectSEO()       → meta tags                     │
   │ injectMenus()     → [data-phantom-menu="name"]    │
   │ injectProducts()  → [data-phantom-products="type"]│
   │ injectPosts()     → [data-phantom-posts="type"]   │
   │ injectCart()      → .shopping-cart-info           │
   │ initWooCommerce() → add-to-cart, quantity, remove │
   │ initCheckout()    → checkout form submission      │
   │ initAuth()        → login/register/password-reset │
   │ initSearch()      → AJAX live search              │
   │ injectCategories()-> #category1                   │
   │ initAnimations()  → 3D tilt, scroll effects       │
   │ hidePreloader()   → remove loading screen         │
   └───────────────────────────────────────────────────┘
6. Swup.js handles subsequent navigation via AJAX
```

### How phantom-data.js Gets the Server Data

The Shell injects a `window.phantomData` object into every page:

```html
<script>
window.phantomData = {
    rest_url: "https://example.com/wp-json/",
    nonce: "abc123...",
    settings: { primary_color: "#ff0000", header_sticky: true, ... },
    current_page: "shop",
    plugin_url: "https://example.com/wp-content/plugins/phantom-core",
    theme_url: "https://example.com/wp-content/plugins/phantom-core/frontend",
    ajax_url: "https://example.com/wp-admin/admin-ajax.php"
};
</script>
```

Then `phantom-data.js` does `fetch(phantomData.rest_url + 'phantom/v1/page-data')` to get ALL page data in one call (cached for 1 hour via transient).

---

## How to Edit the Frontend Without Breaking Anything

### Safe Edits (visual only — zero backend impact)

| What to Edit | How | Backend Impact |
|-------------|-----|---------------|
| CSS styles | Edit `frontend/assets/css/style.css` | None |
| HTML layout | Edit HTML files — keep `data-phantom` attributes | None |
| Images | Replace files in `frontend/assets/images/` | None |
| Colors | Use Customizer (no file editing needed) | None |
| Typography | Use Customizer (no file editing needed) | None |
| Spacing/layout | Use Customizer CSS vars | None |
| Add CSS libraries | Include new `<link>` tags in HTML | None |
| Add JS libraries | Include new `<script>` tags in HTML | None |

### CRITICAL: NEVER Remove These

These are the connection points between HTML templates and the JS data bridge. Removing them breaks the feature:

```
[data-phantom="key"]          — DO NOT REMOVE (settings injection)
[data-phantom-menu="name"]   — DO NOT REMOVE (menu injection)
[data-phantom-products="type"] — DO NOT REMOVE (product grids)
[data-phantom-posts="type"]   — DO NOT REMOVE (blog posts)
[data-phantom-product]        — DO NOT REMOVE (single product page)
[data-phantom-post]           — DO NOT REMOVE (single post page)
[data-phantom-bg="key"]       — DO NOT REMOVE (background images)
data-phantom-alt="key"        — DO NOT REMOVE (image alt text)
.shopping-cart-info           — DO NOT REMOVE (cart display)
#category1                    — DO NOT REMOVE (categories list)
.loader-mask / #preloader     — DO NOT REMOVE (page loader)
#contactpage                  — DO NOT REMOVE (checkout form)
.cart-count                   — DO NOT REMOVE (cart badge)
```

### CSS Class Names Used by phantom-data.js

These class names are hardcoded in JS. If you rename them in HTML, the JS functions won't find their targets:

| Function | Selector | Purpose |
|----------|----------|---------|
| `updateCartCount()` | `.cart-count`, `[data-phantom="cart_count"]` | Badge number |
| `updateCartTotal()` | `.cart-total`, `[data-phantom="cart_total"]` | Total price |
| `renderRelatedProducts()` | `.related-products-grid`, `.related-products-slider` | Related products |
| `showAddToCartNotification()` | `.notification-popup` | Toast message |
| `closeCartDrawer()` | `.cart-drawer`, `.cart-overlay` | Side cart |
| `renderSearchSuggestions()` | `.search-suggestions`, `.search-dropdown` | Live search |
| `mobileMenuToggle()` | `.mobile-menu-toggle`, `.nav-menu` | Hamburger menu |
| `stickyHeader()` | `header`, `.header` | Scroll behavior |
| `addToCartHandler()` | `.add-to-cart-trigger`, `.primary_btn` | Add-to-cart buttons |
| `quantityDecrease()` | `.decrease-button` | Quantity down |
| `quantityIncrease()` | `.increase-button` | Quantity up |
| `removeFromCart()` | `.remove-product` | Remove from cart |

---

## How to Add New Features

### New Setting
1. Add entry in `Settings_Registry::define_entries()` — choose key, type, default, sanitize, section
2. Automatically appears in Customizer + Admin Page + REST API
3. Add `data-phantom="your_key"` to HTML template
4. If CSS var: add to `get_css_var_map()` in BOTH `customizer.php` and `shell.php`
5. If numeric: add to `get_px_keys()` in BOTH files

### New Page Template
1. Create `frontend/your-page.html`
2. Add route in `Shell::$routes` array
3. Add SEO title in `Shell::get_meta_tags()` title map
4. Add `data-phantom` attributes for dynamic content

### New REST Endpoint
1. Add method to `Rest_Controller` class
2. Register in `register_routes()` with `register_rest_route()`
3. Set `permission_callback` — use `__return_true` for public, `manage_options` for admin
4. Sanitize inputs, escape outputs
5. Add frontend consumer in `phantom-data.js` or your own JS file

### New Frontend JS
1. Create `frontend/assets/js/my-feature.js`
2. Reference `window.phantomData` for REST URL, nonce, settings
3. Use `phantomData.rest_url + 'phantom/v1/...'` for API calls
4. Include in HTML template via `<script src="...">`

---

## WooCommerce Frontend Integration

| Feature | Method | Consumed By |
|---------|--------|-------------|
| Add to cart | `wc-ajax=add_to_cart` | `.add-to-cart-trigger`, `.primary_btn` |
| Remove from cart | `wc-ajax=remove_from_cart` | `.remove-product` |
| Update quantity | Store API `update-item` | `.decrease-button`, `.increase-button` |
| Checkout | `wc-ajax=checkout` | `#contactpage` form |
| Cart display | REST `/phantom/v1/cart` | `.shopping-cart-info`, `.cart-count` |
| Product data | REST `/phantom/v1/products` | `[data-phantom-products]` |

**Key CSS Classes for WooCommerce:**
- `.add-to-cart-trigger` — Click handler for adding products to cart
- `.primary_btn` — Alternative add-to-cart (used in product detail)
- `.decrease-button` / `.increase-button` — Quantity stepper
- `.remove-product` — Remove item button
- `#contactpage` — Form ID for checkout submission
- `.coupon-input` / `.apply-coupon-btn` — Coupon code input + button
- `.cart-count` — Cart item count badge (in header)
- `.shopping-cart-info` — Cart dropdown / slide-in panel

---

## Security

### Backend
- All settings sanitized via type-specific callbacks
- Nonce verification on all POST operations
- `manage_options` / `edit_theme_options` capability checks
- URL sanitization via `esc_url_raw`, `wp_unslash`
- CSP headers on all frontend pages

### Frontend
- `escapeHtml(str)` — DOM-based HTML escaping for user-generated content
- `sanitizeUrl(url)` — Only allows `http`, `https`, `mailto`, `tel`, relative paths
- Template content injected via `innerHTML` — trust boundary: comes from own REST API
- No `eval()`, no `document.write()` in any JS file

### Security Headers (injected by Shell)
```
Content-Security-Policy: default-src 'self' ...
X-Content-Type-Options: nosniff
X-Frame-Options: SAMEORIGIN
Referrer-Policy: strict-origin-when-cross-origin
```

---

## Frontend Files Reference

| File | Purpose | When to Edit |
|------|---------|-------------|
| `frontend/*.html` | Page templates | Change layout, add/remove sections |
| `frontend/assets/js/phantom-data.js` | Core data bridge | Change data injection logic |
| `frontend/assets/js/phantom-bridge.js` | Utility helpers | Add shared helper functions |
| `frontend/assets/css/style.css` | Theme CSS | Change visual styling |
| `frontend/assets/css/responsive.css` | Responsive rules | Change breakpoint behavior |
| `frontend/assets/images/` | Static assets | Add/replace images |

### phantom-data.js Function Reference (1,040 lines, 28 functions)

| Function | Lines | Purpose |
|----------|-------|---------|
| `escapeHtml()` | 14 | HTML entity escaping |
| `sanitizeUrl()` | 18 | URL validation |
| `loadSettings()` | 25 | Fetch + cache settings from API |
| `injectSettings()` | 45 | Data-phantom attribute processor |
| `injectBanner()` | 30 | Hero/banner content |
| `injectFooter()` | 25 | Footer content |
| `injectSEO()` | 20 | Meta tag injection |
| `injectMenus()` | 50 | Menu tree builder |
| `injectProducts()` | 80 | Product grid renderer |
| `injectPosts()` | 60 | Blog post grid renderer |
| `injectCart()` | 35 | Cart display |
| `injectCategories()` | 25 | Category list |
| `initWooCommerce()` | 40 | Bind cart/checkout events |
| `initCheckout()` | 45 | Checkout form handler |
| `initAuth()` | 35 | Login/register form handler |
| `initSearch()` | 40 | Live search with suggestions |
| `initAnimations()` | 30 | 3D tilt, scroll reveal |
| `hidePreloader()` | 8 | Remove loading screen |
| `updateCartCount()` | 12 | Cart badge |
| `updateCartTotal()` | 10 | Cart total |
| `renderRelatedProducts()` | 30 | Related products |
| `showAddToCartNotification()` | 15 | Toast notification |
| `closeCartDrawer()` | 10 | Close side cart |
| `mobileMenuToggle()` | 15 | Hamburger menu |
| `stickyHeader()` | 12 | Scroll listener |
| `renderSearchSuggestions()` | 25 | Suggestion dropdown |
---
