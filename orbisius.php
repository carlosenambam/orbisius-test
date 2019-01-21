<?php
/**
 * Plugin Name: Orbisius Test
 * Description: Orbisius Test by Carlos Alvarez
 * Version: 1.0
 * Author: Carlos Alvarez
 * Author URI: https://www.linkedin.com/in/carlos-alvarez-979872161/
 * Copyright: Orbisius
 *
 * @package Orbisius
 */

if ( ! defined( 'ABSPATH' ) ) { // This file cannot be accessed directly.
	exit;
}

define( 'ORBISIUS_PATH', plugin_dir_path( __FILE__ ) );
define( 'ORBISIUS_URL', plugin_dir_url( __FILE__ ) );
define( 'ORBISIUS_VERSION', '1.0.0' );

/**
 * Plugin main class
 */
class OrbisiusTest {

	/**
	 * Add the hook functions
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_setting_submenu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'orbisius_scripts' ), 1 );
		add_action( 'wp_ajax_orbisius_test', array( $this, 'ajax_orbisius_test' ) );
	}

	/**
	 * Add the admin menu
	 */
	public function add_setting_submenu() {
		add_submenu_page( 'options-general.php', 'Orbisius Form', 'Orbisius Test', 'read', 'orbisius-test', array( $this, 'orbisius_form' ) );
	}

	/**
	 * Show the form
	 */
	public function orbisius_form() {
		include ORBISIUS_PATH . 'templates/form.php';
	}

	/**
	 * Enqueue the JS scripts
	 *
	 * @param string $hook The $hook_suffix for the current admin page.
	 */
	public function orbisius_scripts( $hook ) {
		if ( 'settings_page_orbisius-test' !== $hook ) {
			return;
		}

		wp_enqueue_script( 'orbisius-test', ORBISIUS_URL . 'js/ajax.js', array( 'jquery' ), ORBISIUS_VERSION, true );

		wp_localize_script(
			'orbisius-test',
			'orbisius',
			array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'nonce'    => wp_create_nonce( 'orbisius_test' ),
			)
		);
	}

	/**
	 * Ajax handler for the orbisius form
	 *
	 * @throws Exception When $_GET['fullName'] is not defined or it's empty.
	 */
	public function ajax_orbisius_test() {
		check_admin_referer( 'orbisius_test', 'nonce' );

		if ( ! isset( $_GET['fullName'] ) ) {
			throw new Exception( 'fullName is not defined', 1 );
		}

		$full_name = sanitize_text_field( wp_unslash( $_GET['fullName'] ) );

		if ( ! $full_name ) {
			throw new Exception( 'fullName must not be empty', 1 );
		}

		$body = array(
			'full-name' => $full_name,
		);

		$headers = array(
			'Content-Type' => 'text/html',
		);

		$data = wp_remote_get(
			'https://orbisius.com/apps/qs_cmd/?json',
			array(
				'body'    => $body,
				'headers' => $headers,
			)
		);
		wp_send_json( $data['body'] );
	}

}

new OrbisiusTest();
