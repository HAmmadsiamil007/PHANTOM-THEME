# COMPARISON: Impulse v8.2.0 vs Horizon (PHANTOM-merged)

Generated: 2026-06-24

---

## Section Comparison Matrix

### I. Sections Both Have (Different Implementations)

| Impulse Section | Horizon Section | Notes |
|---|---|---|
| `announcement.liquid` | `announcement-bar.liquid` | Announcement bars, different architectures |
| `apps.liquid` | `_blocks.liquid` | App integration blocks |
| `article-template.liquid` | `article-main.liquid` | Article/page layout |
| `blog-template.liquid` / `blog-posts.liquid` | `blog-main.liquid` / `blog-header.liquid` / `blog-slider.liquid` / `featured-blog-posts.liquid` | Blog templates — Horizon is more modular (4 sections vs 2) |
| `collection-header.liquid` | `collection-header.liquid` | Same name |
| `contact-form.liquid` | `contact-form.liquid` | Same name |
| `faq.liquid` | `faq.liquid` | Same name |
| `featured-collection.liquid` | `featured-collections.liquid` / `featured-product.liquid` / `featured-product-information.liquid` | Horizon splits into 3 sections |
| `featured-collections.liquid` | `collection-list.liquid` | Collection listing |
| `featured-product.liquid` | `featured-product.liquid` | Same name |
| `footer.liquid` | `footer.liquid` + `phantom-footer.liquid` | Both have versions |
| `header.liquid` | `header.liquid` + `header-announcements.liquid` | Horizon headers more complex |
| `main-404.liquid` | `main-404.liquid` | Same name |
| `main-cart.liquid` | `main-cart.liquid` + `cart-drawer-section.liquid` + `offcanvas-cart.liquid` | Horizon has dedicated drawer/offcanvas |
| `main-collection.liquid` | `main-collection.liquid` + `collection-products.liquid` + `collection-links.liquid` | Horizon splits |
| `main-page.liquid` | `main-page.liquid` + `page-content.liquid` | Same concept |
| `main-product.liquid` | `product-main.liquid` + `product-information.liquid` | Product templates |
| `main-search.liquid` | `search-main.liquid` + `search-header.liquid` + `search-results.liquid` | Horizon modular search |
| `newsletter.liquid` | `newsletter.liquid` | Same name |
| `predictive-search.liquid` | `predictive-search.liquid` + `predictive-search-empty.liquid` + `phantom-predictive-search.liquid` | Horizon has PHANTOM variant too |
| `product-recommendations.liquid` | `product-recommendations.liquid` | Same name |
| `rich-text.liquid` | `richtext.liquid` | Same concept |
| `slideshow.liquid` | `slideshow.liquid` + `layered-slideshow.liquid` + `hero-carousel.liquid` | Horizon has 3 slideshow variants |
| `testimonials.liquid` | `testimonials.liquid` | Same name |

### II. Impulse-Only Sections (No Horizon Equivalent — Migration Candidates)

| Section | Purpose | Migration Priority |
|---|---|---|
| `advanced-content.liquid` | Custom flexible content builder with row/column layouts | **High** — powerful builder missing in Horizon |
| `age-verification-popup.liquid` | Age gate popup for restricted stores | **Low** — niche use case |
| `background-image-text.liquid` | Full-width image background with overlay text | **Medium** — Horizon has `media-with-text.liquid` but not background variants |
| `background-video-text.liquid` | Full-width video background with overlay text | **Medium** — same as above |
| `collection-return.liquid` | Return policy shown on collection pages | **Low** — utility |
| `countdown.liquid` | Countdown timer section (Horizon has `countdown-timer` as block only) | **Low** — Horizon has block version |
| `featured-video.liquid` | Featured video section | **Medium** — Horizon has `media-with-content` but not dedicated video section |
| `footer-promotions.liquid` | Promotional content in footer | **Low** — Horizon footer already modular |
| `giftcard-header.liquid` | Gift card page header | **Low** — one-off |
| `hero-video.liquid` | Hero section with video background | **Medium** — Horizon `hero.liquid` is image-focused |
| `hotspots.liquid` | Interactive image hotspots (Horizon has `product-hotspots.liquid` — product-specific) | **Low** — Horizon has product version |
| `image-compare.liquid` | Before/after image slider | **Low** — novelty |
| `list-collections-template.liquid` | Collection listing template section | **Low** — Horizon has `main-collection-list.liquid` |
| `logo-list.liquid` | Logo carousel / brand strip | **Medium** — common request |
| `main-page-full-width.liquid` | Full-width page layout | **Low** — edge case |
| `main-product-high-variant.liquid` | High-variant-count product template section | **Low** — niche |
| `map.liquid` | Embedded Google Maps section | **Medium** — common for store contact pages |
| `newsletter-popup.liquid` | Timed/exit-intent newsletter popup | **High** — conversion tool, no Horizon equivalent |
| `password-header.liquid` | Password page header (Horizon has `password.liquid` + `password-main.liquid` + `password-footer.liquid`) | **Low** — Horizon has own |
| `product-full-width.liquid` | Full-width product layout | **Low** — alternative layout |
| `promo-grid.liquid` | Promotional grid with images/text/links | **High** — cornerstone marketing section |
| `recently-viewed.liquid` | Recently viewed products section | **High** — expected UX pattern |
| `scrolling-text.liquid` | Marquee/scrolling ticker text (Horizon has `marquee.liquid`) | **Low** — Horizon has equivalent |
| `store-availability.liquid` | Local store inventory availability (drawer) | **Medium** — multi-location stores need this |
| `text-and-image.liquid` | Rich text + image beside each other | **Medium** — Horizon has `media-with-text.liquid` and `media-with-content.liquid` |
| `text-columns.liquid` | Multi-column text section | **Low** — Horizon has `card-list.liquid` |
| `text-with-icons.liquid` | Text with icon grid | **Medium** — common feature |

