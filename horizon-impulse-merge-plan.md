# Horizon + Impulse Merge Plan

## Strategy
- Keep improved Horizon sections — do NOT remove them
- Only add Impulse features Horizon lacks
- Adapt each feature to work standalone (no Impulse modal/JS framework)
- Prefix Impulse files: `impulse-` for sections, `impulse-` for required snippets
- Git branch per feature for safe rollback
- Test: `shopify theme push` with zero errors per feature

## Features to Merge (Priority Order)

### P1 — Core Conversion/Marketing
| # | Feature | Impulse File(s) | Deps to Create | Effort |
|---|---------|----------------|----------------|--------|
| 1 | Newsletter Popup | `newsletter-popup.liquid` | `impulse-image-element.liquid`, `impulse-newsletter-form.liquid`, `impulse-newsletter-reminder.liquid` | Medium |
| 2 | Promo Grid | `promo-grid.liquid` | `impulse-image-element.liquid`, `promo-grid CSS` | High |
| 3 | Recently Viewed | `recently-viewed.liquid` | JS for localStorage tracking | Medium |
| 4 | Logo List | `logo-list.liquid` | (none — Horizon has `logo-list.liquid` already, check if upgrade needed) | Low |
| 5 | Map | `map.liquid` | (none — standalone Google Maps embed) | Low |
| 6 | Store Availability | `store-availability.liquid` | JS for modal drawer | Medium |

### P2 — Content Builder Enhancements
| # | Feature | Impulse File(s) | Deps to Create | Effort |
|---|---------|----------------|----------------|--------|
| 7 | Advanced Content | `advanced-content.liquid` | `impulse-image-element.liquid` | High |
| 8 | Text With Icons | `text-with-icons.liquid` | `impulse-image-element.liquid` | Low |
| 9 | Background Image Text | `background-image-text.liquid` | (standalone) | Low |
| 10 | Background Video Text | `background-video-text.liquid` | (standalone) | Low |

### P3 — Media Sections
| # | Feature | Impulse File(s) | Deps to Create | Effort |
|---|---------|----------------|----------------|--------|
| 11 | Featured Video | `featured-video.liquid` | (standalone) | Low |
| 12 | Hero Video | `hero-video.liquid` | (standalone) | Low |
| 13 | Text And Image | `text-and-image.liquid` | `impulse-image-element.liquid` | Low |

## Dependency Snippets to Create

| Snippet | Purpose | Used By |
|---------|---------|---------|
| `impulse-image-element.liquid` | Multi-mode image rendering (Shopify CDN, assets, placeholder) | newsletter-popup, promo-grid, advanced-content, text-with-icons, text-and-image |
| `impulse-newsletter-form.liquid` | Newsltter email capture form | newsletter-popup |
| `impulse-newsletter-reminder.liquid` | Reminder bell icon (custom element) | newsletter-popup |

## NOT to Merge (Keep Horizon Versions)
- announcement bar, header, footer, cart, search, blog, collection templates, product templates, slideshow, testimonials, contact form, FAQs, featured product, featured collection, rich text, predictive search, product recommendations
