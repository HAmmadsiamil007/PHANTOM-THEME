# 🌅 Horizon — Premium Multi-Purpose Shopify Theme

> **Version 4.1.0** — A modern, modular, high-performance Shopify 2.0 theme built for every type of store.

---

## 📋 Table of Contents

- [Overview](#overview)
- [Installation](#installation)
- [Theme Architecture](#theme-architecture)
- [Design Token System (21 Foundation Systems)](#design-token-system-21-foundation-systems)
- [Section & Block Systems](#section--block-systems)
- [Templates](#templates)
- [Header System](#header-system)
- [Footer System](#footer-system)
- [Skeleton UI Loading System](#skeleton-ui-loading-system)
- [Positioning & Visibility Engine](#positioning--visibility-engine)
- [Section Token Override Engine](#section-token-override-engine)
- [JavaScript Architecture](#javascript-architecture)
- [CSS & Styling](#css--styling)
- [Settings & Customization](#settings--customization)
- [Performance Optimizations](#performance-optimizations)
- [SEO & Accessibility](#seo--accessibility)
- [Multi-Device Optimization](#multi-device-optimization)
- [Development Guide](#development-guide)
- [Contributing](#contributing)
- [License](#license)

---

## Overview

**Horizon** is a premium multi-purpose Shopify 2.0 theme engineered for performance, flexibility, and visual excellence. Built with a modular architecture — 21 design token systems, a comprehensive skeleton loading UI, per-section positioning/visibility controls, and a powerful token override engine — Horizon adapts seamlessly to any store type: fashion, electronics, home goods, digital products, subscriptions, and more.

### Key Features

| Feature | Description |
|---------|-------------|
| 🎨 **21 Design Token Systems** | Full typography, color, spacing, animations, grid, shadows, and more — all CSS-custom-property driven |
| ⚡ **Skeleton UI Loader** | Integrated shimmer-placeholder loading in 22+ sections for instant perceived performance |
| 📐 **X/Y Positioning Engine** | Per-block and per-section horizontal (left/center/right) and vertical (top/middle/bottom) positioning with separate mobile/desktop values |
| 👁️ **Visibility Toggles** | Show/hide blocks and sections independently on mobile and desktop — 46+ sections supported |
| 🔧 **Section Token Override** | Every section can independently override 30+ design tokens (colors, fonts, spacing, radii, shadows, animations) |
| 📱 **Fully Responsive** | Optimized for mobile, tablet, laptop, desktop, and ultra-wide displays |
| 🏗️ **Shopify 2.0 Architecture** | JSON templates, `@theme` blocks, sections group system, flex PDP |
| 🚀 **Performance Optimized** | Module import maps, deferred JS, `content-visibility: auto`, CSS `contain`, preloads |
| ♿ **WCAG Accessible** | Skip-to-content links, proper ARIA, keyboard navigation, focus management |
| 🔍 **SEO Optimized** | JSON-LD structured data, OG/Twitter cards, canonical URLs, sitemap support |
| 🌐 **Multi-Language & Multi-Currency** | Full Shopify Markets support with country/language selectors |
| 🧩 **30+ Modular JS Modules** | ES module architecture with import maps for efficient code splitting |
| 🎭 **View Transitions** | Smooth page transitions between products and pages |

---

## Installation

### Method 1: Shopify Theme Store (Coming Soon)

1. Purchase and download Horizon from the Shopify Theme Store
2. Go to **Online Store → Themes** in your Shopify admin
3. Click **Add theme → Upload zip file**
4. Select the downloaded `horizon.zip` file
5. Click **Publish** or preview first

### Method 2: Manual Upload

```bash
# Using Shopify CLI (v3+)
shopify theme push --store your-store.myshopify.com --theme "Horizon"

# Or upload via Shopify Admin UI
# 1. Download this repository as ZIP
# 2. Online Store → Themes → Add theme → Upload zip file
```

### Method 3: Development with Shopify CLI

```bash
# Clone the repository
git clone https://github.com/HAmmadsiamil007/PHANTOM-THEME.git

# Navigate to the theme directory
cd PHANTOM-THEME/horizon

# Launch development server
shopify theme dev --store your-store.myshopify.com

# Push to production
shopify theme push --store your-store.myshopify.com --theme "Horizon"
```

### Requirements

- **Shopify Online Store** (Basic plan or higher)
- **Shopify CLI v4.3.0+** (for development)
- Modern browser (Chrome 90+, Firefox 88+, Safari 14+, Edge 90+)

---

## Theme Architecture

```
horizon/
├── assets/              # CSS, JS, SVG, fonts, images
│   ├── base.css              # Core styles
│   ├── skeleton-ui.css       # Skeleton loader styles
│   ├── compat-utilities.css  # Compatibility helpers
│   ├── phantom-ui-enhancements.css  # UI polish layer
│   ├── skeleton-ui.js        # Skeleton loader JS
│   ├── utilities.js          # Core utilities module
│   ├── component.js          # Web component base
│   ├── header.js             # Header component
│   ├── ... 30+ JS modules
├── blocks/              # @theme block definitions (Flex PDP)
│   ├── _section-flex-pdp-*.liquid  # 14 Flex PDP blocks
├── config/              # Theme configuration
│   ├── settings_schema.json    # Theme settings schema
│   ├── settings_data.json      # Default settings data
├── layout/              # Theme layout
│   ├── theme.liquid           # Main layout template
├── locales/             # Translation files
│   ├── en.default.json        # English translations
│   ├── en.default.schema.json # Schema translations
├── sections/            # Section files
│   ├── header.liquid          # Header section
│   ├── footer.liquid          # Footer section
│   ├── main-product.liquid    # Product page
│   ├── main-collection.liquid # Collection page
│   ├── ... 50+ sections
├── snippets/            # Reusable Liquid snippets
│   ├── design-tokens.liquid   # Master token loader
│   ├── skeleton-ui.liquid     # Skeleton renderer
│   ├── block-positioning.liquid # Positioning engine
│   ├── section-visibility.liquid # Visibility toggles
│   ├── section-tokens.liquid  # Token override engine
│   ├── tokens-*.liquid        # 21 token system files
│   ├── ... 80+ snippets
├── templates/           # Template files
│   ├── index.json             # Homepage
│   ├── product.json           # Product default
│   ├── collection.json        # Collection default
│   ├── page.json              # Page default
│   ├── blog.json              # Blog listing
│   ├── article.json           # Article page
│   ├── cart.json              # Cart page
│   ├── search.json            # Search results
│   └── customers/             # Customer account templates
```

---

## Design Token System (21 Foundation Systems)

Horizon's design language is built on 21 modular token systems, each rendered as CSS custom properties on `:root`. This enables consistent, maintainable, and overridable design across every component.

| # | Token System | File | CSS Variables |
|---|-------------|------|---------------|
| 1 | **Typography Scale** | `tokens-typography-scale.liquid` | `--font-size-h1` through `--font-size-h6` |
| 2 | **Font Pairing System** | `tokens-font-pairing.liquid` | `--font-heading--family`, `--font-body--family`, etc. |
| 3 | **Color Palette System** | `color-palette.liquid` | `--color-background`, `--color-foreground` |
| 4 | **Semantic Color Tokens** | `tokens-semantic-colors.liquid` | `--text-primary`, `--surface-secondary`, `--color-success`, etc. |
| 5 | **Spacing Scale** | `tokens-spacing.liquid` | `--space-1` through `--space-40` (4px base) |
| 6 | **Border Radius Scale** | `tokens-border-radius.liquid` | `--radius-none` through `--radius-full` |
| 7 | **Shadow System** | `tokens-shadows.liquid` | `--shadow-sm`, `--shadow-md`, `--shadow-lg`, `--shadow-xl` |
| 8 | **Border System** | `tokens-borders.liquid` | Border styles, widths, colors |
| 9 | **Opacity Tokens** | `tokens-opacity.liquid` | `--opacity-8` through `--opacity-90` |
| 10 | **Z-Index Layers** | `tokens-z-index.liquid` | `--layer-base` through `--layer-modal` |
| 11 | **Animation Tokens** | `tokens-animations.liquid` | `--animation-speed-fast`, `--ease-out-cubic`, etc. |
| 12 | **Transition Tokens** | `tokens-transitions.liquid` | `--transition-base`, `--transition-smooth` |
| 13 | **Grid System** | `tokens-grid.liquid` | `--grid-columns`, `--grid-gap` |
| 14 | **Breakpoints** | `tokens-breakpoints.liquid` | `--bp-xs` through `--bp-3xl`, `--is-mobile`, `--is-desktop` |
| 15 | **Container Widths** | `tokens-container-widths.liquid` | `--page-width-narrow`, `--page-width-wide` |
| 16 | **Icon System** | `tokens-icons.liquid` | `--icon-size-xs` through `--icon-size-xl` |
| 17 | **Illustration System** | `tokens-illustrations.liquid` | Illustration sizing and styles |
| 18 | **Image Ratio System** | `tokens-image-ratios.liquid` | `--ratio-square`, `--ratio-portrait`, `--ratio-landscape` |
| 19 | **Elevation System** | `tokens-elevation.liquid` | `--elevation-hover-offset`, `--elevation-hover-scale` |
| 20 | **Theme Presets** | `tokens-theme-presets.liquid` | Density, contrast, shape, and radius presets |
| 21 | **Padding & Margin** | `tokens-padding-margin.liquid` | `--padding-xs` through `--padding-3xl` |

### Usage

Tokens are automatically loaded via the master snippet in `theme.liquid`:

```liquid
{% render 'design-tokens' %}
```

To use a token in your CSS:

```css
.my-component {
  color: var(--text-primary);
  padding: var(--space-4);
  border-radius: var(--radius-lg);
  box-shadow: var(--shadow-md);
  animation: var(--animation-speed) var(--ease-out-cubic);
}
```

---

## Section & Block Systems

### Available Sections (50+)

| Section | Description | Skeleton? | Positioning? | Visibility? |
|---------|-------------|:---------:|:------------:|:-----------:|
| **Header** | Full-featured header with logo, nav, search, localization, cart | ✓ | ✓ | ✓ |
| **Footer** | Multi-block footer (groups, menus, newsletter, payment icons) | ✓ | ✓ | ✓ |
| **Slideshow/Hero** | Full-width hero carousel with slides | ✓ | ✓ | ✓ |
| **Featured Products** | Product grid from a collection | ✓ | ✓ | ✓ |
| **Featured Collections** | Collection cards grid | ✓ | ✓ | ✓ |
| **Rich Text** | Rich text with headings, buttons | ✓ | ✓ | ✓ |
| **Text & Image** | Side-by-side media + text layout | ✓ | ✓ | ✓ |
| **Text Columns** | Grid of icon/text/button blocks | ✓ | ✓ | ✓ |
| **Testimonials** | Customer testimonial slider | ✓ | ✓ | ✓ |
| **Blog Posts** | Recent blog posts grid | ✓ | ✓ | ✓ |
| **Logo List** | Brand partner logo bar | ✓ | ✓ | ✓ |
| **Newsletter** | Email signup with form | ✓ | ✓ | ✓ |
| **Video** | Video embed (YouTube/Vimeo) | ✓ | ✓ | ✓ |
| **Map** | Google Maps with address | ✓ | ✓ | ✓ |
| **FAQ** | Accordion FAQ with JSON-LD | ✓ | ✓ | ✓ |
| **Contact Form** | Name, email, phone, message | ✓ | ✓ | ✓ |
| **Countdown Timer** | Configurable end date timer | ✓ | ✓ | ✓ |
| **Hotspots** | Interactive image with pins | ✓ | ✓ | ✓ |
| **Image Compare** | Before/after slider | ✓ | ✓ | ✓ |
| **Scrolling Text** | Horizontal marquee | ✓ | ✓ | ✓ |
| **Main Product** | Standard product detail page | ✓ | ✓ | ✓ |
| **Main Collection** | Product grid + sidebar filters | ✓ | ✓ | ✓ |
| **Main Search** | Search results with filters | ✓ | ✓ | ✓ |
| **Main Blog** | Blog article listing | ✓ | ✓ | ✓ |
| **Main Article** | Full article page | ✓ | ✓ | ✓ |
| **Main Page** | Standard content page | ✓ | ✓ | ✓ |
| **Main Cart** | Full cart page | ✓ | ✓ | ✓ |
| **Product Recommendations** | AI product recommendations | ✓ | ✓ | ✓ |
| **Recently Viewed** | Client-side recently viewed | ✓ | ✓ | ✓ |
| **Advanced Content** | Flexible content grid (liquid, image, video, product) | ✓ | ✓ | ✓ |
| **Newsletter Popup** | Modal popup with reminder | ✓ | ✓ | ✓ |
| **Gift Card** | Gift card page header | ✓ | ✓ | ✓ |
| **Password** | Password-protected page | ✓ | ✓ | ✓ |

### Block System

Sections support various block types that can be added/reordered in the Theme Editor:

- **Text blocks** — headings, paragraphs, rich text
- **Image blocks** — single images, galleries, banners
- **Button blocks** — primary/secondary buttons with links
- **Icon blocks** — 25+ SVG icons for text-with-icons
- **Menu blocks** — navigation menus
- **Social links** — social media icon links
- **Email signup** — newsletter subscription forms
- **Payment icons** — accepted payment method icons
- **Logo block** — store logo
- **Video blocks** — YouTube/Vimeo embeds
- **Product blocks** — featured product cards
- **Collection blocks** — collection cards
- **Group blocks** — nested block containers with background, overlay, positioning

---

## Header System

The header is a highly configurable multi-row system with:

### Features
- **4 layout presets**: Logo center/menu left, Logo left/menu left, Logo center/menu center, Logo center/split nav
- **Sticky modes**: Always, scroll-up, never
- **Transparent header** per page type (home, product, collection)
- **Configurable rows**: Top row + bottom row (menu, search, localization)
- **3 cart icon styles**: Cart, bag, bag-minimal
- **Icon/text display mode** for header actions
- **Mega menu** with collection images
- **Dropdown localization** with country flags
- **Toolbar** with secondary menu + social icons
- **Mobile slide-out drawer** with nested menus

### Settings
```json
- Logo position, height (desktop + mobile)
- Menu position, row placement
- Icon stroke weight (thin, default, heavy)
- Search icon toggle, position, row
- Country/language selectors with flags
- Sticky behavior
- Section width (page/full)
- Section height (compact/standard)
- Border width, divider width
- Custom colors per row
- Cart bubble custom colors
- Transparent header per page type
- Design token overrides (colors, fonts, radius)
- Mobile/desktop visibility toggle
```

---

## Footer System

A modern, flexible footer with responsive grid layout:

### Features
- **Dynamic grid** — automatically adjusts columns based on added blocks (1-4)
- **Smart orphan handling** — last-row single items span full width on desktop
- **14 block types**: divider, app, button, follow-on-shop, group, icon, image, menu, payment-icons, text, logo, jumbo-text, social-links, email-signup
- **Column separators** — subtle vertical dividers between columns on desktop
- **Responsive** — stacks vertically on mobile with border separators
- **Section token overrides** — per-section color, font, and radius customization
- **Visibility toggles** — hide on mobile/desktop

---

## Skeleton UI Loading System

Horizon's skeleton loader provides shimmer-animated content placeholders for async-loaded sections, eliminating "content jump" and improving perceived performance.

### How It Works

```html
<div data-skeleton-wrapper>
  <div data-skeleton-content>
    {# Real content — visible by default (server-rendered) #}
  </div>
  <div data-skeleton-placeholder>
    {% render 'skeleton-ui', type: 'product-card', count: 4 %}
  </div>
</div>
```

Server-rendered content is immediately visible. The skeleton activates only during async section loads (e.g., Shopify Section Rendering API).

### Skeleton Types

| Type | Description | Columns Support |
|------|-------------|:---------------:|
| `product-card` | Full product card with image + info | ✓ |
| `collection-card` | Collection card with overlay text | ✓ |
| `hero` | Wide hero/banner with heading + text + button | ✗ |
| `blog-card` | Blog article card with image + meta | ✓ |
| `cart-item` | Cart drawer line item | ✓ |
| `cart-summary` | Cart totals + checkout button | ✗ |
| `card` | Generic card (image + text) | ✓ |
| `text-block` | Heading + 3 text lines | ✗ |
| `text` / `paragraph` | Single text line | ✓ |
| `heading` | Single heading line | ✗ |
| `button` | Button placeholder | ✗ |
| `badge` | Badge placeholder | ✗ |
| `price` | Price placeholder | ✗ |
| `image` | Image with configurable aspect ratio | ✗ |

### Skeleton Aspect Ratios

| Variant | Ratio | Usage |
|---------|-------|-------|
| `square` | 1:1 | Product thumbnails |
| `portrait` | 3:4 | Product cards |
| `landscape` | 16:9 | Blog cards, hero |
| `wide` | 21:9 | Hero banners |

### Integrated Sections

- Hero carousel
- Featured products
- Featured collections
- Product recommendations
- Blog listings & headers
- Collection products & headers
- Search results
- Cart drawer
- Testimonials
- Rich text
- Media with text
- FAQ accordion
- Contact forms
- Newsletter
- Page content
- Card sliders & lists
- Article pages
- Product pages

---

## Positioning & Visibility Engine

### X/Y Positioning

Every block and section can be independently positioned:

```liquid
{% render 'block-positioning', settings: block.settings, block_id: block.id %}
```

**Desktop Settings:**
- **X Position**: Left (`flex-start`), Center, Right (`flex-end`)
- **Y Position**: Top (`flex-start`), Middle (`center`), Bottom (`flex-end`)

**Mobile Settings:**
- **X Position**: Inherit (same as desktop), Left, Center, Right
- **Y Position**: Inherit (same as desktop), Top, Middle, Bottom

Positioning is output as scoped CSS custom properties with responsive media queries.

### Visibility Toggles

Every section supports:
- **Hide on mobile** (`< 750px`)
- **Hide on desktop** (`≥ 750px`)

Applied in 46+ sections via `section-visibility.liquid`:
```liquid
{% render 'section-visibility', settings: section.settings, section_id: section.id %}
```

---

## Section Token Override Engine

Each section can override **30+ design tokens** independently, giving merchants granular control without affecting global styles.

### Overridable Tokens

| Category | Tokens |
|----------|--------|
| **Colors** | Text, background, primary, secondary, accent, heading, body, link, border, success, error, warning, surface |
| **Typography** | Heading font, body font, accent font, base size, heading scale, letter spacing, line height |
| **Spacing** | Spacing scale, section gap, content gap, padding |
| **Borders** | Border radius, border width, border opacity, card radius |
| **Interactions** | Button radius, input radius |
| **Shadows** | Shadow size, shadow opacity |
| **Animations** | Anim duration, anim easing, anim delay |
| **Layout** | Container width, content max width |
| **Icons** | Icon size, icon stroke |
| **States** | Muted opacity, disabled opacity |

### Usage in Sections

```liquid
{% render 'section-tokens', settings: section.settings, section_id: section.id %}
```

Outputs scoped CSS:
```css
#shopify-section-123 {
  --section-text-color: #333;
  --section-bg-color: #f8f8f8;
  --section-border-radius: 12px;
  --section-anim-duration: 0.3s;
}
```

---

## JavaScript Architecture

Horizon uses a modern ES module architecture with import maps for efficient loading.

### Module System

```html
<script type="importmap">
{
  "imports": {
    "@theme/utilities": "{{ 'utilities.js' | asset_url }}",
    "@theme/component": "{{ 'component.js' | asset_url }}",
    "@theme/section-renderer": "{{ 'section-renderer.js' | asset_url }}",
    "@theme/section-hydration": "{{ 'section-hydration.js' | asset_url }}",
    "@theme/product-form": "{{ 'product-form.js' | asset_url }}",
    "@theme/variant-picker": "{{ 'variant-picker.js' | asset_url }}",
    "@theme/media-gallery": "{{ 'media-gallery.js' | asset_url }}",
    "@theme/quick-add": "{{ 'quick-add.js' | asset_url }}",
    "@theme/morph": "{{ 'morph.js' | asset_url }}",
    "@theme/events": "{{ 'events.js' | asset_url }}",
    "@theme/dialog": "{{ 'dialog.js' | asset_url }}",
    "@theme/focus": "{{ 'focus.js' | asset_url }}",
    "@theme/overflow-list": "{{ 'overflow-list.js' | asset_url }}",
    "@theme/scrolling": "{{ 'scrolling.js' | asset_url }}",
    "@theme/performance": "{{ 'performance.js' | asset_url }}",
    "@theme/fly-to-cart": "{{ 'fly-to-cart.js' | asset_url }}",
    "@theme/sticky-add-to-cart": "{{ 'sticky-add-to-cart.js' | asset_url }}",
    "@theme/scroll-container": "{{ 'scroll-container.js' | asset_url }}",
    "@theme/theme-drawer": "{{ 'theme-drawer.js' | asset_url }}",
    "@theme/comparison-slider": "{{ 'comparison-slider.js' | asset_url }}",
    "@theme/money-formatting": "{{ 'money-formatting.js' | asset_url }}",
    "@theme/recently-viewed-products": "{{ 'recently-viewed-products.js' | asset_url }}",
    "@theme/paginated-list": "{{ 'paginated-list.js' | asset_url }}",
    "@theme/popover-polyfill": "{{ 'popover-polyfill.js' | asset_url }}",
    "@theme/view-event-elements": "{{ 'view-event-elements.js' | asset_url }}",
    "@shopify/events": "https://cdn.shopify.com/storefront/standard-events.js"
  }
}
</script>
```

### Loading Strategy

| Strategy | Modules |
|----------|---------|
| **Render-blocking** | View transitions (when enabled) |
| **Eager (`type="module"`)** | Overflow list, dialog, variant picker, product card, product form, quick add, fly-to-cart, accordion, media, price, sku, inventory, slideshow, localization |
| **Deferred** | Page view event, auto-close-details |
| **Module preload** | Utilities, component, section-renderer, morph, focus, recently-viewed, scrolling, events |
| **Conditional** | Cart discount (if enabled), sticky add-to-cart (product pages), paginated-list (collection/search) |
| **Fallback scripts** | mobile-touch.js, offcanvas.js, navbar-scroll.js, skeleton-ui.js |

### Key JavaScript Features

- **Web Components** via `is-land` lazy loading pattern
- **Section hydration** for dynamic content loading
- **View transitions** API for smooth page navigation
- **Cart drawer** with AJAX add/remove/update
- **Variant picker** with image swapping
- **Media gallery** with zoom and lightbox
- **Quick add** for product cards
- **Predictive search** with keyboard navigation
- **Recently viewed** products (localStorage)
- **Slideshow** with autoplay and touch support
- **Localization** switcher (country/language)
- **Product form** with gift card recipient support
- **Accordion** for FAQ and product info
- **Price per item** and volume pricing
- **Fly-to-cart** animation

---

## CSS & Styling

### Stylesheet Architecture

| File | Type | Purpose |
|------|------|---------|
| `base.css` | Core | Reset, typography, grid, forms, buttons, utilities |
| `skeleton-ui.css` | UI | Skeleton loader shimmer animations and layouts |
| `compat-utilities.css` | Compat | Browser compatibility fixes |
| `phantom-ui-enhancements.css` | Polish | Visual polish and refinement layer |

### Design Principles

- **CSS Custom Properties** — All design tokens exposed as CSS variables
- **`content-visibility: auto`** — Lazy render off-screen sections
- **CSS `contain`** — Contain paint/layout to improve rendering
- **Logical Properties** — `padding-inline`, `margin-block` for RTL support
- **Mobile-first** — Base styles target mobile, `@media (min-width: ...)` for larger screens
- **`prefers-reduced-motion`** — Respects user motion preferences

---

## Settings & Customization

### Theme Settings Categories

| Category | Settings |
|----------|----------|
| **Logo & Favicon** | Logo image (default + inverse), logo height (desktop/mobile), favicon |
| **Colors** | Color palette picker (background + foreground) |
| **Typography** | 4 font families (body, subheading, heading, accent), H1-H6 size/line-height/letter-spacing/case, paragraph size/line-height |
| **Page Layout** | Page background color, page width (narrow/normal/wide) |
| **Animations** | Page transitions, product transitions, add-to-cart animation, card hover effect (none/lift/scale/subtle-zoom) |
| **Badges** | Position, corner radius, sale/sold-out colors, font, text case |
| **Buttons** | Primary + secondary: background, text, border colors + width, border radius, font, text case; pills border radius |
| **Cart** | Type (page/drawer), auto-open, note, discount code, installments, express checkout, thumbnail border/radius |
| **Drawers** | Background, text, border colors |
| **Icons** | Stroke weight |
| **Input Fields** | Background, text, border colors, border width, radius, type preset |
| **Popovers & Modals** | Background, text, border colors, radius, shadow toggle |
| **Prices** | Currency code display per context (product pages, cards, cart items, cart total) |
| **Product Cards** | Quick add toggle (desktop + mobile), hover image, card carousel |
| **Search** | Empty state collection, predictive search card radius |
| **Swatches** | Variant image toggle, width, height, radius, border style/width/opacity |
| **Variant Pickers** | Default + selected colors, border width, radius, button width |

---

## Performance Optimizations

| Optimization | Implementation |
|-------------|----------------|
| **CSS `content-visibility: auto`** | Footer, off-screen sections — defer rendering |
| **CSS `contain`** | Header, sections — isolate paint/layout |
| **Module preloads** | Critical JS modules preloaded with `fetchpriority="low"` |
| **Deferred scripts** | Non-critical JS deferred |
| **Import maps** | Efficient ES module loading with named imports |
| **Server-rendered content** | No client-side rendering — content is immediately visible |
| **Skeleton UI** | Instant perceived loading with shimmer placeholders |
| **Font subsetting** | Google Fonts loaded with optimal weights/subsets |
| **Image optimization** | Responsive images with `srcset`, WebP/AVIF via Shopify CDN |
| **Lazy loading** | Off-screen images `loading="lazy"`, intersection observer |
| **View transitions** | Smooth page transitions with render blocking |
| **Header height inline script** | Prevent layout shift from header calculation |
| **Reduced motion support** | Respects `prefers-reduced-motion` |

---

## SEO & Accessibility

### SEO Features

- **JSON-LD structured data**: Organization, Product, Article, FAQ, BreadcrumbList
- **Open Graph tags**: OG title, description, image, type, url
- **Twitter Cards**: Summary card with image
- **Canonical URLs**: Prevent duplicate content
- **Semantic HTML**: `<header>`, `<main>`, `<footer>`, `<nav>`, `<article>`
- **Heading hierarchy**: Proper H1-H6 usage
- **Alt text**: On all product and content images
- **Sitemap**: Shopify auto-generates XML sitemap
- **Meta descriptions**: Customizable per page
- **Schema.org**: Organization schema in header

### Accessibility Features (WCAG 2.1 AA)

- **Skip-to-content link**: Keyboard accessible
- **ARIA labels**: On interactive elements, navigation, search, cart
- **Focus management**: Modal/drawer focus trapping, return focus
- **Keyboard navigation**: Full menu, search, product form, slideshow
- **Color contrast**: WCAG AA compliant ratios
- **Screen reader support**: aria-expanded, aria-controls, aria-live regions
- **Reduced motion**: `prefers-reduced-motion` respected
- **Form labels**: All inputs have associated labels
- **Error messaging**: Form validation with clear error messages

---

## Multi-Device Optimization

| Breakpoint | Name | Target |
|------------|------|--------|
| `0-374px` | XS | Small phones |
| `375-575px` | SM | Large phones |
| `576-749px` | MD | Phablets |
| `750-989px` | LG | Tablets portrait |
| `990-1199px` | XL | Tablets landscape / small desktop |
| `1200-1399px` | 2XL | Desktop |
| `1400-1599px` | 3XL | Wide desktop |
| `1600px+` | Ultra | Ultra-wide displays |

### Mobile-Specific Features

- Touch-optimized navigation with drawer
- Bottom-aligned cart icon for thumb reach
- Mobile search drawer
- Quick add with mobile variant picker
- Sticky add-to-cart on product pages
- Responsive grid (2 columns mobile, 4+ desktop)
- Swipeable slideshows and carousels
- Touch-friendly form inputs (larger tap targets)
- Off-canvas filters on collection pages

---

## Development Guide

### Prerequisites

```bash
# Install Shopify CLI
npm install -g @shopify/cli @shopify/theme

# Or via Homebrew (macOS)
brew install shopify-cli

# Verify installation
shopify --version
# Expected: Shopify CLI v4.3.0+
```

### Development Workflow

```bash
# 1. Clone the repo
git clone https://github.com/HAmmadsiamil007/PHANTOM-THEME.git
cd PHANTOM-THEME/horizon

# 2. Start development server (auto-syncing)
shopify theme dev --store your-store.myshopify.com

# 3. Make changes to files — they auto-sync
# 4. Push to production when ready
shopify theme push --store your-store.myshopify.com --theme "Horizon"

# 5. Run theme validation
shopify theme check
```

### Project Structure Conventions

- **Sections** — One file per section with `{% schema %}` at the bottom
- **Snippets** — Reusable Liquid partials, documented with `{% doc %}`
- **Assets** — CSS, JS, SVG icons, images
- **Tokens** — Naming: `tokens-{category}.liquid`
- **Translations** — All user-facing strings in `locales/en.default.json`

### Adding a New Section

1. Create `sections/my-section.liquid` with `{% schema %}` definition
2. Add translation keys to `locales/en.default.schema.json`
3. Register in `templates/*.json` if needed
4. Add skeleton support (optional but recommended):
   ```liquid
   <div data-skeleton-wrapper>
     <div data-skeleton-content> ... </div>
     <div data-skeleton-placeholder>
       {% render 'skeleton-ui', type: 'product-card', count: 4 %}
     </div>
   </div>
   ```
5. Add positioning + visibility:
   ```liquid
   {% render 'block-positioning', settings: block.settings, block_id: block.id %}
   {% render 'section-visibility', settings: section.settings, section_id: section.id %}
   ```

---

## Contributing

1. Fork the repository
2. Create a feature branch: `git checkout -b feature/my-feature`
3. Make your changes
4. Run validation: `shopify theme check`
5. Commit with conventional commit messages:
   - `feat:` — New feature
   - `fix:` — Bug fix
   - `perf:` — Performance improvement
   - `a11y:` — Accessibility fix
   - `style:` — CSS/styling changes
   - `docs:` — Documentation
6. Push and create a pull request

### Code Style

- **Liquid**: Follow Shopify Liquid best practices, use `{%- -%}` for whitespace control
- **CSS**: Logical properties, custom properties, mobile-first
- **JS**: ES modules, `const`/`let`, no `var`, camelCase
- **Translations**: All strings in locale files, no hardcoded text

---

## License

**Horizon Theme** — Proprietary License

All rights reserved. This theme is licensed for use on a single Shopify store. Redistribution, resale, or modification for redistribution is prohibited.

---

## Support

- **Developer**: [GitHub Repository](https://github.com/HAmmadsiamil007/PHANTOM-THEME)
- **Documentation**: [help.shopify.com/manual/online-store/themes](https://help.shopify.com/manual/online-store/themes)
- **Shopify Support**: [support.shopify.com](https://support.shopify.com/)

---

*Built with ❤️ by Hammad Ismail — Premium Shopify Theme Development*
