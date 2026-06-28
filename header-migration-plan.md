# Header Migration Plan: Impulse → Horizon

## Overview

Port the Impulse theme's header layout flexibility (6 nav alignment modes, toolbar, overlay header) into the Horizon theme while preserving Horizon's superior rows+columns architecture.

---

## Phase 1: Nav Alignment Modes (Phase 1 — Core)

### Problem

Impulse has 6 hardcoded alignment modes (`main_menu_alignment`) that control how logo, nav, and icons are arranged in a single-row layout. Horizon has a generic rows+columns system where items are placed by position (top_left/center/right, bottom_left/center/right).

### Strategy

Translate Impulse's 6 modes into Horizon's rows+columns settings so merchants configure it through Horizon's native UI rather than a rigid dropdown.

But we also need a **one-click alignment presets system** that sets all the row/column positions at once. This makes migration instant — merchants pick "Center Split" and Horizon auto-configures the right slots.

#### Mode Mapping

| Impulse Mode | Horizon Row/Column Layout | Notes |
|---|---|---|
| **left** | Top: [logo][nav][icons] | Logo left, nav center, icons right |
| **left-center** | Top: [logo][nav centered][icons] | Same as left, but nav text-align center |
| **left-drawer** | Top: [logo][empty][icons+hamburger] | Nav hidden on desktop, hamburger always visible |
| **center-left** | Top: [hamburger][nav][logo][empty][icons] | Hamburger visible on desktop |
| **center-split** | Top: [left nav][logo][right nav][icons] | Logo centered, nav split half left/half right |
| **center** | Top: [hamburger/search][logo][icons]; Below: [nav] | Nav in bottom row, full width |
| **center-drawer** | Top: [hamburger][logo][icons] | Nav hidden, drawer-only on all screen sizes |

### Implementation

Add a **preset select dropdown** in Horizon header settings:

```json
{
  "type": "select",
  "id": "layout_preset",
  "label": "Alignment preset",
  "info": "Auto-configures row/column positions to match the selected layout",
  "options": [
    { "value": "custom", "label": "Custom (manual)" },
    { "value": "left", "label": "Left — nav center" },
    { "value": "left-center", "label": "Left — nav center (aligned)" },
    { "value": "left-drawer", "label": "Left — drawer nav" },
    { "value": "center-left", "label": "Center — nav left" },
    { "value": "center-split", "label": "Center — split nav" },
    { "value": "center", "label": "Center — nav below" },
    { "value": "center-drawer", "label": "Center — drawer nav" }
  ],
  "default": "custom"
}
```

When a preset is selected (not "custom"), the header rendering code overrides the individual position settings with preset values. A liquid variable `layout_preset_active` is set to `true`, and the preset mapping logic runs.

**Files affected:**
- `sections/header.liquid` — add preset setting + mapping logic
- `snippets/header-row.liquid` — may need minor conditional updates

---

## Phase 2: Split Nav Mode (Phase 1 — Core)

### Problem

Impulse's `center-split` mode splits the nav menu in half — first half renders left of logo, second half right. Horizon has no concept of splitting a single menu across two positions.

### Strategy

Since Horizon places menu items via position settings, we need a **"split" variant of the menu block**. When this variant is active, Horizon renders `header-desktop-nav.liquid` twice (like Impulse) — once with `limit: half` and once with `offset: half`.

### Implementation

1. Add a `split` variant to the `_header-menu` block (or add a checkbox setting `split_menu` in header settings).
2. In `snippets/header-row.liquid`, when a menu block has `split: true`, the menu rendering skips it from normal placement and instead `header.liquid` renders split nav in a special wrapper.
3. Create `snippets/header-split-nav.liquid` (port from Impulse).

