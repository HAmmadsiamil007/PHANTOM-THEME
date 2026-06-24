---
name: responsive-ui-testing
description: >
  Trigger this skill for any task involving responsive design validation, cross-device
  testing, layout regression prevention, or UI quality assurance. Use when a UI change
  has been made and needs verification across all screen sizes, or when building a new
  Shopify theme, React/Next.js/Vue app, Tailwind project, headless storefront, SaaS
  dashboard, or marketing website. Also trigger when the user reports layout bugs,
  overflow issues, broken grids, or mobile usability problems.
---

# Responsive UI Testing — Production-Grade Quality Assurance

You are operating as a senior Staff Frontend Engineer, QA Automation Engineer, UX Engineer, Accessibility Specialist, Shopify Theme Engineer, and Browser Testing Expert combined into one autonomous agent. Your job is to catch every responsive UI regression before it reaches users.

This skill is optimised for autonomous coding agents (OpenCode, Claude Code, Cursor, Roo Code, Cline, Windsurf). Follow it step-by-step without skipping phases.

---

## PHASE 0 — PRE-FLIGHT: Identify Context

Before testing anything, answer these questions by reading the project files:

1. **Project type** — Shopify theme? Next.js? React SPA? Vue? Static HTML? Tailwind? Identify from `package.json`, `theme.liquid`, `next.config.*`, or `vite.config.*`.
2. **Dev server URL** — Check for `localhost:3000`, `localhost:5173`, `localhost:8080`, `127.0.0.1:9292` (Shopify CLI), or a `.env` / `package.json` start script.
3. **CSS framework** — Tailwind (check `tailwind.config.*`), vanilla CSS, SCSS, CSS Modules, styled-components, etc.
4. **Breakpoints in use** — Extract the actual breakpoints from `tailwind.config.*`, `_variables.scss`, or CSS custom properties. Do NOT assume defaults.
5. **Component library** — Shadcn/ui, Radix, Headless UI, Flowbite, etc. Note any known quirks.
6. **Dark mode support** — Check for `prefers-color-scheme`, `.dark` class toggling, or `next-themes`.
7. **Shopify-specific** — If Shopify theme: note `sections/`, `blocks/`, `snippets/`, and any `{% schema %}` JSON. Theme Editor compatibility must be verified separately.

Output a one-paragraph context summary before proceeding. Example:
> "Project: Next.js 14 + Tailwind CSS 3.4. Dev server: localhost:3000. Breakpoints: default Tailwind (sm:640, md:768, lg:1024, xl:1280, 2xl:1536). Dark mode: next-themes with .dark class. No Shopify context detected."

---

## PHASE 1 — VIEWPORT MATRIX

Test every page / route at **all ten viewports** in this exact order. Do not skip any.

| ID | Label | Width | Height | Device Archetype | Tailwind Prefix |
|----|-------|-------|--------|-----------------|-----------------|
| V1 | Mobile XS | 320px | 568px | iPhone SE (1st gen), small Androids | — |
| V2 | Mobile S | 375px | 667px | iPhone SE (3rd gen), iPhone 8 | — |
| V3 | Mobile M | 390px | 844px | iPhone 14 / 15 | — |
| V4 | Mobile L | 428px | 926px | iPhone 14 Plus / Pro Max | — |
| V5 | Tablet Portrait | 768px | 1024px | iPad Mini / Air portrait | `md:` |
| V6 | Tablet Landscape | 1024px | 768px | iPad Air landscape, small laptops | `lg:` |
| V7 | Laptop | 1280px | 800px | MacBook Air, common laptops | `xl:` |
| V8 | Desktop | 1440px | 900px | Standard desktop monitor | `xl:` |
| V9 | Large Desktop | 1536px | 864px | Large monitors | `2xl:` |
| V10 | Ultrawide | 1920px | 1080px | Full HD monitors, TVs | `2xl:` |

**For each viewport, run ALL checks in Phase 2.**

---

## PHASE 2 — PER-VIEWPORT CHECKLIST

Run every item below at each viewport. Mark each: ✅ PASS | ⚠️ WARN | ❌ FAIL | ⏭️ N/A

### 2A — Layout & Structure

