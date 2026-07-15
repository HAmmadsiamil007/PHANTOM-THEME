<?php
/**
 * Kids Collection - Newsletter section
 *
 * @package optix
 */
$kc_nonce = wp_create_nonce( 'optix_newsletter_nonce' );
?>
<section class="float-left w-100 position-relative newsletter-con main-box background-primary">
  <div class="container">
    <div class="d-flex align-items-center justify-content-between">
      <div class="heading wow fadeInLeft" data-wow-duration="2s" data-wow-delay="0.05s">
        <h3 class="mb-0 text-white"><?php echo wp_kses_post( optix_get_option( 'newsletter_heading' ) ); ?></h3>
      </div>
      <form class="optix-newsletter-form wow fadeInRight" data-wow-duration="2s" data-wow-delay="0.05s" method="post">
        <div class="form-group position-relative mb-0 align-items-center d-flex">
          <input type="email" class="form_style" placeholder="<?php echo esc_attr( optix_get_option( 'newsletter_placeholder' ) ); ?>" name="email" required aria-label="<?php echo esc_attr_x( 'Email address for newsletter', 'newsletter', 'optix' ); ?>">
          <button type="submit" aria-label="<?php echo esc_attr( optix_get_option( 'newsletter_btn_text' ) ); ?>"><?php echo esc_html( optix_get_option( 'newsletter_btn_text' ) ); ?> <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></button>
        </div>
        <div class="optix-newsletter-msg" style="margin-top:5px;font-size:13px;"></div>
      </form>
    </div>
  </div>
</section>
<script>
document.querySelectorAll('.optix-newsletter-form').forEach(function(f){f.addEventListener('submit',function(e){e.preventDefault();var m=f.querySelector('.optix-newsletter-msg');m.innerHTML='';var d=new FormData();d.append('action','optix_newsletter_subscribe');d.append('email',f.querySelector('[name=email]').value);d.append('optix_newsletter_nonce','<?php echo esc_js( $kc_nonce ); ?>');fetch('<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>',{method:'POST',body:d}).then(function(r){return r.json();}).then(function(j){m.innerHTML=j.status==='Success'?'<span style="color:#8bc34a;">'+j.msg+'</span>':'<span style="color:#ff6b6b;">'+j.msg+'</span>';}).catch(function(){m.innerHTML='<span style="color:#ff6b6b;"><?php echo esc_js( __( 'Network error. Please try again.', 'optix' ) ); ?></span>';});});});
</script>
