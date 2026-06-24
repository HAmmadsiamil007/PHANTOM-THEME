# LOOP ENGINEERING SKILL
## Meta-Orchestration · Self-Improvement Loop System
**Location:** `.agents/skills/loop-engineering/SKILL.md`
**Version:** 1.0.0
**Tier:** Premium — Production Ready

---

## OVERVIEW

Loop Engineering is the art of replacing the human reviewer with an intelligent feedback system. Instead of generating one output and stopping, this skill wraps every task in a **Generate → Review → Improve → Repeat** cycle that runs until all quality scores cross configurable thresholds — or a max-iteration guard fires.

```
Traditional Prompting:     Loop Engineering:
Human → AI → Output        Task → Generator → Reviewer → Improver
    ↑                                              ↓
    └─── Manual fix ────────────────── Score ≥ Threshold? → DONE
```

A mediocre model with a strong feedback loop outperforms a powerful model running once. This skill is that feedback loop.

---

## ACTIVATION TRIGGERS

### Keyword Triggers (auto-activate on any of these)
```
"loop"           "iterate"          "improve until"
"review and fix" "make it perfect"  "production-ready"
"polish this"    "keep improving"   "score this"
"audit and fix"  "self-review"      "until it passes"
"quality gate"   "run the loop"     "agent loop"
```

### Contextual Auto-Activation (medium+ complexity tasks)
This skill activates automatically when the task involves:

| Task Type | Auto-Activate Condition |
|---|---|
| Code generation | File > 50 lines OR touches auth/payments/APIs |
| UI creation | Any component with responsive + accessibility requirements |
| Content writing | Public-facing copy, landing pages, documentation |
| Debugging | Error has been unsolved for > 1 attempt |
| Refactoring | Touching > 3 files simultaneously |
| Security-sensitive work | Any auth, session, payment, or data handling code |
| SEO-critical pages | Any page intended for organic traffic |

### Manual Invocation
```
/loop [task description]
/loop --level 3 [task]
/loop --threshold 90 --max-iter 8 [task]
/loop --domains security,accessibility [task]
```

---

## THE 5 LEVELS

Select the level based on task risk, complexity, and resource availability.

---

### LEVEL 1 — Self Review
**When to use:** Quick tasks, solo projects, low-stakes output.
**Description:** The same model generates AND reviews its own output. Fast, cheap, surprisingly effective for catching surface-level issues.

```
[Generator: Claude]
       ↓
[Reviewer: Claude (different prompt context)]
       ↓
[Improver: Claude applies its own suggestions]
       ↓
[Exit Check: Score ≥ threshold?]
```

**Prompt structure:**
```
PHASE 1 — GENERATE:
  [Your generation task here]

PHASE 2 — SELF-REVIEW:
  Now review what you just wrote. Pretend you are a senior engineer
  who did NOT write this code. Score it 0–100 for: [domains].
  List every flaw, no matter how small.

PHASE 3 — IMPROVE:
  Apply every fix from your review. Do not skip anything.

PHASE 4 — VERIFY:
  Confirm each fix was applied. Re-score. Show before/after.
```

**Typical gains:** +15–25 points per iteration.
**Max recommended iterations:** 3

---

### LEVEL 2 — Dual Agent
**When to use:** Production features, client deliverables, anything going to staging.
**Description:** One model generates, a different model (or the same model with enforced adversarial persona) reviews. Eliminates the "reviewer agrees with itself" failure mode.

```
[Generator: claude-sonnet (creator mode)]
       ↓
[Reviewer: claude-opus (critic mode) OR GPT-4o]
       ↓
[Improver: Generator receives critic's report]
       ↓
[Exit Check]
```

**Reviewer persona prompt:**
```
You are a hostile senior engineer doing a code review.
Your job is to find everything wrong with this output.
You do NOT care about hurting feelings.
You WILL fail this review if anything is below production standard.
Score ruthlessly on: [domains].
Every score below 80 requires a written justification and exact fix.
```

**Typical gains:** +25–40 points vs. single-shot.
**Max recommended iterations:** 4

---