### III. Horizon-Only Sections (No Impulse Equivalent — Horizon Strengths)

| Horizon Section | Purpose |
|---|---|
| `hero.liquid` | Complex hero with dual media slots, video/image per slot, mobile-specific media |
| `layered-slideshow.liquid` | Layered tabbed slideshow with panel reveal |
| `hero-carousel.liquid` | Bootstrap carousel hero |
| `marquee.liquid` | Scrolling marquee banner |
| `card-list.liquid` | Flexible card grid |
| `card-slider.liquid` | Card carousel |
| `carousel.liquid` | Generic image/content carousel |
| `cart-count-badge.liquid` | Cart count badge |
| `cart-drawer-section.liquid` | Dedicated cart drawer section |
| `offcanvas-cart.liquid` | Offcanvas cart panel |
| `collection-links.liquid` | Curated collection links |
| `collection-products.liquid` | Product grid within collection |
| `custom-liquid.liquid` | Raw Liquid/HTML injection |
| `divider.liquid` / `separator.liquid` | Visual dividers |
| `featured-products.liquid` | Featured products grid |
| `featured-product-information.liquid` | Product info on featured product |
| `footer-utilities.liquid` | Footer utility links |
| `header-announcements.liquid` | Header announcement section |
| `html.liquid` | Custom HTML section |
| `logo.liquid` | Logo display |
| `main-collection-list.liquid` | Collection list page |
| `navbar.liquid` | Navigation bar section |
| `product-hotspots.liquid` | Product-specific hotspot pins |
| `product-information.liquid` | Flexible product info |
| `product-list.liquid` | Product list layout |
| `quick-order-list.liquid` | B2B quick order |
| `richtext.liquid` | Rich text with buttons |
| `section-rendering-product-card.liquid` | Product card rendering |
| `section.liquid` | Generic section wrapper |
| `blog-slider.liquid` | Blog post carousel |
| `blog-header.liquid` | Blog page header |
| `media-with-content.liquid` / `media-with-text.liquid` | Media+text combos |
| `password.liquid` / `password-main.liquid` / `password-footer.liquid` | Password page suite |
| `search-header.liquid` / `search-results.liquid` | Modular search |
| `page-content.liquid` | Flexible page content |

---

## Template Comparison

| Template | Impulse | Horizon |
|---|---|---|
| `404.json` | ✅ | ✅ |
| `article.json` | ✅ | ✅ |
| `blog.json` | ✅ | ✅ |
| `cart.json` | ✅ | ✅ |
| `collection.json` | ✅(3 variants) | ✅(1) |
| `gift_card.liquid` | ✅ | ✅ |
| `index.json` | ✅ | ✅ |
| `list-collections.json` | ✅ | ✅ |
| `page.json` | ✅(5 variants) | ✅(2) |
| `password.json` | ✅ | ✅ |
| `product.json` | ✅(6 variants) | ✅(1) |
| `search.json` | ✅ | ✅ |
| `cart.ajax.liquid` | ✅ | ❌ |

**Impulse has 23 template files, Horizon has 13.** Impulse provides many specialized page/product/collection template variants that Horizon lacks.

---

## Snippet Comparison

