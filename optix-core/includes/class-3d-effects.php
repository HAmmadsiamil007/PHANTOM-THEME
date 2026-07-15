<?php
declare(strict_types=1);

namespace OptixCore;

defined( 'ABSPATH' ) || exit;

class ThreeD_Effects {

	private static ?ThreeD_Effects $instance = null;

	public static function get_instance(): self {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function init(): void {
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ], 20 );
	}

	public function enqueue_scripts(): void {
		if ( ! optix_get_option( 'effect_3d_enable' ) ) {
			return;
		}

		wp_add_inline_script( 'optix-profile-script', '
document.addEventListener("DOMContentLoaded",function(){
  var e=document.querySelectorAll(".optix-tilt-3d");
  e.forEach(function(t){
    t.addEventListener("mousemove",function(e){
      var n=t.getBoundingClientRect();
      t.style.transform="rotateX("+(-((e.clientY-n.top)/n.height-.5)*10)+"deg) rotateY("+((e.clientX-n.left)/n.width-.5)*10+"deg)"
    });
    t.addEventListener("mouseleave",function(){t.style.transform="rotateX(0deg) rotateY(0deg)"})
  })
});
' );
	}
}