### LEVEL 3 — Specialist Agents
**When to use:** Full feature builds, theme sections, complex UI components, anything that touches multiple concerns simultaneously.
**Description:** Different agents review different domains. Each specialist only judges their domain. Prevents any single reviewer from being "good enough" across all dimensions.

```
[Generator]
     ↓
[Security Agent] ──────────────────→ Security Score
[UI/UX Agent] ──────────────────────→ UX Score
[Performance Agent] ────────────────→ Perf Score
[Accessibility Agent] ──────────────→ A11y Score
[SEO Agent] ────────────────────────→ SEO Score
     ↓
[Aggregator: combines all reports]
     ↓
[Improver: applies all specialist fixes]
     ↓
[Exit Check: ALL scores ≥ threshold]
```

**Skill integration map:**
```yaml
security:        call → [security-audit skill] or inline security reviewer
ui_ux:           call → [ui-ux-pro-max skill] or inline UX reviewer
performance:     call → [performance-audit skill] or inline perf reviewer
accessibility:   call → [accessibility-compliance skill] or WCAG 2.1 AA checklist
seo:             call → [seo-audit skill] or inline SEO reviewer
copywriting:     call → [copywriter skill] or inline copy reviewer
code_quality:    call → [impeccable skill] or inline QA reviewer
```

**Aggregator prompt:**
```
You have received specialist reports from [N] reviewers.
Deduplicate overlapping issues.
Sort all fixes by: (1) severity, (2) domain priority.
Create a single ordered fix list.
Estimate effort for each fix: [LOW / MED / HIGH].
Output: structured JSON fix manifest.
```

**Typical gains:** +35–55 points vs. single-shot.
**Max recommended iterations:** 5

---

### LEVEL 4 — Tool Feedback
**When to use:** Any code that can be executed, tested, or linted. The ground truth.
**Description:** The AI writes code, then RUNS it. Real errors become the reviewer. No hallucinated scores — only actual pass/fail results from real tools.

```
[Generator: write code]
       ↓
[Tool Executor]
  ├── Run: npm test / pytest / cargo test
  ├── Run: eslint / prettier / ruff / clippy
  ├── Run: lighthouse (performance/SEO/A11y)
  ├── Run: axe-core (accessibility)
  └── Run: custom test suite
       ↓
[Error Parser: structured error → fix instruction]
       ↓
[Improver: fixes actual failures]
       ↓
[Re-run tools]
       ↓
[Exit: All tests pass AND lint clean AND scores ≥ threshold]
```

**Tool result parser:**
```
Parse tool output:
- Extract: error type, file, line number, message
- Classify: bug / style / warning / test failure / type error
- Priority: blocking (must fix) vs. advisory (should fix)
- Map to: specific code location
Output as: structured fix manifest with file paths and line numbers
```

**Exit condition:**
```
ALL of:
  tests: 0 failures
  lint: 0 errors (warnings allowed)
  lighthouse: performance > threshold.performance
  lighthouse: accessibility > threshold.accessibility
  lighthouse: seo > threshold.seo
  custom: all project-specific checks pass
```

**Max recommended iterations:** 8 (tools are cheap to run)

---

### LEVEL 5 — Autonomous Loop
**When to use:** Full pipelines, CI/CD integration, deploy-to-production workflows. No human in the loop.
**Description:** The system plans, builds, tests, deploys, and monitors without pausing for human review. Humans define the goal and thresholds. The loop handles everything else.

```
[TASK INPUT]
     ↓
[PLANNER: breaks task into subtasks with dependencies]
     ↓
[BUILDER: executes each subtask]
     ↓
[TESTER: Level 4 tool feedback on each subtask]
     ↓
[INTEGRATOR: assembles subtasks into final artifact]
     ↓
[FINAL REVIEW: Level 3 specialist agents]
     ↓
[DEPLOY: push to staging → run smoke tests]
     ↓
[MONITOR: check live metrics for 5 min post-deploy]
     ↓
[DECISION: metrics healthy? → promote to prod : rollback]
```

**Safety gates for Level 5:**
```yaml
# MANDATORY — never skip these in autonomous mode
pre_deploy_gate:
  - all_tests_pass: true
  - no_security_critical: true
  - performance_regression: false
  - human_approval_required: true   # override to false only with explicit config

post_deploy_gate:
  - error_rate_delta: < 0.1%
  - p95_latency_delta: < 200ms
  - rollback_trigger: error_rate > 1% within 5min
```

