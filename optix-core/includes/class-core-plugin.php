<?php
declare(strict_types=1);

namespace OptixCore;

use OptixCore\Registry\Settings_Registry;
use OptixCore\Registry\Section_Registry;
use OptixCore\Registry\Component_Registry;
use OptixCore\Registry\Animation_Registry;
use OptixCore\Registry\Asset_Registry;
use OptixCore\Registry\Block_Registry;
use OptixCore\Registry\Color_Registry;
use OptixCore\Registry\Demo_Registry;
use OptixCore\Registry\Hook_Registry;
use OptixCore\Registry\Layout_Registry;
use OptixCore\Registry\Preset_Registry;
use OptixCore\Registry\Responsive_Registry;
use OptixCore\Registry\Template_Registry;
use OptixCore\Registry\Typography_Registry;
use OptixCore\Registry\WooCommerce_Registry;
use OptixCore\Engine\Typography_Engine;
use OptixCore\Engine\Color_Engine;
use OptixCore\Engine\Layout_Engine;
use OptixCore\Engine\Responsive_Engine;
use OptixCore\Engine\Header_Builder;
use OptixCore\Engine\Footer_Builder;
use OptixCore\Engine\Blog_Engine;
use OptixCore\Engine\Search_Engine;
use OptixCore\Engine\Accessibility_Engine;
use OptixCore\Engine\Product_Engine;

defined( 'ABSPATH' ) || exit;

class Plugin {

	private static ?Plugin $instance = null;

	public static function get_instance(): self {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function init(): void {
		$this->init_registries();
		Options_Manager::get_instance()->init();
		Profile_Router::get_instance()->init();
		Cpt_Manager::get_instance()->init();
		Taxonomy_Manager::get_instance()->init();
		Maintenance_Mode::get_instance()->init();
		Mega_Menu::get_instance()->init();
		ThreeD_Effects::get_instance()->init();
		Acf_Blocks::get_instance()->init();
		Dynamic_CSS_Generator::get_instance()->init();
		Settings_Page::get_instance()->init();

		$this->init_engines();

		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			Section_Manifest_Validator::get_instance()->init();
		}
		Theme_API::get_instance()->init();
		Head_Manager::get_instance()->init();
		Asset_Registry::get_instance()->init();
		if ( class_exists( __NAMESPACE__ . '\\Demo_Importer' ) ) {
			Demo_Importer::get_instance()->init();
		}
		if ( class_exists( __NAMESPACE__ . '\\Ajax_Handlers' ) ) {
			Ajax_Handlers::get_instance()->init();
		}
		do_action( 'optix_core/init' );
	}

	private function init_engines(): void {
		Typography_Engine::get_instance()->init();
		Color_Engine::get_instance()->init();
		Layout_Engine::get_instance()->init();
		Responsive_Engine::get_instance()->init();
		Header_Builder::get_instance()->init();
		Footer_Builder::get_instance()->init();
		Blog_Engine::get_instance()->init();
		Search_Engine::get_instance()->init();
		Accessibility_Engine::get_instance()->init();
		if ( class_exists( 'WooCommerce' ) ) {
			Product_Engine::get_instance()->init();
		}
	}

	private function init_registries(): void {
		Settings_Registry::get_instance()->register();
		Section_Registry::get_instance()->register();
		Component_Registry::get_instance()->register();
		Template_Registry::get_instance()->register();
		Animation_Registry::get_instance()->register();
		Asset_Registry::get_instance()->register();
		Block_Registry::get_instance()->register();
		Color_Registry::get_instance()->register();
		Demo_Registry::get_instance()->register();
		Hook_Registry::get_instance()->register();
		Layout_Registry::get_instance()->register();
		Preset_Registry::get_instance()->register();
		Responsive_Registry::get_instance()->register();
		Typography_Registry::get_instance()->register();
		if ( class_exists( 'WooCommerce' ) ) {
			WooCommerce_Registry::get_instance()->register();
		}
	}
}
