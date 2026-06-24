# Horizon Theme — Full Analysis

## Meta
- **Name:** Horizon v4.1.0
- **Author:** (Dawn-based architecture, custom build)
- **Shopify OS:** 2.0 (JSON templates, advanced sections)
- **Stack:** Vanilla JS (ES6+), CSS custom properties, component-based architecture
- **Architecture:** Component-class JS, custom element hydration, view transitions
- **Directory:** `C:\Users\hammad\Downloads\uiux\horizon\`

---

## 1. Sections (40 total)

### announcement-bar
- Animated announcement bar for promotions

### banner
- Full-width hero with image, video, text overlay, buttons
- **Blocks:** heading, text, buttons, image

### blog-posts
- Blog posts grid/carousel with featured post option

### cart-products
- Cart line items with quantity, price, line item properties

### cart-summary
- Cart totals, discounts, shipping calculator, checkout button

### cart-title
- Cart page heading with back link

### chat-drawer
- Chat widget drawer (unique)

### collection-list
- Collection cards grid

### collection-products
- Product grid with filters, sorting, pagination

### contact-form
- Contact form with custom fields

### contact-info
- Contact info display

### custom-liquid
- Arbitrary Liquid/HTML output

### email-signup
- Newsletter/Blog email signup with custom styles

### faq
- Accordion FAQ with search/filter capability

### faq-main
- Full-page FAQ with categories

### featured-blog-posts
- Featured blog posts with carousel

### featured-collection
- Featured product collection grid

### featured-collection-tabbed
- Tabbed featured collections (unique — similar to open PHANTOM's products-tabs)

### featured-product
- Single featured product with full details

### footer
- Multi-column footer: logo, menus, social, copyright, payment icons
- **Blocks:** Menu columns, newsletter, social, copyright, policy list

### header
- Advanced header: sticky, transparent, mega menu, multi-row, search overlay
- **Blocks:** Logo, menu, announcements, icons

### hero
- Hero section variant

### image-hotspot
- Interactive image with product hotspots/tooltips

### image-text
- Image with text overlay

### image-with-text
- Side-by-side image + text

### layered-slideshow
- Parallax layered slideshow with depth effect (unique)

### marquee
- Scrolling marquee text/news ticker

### media-with-text
- Full-width media + text split

### multi-column
- Grid of column items (icons, images, text)

### newsletter-popup
- Newsletter signup popup/modal

### page
- Page content wrapper

### popup
- Generic marketing popup

### product-recommendations
- Shopify product recommendations via API

### product-tabs
- Tabbed product details/information (unique)

### recently-viewed
- Recently viewed products via localStorage

### related-products
- Related products by collection/tag

### search
- Search results page

### text-boxes
- Rich text box sections

### trending-products
- Trending/popular products carousel

### video
- Video section with poster image, autoplay, controls

---

## 2. Blocks (95 total)

### Content-type Blocks (reusable across sections)
| Block | Purpose |
|-------|---------|
| `_accordion-row.liquid` | Accordion item |
| `_announcement.liquid` | Announcement bar slide |
| `_blog-post-card.liquid` | Blog post card layout |
| `_blog-post-content.liquid` | Blog post body |
| `_blog-post-description.liquid` | Blog excerpt |
| `_blog-post-featured-image.liquid` | Featured image |
| `_blog-post-image.liquid` | Blog post image |
| `_blog-post-info-text.liquid` | Date/author meta |
| `_blog-post-title.liquid` | Blog title |
| `_card.liquid` | Generic card wrapper |
| `_carousel-content.liquid` | Carousel slide |
| `_cart-products.liquid` | Cart line items block |
| `_cart-summary.liquid` | Cart totals block |
| `_cart-title.liquid` | Cart heading block |
| `_collection-card-image.liquid` | Collection card image |
| `_collection-card.liquid` | Collection card |
| `_collection-image.liquid` | Collection image block |
| `_collection-info.liquid` | Collection info |
| `_collection-link.liquid` | Collection link |
| `_content-without-appearance.liquid` | HTML content block |
| `_content.liquid` | Content block |
| `_divider.liquid` | Horizontal divider |
| `_featured-blog-posts-card.liquid` | Featured blog card |
| `_featured-blog-posts-image.liquid` | Featured blog image |
| `_featured-blog-posts-title.liquid` | Featured blog title |
| `_featured-product-gallery.liquid` | Product gallery block |
| `_featured-product-information-carousel.liquid` | Product info carousel |
| `_featured-product-price.liquid` | Product price block |
| `_featured-product.liquid` | Featured product full |
| `_footer-social-icons.liquid` | Social icons in footer |
| `_header-logo.liquid` | Logo block |
| `_header-menu.liquid` | Menu block |
| `_heading.liquid` | Generic heading |
| `_hotspot-product.liquid` | Hotspot tooltip |
| `_image.liquid` | Generic image block |
| `_inline-collection-title.liquid` | Collection inline title |
| `_inline-text.liquid` | Inline text modifier |
| `_layered-slide.liquid` | Layered slideshow slide |
| `_marquee.liquid` | Marquee item |
| `_media-without-appearance.liquid` | Media block |
| `_product-card-gallery.liquid` | Product card image gallery |
| `_product-card-group.liquid` | Product card group |
| `_product-card.liquid` | Product card standalone |
| `_product-details.liquid` | Product details (title, price, description) |
| `_product-list-button.liquid` | Product list button |
| `_product-list-content.liquid` | Product list content |
| `_product-list-text.liquid` | Product list text |
| `_product-media-gallery.liquid` | Product media gallery |
| `_search-input.liquid` | Search input |
| `_slide.liquid` | Generic slide |
| `_social-link.liquid` | Social media link |

### Functional Blocks (section components)
| Block | Purpose |
|-------|---------|
| `accelerated-checkout.liquid` | Dynamic checkout button |
| `accordion.liquid` | Accordion behavior |
| `add-to-cart.liquid` | Add to cart button |
| `button.liquid` | Button component |
| `buy-buttons.liquid` | Buy buttons wrapper |
| `collection-card.liquid` | Collection card wrapper |
| `collection-title.liquid` | Collection title |
| `comparison-slider.liquid` | Before/after slider |
| `contact-form-submit-button.liquid` | Contact form submit |
| `contact-form.liquid` | Contact form |
| `custom-liquid.liquid` | Custom liquid |
| `disclosures.liquid` | Disclosure elements |
| `email-signup.liquid` | Email signup |
| `featured-collection.liquid` | Featured collection |
| `filters.liquid` | Collection filters |
| `follow-on-shop.liquid` | Follow on Shop |
| `footer-copyright.liquid` | Footer copyright |
| `footer-policy-list.liquid` | Footer policy links |
| `group.liquid` | Block group wrapper |
| `icon.liquid` | Icon renderer |
| `image.liquid` | Image component |
| `jumbo-text.liquid` | Large featured text |
| `logo.liquid` | Logo renderer |
| `menu.liquid` | Menu component |
| `page-content.liquid` | Page content |
| `page.liquid` | Page wrapper |
| `payment-icons.liquid` | Payment icon list |
| `popup-link.liquid` | Popup trigger link |
| `price.liquid` | Price renderer |
| `product-card.liquid` | Product card |
| `product-custom-property.liquid` | Custom property |
| `product-description.liquid` | Product description |
| `product-inventory.liquid` | Inventory status |
| `product-recommendations.liquid` | Recommendations |
| `product-title.liquid` | Product title |
| `quantity.liquid` | Quantity selector |
| `review.liquid` | Review component |
| `sku.liquid` | SKU display |
| `social-links.liquid` | Social link list |
| `spacer.liquid` | Vertical spacer |
| `swatches.liquid` | Color swatches |
| `text.liquid` | Text block |
| `variant-picker.liquid` | Variant dropdown/button |
| `video.liquid` | Video embed |

## 3. Snippets (119 total)

### Layout & Structure
- `section.liquid` — Section wrapper with settings (colors, spacing, width)
- `group.liquid` — Block group with layout options
- `theme-editor.liquid` — Theme editor meta tags
- `theme-drawer.liquid`, `theme-drawer-header.liquid`, `theme-drawer-styles.liquid` — Side drawer system
- `theme-styles-variables.liquid` — CSS custom property output

### Navigation & Header
- `header-actions.liquid`, `header-row.liquid` — Header layout
- `header-drawer.liquid` — Mobile nav drawer
- `mega-menu-list.liquid` — Mega menu
- `menu-font-styles.liquid`, `submenu-font-styles.liquid` — Menu typography
- `search.liquid`, `search-modal.liquid` — Search UI
- `predictive-search-*.liquid` (3 files) — Live search results
- `localization-form.liquid` — Country/language picker
- `overflow-list.liquid` — Wrapping list component

### Cart
- `cart-drawer.liquid` — Slide-out cart
- `cart-bubble.liquid` — Cart count badge
- `cart-products.liquid`, `cart-summary.liquid` — Cart content
- `cart-disclosure-tooltip.liquid` — Cart disclosure tooltip
- `cart-items-component.liquid` — Cart items component
- `gift-card-recipient-form.liquid` (+ styles) — Gift card form

### Product
- `product-card.liquid` — Product card
- `product-grid.liquid` — Product grid layout
- `product-media.liquid`, `product-media-gallery-content.liquid` (+ styles) — Media gallery
- `product-information-content.liquid` — Product info block
- `product-badges-styles.liquid` — Badge rendering
- `price.liquid`, `format-price.liquid`, `unit-price.liquid` — Price helpers
- `variant-main-picker.liquid`, `variant-picker-styles.liquid` — Variant selector
- `variant-swatches.liquid` — Swatch rendering
- `variant-button-custom-styles.liquid` — Button variant styles
- `quantity-selector.liquid` — Qty input
- `add-to-cart-button.liquid` — Add to cart btn
- `buy-buttons-styles.liquid` — Buy buttons styling
- `quick-add.liquid`, `quick-add-modal.liquid`, `quick-add-styles.liquid`, `quick-add-modal-styles.liquid` — Quick add system
- `sku.liquid` — SKU display
- `strikethrough-variant.liquid` — Compare-at variant
- `volume-pricing-info.liquid` — Volume pricing
- `tax-info.liquid` — Tax info line
- `gift-card-recipient-button-custom-styles.liquid` — Recipient button

### Collection & Filters
- `editorial-collection-grid.liquid` — Editorial collection grid
- `editorial-product-grid.liquid` — Editorial product grid
- `filter-remove-buttons.liquid` — Active filter pills
- `list-filter.liquid` — Filter dropdown/checkbox
- `price-filter.liquid` — Price range filter
- `sorting.liquid` — Sort dropdown
- `grid-density-controls.liquid` — Grid density toggle

### Blog
- `editorial-blog-grid.liquid` — Blog grid layout
- `blog-comment-form.liquid` — Comment form

### UI Components
- `accordion-custom-component.liquid`, `accordion-styles.liquid` — Accordion
- `button.liquid`, `button-custom-styles.liquid` — Button component
- `checkbox.liquid` — Custom checkbox
- `divider.liquid` — Horizontal rule
- `icon.liquid`, `icon-or-image.liquid` — Icon renderer
- `image.liquid` — Responsive image
- `input-custom-styles.liquid` — Input styling
- `jumbo-text.liquid` — Jumbo typography
- `media.liquid` — Media (image/video)
- `slideshow.liquid`, `slideshow-arrow.liquid`, `slideshow-arrows.liquid`, `slideshow-controls.liquid`, `slideshow-slide.liquid`, `slideshow-styles.liquid` — Slideshow system
- `spacer.liquid` — Whitespace
- `swatch.liquid` — Swatch renderer
- `video.liquid` — Video element

### Background & Effects
- `background-media.liquid` — Section background video/image
- `bento-grid.liquid` — Bento grid layout
- `overlay.liquid` — Color overlay
- `border-override.liquid` — Border customization
- `contrast-override.liquid` — Contrast mode
- `brightness-opacities.liquid` — Brightness/opacity utilities

### Utilities
- `scripts.liquid` — JS loading
- `stylesheets.liquid` — CSS loading
- `fonts.liquid` — Font loading
- `color-palette.liquid` — Color scheme values
- `meta-tags.liquid` — SEO meta tags
- `skip-to-content-link.liquid` — Accessibility skip link
- `pagination-controls.liquid` — Page navigation
- `resource-card.liquid`, `resource-image.liquid`, `resource-list.liquid`, `resource-list-carousel.liquid` — Resource components
- `resolve-custom-hover.liquid` — Custom hover effects
- `spacing-padding.liquid`, `spacing-style.liquid`, `gap-style.liquid`, `size-style.liquid`, `typography-style.liquid` — Style helpers
- `link-featured-image.liquid` — Featured image link
- `card-gallery.liquid` — Card gallery
- `layout-panel-style.liquid` — Panel layout
- `text.liquid` — Text renderer
- `collection-card.liquid` — Collection card
- `util-*.liquid` (5 files) — Utility helpers (image sizes, palette hover, etc.)

---

## 4. Templates (13)

| Template | Notes |
|----------|-------|
| `index.json` | Homepage |
| `product.json` | Product detail |
| `collection.json` | Collection listing |
| `blog.json` | Blog listing |
| `article.json` | Article page |
| `page.json` | Generic page |
| `page.contact.json` | Contact page |
| `cart.json` | Cart page |
| `search.json` | Search results |
| `list-collections.json` | All collections |
| `404.json` | Not found |
| `password.json` | Password page |
| `gift_card.liquid` | Gift card (liquid, not JSON) |

---

## 5. Layout

### `theme.liquid`
- Component-class JS architecture (ES6 modules)
- Section hydration system (lazy-load sections)
- View transitions API support
- CSS custom properties for all design tokens
- Font loading via `fonts.liquid` snippet
- Performance optimized (`performance.js`)
- Accessibility: skip-to-content, ARIA, focus management
- Rich SEO: JSON-LD, meta tags, Open Graph

### `password.liquid`
- Minimal password page layout

---

## 6. Config & Settings

- Full color scheme system (multiple palettes, custom colors)
- Typography controls (Google Fonts integration)
- Layout settings (container width, gaps, spacing)
- Header customization (sticky, transparent, layout)
- Footer blocks (multi-column)
- Animation preferences (reduced motion support)
- Social media links
- Custom CSS/JS
- Checkout customization

---

## 7. Locales (51 files)

Extensive multi-language support:
- 25+ language translations
- Each language has translation `.json` + `.schema.json` (for admin labels)
- Includes: bg, cs, da, de, el, en, es, fi, fr, hr, hu, id, it, ja, ko, lt, nb, nl, pl, pt-BR, pt-PT, ro, ru, sk, sl, sv, th, tr, vi, zh-CN, zh-TW

---

## 8. Assets (122 files)

### JavaScript Architecture (Component-based)
| Category | Files |
|----------|-------|
| **Core** | `component.js` (base class), `events.js`, `focus.js`, `utilities.js`, `performance.js` |
| **Section hydration** | `section-hydration.js`, `section-renderer.js` |
| **Header** | `header.js`, `header-actions.js`, `header-drawer.js`, `header-menu.js` |
| **Cart** | `cart-drawer.js`, `cart-icon.js`, `cart-note.js`, `cart-discount.js`, `component-cart-items.js`, `component-cart-quantity-selector.js` |
| **Product** | `product-form.js`, `product-card.js`, `product-price.js`, `product-inventory.js`, `product-custom-property.js`, `product-sku.js`, `product-recommendations.js`, `variant-picker.js`, `media-gallery.js` |
| **Search** | `predictive-search.js`, `search-page-input.js` |
| **Slideshow** | `slideshow.js`, `layered-slideshow.js`, `marquee.js` |
| **UI** | `accordion-custom.js`, `dialog.js`, `floating-panel.js`, `morph.js`, `scroll-container.js`, `scrolling.js`, `show-more.js`, `auto-close-details.js` |
| **Quick add** | `quick-add.js` |
| **Other** | `announcement-bar.js`, `blog-posts-list.js`, `comparison-slider.js`, `disclosures-summary-fit.js`, `drag-zoom-wrapper.js`, `facets.js`, `fly-to-cart.js`, `gift-card-recipient-form.js`, `local-pickup.js`, `localization.js`, `component-quantity-selector.js`, `copy-to-clipboard.js`, `jumbo-text.js`, `media.js`, `money-formatting.js`, `overflow-list.js`, `paginated-list.js`, `paginated-list-aspect-ratio.js`, `price-per-item.js`, `product-hotspot.js`, `qr-code-generator.js`, `qr-code-image.js`, `recently-viewed-products.js`, `results-list.js`, `rte-formatter.js`, `sticky-add-to-cart.js`, `video-background.js`, `volume-pricing.js`, `volume-pricing-info.js`, `zoom-dialog.js` |

### CSS
- `base.css` — Main stylesheet with CSS custom properties
- `overflow-list.css` — Overflow list component styles
- `template-giftcard.css` — Gift card specific styles

### SVG Icons (26 files)
All standard Shopify UI icons (cart, search, account, close, chevrons, play, pause, etc.)

---

## 9. Unique / Notable Features

1. **Component-Class JS** — Custom `Component` base class with lifecycle hooks (mount, update, destroy). All JS extends this.
2. **Section Hydration** — Lazy-load section JavaScript only when section enters viewport
3. **View Transitions API** — SPA-like page transitions using browser View Transitions API
4. **Layered Slideshow** — Parallax depth-effect slideshow (standout feature)
5. **Featured Collection Tabbed** — Tabbed product collections (same concept as open PHANTOM's products-tabs but likely more polished)
6. **Product Tabs** — Tabbed product info/details
7. **Image Hotspots** — Interactive product hotspots on images
8. **Chat Drawer** — Built-in chat widget drawer
9. **Mega Menu** — Advanced mega menu with multiple layouts
10. **Quick Add System** — Full quick-add modal with variant selection
11. **Bento Grid** — Editorial bento grid layout
12. **Filter System** — Advanced faceted filtering with price range, grid density
13. **Newsletter Popup** — Email signup modal
14. **Recently Viewed** — localStorage-based product history
15. **Trending Products** — Popular/sale products section
16. **95 Blocks** — Extensive block system for maximum page builder flexibility
17. **117+ Snippets** — Highly modular, reusable component architecture
18. **51 Locales** — Production-grade internationalization
19. **122 Assets** — Full JS architecture, not just jQuery/plugin-based
20. **No framework dependencies** — Pure vanilla JS, no Bootstrap/jQuery

---

## 10. Summary

Horizon v4.1.0 is a **modern, component-architecture** Shopify OS 2.0 theme with **zero framework dependencies** (no Bootstrap, no jQuery). It features a custom **Component-class JS** architecture with section hydration, view transitions, extensive block system (95 blocks), and advanced sections (Layered Slideshow, Image Hotspots, Tabbed Collections, Chat Drawer, Mega Menu). It is production-grade with 51 locale files, 122 JS/CSS/asset files, and fully modular snippet architecture. It is significantly more sophisticated than open PHANTOM in terms of code architecture, but open PHANTOM has a few unique sections (Marketing Popup with exit intent, Marquee Text, Logo Slider, Products Tabs concept, Trends, Bottom Slider, Bottom Banner) that horizon does not appear to have.