> ⚠️ **Level 5 requires explicit user opt-in.** Default config caps at Level 4. Never deploy to production autonomously without `autonomous_deploy: true` in config.

---

## CORE FORMULA

Every loop, regardless of level, runs these 4 components:

### 1. GENERATOR
Creates the first version of the artifact.

**Inputs:** Task description, context, constraints, style guides, existing codebase.
**Outputs:** First draft artifact.

**Generator prompt template:**
```
CONTEXT:
  Project: {project_name}
  Stack: {tech_stack}
  Standards: {coding_standards}
  Existing patterns: {codebase_patterns}

TASK: {task_description}

CONSTRAINTS:
  - Follow existing naming conventions
  - Match the current file structure
  - Do not introduce new dependencies without noting them
  - Target: {target_quality_threshold}% quality score

OUTPUT: Complete, working implementation. No TODOs. No placeholders.
```

---

### 2. REVIEWER
Finds every flaw in the current artifact. Outputs a scored report.

**Reviewer prompt template:**
```
You are a world-class senior {role} reviewing the following output.

ARTIFACT:
{artifact}

REVIEW DOMAINS: {active_domains}

For each domain, provide:
  1. Score: 0–100
  2. Critical issues (blocking): list with exact locations
  3. Major issues (important): list with exact locations
  4. Minor issues (polish): list
  5. What is done well (do not change)

SCORING GUIDE: (see Scoring Rubrics section)

OUTPUT FORMAT:
{
  "domain_scores": { "security": N, "performance": N, ... },
  "aggregate_score": N,
  "blocking_issues": [...],
  "major_issues": [...],
  "minor_issues": [...],
  "preserve": [...],
  "ready_to_ship": true/false
}
```

---

### 3. IMPROVER
Applies the reviewer's findings. Never skips. Never argues.

**Improver prompt template:**
```
You have a review report for an artifact. Apply ALL fixes.

CURRENT ARTIFACT:
{current_artifact}

REVIEW REPORT:
{review_report}

RULES:
  - Fix every blocking issue. No exceptions.
  - Fix every major issue unless it conflicts with a constraint (explain why).
  - Fix minor issues if they take < 3 lines. Otherwise note them.
  - Do NOT change anything in the "preserve" list.
  - Do NOT introduce new patterns not already in the codebase.
  - After applying fixes, list what was changed and why.

OUTPUT:
  1. Improved artifact (complete, not a diff)
  2. Change manifest: [ { issue: "...", fix: "...", lines_changed: N } ]
```

---

### 4. EXIT CONDITION
Decides whether to loop again or finalize.

**Exit condition logic:**
```python
def check_exit(scores: dict, config: LoopConfig, iteration: int) -> ExitDecision:
    # Guard: max iterations reached
    if iteration >= config.max_iterations:
        return ExitDecision(
            stop=True,
            reason="MAX_ITERATIONS_REACHED",
            forced=True
        )

    # Check all active domains meet threshold
    for domain in config.active_domains:
        threshold = config.thresholds.get(domain, config.default_threshold)
        if scores[domain] < threshold:
            return ExitDecision(
                stop=False,
                reason=f"{domain} score {scores[domain]} < threshold {threshold}",
                next_focus=domain
            )

    # All domains pass
    return ExitDecision(
        stop=True,
        reason="ALL_THRESHOLDS_MET",
        forced=False
    )
```

---

## SCORING RUBRICS

### CODE QUALITY (0–100)
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
| **Total** | **100** |

---

### SECURITY (0–100)
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
| **Total** | **100** |

---

### PERFORMANCE (0–100)
| Checkpoint | Points |
|---|---|
| No N+1 query patterns | 15 |
| Database queries have appropriate indexes | 12 |
| Heavy operations are async/non-blocking | 12 |
| No synchronous file I/O in request path | 10 |
| Response payload is minimal (no over-fetching) | 10 |
| Images optimized (WebP, lazy load, correct sizing) | 10 |
| No render-blocking scripts in `<head>` | 8 |
| Caching headers set correctly | 8 |
| Bundle size within project limits | 8 |
| Core Web Vitals pass (LCP < 2.5s, CLS < 0.1, FID < 100ms) | 7 |
| **Total** | **100** |

