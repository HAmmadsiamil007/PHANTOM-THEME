# Quality Review — Phase 3 Registries

**Goal:** Achieve 100/100 on Code Quality and Security rubrics for all 11 registry files.

## Files to Review
All in `C:\Users\hamma\Downloads\wordpress\optix-core\includes\registry/`:
- `class-layout-registry.php` (pure data)
- `class-woocommerce-registry.php` (pure data)
- `class-typography-registry.php` (pure data)
- `class-color-registry.php` (pure data)
- `class-responsive-registry.php` (pure data)
- `class-animation-registry.php` (pure data)
- `class-hook-registry.php` (pure data)
- `class-block-registry.php` (2 getter methods)
- `class-preset-registry.php` (CRUD methods + apply())
- `class-demo-registry.php` (6 getter methods)
- `class-asset-registry.php` (init + enqueue + registration methods)

## Known Issues to Fix

### Issue 1: Asset_Registry redundant register() calls
`Asset_Registry` calls `$this->register()` in every public method (`init()`, `add_asset()`, `get_asset()`, `remove_asset()`, `register_style()`, `register_script()`, `enqueue_assets()`). This is redundant since `class-core-plugin.php` already calls `register()`. Remove all `$this->register()` calls — the guard `if ($this->registered) return;` makes them harmless but they violate single-responsibility.

### Issue 2: Preset_Registry::load() false-positive check
Line 46: `if (false === $value) { return null; }`
Since `get_option()` is called with default `null`, a non-existent option returns `null`, not `false`. A stored `false` value would incorrectly return `null`. Change to: `if (null === $value) { return null; }`

### Issue 3: Demo_Registry uses unconventional entry nesting
All demo package data is nested under `'default'` key within each entry (e.g., `$entry['default']['steps']`). The getter methods bypass `Base_Registry::get()` by accessing `$this->entries` directly. This works but is unconventional.

Fix: Move package data to the entry top level (remove the `'default'` wrapper), and update all 6 getter methods to use `$entry['...']` instead of `$entry['default']['...']`.

### Issue 4: Hook_Registry params
Hook entries have `'params' => []` — verify this is correct for documentation-only registry.

### Issue 5: Color_Registry magic number field keys
Color entries use short keys like `'primary'`, `'text'`, `'heading'`, `'background'` — verify they have proper context with `'label'` fields.

## Rubrics

### Code Quality (100 points)
| Checkpoint | Points |
|---|---|
| No syntax errors or type errors | 15 |
| No runtime exceptions in happy path | 15 |
| Error handling covers all failure modes | 12 |
| No dead code, unused variables, unused imports | 8 |
| Functions are single-responsibility (< 30 lines each) | 10 |
| Naming is descriptive and consistent with codebase | 10 |
| No magic numbers — all values are named constants | 8 |
| Adequate comments on non-obvious logic | 8 |
| No code duplication (DRY) | 7 |
| Follows project's existing architectural patterns | 7 |

### Security (100 points)
| Checkpoint | Points |
|---|---|
| No SQL injection vectors (parameterized queries only) | 18 |
| No XSS vectors (output escaped, CSP present) | 15 |
| No hardcoded secrets, tokens, or passwords | 15 |
| Authentication checks on all protected routes | 15 |
| Input validation on all user-supplied data | 12 |
| Sensitive data not logged or exposed in errors | 10 |
| CORS configured correctly | 8 |
| Dependencies have no known critical CVEs | 7 |

## Process
1. Review all 11 files for Code Quality and Security issues
2. Score each domain 0-100
3. Fix ALL issues found (fix every issue, no exceptions)
4. Re-verify: run `php -l` on all modified files
5. Report final scores

Write full report to `C:\Users\hamma\Downloads\wordpress\.superpowers\sdd\qa-report.md`