| Metric | Impulse | Horizon |
|---|---|---|
| Total snippets | **107** | **119** |
| UI components | Element system (13 `element.*` files — button, badge, icon, image, input, text, video, checkbox, radio, select, swatch, divider, placeholder) | Template-level partials (no element abstraction) |
| Layout system | 4 layout files (`layout.grid`, `layout.grid.cell`, `layout.stack`, `layout.sticky-scroller`) | CSS-driven (Bootstrap 5 classes) |
| Flex PDP | 19 `section.flex-pdp.*` snippets | No flex PDP system |
| JS loading | `head.import-map.liquid` (ES module import maps) + `head.is-land.liquid` (lazy-loading) | Traditional `<script>` tags |
| Design tokens | `css-variables.liquid` + `style.primitive-tokens.liquid` + `style.normalize.liquid` | `theme-styles-variables.liquid` + `color-palette.liquid` — Bootstrap CSS variable based |
| Image handling | `image-element.liquid` (abstraction layer), `element.image.sizes.liquid`, `sizes-explicit.liquid` | `media.liquid`, `image.liquid` (direct) |
| Price handling | `product.price.liquid` | `price.liquid`, `format-price.liquid` |
| Cart | 3 files (`cart-drawer.liquid`, `cart-item.liquid`, `quantity-input.liquid`) | 7+ files (`cart-bubble.liquid`, `cart-drawer.liquid`, `cart-products.liquid`, `cart-summary.liquid`, `cart-items-component.liquid`, `quantity-selector.liquid`, `cart-disclosure-tooltip.liquid`) |
| Product grid | `product-grid-item.liquid`, `onboarding-product-grid-item.liquid` | `product-card.liquid`, `product-grid.liquid`, `editorial-product-grid.liquid`, `util-product-grid-card-size.liquid` |

### Snippet Categories Unique to Each

**Impulse unique snippet categories:**
- Element system (`element.*`) — reusable primitive building blocks
- Layout system (`layout.*`) — grid/stack/cell layout primitives
- Flex PDP system (19 files) — modular product page builder
- Import map / is-land — ES module pattern + lazy loading
- Tooltip / tool-tip-trigger
- Photoswipe template / lightbox
- Quick shop modal
- Tab / tab-contact
- Multi-selectors
- Sub-collections

**Horizon unique snippet categories:**
- Bootstrap utility classes (`spacing-style`, `size-style`, `gap-style`, `typography-style`, `border-override`, `contrast-override`, `brightness-opacities`)
- Button variants (`button.liquid`, `button-custom-styles.liquid`, `buy-buttons-styles.liquid`, `variant-button-custom-styles.liquid`)
- Filter system (`list-filter.liquid`, `filter-remove-buttons.liquid`, `price-filter.liquid`, `sorting.liquid`)
- Drawer (3 files: `theme-drawer.liquid`, `theme-drawer-styles.liquid`, `theme-drawer-header.liquid`)
- Slideshow components (6 files: slide, slides, arrows, controls, styles)
- Newsletter reminder variant
- Strikethrough variant pricing
- Volume pricing info
- Chat drawer

---

## Asset Comparison

| Category | Impulse | Horizon |
|---|---|---|
| JS files | 14 `.js` + `vendor-scripts-v11.js` + `vendor.in-view.js` | 63 `.js` |
| CSS/SCSS | `theme.css`, `country-flags.css` (minimal) | `base.css`, `overflow-list.css`, `template-giftcard.css` (+ inline in JS) |
| SVG icons | 5 `ico-select*.svg.liquid` | 34 `icon-*.svg` files |
| Other | `country-flags.css.liquid` | 5 `.d.ts` type definition files |
| **Total** | ~17 assets | ~97 assets |

Horizon's asset count is **5.7x larger** than impulse's. This suggests:
- Horizon inlines more CSS in JS/theme files (fewer standalone CSS files)
- Horizon has many more JS modules (63 vs 14)
- Horizon uses many individual icon SVGs (34) vs impulse's generic select icons (5)

---

## Block Comparison

Horizon has **97 block files** (all with `_` prefix for theme editor discovery + standalone blocks).

Impulse has **14 Flex PDP blocks** (all `_section-flex-pdp-*` named for the product page builder).

Horizon blocks cover: carousel slides, blog cards, product cards, collection cards, media, text, buttons, forms, menus, footers, social links, popups, disclosures, variant pickers, swatches, videos, comparison sliders, countdown timers, split image banners, and more.

Impulse's 14 blocks are solely for the Flexible PDP system — impulse doesn't use block files for other section content the way Horizon does.

---

## Architecture Comparison

