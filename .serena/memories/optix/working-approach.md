# Optix Working Approach

Always follow this workflow for all Optix tasks:

1. **Brainstorm first** — present design sections, get approval per section, write design doc
2. **Use writing-plans skill** — create detailed implementation plan with task breakdown
3. **Loop engineering** — generate → review → improve → repeat until 90%+ quality
4. **Update CONTEXT.md** — sharpen domain terminology as decisions crystallize
5. **Save to serena memory** — store key architectural decisions for future sessions
6. **Use existing codebase patterns** — singleton get_instance(), Option API with optix_ prefix, profile-based routing

Key MCP tools available:
- context7: for library/framework docs
- serena: for codebase analysis with symbol-aware tools
- Open Design: for visual design generation
- Playwright: for browser testing

## Architecture Principles
- Registries know nothing about HTML/CSS/animations
- Frontend is "dumb" — only reads and renders
- Everything comes from one source of truth (Settings_Registry)
- Profiles are swappable — drop-in replacement of HTML/CSS/JS
- Plugin owns everything engine-related; theme is thin shell + profiles

## Files
- `STATUS.md` at project root — always consult for section-by-section completion
- `docs/superpowers/specs/2026-07-14-optix-framework-architecture-design.md` — v3 final spec
- `docs/superpowers/plans/` — implementation plans
- `.superpowers/sdd/` — subagent-driven development task briefs + reports
- `mem:optix/framework-architecture` — overall architecture + status (kept in sync)
