# Optix Kids Collection — Comprehensive Execution Plan

## Theme Path
`C:\Users\hamma\Downloads\wordpress\wp-content\themes\kids-collection\`

## Execution Protocol
- One fix at a time
- Before each task: sequential-thinking
- After each task: sequential-thinking review
- Every task saved to Serena after completion
- All skills & MCPs engaged

---

## PHASE 1: CRITICAL — Production-Breaking Bugs

### 1.1 optix_img() — Missing 2nd parameter $fallback
- File: inc/helpers.php
- Problem: Called as optix_img('path', 'fallback') but defined with 1 param
- Fix: Add $fallback = null parameter, use when file doesn't exist

### 1.2 optix_img() — Wrong image base path  
- File: inc/helpers.php
- Problem: Path uses assets/svg/images/ instead of assets/kids-collection/images/
- Fix: Update base path constant

### 1.3 general_kc_img_base — Mismatch with optix_img()
- File: inc/helpers.php
- Problem: Filter uses different path than optix_img()
- Fix: Align both paths

### 1.4 Footer social icon key mismatch
- Files: inc/customizer/kids_collection_footer.php, footer.php
- Problem: Customizer saves facebook/twitter but template-tags.php outputs fb/tw
- Fix: Align key names

### 1.5 coming-soon.php — Hardcoded URLs + broken $kc_img call
- File: coming-soon.php
- Problem: get_template_directory_uri() . '/assets/images/' + wrong optix_img usage
- Fix: Use optix_img() properly

### 1.6 post-thumbnails not registered  
- File: functions.php
- Problem: Missing add_theme_support('post-thumbnails')
- Fix: Add it

---

## PHASE 2: HIGH — Major Feature Gaps

### 2.1 Cookie consent bar — never outputs HTML
- Files: inc/customizer/kids_collection_general.php, footer.php
- Problem: Setting exists, no output code
- Fix: Add markup + AJAX handler

### 2.2 SEO meta tags — never output to <head>
- File: header.php
- Fix: Add wp_head() meta description/keywords output

### 2.3 GA/GTM/FB Pixel — never fire
- Files: header.php, footer.php
- Fix: Add tracking code injectors

### 2.4 Newsletter AJAX submit broken
- Files: functions.php, assets/js/custom.js
- Fix: Wire up AJAX handler + frontend JS

### 2.5 Preloader toggle — setting ignored
- File: header.php
- Fix: Honor the toggle

### 2.6 Duplicate preloader option
- File: Customizer files
- Fix: Deduplicate

### 2.7 Custom CSS/JS — wp_strip_all_tags strips syntax
- File: Sanitizer in customizer
- Fix: Use wp_kses_post or esc_textarea

### 2.8 Google Fonts — always loads
- File: functions.php or header.php
- Fix: Add conditional check

---

## PHASE 3: MEDIUM — Architectural & Quality

### 3.1 theme.json font mismatch
- File: theme.json
- Fix: Update to match actual enqueued font

### 3.2 Typo in defaults.php
- File: inc/customizer/defaults.php
- Fix: Correct misspelled strings

### 3.3 coming-soon.php duplicate <div>
- File: coming-soon.php
- Fix: Remove extraneous wrapper

### 3.4 Coming soon date — text to date picker
- File: Customizer field
- Fix: Change field type

### 3.5 three-colum-sidbar.php typo
- File: three-colum-sidbar.php
- Fix: Rename + register new template

### 3.6 404 fallback verification
- File: 404.php
- Fix: Verify fallback chain

### 3.7 Assets directory cleanup
- Fix: Remove unused SVGs

### 3.8 Restore Defaults function
- File: functions.php
- Fix: Add reset button in Customizer

### 3.9 Missing template files
- Files: attachment.php, author.php, category.php, tag.php, date.php, home.php
- Fix: Create bare-bones pass-through templates

---

## PHASE 4: LOW — Minor & Cosmetic

### 4.1 Remaining typo fixes
### 4.2 Code comment cleanup
### 4.3 Accessibility audits

---

## Task Status Log

