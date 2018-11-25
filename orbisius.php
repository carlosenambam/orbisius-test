<?php 

/*
Plugin Name: Orbisius Test
Description: Orbisius Test by Carlos Alvarez
Version: 1.0
Author: Carlos Alvarez
Author URI: https://www.linkedin.com/in/carlos-alvarez-979872161/
Copyright: Orbisius
*/

//Add Orbisius Form as a submenu
add_action('admin_menu', 'add_orbisius_test');

function add_orbisius_test() {
	add_submenu_page('options-general.php', 'Orbisius Form', 'Orbisius Test', 'read', 'orbisius-test', 'orbisius_form');
}

function orbisius_form() {

	?>

		<form id="test-form">
			<label for="full-name">Full Name</label>
			<input type="text" name="full-name" id="full-name" required><br>
			<button class="button button-primary button-large" id="submit">Send</button>
		</form>
		<p>Response here:</p>
		<div id="response-body" style="display: inline-block; min-width: 200px; min-height: 200px; border: 1px solid #000;">
			
		</div>

	<?php

}

//Enqueue Script for AJAX Calls

add_action( 'admin_enqueue_scripts', 'orbisius_scripts', 1 );

function orbisius_scripts($hook) {

	if($hook !== 'settings_page_orbisius-test') {
		return;
	}

    wp_enqueue_script( 'orbisius-test', plugin_dir_url(__FILE__).'ajax.js', array('jquery') );
}




//AJAX Calls
add_action( 'wp_ajax_orbisius_test', 'ajax_orbisius_test' );

function ajax_orbisius_test() {
	$data = wp_remote_post('https://orbisius.com/apps/qs_cmd/?json', array( 'headers' => array('full-name' => $_GET['fullName']) ));
	echo print_r($data['body'], true);
	exit();
}