- [ ] **No horizontal scrollbar** — `document.documentElement.scrollWidth <= window.innerWidth`. A horizontal scrollbar at any viewport is an automatic ❌ FAIL.
- [ ] **No overflowing elements** — Query all elements; none should have `offsetLeft + offsetWidth > document.body.clientWidth`. Log the offending selector.
- [ ] **Grid integrity** — CSS Grid columns collapse correctly; no orphaned single items in a multi-column row unless intentional.
- [ ] **Flexbox wrapping** — Flex rows wrap at intended breakpoints. Items do not overflow their flex container.
- [ ] **Container max-width** — Content does not stretch edge-to-edge on V9 and V10 without a `max-w-*` constraint or equivalent.
- [ ] **Stacking order at mobile** — Columns that are side-by-side on desktop stack vertically on V1–V4.
- [ ] **Section padding/margin** — Sections maintain appropriate spacing; no collapsed margins creating content collisions.
- [ ] **Content not clipped** — No `overflow: hidden` on a parent that clips visible child content at narrow widths.

### 2B — Navigation

- [ ] **Desktop nav visible** at V7–V10; hidden or collapsed at V1–V5.
- [ ] **Hamburger / mobile menu present** at V1–V5 if desktop nav is hidden.
- [ ] **Mobile menu opens and closes** — Toggle works; overlay appears; body scroll locks; ESC key closes it.
- [ ] **Mobile menu is full-screen or covers full width** — No partial-width drawer that clips links.
- [ ] **Active/current page link** is visually indicated at all viewports.
- [ ] **Dropdown menus** function on hover (desktop) and on tap/click (mobile) without requiring hover.
- [ ] **Skip-to-content link** is present and functional (keyboard: Tab → Enter jumps focus past nav).
- [ ] **Sticky header** does not cover content on scroll; `scroll-padding-top` or equivalent is set.

### 2C — Typography

- [ ] **Base font size ≥ 16px** on V1–V4 (browser default; never override to less than 16px for body text).
- [ ] **Line length (measure)** — Body text stays between 45–80 characters per line. At V9–V10 text should not span the full viewport width without a container.
- [ ] **Heading scale** — H1 is visually dominant at all viewports. Headings do not shrink below 20px on mobile.
- [ ] **No text overflow** — Single-word overflow does not break layout (`overflow-wrap: break-word` or `word-break` applied where needed).
- [ ] **No text-size-adjust: none** on `<html>` (this blocks browser accessibility zoom).
- [ ] **Line-height ≥ 1.4** for body text (WCAG 1.4.12).

### 2D — Images & Media

- [ ] **All `<img>` elements have explicit `width` and `height` attributes** or aspect-ratio CSS to prevent layout shift.
- [ ] **Images are responsive** — `max-width: 100%` or Tailwind `w-full` / `object-fit: cover`. No image overflows its container.
- [ ] **Hero images** fill intended area at all viewports; no visible white gaps beside or below hero.
- [ ] **`srcset` / `sizes`** used for large images (performance); verify in Network tab.
- [ ] **Lazy loading** — Images below the fold have `loading="lazy"`.
- [ ] **Videos** are constrained by container; no overflow. `autoplay` videos have `muted` attribute (required by browsers).
- [ ] **SVG icons** scale correctly at all viewports; no fixed-px SVG that overflows on V1.

### 2E — Interactive Components

#### Buttons & CTAs
- [ ] **Touch target ≥ 44×44px** on V1–V5 (WCAG 2.5.5; Apple HIG; Google Material). Measure with `getBoundingClientRect()`.
- [ ] **Touch targets have ≥ 8px gap** between adjacent targets to prevent mis-taps.
- [ ] **Hover states** visible on desktop; focus states visible at all viewports.
- [ ] **Disabled state** is visually distinct and `aria-disabled="true"` or `disabled` attribute is present.

#### Forms
- [ ] **Input fields are full-width on mobile** (V1–V4) and not cut off.
- [ ] **Labels are visible** — No placeholder-only labels (fails WCAG 1.3.1).
- [ ] **Error messages are inline** and associated via `aria-describedby`.
- [ ] **Virtual keyboard does not cover submit button** — Test on V1–V4 (emulate by reducing viewport height by ~40%).
- [ ] **Autofill styles** don't break layout (check `-webkit-autofill` styles).
- [ ] **Select / dropdowns** are usable on touch; native selects preferred on mobile.
- [ ] **Date / time inputs** fall back gracefully on unsupported browsers.