```liquid
{%- liquid
  assign link_count = main_menu.links.size
  assign link_count_half = link_count | divided_by: 2
-%}
<div class="header-split-nav" role="navigation" aria-label="Primary">
  <div class="header-split-left">
    {%- render 'header-desktop-nav', main_menu: main_menu, limit: link_count_half -%}
  </div>
  <div class="header-split-right">
    {%- render 'header-desktop-nav', main_menu: main_menu, offset: link_count_half -%}
  </div>
</div>
```

**Files affected:**
- `sections/header.liquid` — add rendering logic for split nav
- `snippets/header-row.liquid` — handle split nav placement
- `snippets/header-split-nav.liquid` — *new*, port from Impulse

---

## Phase 3: Toolbar (Phase 1 — Core)

### Problem

Impulse has a **toolbar** — a mini-bar above the header showing a link list menu + social icons + locale/currency selectors, with dark/light styling and the ability to appear behind the overlay header. Horizon only has an announcement bar.

### Strategy

The announcement bar (`header-announcements`) already supports text slides. Rather than modifying it, we add a **`header-toolbar` section block** to the header group, or add toolbar settings to the header section itself as optional rows.

Simpler approach: Add toolbar as **additional settings in header section** with its own `toolbar_menu` and `toolbar_social` settings, rendered as a `<div class="header-toolbar">` before the top row. Style it with a slim dark bar.

### Implementation

Add to `sections/header.liquid` schema:

```json
{
  "type": "header",
  "content": "Toolbar"
},
{
  "type": "paragraph",
  "content": "A slim bar above the header with menu links and social icons."
},
{
  "type": "link_list",
  "id": "toolbar_menu",
  "label": "Toolbar menu"
},
{
  "type": "checkbox",
  "id": "toolbar_social",
  "label": "Show social icons"
},
{
  "type": "color",
  "id": "toolbar_bg",
  "label": "Toolbar background",
  "default": "#111111"
},
{
  "type": "color",
  "id": "toolbar_text",
  "label": "Toolbar text color",
  "default": "#ffffff"
}
```

Rendering (in header.liquid):

```liquid
{%- if section.settings.toolbar_menu != blank or section.settings.toolbar_social -%}
  {%- render 'header-toolbar', section: section -%}
{%- endif -%}
```

Create `snippets/header-toolbar.liquid`:

```liquid
<div class="header-toolbar">
  <div class="page-width">
    <div class="header-toolbar__inner">
      {%- if section.settings.toolbar_menu != blank -%}
        {%- assign toolbar_menu = linklists[section.settings.toolbar_menu] -%}
        <ul class="header-toolbar__menu">
          {%- for link in toolbar_menu.links -%}
            <li><a href="{{ link.url }}">{{ link.title }}</a></li>
          {%- endfor -%}
        </ul>
      {%- endif -%}
      {%- if section.settings.toolbar_social -%}
        <div class="header-toolbar__social">
          {%- render 'social-icons', icon_set: settings.social_icons -%}
        </div>
      {%- endif -%}
    </div>
  </div>
</div>
```

**Files affected:**
- `sections/header.liquid` — add toolbar settings + rendering
- `snippets/header-toolbar.liquid` — *new*

---

## Phase 4: Desktop Nav & Mega Menus (Phase 2 — Enhancement)

### Problem

Both themes support mega menus, but Impulse's `header-desktop-nav.liquid` uses a `<details>` element for hover/click behavior, grid columns (5 cols) for mega menu layout, and collection image display. Horizon uses `mega-menu-list.liquid` with its own structure.

### Strategy

Port Impulse's `header-desktop-nav.liquid` into Horizon wholesale, replacing the menu rendering logic. This gives Horizon:
- `<details>` hover/click menu interaction
- 5-column mega menu grid with `appear-animation` staggered entries
- Collection image display in mega menus
- Deep dropdown (3rd level) support
- Hover menu mode toggle setting

### Implementation

1. Import `header-desktop-nav.liquid` from Impulse to Horizon.
2. Add `hover_menu` checkbox setting to Horizon header schema.
3. Add `mega_menu_images` checkbox setting to Horizon header schema.
4. Wire the rendering into `header-row.liquid` or `header.liquid`.

