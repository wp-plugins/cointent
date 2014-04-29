<?php


require_once COINTENT_DIR. '/admin/cointent-general-settings.php';
require_once COINTENT_DIR . '/admin/ajax.php';

add_action( "admin_menu", 'cointent_add_admin_pages' );

function cointent_add_admin_pages() {
	add_menu_page( 'CoinTent', 'CoinTent', 'manage_options', 'cointent.php', 'cointent_general_settings', plugins_url('/images/admin_icon.png', BASE_DIR) );
	//add_options_page( 'Cointent', 'Cointent', 'manage_options',  'cointent_options_settings' );
	cointent_register_settings();

	$options = get_option( 'Cointent' );
	global $wp_version;

	if ( version_compare( $wp_version, '3.3', '>=' ) && !isset( $options['tracking_popup'] ) ) {
		require_once COINTENT_DIR . '/admin/cointent-pointers.php';
	}
}
function cointent_register_settings() {
	register_setting( 'cointent-settings-group', 'Cointent', 'cointent_validate_settings' );
	wp_register_style('cointent-wp-plugin-admin', plugins_url('style.css', BASE_DIR) );
	wp_enqueue_style('cointent-wp-plugin-admin');

}

function cointent_validate_settings($input) {
	$result = get_option('Cointent');
	$result['publisher_id'] = intval($input['publisher_id']);
	$result['environment'] = $input['environment'];
	$result['cointent_tracking'] = (bool)$input['cointent_tracking'];
	$result['view_type'] = $input['view_type'];
	
	if (isset($input['include_categories'])) {
		$result['include_categories'] = $input['include_categories'];
	} else {
		$result['include_categories'] = array();
	}

	if (isset($input['exclude_categories'])) {
		$result['exclude_categories'] = $input['exclude_categories'];
	} else {
		$result['exclude_categories'] = array();
	}

	/*CSS classes */
	$result['widget_wrapper_prepurchase'] = (string)trim($input['widget_wrapper_prepurchase']);
	if(!preg_match('/^[a-z0-9A-Z]{0,32}$/i', $result['widget_wrapper_prepurchase'])) {
		$result['widget_wrapper_prepurchase'] = '';
	}

	$result['widget_wrapper_postpurchase'] = (string)trim($input['widget_wrapper_postpurchase']);
	if(!preg_match('/^[a-z0-9A-Z]{0,32}$/i', $result['widget_wrapper_postpurchase'])) {
		$result['widget_wrapper_postpurchase'] = '';
	}
	/*TITLES */

	$result['widget_title'] = (string)trim($input['widget_title']);
	if(!preg_match('/^[a-z0-9A-Z]{0,140}$/i', $result['widget_title'])) {
		$result['widget_title'] = '';
	}

	$result['widget_subtitle'] = (string)trim($input['widget_subtitle']);
	if(!preg_match('/^[a-z0-9A-Z]{0,140}$/i', $result['widget_subtitle'])) {
		$result['widget_subtitle'] = '';
	}

	$result['widget_post_purchase_title'] = (string)trim($input['widget_post_purchase_title']);
	if(!preg_match('/^[a-z0-9A-Z]{0,140}$/i', $result['widget_post_purchase_title'])) {
		$result['widget_post_purchase_title'] = '';
	}

	$result['widget_post_purchase_subtitle'] = (string)trim($input['widget_post_purchase_subtitle']);
	if(!preg_match('/^[a-z0-9A-Z]{0,140}$/i', $result['widget_post_purchase_subtitle'])) {
		$result['widget_post_purchase_subtitle'] = '';
	}


	return $result;
}
