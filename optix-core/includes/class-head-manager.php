<?php
declare(strict_types=1);

namespace OptixCore;

defined( 'ABSPATH' ) || exit;

class Head_Manager {

	private static ?Head_Manager $instance = null;

	public static function get_instance(): self {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {}
	private function __clone() {}
	public function __wakeup() {}

	public function init(): void {
		add_action( 'wp_head', [ $this, 'output_seo_tags' ], 1 );
		add_action( 'wp_head', [ $this, 'output_analytics' ], 2 );
		add_action( 'wp_head', [ $this, 'output_json_ld_schema' ], 3 );
		add_action( 'wp_body_open', [ $this, 'render_gtm_noscript' ], 1 );
	}

	public function output_seo_tags(): void {
		$description = optix_get_option( 'seo_meta_description' );
		if ( $description ) {
			echo '<meta name="description" content="' . esc_attr( $description ) . '" />' . "\n";
		}

		$keywords = optix_get_option( 'seo_meta_keywords' );
		if ( $keywords ) {
			echo '<meta name="keywords" content="' . esc_attr( $keywords ) . '" />' . "\n";
		}

		$og_title = optix_get_option( 'seo_og_title' );
		if ( $og_title ) {
			echo '<meta property="og:title" content="' . esc_attr( $og_title ) . '" />' . "\n";
		}

		$og_description = optix_get_option( 'seo_og_description' );
		if ( $og_description ) {
			echo '<meta property="og:description" content="' . esc_attr( $og_description ) . '" />' . "\n";
		}

		$og_image = optix_get_option( 'seo_og_image' );
		if ( $og_image ) {
			echo '<meta property="og:image" content="' . esc_url( $og_image ) . '" />' . "\n";
		}
	}

	public function output_analytics(): void {
		$ga_id = optix_get_option( 'google_analytics_id' );
		if ( $ga_id ) {
			?>
<!-- Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_attr( $ga_id ); ?>"></script>
<script>
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('js', new Date());
gtag('config', '<?php echo esc_js( $ga_id ); ?>');
</script>
			<?php
		}

		$gtm = optix_get_option( 'google_tag_manager' );
		if ( $gtm ) {
			?>
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','<?php echo esc_js( $gtm ); ?>');</script>
			<?php
		}

		$fb_pixel = optix_get_option( 'facebook_pixel' );
		if ( $fb_pixel ) {
			?>
<!-- Facebook Pixel -->
<script>
!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
document,'script','https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '<?php echo esc_js( $fb_pixel ); ?>');
fbq('track', 'PageView');
</script>
			<?php
		}
	}

	public function render_gtm_noscript(): void {
		$gtm = optix_get_option( 'google_tag_manager' );
		if ( $gtm ) {
			?>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo esc_attr( $gtm ); ?>"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
			<?php
		}
	}

	public function output_json_ld_schema(): void {
		$logo_id  = get_theme_mod( 'custom_logo' );
		$logo_url = $logo_id ? wp_get_attachment_image_url( $logo_id, 'full' ) : '';
		$blogname = get_bloginfo( 'name' );
		?>
<script type="application/ld+json">
{
	"@context": "https://schema.org",
	"@graph": [
		{
			"@type": "Organization",
			"name": "<?php echo esc_js( $blogname ); ?>",
			"url": "<?php echo esc_url( home_url( '/' ) ); ?>"
			<?php if ( $logo_url ) : ?>,
			"logo": "<?php echo esc_url( $logo_url ); ?>"
			<?php endif; ?>
		},
		{
			"@type": "WebSite",
			"name": "<?php echo esc_js( $blogname ); ?>",
			"url": "<?php echo esc_url( home_url( '/' ) ); ?>",
			"potentialAction": {
				"@type": "SearchAction",
				"target": {
					"@type": "EntryPoint",
					"urlTemplate": "<?php echo esc_url( home_url( '/?s={search_term_string}' ) ); ?>"
				},
				"query-input": "required name=search_term_string"
			}
		}
	]
}
</script>
		<?php
	}

}