### Impulse Architecture
- **ES Module import maps** (`head.import-map.liquid`) — modern JS loading
- **is-land lazy loading** (`head.is-land.liquid`) — defer non-critical JS
- **Element system** — reusable primitive components (button, icon, image, text, video, etc.)
- **Layout primitives** — grid, stack, cell, sticky-scroller
- **Flexible PDP** — drag-and-drop product page via 19 snippets + 14 blocks
- **Design tokens** — `style.primitive-tokens.liquid` (colors, spacing, typography tokens)
- **Theme CSS variables** — `css-variables.liquid` generated from settings
- **Lightweight assets** — 14 JS files, 2 CSS files, minimal SVG set
- **Lazy loading by default** — `is-land` defers images/sections until in-viewport

### Horizon Architecture
- **Bootstrap 5 CSS framework** — utility classes, grid system, components
- **Bento grid snippets** — `bento-grid.liquid` for magazine-style layouts
- **Extensive JS modules** — 63 JS files, many with standalone init
- **Section hydration system** — `section-hydration.js`, `section-renderer.js`
- **Modular section system** — 71 sections, each self-contained with schema
- **Block-heavy** — 97 block files for content flexibility
- **CSS variable tokens** — `theme-styles-variables.liquid`, `color-palette.liquid`
- **Overlay system** — `overlay.liquid`
- **Editorial grids** — dedicated `editorial-product-grid.liquid`, `editorial-collection-grid.liquid`, `editorial-blog-grid.liquid`

---

## Key Findings

### What Impulse Does That Horizon Can't (Easily)
1. **ES Module import maps** — Impulse uses modern JS module system; Horizon uses traditional `<script>` tags
2. **Element system** — Impulse's `element.*` snippets are reusable primitives; Horizon repeats patterns per-section
3. **is-land lazy loading** — Impulse lazily loads all non-critical sections; Horizon has `section-hydration.js` but loads more upfront JS
4. **Flexible PDP** — 19 snippets + 14 blocks for drag-and-drop product page; Horizon uses fixed product templates
5. **Age verification popup** — regulatory requirement for some stores
6. **Newsletter popup** — conversion optimization tool
7. **Recently viewed products** — expected UX for repeat visitors
8. **Promo grid** — versatile marketing layout
9. **Logo list** — common for brand trust sections
10. **Google Map** — contact page staple
11. **Quick shop modal** — faster browsing

### What Horizon Does That Impulse Can't (Easily)
1. **Bootstrap 5 framework** — mature responsive utility system
2. **3 slideshow variants** — layered, carousel, standard — more options
3. **Dedicated cart drawer + offcanvas** — 3 cart modes vs impulse's 1
4. **Modular search** — separate header/results/empty sections
5. **63 JS modules** — richer interactive functionality
6. **97 blocks** — more granular content building blocks
7. **Filter/sort system** — robust faceted search
8. **Bento grid layout** — magazine-style grids
9. **Hero with dual media slots** — complex hero compositions
10. **Editorial grids** — curated content layouts
11. **Product hotspots** — shoppable images
12. **Quantity selectors with B2B support** — volume pricing, quick order list

### Shared Gaps (Both Missing)
- Customer account pages (neither has custom `.json` — both use Shopify defaults)
- Gift card recipient form snippet (known pattern)
- Cross-selling / post-purchase upsell section
- Live chat / customer support widget
- Wishlist / favorites functionality (requires app)
- Size guide / sizing chart section
- Product comparison table
- Store locator (beyond simple map)

---

## Migration Priority Matrix

### High Priority (Missing UX/Conversion Features)
1. **Newsletter popup** — `newsletter-popup.liquid` + dependencies
2. **Promo grid** — `promo-grid.liquid` + snippets
3. **Recently viewed** — `recently-viewed.liquid` + JS + storage
4. **Logo list** — `logo-list.liquid`
5. **Map** — `map.liquid`
6. **Store availability** — `store-availability.liquid`

### Medium Priority (Quality-of-Life)
1. **Advanced content** — `advanced-content.liquid`
2. **Background image/video text** — 2 sections
3. **Featured video** — `featured-video.liquid`
4. **Hero video** — `hero-video.liquid`
5. **Text with icons** — `text-with-icons.liquid`
6. **Text and image** — `text-and-image.liquid`

### Low Priority (Niche or Already Covered)
1. Age verification popup
2. Collection return
3. Countdown (Horizon has block version)
4. Hotspots (Horizon has product version)
5. Image compare
6. Scrolling text (Horizon has marquee)
7. Product full-width
8. Text columns (covered by card-list)
9. Main page full-width
10. High-variant PDP

### DO NOT Migrate (Better as Horizon Native)
- Slideshow — Horizon has 3 better variants
- Header — Horizon's is more modular
- Footer — already well-handled
- Search — Horizon's is more modular
- Cart — Horizon has 3 cart modes
- Main product — Horizon's is sufficient
- Product recommendations — fine as-is
- Collection list — covered
- Blog — Horizon has more granular blog sections
