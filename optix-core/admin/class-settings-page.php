<?php
declare(strict_types=1);

namespace OptixCore;

use OptixCore\Registry\Settings_Registry;

defined( 'ABSPATH' ) || exit;

class Settings_Page {

	private static ?Settings_Page $instance = null;

	private array $tab_sections = [
		'general'      => [ 'branding', 'header', 'topbar' ],
		'navigation'   => [ 'navigation' ],
		'homepage'     => [ 'hero', 'collections', 'home_sections' ],
		'shop'         => [ 'shop_page', 'product_page', 'product_cards', 'woocommerce' ],
		'blog'         => [ 'blog' ],
		'footer'       => [ 'footer' ],
		'typography'   => [ 'typography' ],
		'colors'       => [ 'colors' ],
		'buttons'      => [ 'buttons', 'forms' ],
		'layout'       => [ 'layout', 'spacing', 'responsive' ],
		'effects'      => [ 'animations', 'effects_3d' ],
		'integrations' => [ 'integrations', 'search', 'performance', 'seo', 'accessibility' ],
		'custom_code'  => [ 'custom_code', 'import_export' ],
		'pages'        => [ 'about_page', 'contact_page', 'faq_page', 'coming_soon', 'error_404', 'login_page', 'register_page', 'portfolio', 'thank_you', 'load_more', 'privacy', 'terms', 'team', 'testimonials', 'announcement_bar' ],
	];

