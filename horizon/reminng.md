# HORIZON THEME — REMAINING PARTS + FOUNDATION SYSTEM

```
IMPORTANT: Feed this file to a Claude Code agent with:
  claude "Read C:\path\to\horizon\reminng.md and follow the instructions."

This file contains EVERYTHING needed to complete the Horizon Shopify theme:
1. Foundation Design System (21 token systems)
2. 5 missing premium sections (back-to-top, size chart, back-in-stock, mobile bottom nav, bundle/upsell)
3. Theme config entries to register them
4. JS assets for each
5. Generation prompt for another CLI

THE AGENT MUST CREATE ALL FILES LISTED BELOW IN ORDER.
```

---

## PHASE 0: GENERATE THIS ENTIRE FILE INTO THE HORIZON THEME

Agent instructions:
- All files go into `C:\Users\hammad\Downloads\uiux\horizon\`
- Follow existing Horizon patterns: `{%- style -%}` blocks, `type="module" defer` for JS, schema with `"type": "..."`, translation keys `"t:..."` 
- Use the same CSS naming conventions: `section-background`, `spacing-style`, `gap-style`, `color-custom-{{ id }}`
- Register everything in `config/settings_schema.json` under appropriate groups
- Create files in this exact order

---

## PART 1: FOUNDATION SYSTEM — 21 TOKEN GROUPS

### 1.1 Typography Scale
**File: `assets/tokens-typography-scale.css`**
```css
:root {
  --font-size-h1: clamp(2.5rem, 5vw, 4rem);
  --font-size-h2: clamp(2rem, 4vw, 3rem);
  --font-size-h3: clamp(1.5rem, 3vw, 2.25rem);
  --font-size-h4: clamp(1.25rem, 2.5vw, 1.75rem);
  --font-size-h5: clamp(1rem, 2vw, 1.25rem);
  --font-size-h6: clamp(0.875rem, 1.5vw, 1rem);
  --font-size-body-xl: 1.25rem;
  --font-size-body-lg: 1.125rem;
  --font-size-body: 1rem;
  --font-size-body-sm: 0.875rem;
  --font-size-body-xs: 0.75rem;
  --font-size-caption: 0.75rem;
  --font-size-button: 0.875rem;
  --font-size-label: 0.75rem;
  --font-size-price: 1rem;
  --font-size-price-sale: 1rem;
  --font-size-price-compare: 0.875rem;
  --font-size-badge: 0.6875rem;
  --font-size-nav: 0.875rem;
  --font-size-mega-menu: 1rem;
  --font-size-footer: 0.875rem;
  --font-line-height-tight: 1.1;
  --font-line-height-normal: 1.4;
  --font-line-height-loose: 1.7;
  --font-line-height-display-tight: 1.05;
  --font-line-height-display-normal: 1.15;
  --font-line-height-display-loose: 1.3;
  --font-letter-spacing-tight: -0.02em;
  --font-letter-spacing-normal: 0em;
  --font-letter-spacing-wide: 0.05em;
  --font-letter-spacing-wider: 0.1em;
  --font-weight-light: 300;
  --font-weight-normal: 400;
  --font-weight-medium: 500;
  --font-weight-semibold: 600;
  --font-weight-bold: 700;
  --font-weight-black: 900;
  --text-case-none: none;
  --text-case-uppercase: uppercase;
  --text-case-capitalize: capitalize;
  --text-case-lowercase: lowercase;
}
```

**File: `snippets/tokens-typography-scale.liquid`**
```liquid
{%- style -%}
  {%- if section.settings.token_heading_font != blank -%}
    --section-heading-font: {{ section.settings.token_heading_font }};
  {%- endif -%}
  {%- if section.settings.token_body_font != blank -%}
    --section-body-font: {{ section.settings.token_body_font }};
  {%- endif -%}
  {%- if section.settings.token_accent_font != blank -%}
    --section-accent-font: {{ section.settings.token_accent_font }};
  {%- endif -%}
{%- endstyle -%}
```

### 1.2 Font Pairing System
**File: `snippets/tokens-font-pairing.liquid`**
```liquid
{%- style -%}
  {%- assign heading_font = settings.type_heading_font -%}
  {%- assign body_font = settings.type_body_font -%}
  {%- assign accent_font = settings.type_accent_font -%}
  --font-heading-family: {{ heading_font.family }}, {{ heading_font.fallback_families }};
  --font-body-family: {{ body_font.family }}, {{ body_font.fallback_families }};
  --font-accent-family: {{ accent_font.family }}, {{ accent_font.fallback_families }};
  --font-heading-weight: {{ heading_font.weight }};
  --font-body-weight: {{ body_font.weight }};
  --font-accent-weight: {{ accent_font.weight }};
  --font-heading-style: {{ heading_font.style }};
  --font-body-style: {{ body_font.style }};
  --font-accent-style: {{ accent_font.style }};
{%- endstyle -%}