---

### ACCESSIBILITY / A11Y (0–100)
| Checkpoint | Points |
|---|---|
| Color contrast ≥ 4.5:1 (WCAG AA) | 15 |
| All interactive elements keyboard-navigable | 15 |
| All images have descriptive `alt` text | 12 |
| Form inputs have associated `<label>` elements | 12 |
| ARIA roles used correctly (not overused) | 10 |
| Focus indicators visible on all focusable elements | 10 |
| No content relies solely on color to convey meaning | 8 |
| Heading hierarchy is logical (no skipped levels) | 8 |
| Landmark regions defined (`main`, `nav`, `footer`) | 5 |
| Error messages are descriptive and linked to fields | 5 |
| **Total** | **100** |

---

### UI / UX (0–100)
| Checkpoint | Points |
|---|---|
| Layout is responsive at 320px, 768px, 1280px, 1920px | 18 |
| Spacing uses consistent scale (no arbitrary pixel values) | 12 |
| Interactive states defined: hover, active, focus, disabled | 12 |
| Loading states handled (skeleton / spinner / optimistic UI) | 10 |
| Empty states handled (not just blank space) | 8 |
| Error states handled with clear user guidance | 8 |
| Typography scale is consistent with design system | 8 |
| Touch targets ≥ 44×44px on mobile | 8 |
| Animations respect `prefers-reduced-motion` | 8 |
| Visual hierarchy guides user to primary action | 8 |
| **Total** | **100** |

---

### SEO (0–100)
| Checkpoint | Points |
|---|---|
| `<title>` tag present, unique, 50–60 chars | 15 |
| Meta description present, unique, 120–160 chars | 12 |
| H1 present, contains primary keyword, only one per page | 12 |
| Heading structure logical and keyword-rich | 10 |
| All images have `alt` text with descriptive keywords | 10 |
| Page URL is clean, lowercase, hyphenated (no query strings for content) | 8 |
| Canonical tag set correctly | 8 |
| Structured data (JSON-LD schema) implemented where relevant | 8 |
| Internal links use descriptive anchor text | 8 |
| Core Web Vitals pass (impacts rankings) | 9 |
| **Total** | **100** |

---

### COPYWRITING (0–100)
| Checkpoint | Points |
|---|---|
| Headline has clear value proposition | 20 |
| First sentence hooks immediately (no preamble) | 15 |
| Language matches target audience vocabulary | 12 |
| Benefits emphasized over features | 12 |
| CTA is specific and action-oriented (not "Click Here") | 10 |
| No jargon unless audience-appropriate | 8 |
| Readability score: Flesch-Kincaid ≥ 60 for general audiences | 8 |
| Social proof or credibility signals present | 8 |
| Urgency or motivation to act is present | 7 |
| **Total** | **100** |

---

## CONFIGURATION

### Default Configuration
```yaml
# .agents/skills/loop-engineering/config.yaml

loop_engineering:
  # ── Level ─────────────────────────────────────────────
  default_level: 1                  # 1–5
  auto_escalate: true               # bump level if score stagnates

  # ── Iteration Control ─────────────────────────────────
  max_iterations: 5                 # hard stop regardless of scores
  stagnation_threshold: 3           # if score improves < 3pts, escalate level or stop
  
  # ── Quality Thresholds ────────────────────────────────
  default_threshold: 85             # applies to all domains unless overridden
  thresholds:
    code_quality: 85
    security: 90                    # security is held to higher standard
    performance: 80
    accessibility: 85
    ui_ux: 80
    seo: 75
    copywriting: 80

  # ── Active Domains ────────────────────────────────────
  # Override per-task. Omitting a domain skips its review.
  active_domains:
    - code_quality
    - security
    - performance
    - accessibility
    - ui_ux
    # - seo          # add for public-facing pages
    # - copywriting  # add for content tasks

  # ── Output Verbosity ──────────────────────────────────
  verbosity: "summary"              # "silent" | "summary" | "verbose" | "debug"
  # silent:  only final artifact
  # summary: final artifact + scorecard
  # verbose: artifact + scorecard + per-iteration reports
  # debug:   everything including reviewer raw output

  # ── Intermediate Output ───────────────────────────────
  show_intermediate: false          # show each iteration's output? false = only final
  show_diff: false                  # show what changed each iteration?

  # ── Skill Integration ─────────────────────────────────
  use_specialist_skills: true       # call registered skills in Level 3+
  skill_registry:
    security:       "security-audit"
    ui_ux:          "ui-ux-pro-max"
    accessibility:  "accessibility-compliance"
    seo:            "seo-audit"
    code_quality:   "impeccable"
    copywriting:    "copywriter"
    performance:    "performance-audit"

  # ── Autonomous Mode (Level 5 only) ────────────────────
  autonomous_deploy: false          # MUST be explicitly set to true
  deploy_target: "staging"          # "staging" | "production"
  rollback_on_regression: true
```

