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
	$result['preview_count'] = intval($input['preview_count']);

	$result['publisher_token'] = $input['publisher_token'];
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
	$pregString = '/^[a-z0-9A-Z\s<>\()!?._-]{0,140}$/i';
	/*CSS classes */
	$prevalidate = (string)trim($input['widget_wrapper_prepurchase']);
	if(preg_match($pregString, $prevalidate)) {
		$result['widget_wrapper_prepurchase'] = $prevalidate;
	}

	$prevalidate = (string)trim($input['widget_wrapper_postpurchase']);
	if(preg_match($pregString, $prevalidate)) {
		$result['widget_wrapper_postpurchase'] = $prevalidate;
	}

	/*TITLES */
	$prevalidate = (string)trim($input['widget_title']);
	if(preg_match($pregString, $prevalidate)) {
		$result['widget_title'] =  $prevalidate;
	}

	$prevalidate = (string)trim($input['widget_subtitle']);
	if(preg_match($pregString, $prevalidate)) {
		$result['widget_subtitle'] = $prevalidate;
	}

	$prevalidate = (string)trim($input['widget_post_purchase_title']);
	if(preg_match($pregString, $prevalidate)) {
		$result['widget_post_purchase_title'] = $prevalidate;
	}

	$prevalidate = (string)trim($input['widget_post_purchase_subtitle']);
	if(preg_match($pregString, $prevalidate)) {
		$result['widget_post_purchase_subtitle'] = $prevalidate;
	}

	return $result;
}
