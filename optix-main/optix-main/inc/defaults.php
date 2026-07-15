<?php
/**
 * Default values for Optix Kids Collection CMS options.
 *
 * Every hardcoded value from the original theme is captured here.
 * The fallback chain is: ACF field → defaults.php → caller $default → ''
 *
 * @package optix
 */

namespace Optix;

/**
 * Return the entire defaults array.
 */
function get_defaults(): array {
	return array(
		// ── General ──────────────────────────────────────────
		'general_site_logo'                         => 'logo.png',
		'general_kc_img_base'                       => '/assets/kids-collection/images',
		'general_preloader_enable'                  => 1,
		'custom_css'                                => '',
		'custom_js'                                 => '',

		// ── Top Bar ──────────────────────────────────────────
		'topbar_enable'                             => 1,
		'topbar_sale_text'                          => 'Summer sale discount off <span class="d-inline-block">60%</span> on all of your orders!',
		'topbar_bg'                                 => '#222222',
		'topbar_text'                               => '#ffffff',
		'topbar_languages'                          => array(
			array(
				'lang_flag'    => 'header-flag1.png',
				'lang_code'    => 'EN',
				'lang_url'     => '/',
				'lang_country' => 'UK',
			),
			array(
				'lang_flag'    => 'header-flag2.png',
				'lang_code'    => 'USA',
				'lang_url'     => '/',
				'lang_country' => 'SE',
			),
		),
		'topbar_currencies'                         => array(
			array(
				'currency_code' => 'USD',
				'currency_url'  => '/',
			),
			array(
				'currency_code' => 'EUR',
				'currency_url'  => '/',
			),
			array(
				'currency_code' => 'GBP',
				'currency_url'  => '/',
			),
			array(
				'currency_code' => 'INR',
				'currency_url'  => '/',
			),
			array(
				'currency_code' => 'PKR',
				'currency_url'  => '/',
			),
		),

		// ── Header ───────────────────────────────────────────
		'header_logo'                               => '/logo.png',
		'header_logo_width'                         => 150,
		'header_search_icon'                        => '/header-search.png',
		'header_cart_icon'                          => '/header-cart.png',
		'header_login_icon'                         => '/header-admin.png',
		'header_cart_count'                         => 0,
		'header_sticky'                             => 1,
		'header_height'                             => 80,
		'menu_font_size'                            => 14,
		'search_placeholder'                        => 'Search products…',

		// ── Footer ───────────────────────────────────────────
		'footer_logo'                               => '/footer-logo.png',
		'footer_about_text'                         => '',
		'footer_copyright'                          => 'Copyright (c) %d claudia.com All rights reserved.',
		'footer_payment_cards'                      => '/payment-cards.png',
		'footer_phone'                              => '+1235 211 5236',
		'footer_email'                              => 'hello@claudia.com',
		'footer_address'                            => '121 King Street Melbourne, <br>3000, Australia',
		'footer_text'                               => '#999999',
		'footer_heading_text'                       => '#ffffff',
		'footer_link'                               => '#cccccc',
		'footer_social'                             => array(
			array(
				'platform' => 'Facebook',
				'url'      => 'https://www.facebook.com/',
			),
			array(
				'platform' => 'Instagram',
				'url'      => 'https://www.instagram.com/',
			),
			array(
				'platform' => 'YouTube',
				'url'      => 'https://www.youtube.com/',
			),
		),
		'footer_nav'                                => array(
			array(
				'label' => 'Home',
				'url'   => '/',
			),
			array(
				'label' => 'Shop',
				'url'   => '/shop/',
			),
			array(
				'label' => 'About',
				'url'   => '/about/',
			),
			array(
				'label' => 'Blog',
				'url'   => '/blog/',
			),
			array(
				'label' => 'Contact',
				'url'   => '/contact/',
			),
		),
		'footer_support'                            => array(
			array(
				'label' => 'Term of use',
				'url'   => '/term-of-use/',
			),
			array(
				'label' => 'Privacy policy',
				'url'   => '/privacy-policy/',
			),
			array(
				'label' => 'Cookie policy',
				'url'   => '/cookie-policy/',
			),
			array(
				'label' => 'Latest Posts',
				'url'   => '/single-blog/',
			),
			array(
				'label' => 'Care Guide',
				'url'   => '/contact/',
			),
		),

		// ── Newsletter ───────────────────────────────────────
		'newsletter_heading'                        => 'Subscribe to Our Newsletter :',
		'newsletter_placeholder'                    => 'Enter Your Email Address:',
		'newsletter_btn_text'                       => 'Subscribe',
		'newsletter_action_url'                     => '/wp-forms/subscribe',

		// ── Home Page: Banner ────────────────────────────────
		'home_banner_enable'                        => 1,
		'home_banner_heading'                       => 'Claudia Kids Collection',
		'home_banner_title'                         => 'Little Treasures, <br>Big Smiles!',
		'home_banner_description'                   => 'Discover a world of fun and joy with our toys, clothes, and essentials that bring smiles.',
		'home_banner_btn_text'                      => 'Shop Now',
		'home_banner_btn_url'                       => '/shop/',
		'home_banner_img1'                          => '/banner-img1.png',
		'home_banner_img2'                          => '/banner-img2.png',

		// ── Home Page: Promotion ─────────────────────────────
		'home_promotion_enable'                     => 1,
		'home_promotion_boxes'                      => array(
			array(
				'tag'      => 'Trending',
				'title'    => 'Kids Collection',
				'discount' => 'Up to 50% Off',
				'image'    => '/promotion-img1.png',
				'bg_class' => 'bg-light1',
				'link'     => '/shop/',
				'btn_text' => 'Shop Now',
			),
			array(
				'tag'      => 'Latest',
				'title'    => 'Boys Collection',
				'discount' => 'Up to 30% Off',
				'image'    => '/promotion-img2.png',
				'bg_class' => 'bg-light2',
				'link'     => '/shop/',
				'btn_text' => 'Shop Now',
			),
			array(
				'tag'      => 'Hot Deals',
				'title'    => 'Buy One Get One Free',
				'discount' => '',
				'image'    => '/promotion-img3.png',
				'bg_class' => 'bg-light3',
				'link'     => '/shop/',
				'btn_text' => 'Shop Now',
			),
			array(
				'tag'      => 'New Arrivals',
				'title'    => 'Girls Collection',
				'discount' => '',
				'image'    => '/promotion-img4.png',
				'bg_class' => 'bg-light4',
				'link'     => '/shop/',
				'btn_text' => 'Shop Now',
			),
		),

		// ── Home Page: Products ──────────────────────────────
		'home_products_enable'                      => 1,
		'home_products_heading'                     => 'Our Collection',
		'home_products_title'                       => 'Popular Products',
		'home_products_count'                       => 6,
		'home_products_btn_text'                    => 'View All',
		'home_products_fallback_img'                => '/product-img1.png',
		'home_products_price_multiplier'            => 1.3,

		// ── Home Page: CTA Sale ──────────────────────────────
		'home_cta_enable'                           => 1,
		'home_cta_title'                            => 'Mid Season Sale!',
		'home_cta_subtitle'                         => 'Get 20% Off on All New Arrivals!',
		'home_cta_btn_text'                         => 'Get this Deal',
		'home_cta_btn_url'                          => '/shop/',

		// ── Home Page: Categories ────────────────────────────
		'home_categories_enable'                    => 1,
		'home_categories_heading'                   => 'Categories',
		'home_categories_title'                     => 'Product Categories',
		'home_categories'                           => array(
			array(
				'title'    => 'Kids Toys',
				'image'    => '/pc-img1.png',
				'bg_class' => 'bg-light1',
				'url'      => '/shop/',
			),
			array(
				'title'    => 'Clothes',
				'image'    => '/pc-img2.png',
				'bg_class' => 'bg-light2',
				'url'      => '/shop/',
			),
			array(
				'title'    => 'Girls',
				'image'    => '/pc-img3.png',
				'bg_class' => 'bg-light3',
				'url'      => '/shop/',
			),
			array(
				'title'    => 'Accessories',
				'image'    => '/pc-img4.png',
				'bg_class' => 'bg-light4',
				'url'      => '/shop/',
			),
			array(
				'title'    => 'New Born',
				'image'    => '/pc-img5.png',
				'bg_class' => 'bg-light5',
				'url'      => '/shop/',
			),
			array(
				'title'    => 'Boys',
				'image'    => '/pc-img6.png',
				'bg_class' => 'bg-light6',
				'url'      => '/shop/',
			),
		),

		// ── Home Page: Top Selling ───────────────────────────
		'home_top_selling_enable'                   => 1,
		'home_top_selling_title'                    => 'Top Selling Products',
		'home_top_selling_count'                    => 4,

		// ── Home Page: Testimonials ──────────────────────────
		'home_testimonials_enable'                  => 1,
		'home_testimonials_heading'                 => 'Testimonials',
		'home_testimonials_title'                   => 'Our Client Reviews',
		'home_testimonials'                         => array(
			array(
				'rating' => 5,
				'text'   => '...',
				'name'   => 'Katrina Parker',
				'role'   => 'Happy Client',
				'avatar' => '/review-person1.jpg',
			),
			array(
				'rating' => 5,
				'text'   => '...',
				'name'   => 'Fergus Douchebag',
				'role'   => 'Happy Customer',
				'avatar' => '/review-person2.jpg',
			),
			array(
				'rating' => 5,
				'text'   => '...',
				'name'   => 'Erika Neurth',
				'role'   => 'Happy Customer',
				'avatar' => '/review-person3.jpg',
			),
			array(
				'rating' => 5,
				'text'   => '...',
				'name'   => 'Alina James',
				'role'   => 'Happy Client',
				'avatar' => '/review-person4.jpg',
			),
		),

		// ── Home Page: Instagram ─────────────────────────────
		'home_instagram_enable'                     => 1,
		'home_instagram_heading'                    => '@claudia instagram',
		'home_instagram_title'                      => 'Find us On Instagram',
		'home_instagram_images'                     => array(
			array(
				'image' => '/follow-image1.jpg',
				'url'   => 'https://www.instagram.com/',
			),
			array(
				'image' => '/follow-image2.jpg',
				'url'   => 'https://www.instagram.com/',
			),
			array(
				'image' => '/follow-image3.jpg',
				'url'   => 'https://www.instagram.com/',
			),
			array(
				'image' => '/follow-image4.jpg',
				'url'   => 'https://www.instagram.com/',
			),
		),

		// ── Home Page: Benefits ──────────────────────────────
		'home_benefits_enable'                      => 1,
		'home_benefits'                             => array(
			array(
				'icon' => '/benefit-icon1.png',
				'text' => 'Free Worldwide Shipping',
			),
			array(
				'icon' => '/benefit-icon2.png',
				'text' => 'Secure Checkout Hassle Free',
			),
			array(
				'icon' => '/benefit-icon3.png',
				'text' => '24/7 Live Chat Support',
			),
			array(
				'icon' => '/benefit-icon4.png',
				'text' => '30 Days Money Back Guarantee',
			),
		),

		// ── About Page ───────────────────────────────────────
		'about_about_enable'                        => 1,
		'about_about_heading'                       => 'About Us',
		'about_about_title'                         => 'Unique clothes & Toys For Kids',
		'about_about_text_1'                        => 'At Claudia Kids, we believe every child\'s world is full of wonder and imagination.',
		'about_about_text_2'                        => 'Our passion lies in designing and curating playful, high-quality pieces.',
		'about_about_image'                         => '/about-us-img.jpg',
		'about_about_btn_text'                      => 'Read More',
		'about_about_btn_url'                       => '/shop/',
		'about_mission_enable'                      => 1,
		'about_mission_heading'                     => 'Our Mission',
		'about_mission_title'                       => 'Start of Countless Collection.',
		'about_mission_text_1'                      => '',
		'about_mission_text_2'                      => '',
		'about_team_enable'                         => 1,
		'about_team_heading'                        => 'Experts Team',
		'about_team_title'                          => 'Our Team Members',
		'about_team_members'                        => array(
			array(
				'image'     => '/team-person1.jpg',
				'name'      => 'Marvin Joner',
				'role'      => 'Co Founder',
				'facebook'  => '#',
				'instagram' => '#',
				'youtube'   => '#',
			),
			array(
				'image'     => '/team-person2.jpg',
				'name'      => 'Patricia Woodrum',
				'role'      => 'Staff Worker',
				'facebook'  => '#',
				'instagram' => '#',
				'youtube'   => '#',
			),
			array(
				'image'     => '/team-person3.jpg',
				'name'      => 'Hannaz Stone',
				'role'      => 'Shop Worker',
				'facebook'  => '#',
				'instagram' => '#',
				'youtube'   => '#',
			),
		),
		'about_categories_enable'                   => 1,
		'about_instagram_enable'                    => 1,
		'about_benefits_enable'                     => 1,

		// ── Contact Page ─────────────────────────────────────
		'contact_info_heading'                      => 'Contact Info',
		'contact_info_title'                        => 'Our Information',
		'contact_location_icon'                     => '/loc-img.png',
		'contact_location_text'                     => '121 King Street, Melbourne Victoria <br>3000 Australia',
		'contact_location_title'                    => 'Our Location',
		'contact_phone_icon'                        => '/contact-img.png',
		'contact_phone_numbers'                     => array( '(+61 3 8376 6284)', '(+800 2345 6789)' ),
		'contact_phone_title'                       => 'Phone Number',
		'contact_email_icon'                        => '/email-img.png',
		'contact_email_addresses'                   => array( 'info@claudia.com', 'claudia@gmail.com' ),
		'contact_email_title'                       => 'Email Us:',
		'contact_form_heading'                      => 'Get in Touch',
		'contact_form_title'                        => 'Send Us a Message',
		'contact_form_btn_text'                     => 'Send Now',
		'contact_map_embed'                         => '',

		// ── Blog ─────────────────────────────────────────────
		'blog_enable'                               => 1,
		'blog_title'                                => 'Blog',
		'blog_posts_per_page'                       => 6,
		'blog_tabs'                                 => array( 'All', 'Advices', 'Announcements', 'News', 'Consultation', 'Development' ),

		// ── FAQ ──────────────────────────────────────────────
		'faq_enable'                                => 1,
		'faq_instagram_enable'                      => 1,
		'faq_benefits_enable'                       => 1,
		'faq_heading'                               => 'FAQs',
		'faq_title'                                 => 'Frequently Asked Questions',
		'faq_items'                                 => array(),

		// ── Coming Soon ──────────────────────────────────────
		'coming_soon_logo'                          => '/large-logo.png',
		'coming_soon_subtitle'                      => 'Our Website is under construction',
		'coming_soon_title'                         => 'Coming Soon',
		'coming_soon_date'                          => '',

		// ── 404 ──────────────────────────────────────────────
		'404_title'                                 => 'We Could Not Find The Page You\'re Looking For',
		'404_description'                           => 'The link you\'re trying to access is probably broken, or the page has been removed.',
		'404_btn_text'                              => 'Back to Homepage',

		// ── Shop ─────────────────────────────────────────────
		'shop_title'                                => 'Shop',
		'shop_products_per_page'                    => 12,
		'shop_columns'                              => 3,
		'shop_enable_sidebar'                       => 1,
		'shop_enable'                               => true,
		'shop_search_placeholder'                   => 'Search',
		'shop_cat_1'                                => 'Baby Care & Essentials',
		'shop_cat_2'                                => 'Personal Wellness & Hygiene',
		'shop_cat_3'                                => 'Prescription Medicines',
		'shop_cat_4'                                => 'Vitamins & Health Supplements',
		'shop_cat_5'                                => 'Home Health Devices',
		'shop_cat_6'                                => 'First Aid & Medical Supplies',
		'shop_size_1'                               => '100 ml',
		'shop_size_2'                               => '50 ml',
		'shop_size_3'                               => '200 ml',
		'shop_size_4'                               => '150 ml',
		'shop_size_5'                               => '30 ml',
		'shop_size_6'                               => '300 mg',
		'shop_size_7'                               => '500 mg',
		'shop_size_8'                               => '90 mg',
		'shop_size_9'                               => '400 mg',
		'shop_color_1'                              => 'dot1',
		'shop_color_2'                              => 'dot2',
		'shop_color_3'                              => 'dot3',
		'shop_color_4'                              => 'dot4',
		'shop_color_5'                              => 'dot5',
		'shop_color_6'                              => 'dot6',
		'shop_sort_1'                               => 'DermaGlow',
		'shop_sort_2'                               => 'ImmunoBoost',

		// ── Product Detail ───────────────────────────────────
		'product_related_title'                     => 'Related Products',
		'product_related_count'                     => 12,
		'product_related_sale_tag'                  => 'Sale',
		'product_more_title'                        => 'More Products',
		'product_detail_title'                      => 'Product Details',
		'product_detail_name'                       => 'Dreamy Day Pajamas',
		'product_detail_price'                      => '$38.00',
		'product_detail_original_price'             => '$89.00',
		'product_detail_rating'                     => '4.9/5',
		'product_detail_desc'                       => '',
		'product_detail_stock'                      => 'In stock',
		'product_detail_sku_label'                  => 'SKU:',
		'product_detail_sku_value'                  => 'HD_158',
		'product_detail_categories_label'           => 'Categories:',
		'product_detail_categories_value'           => 'Decor, Home Decor, Furniture, Interior',
		'product_detail_tags_label'                 => 'Tags:',
		'product_detail_tags_value'                 => 'Pjs, Pajamas',
		'product_detail_color_label'                => 'Color:',
		'product_detail_add_to_cart'                => 'Add to Cart',
		'product_detail_wishlist'                   => 'Add to wishlist',
		'product_detail_compare'                    => 'Compare',
		'product_detail_safe_checkout'              => 'Guaranteed Safe Checkout:',
		'product_detail_tab_description'            => 'Description',
		'product_detail_tab_additional'             => 'Additional Information',
		'product_detail_tab_reviews'                => 'Reviews',
		'product_detail_info_features_label'        => 'Features',
		'product_detail_info_features_value'        => 'Adjustable, Foldable, Cushioned, Storage, Reclining',
		'product_detail_info_materials_label'       => 'Materials',
		'product_detail_info_materials_value'       => 'Wood, Metal, Plastic, Glass, Upholstery',
		'product_detail_info_types_label'           => 'Types',
		'product_detail_info_types_value'           => 'Chairs, Tables, Sofas, Beds, Cabinets',
		'product_detail_review_heading'             => 'Review',
		'product_detail_review_title'               => 'Post Your Review',
		'product_detail_review_btn'                 => 'Post Review',
		'product_detail_review_name_placeholder'    => 'Your Name',
		'product_detail_review_email_placeholder'   => 'Your Email',
		'product_detail_review_comment_placeholder' => 'Your Review',
		'product_detail_review_author'              => 'Jonathan Andrew',
		'product_detail_review_role'                => 'Happy Customer',

		// ── Cart ─────────────────────────────────────────────
		'cart_title'                                => 'Cart',
		'cart_heading'                              => 'Shopping Cart',
		'cart_continue_text'                        => 'Continue Shopping',
		'cart_summary_title'                        => 'Summary',
		'cart_tax_label'                            => 'Estimate Tax',
		'cart_tax_description'                      => 'Enter your billing address to get a tax estimate.',
		'cart_country_label'                        => 'Country',
		'cart_country_placeholder'                  => 'Select Country',
		'cart_state_label'                          => 'State/Province',
		'cart_state_placeholder'                    => 'Select State',
		'cart_zip_label'                            => 'Zip/ postal code',
		'cart_subtotal_label'                       => 'Sub Total',
		'cart_total_label'                          => 'Total',
		'cart_discount_label'                       => 'Apply Discount Code',
		'cart_discount_placeholder'                 => 'Enter discount code',
		'cart_discount_btn'                         => 'Apply Discount',
		'cart_checkout_text'                        => 'Proceed to checkout',
		'cart_coupon_enable'                        => 1,
		'cart_cross_sell_enable'                    => 1,

		// ── Checkout ─────────────────────────────────────────
		'checkout_title'                            => 'Checkout',
		'checkout_step_shipping'                    => 'Shipping',
		'checkout_step_payment'                     => 'Review & Payments',
		'checkout_shipping_label'                   => 'Shipping Address:',
		'checkout_email_label'                      => 'Email Address',
		'checkout_account_note'                     => 'You can create an account after checkout.',
		'checkout_fname_label'                      => 'First Name',
		'checkout_lname_label'                      => 'Last Name',
		'checkout_company_label'                    => 'Company',
		'checkout_street_label'                     => 'Street Address',
		'checkout_street_line1_label'               => 'Street Address: Line1',
		'checkout_country_label'                    => 'Country',
		'checkout_country_placeholder'              => 'Select Country',
		'checkout_state_label'                      => 'State/Province',
		'checkout_state_placeholder'                => 'Select State',
		'checkout_city_label'                       => 'City',
		'checkout_city_placeholder'                 => 'Select City',
		'checkout_zip_label'                        => 'Zip/ postal code',
		'checkout_phone_label'                      => 'Phone Number',
		'checkout_shipping_methods_label'           => 'Shipping Methods:',
		'checkout_shipping_free_price'              => '$0.00',
		'checkout_shipping_free_label'              => 'Free',
		'checkout_shipping_free_name'               => 'Free Shipping',
		'checkout_shipping_flat_price'              => '$5.00',
		'checkout_shipping_flat_label'              => 'Fixed',
		'checkout_shipping_flat_name'               => 'Flat Rate',
		'checkout_next_btn'                         => 'Next',
		'checkout_summary_title'                    => 'Order Summary',
		'checkout_subtotal_label'                   => 'Sub Total',
		'checkout_total_label'                      => 'Total',
		'checkout_items_label'                      => 'Items in cart',
		'checkout_item_name'                        => 'Charm Wall Clock',
		'checkout_item_qty_label'                   => 'Qty:',
		'checkout_item_price'                       => '$38.00',
		'checkout_payment_label'                    => 'Payment Method:',
		'checkout_credit_card_label'                => 'Credit card',
		'checkout_card_number_label'                => 'Card number',
		'checkout_expiration_label'                 => 'Expiration date',
		'checkout_month_placeholder'                => 'Month',
		'checkout_year_placeholder'                 => 'Year',
		'checkout_security_label'                   => 'Security Code',
		'checkout_cod_label'                        => 'Cash on Delivery',
		'checkout_terms_text'                       => 'By clicking the button, you agree to the',
		'checkout_terms_link_text'                  => 'Terms and Conditions',

		// ── Colors ───────────────────────────────────────────
		'color_primary'                             => '#705b53',
		'color_secondary'                           => '#c19a6b',
		'color_accent'                              => '#d4a373',
		'color_text'                                => '#666666',
		'color_heading'                             => '#222222',
		'color_background'                          => '#ffffff',
		'color_header_bg'                           => '#ffffff',
		'color_footer_bg'                           => '#222222',
		'color_link'                                => '#705b53',
		'color_link_hover'                          => '#c19a6b',
		'color_border'                              => '#e5e5e5',
		'color_sale'                                => '#e74c3c',

		// ── Typography ───────────────────────────────────────
		'typography_heading_font'                   => 'Archivo',
		'typography_body_font'                      => 'Jost',
		'typography_base_size'                      => 16,
		'typography_heading_weight'                 => '700',
		'typography_body_weight'                    => '400',
		'typography_line_height'                    => 1.6,
		'typography_letter_spacing'                 => 0,

		// ── Buttons ──────────────────────────────────────────
		'button_bg'                                 => '#705b53',
		'button_text'                               => '#ffffff',
		'button_bg_hover'                           => '#c19a6b',
		'button_text_hover'                         => '#ffffff',
		'button_radius'                             => 4,
		'button_padding_y'                          => 12,
		'button_padding_x'                          => 24,

		// ── Portfolio ────────────────────────────────────────
		'portfolio_title'                           => 'Our Projects',
		'portfolio_projects_per_page'               => 9,
		'portfolio_columns'                         => 3,

		// ── Maintenance Mode ─────────────────────────────────
		'maintenance_mode_enable'                   => 0,

		// ── Animations ───────────────────────────────────────
		'animations_enable'                         => 1,
		'animations_duration'                       => '2s',
		'animations_delay'                          => '0.05s',
		'effect_3d_enable'                          => 0,
		'effect_3d_perspective'                     => 1000,
		'effect_3d_rotate_x'                        => 5,
		'effect_3d_rotate_y'                        => 5,

		// ── Performance ──────────────────────────────────────
		'performance_lazy_load'                     => 1,
		'performance_minify_css'                    => 0,
		'performance_minify_js'                     => 0,

		// ── Social Media ─────────────────────────────────────
		'social_facebook'                           => 'https://www.facebook.com/',
		'social_instagram'                          => 'https://www.instagram.com/',
		'social_youtube'                            => 'https://www.youtube.com/',
		'social_twitter'                            => 'https://twitter.com/',
		'social_pinterest'                          => '',
		'social_tiktok'                             => '',

		// ── Cookie / GDPR ────────────────────────────────────
		'cookie_enable'                             => 0,
		'cookie_message'                            => 'This website uses cookies to improve your experience.',
		'cookie_accept_text'                        => 'Accept',
		'cookie_decline_text'                       => 'Decline',
		'cookie_policy_link'                        => '/privacy-policy/',
		'cookie_title'                              => 'Cookies Policy',
		'cookie_content'                            => '',
		'privacy_title'                             => 'Privacy Policy',
		'privacy_content'                           => '',

		// ── Page Templates ───────────────────────────────────
		'four_column_title'                         => 'Four Column',
		'three_column_title'                        => 'Three Column',
		'three_colum_sidbar_title'                  => 'Three Column Sidebar',
		'two_column_title'                          => 'Two Column',
		'six_colum_full_wide_title'                 => 'Six Column',
		'one_column_title'                          => 'One Column',

		// ── Team Page ─────────────────────────────────────────
		'team_title'                                => 'Team',
		'team_title_inner'                          => 'Our Team Members',
		'team_heading'                              => 'Experts Team',
		'team_mission_heading'                      => 'Our Mission',
		'team_mission_title'                        => 'Inspiring Homes <br>Enriching Lives',
		'team_members'                              => array(),

		// ── Testimonials Page ─────────────────────────────────
		'testimonials_title'                        => 'Testimonials',
		'testimonials_title_inner'                  => 'Our Client Reviews',
		'testimonials_heading'                      => 'Testimonials',

		// ── Thank You Page ────────────────────────────────────
		'thank_you_title'                           => 'Thank You!',
		'thank_you_text'                            => 'Thank you for your order! We\'re committed to your health and well-being.',
		'thank_you_btn_text'                        => 'Back to Home',
		'thank_you_btn_url'                         => home_url( '/' ),
		'thank_you_icon'                            => '',

		// ── Terms & Conditions ────────────────────────────────
		'terms_title'                               => 'Term of Use',
		'terms_content'                             => '',

		// ── Single Blog ───────────────────────────────────────
		'single_blog_author_image'                  => '',
		'single_blog_author_name'                   => 'Billy wallson',
		'single_blog_author_role'                   => 'Senior Director',
		'single_blog_author_bio'                    => '',
		'single_blog_quote_image'                   => '',
		'single_blog_quote_text'                    => '',
		'single_blog_prev_text'                     => 'Prev',
		'single_blog_next_text'                     => 'Next',
		'single_blog_tags_heading'                  => 'Related Tags',
		'single_blog_social_heading'                => 'Social Share',
		'single_blog_reply_text'                    => 'Reply',
		'single_blog_comment_heading'               => 'Leave a Comment',
		'single_blog_comment_placeholder'           => 'Enter your comment here...',
		'single_blog_comment_name_placeholder'      => 'Your name',
		'single_blog_comment_email_placeholder'     => 'Your e-mail',
		'single_blog_comment_btn'                   => 'Post Comment',
		'single_blog_search_heading'                => 'Search News',
		'single_blog_search_placeholder'            => 'Search Here...',
		'single_blog_categories_heading'            => 'Popular Category',
		'single_blog_follow_heading'                => 'Follow Us',
		'single_blog_feeds_heading'                 => 'Feeds',
		'single_blog_tags_sidebar_heading'          => 'Tags',

		// ── Login ─────────────────────────────────────────────
		'login_title'                               => 'Welcome Back !',
		'login_btn_text'                            => 'Login',
		'login_email_label'                         => 'Enter Your E-mail',
		'login_password_label'                      => 'Enter Your Password',
		'login_remember_label'                      => 'Remember me',
		'login_lost_password_text'                  => 'Lost Password?',
		'login_lost_password_url'                   => '/contact/',
		'login_join_link'                           => 'Join now, create your FREE account',
		'login_logo'                                => '',

		// ── Join / Register ───────────────────────────────────
		'join_title'                                => 'Create Your FREE Account',
		'join_btn_text'                             => 'Register Now',
		'join_name_label'                           => 'Your full name',
		'join_email_label'                          => 'Your e-mail',
		'join_password_label'                       => 'Enter your password',
		'join_updates_label'                        => 'Inform me about new features and updates (max. twice a month)',
		'join_login_link'                           => 'Already have an account?',
		'join_referral_label'                       => 'How did you find out about Noncorehub?',
		'join_referral_default'                     => 'Please, choose the first interaction you remember.',
		'join_logo'                                 => '',

		// ── Load More ─────────────────────────────────────────
		'load_more_title'                           => 'Load More',
		'load_more_button_text'                     => 'Load More',
		'load_more_video_vimeo'                     => '',
		'load_more_video_youtube'                   => '',
		'load_more_by_text'                         => 'By : Admin',
		'load_more_category_text'                   => 'Virtual Assistant',
		'load_more_date'                            => 'Dec 20,2022',
		'load_more_read_more'                       => 'Read More',
		'load_more_post_title'                      => 'Why You Need Virtual Assistant for Your Company',
		'load_more_img_1'                           => 'standard_post_img01.jpg',
		'load_more_img_2'                           => 'standard_post_img02.jpg',
		'load_more_img_3'                           => 'standard_post_img04.jpg',
		'load_more_img_4'                           => 'standard_post_img06.jpg',
		'load_more_img_5'                           => 'standard_post_img03.jpg',
		'load_more_img_6'                           => 'blog-image4.jpg',

	);
}

/**
 * Get a single default value by key.
 */
function get_default( string $key ) {
	static $defaults = null;
	if ( null === $defaults ) {
		$defaults = get_defaults();
	}
	return $defaults[ $key ] ?? null;
}
