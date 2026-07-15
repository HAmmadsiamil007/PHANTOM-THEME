<?php
declare(strict_types=1);

namespace OptixCore\Registry;

defined('ABSPATH') || exit;

class Component_Registry extends Base_Registry {

    protected function define_entries(): array {
        return [
            'product_card' => [
                'default' => [
                    'tag' => 'div',
                    'class' => 'seller-box',
                    'settings' => [
                        'show_rating' => true,
                        'show_price' => true,
                        'show_sale_tag' => true,
                        'show_add_to_cart' => true,
                        'show_wishlist' => true,
                        'show_quick_view' => true,
                        'image_size' => 'medium',
                        'price_multiplier' => 1.3,
                    ],
                ],
                'dependencies' => [],
                'description' => 'Product card with image, rating, price, action icons',
            ],
            'testimonial_card' => [
                'default' => [
                    'tag' => 'div',
                    'class' => 'testimonial-box',
                    'settings' => [
                        'show_rating' => true,
                        'show_avatar' => true,
                        'show_role' => true,
                        'max_rating' => 5,
                    ],
                ],
                'dependencies' => [],
                'description' => 'Testimonial card with rating stars, text, name, role, avatar',
            ],
            'category_item' => [
                'default' => [
                    'tag' => 'li',
                    'class' => 'position-relative',
                    'settings' => [
                        'show_bg_class' => true,
                        'aspect_ratio' => '1/1',
                    ],
                ],
                'dependencies' => [],
                'description' => 'Product category icon item with label',
            ],
            'benefit_item' => [
                'default' => [
                    'tag' => 'div',
                    'class' => 'benefits-box',
                    'settings' => [
                        'show_icon' => true,
                        'icon_size' => 40,
                    ],
                ],
                'dependencies' => [],
                'description' => 'Benefit/feature item with icon and text',
            ],
            'instagram_item' => [
                'default' => [
                    'tag' => 'li',
                    'class' => '',
                    'settings' => [
                        'overlay_icon' => true,
                        'link_target' => '_blank',
                    ],
                ],
                'dependencies' => [],
                'description' => 'Instagram gallery image item with overlay icon',
            ],
            'team_member' => [
                'default' => [
                    'tag' => 'div',
                    'class' => 'team-box',
                    'settings' => [
                        'show_social' => true,
                        'social_platforms' => ['facebook', 'instagram', 'youtube'],
                    ],
                ],
                'dependencies' => [],
                'description' => 'Team member card with image, name, role, social links',
            ],
            'promo_box' => [
                'default' => [
                    'tag' => 'div',
                    'class' => 'promo-box1',
                    'settings' => [
                        'show_tag' => true,
                        'show_discount' => true,
                        'show_cta' => true,
                    ],
                ],
                'dependencies' => [],
                'description' => 'Promotional box with tag, title, discount, CTA, image',
            ],
            'social_link' => [
                'default' => [
                    'tag' => 'a',
                    'class' => 'social-link',
                    'settings' => [
                        'target' => '_blank',
                        'rel' => 'noopener noreferrer',
                        'show_icon' => true,
                    ],
                ],
                'dependencies' => [],
                'description' => 'Social media profile link icon',
            ],
            'nav_link' => [
                'default' => [
                    'tag' => 'a',
                    'class' => 'nav-link',
                    'settings' => [
                        'show_icon' => false,
                        'icon_position' => 'left',
                    ],
                ],
                'dependencies' => [],
                'description' => 'Navigation menu link item',
            ],
            'rating_stars' => [
                'default' => [
                    'tag' => 'ul',
                    'class' => 'list-unstyled',
                    'settings' => [
                        'max_stars' => 5,
                        'star_icon' => 'fa-solid fa-star',
                        'show_text' => false,
                    ],
                ],
                'dependencies' => [],
                'description' => 'Star rating display with configurable count',
            ],
            'button' => [
                'default' => [
                    'tag' => 'a',
                    'class' => 'secondary_btn',
                    'settings' => [
                        'style' => 'primary',
                        'size' => 'md',
                        'show_icon' => true,
                        'icon' => 'fa-solid fa-arrow-right',
                    ],
                ],
                'dependencies' => [],
                'description' => 'CTA button with optional arrow icon',
            ],
            'section_heading' => [
                'default' => [
                    'tag' => 'div',
                    'class' => 'heading-title-con text-center',
                    'settings' => [
                        'show_subtitle' => true,
                        'heading_tag' => 'h2',
                        'subtitle_tag' => 'span',
                        'animation' => true,
                    ],
                ],
                'dependencies' => [],
                'description' => 'Section heading with optional subtitle and animations',
            ],
            'breadcrumb' => [
                'default' => [
                    'tag' => 'nav',
                    'class' => 'breadcrumb-con',
                    'settings' => [
                        'separator' => '/',
                        'show_home' => true,
                        'home_label' => 'Home',
                    ],
                ],
                'dependencies' => [],
                'description' => 'Breadcrumb navigation trail',
            ],
            'pagination' => [
                'default' => [
                    'tag' => 'div',
                    'class' => 'pagination-con',
                    'settings' => [
                        'style' => 'numeric',
                        'show_prev_next' => true,
                        'prev_text' => 'Prev',
                        'next_text' => 'Next',
                    ],
                ],
                'dependencies' => [],
                'description' => 'Page pagination with numeric and prev/next',
            ],
            'search_form' => [
                'default' => [
                    'tag' => 'form',
                    'class' => 'search-form',
                    'settings' => [
                        'placeholder' => 'Search products...',
                        'button_text' => 'Search',
                        'ajax' => false,
                    ],
                ],
                'dependencies' => [],
                'description' => 'Search form with configurable placeholder',
            ],
            'cookie_consent' => [
                'default' => [
                    'tag' => 'div',
                    'class' => 'optix-cookie-bar',
                    'settings' => [
                        'position' => 'bottom',
                        'accept_text' => 'Accept',
                        'decline_text' => 'Decline',
                        'expiry_days' => 365,
                    ],
                ],
                'dependencies' => [],
                'description' => 'GDPR cookie consent bar with accept/decline',
            ],
            'preloader' => [
                'default' => [
                    'tag' => 'div',
                    'class' => 'optix-preloader',
                    'settings' => [
                        'animation' => 'fade',
                        'duration' => 500,
                    ],
                ],
                'dependencies' => [],
                'description' => 'Page preloader animation overlay',
            ],
        ];
    }

