<?php
/**
 * AJAX handlers for Optix Kids Collection theme.
 *
 * @package optix
 * @deprecated 1.0.0 Functionality migrated to OptixCore\Ajax_Handlers in optix-core plugin.
 */

namespace Optix;

_deprecated_file( __FILE__, '1.0.0', 'OptixCore\Ajax_Handlers' );

/**
 * Handle contact form submission via AJAX.
 */
function handle_contact_form() {
  check_ajax_referer( 'optix_contact_nonce', 'optix_contact_nonce' );

  $fname = isset( $_POST['fname'] ) ? sanitize_text_field( wp_unslash( $_POST['fname'] ) ) : '';
  $email = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
  $phone = isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '';
  $msg   = isset( $_POST['msg'] ) ? sanitize_textarea_field( wp_unslash( $_POST['msg'] ) ) : '';

  if ( empty( $fname ) || empty( $email ) || empty( $msg ) ) {
    wp_send_json( [
      'status' => 'Error',
      'msg'    => __( 'Please fill in all required fields.', 'optix' ),
    ] );
  }

  $to      = get_option( 'admin_email' );
  $subject = sprintf( __( 'Contact form submission from %s', 'optix' ), $fname );
  $headers = [
    'Content-Type: text/plain; charset=UTF-8',
    'Reply-To: ' . $email,
  ];
  $body    = "Name: $fname\nEmail: $email\nPhone: $phone\nMessage:\n$msg";

  $sent = wp_mail( $to, $subject, $body, $headers );

  if ( $sent ) {
    wp_send_json( [
      'status' => 'Success',
      'msg'    => __( 'Your message has been sent successfully!', 'optix' ),
    ] );
  } else {
    wp_send_json( [
      'status' => 'Error',
      'msg'    => __( 'Failed to send message. Please try again later.', 'optix' ),
    ] );
  }
}
add_action( 'wp_ajax_optix_send_contact', __NAMESPACE__ . '\handle_contact_form' );
add_action( 'wp_ajax_nopriv_optix_send_contact', __NAMESPACE__ . '\handle_contact_form' );

/**
 * Handle newsletter subscription via AJAX.
 */
function handle_newsletter_subscribe() {
  check_ajax_referer( 'optix_newsletter_nonce', 'optix_newsletter_nonce' );

  $ip = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
  $throttle = 'optix_nl_throttle_' . wp_hash( $ip );
  if ( get_transient( $throttle ) ) {
    wp_send_json( [
      'status' => 'Error',
      'msg'    => __( 'Please wait before subscribing again.', 'optix' ),
    ] );
  }

  $email = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';

  if ( ! is_email( $email ) ) {
    wp_send_json( [
      'status' => 'Error',
      'msg'    => __( 'Please enter a valid email address.', 'optix' ),
    ] );
  }

  $subs = (array) get_option( 'optix_newsletter_subscribers', [] );
  if ( in_array( $email, $subs, true ) ) {
    wp_send_json( [
      'status' => 'Success',
      'msg'    => __( 'You are already subscribed!', 'optix' ),
    ] );
  }

  if ( count( $subs ) >= 1000 ) {
    wp_send_json( [
      'status' => 'Error',
      'msg'    => __( 'Subscription list is full. Please try again later.', 'optix' ),
    ] );
  }

  $subs[] = $email;
  $saved  = update_option( 'optix_newsletter_subscribers', $subs );

  if ( $saved ) {
    set_transient( $throttle, 1, 30 );
    wp_send_json( [
      'status' => 'Success',
      'msg'    => __( 'Thank you for subscribing!', 'optix' ),
    ] );
  } else {
    wp_send_json( [
      'status' => 'Error',
      'msg'    => __( 'Something went wrong. Please try again.', 'optix' ),
    ] );
  }
}
add_action( 'wp_ajax_optix_newsletter_subscribe', __NAMESPACE__ . '\handle_newsletter_subscribe' );
add_action( 'wp_ajax_nopriv_optix_newsletter_subscribe', __NAMESPACE__ . '\handle_newsletter_subscribe' );

/**
 * Restore all theme options to defaults.
 */
function handle_restore_defaults() {
  check_ajax_referer( 'optix_restore_nonce', 'optix_restore_nonce' );
  if ( ! current_user_can( 'manage_options' ) ) {
    wp_send_json( [ 'status' => 'Error', 'msg' => __( 'Permission denied.', 'optix' ) ] );
  }

  $defaults = get_defaults();
  $count    = 0;

  foreach ( $defaults as $key => $value ) {
    update_option( 'optix_' . $key, $value );
    $count++;
  }

  wp_send_json( [
    'status' => 'Success',
    'msg'    => sprintf( __( '%d options restored to defaults.', 'optix' ), $count ),
  ] );
}
add_action( 'wp_ajax_optix_restore_defaults', __NAMESPACE__ . '\handle_restore_defaults' );