### Per-Task Override Examples
```bash
# Quick self-review on a simple component
/loop --level 1 --max-iter 2 --domains code_quality,ui_ux "refactor the cart button"

# High-security feature, strict thresholds
/loop --level 3 --threshold-security 95 --threshold-performance 90 "build the login flow"

# Landing page — copywriting + SEO + UX
/loop --level 2 --domains copywriting,seo,ui_ux --threshold 88 "write the pricing page"

# Full production feature, show everything
/loop --level 4 --verbosity verbose --show-intermediate "build the checkout process"

# Silent mode — only give me the final result
/loop --verbosity silent --max-iter 5 "fix the navigation bug"
```

---

## INTEGRATION PROTOCOL

### How the Loop Calls Specialist Skills

When `use_specialist_skills: true` and running Level 3+, the loop delegates each review domain to a registered skill. The protocol:

```
LOOP ORCHESTRATOR
     │
     ├─── Calls: security-audit skill
     │      Input:  current artifact + "review for security only, output JSON scores"
     │      Output: { score: N, issues: [...], fixes: [...] }
     │
     ├─── Calls: ui-ux-pro-max skill  
     │      Input:  current artifact + "review for UX only, output JSON scores"
     │      Output: { score: N, issues: [...], fixes: [...] }
     │
     ├─── Calls: accessibility-compliance skill
     │      Input:  current artifact + "WCAG 2.1 AA audit, output JSON scores"
     │      Output: { score: N, issues: [...], fixes: [...] }
     │
     └─── Aggregator collects all JSON, deduplicates, creates unified fix manifest
```

### Fallback (No Skill Registered)
If a domain has no registered skill, the loop falls back to an inline specialist prompt:

```
You are a world-class {domain} specialist.
Your ONLY job is to review the following for {domain} quality.
Do not comment on other aspects.
Use the {domain} scoring rubric (defined in LOOP_ENGINEERING/SKILL.md).
Output: JSON matching the specialist output schema.
```

### Skill Communication Schema
All skills called inside a loop MUST return this format:
```json
{
  "domain": "security",
  "score": 72,
  "threshold": 90,
  "passed": false,
  "blocking_issues": [
    {
      "id": "SEC-001",
      "severity": "critical",
      "description": "User input used directly in SQL query at line 47",
      "file": "src/db/queries.ts",
      "line": 47,
      "fix": "Use parameterized query: db.query('SELECT * FROM users WHERE id = $1', [userId])"
    }
  ],
  "major_issues": [],
  "minor_issues": [],
  "preserve": ["The error handling structure at lines 55-62 is correct, do not change it"]
}
```

---

## FULL LOOP PROTOCOL

Step-by-step execution from invocation to final output:

