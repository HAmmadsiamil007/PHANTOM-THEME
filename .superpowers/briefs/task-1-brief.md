# Task 1: Create ph-design-tokens.css.liquid

**Files:**
- Create: `assets/ph-design-tokens.css.liquid`

**Context:** This is the core file for Phase 6 of PHANTOM theme. You are creating a CSS custom properties file with 7 token systems. Follow the exact token names and values below.

## Token Specifications

### 1. Spacing Scale (12 steps)
```css
--ph-space-1: 4px;   --ph-space-7: 40px;
--ph-space-2: 8px;   --ph-space-8: 48px;
--ph-space-3: 12px;  --ph-space-9: 64px;
--ph-space-4: 16px;  --ph-space-10: 80px;
--ph-space-5: 24px;  --ph-space-11: 96px;
--ph-space-6: 32px;  --ph-space-12: 120px;
```
Semantic aliases: `--ph-gap-xs` thru `--ph-gap-xl` mapping to `--ph-space-1` thru `--ph-space-5`

### 2. Shadow System (5 levels)
```css
--ph-shadow-color-rgb: 0, 0, 0;
--ph-shadow-opacity: 0.15;
--ph-shadow-alpha-sm: calc(var(--ph-shadow-opacity) * 0.33);
--ph-shadow-alpha-md: calc(var(--ph-shadow-opacity) * 0.47);
--ph-shadow-alpha-lg: calc(var(--ph-shadow-opacity) * 0.67);
--ph-shadow-alpha-xl: calc(var(--ph-shadow-opacity) * 0.80);
--ph-shadow-alpha-2xl: calc(var(--ph-shadow-opacity) * 1.00);
--ph-shadow-sm: 0 1px 2px rgba(var(--ph-shadow-color-rgb), var(--ph-shadow-alpha-sm));
--ph-shadow-md: 0 4px 6px rgba(var(--ph-shadow-color-rgb), var(--ph-shadow-alpha-md));
--ph-shadow-lg: 0 10px 25px rgba(var(--ph-shadow-color-rgb), var(--ph-shadow-alpha-lg));
--ph-shadow-xl: 0 20px 50px rgba(var(--ph-shadow-color-rgb), var(--ph-shadow-alpha-xl));
--ph-shadow-2xl: 0 30px 80px rgba(var(--ph-shadow-color-rgb), var(--ph-shadow-alpha-2xl));
```

### 3. Z-Index Layers (6 layers)
```css
--ph-z-base: 1; --ph-z-nav: 100; --ph-z-overlay: 300;
--ph-z-drawer: 400; --ph-z-modal: 500; --ph-z-toast: 600;
```

### 4. Elevation System (5 levels)
```css
--ph-elevation-ground: 0 0 0 rgba(0,0,0,0);
--ph-elevation-raised: var(--ph-shadow-sm);
--ph-elevation-overlay: var(--ph-shadow-lg);
--ph-elevation-modal: var(--ph-shadow-xl);
--ph-elevation-toast: var(--ph-shadow-2xl);
```

### 5. Border System
Width: `--ph-border-width-thin: 1px`, `--ph-border-width-default: 2px`, `--ph-border-width-thick: 4px`
Radius: `--ph-border-radius-sm: 2px`, `--ph-border-radius-md: 4px`, `--ph-border-radius-lg: 8px`, `--ph-border-radius-full: 9999px`

### 6. Opacity Scale (11 steps)
0, 10, 20, 30, 40, 50, 60, 70, 80, 90, 100 → `--ph-opacity-{N}: {N/100}`

### 7. Dark Mode Override
```css
@media (prefers-color-scheme: dark) {
  :root {
    --ph-shadow-color-rgb: 255, 255, 255;
    --ph-shadow-opacity: 0.08;
  }
}
```

### 8. Loading Spinner Keyframes
```css
@keyframes ph-spin {
  to { transform: rotate(360deg); }
}
```

## File Structure
All tokens under `:root { ... }`. Dark mode override AFTER the closing `}` of :root. Spinner keyframes at the very end.

## Verification
```bash
node -e "const fs=require('fs');const c=fs.readFileSync('assets/ph-design-tokens.css.liquid','utf8');console.log('Size:',c.length,'bytes');console.log('Has :root:',c.includes(':root'));console.log('Has dark:',c.includes('prefers-color-scheme'));"
```

Expected: Size > 2000 bytes, Has :root: true, Has dark: true

## Report
Write full report to: `.superpowers/briefs/task-1-report.md`
Report must include: status (DONE/DONE_WITH_CONCERNS/NEEDS_CONTEXT/BLOCKED), list of commits, test verification output, any concerns.