#### Modals & Dialogs
- [ ] **Modal opens and closes** at all viewports.
- [ ] **Modal is scrollable** if content exceeds viewport height — never clips content.
- [ ] **Focus trap** is active inside modal (Tab cycles only within modal).
- [ ] **ESC key closes** modal.
- [ ] **Backdrop click closes** modal (unless intentionally prevented).
- [ ] **`role="dialog"` and `aria-modal="true"`** present.
- [ ] **On V1–V4**: modal is either full-screen or has adequate padding from screen edges (≥ 16px).

#### Drawers / Sidebars
- [ ] **Drawer slides in** from correct direction (left or right) without flicker.
- [ ] **Drawer width** — On V1–V4: full-width or ≥ 85% of viewport. On V7+: fixed width (e.g. 320–400px).
- [ ] **Body scroll locked** when drawer is open.
- [ ] **Close button is reachable** (top-right or top-left, ≥ 44×44px touch target).
- [ ] **Focus trap** inside drawer.

#### Carousels / Sliders
- [ ] **Swipe gesture** works on V1–V5.
- [ ] **Arrow buttons** are ≥ 44×44px.
- [ ] **Dots / pagination** indicators are tappable (≥ 44×44px touch target) if present.
- [ ] **Auto-play pauses** on hover and on focus (WCAG 2.2.2).
- [ ] **Reduced motion**: carousel does NOT auto-play and transitions are instant when `prefers-reduced-motion: reduce`.
- [ ] **`aria-label`** on carousel container; each slide has `aria-roledescription="slide"`.

#### Tables
- [ ] **Tables do not break layout on V1–V4** — either horizontal scroll within a container, or responsive card layout.
- [ ] **`<th>` elements** have `scope` attribute.

### 2F — Z-Index & Layering

- [ ] **Sticky header** sits above all scrollable content; `z-index` is defined.
- [ ] **Modals and drawers** appear above sticky headers.
- [ ] **Tooltips and dropdowns** are not clipped by parent `overflow: hidden`.
- [ ] **Cookie banners / GDPR bars** do not cover the main CTA or critical content.
- [ ] **Chat widgets / floating buttons** do not overlap with site navigation or CTAs on V1–V4.

### 2G — Accessibility (WCAG 2.2)

- [ ] **Colour contrast ≥ 4.5:1** for normal text; ≥ 3:1 for large text (18px+ or 14px+ bold). Use a contrast checker.
- [ ] **Focus indicator visible** on ALL interactive elements at all viewports. Never `outline: none` without a custom replacement.
- [ ] **Keyboard navigation** — Tab through the entire page; every interactive element is reachable and operable.
- [ ] **`alt` text** on all meaningful images; `alt=""` on decorative images.
- [ ] **ARIA landmarks** — `<header>`, `<main>`, `<nav>`, `<footer>` or `role` equivalents present.
- [ ] **Heading hierarchy** — One `<h1>` per page; headings do not skip levels (h1 → h2 → h3).
- [ ] **Link text is descriptive** — No "click here" or "read more" without context.
- [ ] **Icon-only buttons** have `aria-label`.
- [ ] **Live regions** (`aria-live`) used for dynamic updates (cart count, toast notifications, form errors).

### 2H — Performance Signals

- [ ] **No render-blocking resources** in `<head>` (check with DevTools or Lighthouse).
- [ ] **CLS (Cumulative Layout Shift) < 0.1** — Observe the page load; no elements jumping after initial paint. Common causes: images without dimensions, fonts loading late, dynamic content injection above the fold.
- [ ] **LCP (Largest Contentful Paint) < 2.5s** on simulated Fast 3G.
- [ ] **No layout shift from web fonts** — `font-display: swap` or `optional` used; or fonts are preloaded.
- [ ] **No unnecessary `!important`** overrides in CSS that could cause specificity conflicts.

### 2I — Console & Network

- [ ] **Zero JavaScript errors** in the browser console at each viewport. Log every error with its stack trace.
- [ ] **Zero failed network requests** (404, 500, CORS errors) visible in the Network tab. Log each failure.
- [ ] **No missing assets** — All images, fonts, scripts, and stylesheets load successfully.
- [ ] **API calls succeed** — For dynamic content (cart, product data, user session), verify API responses are 200.

---

## PHASE 3 — ADVANCED ENVIRONMENT TESTS

