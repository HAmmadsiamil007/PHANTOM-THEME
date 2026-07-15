<?php
declare(strict_types=1);

namespace OptixCore;

defined( 'ABSPATH' ) || exit;

class Ajax_Handlers {

	private static ?self $instance = null;

	private function __construct() {}
	private function __clone() {}
	public function __wakeup(): void {
		throw new \RuntimeException( 'Cannot unserialize singleton' );
	}

	public static function get_instance(): self {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function init(): void {
		add_action( 'wp_ajax_optix_send_contact', [ $this, 'handle_contact_form' ] );
		add_action( 'wp_ajax_nopriv_optix_send_contact', [ $this, 'handle_contact_form' ] );
		add_action( 'wp_ajax_optix_newsletter_subscribe', [ $this, 'handle_newsletter_subscribe' ] );
		add_action( 'wp_ajax_nopriv_optix_newsletter_subscribe', [ $this, 'handle_newsletter_subscribe' ] );
		add_action( 'wp_ajax_optix_restore_defaults', [ $this, 'handle_restore_defaults' ] );
	}

	public function handle_contact_form(): void {
		check_ajax_referer( 'optix_contact_nonce', 'optix_contact_nonce' );

		$fname = isset( $_POST['fname'] ) ? sanitize_text_field( wp_unslash( $_POST['fname'] ) ) : '';
		$email = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
		$phone = isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '';
		$msg   = isset( $_POST['msg'] ) ? sanitize_textarea_field( wp_unslash( $_POST['msg'] ) ) : '';

		if ( empty( $fname ) || empty( $email ) || empty( $msg ) ) {
			wp_send_json( [
				'status' => 'Error',
				'msg'    => __( 'Please fill in all required fields.', 'optix-core' ),
			] );
		}

		if ( ! is_email( $email ) ) {
			wp_send_json( [
				'status' => 'Error',
				'msg'    => __( 'Please enter a valid email address.', 'optix-core' ),
			] );
		}

		$to = get_option( 'admin_email' );
		if ( empty( $to ) || ! is_email( $to ) ) {
			$to = get_bloginfo( 'admin_email' );
		}
		$subject = sprintf( __( 'Contact form submission from %s', 'optix-core' ), $fname );
		$headers = [
			'Content-Type: text/plain; charset=UTF-8',
			'Reply-To: ' . $email,
		];
		$body    = "Name: $fname\nEmail: $email\nPhone: $phone\nMessage:\n$msg";

		$sent = wp_mail( $to, $subject, $body, $headers );

		if ( $sent ) {
			wp_send_json( [
				'status' => 'Success',
				'msg'    => __( 'Your message has been sent successfully!', 'optix-core' ),
			] );
		} else {
			wp_send_json( [
				'status' => 'Error',
				'msg'    => __( 'Failed to send message. Please try again later.', 'optix-core' ),
			] );
		}
	}

	public function handle_newsletter_subscribe(): void {
		check_ajax_referer( 'optix_newsletter_nonce', 'optix_newsletter_nonce' );

		$ip  = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
		$key = ! empty( $ip ) ? 'optix_nl_throttle_' . wp_hash( $ip ) : '';
		if ( ! empty( $key ) && get_transient( $key ) ) {
			wp_send_json( [
				'status' => 'Error',
				'msg'    => __( 'Please wait before subscribing again.', 'optix-core' ),
			] );
		}

		$email = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';

		if ( ! is_email( $email ) ) {
			wp_send_json( [
				'status' => 'Error',
				'msg'    => __( 'Please enter a valid email address.', 'optix-core' ),
			] );
		}

		$subs = (array) get_option( 'optix_newsletter_subscribers', [] );
		if ( in_array( $email, $subs, true ) ) {
			wp_send_json( [
				'status' => 'Success',
				'msg'    => __( 'You are already subscribed!', 'optix-core' ),
			] );
		}

		if ( count( $subs ) >= 1000 ) {
			wp_send_json( [
				'status' => 'Error',
				'msg'    => __( 'Subscription list is full. Please try again later.', 'optix-core' ),
			] );
		}

		$subs[] = $email;
		$saved  = update_option( 'optix_newsletter_subscribers', $subs );

		if ( $saved ) {
			if ( ! empty( $key ) ) {
				set_transient( $key, 1, 30 );
			}
			wp_send_json( [
				'status' => 'Success',
				'msg'    => __( 'Thank you for subscribing!', 'optix-core' ),
			] );
		} else {
			wp_send_json( [
				'status' => 'Error',
				'msg'    => __( 'Something went wrong. Please try again.', 'optix-core' ),
			] );
		}
	}

	public function handle_restore_defaults(): void {
		check_ajax_referer( 'optix_restore_nonce', 'optix_restore_nonce' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json( [ 'status' => 'Error', 'msg' => __( 'Permission denied.', 'optix-core' ) ] );
		}

		$registry = \OptixCore\Registry\Settings_Registry::get_instance();
		$registry->register();
		$count = 0;

		foreach ( $registry->get_all() as $key => $entry ) {
			$default = $entry['default'] ?? null;
			if ( null !== $default ) {
				$registry->set( $key, $default );
				$count++;
			}
		}

		wp_send_json( [
			'status' => 'Success',
			'msg'    => sprintf( __( '%d options restored to defaults.', 'optix-core' ), $count ),
		] );
	}
}