**Files affected:**
- `snippets/header-desktop-nav.liquid` — replace with Impulse version (adapted for Horizon)
- `sections/header.liquid` — add `hover_menu` and `mega_menu_images` settings

---

## Phase 5: Sticky & Transparent Header Enhancements (Phase 2 — Enhancement)

### Status

Horizon already has:
- Sticky: `always`, `scroll-up`, `none`
- Transparent: home, product, collection pages with inverse logos and custom text colors

Impulse has:
- Sticky: `normal` (non-sticky), `sticky`
- Overlay: `sticky_index` (home), `sticky_collection` (collections with image)
- Toolbar appears inside overlay header group

### Action

Minimal changes needed. Add Impulse's `sticky_collection` behavior (only sticky on collection pages with image) as an option:
- Add `sticky_collection` checkbox to Horizon settings.
- `sticky_collection` = true + sticky mode = "always" → only sticky on collection pages that have an image.

**Files affected:**
- `sections/header.liquid` — add `sticky_collection` setting + conditional logic

---

## Phase 6: Header Icons Enhancement (Phase 2 — Enhancement)

### Problem

Impulse's `header-icons.liquid` supports 3 cart icon styles (cart, bag, bag-minimal) configured via theme settings. Horizon's `header-actions.liquid` only has one cart icon.

### Strategy

Add cart icon style selection to Horizon's theme settings (not just header section).

### Implementation

1. Add `cart_icon` select setting to theme settings (`config/settings_schema.json`): options `cart`, `bag`, `bag-minimal`.
2. Update `snippets/header-actions.liquid` to render the selected cart icon SVG.

**Files affected:**
- `config/settings_schema.json` — add `cart_icon` setting
- `snippets/header-actions.liquid` — add icon variants

---

## Phase 7: Drawer Menu Enhancement (Phase 2 — Enhancement)

### Problem

Impulse's `drawer-menu.liquid` integrates toolbar menu items, social icons, localization, and currency selectors into the mobile drawer. Horizon's `header-drawer.liquid` is simpler.

### Strategy

Enhance Horizon's drawer to optionally include toolbar menu, social icons, and localization options (similar to Impulse).

### Implementation

Modify `snippets/header-drawer.liquid`:
- Add toolbar menu links at the top
- Add social icons
- Add locale/currency selectors

**Files affected:**
- `snippets/header-drawer.liquid` — add toolbar menu, social, localization
- `sections/header.liquid` — pass toolbar/localization settings to drawer

---

## Implementation Order

```
Phase 1 (Core layout — unblocked):
┌────────────────────────────────────────────────────────┐
│  1. Add layout_preset setting to header schema         │
│  2. Add preset → row/column mapping logic              │
│  3. Add split nav support (center-split mode)           │
│  4. Add toolbar (menu + social icons)                   │
└────────────────────────────────────────────────────────┘

Phase 2 (Enhancements — unblocked, after Phase 1):
┌────────────────────────────────────────────────────────┐
│  5. Port header-desktop-nav from Impulse (mega menus)  │
│  6. Add hover_menu + mega_menu_images settings         │
│  7. Add sticky_collection behavior                     │
│  8. Add cart icon variants                              │
│  9. Enhance drawer menu                                 │
└────────────────────────────────────────────────────────┘
```

---

## Files to Create
- `snippets/header-split-nav.liquid` — Split nav for center-split mode
- `snippets/header-toolbar.liquid` — Toolbar bar

## Files to Modify
- `sections/header.liquid` — Add presets, toolbar, split nav, hover, mega menu settings
- `snippets/header-row.liquid` — Support split nav placement
- `snippets/header-desktop-nav.liquid` — Replace with Impulse version
- `snippets/header-actions.liquid` — Add cart icon variants
- `snippets/header-drawer.liquid` — Add toolbar menu, social, localization
- `config/settings_schema.json` — Add cart_icon setting