Run these once per page (not per viewport). They catch regressions that single-viewport tests miss.

### 3A — Dark Mode

If the project supports dark mode:
1. Enable dark mode (OS-level or `.dark` class toggle).
2. At V2 (375px) and V7 (1280px), verify:
   - [ ] No white flashes on page load.
   - [ ] All text remains readable (contrast ≥ 4.5:1 in dark mode).
   - [ ] Images with transparent backgrounds don't appear broken.
   - [ ] Focus rings and borders are visible.
   - [ ] Charts, icons, and illustrations are adapted (not just inverted).

### 3B — High Zoom

1. Set browser zoom to **200%** (Ctrl/Cmd + scroll or browser zoom setting).
   - [ ] No horizontal overflow.
   - [ ] Text reflows correctly; no content cut off.
   - [ ] All interactive elements still reachable.
2. Set browser zoom to **400%**.
   - [ ] Page is usable without horizontal scrolling (WCAG 1.4.10 Reflow).
   - [ ] Navigation is still accessible (hamburger or equivalent).

### 3C — Reduced Motion

Emulate `prefers-reduced-motion: reduce`:
- [ ] Carousels do not auto-advance.
- [ ] Page transitions are instant or simplified.
- [ ] Looping animations are paused.
- [ ] Parallax scrolling is disabled.
- [ ] Loading spinners are replaced with static indicators or remain subtle.

### 3D — Orientation Change

At V3 (390px), simulate orientation change to landscape (844×390):
- [ ] Layout reflows without horizontal overflow.
- [ ] Navigation remains accessible.
- [ ] Modals or drawers that were open remain correctly positioned.
- [ ] No content is hidden behind the browser's UI chrome.

### 3E — Slow Network / Low-End Device

Throttle to **Slow 3G** (DevTools Network tab):
- [ ] Loading states / skeletons are shown.
- [ ] No layout shift when content loads in.
- [ ] Fonts don't cause invisible text (FOIT) for > 3 seconds.
- [ ] Images below the fold haven't been eagerly loaded (lazy load working).
- [ ] Forms remain usable before all scripts have loaded.

CPU throttle to **4x slowdown** (DevTools Performance tab):
- [ ] Interactions remain responsive. Tap/click handlers fire within 100ms of input.
- [ ] No long tasks > 50ms blocking the main thread during scroll.

### 3F — Touch Interaction

On V1–V5 (or DevTools touch emulation):
- [ ] **Swipe gestures** — Carousels, drawers, and bottom sheets respond to horizontal/vertical swipe.
- [ ] **Pinch-to-zoom** is NOT disabled (`user-scalable=no` is NEVER set in the viewport meta tag — this fails WCAG 1.4.4).
- [ ] **Tap delay** — No 300ms tap delay. `touch-action: manipulation` or `FastClick` equivalent applied.
- [ ] **Long-press** — No accidental context menus on long-press of interactive elements (unless intentional).
- [ ] **Scroll containers** — Horizontally scrollable areas have `-webkit-overflow-scrolling: touch` or `scroll-snap` for smooth mobile scrolling.

### 3G — Shopify Theme Editor (Shopify Projects Only)

If this is a Shopify theme, verify inside the Theme Editor (Customize):
- [ ] All sections render without JS errors in the editor preview.
- [ ] Section blocks can be added, reordered, and removed without breaking layout.
- [ ] Schema settings (colors, images, text) update the preview in real time.
- [ ] `shopify:section:load` and `shopify:block:select` events are handled if custom JS is used.
- [ ] Sections work when placed in any position (first, middle, last on page).
- [ ] `sections-everywhere` sections display correctly in the editor sidebar.
- [ ] Predictive search, cart drawer, and quick-buy features function inside the editor.
- [ ] No hardcoded pixel widths that break the editor's 390px mobile preview.

### 3H — Cross-Browser Spot Check

Test the most complex page at V2 (375px) and V7 (1280px) in:
- [ ] **Chrome / Chromium** (primary dev browser — likely already done).
- [ ] **Safari / WebKit** — Check for flexbox gap support, `aspect-ratio`, `clamp()`, `scroll-behavior`, and `position: sticky` differences.
- [ ] **Firefox** — Check for scrollbar width affecting layout, `gap` in flex, and `-moz-` prefix gaps.
- [ ] Flag any ❌ FAIL that appears only in Safari or Firefox for a targeted fix.

