<?php
/**
 * Kids Collection - Join Now
 *
 * @package optix
 */

$kc_img = kc_img_base();

if ( is_user_logged_in() ) : ?>
    <section class="login-form sign-up-form d-flex align-items-center bg-lavendr">
        <div class="container">
            <div class="login-form-box">
                <div class="login-card text-center">
                    <h2><?php esc_html_e( 'You are already registered and logged in', 'optix' ); ?></h2>
                    <p><?php echo esc_html( optix_get_option( 'join_logged_in_text', 'Welcome back! You already have an active account.' ) ); ?></p>
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover-effect btn btn-primary"><?php esc_html_e( 'Go to Homepage', 'optix' ); ?></a>
                </div>
            </div>
        </div>
    </section>
<?php else : ?>
<!-- JOIN NOW FORM SECTION -->
<section class="login-form sign-up-form d-flex align-items-center bg-lavendr">
    <div class="container">
        <div class="login-form-title text-center">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="d-inline-block">
                <figure class="login-page-logo">
                    <img loading="lazy" src="<?php echo esc_url( optix_img( optix_get_option( 'join_logo' ), $kc_img . '/large-logo.png' ) ); ?>" alt="">
                </figure>
            </a>
            <h2><?php echo esc_html( optix_get_option( 'join_title', 'Create Your FREE Account' ) ); ?></h2>
        </div>
        <div class="login-form-box">
            <div class="login-card">
                <form class="" action="<?php echo esc_url( site_url( 'wp-login.php?action=register', 'login_post' ) ); ?>" method="POST">
                    <div class="form-group">
                        <label for="exampleInputName1"><?php echo esc_html( optix_get_option( 'join_name_label', 'Your full name' ) ); ?></label>
                        <input class="input-field form-control" type="text" id="exampleInputName1" name="full_name"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo esc_html( optix_get_option( 'join_email_label', 'Your e-mail' ) ); ?></label>
                        <input class="input-field form-control" type="email" id="exampleInputEmail1" name="email"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1"><?php echo esc_html( optix_get_option( 'join_password_label', 'Enter your password' ) ); ?> <small>min. 6
                                characters</small></label>
                        <input class="input-field form-control" type="password" id="exampleInputPassword1"
                            name="password" minlength="6" required>
                    </div>
                    <div class="form-group">
                        <label for="inputNoncorehub"><?php echo esc_html( optix_get_option( 'join_referral_label', 'How did you find out about Noncorehub?' ) ); ?>
                            <small>(optional)</small></label>
                        <select id="inputNoncorehub" name="referral_source" class="input-field form-control select-option">
                            <option selected><?php echo esc_html( optix_get_option( 'join_referral_default', 'Please, choose the first interaction you remember.' ) ); ?></option>
                            <option value="online_community">Online community (e.g. GitHub, Reddit, Stack Overflow, Hacker News, ...)</option>
                            <option value="article">Website article, blog (not ours)</option>
                            <option value="review_sites">Review site (e.g. G2, Capterra, ...)</option>
                            <option value="search">Search engine (e.g. Google, Bing, ...)</option>
                            <option value="mobile_app">App store listing (App Store, Google Play)</option>
                            <option value="social">Social media (e.g. Twitter / Quora / Facebook / LinkedIn...)</option>
                            <option value="youtube">YouTube</option>
                            <option value="friend">From a friend, colleague, ...</option>
                            <option value="message_groups">Message group (e.g. Discord, Slack, Telegram, ...)</option>
                            <option value="events">Event (e.g. workshop, conference or meet up)</option>
                            <option value="podcast">Podcast</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div>
                        <label class="font-weight-normal mb-md-4 mb-3" style="cursor: pointer;">
                            <input class="checkbox" type="checkbox" name="receive_updates">
                            <?php echo esc_html( optix_get_option( 'join_updates_label', 'Inform me about new features and updates (max. twice a month)' ) ); ?>
                        </label>
                    </div>
                    <button type="submit" class="hover-effect btn btn-primary mb-0"><?php echo esc_html( optix_get_option( 'join_btn_text', 'Register Now' ) ); ?></button>
                </form>
            </div>
            <div class="join-now-outer text-center">
                <a href="<?php echo esc_url( home_url( '/login/' ) ); ?>"><?php echo esc_html( optix_get_option( 'join_login_link', 'Already have an account?' ) ); ?></a>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>
