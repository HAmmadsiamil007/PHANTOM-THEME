<?php
declare(strict_types=1);

namespace OptixCore\Registry;

defined('ABSPATH') || exit;

class Section_Registry extends Base_Registry {

    protected function define_entries(): array {
        return [
            'topbar' => [
                'default' => [
                    'enable' => true,
                    'fields' => ['topbar_enable', 'topbar_sale_text', 'topbar_bg', 'topbar_text', 'topbar_languages', 'topbar_currencies'],
                ],
                'template_part' => 'template-parts/kids-collection/topbar',
                'description' => 'Top notification bar with sale text, language switcher, currency selector',
            ],
            'header' => [
                'default' => [
                    'enable' => true,
                    'fields' => ['header_logo', 'header_logo_width', 'header_search_icon', 'header_cart_icon', 'header_login_icon', 'header_cart_count', 'header_sticky', 'header_height', 'menu_font_size', 'search_placeholder'],
                ],
                'template_part' => 'template-parts/kids-collection/site-header',
                'description' => 'Site header with logo, navigation, search, cart, login',
            ],
            'search_overlay' => [
                'default' => [
                    'enable' => true,
                    'fields' => ['search_placeholder'],
                ],
                'template_part' => 'template-parts/kids-collection/search-overlay',
                'description' => 'Full-screen search overlay',
            ],
            'banner' => [
                'default' => [
                    'enable' => true,
                    'fields' => ['home_banner_enable', 'home_banner_heading', 'home_banner_title', 'home_banner_description', 'home_banner_btn_text', 'home_banner_btn_url', 'home_banner_img1', 'home_banner_img2'],
                ],
                'template_part' => null,
                'description' => 'Homepage hero banner with heading, title, description, CTA, images',
            ],
            'promotion' => [
                'default' => [
                    'enable' => true,
                    'fields' => ['home_promotion_enable', 'home_promotion_boxes'],
                ],
                'template_part' => null,
                'description' => 'Promotional boxes grid (4-box layout with tags, titles, discounts)',
            ],
            'products' => [
                'default' => [
                    'enable' => true,
                    'fields' => ['home_products_enable', 'home_products_heading', 'home_products_title', 'home_products_count', 'home_products_btn_text', 'home_products_fallback_img', 'home_products_price_multiplier'],
                ],
                'template_part' => null,
                'description' => 'Product collection grid with WooCommerce products',
            ],
            'cta' => [
                'default' => [
                    'enable' => true,
                    'fields' => ['home_cta_enable', 'home_cta_title', 'home_cta_subtitle', 'home_cta_btn_text', 'home_cta_btn_url'],
                ],
                'template_part' => null,
                'description' => 'Call-to-action banner with title, subtitle, button',
            ],
            'categories' => [
                'default' => [
                    'enable' => true,
                    'fields' => ['home_categories_enable', 'home_categories_heading', 'home_categories_title', 'home_categories'],
                ],
                'template_part' => null,
                'description' => 'Product categories grid with icons and labels',
            ],
            'top_selling' => [
                'default' => [
                    'enable' => true,
                    'fields' => ['home_top_selling_enable', 'home_top_selling_title', 'home_top_selling_count'],
                ],
                'template_part' => null,
                'description' => 'Top selling products grid',
            ],
            'testimonials' => [
                'default' => [
                    'enable' => true,
                    'fields' => ['home_testimonials_enable', 'home_testimonials_heading', 'home_testimonials_title', 'home_testimonials'],
                ],
                'template_part' => null,
                'description' => 'Client testimonials carousel with ratings and avatars',
            ],
            'instagram' => [
                'default' => [
                    'enable' => true,
                    'fields' => ['home_instagram_enable', 'home_instagram_heading', 'home_instagram_title', 'home_instagram_images'],
                ],
                'template_part' => null,
                'description' => 'Instagram feed grid with follow links',
            ],
            'benefits' => [
                'default' => [
                    'enable' => true,
                    'fields' => ['home_benefits_enable', 'home_benefits'],
                ],
                'template_part' => null,
                'description' => 'Benefits/features row with icons and text',
            ],
            'newsletter' => [
                'default' => [
                    'enable' => true,
                    'fields' => ['newsletter_heading', 'newsletter_placeholder', 'newsletter_btn_text', 'newsletter_action_url'],
                ],
                'template_part' => 'template-parts/kids-collection/newsletter',
                'description' => 'Email newsletter signup form',
            ],
            'footer' => [
                'default' => [
                    'enable' => true,
                    'fields' => ['footer_logo', 'footer_about_text', 'footer_copyright', 'footer_payment_cards', 'footer_phone', 'footer_email', 'footer_address', 'footer_text', 'footer_heading_text', 'footer_link', 'footer_social_links', 'footer_social', 'footer_nav', 'footer_support'],
                ],
                'template_part' => 'template-parts/kids-collection/site-footer',
                'description' => 'Site footer with logo, about, navigation, social links, payment cards',
            ],
            'preloader' => [
                'default' => [
                    'enable' => false,
                    'fields' => ['general_preloader_enable'],
                ],
                'template_part' => 'template-parts/kids-collection/preloader',
                'description' => 'Page preloader animation',
            ],
            'cookie_bar' => [
                'default' => [
                    'enable' => false,
                    'fields' => ['cookie_enable', 'cookie_message', 'cookie_accept_text', 'cookie_decline_text', 'cookie_policy_link', 'cookie_title', 'cookie_content'],
                ],
                'template_part' => null,
                'description' => 'GDPR cookie consent bar',
            ],
            'about_about' => [
                'default' => [
                    'enable' => true,
                    'fields' => ['about_about_enable', 'about_about_heading', 'about_about_title', 'about_about_text_1', 'about_about_text_2', 'about_about_image', 'about_about_btn_text', 'about_about_btn_url'],
                ],
                'template_part' => null,
                'description' => 'About page - about section with image and text',
            ],
            'about_mission' => [
                'default' => [
                    'enable' => true,
                    'fields' => ['about_mission_enable', 'about_mission_heading', 'about_mission_title', 'about_mission_text_1', 'about_mission_text_2'],
                ],
                'template_part' => null,
                'description' => 'About page - mission statement section',
            ],
            'about_team' => [
                'default' => [
                    'enable' => true,
                    'fields' => ['about_team_enable', 'about_team_heading', 'about_team_title', 'about_team_members'],
                ],
                'template_part' => null,
                'description' => 'About page - team members grid',
            ],
            'about_categories' => [
                'default' => [
                    'enable' => true,
                    'fields' => ['about_categories_enable'],
                ],
                'template_part' => null,
                'description' => 'About page - categories section',
            ],
            'about_instagram' => [
                'default' => [
                    'enable' => true,
                    'fields' => ['about_instagram_enable'],
                ],
                'template_part' => null,
                'description' => 'About page - Instagram feed',
            ],
            'about_benefits' => [
                'default' => [
                    'enable' => true,
                    'fields' => ['about_benefits_enable'],
                ],
                'template_part' => null,
                'description' => 'About page - benefits/features row',
            ],
            'contact_info' => [
                'default' => [
                    'enable' => true,
                    'fields' => ['contact_info_heading', 'contact_info_title', 'contact_location_icon', 'contact_location_text', 'contact_location_title', 'contact_phone_icon', 'contact_phone_numbers', 'contact_phone_title', 'contact_email_icon', 'contact_email_addresses', 'contact_email_title', 'contact_map_embed'],
                ],
                'template_part' => null,
                'description' => 'Contact page - contact information section',
            ],
            'contact_form' => [
                'default' => [
                    'enable' => true,
                    'fields' => ['contact_form_heading', 'contact_form_title', 'contact_form_btn_text'],
                ],
                'template_part' => null,
                'description' => 'Contact page - contact form section',
            ],
            'blog' => [
                'default' => [
                    'enable' => true,
                    'fields' => ['blog_enable', 'blog_title', 'blog_posts_per_page', 'blog_tabs'],
                ],
                'template_part' => null,
                'description' => 'Blog index page with tabs and post grid',
            ],
            'faq' => [
                'default' => [
                    'enable' => true,
                    'fields' => ['faq_enable', 'faq_instagram_enable', 'faq_benefits_enable', 'faq_heading', 'faq_title', 'faq_items'],
                ],
                'template_part' => null,
                'description' => 'FAQ accordion page',
            ],
            'coming_soon' => [
                'default' => [
                    'enable' => false,
                    'fields' => ['coming_soon_logo', 'coming_soon_subtitle', 'coming_soon_title', 'coming_soon_date'],
                ],
                'template_part' => null,
                'description' => 'Coming soon / maintenance mode page',
            ],
            'error_404' => [
                'default' => [
                    'enable' => true,
                    'fields' => ['404_title', '404_description', '404_btn_text'],
                ],
                'template_part' => null,
                'description' => '404 error page',
            ],
            'shop' => [
                'default' => [
                    'enable' => true,
                    'fields' => ['shop_enable', 'shop_title', 'shop_products_per_page', 'shop_columns', 'shop_enable_sidebar'],
                ],
                'template_part' => null,
                'description' => 'Shop archive page',
            ],
            'product_detail' => [
                'default' => [
                    'enable' => true,
                    'fields' => ['product_related_title', 'product_related_count', 'product_related_sale_tag', 'product_more_title', 'product_detail_title', 'product_detail_name', 'product_detail_price', 'product_detail_original_price', 'product_detail_rating', 'product_detail_desc', 'product_detail_stock', 'product_detail_sku_label', 'product_detail_sku_value', 'product_detail_categories_label', 'product_detail_categories_value', 'product_detail_tags_label', 'product_detail_tags_value', 'product_detail_color_label', 'product_detail_add_to_cart', 'product_detail_wishlist', 'product_detail_compare', 'product_detail_safe_checkout', 'product_detail_tab_description', 'product_detail_tab_additional', 'product_detail_tab_reviews', 'product_detail_info_features_label', 'product_detail_info_features_value', 'product_detail_info_materials_label', 'product_detail_info_materials_value', 'product_detail_info_types_label', 'product_detail_info_types_value', 'product_detail_review_heading', 'product_detail_review_title', 'product_detail_review_btn', 'product_detail_review_name_placeholder', 'product_detail_review_email_placeholder', 'product_detail_review_comment_placeholder', 'product_detail_review_author', 'product_detail_review_role'],
                ],
                'template_part' => null,
                'description' => 'Single product detail page',
            ],
            'cart' => [
                'default' => [
                    'enable' => true,
                    'fields' => ['cart_title', 'cart_heading', 'cart_continue_text', 'cart_summary_title', 'cart_tax_label', 'cart_tax_description', 'cart_country_label', 'cart_country_placeholder', 'cart_state_label', 'cart_state_placeholder', 'cart_zip_label', 'cart_subtotal_label', 'cart_total_label', 'cart_discount_label', 'cart_discount_placeholder', 'cart_discount_btn', 'cart_checkout_text', 'cart_coupon_enable', 'cart_cross_sell_enable'],
                ],
                'template_part' => null,
                'description' => 'Shopping cart page',
            ],
            'checkout' => [
                'default' => [
                    'enable' => true,
                    'fields' => ['checkout_title', 'checkout_step_shipping', 'checkout_step_payment', 'checkout_shipping_label', 'checkout_email_label', 'checkout_account_note', 'checkout_fname_label', 'checkout_lname_label', 'checkout_company_label', 'checkout_street_label', 'checkout_street_line1_label', 'checkout_country_label', 'checkout_country_placeholder', 'checkout_state_label', 'checkout_state_placeholder', 'checkout_city_label', 'checkout_city_placeholder', 'checkout_zip_label', 'checkout_phone_label', 'checkout_shipping_methods_label', 'checkout_shipping_free_price', 'checkout_shipping_free_label', 'checkout_shipping_free_name', 'checkout_shipping_flat_price', 'checkout_shipping_flat_label', 'checkout_shipping_flat_name', 'checkout_next_btn', 'checkout_summary_title', 'checkout_subtotal_label', 'checkout_total_label', 'checkout_items_label', 'checkout_item_name', 'checkout_item_qty_label', 'checkout_item_price', 'checkout_payment_label', 'checkout_credit_card_label', 'checkout_card_number_label', 'checkout_expiration_label', 'checkout_month_placeholder', 'checkout_year_placeholder', 'checkout_security_label', 'checkout_cod_label', 'checkout_terms_text', 'checkout_terms_link_text'],
                ],
                'template_part' => null,
                'description' => 'Multi-step checkout page',
            ],
            'login' => [
                'default' => [
                    'enable' => true,
                    'fields' => ['login_title', 'login_btn_text', 'login_email_label', 'login_password_label', 'login_remember_label', 'login_lost_password_text', 'login_lost_password_url', 'login_join_link', 'login_logo'],
                ],
                'template_part' => null,
                'description' => 'Customer login page',
            ],
            'join' => [
                'default' => [
                    'enable' => true,
                    'fields' => ['join_title', 'join_btn_text', 'join_name_label', 'join_email_label', 'join_password_label', 'join_updates_label', 'join_login_link', 'join_referral_label', 'join_referral_default', 'join_logo'],
                ],
                'template_part' => null,
                'description' => 'Customer registration page',
            ],
            'thank_you' => [
                'default' => [
                    'enable' => true,
                    'fields' => ['thank_you_title', 'thank_you_text', 'thank_you_btn_text', 'thank_you_btn_url', 'thank_you_icon'],
                ],
                'template_part' => null,
                'description' => 'Post-order thank you page',
            ],
            'single_blog' => [
                'default' => [
                    'enable' => true,
                    'fields' => ['single_blog_author_image', 'single_blog_author_name', 'single_blog_author_role', 'single_blog_author_bio', 'single_blog_quote_image', 'single_blog_quote_text', 'single_blog_prev_text', 'single_blog_next_text', 'single_blog_tags_heading', 'single_blog_social_heading', 'single_blog_reply_text', 'single_blog_comment_heading', 'single_blog_comment_placeholder', 'single_blog_comment_name_placeholder', 'single_blog_comment_email_placeholder', 'single_blog_comment_btn', 'single_blog_search_heading', 'single_blog_search_placeholder', 'single_blog_categories_heading', 'single_blog_follow_heading', 'single_blog_feeds_heading', 'single_blog_tags_sidebar_heading'],
                ],
                'template_part' => null,
                'description' => 'Single blog post page',
            ],
            'portfolio' => [
                'default' => [
                    'enable' => true,
                    'fields' => ['portfolio_title', 'portfolio_projects_per_page', 'portfolio_columns'],
                ],
                'template_part' => null,
                'description' => 'Portfolio archive page',
            ],
            'team' => [
                'default' => [
                    'enable' => true,
                    'fields' => ['team_title', 'team_title_inner', 'team_heading', 'team_mission_heading', 'team_mission_title', 'team_members'],
                ],
                'template_part' => null,
                'description' => 'Team page with members grid',
            ],
            'testimonials_page' => [
                'default' => [
                    'enable' => true,
                    'fields' => ['testimonials_title', 'testimonials_title_inner', 'testimonials_heading'],
                ],
                'template_part' => null,
                'description' => 'Testimonials archive page',
            ],
            'cookie_policy' => [
                'default' => [
                    'enable' => true,
                    'fields' => ['privacy_title', 'privacy_content'],
                ],
                'template_part' => null,
                'description' => 'Cookie policy page',
            ],
            'privacy_policy' => [
                'default' => [
                    'enable' => true,
                    'fields' => ['privacy_title', 'privacy_content'],
                ],
                'template_part' => null,
                'description' => 'Privacy policy page',
            ],
            'terms' => [
                'default' => [
                    'enable' => true,
                    'fields' => ['terms_title', 'terms_content'],
                ],
                'template_part' => null,
                'description' => 'Terms of use page',
            ],
        ];
    }

