/**
 * Navigation JavaScript for Optix theme.
 *
 * Handles mobile menu toggle, dropdown accessibility, and submenu animation.
 */
( function() {
	'use strict';

	var menuToggle = document.querySelector( '.menu-toggle' );
	var navMenu    = document.querySelector( '.nav-menu' );

	if ( menuToggle && navMenu ) {
		menuToggle.addEventListener( 'click', function() {
			navMenu.classList.toggle( 'toggled' );
			var expanded = navMenu.classList.contains( 'toggled' );
			menuToggle.setAttribute( 'aria-expanded', expanded );
		} );
	}

	var dropdowns = document.querySelectorAll( '.menu-item-has-children > a' );
	dropdowns.forEach( function( link ) {
		link.addEventListener( 'click', function( e ) {
			if ( window.innerWidth < 768 ) {
				var li = this.parentElement;
				li.classList.toggle( 'submenu-open' );
				e.preventDefault();
			}
		} );
	} );
} )();