```
╔══════════════════════════════════════════════════════════╗
║                  LOOP ENGINEERING PROTOCOL               ║
╠══════════════════════════════════════════════════════════╣
║                                                          ║
║  1. PARSE INVOCATION                                     ║
║     - Extract task, level, config overrides              ║
║     - Load config: defaults → project config → overrides ║
║     - Determine active domains                           ║
║     - Log: "Loop Engineering activated | Level N | Max N"║
║                                                          ║
║  2. BASELINE GENERATION (Iteration 0)                    ║
║     - Run Generator with full context                    ║
║     - Store as: artifact_v0                              ║
║     - If verbosity=verbose: display artifact_v0          ║
║                                                          ║
║  3. INITIAL REVIEW (sets baseline scores)                ║
║     - Run Reviewer on artifact_v0                        ║
║     - Store baseline scores: { domain: score, ... }      ║
║     - Run EXIT CHECK — if already passing, output now    ║
║                                                          ║
║  4. IMPROVEMENT LOOP (iterations 1..max)                 ║
║     For each iteration:                                  ║
║     a. Run Improver on current artifact + review report  ║
║     b. Run Reviewer on improved artifact                 ║
║     c. Store iteration scores                            ║
║     d. Check stagnation: score_delta < stagnation_threshold?║
║        → If yes: warn + escalate level or stop           ║
║     e. Run EXIT CHECK                                    ║
║        → If STOP: break loop, proceed to output         ║
║        → If CONTINUE: next iteration                    ║
║                                                          ║
║  5. FINAL OUTPUT                                         ║
║     - Emit final artifact                                ║
║     - Emit scorecard (see Output Format)                 ║
║     - Emit change summary                                ║
║     - Emit: warnings if forced-stop by max_iterations    ║
║                                                          ║
╚══════════════════════════════════════════════════════════╝
```

---

## OUTPUT FORMAT

### Final Artifact
The complete, improved artifact. No diffs. No partial code. The full, working file.

### Scorecard
```
┌─────────────────────────────────────────────────────────────┐
│  LOOP ENGINEERING SCORECARD                                  │
│  Task: [task description]                                    │
│  Level: 2 | Iterations: 3 / 5 | Exit: THRESHOLDS_MET        │
├─────────────────────┬──────────┬──────────┬──────────────────┤
│ Domain              │ Start    │ Final    │ Δ Change         │
├─────────────────────┼──────────┼──────────┼──────────────────┤
│ Code Quality        │  61/100  │  89/100  │ ↑ +28  ✅ PASS  │
│ Security            │  45/100  │  92/100  │ ↑ +47  ✅ PASS  │
│ Performance         │  70/100  │  84/100  │ ↑ +14  ✅ PASS  │
│ Accessibility       │  52/100  │  87/100  │ ↑ +35  ✅ PASS  │
│ UI / UX             │  68/100  │  82/100  │ ↑ +14  ✅ PASS  │
├─────────────────────┼──────────┼──────────┼──────────────────┤
│ AGGREGATE           │  59/100  │  87/100  │ ↑ +28  ✅ PASS  │
└─────────────────────┴──────────┴──────────┴──────────────────┘
```

### Change Summary
```
CHANGES MADE (3 iterations):

ITERATION 1 — 8 fixes applied:
  [SEC-001] Fixed SQL injection: parameterized all 3 user queries
  [SEC-002] Removed hardcoded API key from config.ts → moved to env
  [A11Y-001] Added alt text to 5 product images
  [A11Y-002] Added aria-label to icon-only buttons (cart, search, close)
  [PERF-001] Added lazy loading to below-fold images
  [PERF-002] Moved 2 render-blocking scripts to end of body
  [UX-001] Added loading spinner to async form submission
  [CODE-001] Extracted magic number 1440 → MAX_DESKTOP_WIDTH constant

ITERATION 2 — 4 fixes applied:
  [SEC-003] Added CSRF token to checkout form
  [A11Y-003] Fixed heading hierarchy: H3 was skipping H2 on mobile menu
  [PERF-003] Images converted to WebP with fallback
  [UX-002] Added empty state to search results

ITERATION 3 — 2 fixes applied:
  [CODE-002] Removed 3 unused imports flagged in iteration 2 review
  [UX-003] Focus trap implemented in modal (keyboard navigation)

ITEMS DEFERRED (below threshold effort):
  [MINOR-001] Lighthouse score 84 vs 85 target — 1 point below (font-display: swap)
              → Accepted: within acceptable range, non-blocking
```

---

## USE CASES & EXAMPLES