---

## PHASE 4 — AUTO-FIX WORKFLOW

For every ❌ FAIL or ⚠️ WARN, follow this exact repair sequence:

### Step 1 — Diagnose

Before writing any code, state:
- The **failing viewport(s)**.
- The **root cause** (e.g., `min-width: 600px` on a flex child, missing `overflow-x: hidden`, hardcoded `width: 900px`).
- The **affected selector** (copy the exact CSS selector or component name).

### Step 2 — Fix

Apply the minimal targeted fix. Prefer:

| Problem | Preferred Fix |
|---------|---------------|
| Horizontal overflow | Find the offending element (`overflow-x: auto` on `body` to reveal it), then remove fixed width or add `max-width: 100%`. |
| Flex items not stacking | Add `flex-direction: column` inside the correct `@media` query or Tailwind's `sm:flex-row` / `flex-col` pattern. |
| Touch target too small | Add padding or use `min-h-[44px] min-w-[44px]` (Tailwind) or `min-height: 44px; min-width: 44px` (CSS). |
| Text overflow | Add `overflow-wrap: break-word` or `word-break: break-word` to the container. |
| Image overflow | Add `max-width: 100%; height: auto;` to the `<img>` or use Tailwind `w-full object-cover`. |
| Sticky header overlap | Add `scroll-padding-top: [header height]` to `<html>` or anchor targets. |
| Missing mobile nav | Implement hamburger toggle with `aria-expanded`, `aria-controls`, and focus trap. |
| Modal not scrollable | Wrap modal content in a scrollable inner div: `overflow-y: auto; max-height: 90vh`. |
| CLS from images | Add `width` and `height` HTML attributes matching the image's aspect ratio. |
| Z-index collision | Create a documented z-index scale: base(0), sticky(10), dropdown(20), modal(30), toast(40). |
| Console JS error | Read the stack trace, identify the component, fix the root cause (don't silence with try/catch). |
| ARIA missing | Add the minimum required attributes per the ARIA Authoring Practices Guide (APG). |

### Step 3 — Verify Fix

After each fix:
1. Re-test ONLY the failing viewport(s).
2. Confirm the fix does not break adjacent viewports (test V one size above and one size below).
3. Re-run the specific checklist items that were failing.
4. Change the status from ❌ / ⚠️ to ✅ in your report.

### Step 4 — Regression Check

After all fixes are applied:
1. Do a full sweep of Phase 2 at V2, V5, and V7 (minimum) to confirm no regressions.
2. Re-run Phase 3A (dark mode) if any color or CSS variable was modified.

---

## PHASE 5 — FAILURE CRITERIA

Declare the build **BLOCKED** (do not ship) if ANY of the following are true:

| # | Condition |
|---|-----------|
| F1 | Horizontal scrollbar exists at ANY viewport (V1–V10). |
| F2 | Any JavaScript error in the console that affects user-facing functionality. |
| F3 | Any 404 or 500 on a critical asset (CSS, main JS bundle, fonts). |
| F4 | `user-scalable=no` in the viewport meta tag (hard WCAG failure). |
| F5 | Body text contrast below 4.5:1 in either light or dark mode. |
| F6 | Navigation is completely inaccessible on V1 (320px). |
| F7 | Any form submit button is unreachable or non-functional on V1–V4. |
| F8 | Modal or drawer has no focus trap (keyboard users cannot escape). |
| F9 | CLS > 0.25 on any page (severe layout instability). |
| F10 | A Shopify section crashes the Theme Editor (if applicable). |

---

## PHASE 6 — SUCCESS CRITERIA

Declare the build **READY TO SHIP** only when ALL of the following are true:

| # | Condition |
|---|-----------|
| S1 | All 10 viewports pass Phase 2 with zero ❌ FAIL items. |
| S2 | ⚠️ WARN items are either fixed or have a documented, accepted deferral reason. |
| S3 | All Phase 3 environment tests pass (dark mode, zoom, reduced motion, orientation, slow network). |
| S4 | Zero JS console errors on all pages. |
| S5 | Zero failed network requests. |
| S6 | Touch targets ≥ 44×44px on all interactive elements on V1–V5. |
| S7 | WCAG 2.2 AA contrast passing on all text in both light and dark mode. |
| S8 | Keyboard navigation is complete — every interactive element reachable by Tab. |
| S9 | No `user-scalable=no` in any viewport meta tag. |
| S10 | CLS < 0.1 on page load. |

---

## PHASE 7 — FINAL REPORT FORMAT

Output your test report in this exact format. Copy and fill in every row.

```
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
RESPONSIVE UI TEST REPORT
Project: [Name / URL]
Date: [YYYY-MM-DD]
Pages Tested: [List all routes/pages]
Tester: [Agent name / version]
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

VIEWPORT RESULTS
────────────────────────────────────────────────────────────
V1  320px   [✅ PASS | ⚠️ WARN | ❌ FAIL]  [Brief note]
V2  375px   [✅ PASS | ⚠️ WARN | ❌ FAIL]  [Brief note]
V3  390px   [✅ PASS | ⚠️ WARN | ❌ FAIL]  [Brief note]
V4  428px   [✅ PASS | ⚠️ WARN | ❌ FAIL]  [Brief note]
V5  768px   [✅ PASS | ⚠️ WARN | ❌ FAIL]  [Brief note]
V6  1024px  [✅ PASS | ⚠️ WARN | ❌ FAIL]  [Brief note]
V7  1280px  [✅ PASS | ⚠️ WARN | ❌ FAIL]  [Brief note]
V8  1440px  [✅ PASS | ⚠️ WARN | ❌ FAIL]  [Brief note]
V9  1536px  [✅ PASS | ⚠️ WARN | ❌ FAIL]  [Brief note]
V10 1920px  [✅ PASS | ⚠️ WARN | ❌ FAIL]  [Brief note]

ADVANCED ENVIRONMENT RESULTS
────────────────────────────────────────────────────────────
Dark Mode:          [✅ | ⚠️ | ❌ | ⏭️ N/A]
Zoom 200%:          [✅ | ⚠️ | ❌]
Zoom 400% (Reflow): [✅ | ⚠️ | ❌]
Reduced Motion:     [✅ | ⚠️ | ❌]
Orientation Change: [✅ | ⚠️ | ❌]
Slow Network (3G):  [✅ | ⚠️ | ❌]
CPU Throttle (4x):  [✅ | ⚠️ | ❌]
Touch Interactions: [✅ | ⚠️ | ❌]
Shopify Editor:     [✅ | ⚠️ | ❌ | ⏭️ N/A]
Safari/Firefox:     [✅ | ⚠️ | ❌]

ISSUES FOUND
────────────────────────────────────────────────────────────
[List each issue with: Severity | Viewport | Description | Root Cause | Fix Applied | Status]

Example:
❌ FAIL | V1–V4 | Horizontal scrollbar on homepage | .hero-image has width:1200px | Changed to max-width:100% | ✅ FIXED
⚠️ WARN | V10   | Content stretches to full 1920px | No max-width on .container | Added max-w-7xl mx-auto | ✅ FIXED

CONSOLE ERRORS
────────────────────────────────────────────────────────────
[List each error with: Page | Viewport | Error message | Stack trace summary | Fix Applied]
None found ✅  /or/  [Error list]

FAILED NETWORK REQUESTS
────────────────────────────────────────────────────────────
[List each with: URL | Status Code | Page where it occurs | Fix Applied]
None found ✅  /or/  [Request list]

ACCESSIBILITY SUMMARY
────────────────────────────────────────────────────────────
Contrast failures:      [Count or None ✅]
Missing alt text:       [Count or None ✅]
Missing ARIA labels:    [Count or None ✅]
Keyboard nav gaps:      [Count or None ✅]
Touch target failures:  [Count or None ✅]
Focus trap missing:     [Count or None ✅]

PERFORMANCE SIGNALS
────────────────────────────────────────────────────────────
CLS:  [Value] — [✅ < 0.1 | ⚠️ 0.1–0.25 | ❌ > 0.25]
LCP:  [Value] — [✅ < 2.5s | ⚠️ 2.5–4s | ❌ > 4s]
Images with missing dimensions: [Count or None ✅]
Lazy loading applied:  [✅ Yes | ❌ No]

VERDICT
────────────────────────────────────────────────────────────
[✅ READY TO SHIP — All success criteria met.]
  /or/
[🚫 BLOCKED — Failure criteria triggered: F[numbers]. Issues must be resolved before shipping.]
  /or/
[⚠️ CONDITIONAL — No blocking failures. [N] warnings deferred: [brief reason]. Acceptable to ship with tracking ticket.]

Total Issues Found:   [N]
Total Issues Fixed:   [N]
Issues Deferred:      [N]
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
```

---

## QUICK REFERENCE — COMMON FIXES BY FRAMEWORK

### Tailwind CSS
```html
<!-- Prevent horizontal overflow at root level -->
<body class="overflow-x-hidden">

<!-- Responsive container -->
<div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

<!-- Stack on mobile, side-by-side on tablet -->
<div class="flex flex-col md:flex-row gap-4 md:gap-6">

<!-- Touch-friendly button -->
<button class="min-h-[44px] min-w-[44px] px-4 py-3">

<!-- Responsive image -->
<img class="w-full h-auto object-cover" width="800" height="450" loading="lazy" alt="...">

<!-- Responsive typography -->
<h1 class="text-2xl sm:text-3xl md:text-4xl xl:text-5xl font-bold">

<!-- Hide on mobile, show on desktop -->
<nav class="hidden lg:flex">
<!-- Show on mobile, hide on desktop -->
<button class="lg:hidden" aria-label="Open menu">
```

### Shopify Liquid
```liquid
{%- comment -%} Responsive image with Shopify CDN {%- endcomment -%}
<img
  src="{{ image | image_url: width: 800 }}"
  srcset="{{ image | image_url: width: 400 }} 400w,
          {{ image | image_url: width: 800 }} 800w,
          {{ image | image_url: width: 1200 }} 1200w"
  sizes="(max-width: 768px) 100vw, 50vw"
  width="{{ image.width }}"
  height="{{ image.height }}"
  loading="lazy"
  alt="{{ image.alt | escape }}"
>

{%- comment -%} Section wrapper with max-width {%- endcomment -%}
<div class="section-wrapper" style="max-width: var(--page-width); margin: 0 auto; padding: 0 1.5rem;">
  {{ section.blocks | ... }}
</div>
```

### React / Next.js
```jsx
// Responsive image (Next.js)
import Image from 'next/image'
<Image src="/hero.jpg" alt="Hero" fill sizes="(max-width: 768px) 100vw, 50vw" priority />

// Accessible modal with focus trap
import { Dialog } from '@headlessui/react'
<Dialog open={isOpen} onClose={setIsOpen}>
  <Dialog.Panel>...</Dialog.Panel>
</Dialog>

// Skip link (place as first element in layout)
<a href="#main-content" className="sr-only focus:not-sr-only focus:fixed focus:top-4 focus:left-4 z-50 bg-white px-4 py-2">
  Skip to content
</a>
```

### CSS Custom Properties for Z-Index Scale
```css
:root {
  --z-below: -1;
  --z-base: 0;
  --z-raised: 1;
  --z-dropdown: 20;
  --z-sticky: 30;
  --z-overlay: 40;
  --z-modal: 50;
  --z-toast: 60;
  --z-tooltip: 70;
}
```

### Viewport Meta (Required — Never Add `user-scalable=no`)
```html
<!-- ✅ Correct -->
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- ❌ Never do this — WCAG failure, blocks accessibility zoom -->
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
```

---

## AGENT BEHAVIOUR RULES

1. **Never skip a viewport** — Even if V1 and V2 look similar, test both. Bugs appear at specific widths.
2. **Never assume** — Do not assume a component works because it looks fine at one size. Test every item in Phase 2 at every viewport.
3. **Fix root causes, not symptoms** — Do not add `overflow: hidden` to hide an overflow problem without finding what is overflowing.
4. **One fix at a time** — Apply one change, re-test, confirm. Do not batch unrelated fixes.
5. **Document everything** — Every issue found, every fix applied, and every deferral decision must appear in the Phase 7 report.
6. **Console is law** — A single unhandled JS error is a ❌ FAIL, regardless of whether it visually affects the page.
7. **Accessibility is not optional** — WCAG 2.2 AA compliance is a hard requirement, not a bonus.
8. **Shopify editor is a separate environment** — Always test Shopify themes inside the Theme Customizer, not just in the storefront preview.
9. **Re-test after every fix** — A fix can introduce a regression. Always verify adjacent viewports.
10. **Ship the report** — Always output the Phase 7 report, even for a PASS verdict. It is the audit trail.
