# Completed Fixes Log

## Phase 1.1 — optix_img() $fallback parameter ✅
- **File:** `inc/template-functions.php` (lines 90, 129)
- **Change:** Added `$fallback = ''` parameter to both namespaced + global `optix_img()`
- **Logic:** When `$path` is empty or `'0'`, return `$fallback` directly (it's already a full URL from `$kc_img` prefix)
- **Verified:** PHP syntax clean

## Next: Phase 1.2/1.3 — Verify image base path alignment