### Use Case 1: Login Page (Level 3)
```
User: /loop --level 3 --domains security,accessibility,ui_ux "build a login form"

Loop:
  Generator → Creates login.tsx with form, validation, API call
  
  Specialist Reviews:
    Security:      score=58 → SQL injection risk, no CSRF, no rate limiting
    Accessibility: score=49 → Missing labels, no error announcement, bad contrast
    UI/UX:         score=71 → No loading state, no "forgot password" affordance
  
  Aggregator → 15 fixes, sorted by severity
  
  Improver → Applies all 15 fixes
  
  Re-review:
    Security:      score=91 ✅
    Accessibility: score=88 ✅
    UI/UX:         score=84 ✅
  
  Exit: ALL PASS → Output final login.tsx + scorecard
```

---

### Use Case 2: Landing Page Headline (Level 1, Self-Review)
```
User: /loop --level 1 --domains copywriting "write a SaaS landing page headline"

Loop:
  Generator → "Manage your projects better."  [score: 41]
  
  Reviewer:
    Hook strength:        3/20 — generic, no differentiation
    Conversion potential: 8/20 — no urgency, no benefit
    Uniqueness:           6/20 — used by thousands of SaaS sites
    Audience clarity:     4/20 — who is this for?
    Total: 41/100
  
  Improver → "Ship features 3× faster — without the standup chaos."
  
  Re-review: score=87 ✅ → Output
```

---

### Use Case 3: Bugfix with Tool Feedback (Level 4)
```
User: /loop --level 4 "fix the broken checkout flow"

Loop:
  Generator → Analyzes error logs, writes fix for race condition
  
  Tool Executor:
    → npm test: 3 failures (checkout.test.ts)
    → eslint: 2 errors
  
  Error Parser → 5 structured fix instructions
  
  Improver → Applies all 5 fixes
  
  Tool Re-run:
    → npm test: 0 failures ✅
    → eslint: 0 errors ✅
  
  Final Review (score-based):
    Code Quality: 88 ✅
    Security:     91 ✅
  
  Exit: TOOLS_CLEAN + THRESHOLDS_MET → Output
```

---

## ANTI-PATTERNS TO AVOID

| Anti-Pattern | Problem | Solution |
|---|---|---|
| No exit condition | Infinite loop | Always set `max_iterations` |
| Reviewer too lenient | Scores inflate, loop exits too early | Use adversarial reviewer persona |
| All domains active always | Slow, expensive, irrelevant checks | Activate only relevant domains |
| Improver skips "hard" fixes | Loop stagnates, wastes iterations | Improver prompt must be non-negotiable |
| Score without location | "Security: 60" with no fix instructions | Require file + line in every issue |
| No stagnation detection | Loop burns all iterations on tiny gains | Implement `stagnation_threshold` |
| Level 5 without human gate | Autonomous deploys break prod | Never set `autonomous_deploy: true` without review |

---

## STAGNATION HANDLING

If the score improves by less than `stagnation_threshold` points across an iteration:

```
IF score_delta < stagnation_threshold:
  
  IF current_level < 3:
    → Escalate level (try specialist agents or tool feedback)
    → Log: "Stagnation detected. Escalating to Level N."
  
  ELSE IF remaining_iterations > 1:
    → Request human input: "Score stagnated at N/100. 
       Remaining issues may require architectural changes.
       Proceed with next iteration or accept current state?"
  
  ELSE:
    → Stop. Return current artifact with warning.
    → Log: "Loop stagnated. Final score: N/100. Unresolved issues: [list]"
```

---

## QUICK REFERENCE

```
FORMULA:    Generate → Review → Improve → [Exit?] → Repeat
LEVELS:     1=Self  2=Dual  3=Specialist  4=Tools  5=Autonomous
EXIT:       Score ≥ threshold AND iteration < max_iterations
SCORES:     0–100 per domain, default threshold 85
CONFIG:     .agents/skills/loop-engineering/config.yaml
INVOKE:     /loop [--level N] [--threshold N] [task]
OUTPUT:     Final artifact + scorecard + change summary
```

---

## VERSION HISTORY

| Version | Change |
|---|---|
| 1.0.0 | Initial release — 5 levels, 7 scoring rubrics, full protocol |

---

*Loop Engineering Skill — Premium Tier*
*Designed to ship with production-grade agent systems.*
*The biggest improvements come from better feedback loops, not better models.*
