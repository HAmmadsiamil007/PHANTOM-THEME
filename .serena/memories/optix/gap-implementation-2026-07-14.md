# Gap Implementation — 2026-07-14

## Completed Gap-Fixes

### REST API (4 new routes added to `class-rest-controller.php`)
- `GET /profiles` — Lists available profiles with active/css/js metadata
- `POST /presets` — Saves current settings as a named preset (optional custom data)
- `GET /presets/{id}` — Loads a preset by name
- `POST /cache/flush` — Flushes Settings_Registry cache + Cache::flush_all()

### WP-CLI (6 new commands added to `class-commands.php`)
- `wp optix profile list [--format]` — Lists all profiles with active/css/js status
- `wp optix preset save <name>` — Captures all current settings as a preset
- `wp optix preset load <name>` — Applies a preset's settings
- `wp optix demo import <package>` — Runs demo import with per-step status output
- `wp optix status` — Shows system overview (version, profile, registry count, etc.)
- `wp optix doctor` — Runs 7 diagnostic checks with pass/warn/error summary

### CI Pipeline
- `.github/workflows/ci.yml` already existed with 3 jobs (phpcs, phpunit, validate)
- CI covers PHP 8.1/8.2, WordPress coding standards, PHPUnit, ABSPATH guards

### CONTEXT.md Updated
- Registries description: "15 concrete + 1 abstract" (was "15 data definition classes")
- Added Block_Registry to relationships section

## Test Results
- 165 tests total: 140 pass, 25 skip (no WP REST/CLI classes in stub env), 0 failures, 0 errors

## Key Bug Fixes During Implementation
- CLI `demo_import`: Fixed return key from `steps` to `log`, added proper error handling
- REST `flush_cache`: Changed from `delete_option()` marker to `Cache::get_instance()->flush_all()`

## Known Remaining Gaps (deferred)
- `rest-api.md` doc file missing — should document all 15 routes
- `status` and `doctor` WP-CLI commands reference WP core APIs unavailable in stub tests
- 17 Settings_Registry entries still short of 471-target (currently 454)
