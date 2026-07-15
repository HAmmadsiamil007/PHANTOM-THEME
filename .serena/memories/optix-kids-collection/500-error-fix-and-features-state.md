# 500 Error Fix and Features State

## Root Cause
The 500 error was caused by PHP 8+ strict signature mismatch in `Portfolio_Category::register($post_types)` vs the abstract `Taxonomy::register()` which had no parameters. Fixed by adding `$post_types = []` parameter to the abstract method.

## Additional Fixes Applied
1. **taxonomy.php:67** - `pll_translatable` array key warning fixed with `! empty()` check
2. **class-portfolio.php → portfolio.php** - Renamed file to match loader path convention (`build_post_types()` expects `portfolio.php` not `class-portfolio.php`)
3. **acf-blocks.php** - Added `register_block_type()` fallback when `acf_register_block_type()` not available (Free ACF vs PRO)
4. **class-optix-theme-options.php:437** - Added 3D effects fields to `get_field_map()` for Animations settings page
5. **defaults.php** - Added 3D effects defaults

## Features Added
- **Portfolio CPT** - Registered via `THEME_SETTINGS['post_types']`, slug `portfolio`, labels "Projects"
- **Portfolio Category taxonomy** - `portfolio_category`, hierarchical, REST enabled
- **Maintenance Mode** - Toggle in Advanced Settings, template `maintenance.php`
- **Mega Menu** - CSS in `dynamic-css.php`, JS in `optix-extras.js`
- **3D Tilt Effects** - Toggle + settings in Animations page, CSS/JS on front-end
- **ACF Blocks** - `portfolio-grid`, `project-highlight` registered (fallback for free ACF)

## Status
All features verified working. Front page loads. Admin pages accessible. Portfolio CPT admin works. Taxonomy admin works. 3D CSS outputs when enabled.
