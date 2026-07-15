<?php
declare(strict_types=1);

namespace OptixCore;

use OptixCore\Registry\Settings_Registry;
use OptixCore\Registry\Demo_Registry;

defined('ABSPATH') || exit;

class Setup_Wizard {

    private static ?self $instance = null;
    private string $step = 'welcome';
    private array $steps = ['welcome', 'profile', 'settings', 'woocommerce', 'demo', 'done'];

    public static function get_instance(): self {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function init(): void {
        add_action('admin_menu', [$this, 'add_wizard_page']);
        add_action('admin_init', [$this, 'maybe_redirect']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_styles']);
        add_filter('admin_body_class', [$this, 'add_body_class']);
        add_action('admin_post_optix_setup_step', [$this, 'handle_submission']);
    }

    public function add_wizard_page(): void {
        add_submenu_page(
            null,
            __('Setup Wizard', 'optix-core'),
            __('Setup Wizard', 'optix-core'),
            'manage_options',
            'optix-setup',
            [$this, 'render']
        );
    }

    public function maybe_redirect(): void {
        if (!current_user_can('manage_options')) {
            return;
        }
        if (get_option('optix_setup_complete', false)) {
            return;
        }
        $screen = get_current_screen();
        if ($screen && 'admin_page_optix-setup' === $screen->id) {
            return;
        }
        if (isset($_GET['page']) && 'optix-setup' === $_GET['page']) {
            return;
        }
        if (defined('DOING_AJAX') && DOING_AJAX) {
            return;
        }
        wp_safe_redirect(admin_url('admin.php?page=optix-setup'));
        exit;
    }

    public function enqueue_styles(string $hook_suffix): void {
        if ('admin_page_optix-setup' !== $hook_suffix) {
            return;
        }
        wp_enqueue_media();
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker');
        wp_add_inline_style('wp-color-picker', '
.optix-setup-wizard {
    max-width: 800px;
    margin: 40px auto;
    background: #fff;
    padding: 40px;
    border-radius: 8px;
    box-shadow: 0 2px 20px rgba(0,0,0,0.08);
}
.optix-setup-wizard h1 {
    margin-top: 0;
    font-size: 28px;
    font-weight: 600;
}
.optix-setup-wizard .step-indicator {
    display: flex;
    margin-bottom: 40px;
    padding: 0;
    list-style: none;
}
.optix-setup-wizard .step-indicator .step {
    flex: 1;
    text-align: center;
    position: relative;
    font-size: 13px;
    color: #999;
}
.optix-setup-wizard .step-indicator .step .circle {
    display: block;
    width: 32px;
    height: 32px;
    line-height: 32px;
    margin: 0 auto 8px;
    border-radius: 50%;
    background: #f0f0f1;
    color: #666;
    font-size: 14px;
    font-weight: 600;
}
.optix-setup-wizard .step-indicator .step.active .circle {
    background: #2271b1;
    color: #fff;
}
.optix-setup-wizard .step-indicator .step.completed .circle {
    background: #46b450;
    color: #fff;
}
.optix-setup-wizard .step-indicator .step.active {
    color: #2271b1;
    font-weight: 600;
}
.optix-setup-wizard .step-indicator .step.completed {
    color: #46b450;
}
.optix-setup-wizard .step-indicator .step::after {
    content: "";
    position: absolute;
    top: 16px;
    left: calc(50% + 20px);
    width: calc(100% - 40px);
    height: 2px;
    background: #e5e5e5;
}
.optix-setup-wizard .step-indicator .step:last-child::after {
    display: none;
}
.optix-setup-wizard .step-indicator .step.completed::after {
    background: #46b450;
}
.optix-setup-wizard .setup-body {
    min-height: 300px;
}
.optix-setup-wizard .setup-footer {
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #ddd;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.optix-setup-wizard .setup-footer .button {
    min-width: 120px;
    text-align: center;
}
.optix-setup-wizard .requirements-list {
    list-style: none;
    padding: 0;
    margin: 20px 0;
}
.optix-setup-wizard .requirements-list li {
    padding: 10px 15px;
    margin-bottom: 8px;
    background: #f6f7f7;
    border-radius: 4px;
    font-size: 14px;
}
.optix-setup-wizard .requirements-list .pass {
    color: #46b450;
}
.optix-setup-wizard .requirements-list .fail {
    color: #d63638;
}
.optix-setup-wizard .profile-options {
    margin: 20px 0;
}
.optix-setup-wizard .profile-option {
    display: block;
    padding: 15px;
    margin-bottom: 10px;
    border: 2px solid #e5e5e5;
    border-radius: 6px;
    cursor: pointer;
    transition: border-color 0.2s;
}
.optix-setup-wizard .profile-option:hover {
    border-color: #2271b1;
}
.optix-setup-wizard .profile-option.selected {
    border-color: #2271b1;
    background: #f0f6fc;
}
.optix-setup-wizard .profile-option input[type="radio"] {
    margin-right: 10px;
}
.optix-setup-wizard .profile-option h3 {
    margin: 0 0 4px;
    font-size: 16px;
}
.optix-setup-wizard .profile-option p {
    margin: 0;
    color: #666;
    font-size: 13px;
}
.optix-setup-wizard .form-table th {
    width: 150px;
}
.optix-setup-wizard .color-presets {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    margin: 10px 0;
}
.optix-setup-wizard .color-preset {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: 3px solid transparent;
    cursor: pointer;
    transition: border-color 0.2s;
}
.optix-setup-wizard .color-preset:hover,
.optix-setup-wizard .color-preset.selected {
    border-color: #2271b1;
}
.optix-setup-wizard .demo-packages {
    margin: 20px 0;
}
.optix-setup-wizard .demo-package {
    padding: 20px;
    margin-bottom: 12px;
    border: 2px solid #e5e5e5;
    border-radius: 6px;
    cursor: pointer;
    transition: border-color 0.2s;
}
.optix-setup-wizard .demo-package:hover {
    border-color: #2271b1;
}
.optix-setup-wizard .demo-package.selected {
    border-color: #2271b1;
    background: #f0f6fc;
}
.optix-setup-wizard .demo-package input[type="checkbox"] {
    margin-right: 10px;
}
.optix-setup-wizard .demo-package h3 {
    margin: 0 0 4px;
    font-size: 15px;
}
.optix-setup-wizard .demo-package p {
    margin: 0;
    color: #666;
    font-size: 13px;
}
.optix-setup-wizard .done-icon {
    text-align: center;
    font-size: 64px;
    color: #46b450;
    margin-bottom: 20px;
}
.optix-setup-wizard .done-actions {
    display: flex;
    gap: 12px;
    justify-content: center;
    margin-top: 30px;
}
');
    }

    public function add_body_class(string $classes): string {
        $screen = get_current_screen();
        if ($screen && 'admin_page_optix-setup' === $screen->id) {
            $classes .= ' optix-wizard-active';
        }
        return $classes;
    }

    public function render(): void {
        if (!current_user_can('manage_options')) {
            wp_die(esc_html__('You do not have sufficient permissions.', 'optix-core'));
        }

        $this->step = isset($_GET['step']) ? sanitize_key($_GET['step']) : 'welcome';
        if (!in_array($this->step, $this->steps, true)) {
            $this->step = 'welcome';
        }

        $step_index = array_search($this->step, $this->steps, true);
        ?>
        <div class="wrap optix-setup-wizard">
            <ul class="step-indicator">
                <?php foreach ($this->steps as $i => $s): ?>
                    <li class="step<?php echo $i === $step_index ? ' active' : ''; ?><?php echo $i < $step_index ? ' completed' : ''; ?>">
                        <span class="circle"><?php echo $i < $step_index ? '&#10003;' : esc_html((string) ($i + 1)); ?></span>
                        <?php echo esc_html(ucfirst($s)); ?>
                    </li>
                <?php endforeach; ?>
            </ul>

            <div class="setup-body">
                <?php $this->render_step($this->step); ?>
            </div>
        </div>
        <?php
    }

    private function render_step(string $step): void {
        switch ($step) {
            case 'welcome':
                $this->render_welcome();
                break;
            case 'profile':
                $this->render_profile();
                break;
            case 'settings':
                $this->render_settings();
                break;
            case 'woocommerce':
                $this->render_woocommerce();
                break;
            case 'demo':
                $this->render_demo();
                break;
            case 'done':
                $this->render_done();
                break;
        }
    }

    private function render_welcome(): void {
        $php_ok = version_compare(PHP_VERSION, '8.1', '>=');
        $wp_ok = version_compare($GLOBALS['wp_version'] ?? '0', '6.4', '>=');
        $wc_active = class_exists('WooCommerce');
        ?>
        <h1><?php esc_html_e('Welcome to Optix Framework', 'optix-core'); ?></h1>
        <p><?php esc_html_e('Thank you for installing Optix Core. This quick setup wizard will help you configure the framework and get your site running in minutes.', 'optix-core'); ?></p>

        <h2><?php esc_html_e('Requirements Check', 'optix-core'); ?></h2>
        <ul class="requirements-list">
            <li>
                <span class="<?php echo $php_ok ? 'pass' : 'fail'; ?>">
                    <?php echo $php_ok ? '&#10003;' : '&#10007;'; ?>
                </span>
                <?php esc_html_e('PHP 8.1+', 'optix-core'); ?>
                (<?php echo esc_html(PHP_VERSION); ?>)
            </li>
            <li>
                <span class="<?php echo $wp_ok ? 'pass' : 'fail'; ?>">
                    <?php echo $wp_ok ? '&#10003;' : '&#10007;'; ?>
                </span>
                <?php esc_html_e('WordPress 6.4+', 'optix-core'); ?>
                (<?php echo esc_html($GLOBALS['wp_version'] ?? '0'); ?>)
            </li>
            <li>
                <span class="<?php echo $wc_active ? 'pass' : 'pass'; ?>">
                    <?php echo $wc_active ? '&#10003;' : '&#8212;'; ?>
                </span>
                <?php esc_html_e('WooCommerce', 'optix-core'); ?>
                (<?php echo $wc_active ? esc_html__('Active', 'optix-core') : esc_html__('Optional', 'optix-core'); ?>)
            </li>
        </ul>

        <div class="setup-footer">
            <span></span>
            <a href="<?php echo esc_url(admin_url('admin.php?page=optix-setup&step=profile')); ?>" class="button button-primary">
                <?php esc_html_e('Get Started', 'optix-core'); ?>
            </a>
        </div>
        <?php
    }

    private function render_profile(): void {
        $active_profile = get_option('optix_active_profile', 'default');
        $theme_dir = get_template_directory() . '/profiles/';
        $profiles = [];

        if (is_dir($theme_dir)) {
            $dirs = glob($theme_dir . '*', GLOB_ONLYDIR);
            if ($dirs) {
                foreach ($dirs as $dir) {
                    $name = basename($dir);
                    $desc_file = $dir . '/description.txt';
                    $description = '';
                    if (file_exists($desc_file)) {
                        $description = sanitize_textarea_field((string) file_get_contents($desc_file));
                    }
                    $profiles[$name] = $description ?: sprintf(
                        __('%s profile', 'optix-core'),
                        ucfirst($name)
                    );
                }
            }
        }

        if (empty($profiles)) {
            $profiles['default'] = __('Default profile', 'optix-core');
        }
        ?>
        <h1><?php esc_html_e('Choose a Profile', 'optix-core'); ?></h1>
        <p><?php esc_html_e('Select the profile that best fits your site. You can switch later in settings.', 'optix-core'); ?></p>

        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <?php wp_nonce_field('optix_setup_nonce', 'optix_setup_nonce'); ?>
            <input type="hidden" name="action" value="optix_setup_step">
            <input type="hidden" name="step" value="profile">
            <input type="hidden" name="redirect_to" value="settings">

            <div class="profile-options">
                <?php foreach ($profiles as $name => $desc): ?>
                    <label class="profile-option<?php echo $name === $active_profile ? ' selected' : ''; ?>">
                        <input type="radio" name="profile" value="<?php echo esc_attr($name); ?>" <?php checked($name, $active_profile); ?>>
                        <h3><?php echo esc_html(ucfirst($name)); ?></h3>
                        <p><?php echo esc_html($desc); ?></p>
                    </label>
                <?php endforeach; ?>
            </div>

            <div class="setup-footer">
                <a href="<?php echo esc_url(admin_url('admin.php?page=optix-setup&step=welcome')); ?>" class="button">
                    <?php esc_html_e('Back', 'optix-core'); ?>
                </a>
                <button type="submit" class="button button-primary">
                    <?php esc_html_e('Next Step', 'optix-core'); ?>
                </button>
            </div>
        </form>
        <?php
    }

    private function render_settings(): void {
        $registry = Settings_Registry::get_instance();
        $registry->register();
        ?>
        <h1><?php esc_html_e('Quick Settings', 'optix-core'); ?></h1>
        <p><?php esc_html_e('Configure your brand colors and typography. You can change these later.', 'optix-core'); ?></p>

        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <?php wp_nonce_field('optix_setup_nonce', 'optix_setup_nonce'); ?>
            <input type="hidden" name="action" value="optix_setup_step">
            <input type="hidden" name="step" value="settings">
            <input type="hidden" name="redirect_to" value="<?php echo class_exists('WooCommerce') ? 'woocommerce' : 'demo'; ?>">

            <table class="form-table">
                <tr>
                    <th scope="row"><?php esc_html_e('Site Logo', 'optix-core'); ?></th>
                    <td>
                        <div class="logo-upload-wrap">
                            <input type="hidden" name="site_logo" id="optix-site-logo" value="<?php echo esc_attr($registry->get('site_logo') ?? ''); ?>">
                            <button type="button" class="button" id="optix-logo-upload">
                                <?php esc_html_e('Select Logo', 'optix-core'); ?>
                            </button>
                            <button type="button" class="button" id="optix-logo-remove" style="display:none;">
                                <?php esc_html_e('Remove', 'optix-core'); ?>
                            </button>
                            <div id="optix-logo-preview" style="margin-top:10px;max-width:200px;">
                                <?php
                                $logo_id = $registry->get('site_logo');
                                if ($logo_id && wp_get_attachment_image_url((int) $logo_id)) {
                                    echo wp_get_attachment_image((int) $logo_id, 'medium');
                                }
                                ?>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e('Primary Color', 'optix-core'); ?></th>
                    <td>
                        <input type="text" name="color_primary" value="<?php echo esc_attr($registry->get('color_primary') ?: '#705b53'); ?>" class="optix-color-picker" data-default-color="#705b53">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e('Secondary Color', 'optix-core'); ?></th>
                    <td>
                        <input type="text" name="color_secondary" value="<?php echo esc_attr($registry->get('color_secondary') ?: '#c19a6b'); ?>" class="optix-color-picker" data-default-color="#c19a6b">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e('Accent Color', 'optix-core'); ?></th>
                    <td>
                        <input type="text" name="color_accent" value="<?php echo esc_attr($registry->get('color_accent') ?: '#d4a373'); ?>" class="optix-color-picker" data-default-color="#d4a373">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e('Heading Font', 'optix-core'); ?></th>
                    <td>
                        <select name="typography_heading_font">
                            <?php
                            $fonts = ['Archivo', 'Jost', 'Inter', 'Playfair Display', 'Merriweather', 'Lato', 'Montserrat', 'Open Sans', 'Poppins', 'Raleway'];
                            $current = $registry->get('typography_heading_font') ?: 'Archivo';
                            foreach ($fonts as $font) {
                                printf(
                                    '<option value="%s" %s>%s</option>',
                                    esc_attr($font),
                                    selected($font, $current, false),
                                    esc_html($font)
                                );
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e('Body Font', 'optix-core'); ?></th>
                    <td>
                        <select name="typography_body_font">
                            <?php
                            $current = $registry->get('typography_body_font') ?: 'Jost';
                            foreach ($fonts as $font) {
                                printf(
                                    '<option value="%s" %s>%s</option>',
                                    esc_attr($font),
                                    selected($font, $current, false),
                                    esc_html($font)
                                );
                            }
                            ?>
                        </select>
                    </td>
                </tr>
            </table>

            <div class="setup-footer">
                <a href="<?php echo esc_url(admin_url('admin.php?page=optix-setup&step=profile')); ?>" class="button">
                    <?php esc_html_e('Back', 'optix-core'); ?>
                </a>
                <button type="submit" class="button button-primary">
                    <?php esc_html_e('Next Step', 'optix-core'); ?>
                </button>
            </div>
        </form>

        <script>
        jQuery(document).ready(function($) {
            $('.optix-color-picker').wpColorPicker();

            var frame;
            $('#optix-logo-upload').on('click', function(e) {
                e.preventDefault();
                if (frame) {
                    frame.open();
                    return;
                }
                frame = wp.media({
                    title: '<?php echo esc_js(__('Select Logo', 'optix-core')); ?>',
                    button: { text: '<?php echo esc_js(__('Use as Logo', 'optix-core')); ?>' },
                    multiple: false
                });
                frame.on('select', function() {
                    var attachment = frame.state().get('selection').first().toJSON();
                    $('#optix-site-logo').val(attachment.id);
                    $('#optix-logo-preview').html('<img src="' + attachment.url + '" style="max-width:200px;height:auto;">');
                    $('#optix-logo-remove').show();
                });
                frame.open();
            });

            $('#optix-logo-remove').on('click', function() {
                $('#optix-site-logo').val('');
                $('#optix-logo-preview').empty();
                $(this).hide();
            });

            if ($('#optix-logo-preview').children().length) {
                $('#optix-logo-remove').show();
            }
        });
        </script>
        <?php
    }

    private function render_woocommerce(): void {
        if (!class_exists('WooCommerce')) {
            wp_safe_redirect(admin_url('admin.php?page=optix-setup&step=demo'));
            exit;
        }

        $registry = Settings_Registry::get_instance();
        $registry->register();
        ?>
        <h1><?php esc_html_e('WooCommerce Settings', 'optix-core'); ?></h1>
        <p><?php esc_html_e('Configure your online store layout and product display.', 'optix-core'); ?></p>

        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <?php wp_nonce_field('optix_setup_nonce', 'optix_setup_nonce'); ?>
            <input type="hidden" name="action" value="optix_setup_step">
            <input type="hidden" name="step" value="woocommerce">
            <input type="hidden" name="redirect_to" value="demo">

            <table class="form-table">
                <tr>
                    <th scope="row"><?php esc_html_e('Shop Layout', 'optix-core'); ?></th>
                    <td>
                        <select name="shop_layout">
                            <?php
                            $layouts = [
                                'full-width' => __('Full Width', 'optix-core'),
                                'sidebar-left' => __('Sidebar Left', 'optix-core'),
                                'sidebar-right' => __('Sidebar Right', 'optix-core'),
                            ];
                            $current = $registry->get('shop_layout') ?: 'sidebar-left';
                            foreach ($layouts as $val => $label) {
                                printf(
                                    '<option value="%s" %s>%s</option>',
                                    esc_attr($val),
                                    selected($val, $current, false),
                                    esc_html($label)
                                );
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e('Products Per Page', 'optix-core'); ?></th>
                    <td>
                        <input type="number" name="products_per_page" value="<?php echo esc_attr($registry->get('products_per_page') ?: 12); ?>" min="1" max="100" step="1" class="small-text">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e('Product Columns', 'optix-core'); ?></th>
                    <td>
                        <input type="number" name="shop_columns" value="<?php echo esc_attr($registry->get('shop_columns') ?: 3); ?>" min="1" max="6" step="1" class="small-text">
                    </td>
                </tr>
            </table>

            <div class="setup-footer">
                <a href="<?php echo esc_url(admin_url('admin.php?page=optix-setup&step=settings')); ?>" class="button">
                    <?php esc_html_e('Back', 'optix-core'); ?>
                </a>
                <button type="submit" class="button button-primary">
                    <?php esc_html_e('Next Step', 'optix-core'); ?>
                </button>
            </div>
        </form>
        <?php
    }

    private function render_demo(): void {
        $demo_registry = Demo_Registry::get_instance();
        $demo_registry->register();
        $packages = $demo_registry->list_packages();
        ?>
        <h1><?php esc_html_e('Demo Import', 'optix-core'); ?></h1>
        <p><?php esc_html_e('Optionally import demo content to get started quickly. You can skip this step.', 'optix-core'); ?></p>

        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <?php wp_nonce_field('optix_setup_nonce', 'optix_setup_nonce'); ?>
            <input type="hidden" name="action" value="optix_setup_step">
            <input type="hidden" name="step" value="demo">
            <input type="hidden" name="redirect_to" value="done">

            <div class="demo-packages">
                <label class="demo-package">
                    <input type="checkbox" name="import_demo" value="0">
                    <h3><?php esc_html_e('No demo content', 'optix-core'); ?></h3>
                    <p><?php esc_html_e('Start with a clean site and configure everything manually.', 'optix-core'); ?></p>
                </label>
                <?php foreach ($packages as $id => $pkg): ?>
                    <label class="demo-package">
                        <input type="radio" name="demo_package" value="<?php echo esc_attr($id); ?>">
                        <h3><?php echo esc_html($pkg['title']); ?></h3>
                        <p><?php echo esc_html($pkg['description']); ?></p>
                        <?php if (!empty($pkg['required_plugins'])): ?>
                            <p class="description">
                                <?php esc_html_e('Requires:', 'optix-core'); ?>
                                <?php echo esc_html(implode(', ', $pkg['required_plugins'])); ?>
                            </p>
                        <?php endif; ?>
                    </label>
                <?php endforeach; ?>
            </div>

            <div class="setup-footer">
                <a href="<?php echo esc_url(admin_url('admin.php?page=optix-setup&step=' . (class_exists('WooCommerce') ? 'woocommerce' : 'settings'))); ?>" class="button">
                    <?php esc_html_e('Back', 'optix-core'); ?>
                </a>
                <button type="submit" class="button button-primary">
                    <?php esc_html_e('Next Step', 'optix-core'); ?>
                </button>
                <a href="<?php echo esc_url(admin_url('admin.php?page=optix-setup&step=done')); ?>" class="button">
                    <?php esc_html_e('Skip', 'optix-core'); ?>
                </a>
            </div>
        </form>
        <?php
    }

    private function render_done(): void {
        ?>
        <div class="done-icon">&#10003;</div>
        <h1 style="text-align:center;"><?php esc_html_e('Setup Complete!', 'optix-core'); ?></h1>
        <p style="text-align:center;font-size:16px;">
            <?php esc_html_e('Your Optix Framework is ready. You can start building your site now.', 'optix-core'); ?>
        </p>

        <div class="done-actions">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="button button-primary" target="_blank">
                <?php esc_html_e('View Site', 'optix-core'); ?>
            </a>
            <a href="<?php echo esc_url(admin_url('admin.php?page=optix-framework')); ?>" class="button">
                <?php esc_html_e('Open Settings', 'optix-core'); ?>
            </a>
            <a href="<?php echo esc_url(admin_url('admin.php?page=optix-setup&step=profile')); ?>" class="button">
                <?php esc_html_e('Switch Profile', 'optix-core'); ?>
            </a>
        </div>
        <?php
    }

    public function handle_submission(): void {
        if (!current_user_can('manage_options')) {
            wp_die(esc_html__('You do not have sufficient permissions.', 'optix-core'));
        }

        check_admin_referer('optix_setup_nonce', 'optix_setup_nonce');

        $step = isset($_POST['step']) ? sanitize_key($_POST['step']) : '';
        $redirect_to = isset($_POST['redirect_to']) ? sanitize_key($_POST['redirect_to']) : 'done';

        switch ($step) {
            case 'profile':
                if (!empty($_POST['profile'])) {
                    $profile = sanitize_file_name(wp_unslash($_POST['profile']));
                    update_option('optix_active_profile', $profile);
                }
                break;

            case 'settings':
                $registry = Settings_Registry::get_instance();
                $registry->register();

                $settings_map = [
                    'site_logo' => 'absint',
                    'color_primary' => 'sanitize_hex_color',
                    'color_secondary' => 'sanitize_hex_color',
                    'color_accent' => 'sanitize_hex_color',
                    'typography_heading_font' => 'sanitize_text_field',
                    'typography_body_font' => 'sanitize_text_field',
                ];

                foreach ($settings_map as $key => $sanitize_cb) {
                    if (isset($_POST[$key])) {
                        $value = wp_unslash($_POST[$key]);
                        if ('absint' === $sanitize_cb) {
                            $value = absint($value);
                        } elseif ('sanitize_hex_color' === $sanitize_cb) {
                            $value = sanitize_hex_color($value) ?: '';
                        } else {
                            $value = sanitize_text_field((string) $value);
                        }
                        $registry->set($key, $value);
                    }
                }
                break;

            case 'woocommerce':
                $registry = Settings_Registry::get_instance();
                $registry->register();

                $wc_map = [
                    'shop_layout' => 'sanitize_text_field',
                    'products_per_page' => 'absint',
                    'shop_columns' => 'absint',
                ];

                foreach ($wc_map as $key => $sanitize_cb) {
                    if (isset($_POST[$key])) {
                        $value = wp_unslash($_POST[$key]);
                        if ('absint' === $sanitize_cb) {
                            $value = absint($value);
                        } else {
                            $value = sanitize_text_field((string) $value);
                        }
                        $registry->set($key, $value);
                    }
                }
                break;

			case 'demo':
				if (!empty($_POST['demo_package'])) {
					$package = sanitize_key(wp_unslash($_POST['demo_package']));
					update_option('optix_demo_import_pending', $package);
				}
				break;
        }

        if ('done' === $redirect_to) {
            update_option('optix_setup_complete', time());
        }

        wp_safe_redirect(admin_url('admin.php?page=optix-setup&step=' . $redirect_to));
        exit;
    }
}
