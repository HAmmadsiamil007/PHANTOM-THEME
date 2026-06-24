## AI Block Integration
- 3 AI-generated blocks (split image banner, footer, countdown timer) copied from `open PHANTOM/blocks/` to `horizon/blocks/`
- Horizon's `_blocks.liquid` accepts `@theme` → blocks auto-discoverable in theme editor
- Blocks are self-contained (no snippet dependencies)
- NOT available in `section.liquid` (only `_blocks.liquid` wrapper)

# PHANTOM SHOPIFY THEME AGENT

## Core Systems (Always Load)

1. **Karpathy Guidelines** — load from `.agents/skills/caveman/skills/karpathy-guidelines/SKILL.md`
2. **Headroom** — MCP for conversation compression
3. **Caveman** — load from `.agents/skills/caveman/SKILL.md`
4. **Context7** — MCP for library/framework docs
5. **Impeccable** — load from `.agents/skills/impeccable/SKILL.md` (always loaded for UI polish, audit, and quality gates)
6. **Loop Engineering** — load from `.agents/skills/loop-engineering/SKILL.md` (always loaded for quality iteration loops on generated code)

**ALWAYS load these 6 at startup. They are the minimum baseline for every session.**

## mcp.txt — Read Me First

This project has a companion `mcp.txt` file in the project root. Read it at the start of every session. It contains the complete inventory of installed skills, MCP servers, CLI tools, and the skill use-case selection matrix. Treat it as the authoritative reference for what tools and skills are available.

## General Rules

- Think before coding. State assumptions, surface tradeoffs.
- Prefer simple solutions. Minimum code, nothing speculative.
- Make surgical changes. Touch only what you must.
- Match existing code style.
- Retrieve relevant project memory from Caveman before planning.
- Use Headroom to compress history, memory, and large tool outputs.
- Use Context7 whenever framework/library documentation is needed.
- Use the minimum number of skills necessary. Do not load unnecessary skills.
- **Do not add comments to code unless the user asks.**
- **Do not add explanation summaries or postambles after making changes — just stop.**

## Skill Selection

### Shopify Tasks
Load: `shopify-liquid`, `shopify-liquid-themes`, `shopify-dev`
If carts/accounts/filtering/search/Storefront API: also load `shopify-storefront-graphql`

### UI / Design Tasks
Always load: `ui-ux-pro-max`, `ui-ux-pro-max-skill`, `taste-design`, `taste-skill`, `frontend-design`
If responsive/mobile layout: also load `Responsive UI skill`
If banners/cards/heroes: also load `banner-design`
If brand identity/logos: also load `brand`
If design systems/tokens: also load `design-system`
If page layouts/wireframes: also load `design`
If presentations/slides: also load `slides`
If CSS/styling/theming: also load `ui-styling`
If colors/palettes/themes: also load `color-expert`
If design generation: load Stitch MCP + Stitch Design Skills
If reverse engineering a site: load `skillui`

### Quality Tasks
If modifying UI/interactions: load `accessibility-compliance-accessibility-audit`, `mobile-responsiveness`
If modifying metadata/schema/SEO/CWV: load `seo-audit`

### Animation Tasks
Only when animation requested: load GSAP skills, `design-motion-principles`. Do not load otherwise.

### Documentation Tasks
Use Context7 before implementing unfamiliar APIs, libraries, frameworks, or Shopify features.

### Missing Capability
Only if no installed skill covers the task: use `find-skills`

## Output Quality Standard

Every solution must be:
- Premium Shopify theme quality
- Mobile-first
- WCAG compliant
- SEO friendly
- Performance optimized
- Production ready
- Clean and maintainable
- Consistent with Shopify best practices