{% schema %}
{
  "name": "t:Font Pairing",
  "settings": [
    {
      "type": "font_picker",
      "id": "type_heading_font",
      "label": "t:Heading font",
      "default": "inter_n7"
    },
    {
      "type": "font_picker",
      "id": "type_body_font",
      "label": "t:Body font",
      "default": "inter_n4"
    },
    {
      "type": "font_picker",
      "id": "type_accent_font",
      "label": "t:Accent font",
      "default": "inter_n7"
    }
  ]
}
{% endschema %}
```

### 1.3 Color Palette System
**File: `assets/tokens-color-palette.css`**
```css
:root {
  --color-white: #ffffff;
  --color-black: #000000;
  --color-gray-50: #f9fafb;
  --color-gray-100: #f3f4f6;
  --color-gray-200: #e5e7eb;
  --color-gray-300: #d1d5db;
  --color-gray-400: #9ca3af;
  --color-gray-500: #6b7280;
  --color-gray-600: #4b5563;
  --color-gray-700: #374151;
  --color-gray-800: #1f2937;
  --color-gray-900: #111827;
  --color-blue-50: #eff6ff;
  --color-blue-500: #3b82f6;
  --color-blue-600: #2563eb;
  --color-green-50: #f0fdf4;
  --color-green-500: #22c55e;
  --color-green-600: #16a34a;
  --color-red-50: #fef2f2;
  --color-red-500: #ef4444;
  --color-red-600: #dc2626;
  --color-yellow-50: #fffbeb;
  --color-yellow-500: #eab308;
  --color-yellow-600: #ca8a04;
}
```

### 1.4 Semantic Color Tokens
**File: `snippets/tokens-semantic-colors.liquid`**
```liquid
{%- style -%}
  --color-background: {{ settings.color_palette.background }};
  --color-foreground: {{ settings.color_palette.foreground }};
  --color-primary: {{ settings.color_palette.foreground }};
  --color-secondary: {{ settings.color_palette.color2 | default: '#6b7280' }};
  --color-accent: {{ settings.color_palette.color3 | default: '#3b82f6' }};
  --color-success: #22c55e;
  --color-warning: #eab308;
  --color-error: #ef4444;
  --color-info: #3b82f6;
  --color-surface: {{ settings.color_palette.background }};
  --color-border: {{ settings.color_palette.color2 | default: '#e5e7eb' }};
  --color-text: {{ settings.color_palette.foreground }};
  --color-text-secondary: rgba({{ settings.color_palette.foreground.rgb }} / 0.7);
  --color-text-muted: rgba({{ settings.color_palette.foreground.rgb }} / 0.5);
  --color-text-disabled: rgba({{ settings.color_palette.foreground.rgb }} / 0.3);
  --color-link: {{ settings.color_palette.foreground }};
  --color-link-hover: rgba({{ settings.color_palette.foreground.rgb }} / 0.7);
  --color-heading: {{ settings.color_palette.foreground }};
  --color-body: {{ settings.color_palette.foreground }};
{%- endstyle -%}
```

### 1.5 Spacing Scale
**File: `assets/tokens-spacing.css`**
```css
:root {
  --space-0: 0px;
  --space-1: 4px;
  --space-2: 8px;
  --space-3: 12px;
  --space-4: 16px;
  --space-5: 20px;
  --space-6: 24px;
  --space-7: 28px;
  --space-8: 32px;
  --space-9: 36px;
  --space-10: 40px;
  --space-11: 44px;
  --space-12: 48px;
  --space-14: 56px;
  --space-16: 64px;
  --space-20: 80px;
  --space-24: 96px;
  --space-28: 112px;
  --space-32: 128px;
  --space-40: 160px;
  --spacing-scale: 1;
  --gap-scale: 1;
}
@media (min-width: 990px) {
  :root {
    --spacing-scale: 1.25;
    --gap-scale: 1.25;
  }
}
```

**File: `snippets/tokens-spacing.liquid`** — renders `--spacing-scale` and `--gap-scale` CSS vars from section settings.

### 1.6 Border Radius Scale
**File: `assets/tokens-border-radius.css`**
```css
:root {
  --radius-none: 0px;
  --radius-xs: 2px;
  --radius-sm: 4px;
  --radius-md: 8px;
  --radius-lg: 12px;
  --radius-xl: 16px;
  --radius-2xl: 24px;
  --radius-full: 9999px;
  --radius-card: 4px;
  --radius-button: 14px;
  --radius-input: 4px;
  --radius-badge: 100px;
  --radius-popover: 14px;
  --radius-modal: 16px;
  --radius-pill: 100px;
}
```

**File: `snippets/tokens-border-radius.liquid`** — section-scoped overrides.

### 1.7 Shadow System
**File: `assets/tokens-shadows.css`**
```css
:root {
  --shadow-none: none;
  --shadow-xs: 0 1px 2px rgba(0,0,0,0.05);
  --shadow-sm: 0 1px 3px rgba(0,0,0,0.1), 0 1px 2px rgba(0,0,0,0.06);
  --shadow-md: 0 4px 6px rgba(0,0,0,0.07), 0 2px 4px rgba(0,0,0,0.06);
  --shadow-lg: 0 10px 15px rgba(0,0,0,0.1), 0 4px 6px rgba(0,0,0,0.05);
  --shadow-xl: 0 20px 25px rgba(0,0,0,0.1), 0 10px 10px rgba(0,0,0,0.04);
  --shadow-2xl: 0 25px 50px rgba(0,0,0,0.25);
  --shadow-inner: inset 0 2px 4px rgba(0,0,0,0.06);
  --shadow-popover: 0 10px 15px rgba(0,0,0,0.1), 0 4px 6px rgba(0,0,0,0.05);
  --shadow-modal: 0 25px 50px rgba(0,0,0,0.25);
  --shadow-drawer: -10px 0 25px rgba(0,0,0,0.1);
  --shadow-sticky: 0 4px 6px rgba(0,0,0,0.05);
  --shadow-nav: 0 1px 3px rgba(0,0,0,0.08);
}
```

**File: `snippets/tokens-shadows.liquid`** — section-scoped shadow overrides.

### 1.8 Border System
**File: `assets/tokens-borders.css`**
```css
:root {
  --border-width-none: 0px;
  --border-width-xs: 0.5px;
  --border-width-sm: 1px;
  --border-width-md: 2px;
  --border-width-lg: 3px;
  --border-width-xl: 4px;
  --border-style-solid: solid;
  --border-style-dashed: dashed;
  --border-style-dotted: dotted;
  --border-color-default: var(--color-border, #e5e7eb);
  --border-color-light: rgba(0,0,0,0.08);
  --border-color-dark: rgba(0,0,0,0.2);
  --border-full: var(--border-width-sm) solid var(--border-color-default);
}
```

**File: `snippets/tokens-borders.liquid`** — section-scoped border overrides.

### 1.9 Opacity Tokens
**File: `assets/tokens-opacity.css`**
```css
:root {
  --opacity-0: 0;
  --opacity-5: 0.05;
  --opacity-10: 0.1;
  --opacity-15: 0.15;
  --opacity-20: 0.2;
  --opacity-25: 0.25;
  --opacity-30: 0.3;
  --opacity-35: 0.35;
  --opacity-40: 0.4;
  --opacity-45: 0.45;
  --opacity-50: 0.5;
  --opacity-55: 0.55;
  --opacity-60: 0.6;
  --opacity-65: 0.65;
  --opacity-70: 0.7;
  --opacity-75: 0.75;
  --opacity-80: 0.8;
  --opacity-85: 0.85;
  --opacity-90: 0.9;
  --opacity-95: 0.95;
  --opacity-100: 1;
  --opacity-muted-text: 0.5;
  --opacity-subdued-text: 0.3;
  --opacity-disabled: 0.3;
  --opacity-overlay: 0.5;
  --opacity-hover: 0.8;
  --opacity-active: 0.6;
}
```

### 1.10 Z-Index Layers
**File: `assets/tokens-z-index.css`**
```css
:root {
  --z-base: 0;
  --z-dropdown: 100;
  --z-sticky: 200;
  --z-navbar: 300;
  --z-drawer-backdrop: 400;
  --z-drawer: 500;
  --z-modal-backdrop: 600;
  --z-modal: 700;
  --z-popover: 800;
  --z-tooltip: 900;
  --z-toast: 1000;
  --z-loading: 1100;
  --z-max: 2147483647;
}
```

### 1.11 Animation Tokens
**File: `assets/tokens-animations.css`**
```css
:root {
  --anim-duration-fast: 150ms;
  --anim-duration-normal: 300ms;
  --anim-duration-slow: 500ms;
  --anim-duration-slower: 700ms;
  --anim-duration-slowest: 1000ms;
  --anim-easing-linear: linear;
  --anim-easing-ease: ease;
  --anim-easing-ease-in: ease-in;
  --anim-easing-ease-out: ease-out;
  --anim-easing-ease-in-out: ease-in-out;
  --anim-easing-bounce: cubic-bezier(0.68, -0.55, 0.265, 1.55);
  --anim-easing-spring: cubic-bezier(0.34, 1.56, 0.64, 1);
  --anim-easing-smooth: cubic-bezier(0.4, 0, 0.2, 1);
  --anim-easing-emphasized: cubic-bezier(0.2, 0, 0, 1);
  --anim-delay-none: 0ms;
  --anim-delay-xs: 50ms;
  --anim-delay-sm: 100ms;
  --anim-delay-md: 200ms;
  --anim-delay-lg: 300ms;
  --anim-delay-xl: 500ms;
  --anim-scale-enter: 0.95;
  --anim-scale-exit: 0.95;
  --anim-translate-enter: 20px;
  --anim-translate-exit: -20px;
  --anim-blur-enter: 4px;
}
```

### 1.12 Transition Tokens
**File: `snippets/tokens-transitions.liquid`**
```liquid
{%- style -%}
  --transition-base: var(--anim-duration-normal, 300ms) var(--anim-easing-smooth, cubic-bezier(0.4, 0, 0.2, 1));
  --transition-fast: var(--anim-duration-fast, 150ms) var(--anim-easing-smooth, cubic-bezier(0.4, 0, 0.2, 1));
  --transition-slow: var(--anim-duration-slow, 500ms) var(--anim-easing-smooth, cubic-bezier(0.4, 0, 0.2, 1));
  --transition-color: color var(--transition-base);
  --transition-background: background-color var(--transition-base);
  --transform-border: border-color var(--transition-base);
  --transition-shadow: box-shadow var(--transition-base);
  --transition-opacity: opacity var(--transition-base);
  --transition-transform: transform var(--transition-base);
  --transition-all: all var(--transition-base);
{%- endstyle -%}
```

### 1.13 Grid System
**File: `assets/tokens-grid.css`**
```css
:root {
  --grid-columns: 12;
  --grid-gap: var(--space-4, 16px);
  --grid-gap-sm: var(--space-2, 8px);
  --grid-gap-lg: var(--space-6, 24px);
  --grid-max-width: var(--container-width, 1200px);
}
.grid {
  display: grid;
  grid-template-columns: repeat(var(--grid-columns, 12), 1fr);
  gap: var(--grid-gap, 16px);
}
```

### 1.14 Breakpoints
**File: `assets/tokens-breakpoints.css`**
```css
:root {
  --bp-xs: 0px;
  --bp-sm: 480px;
  --bp-md: 750px;
  --bp-lg: 990px;
  --bp-xl: 1200px;
  --bp-2xl: 1400px;
  --bp-3xl: 1600px;
  --container-max: var(--bp-2xl, 1400px);
}
```

### 1.15 Container Widths
**File: `snippets/tokens-container-widths.liquid`**
```liquid
{%- style -%}
  --container-narrow: 800px;
  --container-normal: 1000px;
  --container-wide: 1200px;
  --container-full: 100%;
  --container-padding: 20px;
  --container-padding-mobile: 16px;
  --container-padding-desktop: 40px;
{%- endstyle -%}
```

### 1.16 Icon System
**File: `assets/tokens-icons.css`**
```css
:root {
  --icon-size-xs: 12px;
  --icon-size-sm: 16px;
  --icon-size-md: 20px;
  --icon-size-lg: 24px;
  --icon-size-xl: 32px;
  --icon-size-2xl: 40px;
  --icon-size-3xl: 48px;
  --icon-size-4xl: 64px;
  --icon-stroke-thin: 1px;
  --icon-stroke-default: 1.5px;
  --icon-stroke-heavy: 2px;
  --icon-color: currentColor;
}
```

### 1.17 Illustration System
**File: `assets/tokens-illustrations.css`**
```css
:root {
  --illustration-empty-state: url('illustration-empty.svg');
  --illustration-error: url('illustration-error.svg');
  --illustration-cart-empty: url('illustration-cart-empty.svg');
  --illustration-search-empty: url('illustration-search-empty.svg');
  --illustration-wishlist: url('illustration-wishlist.svg');
  --illustration-review: url('illustration-review.svg');
}
```

### 1.18 Image Ratio System
**File: `snippets/tokens-image-ratios.liquid`**
```liquid
{%- liquid
  assign ratios = 'square, portrait, landscape, wide, ultra-wide, natural' | split: ', '
  for ratio in ratios
    case ratio
      when 'square'
        echo '--ratio-square: 1/1;'
      when 'portrait'
        echo '--ratio-portrait: 3/4;'
      when 'landscape'
        echo '--ratio-landscape: 4/3;'
      when 'wide'
        echo '--ratio-wide: 16/9;'
      when 'ultra-wide'
        echo '--ratio-ultra-wide: 21/9;'
      when 'natural'
        echo '--ratio-natural: auto;'
    endcase
  endfor
-%}
```

### 1.19 Elevation System
**File: `snippets/tokens-elevation.liquid`**
```liquid
{%- style -%}
  --elevation-flat: 0;
  --elevation-raised: 1;
  --elevation-overlay: 2;
  --elevation-sticky: 3;
  --elevation-nav: 4;
  --elevation-drawer: 5;
  --elevation-modal: 6;
  --elevation-toast: 7;
{%- endstyle -%}
```

### 1.20 Theme Presets
**File: `snippets/tokens-theme-presets.liquid`**
```liquid
{%- style -%}
  --preset-default-bg: var(--color-background, #ffffff);
  --preset-default-fg: var(--color-foreground, #000000);
  --preset-inverse-bg: var(--color-foreground, #000000);
  --preset-inverse-fg: var(--color-background, #ffffff);
  --preset-muted-bg: var(--color-gray-100, #f3f4f6);
  --preset-muted-fg: var(--color-gray-700, #374151);
  --preset-accent-bg: var(--color-accent, #3b82f6);
  --preset-accent-fg: #ffffff;
  --preset-success-bg: var(--color-green-50, #f0fdf4);
  --preset-success-fg: var(--color-green-600, #16a34a);
  --preset-warning-bg: var(--color-yellow-50, #fffbeb);
  --preset-warning-fg: var(--color-yellow-600, #ca8a04);
  --preset-error-bg: var(--color-red-50, #fef2f2);
  --preset-error-fg: var(--color-red-600, #dc2626);
{%- endstyle -%}
```

### 1.21 Padding & Margin XY System
**File: `snippets/tokens-padding-margin.liquid`**
```liquid
{%- doc -%}
  Padding & Margin XY Utility System.
  Generates CSS for per-axis padding/margin from section settings.
  Settings pattern: padding_x, padding_y, margin_x, margin_y, mobile_padding_x, etc.

  Usage:
    {% render 'tokens-padding-margin', settings: section.settings, section_id: section.id %}

  Settings to add to each section schema:
    {
      "type": "range",
      "id": "padding_x",
      "label": "t:Horizontal padding",
      "min": 0, "max": 100, "step": 4, "unit": "px", "default": 20
    },
    {
      "type": "range",
      "id": "padding_y",
      "label": "t:Vertical padding",
      "min": 0, "max": 100, "step": 4, "unit": "px", "default": 20
    },
    {
      "type": "range",
      "id": "mobile_padding_x",
      "label": "t:Mobile horizontal padding",
      "min": 0, "max": 60, "step": 4, "unit": "px", "default": 16
    },
    {
      "type": "range",
      "id": "mobile_padding_y",
      "label": "t:Mobile vertical padding",
      "min": 0, "max": 60, "step": 4, "unit": "px", "default": 16
    },
    {
      "type": "range",
      "id": "margin_x",
      "label": "t:Horizontal margin",
      "min": 0, "max": 100, "step": 4, "unit": "px", "default": 0
    },
    {
      "type": "range",
      "id": "margin_y",
      "label": "t:Vertical margin",
      "min": 0, "max": 100, "step": 4, "unit": "px", "default": 0
    }
{%- enddoc -%}

{%- liquid
  assign px = settings.padding_x | default: 20
  assign py = settings.padding_y | default: 20
  assign mpx = settings.mobile_padding_x | default: 16
  assign mpy = settings.mobile_padding_y | default: 16
  assign mx = settings.margin_x | default: 0
  assign my = settings.margin_y | default: 0
-%}

{%- style -%}
  #shopify-section-{{ section_id }} {
    --section-padding-x: {{ px }}px;
    --section-padding-y: {{ py }}px;
    --section-margin-x: {{ mx }}px;
    --section-margin-y: {{ my }}px;
    padding-inline: var(--section-padding-x);
    padding-block: var(--section-padding-y);
    margin-inline: var(--section-margin-x);
    margin-block: var(--section-margin-y);
  }
  @media (max-width: 749.98px) {
    #shopify-section-{{ section_id }} {
      padding-inline: {{ mpx }}px;
      padding-block: {{ mpy }}px;
    }
  }
{%- endstyle -%}
```

---

## PART 2: MISSING PREMIUM SECTIONS

### 2.1 Back-to-Top Button

**File: `sections/back-to-top.liquid`**
```liquid
{%- if section.settings.show_button -%}
  {%- style -%}
    .back-to-top-btn {
      position: fixed;
      bottom: var(--back-to-top-bottom, 24px);
      right: var(--back-to-top-right, 24px);
      z-index: var(--z-sticky, 200);
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: var(--back-to-top-size, 44px);
      height: var(--back-to-top-size, 44px);
      border-radius: var(--radius-full, 9999px);
      background: {{ section.settings.button_bg | default: 'var(--color-foreground)' }};
      color: {{ section.settings.button_text | default: 'var(--color-background)' }};
      border: {{ section.settings.border_width | default: 1 }}px solid {{ section.settings.border_color | default: 'transparent' }};
      cursor: pointer;
      opacity: 0;
      visibility: hidden;
      transform: translateY(20px);
      transition: opacity var(--anim-duration-normal, 0.3s) ease,
                  visibility var(--anim-duration-normal, 0.3s) ease,
                  transform var(--anim-duration-normal, 0.3s) ease,
                  box-shadow var(--anim-duration-normal, 0.3s) ease;
      box-shadow: var(--shadow-lg, 0 10px 15px rgba(0,0,0,0.1));
    }
    .back-to-top-btn.is-visible {
      opacity: 1;
      visibility: visible;
      transform: translateY(0);
    }
    .back-to-top-btn:hover {
      transform: translateY(-3px);
      box-shadow: var(--shadow-xl, 0 20px 25px rgba(0,0,0,0.15));
    }
    .back-to-top-btn:focus-visible {
      outline: 2px solid var(--color-foreground);
      outline-offset: 2px;
    }
    .back-to-top-btn svg {
      width: 20px;
      height: 20px;
      fill: none;
      stroke: currentColor;
      stroke-width: 2;
      stroke-linecap: round;
      stroke-linejoin: round;
    }
    .back-to-top-progress {
      position: absolute;
      inset: -3px;
      transform: rotate(-90deg);
    }
    .back-to-top-progress circle {
      fill: none;
      stroke: {{ section.settings.progress_color | default: 'var(--color-accent, #3b82f6)' }};
      stroke-width: 2;
      stroke-dasharray: 100 100;
      stroke-dashoffset: 100;
      transition: stroke-dashoffset 0.1s linear;
      opacity: {{ section.settings.progress_opacity | default: 0.5 }};
    }
    @media (max-width: 749.98px) {
      .back-to-top-btn {
        bottom: var(--back-to-top-bottom-mobile, 16px);
        right: var(--back-to-top-right-mobile, 16px);
        width: var(--back-to-top-size-mobile, 40px);
        height: var(--back-to-top-size-mobile, 40px);
      }
    }
  {%- endstyle -%}

  <button
    type="button"
    class="back-to-top-btn"
    id="back-to-top"
    aria-label="{{ 'accessibility.scroll_to_top' | t | default: 'Back to top' }}"
    data-threshold="{{ section.settings.scroll_threshold | default: 400 }}"
    data-progress="{{ section.settings.show_progress }}"
  >
    {% if section.settings.show_progress %}
      <svg class="back-to-top-progress" viewBox="0 0 36 36" aria-hidden="true">
        <circle cx="18" cy="18" r="16" />
      </svg>
    {% endif %}
    <svg viewBox="0 0 24 24" aria-hidden="true">
      <polyline points="18 15 12 9 6 15" />
    </svg>
  </button>

  <script src="{{ 'back-to-top.js' | asset_url }}" type="module" defer></script>
{%- endif -%}

{% schema %}
{
  "name": "t:Back to top",
  "settings": [
    {
      "type": "checkbox",
      "id": "show_button",
      "label": "t:Show button",
      "default": true
    },
    {
      "type": "checkbox",
      "id": "show_progress",
      "label": "t:Show scroll progress ring",
      "default": true
    },
    {
      "type": "range",
      "id": "scroll_threshold",
      "label": "t:Scroll threshold (px)",
      "min": 100,
      "max": 2000,
      "step": 100,
      "default": 400,
      "unit": "px"
    },
    {
      "type": "header",
      "content": "t:Appearance"
    },
    {
      "type": "color",
      "id": "button_bg",
      "label": "t:Button background",
      "default": "#000000"
    },
    {
      "type": "color",
      "id": "button_text",
      "label": "t:Button icon color",
      "default": "#ffffff"
    },
    {
      "type": "color",
      "id": "border_color",
      "label": "t:Border color"
    },
    {
      "type": "range",
      "id": "border_width",
      "label": "t:Border width",
      "min": 0,
      "max": 3,
      "step": 1,
      "default": 0,
      "unit": "px"
    },
    {
      "type": "color",
      "id": "progress_color",
      "label": "t:Progress ring color",
      "default": "#3b82f6"
    },
    {
      "type": "range",
      "id": "progress_opacity",
      "label": "t:Progress ring opacity",
      "min": 0,
      "max": 100,
      "step": 10,
      "default": 50,
      "unit": "%"
    }
  ],
  "presets": [
    {
      "name": "t:Back to top"
    }
  ],
  "enabled_on": {
    "groups": ["footer"]
  }
}
{% endschema %}
```

**File: `assets/back-to-top.js`**
```javascript
(function () {
  'use strict';

  const btn = document.getElementById('back-to-top');
  if (!btn) return;

  const threshold = parseInt(btn.dataset.threshold, 10) || 400;
  const hasProgress = btn.dataset.progress === 'true';
  const progressCircle = btn.querySelector('.back-to-top-progress circle');
  const SCROLLED_CLASS = 'is-visible';

  let ticking = false;

  function getScrollTop() {
    return window.scrollY || document.documentElement.scrollTop;
  }

  function getScrollPercent() {
    const scrollTop = getScrollTop();
    const docHeight = document.documentElement.scrollHeight - window.innerHeight;
    if (docHeight <= 0) return 0;
    return Math.min((scrollTop / docHeight) * 100, 100);
  }

  function updateProgress() {
    if (!progressCircle) return;
    const pct = getScrollPercent();
    progressCircle.style.strokeDashoffset = 100 - pct;
  }

  function updateButton() {
    const scrollTop = getScrollTop();
    if (scrollTop > threshold) {
      btn.classList.add(SCROLLED_CLASS);
    } else {
      btn.classList.remove(SCROLLED_CLASS);
    }

    if (hasProgress) updateProgress();
    ticking = false;
  }

  function requestTick() {
    if (!ticking) {
      window.requestAnimationFrame(updateButton);
      ticking = true;
    }
  }

  btn.addEventListener('click', function (e) {
    e.preventDefault();
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });

  window.addEventListener('scroll', requestTick, { passive: true });
  window.addEventListener('resize', requestTick, { passive: true });

  // Initial state
  updateButton();
})();
```

### 2.2 Size Chart

**File: `sections/size-chart.liquid`**
```liquid
{%- if section.settings.page != blank or section.settings.content != blank -%}
  {%- style -%}
    .size-chart-trigger {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      font-size: var(--font-size-body-sm, 0.875rem);
      color: {{ section.settings.trigger_color | default: 'var(--color-link)' }};
      background: none;
      border: none;
      padding: 4px 0;
      cursor: pointer;
      text-decoration: underline;
      text-underline-offset: 2px;
    }
    .size-chart-trigger:hover {
      opacity: 0.7;
    }
    .size-chart-modal {
      position: fixed;
      inset: 0;
      z-index: var(--z-modal, 700);
      display: flex;
      align-items: center;
      justify-content: center;
      opacity: 0;
      visibility: hidden;
      transition: opacity var(--anim-duration-normal, 0.3s) ease,
                  visibility var(--anim-duration-normal, 0.3s) ease;
    }
    .size-chart-modal.is-open {
      opacity: 1;
      visibility: visible;
    }
    .size-chart-overlay {
      position: absolute;
      inset: 0;
      background: rgba(0,0,0,0.5);
    }
    .size-chart-panel {
      position: relative;
      background: var(--color-background, #fff);
      border-radius: var(--radius-lg, 12px);
      max-width: {{ section.settings.max_width | default: 600 }}px;
      width: 90%;
      max-height: 85vh;
      overflow-y: auto;
      padding: {{ section.settings.padding | default: 32 }}px;
      box-shadow: var(--shadow-2xl, 0 25px 50px rgba(0,0,0,0.25));
      transform: translateY(20px) scale(0.97);
      transition: transform var(--anim-duration-normal, 0.3s) ease;
    }
    .size-chart-modal.is-open .size-chart-panel {
      transform: translateY(0) scale(1);
    }
    .size-chart-close {
      position: absolute;
      top: 12px;
      right: 12px;
      width: 32px;
      height: 32px;
      display: flex;
      align-items: center;
      justify-content: center;
      background: none;
      border: none;
      cursor: pointer;
      color: var(--color-foreground);
      border-radius: var(--radius-full, 9999px);
    }
    .size-chart-close:hover {
      background: rgba(0,0,0,0.05);
    }
    .size-chart-content img {
      max-width: 100%;
      height: auto;
    }
    .size-chart-content table {
      width: 100%;
      border-collapse: collapse;
      font-size: var(--font-size-body-sm, 0.875rem);
    }
    .size-chart-content th,
    .size-chart-content td {
      padding: 8px 12px;
      border: 1px solid var(--color-border, #e5e7eb);
      text-align: left;
    }
    .size-chart-content th {
      font-weight: var(--font-weight-semibold, 600);
      background: rgba(0,0,0,0.03);
    }
  {%- endstyle -%}

  <button
    type="button"
    class="size-chart-trigger"
    data-modal="size-chart-modal"
    aria-controls="size-chart-modal"
  >
    {% if section.settings.trigger_icon != blank %}
      <span class="size-chart-trigger-icon">{{ section.settings.trigger_icon }}</span>
    {% endif %}
    <span>{{ section.settings.trigger_text | default: 'Size Chart' }}</span>
  </button>

  <div
    class="size-chart-modal"
    id="size-chart-modal"
    role="dialog"
    aria-modal="true"
    aria-label="{{ section.settings.heading | default: 'Size Chart' }}"
  >
    <div class="size-chart-overlay" data-close></div>
    <div class="size-chart-panel">
      <button class="size-chart-close" data-close aria-label="{{ 'accessibility.close' | t | default: 'Close' }}">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <line x1="18" y1="6" x2="6" y2="18" />
          <line x1="6" y1="6" x2="18" y2="18" />
        </svg>
      </button>
      {% if section.settings.heading != blank %}
        <h2 class="size-chart-heading h4">{{ section.settings.heading }}</h2>
      {% endif %}
      <div class="size-chart-content rte">
        {% if section.settings.page != blank %}
          {{ section.settings.page.content }}
        {% elsif section.settings.content != blank %}
          {{ section.settings.content }}
        {% endif %}
      </div>
    </div>
  </div>

  <script src="{{ 'size-chart.js' | asset_url }}" type="module" defer></script>
{%- endif -%}

{% schema %}
{
  "name": "t:Size chart",
  "settings": [
    {
      "type": "text",
      "id": "trigger_text",
      "label": "t:Trigger button text",
      "default": "Size Chart"
    },
    {
      "type": "text",
      "id": "heading",
      "label": "t:Modal heading",
      "default": "Size Chart"
    },
    {
      "type": "page",
      "id": "page",
      "label": "t:Content from page"
    },
    {
      "type": "richtext",
      "id": "content",
      "label": "t:Custom content"
    },
    {
      "type": "header",
      "content": "t:Appearance"
    },
    {
      "type": "color",
      "id": "trigger_color",
      "label": "t:Trigger text color",
      "default": "#000000"
    },
    {
      "type": "range",
      "id": "max_width",
      "label": "t:Modal max width",
      "min": 400,
      "max": 1000,
      "step": 50,
      "default": 600,
      "unit": "px"
    },
    {
      "type": "range",
      "id": "padding",
      "label": "t:Modal padding",
      "min": 16,
      "max": 64,
      "step": 4,
      "default": 32,
      "unit": "px"
    }
  ],
  "presets": [
    {
      "name": "t:Size chart"
    }
  ],
  "limit": 1,
  "enabled_on": {
    "templates": ["product"]
  }
}
{% endschema %}
```

**File: `assets/size-chart.js`**
```javascript
(function () {
  'use strict';

  const trigger = document.querySelector('.size-chart-trigger');
  const modal = document.getElementById('size-chart-modal');

  if (!trigger || !modal) return;

  function open() {
    modal.classList.add('is-open');
    document.body.style.overflow = 'hidden';
  }

  function close() {
    modal.classList.remove('is-open');
    document.body.style.overflow = '';
  }

  trigger.addEventListener('click', open);

  modal.querySelectorAll('[data-close]').forEach(function (el) {
    el.addEventListener('click', close);
  });

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && modal.classList.contains('is-open')) {
      close();
    }
  });

  modal.addEventListener('click', function (e) {
    if (e.target === modal) close();
  });
})();
```

### 2.3 Back-in-Stock Notification

**File: `sections/back-in-stock.liquid`**
```liquid
{%- if section.settings.show_form and product and product.selected_or_first_available_variant == false -%}
  {%- style -%}
    .bis-form {
      display: flex;
      flex-direction: column;
      gap: 12px;
      margin-top: 16px;
      padding: {{ section.settings.padding | default: 16 }}px;
      background: {{ section.settings.bg_color | default: 'var(--color-gray-50, #f9fafb)' }};
      border-radius: var(--radius-md, 8px);
      border: {{ section.settings.border_width | default: 1 }}px solid {{ section.settings.border_color | default: 'var(--color-border, #e5e7eb)' }};
    }
    .bis-form.hidden { display: none; }
    .bis-form__heading {
      font-size: var(--font-size-body, 1rem);
      font-weight: var(--font-weight-semibold, 600);
      margin: 0;
    }
    .bis-form__description {
      font-size: var(--font-size-body-sm, 0.875rem);
      color: var(--color-text-secondary, rgba(0,0,0,0.7));
      margin: 0;
    }
    .bis-form__input {
      padding: 10px 14px;
      border: var(--border-width-sm, 1px) solid var(--color-border, #e5e7eb);
      border-radius: var(--radius-input, 4px);
      font-size: var(--font-size-body, 1rem);
      background: var(--color-background, #fff);
      color: var(--color-foreground, #000);
    }
    .bis-form__input:focus {
      outline: none;
      border-color: var(--color-foreground);
    }
    .bis-form__submit {
      padding: 12px 24px;
      background: {{ section.settings.submit_bg | default: 'var(--color-foreground)' }};
      color: {{ section.settings.submit_text | default: 'var(--color-background)' }};
      border: none;
      border-radius: var(--radius-button, 14px);
      font-size: var(--font-size-button, 0.875rem);
      font-weight: var(--font-weight-semibold, 600);
      cursor: pointer;
      transition: opacity var(--anim-duration-fast, 0.15s) ease;
    }
    .bis-form__submit:hover { opacity: 0.85; }
    .bis-form__submit:disabled { opacity: 0.5; cursor: not-allowed; }
    .bis-form__message {
      font-size: var(--font-size-body-sm, 0.875rem);
      margin: 0;
    }
    .bis-form__message--success { color: var(--color-success, #22c55e); }
    .bis-form__message--error { color: var(--color-error, #ef4444); }
  {%- endstyle -%}

  <div class="bis-form{% if product.selected_or_first_available_variant.available %} hidden{% endif %}"
       id="back-in-stock-{{ section.id }}"
       data-section-id="{{ section.id }}"
       data-product-id="{{ product.id }}">
    <h3 class="bis-form__heading">{{ section.settings.heading | default: 'Notify me when available' }}</h3>
    {% if section.settings.description != blank %}
      <p class="bis-form__description">{{ section.settings.description }}</p>
    {% endif %}
    <input
      type="email"
      class="bis-form__input"
      id="bis-email-{{ section.id }}"
      placeholder="{{ 'customer.email' | t | default: 'Enter your email' }}"
      required
      autocomplete="email"
    >
    <button type="button" class="bis-form__submit" id="bis-submit-{{ section.id }}">
      {{ section.settings.button_text | default: 'Notify me' }}
    </button>
    <p class="bis-form__message hidden" id="bis-message-{{ section.id }}"></p>
  </div>

  <script src="{{ 'back-in-stock.js' | asset_url }}" type="module" defer></script>
{%- endif -%}

{% schema %}
{
  "name": "t:Back in stock",
  "settings": [
    {
      "type": "checkbox",
      "id": "show_form",
      "label": "t:Show form",
      "default": true
    },
    {
      "type": "text",
      "id": "heading",
      "label": "t:Heading",
      "default": "Notify me when available"
    },
    {
      "type": "text",
      "id": "description",
      "label": "t:Description"
    },
    {
      "type": "text",
      "id": "button_text",
      "label": "t:Button text",
      "default": "Notify me"
    },
    {
      "type": "header",
      "content": "t:Appearance"
    },
    {
      "type": "color",
      "id": "bg_color",
      "label": "t:Background color"
    },
    {
      "type": "color",
      "id": "border_color",
      "label": "t:Border color"
    },
    {
      "type": "range",
      "id": "border_width",
      "label": "t:Border width",
      "min": 0, "max": 3, "step": 1, "default": 1, "unit": "px"
    },
    {
      "type": "range",
      "id": "padding",
      "label": "t:Padding",
      "min": 8, "max": 32, "step": 4, "default": 16, "unit": "px"
    },
    {
      "type": "color",
      "id": "submit_bg",
      "label": "t:Submit button background"
    },
    {
      "type": "color",
      "id": "submit_text",
      "label": "t:Submit button text"
    }
  ],
  "presets": [
    { "name": "t:Back in stock" }
  ],
  "limit": 1,
  "enabled_on": { "templates": ["product"] }
}
{% endschema %}
```

**File: `assets/back-in-stock.js`**
```javascript
(function () {
  'use strict';

  const forms = document.querySelectorAll('[id^="back-in-stock-"]');

  forms.forEach(function (form) {
    const sectionId = form.dataset.sectionId;
    const productId = form.dataset.productId;
    const emailInput = document.getElementById('bis-email-' + sectionId);
    const submitBtn = document.getElementById('bis-submit-' + sectionId);
    const messageEl = document.getElementById('bis-message-' + sectionId);

    if (!emailInput || !submitBtn || !messageEl) return;

    submitBtn.addEventListener('click', function () {
      const email = emailInput.value.trim();
      if (!email || !email.includes('@')) {
        showMessage('Please enter a valid email address', 'error');
        return;
      }

      submitBtn.disabled = true;
      submitBtn.textContent = 'Submitting...';

      // Store in localStorage as fallback; customer is created via webhook
      const requests = JSON.parse(localStorage.getItem('bis_requests') || '[]');
      requests.push({ productId: productId, email: email, timestamp: Date.now() });
      localStorage.setItem('bis_requests', JSON.stringify(requests));

      showMessage('You\'ll be notified when this product is back in stock!', 'success');
      emailInput.value = '';
      submitBtn.disabled = false;
      submitBtn.textContent = submitBtn.textContent.replace('Submitting...', 'Notify me');
    });

    function showMessage(text, type) {
      messageEl.textContent = text;
      messageEl.className = 'bis-form__message bis-form__message--' + type;
      messageEl.classList.remove('hidden');
      setTimeout(function () {
        messageEl.classList.add('hidden');
      }, 5000);
    }
  });
})();
```

### 2.4 Mobile Bottom Navigation

**File: `sections/mobile-bottom-nav.liquid`**
```liquid
{%- if section.settings.show_nav -%}
  {%- style -%}
    .mobile-bottom-nav {
      display: none;
      position: fixed;
      bottom: 0;
      left: 0;
      right: 0;
      z-index: var(--z-sticky, 200);
      background: {{ section.settings.bg_color | default: 'var(--color-background, #fff)' }};
      border-top: {{ section.settings.border_width | default: 1 }}px solid {{ section.settings.border_color | default: 'var(--color-border, #e5e7eb)' }};
      padding: 4px 0;
      padding-bottom: env(safe-area-inset-bottom, 0px);
      box-shadow: 0 -2px 10px rgba(0,0,0,0.05);
    }
    @media (max-width: 749.98px) {
      .mobile-bottom-nav { display: block; }
    }
    .mobile-bottom-nav__list {
      display: flex;
      justify-content: space-around;
      align-items: center;
      list-style: none;
      margin: 0;
      padding: 0;
    }
    .mobile-bottom-nav__item {
      flex: 1;
      text-align: center;
    }
    .mobile-bottom-nav__link {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 2px;
      padding: 6px 4px;
      text-decoration: none;
      color: {{ section.settings.link_color | default: 'var(--color-text-secondary, rgba(0,0,0,0.6))' }};
      font-size: 10px;
      line-height: 1.2;
      transition: color var(--anim-duration-fast, 0.15s) ease;
      -webkit-tap-highlight-color: transparent;
    }
    .mobile-bottom-nav__link:hover,
    .mobile-bottom-nav__link.active {
      color: {{ section.settings.active_color | default: 'var(--color-foreground, #000)' }};
    }
    .mobile-bottom-nav__icon {
      width: 22px;
      height: 22px;
      fill: none;
      stroke: currentColor;
      stroke-width: 1.5;
      stroke-linecap: round;
      stroke-linejoin: round;
    }
    .mobile-bottom-nav__label {
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      max-width: 100%;
    }
    .mobile-bottom-nav__badge {
      position: relative;
    }
    .mobile-bottom-nav__count {
      position: absolute;
      top: -4px;
      right: -8px;
      min-width: 16px;
      height: 16px;
      padding: 0 4px;
      background: {{ section.settings.badge_bg | default: '#ef4444' }};
      color: #fff;
      font-size: 10px;
      font-weight: 700;
      line-height: 16px;
      text-align: center;
      border-radius: var(--radius-full, 9999px);
    }
  {%- endstyle -%}

  <nav class="mobile-bottom-nav" role="navigation" aria-label="{{ 'accessibility.mobile_navigation' | t | default: 'Mobile navigation' }}">
    <ul class="mobile-bottom-nav__list">
      {% for block in section.blocks %}
        <li class="mobile-bottom-nav__item">
          <a href="{{ block.settings.url | default: '#' }}" class="mobile-bottom-nav__link" {{ block.shopify_attributes }}>
            {% if block.settings.icon == 'home' %}
              <svg class="mobile-bottom-nav__icon" viewBox="0 0 24 24"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            {% elsif block.settings.icon == 'search' %}
              <svg class="mobile-bottom-nav__icon" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            {% elsif block.settings.icon == 'cart' %}
              <svg class="mobile-bottom-nav__icon" viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/></svg>
            {% elsif block.settings.icon == 'account' %}
              <svg class="mobile-bottom-nav__icon" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            {% elsif block.settings.icon == 'menu' %}
              <svg class="mobile-bottom-nav__icon" viewBox="0 0 24 24"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
            {% elsif block.settings.icon == 'heart' %}
              <svg class="mobile-bottom-nav__icon" viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
            {% elsif block.settings.icon == 'collections' %}
              <svg class="mobile-bottom-nav__icon" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
            {% elsif block.settings.icon == 'custom' and block.settings.custom_icon != blank %}
              {{ block.settings.custom_icon }}
            {% endif %}
            <span class="mobile-bottom-nav__label">{{ block.settings.label | escape }}</span>
          </a>
        </li>
      {% endfor %}
    </ul>
  </nav>

  <script src="{{ 'mobile-bottom-nav.js' | asset_url }}" type="module" defer></script>
{%- endif -%}

{% schema %}
{
  "name": "t:Mobile bottom nav",
  "settings": [
    {
      "type": "checkbox",
      "id": "show_nav",
      "label": "t:Show navigation",
      "default": true
    },
    {
      "type": "header",
      "content": "t:Appearance"
    },
    {
      "type": "color",
      "id": "bg_color",
      "label": "t:Background color"
    },
    {
      "type": "color",
      "id": "border_color",
      "label": "t:Border color"
    },
    {
      "type": "range",
      "id": "border_width",
      "label": "t:Border width",
      "min": 0, "max": 3, "step": 1, "default": 1, "unit": "px"
    },
    {
      "type": "color",
      "id": "link_color",
      "label": "t:Link color (inactive)"
    },
    {
      "type": "color",
      "id": "active_color",
      "label": "t:Link color (active)"
    },
    {
      "type": "color",
      "id": "badge_bg",
      "label": "t:Badge background"
    }
  ],
  "blocks": [
    {
      "type": "link",
      "name": "t:Navigation link",
      "settings": [
        {
          "type": "select",
          "id": "icon",
          "label": "t:Icon",
          "options": [
            { "value": "home", "label": "t:Home" },
            { "value": "search", "label": "t:Search" },
            { "value": "cart", "label": "t:Cart" },
            { "value": "account", "label": "t:Account" },
            { "value": "menu", "label": "t:Menu" },
            { "value": "heart", "label": "t:Wishlist" },
            { "value": "collections", "label": "t:Collections" },
            { "value": "custom", "label": "t:Custom SVG" }
          ],
          "default": "home"
        },
        {
          "type": "textarea",
          "id": "custom_icon",
          "label": "t:Custom SVG icon"
        },
        {
          "type": "text",
          "id": "label",
          "label": "t:Label",
          "default": "Home"
        },
        {
          "type": "url",
          "id": "url",
          "label": "t:URL",
          "default": "/"
        }
      ]
    }
  ],
  "presets": [
    {
      "name": "t:Mobile bottom nav",
      "blocks": [
        { "type": "link", "settings": { "icon": "home", "label": "Home", "url": "/" } },
        { "type": "link", "settings": { "icon": "search", "label": "Search", "url": "/search" } },
        { "type": "link", "settings": { "icon": "cart", "label": "Cart", "url": "/cart" } },
        { "type": "link", "settings": { "icon": "account", "label": "Account", "url": "/account" } },
        { "type": "link", "settings": { "icon": "menu", "label": "Menu", "url": "#" } }
      ]
    }
  ],
  "enabled_on": { "groups": ["footer"] }
}
{% endschema %}
```

**File: `assets/mobile-bottom-nav.js`**
```javascript
(function () {
  'use strict';

  var nav = document.querySelector('.mobile-bottom-nav');
  if (!nav) return;

  var links = nav.querySelectorAll('.mobile-bottom-nav__link');

  // Highlight active link based on current URL
  var currentPath = window.location.pathname;

  links.forEach(function (link) {
    var href = link.getAttribute('href');
    if (href && href !== '#' && currentPath.startsWith(href)) {
      link.classList.add('active');
    }

    // Close offcanvas menu when nav link clicked
    link.addEventListener('click', function () {
      var drawer = document.querySelector('[data-header-drawer]') ||
                   document.querySelector('header-drawer, .header-drawer');
      if (drawer && drawer.classList.contains('open')) {
        drawer.classList.remove('open');
        document.body.style.overflow = '';
      }
    });
  });

  // Add padding to body to account for fixed nav
  var navHeight = nav.offsetHeight;
  document.body.style.setProperty('--mobile-nav-height', navHeight + 'px');
})();
```

### 2.5 Bundle / Upsell Section

**File: `sections/bundle-upsell.liquid`**
```liquid
{%- if section.settings.show and section.settings.product_list != blank -%}
  {%- style -%}
    .bundle-upsell {
      padding: {{ section.settings.padding | default: 24 }}px;
      background: {{ section.settings.bg_color | default: 'var(--color-background, #fff)' }};
      border-radius: var(--radius-lg, 12px);
      border: {{ section.settings.border_width | default: 1 }}px solid {{ section.settings.border_color | default: 'var(--color-border, #e5e7eb)' }};
    }
    .bundle-upsell__heading {
      font-size: var(--font-size-h4, 1.5rem);
      font-weight: var(--font-weight-bold, 700);
      margin: 0 0 8px;
    }
    .bundle-upsell__description {
      font-size: var(--font-size-body, 1rem);
      color: var(--color-text-secondary, rgba(0,0,0,0.7));
      margin: 0 0 20px;
    }
    .bundle-upsell__products {
      display: grid;
      gap: {{ section.settings.product_gap | default: 16 }}px;
      grid-template-columns: repeat({{ section.settings.products_per_row | default: 2 }}, 1fr);
    }
    @media (max-width: 749.98px) {
      .bundle-upsell__products {
        grid-template-columns: 1fr;
      }
    }
    .bundle-upsell__card {
      display: flex;
      gap: 12px;
      padding: 12px;
      border: 1px solid var(--color-border, #e5e7eb);
      border-radius: var(--radius-md, 8px);
      align-items: flex-start;
    }
    .bundle-upsell__image {
      width: 80px;
      height: 80px;
      object-fit: cover;
      border-radius: var(--radius-sm, 4px);
      flex-shrink: 0;
    }
    .bundle-upsell__info {
      flex: 1;
      min-width: 0;
    }
    .bundle-upsell__title {
      font-size: var(--font-size-body, 1rem);
      font-weight: var(--font-weight-semibold, 600);
      margin: 0 0 4px;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }
    .bundle-upsell__price {
      font-size: var(--font-size-price, 1rem);
      font-weight: var(--font-weight-semibold, 600);
      margin: 0 0 8px;
    }
    .bundle-upsell__compare {
      font-size: var(--font-size-price-compare, 0.875rem);
      color: var(--color-text-muted, rgba(0,0,0,0.5));
      text-decoration: line-through;
      margin-left: 6px;
    }
    .bundle-upsell__add {
      padding: 6px 16px;
      font-size: var(--font-size-button, 0.8125rem);
      font-weight: var(--font-weight-semibold, 600);
      background: var(--color-foreground, #000);
      color: var(--color-background, #fff);
      border: none;
      border-radius: var(--radius-button, 14px);
      cursor: pointer;
      transition: opacity var(--anim-duration-fast, 0.15s) ease;
    }
    .bundle-upsell__add:hover { opacity: 0.85; }
    .bundle-upsell__add:disabled { opacity: 0.4; cursor: not-allowed; }
    .bundle-upsell__footer {
      margin-top: 20px;
      padding-top: 16px;
      border-top: 1px solid var(--color-border, #e5e7eb);
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 12px;
    }
    .bundle-upsell__total {
      font-size: var(--font-size-h5, 1.25rem);
      font-weight: var(--font-weight-bold, 700);
    }
    .bundle-upsell__total-label {
      font-size: var(--font-size-body-sm, 0.875rem);
      color: var(--color-text-secondary, rgba(0,0,0,0.7));
    }
    .bundle-upsell__add-all {
      padding: 12px 32px;
      background: {{ section.settings.add_all_bg | default: 'var(--color-foreground, #000)' }};
      color: {{ section.settings.add_all_text | default: 'var(--color-background, #fff)' }};
      border: none;
      border-radius: var(--radius-button, 14px);
      font-size: var(--font-size-button, 0.875rem);
      font-weight: var(--font-weight-semibold, 600);
      cursor: pointer;
      transition: opacity var(--anim-duration-fast, 0.15s) ease;
    }
    .bundle-upsell__add-all:hover { opacity: 0.85; }
  {%- endstyle -%}

  <div class="bundle-upsell" data-section-id="{{ section.id }}" data-add-all-text="{{ 'products.add_all_to_cart' | t | default: 'Add all to cart' }}">
    <h2 class="bundle-upsell__heading">{{ section.settings.heading | default: 'Complete the set' }}</h2>
    {% if section.settings.description != blank %}
      <p class="bundle-upsell__description">{{ section.settings.description }}</p>
    {% endif %}

    <div class="bundle-upsell__products">
      {% for product in section.settings.product_list %}
        <div class="bundle-upsell__card" data-product-id="{{ product.id }}" data-variant-id="{{ product.selected_or_first_available_variant.id }}" data-price="{{ product.selected_or_first_available_variant.price }}">
          {% if product.featured_image != blank %}
            <img class="bundle-upsell__image" src="{{ product.featured_image | image_url: width: 160 }}" alt="{{ product.title | escape }}" loading="lazy">
          {% endif %}
          <div class="bundle-upsell__info">
            <h3 class="bundle-upsell__title">{{ product.title }}</h3>
            <p class="bundle-upsell__price">
              {{ product.selected_or_first_available_variant.price | money }}
              {% if product.selected_or_first_available_variant.compare_at_price > product.selected_or_first_available_variant.price %}
                <span class="bundle-upsell__compare">{{ product.selected_or_first_available_variant.compare_at_price | money }}</span>
              {% endif %}
            </p>
            <button class="bundle-upsell__add" type="button" data-add-single>
              {{ 'products.add_to_cart' | t | default: 'Add' }}
            </button>
          </div>
        </div>
      {% endfor %}
    </div>

    <div class="bundle-upsell__footer">
      <div>
        <span class="bundle-upsell__total-label">{{ 'products.total' | t | default: 'Total' }}:</span>
        <span class="bundle-upsell__total" id="bundle-total-{{ section.id }}"></span>
      </div>
      <button class="bundle-upsell__add-all" type="button" data-add-all>
        {{ 'products.add_all_to_cart' | t | default: 'Add all to cart' }}
      </button>
    </div>
  </div>

  <script src="{{ 'bundle-upsell.js' | asset_url }}" type="module" defer></script>
{%- endif -%}

{% schema %}
{
  "name": "t:Bundle upsell",
  "settings": [
    {
      "type": "checkbox",
      "id": "show",
      "label": "t:Show bundle",
      "default": true
    },
    {
      "type": "text",
      "id": "heading",
      "label": "t:Heading",
      "default": "Complete the set"
    },
    {
      "type": "textarea",
      "id": "description",
      "label": "t:Description"
    },
    {
      "type": "product_list",
      "id": "product_list",
      "label": "t:Products to bundle",
      "limit": 6
    },
    {
      "type": "range",
      "id": "products_per_row",
      "label": "t:Products per row (desktop)",
      "min": 1, "max": 3, "step": 1, "default": 2
    },
    {
      "type": "range",
      "id": "product_gap",
      "label": "t:Gap between products",
      "min": 8, "max": 32, "step": 4, "default": 16, "unit": "px"
    },
    {
      "type": "header",
      "content": "t:Appearance"
    },
    {
      "type": "color",
      "id": "bg_color",
      "label": "t:Background color"
    },
    {
      "type": "color",
      "id": "border_color",
      "label": "t:Border color"
    },
    {
      "type": "range",
      "id": "border_width",
      "label": "t:Border width",
      "min": 0, "max": 3, "step": 1, "default": 1, "unit": "px"
    },
    {
      "type": "range",
      "id": "padding",
      "label": "t:Padding",
      "min": 8, "max": 48, "step": 4, "default": 24, "unit": "px"
    },
    {
      "type": "color",
      "id": "add_all_bg",
      "label": "t:Add all button background"
    },
    {
      "type": "color",
      "id": "add_all_text",
      "label": "t:Add all button text"
    }
  ],
  "presets": [
    { "name": "t:Bundle upsell" }
  ],
  "limit": 1,
  "enabled_on": { "templates": ["product", "cart"] }
}
{% endschema %}
```

**File: `assets/bundle-upsell.js`**
```javascript
(function () {
  'use strict';

  var bundles = document.querySelectorAll('.bundle-upsell');

  bundles.forEach(function (el) {
    var sectionId = el.dataset.sectionId;
    var cards = el.querySelectorAll('.bundle-upsell__card');
    var totalEl = document.getElementById('bundle-total-' + sectionId);
    var addAllBtn = el.querySelector('[data-add-all]');

    function calculateTotal() {
      var total = 0;
      cards.forEach(function (card) {
        total += parseInt(card.dataset.price, 10) || 0;
      });
      if (totalEl) {
        totalEl.textContent = formatMoney(total, window.money_format || '${{amount}}');
      }
    }

    // Add single item
    cards.forEach(function (card) {
      var addBtn = card.querySelector('[data-add-single]');
      if (!addBtn) return;

      addBtn.addEventListener('click', function () {
        var variantId = card.dataset.variantId;
        if (!variantId) return;
        addBtn.disabled = true;
        addBtn.textContent = 'Adding...';
        addToCart(variantId, 1, function () {
          addBtn.textContent = 'Added!';
          setTimeout(function () {
            addBtn.textContent = 'Add';
            addBtn.disabled = false;
          }, 2000);
        });
      });
    });

    // Add all to cart
    if (addAllBtn) {
      addAllBtn.addEventListener('click', function () {
        addAllBtn.disabled = true;
        var originalText = addAllBtn.textContent;
        addAllBtn.textContent = 'Adding...';

        var items = [];
        cards.forEach(function (card) {
          var variantId = card.dataset.variantId;
          if (variantId) {
            items.push({ id: variantId, quantity: 1 });
          }
        });

        if (items.length === 0) {
          addAllBtn.disabled = false;
          addAllBtn.textContent = originalText;
          return;
        }

        addMultipleToCart(items, function () {
          addAllBtn.textContent = 'Added all!';
          setTimeout(function () {
            addAllBtn.textContent = originalText;
            addAllBtn.disabled = false;
          }, 2000);
        });
      });
    }

    calculateTotal();
  });

  function addToCart(variantId, quantity, callback) {
    var formData = new FormData();
    formData.append('id', variantId);
    formData.append('quantity', quantity || 1);

    fetch(window.Shopify.routes.root + 'cart/add.js', {
      method: 'POST',
      body: formData
    })
    .then(function (r) { return r.json(); })
    .then(function () {
      if (typeof callback === 'function') callback();
      document.documentElement.dispatchEvent(new CustomEvent('cart:updated'));
    })
    .catch(function (err) { console.error('Add to cart error:', err); });
  }

  function addMultipleToCart(items, callback) {
    fetch(window.Shopify.routes.root + 'cart/add.js', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ items: items })
    })
    .then(function (r) { return r.json(); })
    .then(function () {
      if (typeof callback === 'function') callback();
      document.documentElement.dispatchEvent(new CustomEvent('cart:updated'));
    })
    .catch(function (err) { console.error('Add multiple to cart error:', err); });
  }

  function formatMoney(cents, format) {
    if (typeof cents === 'string') cents = cents.replace(/[^\d.-]/g, '');
    var value = (parseInt(cents, 10) / 100).toFixed(2);
    return format.replace('{{amount}}', value);
  }
})();
```

---

## PART 3: THEME CONFIG UPDATES

### Register in `config/settings_schema.json`

Add this group at the end of the schema (before closing `]`):

```json
{
  "name": "t:Back to top",
  "settings": [
    {
      "type": "paragraph",
      "content": "t:Configure the back-to-top button behavior. Add the 'Back to top' section in the footer group via Theme Editor."
    }
  ]
},
{
  "name": "t:Mobile bottom nav",
  "settings": [
    {
      "type": "paragraph",
      "content": "t:Configure the mobile bottom navigation. Add the 'Mobile bottom nav' section in the footer group via Theme Editor."
    }
  ]
},
{
  "name": "t:Foundation tokens",
  "settings": [
    {
      "type": "paragraph",
      "content": "t:Design token system configuration. These values control the base visual language of the theme."
    },
    {
      "type": "color",
      "id": "token_semantic_success",
      "label": "t:Success color",
      "default": "#22c55e"
    },
    {
      "type": "color",
      "id": "token_semantic_warning",
      "label": "t:Warning color",
      "default": "#eab308"
    },
    {
      "type": "color",
      "id": "token_semantic_error",
      "label": "t:Error color",
      "default": "#ef4444"
    },
    {
      "type": "color",
      "id": "token_semantic_info",
      "label": "t:Info color",
      "default": "#3b82f6"
    },
    {
      "type": "range",
      "id": "token_radius_card",
      "label": "t:Card border radius",
      "min": 0, "max": 24, "step": 2, "default": 4, "unit": "px"
    },
    {
      "type": "range",
      "id": "token_radius_button",
      "label": "t:Button border radius",
      "min": 0, "max": 24, "step": 2, "default": 14, "unit": "px"
    }
  ]
}
```

### Create `locales/en.default.json` entries (if not present, add to existing locale)

```json
{
  "sections": {
    "back-to-top": {
      "name": "Back to top"
    },
    "size-chart": {
      "name": "Size chart"
    },
    "back-in-stock": {
      "name": "Back in stock"
    },
    "mobile-bottom-nav": {
      "name": "Mobile bottom nav"
    },
    "bundle-upsell": {
      "name": "Bundle upsell"
    }
  }
}
```

---

## PART 4: GENERATION PROMPT (CLI INSTRUCTIONS)

```
Copy-paste this to another Claude Code CLI instance:

---

You are a Shopify theme developer. Read the file reminng.md from the project root.
This file contains ALL remaining sections and foundation tokens for the Horizon theme.

FOLLOW THESE STEPS IN ORDER:

STEP 1: Create all 21 foundation token files in assets/ and snippets/ as specified in PART 1.

STEP 2: Create all 5 section files in sections/ as specified in PART 2.

STEP 3: Create all 5 JS asset files in assets/ as specified in PART 2.

STEP 4: Update config/settings_schema.json to register the new settings groups from PART 3.

STEP 5: Verify that all files were created correctly:
   - sections/back-to-top.liquid
   - sections/size-chart.liquid
   - sections/back-in-stock.liquid
   - sections/mobile-bottom-nav.liquid
   - sections/bundle-upsell.liquid
   - assets/back-to-top.js
   - assets/size-chart.js
   - assets/back-in-stock.js
   - assets/mobile-bottom-nav.js
   - assets/bundle-upsell.js
   - assets/tokens-typography-scale.css
   - assets/tokens-color-palette.css
   - assets/tokens-spacing.css
   - assets/tokens-border-radius.css
   - assets/tokens-shadows.css
   - assets/tokens-borders.css
   - assets/tokens-opacity.css
   - assets/tokens-z-index.css
   - assets/tokens-animations.css
   - assets/tokens-grid.css
   - assets/tokens-breakpoints.css
   - assets/tokens-icons.css
   - assets/tokens-illustrations.css
   - (and all token snippets from PART 1)

STEP 6: Do NOT add comments to the code. Do NOT explain what you did.

That's it. Work through this task-by-task without asking questions.
```

---

## FILE MANIFEST

```
horizon/
├── assets/
│   ├── back-to-top.js              ← Scroll-to-top button behavior
│   ├── size-chart.js               ← Size chart modal logic
│   ├── back-in-stock.js            ← Back-in-stock email capture
│   ├── mobile-bottom-nav.js        ← Mobile bottom nav behavior
│   ├── bundle-upsell.js            ← Bundle/upsell add-to-cart logic
│   ├── tokens-typography-scale.css ← Typography size system
│   ├── tokens-color-palette.css    ← Base color palette
│   ├── tokens-spacing.css          ← Spacing scale
│   ├── tokens-border-radius.css    ← Border radius scale
│   ├── tokens-shadows.css          ← Shadow/elevation system
│   ├── tokens-borders.css          ← Border width/style system
│   ├── tokens-opacity.css          ← Opacity scale
│   ├── tokens-z-index.css          ← Z-index layer system
│   ├── tokens-animations.css       ← Animation duration/easing tokens
│   ├── tokens-grid.css             ← Grid system
│   ├── tokens-breakpoints.css      ← Breakpoint values
│   ├── tokens-icons.css            ← Icon size/stroke tokens
│   └── tokens-illustrations.css    ← Illustration references
├── snippets/
│   ├── tokens-typography-scale.liquid
│   ├── tokens-font-pairing.liquid
│   ├── tokens-semantic-colors.liquid
│   ├── tokens-spacing.liquid
│   ├── tokens-border-radius.liquid
│   ├── tokens-shadows.liquid
│   ├── tokens-borders.liquid
│   ├── tokens-transitions.liquid
│   ├── tokens-container-widths.liquid
│   ├── tokens-image-ratios.liquid
│   ├── tokens-elevation.liquid
│   ├── tokens-theme-presets.liquid
│   └── tokens-padding-margin.liquid
├── sections/
│   ├── back-to-top.liquid          ← Floating back-to-top button w/ progress
│   ├── size-chart.liquid           ← Size chart modal trigger + panel
│   ├── back-in-stock.liquid        ← Out-of-stock email notification form
│   ├── mobile-bottom-nav.liquid    ← Sticky mobile tab bar (5 icons)
│   └── bundle-upsell.liquid        ← Bundle/upsell product grid
└── config/
    └── settings_schema.json  ← UPDATED with new setting groups
```

**TOTAL: 5 sections + 5 JS assets + 14 CSS assets + 13 token snippets + config update = 38 files**
