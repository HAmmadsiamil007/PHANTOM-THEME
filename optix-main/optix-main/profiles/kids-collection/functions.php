<?php
add_filter( 'optix/css/mega-menu', function () { return '.navigation-wrapper'; } );
add_filter( 'optix/css/tilt-3d', function () { return '.product-card, .project-card'; } );
add_filter( 'optix/css/hero', function () { return '.banner-section, .hero-section'; } );
add_filter( 'optix/css/heading', function () { return 'h1, h2, h3, h4, h5, h6, .heading'; } );
add_filter( 'optix/css/body', function () { return 'body, p, .text'; } );
