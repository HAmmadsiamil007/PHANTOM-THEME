# Horizon Theme — Codebase Analysis

> Generated: 2026-06-25
> Base: Shopify PHANTOM theme → Horizon fork

---

## 1. Project Overview

The Horizon theme is a **Shopify Liquid theme** forked/derived from the **PHANTOM** theme. It uses Shopify's section-based architecture with `_blocks.liquid` for dynamic block discovery in the theme editor.

---

## 2. Git History — Key Milestones

| Commit | Description |
|--------|-------------|
| Latest | `phantom-ui-enhancements.css` integration — polish layer |
| ~Latest | Skeleton UI loader system (from PHANTOM) |
| ~Latest | Article card snippet added |
| ~Latest | Blog slider skeleton |
| ~Latest | Section token fixes |
| ~Latest | Countdown timer block (1460 lines) |
| ~Latest | Split image banner block (1809 lines) |
| ~Latest | Phantom footer section (601 lines) |
| ~Latest | Phantom predictive search section (73 lines) |
| ~Latest | `_blocks.liquid` created in `sections/` directory |

---

## 3. AI-Generated Blocks

### 3.1 countdown-timer.liquid (1460 lines)
- **File**: `sections/`
- **Source**: Copied from `open PHANTOM/blocks/`
- **Features**: Configurable end date, styling options, likely real-time countdown display
- **Status**: Integrated into `_blocks.liquid`

### 3.2 split-image-banner.liquid (1809 lines)
- **File**: `sections/`
- **Source**: Copied from `open PHANTOM/blocks/`
- **Features**: Split layout banner with image/text halves, likely responsive
- **Status**: Integrated into `_blocks.liquid`

### Note
AGENTS.md states 3 AI blocks (including a "footer"), but the footer was already created as `phantom-footer.liquid` (601 lines) in the PHANTOM base, not an AI-copied block. The two AI-generated blocks are countdown-timer and split-image-banner.

---

## 4. AI-Generated Sections (Pre-existing)

### 4.1 phantom-footer.liquid (601 lines)
- Shopifys standard footer section, likely enhanced with PHANTOM design tokens

### 4.2 phantom-predictive-search.liquid (73 lines)
- Minimal predictive search overlay

---

## 5. Architecture

### 5.1 Block Discovery System
- `_blocks.liquid` in `sections/` wraps AI-generated blocks
- Accepts `@theme` parameter → blocks auto-discoverable in theme editor
- Blocks are self-contained (no snippet dependencies)

### 5.2 Directory Structure
```
horizon/
├── layouts/          # theme.liquid
├── sections/         # Sections + _blocks.liquid
├── snippets/         # Reusable Liquid partials
├── templates/        # Page/product/collection templates
├── assets/           # CSS, JS, images
├── config/           # Theme settings
└── locales/          # Translations
```

---

## 6. AGENTS.md Corrections Needed

| Statement | Current (Incorrect) | Correct |
|-----------|-------------------|---------|
| AI block count | "3 AI-generated blocks" | 2 (countdown-timer, split-image-banner) |
| `_blocks.liquid` location | "horizon/blocks/" | `horizon/sections/_blocks.liquid` |
| Footer status | Listed as AI-generated block | It's `phantom-footer.liquid`, a pre-existing PHANTOM section |

---

## 7. Design System Integration

The theme uses a design token approach via `phantom-ui-enhancements.css`, suggesting:
- CSS custom properties for colors, spacing, typography
- PHANTOM's proprietary design tokens
- Potential integration with `ui-ux-pro-max` design system skills