    public function get_dependencies(string $component_key): array {
        $entry = $this->entries[$component_key] ?? null;
        if (null === $entry) {
            return [];
        }
        return $entry['dependencies'] ?? [];
    }

    public function get_settings(string $component_key): array {
        $entry = $this->entries[$component_key] ?? null;
        if (null === $entry) {
            return [];
        }
        return $entry['default']['settings'] ?? [];
    }

    public function render(string $component_key, array $data = []): ?string {
        $entry = $this->entries[$component_key] ?? null;
        if (null === $entry) {
            return null;
        }

        // Cascade file lookup: active profile → default profile → plugin fallback
        $file = 'components/' . str_replace('_', '-', $component_key) . '.php';
        try {
            $active = \OptixCore\Profile_Router::get_instance()->get_active_profile();
            $path   = get_template_directory() . '/profiles/' . $active . '/' . $file;
            if (file_exists($path)) {
                ob_start();
                require $path;
                return ob_get_clean();
            }
        } catch (\Throwable $e) {
            // Profile_Router not yet initialized
        }

        $path = get_template_directory() . '/profiles/default/' . $file;
        if (file_exists($path)) {
            ob_start();
            require $path;
            return ob_get_clean();
        }

        $path = defined('OPTIX_CORE_PATH') ? OPTIX_CORE_PATH . 'templates/' . $file : '';
        if (!empty($path) && file_exists($path)) {
            ob_start();
            require $path;
            return ob_get_clean();
        }

        // Fallback: flat HTML builder
        $settings = array_merge($entry['default']['settings'], $data['settings'] ?? []);
        $tag   = $data['tag'] ?? $entry['default']['tag'];
        $class = $data['class'] ?? $entry['default']['class'];

        $attrs = ' class="' . esc_attr($class) . '"';
        if (!empty($data['attrs'])) {
            foreach ($data['attrs'] as $attr => $val) {
                $attrs .= ' ' . $attr . '="' . esc_attr($val) . '"';
            }
        }

        return '<' . $tag . $attrs . '>' . ($data['content'] ?? '') . '</' . $tag . '>';
    }
}
