# ANALYSIS-impulse.md

## Impulse Shopify Theme — Comprehensive Analysis

---

## 1. Theme Overview

| Property | Value |
|---|---|
| **Theme Name** | Impulse |
| **Original Vendor** | Archetype Themes (https://archetypethemes.co) |
| **Version** | 8.2.0 |
| **Status** | Copy of original Impulse, partially rebranded to "PHANTOM" 2.2.0 in `settings_schema.json` |
| **Architecture** | JSON template sections + Liquid snippets (Shopify 2.0). Uses `{%- sections 'header-group' -%}` with group JSON files. Flex PDP uses `content_for` block slot system. |
| **JS** | Vanilla JS via `theme.js` (~8400 lines) + ES module import maps. Uses `is-land` v4.0.0 for lazy-loading components. |
| **CSS** | Single compiled `theme.css.liquid` (~13,000 lines) with CSS custom properties. |
| **Notable** | Ships design-mode debug script from `https://api.archetypethemes.co/design-mode.js` when in editor. |

---

## 2. Directory Structure

```
impulse-shopify-theme-v8.2.0/
  assets/     — CSS, JS, SVG icons, images, vendor libs (143 files)
  blocks/     — Flex PDP block definitions (@theme blocks, 14 files)
  config/     — settings_schema.json, settings_data.json
  layout/     — theme.liquid (main layout)
  locales/    — 8 locale files (JSON + schema variants)
  sections/   — 56 section-type files (.liquid + .json group definitions)
  snippets/   — 107 reusable Liquid snippet files
  templates/  — 23 template files (JSON + .liquid + customers/ subdir)
```

---

## 3. Config

### 3.1 `settings_schema.json` — Theme Settings (1065 lines)

| Category | Key Settings |
|---|---|
| **Colors** | `color_body_bg` (#fff), `color_body_text` (#1c1d1d), `color_price`, `color_savings_text` (#ff4e4e), `color_borders`, `color_button`, `color_button_text`, `color_sale_tag`, `color_sale_tag_text`, `color_cart_dot` (#ff4f33), `color_small_image_bg`, `color_large_image_bg`, `color_header`, `color_header_text`, `color_announcement`, `color_announcement_text`, `color_footer` (#111), `color_footer_text`, `color_drawer_background`, `color_drawer_text`, `color_drawer_border`, `color_drawer_button`, `color_drawer_button_text`, `color_modal_overlays`, `color_image_text` (#fff), `color_image_overlay` (#000), `color_image_overlay_opacity`, `color_image_overlay_text_shadow` |
| **Typography** | Header font (fahkwang_n3), letter spacing, header base size (22-60px), header line height, capitalize toggle. Body font, body spacing, body base size (12-20px). Navigation size, product style, collection font. Button style (square/round-slight/round/angled), icon weight (2-7px) |
| **Products** | `product_save_amount`, `product_save_type` (dollar/percent), `vendor_enable` |
| **Product Tiles** | `quick_shop_enable`, `quick_shop_text`, `product_grid_image_size` (natural/square/landscape/portrait), `product_grid_image_fill`, `product_hover_image`, `enable_swatches`, `swatch_style` (round/square) |
| **Collection Tiles** | `collection_grid_style` (overlaid/overlaid-box/below), `collection_grid_image`, `collection_grid_text_align`, `collection_grid_tint`, `collection_grid_opacity` |
| **Cart** | `cart_type` (page/drawer), `cart_icon` (bag/bag-minimal/cart), `cart_notes_enable`, `cart_terms_conditions_enable` |
| **Search** | `predictive_search_enabled`, `predictive_search_show_vendor`, `predictive_search_show_price` |
| **Social** | Links for Facebook, Twitter/X, Pinterest, Instagram, Threads, Snapchat, TikTok, Tumblr, LinkedIn, YouTube, Vimeo |
| **Extras** | `show_breadcrumbs`, `text_direction`, `disable_animations` |

### 3.2 `settings_data.json`

Two presets:
- **"Impulse"** — earthy/brown tones (`#321004` body text, `#5e6350` buttons)
- **"Dune"** — light/neutral preset

Includes checkout customization (accent color, button color, logo position/size).

---

## 4. Layout (`layout/theme.liquid`)

```html
<!doctype html>
<html lang="{{ request.locale.iso_code }}" dir="{{ settings.text_direction }}">
<head>
  <!-- Meta, viewport, canonical, preconnects, DNS prefetches, favicon -->
  <!-- SEO title, social meta (OG, Twitter) -->
  <!-- Import map & is-land module, font face declarations -->
  <!-- theme.css (preloaded), CSS variables (inline), <style> block -->
  <!-- vendor-scripts-v11.js (deferred), theme.js (deferred) -->
  <!-- Design-mode debug script (editor only) -->
</head>
<body class="template-{{ template | handle }}" data-* attributes>
  <a class="in-page-link skip-link" href="#MainContent">Skip to content</a>
  <div id="PageContainer" class="page-container">
    <div class="transition-body">
      {%- sections 'header-group' -%}
      {%- sections 'popup-group' -%}
      <main class="main-content" id="MainContent">{{ content_for_layout }}</main>
      {%- sections 'footer-group' -%}
    </div>
  </div>
  <!-- Global modals: video-modal, photoswipe-template, tool-tip -->
</body>
</html>
```

**Key body data attributes**: `data-center-text`, `data-button_style`, `data-type_header_capitalize`, `data-type_headers_align_text`, `data-type_product_capitalize`, `data-swatch_style`, `data-disable-animations`

**Global JS objects** initialized in `<head>`:
- `theme.routes` — home, cart, cartPage, cartAdd, cartChange, search, predictiveSearch
- `theme.strings` — sold out, unavailable, stock labels, save price, cart empty, search labels
- `theme.settings` — cartType, moneyFormat, saveType, productImageSize, predictiveSearch config, quickView, themeName ("Impulse"), themeVersion ("8.2.0")

---

## 5. Templates

### JSON Templates (Shopify 2.0)

| Template | Section Composition |
|---|---|
| **index.json** | `slideshow` → `rich-text` → `promo-grid` → `featured-collections` → `text-and-image` → `testimonials` → `newsletter` → `featured-collection` |
| **product.brand-story.json** | `main-product` + `product-full-width` + `product-recommendations` + `recently-viewed` + `collection-return` + content sections |
| **product.gift-card.json** | `main-product` + `product-recommendations` + `recently-viewed` + `collection-return` |
| **product.high-variant.json** | Flex PDP (`main-product-high-variant`) + `product-recommendations` + `recently-viewed` + content sections |
| **product.modal.json** | `main-product` (quick-shop modal) + `product-recommendations` |
| **product.preorder.json** | `main-product` + `product-recommendations` + `recently-viewed` + `collection-return` |
| **product.product-landing.json** | Full-width product landing |
| **collection.no-sidebar.json** | `collection-header` → `promo-grid` → `main-collection` |
| **collection.no-promos.json** | `collection-header` → `main-collection` |
| **collection.collection-landing.json** | Landing with featured collections + promos |
| **page.json**, **page.about.json**, **page.contact.json**, **page.faq.json**, **page.full-width.json** | Various page layouts |
| **blog.json** | `blog-template` |
| **article.json** | `article-template` |
| **cart.json** | `main-cart` |
| **search.json** | `main-search` |
| **list-collections.json** | `list-collections-template` |
| **404.json** | `main-404` |
| **password.json** | `password-header` |

### Legacy Liquid Templates

- **cart.ajax.liquid** — AJAX-loaded cart content
- **gift_card.liquid** — Gift card page
- **customers/** (7 files) — account, activate_account, addresses, login, order, register, reset_password

---

## 6. Sections (56 total)

### 6.1 Group Definition JSONs

| File | Purpose |
|---|---|
| **header-group.json** | `announcement` + `header` sections, center-split nav, sticky header |
| **footer-group.json** | `footer-promotions` + `footer` sections, newsletter, payment icons |
| **popup-group.json** | `newsletter-popup` + `age-verification-popup` (both disabled by default) |

### 6.2 Layout Sections

| Section | Schema Name | Key Info |
|---|---|---|
| **header.liquid** | Header | Toolbar, sticky/overlay, logo, navigation (center-split/left/center), drawer, mega menus. Blocks: logo. |
| **announcement.liquid** | Announcement bar | Up to 3 announcement slides, compact mode |
| **footer.liquid** | Footer | Multi-column: logo_social, custom, newsletter, menu, follow_shop_cta blocks. Payment icons, copyright, locale/currency |
| **footer-promotions.liquid** | Footer promotions | 3 promo blocks max, image + title + text + button |

### 6.3 Homepage / Marketing Sections

| Section | Schema Name | Description |
|---|---|---|
| **slideshow.liquid** | Slideshow | Full-width hero, parallax, autoplay, bars/dots/arrows nav, mobile images. Blocks: image, video slides. |
| **rich-text.liquid** | Rich text | Heading, page, text, button blocks. Align text, narrow column, divider. |
| **promo-grid.liquid** | Promo grid | Flexible grid: advanced (full CTA), banner, product, collection blocks. Full width, gutter. |
| **featured-collections.liquid** | Featured collections | Grid of collection cards. Blocks: collection picker. |
| **featured-collection.liquid** | Featured collection | Products from single collection, mobile scrollable |
| **featured-product.liquid** | Featured product | Full product form, gallery, buy buttons |
| **featured-video.liquid** | Featured video | YouTube/Vimeo embed |
| **text-and-image.liquid** | Text and image | Side-by-side, overlap, masks, parallax, buttons, colors |
| **text-columns.liquid** | Text columns | Grid of image/title/text/button blocks |
| **text-with-icons.liquid** | Text with icons | 25+ SVG icons built-in, row of icon/text blocks |
| **testimonials.liquid** | Testimonials | Slider with quote/stars icons, background color |
| **blog-posts.liquid** | Blog posts | Recent blog posts grid |
| **logo-list.liquid** | Logo list | Brand/partner logo bar |
| **newsletter.liquid** | Newsletter | Signup with title, text, form, social icons |
| **background-image-text.liquid** | Background image text | Full-width bg image with text overlay, parallax |
| **background-video-text.liquid** | Background video text | Full-width bg video with text overlay |
| **hero-video.liquid** | Hero video | Video background + overlay text/buttons |
| **scrolling-text.liquid** | Marquee | Horizontal scrolling marquee text, configurable speed/direction |
| **countdown.liquid** | Countdown | Timer with bg image, multiple layouts |
| **image-compare.liquid** | Image compare | Before/after slider |
| **hotspots.liquid** | Hotspots | Interactive image with clickable pins + tooltips |
| **map.liquid** | Map | Google Maps section with address overlay |
| **advanced-content.liquid** | Advanced content | Flexible content grid (liquid, image, video, product blocks) |
| **age-verification-popup.liquid** | Age verification | Modal popup, configurable age, test mode, blur bg |
| **newsletter-popup.liquid** | Newsletter popup | Modal with image, reminder bell |

### 6.4 Product Sections

| Section | Description |
|---|---|
| **main-product.liquid** | Standard PDP. Delegates to `product-template` snippet. Blocks: variant_picker, price, quantity_selector, buy_buttons, description, size_chart, inventory_status, sales_point, text, tab, contact, share, custom |
| **main-product-high-variant.liquid** | Flex PDP for high-variant products. Uses `content_for 'block'`/`content_for 'blocks'` slot system. Delegates to `section.flex-pdp` snippet. |
| **product-full-width.liquid** | Full-width product description below main product |
| **product-recommendations.liquid** | Shopify product recommendations web component |
| **recently-viewed.liquid** | Client-side recently viewed via JS |
| **collection-return.liquid** | "Back to collection" link |
| **store-availability.liquid** | Store pickup availability drawer |

### 6.5 Collection/Search Sections

| Section | Description |
|---|---|
| **collection-header.liquid** | Collection hero (image + title + breadcrumbs), optional parallax |
| **main-collection.liquid** | Grid with sidebar filters, sorting, pagination, subcollections |
| **main-search.liquid** | Search results with filters, sorting, grid |
| **predictive-search.liquid** | Live AJAX predictive search |
| **search-results.liquid** | Renders `search-results` snippet |
| **list-collections-template.liquid** | Collection landing page |

### 6.6 Content/Page Sections

| Section | Description |
|---|---|
| **main-page.liquid** / **main-page-full-width.liquid** | Page layouts |
| **contact-form.liquid** | Name, email, phone, message |
| **faq.liquid** | Accordion FAQ with schema.org JSON-LD |
| **article-template.liquid** / **blog-template.liquid** | Blog + article |
| **main-404.liquid** | 404 page |
| **apps.liquid** | App embed section |
| **giftcard-header.liquid** | Gift card page header |

---

## 7. Snippets (107 total)

### 7.1 Layout / Structural

| Snippet | Description |
|---|---|
| **css-variables.liquid** | CSS custom properties for all colors, typography, spacing, button radius |
| **style.primitive-tokens.liquid** | Design token scale: sizes (1px-128px), text sizes (8px-40px), border radius, color palette |
| **style.normalize.liquid** | CSS normalize/reset |
| **font-face.liquid** | `@font-face` declarations from font_picker |
| **head.import-map.liquid** | ES module import map for JS modules |
| **head.is-land.liquid** | Loads `is-land.min.js` as ES module |
| **seo-title.liquid** | `<title>` generation |
| **social-meta-tags.liquid** | OG + Twitter card meta tags |
| **layout.section.liquid** | Generic section wrapper |
| **layout.grid.liquid** / **layout.grid.cell.liquid** | Grid system |
| **layout.stack.liquid** / **layout.sticky-scroller.liquid** | Stack + sticky scroll |
| **breadcrumbs.liquid** | Breadcrumb navigation |
| **pagination.liquid** | Numbered pagination |
| **products_per_row.liquid** | CSS fraction class generator |
| **sizes-explicit.liquid** | Responsive image sizes attribute |
| **utility.breakpoint.liquid** / **utility.breakpoint-style.liquid** | Responsive breakpoint utilities |
| **utility.color-scheme-texture.liquid** | SVG texture overlays |
| **utility.id.liquid** / **utility.translate.liquid** | ID generation + translate utilities |

### 7.2 Element Snippets (Design System)

| Snippet | Description |
|---|---|
| **element.badge.liquid** | Badge component |
| **element.button.liquid** | Button component |
| **element.checkbox.liquid** / **element.radio.liquid** | Form inputs |
| **element.divider.liquid** | Divider |
| **element.icon.liquid** | SVG icon renderer |
| **element.image.liquid** / **element.image.sizes.liquid** | Responsive image output |
| **element.input.liquid** / **element.select.liquid** / **element.swatch.liquid** | Form input variants |
| **element.text.liquid** / **element.text--body.liquid** / **element.text--heading.liquid** / **element.text--rte.liquid** | Text components |
| **element.video.liquid** / **element.model.liquid** | Media components |
| **element.placeholder.liquid** | Placeholder/onboarding content |
| **element.quantity-selector.liquid** | Quantity +/- selector |

### 7.3 Image / Media

| Snippet | Description |
|---|---|
| **image-element.liquid** | Core image builder. Handles Shopify images, hosted assets, Photoswipe. Supports `loading`, `sizes`, `widths`, `preload`, `format`. 202 lines. |
| **media.liquid** | Renders product media (image, external_video, video, model). Alt-based image set grouping (`#groupname_setname`). |
| **media-text.liquid** | Combined media + text layout |
| **video-div.liquid** | YouTube/Vimeo embed iframe with responsive wrapper |
| **video-modal.liquid** / **photoswipe-template.liquid** / **lightbox.liquid** | Media overlays |

### 7.4 Header / Navigation

| Snippet | Description |
|---|---|
| **drawer-menu.liquid** | Mobile slide-out nav (256 lines). Multi-level menus, mega menus, collapsible submenus |
| **announcement-bar.liquid** | Announcement slider with pause/play, compact mode |
| **header-desktop-nav.liquid** | Desktop nav with dropdown/mega menu support |
| **header-split-nav.liquid** | Split navigation (logo centered, nav both sides) |
| **header-icons.liquid** | Search, account, cart icons |
| **header-logo-block.liquid** | Logo block |
| **toolbar.liquid** | Locale/currency selectors, social links, menu |

### 7.5 Cart / Checkout

| Snippet | Description |
|---|---|
| **cart-drawer.liquid** | Slide-out cart drawer (items, notes, discounts, subtotal, checkout) |
| **cart-item.liquid** | Single cart item row |
| **quantity-input.liquid** | Quantity +/- buttons |
| **gift-card-recipient-form.liquid** | Gift card recipient fields |

### 7.6 Product

| Snippet | Description |
|---|---|
| **product-template.liquid** | **Core PDP template (451 lines).** Image position, media gallery, thumbnails, description, variants, buy buttons, size chart, social sharing. |
| **product-template-variables.liquid** | Product JSON data for JS (variants, images, options, price) |
| **product-grid-item.liquid** | **Product card (358 lines).** Image natural/fixed, hover image, badges, swatches, quick shop, colors |
| **product-form.liquid** | Add-to-cart form with gift card recipient, dynamic checkout, preorder |
| **product-price.liquid** | Price display (compare at, sale, unit price) |
| **product-description.liquid** | Product description (inline or tab) |
| **product-images.liquid** | Product media gallery |
| **product-inventory.liquid** | Inventory status bar |
| **product-complementary.liquid** | Complementary products |
| **product.hot-reload.liquid** | Variant change hot-reload |
| **variant-button.liquid** / **variant-dropdown.liquid** | Variant picker UI |
| **form.product.liquid** / **form.product.messages.liquid** | Product form wrapper + messages |
| **size-chart.liquid** (implicit) | Size chart modal |
| **tool-tip-trigger.liquid** | Tooltip trigger |

### 7.7 Collection / Search

| Snippet | Description |
|---|---|
| **collection-grid.liquid** | Grid wrapper with filter bar, sort, grid, empty state (131 lines) |
| **collection-grid-item.liquid** | Collection card |
| **collection-grid-filters.liquid** | Sidebar + drawer filter system |
| **collection-grid-filters-form.liquid** | Filter form (tags, price range, color swatches) |
| **onboarding-product-grid-item.liquid** | Placeholder product card |
| **search-grid-item.liquid** / **search-results.liquid** | Search result card + layout |
| **predictive-search.liquid** | ARIA combobox predictive search, dark bg detection |
| **subcollections.liquid** / **sub-collections.liquid** | Subcollection links |

### 7.8 Content

| Snippet | Description |
|---|---|
| **article-grid-item.liquid** / **comment.liquid** | Blog article card + comment |
| **social-sharing.liquid** | Facebook, Twitter/X, Pinterest share buttons |
| **social-icons.liquid** | Social icon links |
| **newsletter-form.liquid** / **newsletter-reminder.liquid** / **newsletter-section.liquid** | Newsletter components |
| **tab.liquid** / **tab-contact.liquid** | Tab content containers |
| **gallery.liquid** | Image gallery |
| **advanced-accordion.liquid** | Accordion component |
| **multi-selectors.liquid** | Locale/currency dropdowns |
| **collapsible-icons.liquid** / **collapsible-icons-alt.liquid** | Collapse/expand +/- icons |
| **follow-shop-cta.liquid** | Follow shop CTA |
| **footer-custom-text.liquid** / **footer-logo.liquid** / **footer-menu.liquid** / **footer-newsletter.liquid** | Footer block renderers |
| **overlay.drawer.liquid** / **overlay.lightbox.liquid** | Overlay components |
| **customer-account.liquid** | Customer account nav |
| **tool-tip.liquid** | Generic tooltip web component |
| **promo-grid.liquid** | Promo grid rendering (502 lines) — advanced CTAs, banners, products, collections |
| **promo-video.liquid** | Video promo component |
| **scrolling-text.liquid** | Marquee text animation |
| **quick-shop-modal.liquid** | Quick shop modal with product handle lookup |

### 7.9 Flex PDP Snippets

| Snippet | Description |
|---|---|
| **section.flex-pdp.liquid** | Flex PDP orchestrator (80 lines). Accepts `product`, `slot_gallery`, `slot_details`. Renders layout stack with sticky scroller. |
| **section.flex-pdp.* (13 files)** | Title, vendor, price, installments, divider, variant-picker, quantity-picker, gift-recipient, buy-buttons, policies, pick-up, description, sku, inventory, media-gallery, media-grid |

---

## 8. Blocks

### 8.1 Flex PDP Blocks (`blocks/` directory — 14 @theme blocks)

| Block File | Renders | Schema Settings |
|---|---|---|
| `_section-flex-pdp-buy-buttons.liquid` | `section.flex-pdp.buy-buttons` | show_dynamic_checkout |
| `_section-flex-pdp-description.liquid` | `section.flex-pdp.description` | is_tab |
| `_section-flex-pdp-divider.liquid` | — | — |
| `_section-flex-pdp-installments.liquid` | — | — |
| `_section-flex-pdp-inventory.liquid` | — | — |
| `_section-flex-pdp-media-gallery.liquid` | `section.flex-pdp.media-gallery` | aspect_ratio, media_size, video_looping, thumb position, zoom |
| `_section-flex-pdp-pick-up.liquid` | — | — |
| `_section-flex-pdp-policies.liquid` | — | policy types |
| `_section-flex-pdp-price.liquid` | — | show_unit_price, show_taxes |
| `_section-flex-pdp-quantity-picker.liquid` | — | — |
| `_section-flex-pdp-sku.liquid` | — | — |
| `_section-flex-pdp-title.liquid` | — | — |
| `_section-flex-pdp-variant-picker.liquid` | — | picker_type (button/dropdown), enable_color_swatches, enable_size_chart |
| `_section-flex-pdp-vendor.liquid` | — | — |

### 8.2 Section Block Types (inline in section schemas)

Header: `logo`. Announcement: `announcement` (max 3). Footer: `logo_social`, `custom`, `newsletter`, `menu`, `follow_shop_cta`. Slideshow: `image`, `video`. Promo-grid: `advanced`, `banner`, `product`, `collection`. Main-product: `@app`, `variant_picker`, `price`, `quantity_selector`, `buy_buttons`, `description`, `size_chart`, `inventory_status`, `sales_point`, `text`, `tab`, `contact`, `share`, `custom`. Testimonials: `testimonial`. FAQ: `rich-text`, `question`. Text-with-icons: icon blocks (25 SVG types). Advanced-content: `liquid`, `image`, `video`, `product`. Countdown: `content`, `button`. Hotspots: `hotspot` (position). Logo-list: image + link. Footer-promotions: `promotion`.

---

## 9. Assets

### 9.1 JavaScript (20+ files)

| File | Description |
|---|---|
| **theme.js** (~8400 lines) | Main JS. Vanilla JS. Cart, product forms, recently viewed, predictive search, slideshows, modals, drawers, collection filters, image zoom, variant switching, mobile nav, sticky header, currency/locale, newsletter popups, age verification, tooltips, PhotoSwipe. |
| **vendor-scripts-v11.js** | Bundled vendor libs (Flickity, PhotoSwipe, likely) |
| **is-land.min.js** | 11ty/is-land v4.0.0 — lazy-loading web components |
| **element.base-media.js**, **element.image.parallax.js**, **element.model.js**, **element.quantity-selector.js**, **element.text.rte.js**, **element.video.js** | Web components |
| **util.misc.js**, **util.product-loader.js**, **util.resource-loader.js**, **vendor.in-view.js** | Utilities |
| **country-flags.css.liquid** | Currency flag sprite styles |

### 9.2 CSS

| File | Description |
|---|---|
| **theme.css.liquid** (~13,000 lines) | All theme styles: grid, typography, header/nav, product grid/page, cart, collection, footer, slideshow, animations, modals, drawers, forms, buttons, responsive, utilities |
| **theme.css** | Non-Liquid version (pre-processed) |

### 9.3 SVG Icons (90+ files)

**Navigation/UI**: chevrons, close, hamburger, search, filter, plus, minus, check, play, pause, dot

**Social**: facebook, instagram, pinterest, tiktok, tumblr, linkedin, vimeo, youtube, snapchat, rss, x

**Ecommerce**: bag, bag-minimal, cart, tag, lock, gift, truck, package, shield

**Text-with-icons (23)**: bills, calendar, cart, charity, chat, envelope, gears, gift, globe, package, phone, plant, recycle, ribbon, sales-tag, shield, stopwatch, store, thumbs-up, tiktok, trophy, truck, wallet

**Custom labels (13)**: award-winning, bestseller, vegan, women-owned, sustainable, natural, local, leaf, leaf-2, ribbon, staffs-pick, celebration, cool, wand, acorn, amphora, case

**Select arrows**: ico-select.svg (and drawer/footer/menu/white variants)

**Misc images**: marble.jpg, paper.jpg, space.jpg, notebook.svg, plants.svg, wave.svg, minimal-wave.svg, swirl.svg, password-page-background.jpg

---

## 10. Locales

| File | Language |
|---|---|
| `en.default.json` + `en.default.schema.json` | English (primary) |
| `de.json` + `de.schema.json` | German |
| `es.json` + `es.schema.json` | Spanish |
| `fr.json` + `fr.schema.json` | French |
| `it.json` + `it.schema.json` | Italian |
| `pt-BR.json` | Portuguese (Brazil) |
| `pt-PT.json` | Portuguese (Portugal) |

---

## 11. Key Observations

### 11.1 Architecture & Patterns

1. **Hybrid Shopify 2.0 + Legacy**: JSON templates with `{%- sections 'header-group' -%}` (2.0 groups), but also legacy `.liquid` templates (`cart.ajax`, `gift_card`, customer templates). Flex PDP uses `content_for 'block'`/`content_for 'blocks'` slot system.

2. **Flex PDP System**: `main-product-high-variant` uses `@theme` blocks in `blocks/` directory, each rendering a `section.flex-pdp.*` snippet. The orchestrator (`section.flex-pdp.liquid`) accepts gallery + details slots with sticky-scroller layout.

3. **Design Token System**: Two CSS variable systems: `css-variables.liquid` (theme settings) + `style.primitive-tokens.liquid` (design token scale).

4. **Component Architecture**: Snippets organized as design system — `element.*` components, `layout.*` containers, `utility.*` helpers. Sophisticated snippet-based component library.

5. **ES Module Import Map**: Browser-native ES modules via `<script type="importmap">`. Web components lazy-loaded via `is-land`.

### 11.2 Notable Features

6. **Quick Shop / Quick View** — AJAX product modal
7. **Predictive Search** — Full ARIA combobox pattern, searches products/collections/pages/articles
8. **Recently Viewed Products** — Client-side via JS
9. **Custom Labels via Tags** — `_label_Custom Text` tags or `product.metafields.theme.label`
10. **Image Set Grouping** — Alt text `#groupname_setname` for variant-based image filtering
11. **Color Swatches on Grid** — Variant color swatches in product grid
12. **Size Chart Integration** — Auto-connects to variant picker when option name contains translated "size"
13. **Four Button Styles** — Square, Round-slight (3px), Round (50px pill), Angled (skew transform)
14. **Marquee/Scrolling Text** — Configurable speed/direction
15. **Image Comparison Slider** — Before/after draggable
16. **Hotspots** — Interactive pinned image with tooltips
17. **Age Verification** — Configurable modal with blur, test mode
18. **Newsletter Popup + Reminder Bell** — Configurable delay, dismissal tracking
19. **Countdown Timer** — Background image, multiple layouts, end date

### 11.3 Performance

20. **Lazy loading**: Images `loading="lazy"` by default, `data-aos` scroll animations via `vendor.in-view.js`
21. **Deferred scripts**: `vendor-scripts-v11.js` + `theme.js` loaded with `defer`. Customer scripts conditional.
22. **Preconnect hints**: `cdn.shopify.com`, `fonts.shopifycdn.com`. DNS-prefetch for reviews, Google APIs.
23. **CSS preloading**: `theme.css` preloaded
24. **JS bundle size**: `theme.js` ~8400 lines, but modular web components lazy-loaded via `is-land`

### 11.4 Third-Party Integrations

25. **PhotoSwipe** — Image lightbox
26. **Flickity** — Carousel for slideshows, testimonials, announcement bar
27. **Archetype CDN** — `design-mode.js` debug script (editor only)
28. **Google Maps** — Map section requires API key
29. **YouTube/Vimeo** — Video sections
30. **Schema.org** — FAQPage, CollectionPage, article structured data

### 11.5 Unique Decisions

31. **Snippet-as-Component Library** — Full design system from Liquid snippets only, no render-linked sections for elements
32. **Drawer-based UI** — Nav, cart, filters, store availability, quick shop all use consistent drawer pattern
33. **Pre-calculated colors** — `--colorBodyAlpha05`, `--colorBodyDim`, `--colorBodyLightDim` etc.
34. **Metafield-driven labels** — `product.metafields.theme.label` in addition to tag-based
35. **Compact vs Standard** — Announcement bar and many sections support compact modes
36. **Rebranded** — `settings_schema.json` says "PHANTOM" v2.2.0, but codebase is Impulse v8.2.0 by Archetype Themes
37. **No `_blocks.liquid` wrapper** — Unlike Horizon, Impulse defines blocks inline in section schemas or as standalone `@theme` block files
38. **Gift Card support** — Full recipient form, dedicated template, `giftcard-header.liquid`
39. **Country flags** — PNG sprite sheet with CSS background-position for performant currency flags
