# Phase A Gap Analysis — Settings Registry vs Spec Targets

**Generated:** 2026-07-14
**Source:** `class-settings-registry.php` (43 methods) vs `spec §3.4` (42 sections)

---

## Gap Summary

| Metric | Value |
|--------|-------|
| Current entries in code | 303 |
| Spec target sum | 301 |
| Sections matching spec | 9 (exact count hit) |
| Sections below spec | 21 (need new entries) |
| Sections above spec | 11 (may need trimming) |
| Missing sections | 1 (`announcement_bar`) |
| Internal spec inconsistency | Table sums to 301, but §3.4 claims "471 total" |

---

## Sections Missing Entries (Need Work)

Sorted by largest gap first.

| # | Method Name | Current | Spec Target | Delta | Priority |
|---|-------------|---------|-------------|-------|----------|
| 1 | `section_product_cards()` | 1 | 8 | **-7** | 🔴 Critical |
| 2 | `section_spacing()` | 0 | 6 | **-6** | 🔴 Critical |
| 3 | `section_navigation()` | 2 | 6 | **-4** | 🔴 Critical |
| 4 | `section_shop_page()` | 4 | 8 | **-4** | 🔴 Critical |
| 5 | `section_responsive()` | 0 | 4 | **-4** | 🔴 Critical |
| 6 | `section_search()` | 0 | 4 | **-4** | 🔴 Critical |
| 7 | `section_seo()` | 0 | 4 | **-4** | 🔴 Critical |
| 8 | `section_accessibility()` | 0 | 4 | **-4** | 🔴 Critical |
| 9 | `section_home_sections()` | 27 | 30 | **-3** | 🟡 Medium |
| 10 | `section_blog()` | 4 | 7 | **-3** | 🟡 Medium |
| 11 | `section_import_export()` | 0 | 3 | **-3** | 🟡 Medium |
| 12 | `section_hero()` | 8 | 10 | **-2** | 🟡 Medium |
| 13 | `section_collections()` | 4 | 6 | **-2** | 🟡 Medium |
| 14 | `section_animations()` | 3 | 5 | **-2** | 🟡 Medium |
| 15 | `section_performance()` | 3 | 5 | **-2** | 🟡 Medium |
| 16 | `section_custom_code()` | 2 | 4 | **-2** | 🟡 Medium |
| 17 | `section_woocommerce()` | 24 | 25 | **-1** | 🟢 Low |
| 18 | `section_typography()` | 7 | 8 | **-1** | 🟢 Low |
| 19 | `section_buttons()` | 7 | 8 | **-1** | 🟢 Low |
| 20 | `section_forms()` | 5 | 6 | **-1** | 🟢 Low |
| 21 | `section_faq_page()` | 6 | 4 | +2 | 🟡 Has excess, not gap |

**Total missing entries across all sections: 66**

---

## Sections With Excess (Over Target)

These may need trimming to align with spec.

| Method Name | Current | Spec Target | Excess |
|-------------|---------|-------------|--------|
| `section_product_page()` | 39 | 15 | **+24** |
| `section_about_page()` | 20 | 10 | **+10** |
| `section_contact_page()` | 15 | 10 | **+5** |
| `section_integrations()` | 13 | 8 | **+5** |
| `section_footer()` | 16 | 12 | **+4** |
| `section_header()` | 10 | 8 | **+2** |
| `section_layout()` | 6 | 4 | **+2** |

---

## Exact Matches (No Change Needed)

| Method Name | Current | Spec Target |
|-------------|---------|-------------|
| `section_topbar()` | 6 | 6 |
| `section_colors()` | 12 | 12 |
| `section_effects_3d()` | 4 | 4 |
| `section_error_404()` | 3 | 3 |
| `section_register_page()` | 8 | 8 |
| `section_portfolio()` | 3 | 3 |
| `section_privacy()` | 2 | 2 |
| `section_terms()` | 2 | 2 |
| `section_coming_soon()` | 5 | 4 (+1, minor excess) |

---

## Missing Section (Not in Code)

| Section | Spec Target | Status |
|---------|-------------|--------|
| `announcement_bar` | 4 | **Does not exist** — needs new `section_announcement_bar()` method |

---

## Sections With Unclear Mapping

