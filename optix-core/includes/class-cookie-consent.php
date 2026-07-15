<?php
declare(strict_types=1);

namespace OptixCore;

defined( 'ABSPATH' ) || exit;

class Cookie_Consent {

	private static ?self $instance = null;

	private string $cookie_name = 'optix_cookie_consent';

	private int $cookie_expiry = YEAR_IN_SECONDS;

	public static function get_instance(): self {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function init(): void {
		add_action( 'wp_footer', [ $this, 'render_bar' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		add_action( 'wp_ajax_optix_consent', [ $this, 'handle_consent' ] );
		add_action( 'wp_ajax_nopriv_optix_consent', [ $this, 'handle_consent' ] );
		add_filter( 'body_class', [ $this, 'body_class' ] );
	}

	public function render_bar(): void {
		if ( isset( $_COOKIE[ $this->cookie_name ] ) ) {
			return;
		}

		$message = optix_get_option( 'cookie_message', 'This site uses cookies.' );
		?>
		<div id="optix-cookie-bar" style="position:fixed;bottom:0;left:0;right:0;z-index:999999;background:#1a1a1a;color:#fff;padding:1em;text-align:center;font-size:14px;">
			<span><?php echo esc_html( $message ); ?></span>
			<button id="optix-cookie-accept" style="margin-left:1em;padding:0.5em 1.5em;background:#4caf50;color:#fff;border:none;cursor:pointer;">
				<?php echo esc_html__( 'Accept', 'optix-core' ); ?>
			</button>
		</div>
		<?php
	}

	public function enqueue_scripts(): void {
		if ( isset( $_COOKIE[ $this->cookie_name ] ) ) {
			return;
		}

		wp_add_inline_script(
			'jquery',
			'(function($){
				$("#optix-cookie-accept").on("click",function(e){
					e.preventDefault();
					$.post("' . esc_url( admin_url( 'admin-ajax.php' ) ) . '",{
						action:"optix_consent",
						nonce:"' . esc_js( wp_create_nonce( 'optix_consent_nonce' ) ) . '"
					}).done(function(){
						$("#optix-cookie-bar").fadeOut();
					});
				});
			})(jQuery);'
		);
	}

	public function handle_consent(): void {
		check_ajax_referer( 'optix_consent_nonce', 'nonce' );

		setcookie(
			$this->cookie_name,
			'accepted',
			time() + $this->cookie_expiry,
			COOKIEPATH,
			COOKIE_DOMAIN,
			is_ssl(),
			true
		);

		wp_send_json_success( [ 'consent' => 'accepted' ] );
	}

	public function body_class( array $classes ): array {
		if ( isset( $_COOKIE[ $this->cookie_name ] ) ) {
			$classes[] = 'optix-cookie-consent-accepted';
		}
		return $classes;
	}
}
