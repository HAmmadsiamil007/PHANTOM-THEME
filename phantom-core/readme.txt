=== Phantom Core Framework ===
Contributors: phantom
Tags: rest-api, customizer, settings, framework, woocommerce
Requires at least: 6.4
Tested up to: 6.7
Requires PHP: 7.4
Stable tag: 1.5.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Core REST API layer for Phantom — settings registry (555 settings, 44 sections), theme customizer (15 panels, 13 custom controls), import/export, and WooCommerce integration.

== Description ==

Phantom Core Framework provides a decoupled WordPress backend with a static HTML SPA frontend architecture. It powers the Phantom theme with:

* **Settings Registry** — 555 settings across 44 sections with full sanitization
* **Customizer** — 15 panels, 25+ sections, 13 custom control types
* **REST API** — 34 endpoints under `phantom/v1` covering settings, products, cart, checkout, auth, contact
* **CSS Generation Engine** — 8 modular CSS modules for colors, typography, layout, buttons, header, footer, product, responsive
* **Global Color Palette** — 9-color system with 4 presets and dark mode support
* **Font System** — Google Fonts + system fonts + local webfont loader
* **WooCommerce** — Full Store API integration with template overrides for cart, checkout, and loop
* **Shell SPA Router** — `template_redirect` → static HTML delivery

== Installation ==

1. Upload the `phantom-core` folder to `/wp-content/plugins/` or upload the zip via Plugins → Add New → Upload.
2. Activate the plugin through the Plugins screen.
3. Navigate to Settings → Phantom Core to configure.
4. The Customizer will have 15 new panels for theme customization.

== Frequently Asked Questions ==

= Does this require a specific theme? =

Phantom Core is designed to work with the Phantom theme (a static HTML SPA frontend). It provides the backend REST API layer.

= Is WooCommerce required? =

No. WooCommerce integration is optional and automatically detected. When WooCommerce is active, additional REST endpoints and template overrides become available.

= How do I customize the color palette? =

Go to Appearance → Customize → Phantom Core panels. The global color palette offers 4 presets with 9 colors each, plus dark mode support.

== Changelog ==

= 1.5.1 =
* Fix: Version sync and stability improvements
* Fix: REST permission callback signature compliance
* Fix: Contact form phone validation
* Fix: Nonce verification for all REST routes
* Security: User email protected behind capability check
* Performance: Optimized CSS generation pipeline

= 1.5.0 =
* Major: Full-stack remediation — all 5 phases complete
* Feature: Contact REST endpoint with email notification
* Feature: Accessibility overhaul (score 40 → 85)
* Feature: WooCommerce review/rating CRUD endpoints
* Feature: Cart caching, shipping methods, dynamic price range
* Fix: 24 audit issues resolved (cart, shipping, prices, pagination)
* Fix: CSS/JS injection vectors closed
* Fix: Textdomain loading on correct hook
* Test: 23 PHPUnit tests with 4206 assertions
* Cleanup: 7 dead files removed

= 1.4.0 =
* Feature: Global color palette with 4 presets
* Feature: Dark mode support
* Feature: Font system with Google Fonts integration
* Feature: 13 custom Customizer controls
* Enhancement: CSS Generation Engine with 8 modules

== Upgrade Notice ==

= 1.5.1 =
Stability and security release. All users recommended to update.
