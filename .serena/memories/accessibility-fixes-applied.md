# Accessibility Audit — FX Optix Kids Collection Theme

## Issues Resolved (WCAG 2.1 AA)

### High Severity
1. **Social links missing `aria-label`** — site-footer.php:26, about.php:149-176 — Added `aria-label` to each `<a>` around icon-only social links + `aria-hidden="true"` on `<i>` icon.
2. **Shop product icon links** — shop.php:200-208 — Added `aria-label` with product-specific text to add-to-cart, wishlist, view-details links; fixed `alt` text on images.
3. **Contact form labels no `for` attr** — contact.php:114-127 — Added `for` attributes matching input `id`s.
4. **Shop search missing label** — shop.php:38 — Added `aria-label="Search products"` to input + `aria-label` on search button.
5. **Instagram follow links** — front-page.php:417, about.php:245 — Added `aria-label="View on Instagram"` + empty `alt=""` on decorative images.

### Medium Severity
6. **Decorative `<i>` icons missing `aria-hidden="true"`** — Added across front-page.php (~25 instances), blog.php (4), shop.php (~10), newsletter.php (1), site-footer.php (10), contact.php (1).
7. **Footer angle-right icons** — site-footer.php:48-65 — Added `aria-hidden="true"` to all decorative nav list bullets.

### Verified Passing
- Skip link target in header.php → main#content ✓
- Cookie consent buttons have visible text ✓
