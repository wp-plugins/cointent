<?php
/**
 * Plugin Name: CoinTent
 * Plugin URI: http://cointent.com
 * Description: CoinTent let’s you sell individual pieces of content for small amounts ($0.05-$1.00).  You choose what content to sell and how to sell it. We handle the rest.
 * Version: 1.2.0
 * Author: CoinTent, Inc.
 * License: GPL2
 */

define("COINTENT_DIR", plugin_dir_path( __FILE__ ));
define("BASE_DIR",  __FILE__ );

define("COINTENT_PRODUCTION", 'connect.cointent.com');
define("COINTENT_SANDBOX", 'connect.sandbox.cointent.com');
define("API_BASE_URL", 'api.cointent.com');
define("SANDBOX_API_BASE_URL", 'api.sandbox.cointent.com');

// Flag to show/hide the failure message for all shortcodes
define("SHOW_FAILURE_MESSAGE", false);



if(!class_exists('cointent_class'))
{
	class cointent_class {
		const WORDS_PER_MINUTE = 200;
		public function __construct() {
			// Setup admin for cointent
			if (is_admin()) {
				require_once COINTENT_DIR . '/admin/cointent-admin.php';
			} else {
				add_shortcode('cointent_lockedcontent', array(&$this, "cointent_widgetHandler"));
				add_shortcode('cointent_extras', array(&$this, "cointent_extrasHandler"));
				// Handles loading the css for the widget
				add_action('wp_enqueue_scripts', array(&$this, 'cointent_register_plugin_styles'));
				add_filter('the_content', array(&$this, 'cointent_content_filter'), 20);
				add_filter('the_excerpt', array(&$this,'cointent_content_remove_filter'),20 );
				$options = get_option("Cointent");

				if (isset($options['cointent_tracking']) && $options['cointent_tracking']) {
					//	cointent_home_stats();
					add_action('init', array(&$this, "cointent_home_stats"), 5);
					add_filter('the_content', array(&$this, 'cointent_post_stats'), 5);
				}
			}
		}

		/**
		 * Activates the Cointent plugin and does any necessary upgrades and check compatibility
		 */
		public static function cointent_activate() {
			// DO upgrades/migrations if necessary

			$default_options = array(
				'publisher_id' => 0,
				'preview_count' => 55,
				'environment' => 'production',
				'cointent_tracking' => false,
				'include_categories'=> array(),
				'exclude_categories'=> array(),
				'widget_wrapper_prepurchase' => '',
				'widget_wrapper_postpurchase' => '',
				'widget_title' => 'Please purchase to continue reading this premium content',
				'widget_subtitle' => '',
				'widget_post_purchase_title' => 'Thanks for reading!',
				'widget_post_purchase_subtitle' => '',
				'view_type' => 'condensed'
			);
			$options = get_option( 'Cointent', $default_options );
			update_option('Cointent', $options);
		}

		public static function cointent_uninstall() {
			// Remove info from DB on deactivate?
			delete_option( 'Cointent' );
		}
		public static function cointent_deactivate() {
			// Remove info from DB on deactivate?
			// THings to
		}

		/**
		 * Handle hiding some text based on whether the cointent_contentlocker is active
		 * @param  [array] $atts     Any passed information from the shortcode, nothing anticipated
		 * @param  [string] $content Any content from within the cointent_extras shortcode
		 * @return [string] 		 Filtered content
		 */
		function cointent_extrasHandler($atts, $content=null) {
			// Emergency shut off
			// Hides any text marked within cointent_extras
			if (SHOW_FAILURE_MESSAGE) {
				return;
			}

			// If user has access either by purchase or because the article is not gated by cointent
			// Do not show the extra text
			$hasCTaccess = !$this->cointent_is_content_gated();
			if ($hasCTaccess) {
				return;
			}

			// Return the content wrapped in the cointent_extras
			return do_shortcode($content);
		}

		/**
		 * Handle showing the widget at the bottom of the content
		 * @param  [array] $atts     Any passed information from the shortcode, nothing anticipated
		 * @param  [string] $content Any content from within the cointent_extras shortcode
		 * @return [string] 		 Filtered content
		 */
		function cointent_widgetHandler($atts, $content=null) {

			// Emergency shut off
			// Displays a failure message instead of the widget
			if (SHOW_FAILURE_MESSAGE) {
				$message = '<div id="plugin_error_message"><p>CoinTent is currently under maintenance, but will be back up shortly.
				  </br>If you have any questions or concerns please email support@cointent.com</p></div>';

				return do_shortcode($message);
			}

			// Content we want to display by expanding
			$hidden_content = '';

			// If a user is running noscript or turned off javascript display a message that they can't do it
			$no_script = $this->cointent_getNoScriptNotice();

			// If you have been passed authentication information check to see if the user has
			// purchased the content, if you don't just check to see if the content is gated by cointent or not
			if(!isset($_GET['email']) || !isset($_GET['token']) || !isset($_GET['time'])) {
				$isGated = $this->cointent_is_content_gated();
				// If not gated, don't change the content and return
				if (!$isGated) {
					return;
				}
				$hasCTaccess = !$isGated;
			} else {
				$hasCTaccess = $this->cointent_has_access($_GET['email'], $_GET['token'], $_GET['time']);
			}

			// If the user has access or the script has already been loaded, don't load the script again
			if ( $hasCTaccess  && $content) {
				// if there is content in the short code, hide it behind gating.
				$hidden_content ='<div id="cointent_gated">'.$content.'</div>';
			}

			// Added class to wrap widget in, (initial case to apply publisher's css to widget and clear a float)
			$widget_script = $this->cointent_buildWidget($hasCTaccess, $atts);


			$content = $hidden_content .$no_script . $widget_script;
			return do_shortcode(wpautop($content));
		}
		/**
		 * Returns the no script notice
		 * @return [type] [description]
		 */
		function cointent_getNoScriptNotice() {
			return '<noscript>
				<style type="text/css">
					.cointent-widget {display:none;}
					.noscript_view { border: 1px solid #ccc; padding: 10px; background-color: rgb(255, 248, 208);}
				</style>
				<div class="noscript_view">
				This article is paid content! To access, you can pay '.get_bloginfo('name').' with CoinTent. To use CoinTent, you must enable javascript.</div>
			</noscript>';
		}

		/**
		 * Handling building out the widget based on whether the content is gated/ungated locked/unlocked
		 * @return [type] [description]
		 */
		function cointent_buildWidget($hasCTaccess, $atts) {
			// Current post Id - maps to cointent article id
			global $post;
			$time = 0;
			if ($post->post_content)	{
				//defined exerpt
				$content = $post->post_content;
				$time = $this->cointent_getTimeToRead($content);
			}


			$widget_script = '';

			// Setup environment to point the plugin to
			$options =  get_option('Cointent');
			$post_id = get_the_ID();
			$environment =  $options['environment'];
			$base_url = COINTENT_PRODUCTION;

			if ($environment == 'sandbox') {
				$base_url = COINTENT_SANDBOX;
			}


			// Pull information from admin panel
			// TODO: handle it not existing - required information
			$publisher_id = $options['publisher_id'];

			// Get all of the information from the shortcode
			//   article title 					-> Title of the article, if doesn't exist use post title
			//   title  						-> Title to display on the widget BEFORE purchase
			//   subtitle 						-> Message below the title to display on the widget BEFORE purchase
			//   post_purchase_title  			-> Title to display on the widget AFTER purchase
			//   post_purchase_subtitle 		-> Message below the title to display on the widget AFTER purchase
			//   view_type 						-> full - full widget all options
			//   								   condensed - just button and login status
			//   image_url 						-> Image to display on the widget

			extract( shortcode_atts( array(
				'media_type' => 'text',
				'article_title' => '',
				'title' => '',
				'subtitle' => '',
				'post_purchase_title' => '',
				'post_purchase_subtitle' => '',
				'view_type' => '',
				'video_id' => '',
				'image_url' => '',
				'video_src' => '',
				'video_type' => '',
				'video_width' => '640',
				'video_height' => '360',
				'video_poster' => 'https://kconnect.dev.cointent.com/images/default_poster.png'

			), $atts, 'cointent_lockedcontent' ));

			// If we don't have an article title use the one from the post
			if (!$article_title) {
				$article_title = get_the_title($post_id);
			}

			$wrapperClass = $hasCTaccess ? $options['widget_wrapper_postpurchase'] : $options['widget_wrapper_prepurchase'];

			$title = $title ? $title : $options['widget_title'];
			$subtitle = $subtitle ? $subtitle : $options['widget_subtitle'];
			$post_purchase_title = $post_purchase_title ? $post_purchase_title : $options['widget_post_purchase_title'];
			$post_purchase_subtitle = $post_purchase_subtitle ? $post_purchase_subtitle : $options['widget_post_purchase_subtitle'];

			if ($media_type == '' || $media_type == 'text') {
				//	$post_id = get_the_ID(); Set above
				$cssClazz = 'widget';
			} else {
				$cssClazz = 'video';
				$post_id = $video_id;
			}


			$view_type = $view_type ? $view_type : $options['view_type'];

			if ($wrapperClass) {
				$widget_script = '<div class="'.$wrapperClass.'">';
			}

			// Data fields to aid in the creation of the widget
			$dataFields = 'data-publisher-id="'.$publisher_id.'" data-article-id="'.$post_id.'"'.
				'data-article-title="'.$article_title.'"'.
				'data-title="'.$title.'"'.
				'data-time="'.$time.'"'.
				'data-url="'.get_permalink($post_id).'"'.
				'data-subtitle="'.$subtitle.'"'.
				'data-post-purchase-subtitle="'.$post_purchase_subtitle.'"'.
				'data-post-purchase-title="'.$post_purchase_title.'"'.
				'data-view-type="'.$view_type.'"'.
				'data-src="'.$image_url.'"';

			if ($media_type == 'video') {
				$dataFields .= 'data-video-src="'.$video_src.'"'.
					'data-video-type="'.$video_type.'"'.
					'data-video-width="'.$video_width.'"'.
					'data-video-height="'.$video_height.'"'.
					'data-video-poster="'.$video_poster.'"'
				;
			}
			// If the user has access or the script has already been loaded, don't load the script again
			$widget_script .= '<div class="cointent-'.$cssClazz.'" '.$dataFields.'></div>';
			if (!$hasCTaccess && !isset($_GET['loadScript'])) {
				wp_enqueue_script('main-cointent-js');
				$tracking_active = $options['cointent_tracking'];
				if ($tracking_active) {
					$data = array('publisherId'=>$publisher_id, 'gated'=>true);

					if(!is_front_page()) {
						$data['articleId'] = $post_id;
					}
					wp_localize_script('main-cointent-js','cointent_tracking_data', $data);
					$isEnqueued = wp_script_is( 'tracking-cointent-js', 'enqueued' );
					if ($isEnqueued) {
						add_action('wp_print_scripts','dequeueTracking');

						//wp_dequeue_script( 'tracking-cointent-js' );
					}

				}
			}

			// Close the wrapper
			if ($wrapperClass) {
				$widget_script .= '</div>';
			}


			return $widget_script;
		}

		function dequeueTracking () {
			wp_dequeue_script( 'tracking-cointent-js' );
			wp_deregister_script( 'tracking-cointent-js' );
		}

		function cointent_getTimeToRead($content) {
			$wordsPerMin = str_word_count($content) / cointent_class::WORDS_PER_MINUTE;
			$wordsPerMin = round(max(1, $wordsPerMin));
			return $wordsPerMin;
		}


		function cointent_has_access($email = '', $token = '', $timestamp = 0) {
			global $post;

			// If we don't gate they have access
			if (!$this->cointent_is_content_gated()) {
				return true;
			}

			$params = array(
				"email" => $email,
				"token" => $token,
				"time" => $timestamp
			);
			// Retrieve publisher id
			$options = get_option('Cointent');

			$environment =  $options['environment'];
			$publisher_id =  $options['publisher_id'];


			$base_url = API_BASE_URL;
			if ($environment == 'sandbox') {
				$base_url = SANDBOX_API_BASE_URL;
			}
			// Setup call to get gating information
			$url  = 'https://'.$base_url."/gating/publisher/".$publisher_id."/article/".$post->ID;

			$postResult = $this->cointent_callAPI('GET', $url, $params);

			if ($postResult ) {
				$result = json_decode($postResult);
				return $result->gating->access;
			}
			return false;
		}

		/*
		 * Make an api curl call to another server
		 */
		function cointent_callAPI($method, $url, $data = false)
		{
			$curl = curl_init();

			switch ($method)
			{
				case "POST":
					curl_setopt($curl, CURLOPT_POST, 1);

					if ($data)
						curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
					break;
				case "PUT":
					curl_setopt($curl, CURLOPT_PUT, 1);
					break;
				default:
					if ($data) {
						$url = sprintf("%s?%s", $url, http_build_query($data));
					}
			}

			// Optional Authentication:
			curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

			return curl_exec($curl);
		}
		/**
		 * Determine whether post is gated based on include and exclude category settings
		 * Excluded categories are evalutaed first and override included categories
		 * Ex. "Cat E" is set to be excluded and "Cat I" is set to be included
		 *     a post with both "Cat E" and "Cat I" categories will NOT be gated
		 *
		 * @return [boolean] True if the content is gate, False if it is not
		 */
		function cointent_is_content_gated () {
			global $post;
			$mypost = $post;
			$is_gated = false;
			$options = get_option('Cointent');
			// Category of posts that will be gated
			$activeCategories = $options['include_categories'];
			// Category of posts that will not be gated (priority)
			$inactiveCategories = $options['exclude_categories'];

			// If they aren't set make sure we at least have an array to go through
			$activeCategories = $activeCategories ? $activeCategories : array();
			$inactiveCategories = $inactiveCategories ? $inactiveCategories : array();
			if (has_shortcode($post->post_content, 'cointent_lockedcontent')) {
				return true;
			}
			//for these post types, we want to check the parent
			if ($mypost->post_type == "attachment" || $mypost->post_type == "revision") {
				$mypost = get_post($mypost->post_parent);
			}
			// Only gate posts
			if ($mypost->post_type == "post") {
				$post_categories = wp_get_post_categories($mypost->ID);
				// Search through all the EXCLUDE categories
				// if the post matches any, return "not gated"
				foreach($post_categories as $cat) {
					if (array_key_exists($cat, $inactiveCategories)) {
						return false;
					}
				}
				// Search through all the include categories
				// if the post matches any, return "gated"
				foreach($post_categories as $cat) {
					if (array_key_exists($cat, $activeCategories)) {
						$is_gated = true;
					}
				}
			}

			return $is_gated;
		}

		function cointent_content_remove_filter($content) {
			remove_shortcode( 'cointent_lockedcontent' );
			return $content;
		}

		/**
		 * Filters the content for our shortcode, locks if necessary
		 * @param  [type] $content [description]
		 * @return [type]          [description]
		 */
		function cointent_content_filter($content)
		{
			global $post;
			$hasaccess = false;
			$isGated = true;
			if(!isset($_GET['email']) || !isset($_GET['token']) || !isset($_GET['time'])) {
				// Not enough info to do check
				$isGated = $this->cointent_is_content_gated();
				if (!$isGated) {
					remove_shortcode( 'cointent_lockedcontent' );
				}

			} else {
				$hasaccess = $this->cointent_has_access($_GET['email'], $_GET['token'], $_GET['time']);
			}

			$options = get_option('Cointent');

			$view_type = $options['view_type'];
			$subtitle = $options['widget_subtitle'];
			$title = $options['widget_title'];
			$widget_post_purchase_subtitle = $options['widget_post_purchase_subtitle'];
			$widget_post_purchase_title = $options['widget_post_purchase_title'];


			/********* START TP ONLY SECTION ************/
			/*
			* //if they don't have access through cointent or its not gated by us,
			* //leave as is, either another plugin locked it or allowed it
			* if (!$hasaccess || !$isGated) { // USE THIS LINE IF SOMETHING ELSE IS GATING CONTENT ( LIKE PMP )
			*	return do_shortcode($content);
			* }
			*/
			/*********** End TP ONLY SECTION *************/

			/********* THIS SECTION WILL NOT WORK WITH TECHPINION, IT DOES THE LOCKING TP depends on another plugin to do locking *********/
			if ($hasaccess) {

				//$content = $post->post_content;

				$pos = strpos( $content, 'cointent_lockedcontent') ;
				if ($pos <= 0) {

					$content .= '[cointent_lockedcontent view_type="'.$view_type.'" title="'.$title.'" subtitle="'.$subtitle.'"'
						.' post_purchase_title="'.$widget_post_purchase_title.'"'
						.' post_purchase_subtitle="'.$widget_post_purchase_subtitle.'"]'
						.'[/cointent_lockedcontent]';
				}

				return do_shortcode($content);
			}
			else if (!$isGated) {
				return do_shortcode($content);
			}
			/********* END SECTION*********/
			else {
				if ($post->post_content)	{
					//defined exerpt

					$content = $post->post_content;

					$pos = strpos( $content, 'cointent_lockedcontent') ;

					if ($pos <= 0){
						// Make short preview - pulled form wp_trim_excerpt
						// IF THE MORE TAG EXISTS use that as breaking
						$morestring = '<!--more-->';
						$explode_content = explode( $morestring, $post->post_content );
						if (isset($explode_content[0]) && isset($explode_content[1])) {
							$content = $explode_content[0];
						}
						// ELSE use default word count
						else {
							$wordAndPosition = str_word_count($content, 2);
							$arraySlice = array_slice($wordAndPosition, $options['preview_count'], 1,true);
							reset($arraySlice);
							$indexToSplit = key($arraySlice);
							$content = substr($content, 0,$indexToSplit )."...";


						}

						$content = wpautop($content);
						$content .= '[cointent_lockedcontent view_type="'.$view_type.'" title="'.$title.'" subtitle="'.$subtitle.'"'
							.' post_purchase_title="'.$widget_post_purchase_title.'"'
							.' post_purchase_subtitle="'.$widget_post_purchase_subtitle.'"]'
							.'[/cointent_lockedcontent]';
					}
				}
			}

			add_shortcode('cointent_lockedcontent', array(&$this, "cointent_widgetHandler"));
			return do_shortcode($content);
		}

		function cointent_register_plugin_styles() {
			$options = get_option('Cointent');
			$environment =  $options['environment'];
			$base_url = COINTENT_PRODUCTION;
			if ($environment == 'sandbox') {
				$base_url = COINTENT_SANDBOX;
			}

			wp_register_script('main-cointent-js', '//'.$base_url.'/cointent.0.2.js');
			wp_register_script('tracking-cointent-js', '//'.$base_url.'/cointent-tracker.0.2.js');
			$tracking_active = $options['cointent_tracking'];

			if(!$tracking_active) {
				wp_localize_script('main-cointent-js','cointent_tracking_data', array('tracking_inactive'=>true));
			}


			wp_register_style('cointent-wp-plugin', '//'.$base_url.'/style.css' );

			wp_enqueue_style('cointent-wp-plugin');

		}
		/**
		 *	Add stat tracking to head but don't pass article/pub id bc you are just tracking home
		 */
		function cointent_home_stats( ) {
			if (is_front_page()) {
				$options = get_option('Cointent');
				$environment =  $options['environment'];
				$publisher_id = $options['publisher_id'];

				$base_url = COINTENT_PRODUCTION;
				if ($environment == 'sandbox') {
					$base_url = COINTENT_SANDBOX;
				}

				$isEnqueued = wp_script_is( 'main-cointent-js', 'enqueued' );
				if (!$isEnqueued) {
					wp_register_script( 'tracking-cointent-js', '//'.$base_url.'/cointent-tracker.0.2.js');
					wp_enqueue_script('tracking-cointent-js');
				}

				wp_localize_script('tracking-cointent-js','cointent_tracking_data', array('publisherId'=>$publisher_id));
			}
		}
		/**
		 *	Add stat tracking to head & pass article/pub id bc you are tracking an article view
		 */
		function cointent_post_stats($content) {
			// Home has it's own tracking, don't do this
			if (is_front_page()) {
				return $content;
			}
			if ($GLOBALS['post']->post_type == "post") {
				$options = get_option('Cointent');
				$environment =  $options['environment'];

				$base_url = COINTENT_PRODUCTION;
				if ($environment == 'sandbox') {
					$base_url = COINTENT_SANDBOX;
				}
				$isEnqueued = wp_script_is( 'main-cointent-js', 'enqueued' );

				$isGated = $this->cointent_is_content_gated();
				if(!$isGated && !$isEnqueued) {
					wp_register_script( 'tracking-cointent-js', '//'.$base_url.'/cointent-tracker.0.2.js');
					wp_enqueue_script('tracking-cointent-js');
				}

				$data = array('articleId'=>$GLOBALS['post']->ID, 'publisherId'=>$options['publisher_id'], 'gated'=>$isGated);
				wp_localize_script('tracking-cointent-js','cointent_tracking_data', $data);

			}
			return $content;
		}

	}
}

if (class_exists('cointent_class'))
{
	// Installation and uninstallation hooks
	register_activation_hook(__FILE__, array('cointent_class', 'cointent_activate'));
	register_deactivation_hook(__FILE__, array('cointent_class', 'cointent_deactivate'));
	register_uninstall_hook(__FILE__, array('cointent_class', 'cointent_uninstall'));
	// instantiate the plugin class
	$cointent = new cointent_class();

}
?>