# Phantom Shopify Theme Agent — Default Session Prompt

Use this prompt at the **start of every new session or project** to bootstrap the full agent environment. All instructions below are defaults — override per task if needed.

---

## 1. Load Core Systems (ALWAYS, in order)

1. **Karpathy Guidelines** — `~/.agents/skills/andrej-karpathy-skills/skills/karpathy-guidelines/SKILL.md`
2. **Headroom** — MCP for conversation compression
3. **Caveman** — `.agents/skills/caveman/SKILL.md`
4. **Context7** — MCP for library/framework docs
5. **Impeccable** — `.agents/skills/impeccable/.agents/skills/impeccable/SKILL.md` (always loaded for UI polish, audit, quality gates)

## 2. Read Project Config

Read `mcp.txt` at the project root. It contains:
- Complete skill inventory (`./agents/skills/`)
- MCP server list
- CLI tools
- Use-case matrix for task → skill mapping
- Theme file structure reference

## 3. Default Skill Loads by Task Type

| Task | Always Load | Also Load If... |
|------|-------------|-----------------|
| Shopify themes/sections/snippets | `shopify-liquid`, `shopify-liquid-themes`, `shopify-dev` | carts/accounts/filtering → + `shopify-storefront-graphql` |
| UI / Design work | `ui-ux-pro-max`, `ui-ux-pro-max-skill`, `taste-design`, `taste-skill`, `frontend-design` | responsive/mobile → + `Responsive UI skill`; banners → + `banner-design`; brand/logos → + `brand`; design tokens → + `design-system`; wireframes → + `design`; slides → + `slides`; CSS/theming → + `ui-styling`; colors → + `color-expert` |
| Animation / motion | `design-motion-principles`, `GSAP skill` | — |
| Quality | `accessibility-compliance-accessibility-audit`, `mobile-responsiveness` | only when modifying UI |
| SEO / metadata | `seo-audit` | only when modifying schema/metadata |
| Stitch design gen | Stitch MCP + `stitch-generate-design` + `stitch-manage-design-system` + `stitch-upload-to-stitch` | — |
| Reverse engineer site | `skillui` CLI | — |

## 4. Quality Standard (Always Enforced)

- Premium Shopify theme quality
- Mobile-first
- WCAG 2.2 AA compliant
- SEO friendly
- Performance optimized (Core Web Vitals)
- Production ready
- Clean, maintainable, comments-free code
- Consistent with Shopify best practices

## 5. Verification Rules

After every change:
- Run `shopify theme check` for Liquid/theme changes
- Run `npm run lint` / `ruff` / equivalent for code changes
- Verify in browser if UI change
- Do NOT add code comments unless asked
- Do NOT add explanation postambles — just deliver

## 6. Project Structure

```
/  (project root)
├── AGENTS.md          # Behavioral prompt
├── mcp.txt            # Tool/skill inventory (READ THIS)
├── prompt.md          # This file — session init prompt
├── .agents/skills/    # 44 installed skills
├── layouts/           # Theme layouts
├── sections/          # Theme sections
├── snippets/          # Theme snippets
├── templates/         # Theme templates
├── assets/            # CSS, JS, images
├── config/            # settings_data.json, etc.
└── locales/           # Translations
```
