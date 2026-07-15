# Optix Theme CMS-ification Specification (v2 — Ground-Truth Edition)

## 1. Overview

**Goal:** Convert the static Optix Kids Collection theme into a dynamic CMS-powered WordPress theme. All hardcoded content editable via ACF Options Pages. Original design preserved as defaults.

**Key paradigm shift from v1:** The spec is now grounded in the ACTUAL theme files. Key discoveries:
- Template parts are in `template-parts/kids-collection/` (not root)
- A full 598-line `Nav_Walker` already exists
- 30+ templates exist (not 10)
- WooCommerce page templates already exist
- `page-kids-collection.php` routes page slugs to templates
- Testimonials use Bootstrap Carousel (front-page) AND Owl (about-page)
- All animations use `data-wow-duration="2s"` not 1s
- Include chain: `functions.php → inc/hooks.php → inc/hooks/*.php`

**Key files (new & existing):**
| File | Role |
|------|------|
| `inc/class-optix-theme-options.php` | NEW — Options panel |
| `inc/template-functions.php` | EXISTING — add helpers |
| `inc/dynamic-css.php` | NEW — Live CSS generation |
| `inc/includes/nav-walker.php` | EXISTING (598 lines) — EXTEND not replace |
| `inc/hooks.php` | EXISTING — add require for new files |
| `inc/hooks/acf-blocks.php` | EXISTING — preserve |
| `inc/hooks/scripts-styles.php` | EXISTING — preserve |
| `inc/hooks/gutenberg.php` | EXISTING (231 lines) — preserve |
| `inc/template-tags/acf-blocks.php` | EXISTING (147 lines) — preserve |
| `template-parts/kids-collection/*.php` | 5 template parts to modify |
| `templates/kids-collection/*.php` | 30+ templates to modify |
| `page-kids-collection.php` | EXISTING — slug routing, preserve WC bypass |

---

## 2. Complete Current State Analysis

### Root template files (wrappers, NOT modified):
| File | Lines | Purpose |
|------|-------|---------|
| `header.php` | 38 | `<head>`, opens `#page .home_banner_outer`, loads topbar, site-header, search-overlay |
| `footer.php` | 33 | Loads newsletter, site-footer, preloader; closes wrappers; wp_footer() |
| `front-page.php` | 16 | Just: `get_header(); get_template_part('templates/kids-collection/front-page'); get_footer();` |
| `page-kids-collection.php` | 42 | **Critical**: Slug-based template router + WC page bypass |
| `page.php` | 25 | Default page template fallback |

