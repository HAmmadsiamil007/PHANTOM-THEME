<?php
/**
 * Kids Collection - Login
 *
 * @package optix
 */

$kc_img = kc_img_base();

if ( is_user_logged_in() ) {
    $current_user = wp_get_current_user();
    ?>
    <section class="login-form d-flex align-items-center">
        <div class="container">
            <div class="login-form-title text-center">
                <h2><?php echo esc_html( optix_get_option( 'login_title', 'Welcome Back !' ) ); ?></h2>
            </div>
            <div class="login-form-box">
                <div class="login-card text-center">
                    <p><?php echo esc_html( sprintf( __( 'You are logged in as %s.', 'optix' ), $current_user->display_name ) ); ?></p>
                    <a href="<?php echo esc_url( wp_logout_url( home_url( '/' ) ) ); ?>" class="btn btn-primary hover-effect"><?php esc_html_e( 'Log Out', 'optix' ); ?></a>
                </div>
            </div>
        </div>
    </section>
    <?php
    return;
}
?>
<!-- LOGIN FORM SECTION -->
<section class="login-form d-flex align-items-center">
    <div class="container">
        <div class="login-form-title text-center">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="d-inline-block">
                <figure class="login-page-logo">
                    <img loading="lazy" src="<?php echo esc_url( optix_img( optix_get_option( 'login_logo' ), $kc_img . '/large-logo.png' ) ); ?>" alt="">
                </figure>
            </a>
            <h2><?php echo esc_html( optix_get_option( 'login_title', 'Welcome Back !' ) ); ?></h2>
        </div>
        <div class="login-form-box">
            <div class="login-card">
                <form class="" action="<?php echo esc_url( wp_login_url( get_permalink() ) ); ?>" method="POST">
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo esc_html( optix_get_option( 'login_email_label', 'Enter Your E-mail' ) ); ?></label>
                        <input class="input-field form_style" type="text" id="exampleInputEmail1" name="log"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1"><?php echo esc_html( optix_get_option( 'login_password_label', 'Enter Your Password' ) ); ?></label>
                        <input class="input-field form_style" type="password" id="exampleInputPassword1"
                            name="pwd" required>
                    </div>
                    <button type="submit" class="btn btn-primary hover-effect"><?php echo esc_html( optix_get_option( 'login_btn_text', 'Login' ) ); ?></button>
                    <div>
                        <label class="font-weight-normal mb-0" style="cursor: pointer;">
                            <input class="checkbox" type="checkbox" name="rememberme">
                            <?php echo esc_html( optix_get_option( 'login_remember_label', 'Remember me' ) ); ?>
                        </label>
                        <a href="<?php echo esc_url( optix_get_option( 'login_lost_password_url', '/contact/' ) ); ?>" class="forgot-password float-right"><?php echo esc_html( optix_get_option( 'login_lost_password_text', 'Lost Password?' ) ); ?></a>
                    </div>
                </form>
            </div>
            <div class="join-now-outer text-center">
                <a href="<?php echo esc_url( home_url( '/join-now/' ) ); ?>"><?php echo esc_html( optix_get_option( 'login_join_link', 'Join now, create your FREE account' ) ); ?></a>
            </div>
        </div>
    </div>
</section>