    public function get_fields_for(string $section_key): ?array {
        $entry = $this->entries[$section_key] ?? null;
        if (null === $entry) {
            return null;
        }
        return $entry['default']['fields'] ?? [];
    }

    public function get_template_part(string $section_key): ?string {
        $entry = $this->entries[$section_key] ?? null;
        if (null === $entry) {
            return null;
        }
        return $entry['template_part'] ?? null;
    }

    public function is_section_enabled(string $section_key): bool {
        $fields = $this->get_fields_for($section_key);
        if (empty($fields)) {
            return true;
        }
        $enable_field = $fields[0] ?? null;
        if (null === $enable_field) {
            return true;
        }
        if (str_ends_with($enable_field, '_enable')) {
            return (bool) get_option('optix_' . $enable_field, 1);
        }
        return true;
    }

    public function get_enabled_sections(): array {
        $enabled = [];
        foreach ($this->entries as $key => $entry) {
            if ($this->is_section_enabled($key)) {
                $enabled[] = $key;
            }
        }
        return $enabled;
    }

    public function render(string $section_key): void {
        $entry = $this->entries[$section_key] ?? null;
        if (null === $entry) {
            return;
        }

        $template = $entry['template_part'] ?? 'template-parts/' . $section_key;
        $file     = $template . '.php';

        // Cascade: active profile → default profile → plugin fallback
        try {
            $active = \OptixCore\Profile_Router::get_instance()->get_active_profile();
            $path   = get_template_directory() . '/profiles/' . $active . '/' . $file;
            if (file_exists($path)) {
                require $path;
                return;
            }
        } catch (\Throwable $e) {
            // Profile_Router not yet initialized
        }

        $path = get_template_directory() . '/profiles/default/' . $file;
        if (file_exists($path)) {
            require $path;
            return;
        }

        $path = defined('OPTIX_CORE_PATH') ? OPTIX_CORE_PATH . 'templates/' . $file : '';
        if (!empty($path) && file_exists($path)) {
            require $path;
        }
    }
}
