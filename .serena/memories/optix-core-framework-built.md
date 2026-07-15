# Optix Core Framework — Phase 1 Complete

## What was built

### Plugin: `/optix-core/` (17 PHP files, all pass `php -l`)
| File | Purpose |
|------|---------|
| `optix-core.php` | Plugin header, autoloader, activation/deactivation hooks |
| `includes/class-core-plugin.php` | Main plugin class — inits all modules in dependency order |
| `includes/class-options-manager.php` | 4-tier get() (Options API → ACF → theme_mod → defaults), set(), migrate_from_theme(). Full defaults array (~200+ keys) |
| `includes/class-profile-router.php` | Routes template_include, get_template_part, wc_get_template, stylesheet_uri, template_directory_uri. 3-level fallback chain |
| `includes/class-cpt-manager.php` | Registers Portfolio CPT via base Post_Type class |
| `includes/class-taxonomy-manager.php` | Registers Portfolio Category taxonomy |
| `includes/post-type.php` | Base Post_Type abstract class (OptixCore namespace) |
| `includes/taxonomy.php` | Base Taxonomy abstract class (OptixCore namespace) |
| `includes/post-types/portfolio.php` | Portfolio CPT labels + args |
| `includes/taxonomies/portfolio-category.php` | Portfolio Category taxonomy labels + args |
| `includes/class-dynamic-css-generator.php` | wp_head CSS output. ALL selectors filterable (optix/css/mega-menu, optix/css/tilt-3d, optix/css/hero, optix/css/heading, optix/css/body) |
| `includes/class-maintenance-mode.php` | template_redirect handler, 3-level template fallback, wp_die() fallback |
| `includes/class-mega-menu.php` | Adds .optix-mega-menu class to primary nav when enabled |
| `includes/class-3d-effects.php` | Inline JS for 3D tilt effect on .optix-tilt-3d elements |
| `includes/class-acf-blocks.php` | Registers portfolio-grid + project-highlight ACF blocks (ACF optional) |
| `includes/class-theme-api.php` | optix_asset_url(), optix_img() public helpers |
| `admin/class-settings-page.php` | Settings → Optix Framework: profile dropdown, available profiles table, preview links |
| `templates/maintenance.php` | Maintenance mode template (3rd-level fallback) |

### Theme Profile: `/profiles/default/`
- All root templates copied into profiles/default/
- Assets (CSS/JS/images) from assets/kids-collection/ → profiles/default/assets/
- Template parts from template-parts/ → profiles/default/template-parts/
- `functions.php` with CSS selector overrides (optix/css/* filters)
- 13 PHP template files, all passing `php -l`

### Docker Integration
- `docker-compose.yml` updated: added `- ./optix-core:/var/www/html/wp-content/plugins/optix-core` volume

## Architecture
```
Plugin (optix-core/)        — Stores ALL data, registers CPTs, generates CSS
  Profile Router              — Routes template loading to active profile
  Options Manager             — 4-tier fallback, full cache

Theme (optix/)              — Pure presentation
  profiles/default/           — Current kids-collection migrated
  profiles/shoe-store/        — Future client profiles
  functions.php               — Minimal (theme support + enqueue)
```

## Next Steps (Phase 2)
- `docker compose up -d` to start containers
- Activate plugin in /wp-admin/plugins.php
- Visit Settings → Optix Framework to select profile
- Create demo profiles: blog, business, real-estate
- Profile template starter kit (scaffold command)
- Profile export/import as zip
