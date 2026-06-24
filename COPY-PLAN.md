# Copy/Adaptation Plan: open PHANTOM → Horizon

## Comparison Methodology
- Both `ANALYSIS-open-PHANTOM.md` and `ANALYSIS-horizon.md` now exist
- This document identifies what sections, blocks, and features exist in open PHANTOM that are absent from horizon
- Each candidate is rated by **value** (unique functionality) and **effort** (adaptation complexity)

---

## Sections: open PHANTOM exclusives

| Section | open PHANTOM | Horizon Equivalent | Unique? | Value | Effort |
|---------|-------------|-------------------|---------|-------|--------|
| Logo Slider | `logo-slider.liquid` | None | **Yes** | Medium | Low |
| Related Blog Posts | `related-blog-posts.liquid` | None | **Yes** | Medium | Low |
| Bottom Banner | `bottom-banner.liquid` | Can use `banner` section | No | Low | — |
| Bottom Slider | `bottom-slider.liquid` | Can use `slideshow` | No | Low | — |
| Benefits | `benefits.liquid` | Can use `multi-column` | No | Low | — |
| Text Columns w/ Icons | `text-columns-with-icons.liquid` | Can use `multi-column` | No | Low | — |
| Pre Footer | `pre-footer.liquid` | Redundant | No | Low | — |
| CTA | `cta.liquid` | Can use `text-boxes` + button | No | Low | — |

**Genuinely unique sections: 2**
1. **Logo Slider** — Client/brand logo carousel (not in horizon)
2. **Related Blog Posts** — Tag-based related articles below article (not in horizon)

---

## Blocks: open PHANTOM exclusives (AI-generated)

| Block | Type | Horizon Equivalent | Unique? | Value | Effort |
|-------|------|-------------------|---------|-------|--------|
| Countdown Timer | `countdown-timer.liquid` | None | **Yes** | High | Medium |
| Split Image Banner | `split-image-banner.liquid` | Similar to `image-with-text` | Partially | Medium | Low |
| Footer Color Scheme | `footer-color-scheme.liquid` | Horizon footer uses section color scheme | No | Low | — |

**Genuinely unique blocks: 2**
1. **Countdown Timer** — Configurable countdown (high value for promos/launches)
2. **Split Image Banner** — Split-screen hero variant (medium value)

---

## Snippets: open PHANTOM exclusives

| Snippet | Horizon Equivalent | Value |
|---------|-------------------|-------|
| `popup-newsletter.liquid` | Partially in horizon's newsletter-popup | Low |
| `section-tabs.liquid` | Horizon has tabbed collection | Low |
| `vendor-settings.liquid` | Theme-specific config | Low |
| `video-modal.liquid` | Horizon likely has video modal | Low |
| `mega-menu.liquid` | Horizon has mega-menu-list snippet | Low |
| `header-mobile-nav.liquid` | Horizon has header-drawer | Low |

**No unique high-value snippets** — Horizon's snippet library (119) is far more comprehensive than open PHANTOM's (41).

---

## Assets: open PHANTOM exclusives

| Asset | Notes | Value |
|-------|-------|-------|
| GSAP (Tween, ScrollTrigger) | Horizon uses vanilla JS + morph.js/scrolling.js | Medium (different approach) |
| Swiper 11 | Horizon uses custom slideshow.js | Low |
| Bootstrap 5 | Horizon uses custom CSS | Low (wouldn't port) |
| Font Awesome 6 | Horizon uses SVG icons | Low |

---

## Animation System Comparison

**open PHANTOM approach:** GSAP + ScrollTrigger for scroll-triggered entrance animations, parallax, and timed transitions. jQuery-dependent.

**Horizon approach:** Vanilla JS with `scrolling.js` for scroll effects, `morph.js` for element transitions, `view-transitions.js` for page transitions. No third-party dependencies.

**Recommendation:** Do NOT port GSAP animations. Horizon's vanilla approach is more modern and performant. If scroll animations are needed, extend horizon's `scrolling.js`.

---

## Final Copy/Adaptation Candidates (Recommended)

### Priority 1: High Value, Low-Medium Effort

| Item | What to Copy | Adaptation Required |
|------|-------------|-------------------|
| **Logo Slider section** | Full section + Swiper carousel | Rewrite to use horizon's CSS variables, replace Bootstrap with horizon's grid, replace Font Awesome with horizon's SVG icons, use horizon's media snippet |
| **Related Blog Posts section** | Full section logic | Adapt to horizon snippet architecture, use horizon's card/image/text snippets |
| **Countdown Timer block** | Block logic (JS timer, schema) | Rewrite JS as horizon component class, use horizon styling tokens |

### Priority 2: Medium Value

| Item | What to Copy | Adaptation Required |
|------|-------------|-------------------|
| **Split Image Banner block** | Visual layout concept | Rebuild using horizon's existing `image-with-text` + `_content-without-appearance` blocks if possible |

### Not Recommended
- GSAP animations — Horizon's vanilla approach is superior
- Bootstrap dependency — Would bloat horizon
- jQuery-dependent code — Horizon is vanilla
- Bottom Banner/Slider — Already achievable in horizon
- Benefits / Text Columns w/ Icons — Already achievable via multi-column
- Footer Color Scheme — Horizon handles this through section color settings

---

## Implementation Notes

### Logo Slider Adaptation
- **Source:** `open PHANTOM\sections\logo-slider.liquid`
- **Replace:** Bootstrap grid → Horizon CSS variables, `container` → Horizon spacing/padding snippets
- **Replace:** Font Awesome icons → Horizon SVG icon system
- **Replace:** Swiper → Can keep Swiper or convert to Horizon's slideshow system
- **Style:** Use Horizon color scheme, typography scale, and design tokens
- **JS:** Create a lean JS file extending horizon's `Component` base class

### Related Blog Posts Adaptation
- **Source:** `open PHANTOM\sections\related-blog-posts.liquid`
- **Strategy:** Extract the logic for finding related articles by tag, use horizon's `_blog-post-card.liquid` and `_blog-post-*.liquid` block system
- **Structure:** Create as a section that reuses horizon's existing blog post blocks

### Countdown Timer Adaptation
- **Source:** `open PHANTOM\blocks\countdown-timer.liquid` (AI-generated)
- **Strategy:** Port the JSON schema, timer JS logic (rewrite as component class), and styling
- **JS:** Rewrite as horizon component extending base Component class
- **Style:** Use horizon design tokens, CSS variables
