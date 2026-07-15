<?php
/**
 * Kids Collection - Top bar
 *
 * @package optix
 */
?>
<div class="top-bar-con position-relative float-left w-100 main-box">
  <div class="main-container">
    <div class="top-bar-box d-flex align-items-center justify-content-between">
      <div class="top-bar-info">
        <p class="text mb-0"><?php echo wp_kses_post( optix_get_option( 'topbar_sale_text' ) ); ?></p>
      </div>
      <div class="top-bar-social">
        <div class="other_list d-flex align-items-center position-relative">
          <div class="country-selector lang-dropdown">
            <div class="caption">
              <?php
              $optix_languages = optix_get_option( 'topbar_languages' );
              if ( ! empty( $optix_languages ) ) :
                $optix_first = $optix_languages[0];
              ?>
              <img src="<?php echo esc_url( optix_img( $optix_first['lang_flag'] ?? '/header-flag1.png' ) ); ?>" alt="<?php echo esc_attr( $optix_first['lang_code'] ?? 'EN' ); ?>" loading="lazy"><?php echo esc_html( $optix_first['lang_code'] ?? 'EN' ); ?>
              <img src="<?php echo esc_url( optix_img( '/header-dropdown.png' ) ); ?>" alt="dropdown" loading="lazy">
              <?php endif; ?>
            </div>
            <div class="list" id="lang-dropdown">
              <?php if ( ! empty( $optix_languages ) ) : ?>
                <?php foreach ( $optix_languages as $optix_lang ) : ?>
              <div class="item">
                <img src="<?php echo esc_url( optix_img( $optix_lang['lang_flag'] ?? '/header-flag1.png' ) ); ?>" alt="<?php echo esc_attr( $optix_lang['lang_code'] ?? '' ); ?>" loading="lazy">
                <a class="text-decoration-none" href="<?php echo esc_url( $optix_lang['lang_url'] ?? '/' ); ?>" data-country="<?php echo esc_attr( $optix_lang['lang_country'] ?? '' ); ?>"><?php echo esc_html( $optix_lang['lang_code'] ?? '' ); ?></a>
              </div>
                <?php endforeach; ?>
              <?php endif; ?>
            </div>
          </div>
          <div class="currency-selector curr-dropdown">
            <div class="caption">
              <?php
              $optix_currencies = optix_get_option( 'topbar_currencies' );
              $optix_first_cur = ! empty( $optix_currencies ) ? $optix_currencies[0] : null;
              ?>
              <?php echo esc_html( $optix_first_cur['currency_code'] ?? 'USD' ); ?> <img src="<?php echo esc_url( optix_img( '/header-dropdown.png' ) ); ?>" alt="dropdown" loading="lazy">
            </div>
            <div class="list" id="curr-dropdown">
              <?php if ( ! empty( $optix_currencies ) ) : ?>
                <?php foreach ( $optix_currencies as $optix_cur ) : ?>
              <div class="item"><a class="text-decoration-none" href="<?php echo esc_url( $optix_cur['currency_url'] ?? '/' ); ?>" data-currency="<?php echo esc_attr( $optix_cur['currency_code'] ?? '' ); ?>"><?php echo esc_html( $optix_cur['currency_code'] ?? '' ); ?></a></div>
                <?php endforeach; ?>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