### Kids Collection Template Parts (ACTUAL files to modify):
| File | Lines | Content |
|------|-------|---------|
| `template-parts/kids-collection/topbar.php` | 48 | Sale notification text, language dropdown (EN/USA), currency dropdown (USD/EUR/GBP/INR/PKR) |
| `template-parts/kids-collection/site-header.php` | 86 | Logo, Bootstrap navbar with dropdowns (Home→Kid's Collection, About, Blog→Blog/Single Blog, Pages→10 links, Contact), cart/search/login icons |
| `template-parts/kids-collection/site-footer.php` | 83 | 4-col footer: logo+about+social, Navigation links, Support links (ToU/Privacy/Cookie), Contact (phone/email/address), copyright+payment cards |
| `template-parts/kids-collection/search-overlay.php` | 13 | Fullscreen search form |
| `template-parts/kids-collection/preloader.php` | 14 | Loading spinner (loaded in footer.php, NOT header) |
| `template-parts/kids-collection/newsletter.php` | 22 | Email subscribe form (action: `/wp-forms/subscribe`) |

### Kids Collection Templates (30 files — ALL need review):
| File | Lines | Sections |
|------|-------|----------|
| `front-page.php` | 416 | Banner, Promotion(3), Our Collection(products→6), CTA Sale, Product Categories(6), Top Selling(4), Testimonials (Bootstrap Carousel, 4 slides), Instagram Follow(4), Benefits(4) |
| `about.php` | 432 | Sub-banner, editor content, About Us, Mission, Team(OWL Carousel, 8 members), Product Categories(6), Instagram Follow, Benefits |
| `shop.php` | 653 | Sub-banner, sidebar (search/categories/filter by price/colors/size), grid 12 products, pagination |
| `product-detail.php` | 888 | Sub-banner, image gallery, product info, tabs (description/additional info/reviews), related 12 (Owl), accordion |
| `cart.php` | 262 | Sub-banner, cart table, coupon, totals |
| `checkout.php` | 280 | Sub-banner, billing/shipping, payment |
| `blog.php` | 524 | Sub-banner, tabbed (All/Advices/Announcements/News/Consultation/Development), posts, pagination |
| `single-blog.php` | 313 | Sub-banner, article, sidebar (search/categories/social/tags/feeds), comments |
| `contact.php` | 140 | Sub-banner, editor content, Contact Info(3 cards), Contact Form, Map |
| `coming-soon.php` | 41 | Fullscreen countdown (days/hours/min/sec via counter.js) |
| `load-more.php` | 260 | Blog posts with load-more (image/video/audio formats) |
| `join-now.php` | 76 | Registration form (name/email/password/referral) |
| `404.php` | 35 | Error page with back-to-home |
| `team.php` | ~250 | Team page |
| `testimonials.php` | ~250 | Testimonials page |
| `faq.php` | ~300 | FAQ accordion |
| `login.php` | ~40 | Login page |
| `thank-you.php` | ~30 | Thank you page |
| `privacy-policy.php` | ~60 | Privacy policy |
| `cookie-policy.php` | ~60 | Cookie policy |
| `term-of-use.php` | ~50 | Terms of use |
| `one-column.php` | ~200 | Blog layout |
| `two-column.php` | ~250 | Blog layout |
| `three-column.php` | ~200 | Blog layout |
| `three-colum-sidbar.php` | ~500 | Blog layout with sidebar |
| `four-column.php` | ~300 | Blog layout |
| `six-colum-full-wide.php` | ~400 | Blog layout |

---

## 3. Architecture

### 3.1 Existing Integration Chain (PRESERVE)

```
functions.php
  ├── inc/hooks.php              ← ADD: require for optix-theme-options.php
  │   ├── inc/hooks/general.php          ← EXISTING (widgets_init)
  │   ├── inc/hooks/scripts-styles.php   ← EXISTING (305 lines — enqueue all assets)
  │   ├── inc/hooks/block-styles.php     ← EXISTING
  │   ├── inc/hooks/gutenberg.php        ← EXISTING (231 lines — block restrictions)
  │   ├── inc/hooks/acf-blocks.php       ← EXISTING (73 lines — ACF block registry)
  │   └── inc/hooks/forms.php            ← EXISTING
  ├── inc/includes.php
  │   ├── inc/includes/theme-setup.php   ← EXISTING (theme support, WooCommerce)
  │   ├── inc/includes/localization.php  ← EXISTING (Polylang i18n)
  │   ├── inc/includes/nav-walker.php    ← EXISTING (598 lines)
  │   ├── inc/includes/nav-walker-footer.php ← EXISTING
  │   ├── inc/includes/taxonomy.php      ← EXISTING
  │   └── inc/includes/post-type.php     ← EXISTING
  └── inc/template-tags.php
      ├── inc/template-tags/acf-blocks.php ← EXISTING (147 lines)
      └── ...
```

### 3.2 Helper Function Strategy

**Where to add:** `inc/template-functions.php` (CREATE — does not exist yet)
**File to require from:** `inc/hooks.php` (add `require_once __DIR__ . '/template-functions.php';`)

**The `optix_get_option()` function** — Correct fallback chain:
```
ACF field has non-empty value? → return ACF value
  ↓ NO
defaults.php has key with non-empty value? → return defaults value  
  ↓ NO
Caller passed $default parameter? → return $default
  ↓ NO
return ''
```

**Global wrapper** — needed because `templates/kids-collection/*.php` files do NOT have `namespace Optix;`:

```php
// In inc/template-functions.php — global namespace
if ( ! function_exists( 'optix_get_option' ) ) {
    function optix_get_option( $key, $default = null ) {
        return \Optix\optix_get_option( $key, $default );
    }
}
```

### 3.3 defaults.php Strategy

Create `inc/defaults.php` returning an associative array of ALL original hardcoded values extracted from the 30+ templates. Key naming convention: `{section}_{field_name}` where section matches the ACF options tab.

**Image paths note:** The theme uses `$kc_img = get_template_directory_uri() . '/assets/kids-collection/images';` in EVERY template. In defaults.php, use the full URL via `get_template_directory_uri()`.

---

## 4. Options Panel Structure

### 4.1 Registration (`inc/class-optix-theme-options.php`)

Required in `inc/hooks.php`, NOT via autoload.

```php
<?php
namespace Optix;

class Theme_Options {
    const PARENT_SLUG = 'optix-theme-options';
    
    public function __construct() {
        add_action( 'acf/init', [ $this, 'register_option_pages' ] );
        add_action( 'acf/init', [ $this, 'register_field_groups' ] );
    }
    
    public function register_option_pages() {
        if ( ! function_exists( 'acf_add_options_page' ) ) return;
        
        acf_add_options_page( [
            'page_title'  => 'Optix Theme Options',
            'menu_title'  => 'Optix Options',
            'menu_slug'   => self::PARENT_SLUG,
            'capability'  => 'manage_options',
            'redirect'    => true,
            'icon_url'    => 'dashicons-admin-customizer',
            'position'    => 3,
        ] );
        
        $sub_pages = [
            'general'       => 'General',
            'header'        => 'Header',
            'topbar'        => 'Top Bar',
            'footer'        => 'Footer',
            'typography'    => 'Typography',
            'colors'        => 'Colors',
            'home-page'     => 'Home Page',
            'about-page'    => 'About Page',
            'blog'          => 'Blog',
            'contact'       => 'Contact',
            'shop'          => 'Shop',
            'product-detail' => 'Product Detail',
            'cart-checkout' => 'Cart & Checkout',
            'pages'         => 'Other Pages',
            'animations'    => 'Animations',
            'advanced'      => 'Advanced',
            'import-export' => 'Import / Export',
        ];
        
        foreach ( $sub_pages as $slug => $title ) {
            acf_add_options_sub_page( [
                'page_title'  => $title,
                'menu_title'  => $title,
                'parent_slug' => self::PARENT_SLUG,
                'menu_slug'   => self::PARENT_SLUG . '-' . $slug,
                'capability'  => 'manage_options',
            ] );
        }
    }
}
```

### 4.2 Sampling of Field Groups (per actual theme content)

**General Tab:**
| Field | Type | Default | Source |
|-------|------|---------|--------|
| `site_logo` | Image | `logo.png` | site-header.php line 14 |
| `favicon` | Image | — | |
| `kc_img_base` | Text | `/assets/kids-collection/images` | Used by ALL templates |
| `preloader_enable` | True/False | 1 | preloader.php |
| `custom_css` | Textarea | — | |
| `custom_js` | Textarea | — | |

**Top Bar Tab:**
| Field | Type | Default | Source |
|-------|------|---------|--------|
| `topbar_sale_text` | Text | `Summer sale discount off 60% on all of your orders!` | topbar.php line 14 |
| `topbar_languages` | Repeater | — | topbar.php lines 18-33 |
| → `lang_flag` | Image | `header-flag1.png` | |
| → `lang_code` | Text | `EN` | |
| → `lang_url` | URL | `/` | |
| `topbar_currencies` | Repeater | — | topbar.php lines 34-42 |
| → `currency_code` | Text | `USD` | |
| → `currency_url` | URL | `/` | |

**Header Tab:**
| Field | Type | Default | Source |
|-------|------|---------|--------|
| `header_logo` | Image | `logo.png` | site-header.php line 14 |
| `header_logo_width` | Number | — | |
| `header_cart_icon` | Image | `header-cart.png` | site-header.php line 78 |
| `header_search_icon` | Image | `header-search.png` | site-header.php line 75 |
| `header_login_icon` | Image | `header-admin.png` | site-header.php line 81 |

**Home Page Tab (matches ACTUAL sections):**
| Field | Type | Default |
|-------|------|---------|
| `home_enable_banner` | True/False | 1 |
| `home_banner_heading` | Text | `Claudia Kids Collection` |
| `home_banner_title` | Textarea | `Little Treasures, <br>Big Smiles!` |
| `home_banner_description` | Textarea | `Discover a world of fun and joy...` |
| `home_banner_btn_text` | Text | `Shop Now` |
| `home_banner_btn_url` | URL | `/shop/` |
| `home_banner_img1` | Image | `banner-img1.png` |
| `home_banner_img2` | Image | `banner-img2.png` |
| `home_enable_promotion` | True/False | 1 |
| `home_promotion_boxes` | Repeater | 3 boxes |
| → `promo_tag` | Text | `Trending` |
| → `promo_title` | Text | `Kids Collection` |
| → `promo_discount` | Text | `Upto 50% Off` |
| → `promo_image` | Image | — |
| → `promo_bg_class` | Text | `bg-light1` |
| → `promo_link` | URL | `/shop/` |
| `home_enable_products` | True/False | 1 |
| `home_products_heading` | Text | `Our Collection` |
| `home_products_title` | Text | `Popular Products` |
| `home_products_count` | Number | 6 |
| `home_products_btn_text` | Text | `View All` |
| `home_enable_cta_sale` | True/False | 1 |
| `home_cta_title` | Text | `Mid Season Sale!` |
| `home_cta_subtitle` | Text | `Get 20% Off on All New Arrivals!` |
| `home_cta_btn_text` | Text | `Get this Deal` |
| `home_enable_categories` | True/False | 1 |
| `home_categories_heading` | Text | `magna aliqua` |
| `home_categories_title` | Text | `Product Categories` |
| `home_categories` | Repeater | 6 (Kids Toys, Clothes, Girls, Accessories, New Born, Boys) |
| → `cat_title` | Text | — |
| → `cat_image` | Image | — |
| → `cat_bg` | Text | `bg-light1` |
| → `cat_url` | URL | `/shop/` |
| `home_enable_top_selling` | True/False | 1 |
| `home_top_selling_title` | Text | `Top Selling Products` |
| `home_top_selling_count` | Number | 4 |
| `home_enable_testimonials` | True/False | 1 |
| `home_testimonials_heading` | Text | `Testimonials` |
| `home_testimonials_title` | Text | `Our Client Reviews` |
| `home_testimonials` | Repeater | 4 slides |
| → `testimonial_rating` | Number | 5 |
| → `testimonial_text` | Textarea | — |
| → `testimonial_name` | Text | — |
| → `testimonial_role` | Text | — |
| → `testimonial_avatar` | Image | — |
| `home_enable_instagram` | True/False | 1 |
| `home_instagram_heading` | Text | `@claudia instagram` |
| `home_instagram_title` | Text | `Find us On Instagram` |
| `home_instagram_images` | Repeater | 4 images |
| `home_enable_benefits` | True/False | 1 |
| `home_benefits` | Repeater | 4 (Shipping, Checkout, Support, Returns) |

**About Page Tab (matches ACTUAL sections):**
| Field | Type | Default |
|-------|------|---------|
| `about_about_heading` | Text | `About Us` |
| `about_about_title` | Text | `Unique clothes & Toys For Kids` |
| `about_about_text` | WYSIWYG | 2 paragraphs |
| `about_about_image` | Image | `about-us-img.jpg` |
| `about_mission_heading` | Text | `Our Mission` |
| `about_mission_title` | Text | `Start of Countless Collection.` |
| `about_mission_text` | WYSIWYG | 2 paragraphs |
| `about_team_heading` | Text | `Experts Team` |
| `about_team_title` | Text | `Our Team Members` |
| `about_team_members` | Repeater | (8 members — images repeat 1,2,3) |
| → `member_image` | Image | — |
| → `member_name` | Text | — |
| → `member_role` | Text | — |
| → `member_facebook` | URL | — |
| → `member_instagram` | URL | — |
| → `member_youtube` | URL | — |

**Note:** about.php also has Product Categories, Instagram, Benefits (same as front-page). Add enable/disable toggles for those sections too. See Issue #16: shared sections across pages should use reusable field groups.

**Shop Tab:**
| Field | Type | Default |
|-------|------|---------|
| `shop_products_per_page` | Number | 12 |
| `shop_columns` | Number | 3 |
| `shop_enable_sidebar` | True/False | 1 |
| `shop_categories` | Repeater | 6 hardcoded cats |

---

## 5. Template Modifications

### 5.1 Nav Walker — EXTEND Existing, Don't Create New

**Strategy:** The existing `Nav_Walker` (598 lines, `inc/includes/nav-walker.php`) is a full Bootstrap navwalker. Create a **child class** `Kids_Collection_Nav_Walker` that overrides `start_lvl()` to output the kids-collection's custom dropdown structure.

```php
<?php
namespace Optix;

/**
 * Extended nav walker for Kids Collection.
 * Overrides start_lvl/end_lvl to match custom 
 * <div class="dropdown-menu drop-down-content"><ul class="list-unstyled drop-down-pages">
 * structure used in site-header.php
 */
class Kids_Collection_Nav_Walker extends Nav_Walker {
    
    public function start_lvl( &$output, $depth = 0, $args = null ) {
        if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
            $t = '';
            $n = '';
        } else {
            $t = "\t";
            $n = "\n";
        }
        $indent = str_repeat( $t, $depth );
        
        // Kids Collection specific: <div class="dropdown-menu drop-down-content">
        //                            <ul class="list-unstyled drop-down-pages">
        $output .= "\n{$n}{$indent}<div class=\"dropdown-menu drop-down-content\">\n";
        $output .= "{$indent}\t<ul class=\"list-unstyled drop-down-pages\">\n";
    }
    
    public function end_lvl( &$output, $depth = 0, $args = null ) {
        if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
            $t = '';
            $n = '';
        } else {
            $t = "\t";
            $n = "\n";
        }
        $indent = str_repeat( $t, $depth );
        
        $output .= "{$indent}\t</ul>\n";
        $output .= "{$indent}</div>\n";
    }
    
    /**
     * Override start_el to match kids-collection's .nav-item.dropdown.active pattern
     * and add dropdown-color navbar-text-color classes to links.
     */
    public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
        // Use parent to generate most of the HTML
        // but add custom classes
        add_filter( 'nav_menu_css_class', function( $classes ) {
            // Ensure 'nav-item' is present
            if ( ! in_array( 'nav-item', $classes ) ) {
                $classes[] = 'nav-item';
            }
            return $classes;
        }, 10, 1 );
        
        parent::start_el( $output, $item, $depth, $args, $id );
    }
}
```

### 5.2 `template-parts/kids-collection/site-header.php` (THE ACTUAL FILE)

**Changes:**
1. Logo `<img src>` → `optix_get_option('header_logo')`
2. Cart icon counter → `WC()->cart->get_cart_contents_count()` (dynamic, not "0")
3. Hardcoded menu → `wp_nav_menu()` with `Kids_Collection_Nav_Walker`
4. Search icon, login icon → from options
5. Cart/Login URLs → dynamic

**wp_nav_menu call:**
```php
wp_nav_menu( [
    'theme_location' => 'primary',
    'container'      => false,
    'menu_class'     => 'navbar-nav ml-auto',
    'depth'          => 2,
    'walker'         => new \Optix\Kids_Collection_Nav_Walker(),
    'fallback_cb'    => '__return_false',
    'echo'           => false,
] );
```

**Note:** The existing `page-kids-collection.php` has a WooCommerce bypass (lines 14-29) that redirects WC pages to `woocommerce_content()`. Preserve this — it's the CORRECT approach for this theme.

### 5.3 `template-parts/kids-collection/topbar.php`

**Changes:**
1. Sale text → `optix_get_option('topbar_sale_text')`
2. Language dropdown → ACF repeater `topbar_languages`
3. Currency dropdown → ACF repeater `topbar_currencies`

### 5.4 `template-parts/kids-collection/site-footer.php`

**Changes:**
1. Logo → `optix_get_option('footer_logo')`
2. About text → `optix_get_option('footer_about_text')`
3. Social icons → ACF repeater `footer_social_links`
4. Navigation links → `wp_nav_menu('theme_location: footer')` or ACF repeater
5. Support links → ACF repeater `footer_support_links`
6. Contact info (phone/email/address) → ACF fields
7. Copyright text → `optix_get_option('footer_copyright')`

### 5.5 `template-parts/kids-collection/newsletter.php`

**Changes:**
1. Title/heading → `optix_get_option('newsletter_heading')`
2. Placeholder text → `optix_get_option('newsletter_placeholder')`
3. Button text → `optix_get_option('newsletter_btn_text')`
4. Form action URL → `optix_get_option('newsletter_action_url')`

### 5.6 `templates/kids-collection/front-page.php` (416 lines)

**ALL sections in ONE pass. Each wrapped in enable/disable check.**

**Banner Section (lines 14-34):**
```php
<?php if ( optix_get_option( 'home_enable_banner' ) == 1 ) : ?>
<section class="banner-con position-relative float-left w-100 text-center">
  <figure><img src="<?php echo esc_url( optix_get_option( 'home_banner_img1' ) ); ?>" alt="image" class="position-absolute banner-img1"></figure>
  <figure><img src="<?php echo esc_url( optix_get_option( 'home_banner_img2' ) ); ?>" alt="image" class="position-absolute banner-img2"></figure>
  <div class="main-container">
    <div class="banner-inner-wrapper">
      <div class="center-context">
        <span class="d-inline-block primary-text text-uppercase banner-span"><?php echo esc_html( optix_get_option( 'home_banner_heading' ) ); ?></span>
        <h1 class="font-size92"><?php echo wp_kses_post( optix_get_option( 'home_banner_title' ) ); ?></h1>
        <p><?php echo esc_html( optix_get_option( 'home_banner_description' ) ); ?></p>
        <a href="<?php echo esc_url( optix_get_option( 'home_banner_btn_url' ) ); ?>" class="text-decoration-none secondary_btn d-inline-block"><?php echo esc_html( optix_get_option( 'home_banner_btn_text' ) ); ?> <i class="fa-solid fa-arrow-right"></i></a>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>
```

**Promotion Section (lines 37-86):**
- ACF repeater `home_promotion_boxes` with: tag, title, discount, image, bg class, link
- Uses `data-wow-duration="2s" data-wow-delay="0.05s"` on wrappers
- Fallback: original 3 static boxes when no ACF data

**Our Collection / Products Section (lines 88-145):**
- Already dynamic with `wc_get_products()` — PRESERVE this pattern
- Add control fields: heading, title, count (6), button text
- Image fallback: `wp_get_attachment_image_url() ?: $kc_img . '/product-img1.png'` (PRESERVE)

**CTA Sale Section (lines 147-164):**
- ACF: title, subtitle, button text, button URL, enable/disable

**Product Categories Section (lines 166-200):**
- ACF repeater `home_categories`: title, image, bg class, URL
- Fallback: 6 original static categories

**Top Selling Products Section (lines 202-253):**
- Already dynamic with `wc_get_products('orderby' => 'total_sales')` — PRESERVE

**Testimonials Section (lines 255-362) — Bootstrap Carousel, NOT Owl:**
```php
<?php if ( optix_get_option( 'home_enable_testimonials' ) == 1 ) :
$testimonials = optix_get_option( 'home_testimonials' );
if ( ! empty( $testimonials ) ) : ?>
<section class="float-left w-100 testimonial-con2 position-relative padding-top main-box">
  <div class="container">
    <div class="heading-title-con text-center">
      <span class="special-text d-block" data-wow-duration="2s" data-wow-delay="0.05s"><?php echo esc_html( optix_get_option( 'home_testimonials_heading' ) ); ?></span>
      <h2 data-wow-duration="2s" data-wow-delay="0.05s"><?php echo esc_html( optix_get_option( 'home_testimonials_title' ) ); ?></h2>
    </div>
    <div class="row">
      <div class="col-xl-10 col-12 mx-auto position-relative" data-wow-duration="2s" data-wow-delay="0.05s">
        <div id="testimonialcarousel" class="carousel slide" data-bs-ride="carousel">
          <div class="testimonial_carousel text-center position-relative">
            <div class="carousel-inner">
              <?php foreach ( $testimonials as $index => $t ) : ?>
              <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                <div class="testimonial-box">
                  <ul class="list-unstyled">
                    <?php for ( $i = 0; $i < (int) ( $t['rating'] ?? 5 ); $i++ ) : ?>
                    <li><i class="fa-solid fa-star"></i></li>
                    <?php endfor; ?>
                  </ul>
                  <p class="paragarph">&ldquo;<?php echo esc_html( $t['text'] ?? '' ); ?>&rdquo;</p>
                  <div class="lower_content">
                    <span class="name"><?php echo esc_html( $t['name'] ?? '' ); ?></span>
                    <span class="review"><?php echo esc_html( $t['role'] ?? '' ); ?></span>
                  </div>
                </div>
              </div>
              <?php endforeach; ?>
            </div>
          </div>
          <ul class="carousel-indicators">
            <?php foreach ( $testimonials as $index => $t ) : ?>
            <li data-bs-target="#testimonialcarousel" data-bs-slide-to="<?php echo esc_attr( $index ); ?>" class="<?php echo $index === 0 ? 'active' : ''; ?>">
              <figure class="mb-0"><img src="<?php echo esc_url( $t['avatar'] ?? '' ); ?>" alt="image" class="img-fluid invert_effect"></figure>
            </li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
    </div>
  </div>
</section>
<?php endif; endif; ?>
```

**Instagram Follow Section (lines 364-384):**
- ACF repeater `home_instagram_images`: image, URL
- Fallback: 4 original static images

**Benefits Section (lines 386-416):**
- ACF repeater `home_benefits`: icon, text
- Fallback: 4 original benefits

### 5.7 `templates/kids-collection/about.php` (432 lines)

**Sub-banner:** Uses `the_title()` already — preserve.
**Editor Content:** Uses `get_the_content()` already — preserve.
**About Us Section:** Image + heading + title + text from ACF.
**Mission Section:** Heading + title + text from ACF.
**Team Section:** Uses OWL CAROUSEL (unlike front-page testimonials).
```php
<div class="owl-carousel owl-theme" data-wow-duration="2s" data-wow-delay="0.05s">
    <?php foreach ( $team_members as $member ) : ?>
    <div class="item">
        <div class="team-box text-center position-relative">
            <figure><img src="<?php echo esc_url( $member['image'] ); ?>" alt="" class="img-fluid"></figure>
            <h5 class="archivo-font"><?php echo esc_html( $member['name'] ); ?></h5>
            <span class="designation text-color d-block"><?php echo esc_html( $member['role'] ); ?></span>
            <ul class="list-unstyled p-0 mb-0">
                <?php if ( ! empty( $member['facebook'] ) ) : ?>
                <li class="d-inline-block"><a href="<?php echo esc_url( $member['facebook'] ); ?>"><i class="fa-brands fa-facebook-f"></i></a></li>
                <?php endif; ?>
                <!-- instagram, youtube similarly -->
            </ul>
        </div>
    </div>
    <?php endforeach; ?>
</div>
```
**Product Categories, Instagram, Benefits:** Same ACF fields as front-page (reusable field groups).

### 5.8 `templates/kids-collection/shop.php` (653 lines)

**Major conversion:** 12 hardcoded products → dynamic WC loop.

**Sidebar sections** (search, categories, filter by price, colors, size):
- Search → `get_product_search_form()` or ACF
- Categories → `wp_list_categories('taxonomy=product_cat')` or ACF repeater
- Price filter → ACF min/max fields or WC widget
- Colors → ACF repeater
- Size → ACF repeater

**Product grid:** Replace 12 static products with `WC_Query` loop:
```php
$paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
$args = [
    'post_type'      => 'product',
    'posts_per_page' => optix_get_option( 'shop_products_per_page', 12 ),
    'paged'          => $paged,
];
$loop = new WP_Query( $args );
// ... loop same as front-page seller-box structure ...
```

**Pagination:** Replace static pagination links with `paginate_links()`.

### 5.9 `templates/kids-collection/product-detail.php` (888 lines)

**Major conversion:** Static product → dynamic `$product` from WooCommerce.
Already handled by `page-kids-collection.php` WooCommerce bypass for single products. Use `woocommerce_content()` output with custom template overrides.

### 5.10 `templates/kids-collection/blog.php` (524 lines)

**Changes:**
1. Tabs (All/Advices/Announcements/News/Consultation/Development) → ACF category selection or WP categories
2. Posts → `WP_Query` loop
3. Pagination → `paginate_links()`

### 5.11 `templates/kids-collection/single-blog.php` (313 lines)

**Changes:**
1. Content → `the_content()`
2. Comments → `comments_template()`
3. Sidebar (search, categories, social, tags, feeds) → `dynamic_sidebar()` or ACF

### 5.12 `templates/kids-collection/contact.php` (140 lines)

**Changes:**
1. Contact info cards (location, phone, email) → ACF fields
2. Contact form → preserve hardcoded form, add ACF email recipient
3. Map embed → ACF textarea for iframe URL

### 5.13 `templates/kids-collection/coming-soon.php` (41 lines)

**Changes:**
1. Logo → from options
2. Heading text → ACF
3. Title → ACF (`Coming Soon`)
4. Countdown target date → ACF date picker (for counter.js)

### 5.14 Remaining Template Files

All other templates (team, testimonials, faq, login, thank-you, privacy, cookie, terms, blog layouts, 404) follow the same pattern:
- `kc_img` paths → ACF image fields
- Hardcoded text → ACF text fields
- `get_template_part()` loading → preserve

---

## 6. Dynamic CSS/JS

### 6.1 Color Mapping

Strategy unchanged from v1 (see original). Key corrections:
- CSS is output via `wp_head` hook at priority 99 (AFTER theme CSS)
- No `wp_strip_all_tags()` on custom CSS (attribute selectors preserved)
- Responsive breakpoints for typography
- Font family validation to prevent `'System Default'` from loading Google Fonts

### 6.2 Scripts Integration

**Add to `inc/hooks/scripts-styles.php`** (existing enqueue file, 305 lines):
- Register `optix-dynamic-js` with `wp_localize_script()` for AJAX URL + nonce
- Use existing `kc-` prefix convention for all handles

### 6.3 Animation Toggle

WOW.js disable: Same approach as v1.

---

## 7. WooCommerce Integration

### 7.1 Critical: Already Partially Working

The front-page already queries products via `wc_get_products()`. `page-kids-collection.php` already handles WC page routing. **The spec should enhance, not replace, these patterns.**

### 7.2 Theme Support (in `inc/includes/theme-setup.php`)

Already declares WooCommerce support with gallery features. Preserve.

### 7.3 Page Template Strategy (not WC template overrides)

The existing approach (in `page-kids-collection.php` lines 14-29) is:
```php
if ( class_exists( 'WooCommerce' ) ) {
    $wc_pages = [
        wc_get_page_id( 'shop' ),
        wc_get_page_id( 'cart' ),
        wc_get_page_id( 'checkout' ),
        wc_get_page_id( 'myaccount' ),
    ];
    if ( is_page( $wc_pages ) || is_singular( 'product' ) || is_tax( 'product_cat' ) || is_tax( 'product_tag' ) ) {
        get_header();
        echo '<main id="content" class="woocommerce-kids-wrapper">';
        woocommerce_content();
        echo '</main>';
        get_footer();
        return;
    }
}
```

**Strategy:** Instead of creating `woocommerce/` template overrides, let WooCommerce use its built-in templates but wrapped in the theme header/footer. If custom styling is needed, use the existing `assets/kids-collection/css/woocommerce.css`.

### 7.4 Cart Icon Badge

In `site-header.php`, change hardcoded `<span>0</span>` to:
```php
<span><?php echo class_exists( 'WooCommerce' ) ? esc_html( WC()->cart->get_cart_contents_count() ) : '0'; ?></span>
```

---

## 8. Security (unchanged from v1)

Key points:
- Admin hook check uses strict comparison
- Restore defaults uses `options_optix_%` prefix (not `options_%`)
- Import/Export via custom admin page, not ACF fields
- Nonce verification on import/export actions

---

## 9. Implementation Phases (REVISED)

### Phase 1: Foundation (3-4 hours) ⚠️ Includes integration with existing chain
- [ ] Create `inc/defaults.php` with ALL original values from ALL 30+ templates
- [ ] Create `inc/class-optix-theme-options.php` with 17 sub-pages
- [ ] Add `optix_get_option()` to `inc/template-functions.php` (global + namespaced)
- [ ] Add `require` for options class in `inc/hooks.php`
- [ ] Create `inc/dynamic-css.php` 
- [ ] Create `Kids_Collection_Nav_Walker` extending existing `Nav_Walker`
- [ ] Create field groups for General, Header, Top Bar, Footer tabs

### Phase 2: Header & Footer (2 hours)
- [ ] Modify `template-parts/kids-collection/site-header.php`
- [ ] Modify `template-parts/kids-collection/topbar.php`
- [ ] Modify `template-parts/kids-collection/site-footer.php`
- [ ] Modify `template-parts/kids-collection/newsletter.php`
- [ ] Remove hardcoded menu → `wp_nav_menu()` with Kids_Collection_Nav_Walker
- [ ] Fix cart icon "0" → dynamic WC count

### Phase 3: Front Page — ALL SECTIONS (4-5 hours) ⚠️ One pass
- [ ] Banner section (static images → ACF)
- [ ] Promotion boxes (ACF repeater)
- [ ] Products section (enhance existing wc_get_products())
- [ ] CTA Sale section
- [ ] Product Categories (ACF repeater)
- [ ] Top Selling (enhance existing)
- [ ] Testimonials (Bootstrap Carousel from ACF repeater)
- [ ] Instagram Follow (ACF repeater)
- [ ] Benefits (ACF repeater)

### Phase 4: About Page (2 hours)
- [ ] About Us section
- [ ] Mission section
- [ ] Team (Owl Carousel from ACF repeater)
- [ ] Categories, Instagram, Benefits (share groups with front-page)

### Phase 5: Blog Pages (2-3 hours)
- [ ] `blog.php`: tabs, loop, pagination
- [ ] `single-blog.php`: content, sidebar, comments
- [ ] `load-more.php`: AJAX load-more
- [ ] Blog layout templates (1/2/3/4/6 column, sidebar)

### Phase 6: Shop & Product (4-5 hours)
- [ ] `shop.php`: 653-line file — dynamic products, sidebar, pagination
- [ ] `product-detail.php`: 888-line file — WC integration
- [ ] `cart.php`: 262 lines
- [ ] `checkout.php`: 280 lines

### Phase 7: Contact & Forms (1 hour)
- [ ] `contact.php`: info cards, form, map
- [ ] `join-now.php`: registration form
- [ ] `login.php`: login page

### Phase 8: All Other Templates (2-3 hours)
- [ ] `coming-soon.php`: countdown
- [ ] `faq.php`, `team.php`, `testimonials.php`
- [ ] `privacy-policy.php`, `cookie-policy.php`, `term-of-use.php`, `thank-you.php`
- [ ] `404.php`
- [ ] `one-column.php` through `six-colum-full-wide.php`

### Phase 9: Import/Export & Admin (1-2 hours)
- [ ] Custom admin page for backup
- [ ] Export JSON, Import JSON, Restore Defaults
- [ ] Admin CSS/JS

### Phase 10: Polish & Testing (2-3 hours)
- [ ] Test ALL 30+ templates
- [ ] Cross-browser (Chrome, Firefox, Safari, Edge)
- [ ] Mobile responsive (375px, 768px, 1440px)
- [ ] ACF enabled/disabled/partial
- [ ] WooCommerce flow (add-to-cart → checkout)
- [ ] WOW.js toggle, preloader toggle
- [ ] No PHP warnings/errors

---

## 10. Verification Checklist (EXPANDED)

| Check | Method |
|-------|--------|
| All 30+ templates render without errors | WP_DEBUG, manual navigation |
| Header nav shows WordPress menu | Assign menu, verify HTML matches original |
| Header menu fallback works | Remove menu assignment, verify |
| Cart icon shows dynamic count | Add product, verify number changes |
| All sections toggleable | Enable/disable each section |
| ACF disabled = original theme | Deactivate ACF, refresh every page |
| Testimonials carousel works | Click through indicators, prev/next |
| Owl Carousel works on About team | Verify sliding behavior |
| WOW.js animations trigger | Scroll, verify fadeIn effects |
| WOW.js disabled works | Toggle off, verify no animations |
| Shop shows real WC products | Add products, verify display |
| Product detail shows WC data | Single product view |
| Cart/checkout flow works | Add to cart → checkout → confirm |
| Blog shows real posts | Add posts, verify listing |
| Blog pagination works | Add 20+ posts, navigate pages |
| Contact form submits | Fill and submit, verify email |
| Coming soon countdown works | Set future date, verify timer |
| Language/currency dropdowns work | Click, verify selection |
| Instagram section toggles | Enable/disable |
| Custom CSS/JS injects | Add test CSS/JS, verify on frontend |
| Import/Export works | Export → Reset → Import → verify |
| Mobile menu works | Resize to mobile, tap hamburger |
| No JS console errors | Check on every page type |
| No PHP notices/warnings | WP_DEBUG enabled |

---

## 11. Key Edge Cases & Corrections from v1

| # | Assumption (v1) | Reality (v2) |
|---|-----------------|--------------|
| 1 | Nav walker at `inc/class-header-nav-walker.php` | Extend existing `Nav_Walker` at `inc/includes/nav-walker.php` |
| 2 | 10 templates to modify | 30+ templates exist |
| 3 | Product listing = `product-listing.php` | File is `shop.php` (653 lines) |
| 4 | Testimonials = Owl Carousel | Front-page = Bootstrap Carousel; About = Owl Carousel |
| 5 | WOW.js durations = 1s | ALL use `data-wow-duration="2s" data-wow-delay="0.05s"` |
| 6 | Contact form = shortcode | Hardcoded HTML form, JS validation, custom endpoint |
| 7 | Newsletter = shortcode | Form posts to `/wp-forms/subscribe` |
| 8 | Cart/checkout = WC template overrides | Custom page templates in `templates/kids-collection/` |
| 9 | Preloader in header | Preloader in footer.php (loaded after site-footer) |
| 10 | Gutenberg not handled | Full 231-line Gutenberg integration exists |
| 11 | ACF blocks nonexistent | ACF block system already in `inc/template-tags/acf-blocks.php` |
| 12 | `class_exists('WooCommerce')` check | Already exists in front-page.php |
| 13 | Header cart `0` is static | Need `WC()->cart->get_cart_contents_count()` |
| 14 | `page-kids-collection.php` routing | Templates loaded by `get_template_part('templates/kids-collection/' . $slug)` |
| 15 | Include chain not addressed | Must integrate into existing `functions.php → inc/hooks.php` chain |
| 16 | No existing enqueue system | `inc/hooks/scripts-styles.php` (305 lines) handles ALL enqueues |
| 17 | `namespace Optix;` in templates | Only in root header/footer — NOT in `templates/kids-collection/*.php` |
| 18 | Same section repeated pages | Categories, Instagram, Benefits appear on BOTH front-page AND about-page — use reusable ACF field groups |

---

*Specification v2 — Ground-Truthed against actual theme code. 37 issues identified and corrected from v1.*