	public static function get_instance(): self {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function init(): void {
		add_action( 'admin_menu', [ $this, 'add_admin_menu' ] );
		add_action( 'admin_init', [ $this, 'register_settings' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
	}

	public function add_admin_menu(): void {
		add_options_page(
			__( 'Optix Framework', 'optix-core' ),
			__( 'Optix Framework', 'optix-core' ),
			'manage_options',
			'optix-framework',
			[ $this, 'render_page' ]
		);
	}

	public function enqueue_scripts( string $hook_suffix ): void {
		if ( 'settings_page_optix-framework' !== $hook_suffix ) {
			return;
		}
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker' );
		wp_add_inline_script(
			'wp-color-picker',
			'jQuery(function($){$(".optix-color-field").wpColorPicker();});'
		);
	}

	public function register_settings(): void {
		$registry = Settings_Registry::get_instance();

		foreach ( $registry->get_entries() as $key => $entry ) {
			register_setting( 'optix_framework', 'optix_' . $key, [
				'type'              => $this->map_wp_type( $entry['type'] ?? 'string' ),
				'sanitize_callback' => $entry['sanitize'] ?? null,
				'default'           => $entry['default'] ?? null,
				'show_in_rest'      => false,
			] );
		}

		register_setting( 'optix_framework', 'optix_active_profile', [
			'type'              => 'string',
			'sanitize_callback' => [ $this, 'sanitize_profile' ],
			'default'           => 'default',
		] );
	}

	public function sanitize_profile( $value ): string {
		$profile     = sanitize_file_name( $value ?? 'default' );
		$profile_dir = get_template_directory() . '/profiles/' . $profile;
		if ( ! is_dir( $profile_dir ) ) {
			add_settings_error(
				'optix_framework',
				'invalid_profile',
				__( 'Selected profile directory does not exist. Reverted to default.', 'optix-core' ),
				'error'
			);
			return 'default';
		}
		return $profile;
	}

	public function render_page(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$registry       = Settings_Registry::get_instance();
		$profiles       = $this->get_available_profiles();
		$active_profile = Profile_Router::get_instance()->get_active_profile();
		$current_tab    = sanitize_key( $_GET['tab'] ?? 'general' );

		if ( ! isset( $this->tab_sections[ $current_tab ] ) ) {
			$current_tab = 'general';
		}

		$tab_keys = array_keys( $this->tab_sections );
		$tab_index = array_search( $current_tab, $tab_keys, true );
		$prev_tab  = false !== $tab_index && isset( $tab_keys[ $tab_index - 1 ] ) ? $tab_keys[ $tab_index - 1 ] : '';
		$next_tab  = false !== $tab_index && isset( $tab_keys[ $tab_index + 1 ] ) ? $tab_keys[ $tab_index + 1 ] : '';
		?>
		<div class="wrap">
			<h1><?php echo esc_html__( 'Optix Framework Settings', 'optix-core' ); ?></h1>

			<form action="options.php" method="post">
				<?php settings_fields( 'optix_framework' ); ?>
				<table class="form-table">
					<tr>
						<th scope="row"><?php esc_html_e( 'Active Profile', 'optix-core' ); ?></th>
						<td>
							<select name="optix_active_profile">
								<?php foreach ( $profiles as $profile ) : ?>
									<option value="<?php echo esc_attr( $profile ); ?>" <?php selected( $active_profile, $profile ); ?>>
										<?php echo esc_html( $profile ); ?>
									</option>
								<?php endforeach; ?>
							</select>
							<p class="description"><?php esc_html_e( 'Select which template profile is active on the front-end.', 'optix-core' ); ?></p>
						</td>
					</tr>
				</table>
				<?php submit_button( __( 'Save Profile', 'optix-core' ) ); ?>
			</form>

			<hr>

			<nav class="nav-tab-wrapper">
				<?php foreach ( $this->tab_sections as $tab_slug => $_sections ) : ?>
					<a href="<?php echo esc_url( add_query_arg( 'tab', $tab_slug ) ); ?>"
					   class="nav-tab <?php echo $current_tab === $tab_slug ? 'nav-tab-active' : ''; ?>">
						<?php echo esc_html( $this->get_tab_label( $tab_slug ) ); ?>
					</a>
				<?php endforeach; ?>
			</nav>

			<form action="options.php" method="post">
				<?php settings_fields( 'optix_framework' ); ?>
				<?php $this->render_tab_content( $registry, $current_tab ); ?>
				<?php
				if ( $prev_tab ) {
					echo '<a href="' . esc_url( add_query_arg( 'tab', $prev_tab ) ) . '" class="button">' . esc_html__( '&larr; Previous', 'optix-core' ) . '</a> ';
				}
				submit_button( __( 'Save Changes', 'optix-core' ), 'primary', 'submit', false );
				if ( $next_tab ) {
					echo ' <a href="' . esc_url( add_query_arg( 'tab', $next_tab ) ) . '" class="button">' . esc_html__( 'Next &rarr;', 'optix-core' ) . '</a>';
				}
				?>
			</form>

			<hr>
			<h2><?php esc_html_e( 'Available Profiles', 'optix-core' ); ?></h2>
			<table class="wp-list-table widefat fixed striped">
				<thead>
					<tr>
						<th><?php esc_html_e( 'Profile', 'optix-core' ); ?></th>
						<th><?php esc_html_e( 'Status', 'optix-core' ); ?></th>
						<th><?php esc_html_e( 'Preview', 'optix-core' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ( $profiles as $profile ) : ?>
						<tr>
							<td><?php echo esc_html( $profile ); ?></td>
							<td><?php echo $profile === $active_profile ? esc_html__( 'Active', 'optix-core' ) : esc_html__( 'Inactive', 'optix-core' ); ?></td>
							<td>
								<a href="<?php echo esc_url( add_query_arg( 'optix_preview_profile', $profile, home_url( '/' ) ) ); ?>"
								   class="button button-small" target="_blank">
									<?php esc_html_e( 'Preview', 'optix-core' ); ?>
								</a>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		<?php
	}

	private function render_tab_content( Settings_Registry $registry, string $tab ): void {
		$sections = $this->tab_sections[ $tab ] ?? [];

		foreach ( $sections as $section ) {
			$entries = $this->get_entries_for_section( $registry, $section );
			if ( empty( $entries ) ) {
				continue;
			}
			?>
			<h2 class="optix-section-title"><?php echo esc_html( $this->get_section_label( $section ) ); ?></h2>
			<table class="form-table">
				<tbody>
					<?php foreach ( $entries as $key => $entry ) : ?>
						<tr>
							<th scope="row">
								<label for="optix_<?php echo esc_attr( $key ); ?>">
									<?php echo esc_html( $entry['label'] ?? $key ); ?>
								</label>
							</th>
							<td>
								<?php $this->render_field( $key, $entry, $registry->get( $key ) ); ?>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			<?php
		}
	}

	private function get_entries_for_section( Settings_Registry $registry, string $section ): array {
		$result = [];
		foreach ( $registry->get_entries() as $key => $entry ) {
			if ( ( $entry['section'] ?? '' ) === $section ) {
				$result[ $key ] = $entry;
			}
		}
		return $result;
	}

	private function render_field( string $key, array $entry, mixed $value ): void {
		$name    = 'optix_' . $key;
		$type    = $entry['type'] ?? 'string';
		$default = $entry['default'] ?? '';
		$current = false !== $value ? $value : $default;

		switch ( $type ) {
			case 'text':
			case 'code':
				?>
				<textarea name="<?php echo esc_attr( $name ); ?>"
				          id="<?php echo esc_attr( $name ); ?>"
				          class="large-text <?php echo 'code' === $type ? 'code' : ''; ?>"
				          rows="5"><?php echo esc_textarea( is_string( $current ) ? $current : '' ); ?></textarea>
				<?php
				break;

			case 'int':
				?>
				<input type="number" name="<?php echo esc_attr( $name ); ?>"
				       id="<?php echo esc_attr( $name ); ?>"
				       value="<?php echo esc_attr( (string) intval( $current ) ); ?>"
				       class="regular-text" step="1">
				<?php
				break;

			case 'float':
				?>
				<input type="number" name="<?php echo esc_attr( $name ); ?>"
				       id="<?php echo esc_attr( $name ); ?>"
				       value="<?php echo esc_attr( (string) floatval( $current ) ); ?>"
				       class="regular-text" step="0.01">
				<?php
				break;

			case 'bool':
				?>
				<fieldset>
					<label for="<?php echo esc_attr( $name ); ?>">
						<input type="hidden" name="<?php echo esc_attr( $name ); ?>" value="0">
						<input type="checkbox" name="<?php echo esc_attr( $name ); ?>"
						       id="<?php echo esc_attr( $name ); ?>"
						       value="1" <?php checked( 1, $current ); ?>>
						<?php echo esc_html( $entry['label'] ?? '' ); ?>
					</label>
				</fieldset>
				<?php
				break;

			case 'color':
				?>
				<input type="text" name="<?php echo esc_attr( $name ); ?>"
				       id="<?php echo esc_attr( $name ); ?>"
				       value="<?php echo esc_attr( is_string( $current ) ? $current : '' ); ?>"
				       class="optix-color-field regular-text"
				       data-default-color="<?php echo esc_attr( $default ); ?>">
				<?php
				break;

			case 'select':
				$options = $entry['options'] ?? [];
				?>
				<select name="<?php echo esc_attr( $name ); ?>" id="<?php echo esc_attr( $name ); ?>">
					<?php foreach ( $options as $opt_val => $opt_label ) : ?>
						<option value="<?php echo esc_attr( $opt_val ); ?>" <?php selected( $opt_val, $current ); ?>>
							<?php echo esc_html( $opt_label ); ?>
						</option>
					<?php endforeach; ?>
				</select>
				<?php
				break;

			case 'image':
				?>
				<input type="text" name="<?php echo esc_attr( $name ); ?>"
				       id="<?php echo esc_attr( $name ); ?>"
				       value="<?php echo esc_attr( is_string( $current ) ? $current : '' ); ?>"
				       class="regular-text">
				<button type="button" class="button optix-media-button"
				        data-target="<?php echo esc_attr( $name ); ?>">
					<?php esc_html_e( 'Select Image', 'optix-core' ); ?>
				</button>
				<?php
				break;

			case 'array':
			case 'repeater':
				?>
				<textarea name="<?php echo esc_attr( $name ); ?>"
				          id="<?php echo esc_attr( $name ); ?>"
				          class="large-text code"
				          rows="5"><?php echo esc_textarea( is_string( $current ) ? $current : wp_json_encode( $current, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES ) ); ?></textarea>
				<?php
				break;

			default:
				?>
				<input type="text" name="<?php echo esc_attr( $name ); ?>"
				       id="<?php echo esc_attr( $name ); ?>"
				       value="<?php echo esc_attr( is_string( $current ) ? $current : (string) $current ); ?>"
				       class="regular-text">
				<?php
				break;
		}
	}

	private function map_wp_type( string $type ): string {
		$map = [
			'int'      => 'integer',
			'float'    => 'float',
			'bool'     => 'boolean',
			'array'    => 'array',
			'repeater' => 'array',
		];
		return $map[ $type ] ?? 'string';
	}

	private function get_tab_label( string $slug ): string {
		$labels = [
			'general'      => __( 'General', 'optix-core' ),
			'navigation'   => __( 'Navigation', 'optix-core' ),
			'homepage'     => __( 'Homepage', 'optix-core' ),
			'shop'         => __( 'Shop', 'optix-core' ),
			'blog'         => __( 'Blog', 'optix-core' ),
			'footer'       => __( 'Footer', 'optix-core' ),
			'typography'   => __( 'Typography', 'optix-core' ),
			'colors'       => __( 'Colors', 'optix-core' ),
			'buttons'      => __( 'Buttons &amp; Forms', 'optix-core' ),
			'layout'       => __( 'Layout &amp; Spacing', 'optix-core' ),
			'effects'      => __( 'Effects', 'optix-core' ),
			'integrations' => __( 'Integrations', 'optix-core' ),
			'custom_code'  => __( 'Custom Code', 'optix-core' ),
			'pages'        => __( 'Pages', 'optix-core' ),
		];
		return $labels[ $slug ] ?? ucwords( str_replace( '_', ' ', $slug ) );
	}

	private function get_section_label( string $section ): string {
		$labels = [
			'branding'         => __( 'Branding', 'optix-core' ),
			'header'           => __( 'Header', 'optix-core' ),
			'topbar'           => __( 'Top Bar', 'optix-core' ),
			'navigation'       => __( 'Navigation', 'optix-core' ),
			'hero'             => __( 'Hero Section', 'optix-core' ),
			'collections'      => __( 'Collections', 'optix-core' ),
			'home_sections'    => __( 'Home Sections', 'optix-core' ),
			'shop_page'        => __( 'Shop Page', 'optix-core' ),
			'product_page'     => __( 'Product Page', 'optix-core' ),
			'product_cards'    => __( 'Product Cards', 'optix-core' ),
			'woocommerce'      => __( 'WooCommerce', 'optix-core' ),
			'blog'             => __( 'Blog', 'optix-core' ),
			'footer'           => __( 'Footer', 'optix-core' ),
			'typography'       => __( 'Typography', 'optix-core' ),
			'colors'           => __( 'Colors', 'optix-core' ),
			'buttons'          => __( 'Buttons', 'optix-core' ),
			'forms'            => __( 'Forms', 'optix-core' ),
			'layout'           => __( 'Layout', 'optix-core' ),
			'spacing'          => __( 'Spacing', 'optix-core' ),
			'responsive'       => __( 'Responsive', 'optix-core' ),
			'animations'       => __( 'Animations', 'optix-core' ),
			'effects_3d'       => __( '3D Effects', 'optix-core' ),
			'search'           => __( 'Search', 'optix-core' ),
			'performance'      => __( 'Performance', 'optix-core' ),
			'seo'              => __( 'SEO', 'optix-core' ),
			'accessibility'    => __( 'Accessibility', 'optix-core' ),
			'integrations'     => __( 'Integrations', 'optix-core' ),
			'custom_code'      => __( 'Custom Code', 'optix-core' ),
			'import_export'    => __( 'Import / Export', 'optix-core' ),
			'about_page'       => __( 'About Page', 'optix-core' ),
			'contact_page'     => __( 'Contact Page', 'optix-core' ),
			'faq_page'         => __( 'FAQ Page', 'optix-core' ),
			'coming_soon'      => __( 'Coming Soon', 'optix-core' ),
			'error_404'        => __( '404 Page', 'optix-core' ),
			'login_page'       => __( 'Login Page', 'optix-core' ),
			'register_page'    => __( 'Register Page', 'optix-core' ),
			'portfolio'        => __( 'Portfolio', 'optix-core' ),
			'thank_you'        => __( 'Thank You', 'optix-core' ),
			'load_more'        => __( 'Load More', 'optix-core' ),
			'privacy'          => __( 'Privacy', 'optix-core' ),
			'terms'            => __( 'Terms', 'optix-core' ),
			'team'             => __( 'Team', 'optix-core' ),
			'testimonials'     => __( 'Testimonials', 'optix-core' ),
			'announcement_bar' => __( 'Announcement Bar', 'optix-core' ),
		];
		return $labels[ $section ] ?? ucwords( str_replace( '_', ' ', $section ) );
	}

	private function get_available_profiles(): array {
		$profiles_dir = get_template_directory() . '/profiles';
		if ( ! is_dir( $profiles_dir ) ) {
			return [ 'default' ];
		}
		$dirs     = glob( $profiles_dir . '/*', GLOB_ONLYDIR );
		$profiles = [];
		foreach ( $dirs as $dir ) {
			$profiles[] = basename( $dir );
		}
		return $profiles;
	}
}
