<?php
/**
 * Localization strings.
 *
 * @package optix
 */

namespace Optix;

add_filter( 'air_helper_pll_register_strings', function() {
  $strings = [
    // 'Key: String' => 'String',
  ];

  /**
    * Uncomment if you need to have default accessibility strings
   * translatable via Polylang string translations.
   */
  // foreach ( get_default_localization_strings( get_bloginfo( 'language' ) ) as $key => $value ) {
  // $strings[ "Accessibility: {$key}" ] = $value;
  // }

  return apply_filters( 'optix_translations', $strings );
} );

function get_default_localization_strings( $language = 'en' ) {
  $strings = [
    'en'  => [
      'Add a menu'                                   => __( 'Add a menu', 'optix' ),
      'Open main menu'                               => __( 'Open main menu', 'optix' ),
      'Close main menu'                              => __( 'Close main menu', 'optix' ),
      'Main navigation'                              => __( 'Main navigation', 'optix' ),
      'Back to top'                                  => __( 'Back to top', 'optix' ),
      'Open child menu for'                          => __( 'Open child menu for', 'optix' ),
      'Close child menu for'                         => __( 'Close child menu for', 'optix' ),
      'Skip to content'                              => __( 'Skip to content', 'optix' ),
      'Skip over the carousel element'               => __( 'Skip over the carousel element', 'optix' ),
      'External site'                                => __( 'External site', 'optix' ),
      'opens in a new window'                        => __( 'opens in a new window', 'optix' ),
      'Page not found.'                              => __( 'Page not found.', 'optix' ),
      'The reason might be mistyped or expired URL.' => __( 'The reason might be mistyped or expired URL.', 'optix' ),
      'Search'                                       => __( 'Search', 'optix' ),
      'Block missing required data'                  => __( 'Block missing required data', 'optix' ),
      'This error is shown only for logged in users' => __( 'This error is shown only for logged in users', 'optix' ),
      'No results found for your search'             => __( 'No results found for your search', 'optix' ),
      'Edit'                                         => __( 'Edit', 'optix' ),
      'Previous slide'                               => __( 'Previous slide', 'optix' ),
      'Next slide'                                   => __( 'Next slide', 'optix' ),
      'Last slide'                                   => __( 'Last slide', 'optix' ),
    ],
    'fi'  => [
      'Add a menu'                                   => 'Luo uusi valikko',
      'Open main menu'                               => 'Avaa pÃ¤Ã¤valikko',
      'Close main menu'                              => 'Sulje pÃ¤Ã¤valikko',
      'Main navigation'                              => 'PÃ¤Ã¤valikko',
      'Back to top'                                  => 'Siirry takaisin sivun alkuun',
      'Open child menu for'                          => 'Avaa alavalikko kohteelle',
      'Close child menu for'                         => 'Sulje alavalikko kohteelle',
      'Skip to content'                              => 'Siirry suoraan sisÃ¤ltÃ¶Ã¶n',
      'Skip over the carousel element'               => 'HyppÃ¤Ã¤ karusellisisÃ¤llÃ¶n yli seuraavaan sisÃ¤ltÃ¶Ã¶n',
      'External site'                                => 'Ulkoinen sivusto',
      'opens in a new window'                        => 'avautuu uuteen ikkunaan',
      'Page not found.'                              => 'Hups. NÃ¤yttÃ¤Ã¤, ettei sivua lÃ¶ydy.',
      'The reason might be mistyped or expired URL.' => 'SyynÃ¤ voi olla virheellisesti kirjoitettu tai vanhentunut linkki.',
      'Search'                                       => 'Haku',
      'Block missing required data'                  => 'Lohkon pakollisia tietoja puuttuu',
      'This error is shown only for logged in users' => 'TÃ¤mÃ¤ virhe nÃ¤ytetÃ¤Ã¤n vain kirjautuneille kÃ¤yttÃ¤jille',
      'No results for your search'                   => 'Haullasi ei lÃ¶ytynyt tuloksia',
      'Edit'                                         => 'Muokkaa',
      'Previous slide'                               => 'Edellinen dia',
      'Next slide'                                   => 'Seuraava dia',
      'Last slide'                                   => 'Viimeinen dia',
    ],
  ];

  return ( array_key_exists( $language, $strings ) ) ? $strings[ $language ] : $strings['en'];
} // end get_default_localization_strings

function get_default_localization( $string ) { // phpcs:ignore Universal.NamingConventions.NoReservedKeywordParameterNames.stringFound
  if ( function_exists( 'ask__' ) && array_key_exists( "Accessibility: {$string}", apply_filters( 'air_helper_pll_register_strings', [] ) ) ) {
    return ask__( "Accessibility: {$string}" );
  }

  return esc_html( get_default_localization_translation( $string ) );
} // end get_default_localization

function get_default_localization_translation( $string ) { // phpcs:ignore Universal.NamingConventions.NoReservedKeywordParameterNames.stringFound
  $language = get_bloginfo( 'language' );
  if ( function_exists( 'pll_the_languages' ) ) {
    $language = pll_current_language();
  }

  $translations = get_default_localization_strings( $language );

  return ( array_key_exists( $string, $translations ) ) ? $translations[ $string ] : '';
} // end get_default_localization_translation