| Code Section | Spec Section | Notes |
|-------------|-------------|-------|
| `section_branding()` (3 entries) | Not in spec table | May distribute into header or site_identity |
| `section_team()` (6) + `section_testimonials()` (3) | `about_inner` (4) | Code has 9 combined; spec wants 4 |
| `section_login_page()` (9) | login_page (8) | +1 excess (login_logo is extra) |
| `section_thank_you()` (5) | thank_you (4) | +1 excess (thank_you_icon is extra) |
| `section_load_more()` (6) | load_more (5) | +1 excess |

---

## Per-Section Detail

### 1. `section_product_cards()` — 🔴 -7
**Current:** 1 entry (`shop_columns`)
**Target:** 8 (image_ratio, hover, quick_view, badges, etc.)
**Needs:** 7 new entries for card-level settings (hover effects, image aspect ratio, quick view toggle, sale badge, stock badge, card shadow, card border radius)

### 2. `section_spacing()` — 🔴 -6
**Current:** 0 (empty method)
**Target:** 6 (containers, section_spacing, gutters)
**Needs:** Container max-width, section padding Y, section margin Y, gutter width, block gap, content padding

### 3. `section_navigation()` — 🔴 -4
**Current:** 2 (footer_nav, footer_support — both repeater navs)
**Target:** 6 (menus, mega_menus, mobile)
**Needs:** 4 new entries for mega menu toggle, mobile breakpoint, submenu indicator, dropdown animation

### 4. `section_shop_page()` — 🔴 -4
**Current:** 4 (shop_title, shop_products_per_page, shop_enable_sidebar, shop_enable)
**Target:** 8 (sidebar, filters, pagination, columns)
**Needs:** 4 entries — filter position, pagination style, product columns (currently in product_cards), show/hide sort

### 5. `section_responsive()` — 🔴 -4
**Current:** 0 (empty method)
**Target:** 4 (desktop, laptop, tablet, mobile)
**Needs:** Breakpoint values, container widths per device, font scaling per device

### 6. `section_search()` — 🔴 -4
**Current:** 0 (empty method) — search_placeholder is in header
**Target:** 4 (ajax, live_results, suggestions)
**Needs:** 4 entries — ajax search toggle, live results count, suggestion enable, search placeholder (move from header)

### 7. `section_seo()` — 🔴 -4
**Current:** 0 (empty method)
**Target:** 4 (breadcrumbs, schema, og_meta)
**Needs:** 4 entries — breadcrumb enable, schema type, OG meta enable, meta description template

### 8. `section_accessibility()` — 🔴 -4
**Current:** 0 (empty method)
**Target:** 4 (keyboard_nav, skip_links, contrast)
**Needs:** 4 entries — skip link enable, focus outline toggle, contrast mode, reduced motion respect

### 9. `section_home_sections()` — 🟡 -3
**Current:** 27
**Target:** 30
**Needs:** 3 entries — possibly video section enable, featured collection ID, banner animation toggle

### 10. `section_blog()` — 🟡 -3
**Current:** 4 (blog_enable, blog_title, blog_posts_per_page, blog_tabs)
**Target:** 7 (layout, sidebar, featured_image, author)
**Needs:** 3 entries — blog layout (grid/list), featured image enable, author box enable

### 11. `section_import_export()` — 🟡 -3
**Current:** 0 (empty method)
**Target:** 3 (export, import, reset)
**Needs:** 3 entries — export capability, import capability, reset to defaults

### 12. Sections with -2 gaps → see full table above

---

## Summary of Required Work

1. **Create 1 new section method:** `section_announcement_bar()` with 4 entries
2. **Fill empty sections:** `spacing`, `responsive`, `search`, `seo`, `accessibility`, `import_export` (20 new entries total)
3. **Expand underfilled sections:** `product_cards`, `navigation`, `shop_page`, `blog`, `hero`, `collections`, `animations`, `performance`, `custom_code` (26 new entries)
4. **Consider pruning:** `product_page` (39→15), `about_page` (20→10), `contact_page` (15→10) — these have far more entries than spec allows
5. **Resolve branding section:** Move/redistribute 3 entries from `section_branding()` which has no spec target
6. **Resolve about_inner mapping:** Combine `section_team` + `section_testimonials` (9 entries) into spec-compliant 4-entry `about_inner`
