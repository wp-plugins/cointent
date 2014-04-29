<?php

/**
 * Saving and display of CoinTent settings on the admin page
 */

function cointent_general_settings() {
	$options = get_option('Cointent');

	if (!current_user_can('manage_options')) {
		wp_die(__('You do not have sufficient permissions to access this page.'));
	}

	?>
	<div id="poststuff" class="metabox-holder has-right-sidebar">
		<?php if (!empty($_POST['_Submit'])) : ?>
			<div id="message" class="updated fade"><p><strong><?php _e('Options saved.') ?></strong></p></div>
		<?php endif; ?>
		<img src="<?php echo plugins_url('/images/logo_and_name_265x51.png', BASE_DIR)?>"/> <div><p>Thank you for installing CoinTent! Please feel free to contact us at <a href="mailto:support@cointent.com">support@cointent.com</a></p> <p>For detailed instructions visit us at our <a href="https://cointent.com/docs/wordpress">website</a></p></div>
		<div class="">
			<form action="options.php" method="post" id="cointent-conf">
				 <?php settings_fields( 'cointent-settings-group' ); ?>
				 <?php do_settings_sections( 'cointent-settings-group' ); ?>
				<br>


				<div class="tp-section">

					<div class="body">
						<div class="postbox">
							<h3><?php _e('Publisher Id') ?> </h3>
							<div class="inside">
								<p>Signup to get a publisher ID <a target="_blank" href="https://cointent.com/p/signup">here.</a> </p>
								<input type="text" id="ct_publisher_id" name="Cointent[publisher_id]" value="<?php echo $options['publisher_id'];?>"/><label for="ct_publisher_id">&nbsp;<?php _e( 'Publisher ID' ); ?></label><br>

							</div>
						</div>
					</div>
				</div>


				<div class="tp-section">

					<div class="body">
						<div class="postbox">
							<h3><?php _e('Tracking') ?> </h3>
							<div class="inside">
								<p>Please turn on tracking it helps both of us.  We use keen.io and mixpanel to make sure we have  all the data you will ever need</p>
								<input type="radio" id="ct_active" name="Cointent[cointent_tracking]" value="1" <?php if (isset($options['cointent_tracking']) && $options['cointent_tracking'] == true ) { echo "checked"; }?>/><label for="ct_tracking_active">&nbsp;<?php _e( 'Active' ); ?></label><br>
								<input type="radio" id="ct_inactive" name="Cointent[cointent_tracking]" value="0" <?php if (!isset($options['cointent_tracking']) || $options['cointent_tracking']  == false ) { echo "checked"; }?>/><label for="ct_tracking_inactive">&nbsp;<?php _e( 'Inactive' ); ?></label>
								<input type="hidden" id="ct_tracking_popup" name="Cointent[tracking_popup]" value="<?php echo $options['tracking_popup'] ?>"/>

							</div>
						</div>
					</div>
				</div>


				<div class="tp-section">

					<div class="body">
						<div class="postbox">
							<h3><?php _e('Environment') ?> </h3>

							<div class="inside">
							<p>Sandbox - This is a testing environment, no real payments will be processed, only use this if you have a dev or staging blog that isn't open to the public</p>
							<p>Live - This is the setting you use to on your site when you are ready for real customers to pay real money!</p>
								<input type="radio" id="ct_sandbox" name="Cointent[environment]" disabled="disabled" value="sandbox" <?php if ($options['environment'] ==  "sandbox" ) { echo "checked"; }?>/><label for="ct_sandbox">&nbsp;<?php _e( 'Sandbox - for testing only' ); ?></label><br>
								<input type="radio" id="ct_production" name="Cointent[environment]" value="production" <?php if ($options['environment'] ==  "production" ) { echo "checked"; }?>/><label for="ct_production">&nbsp;<?php _e( 'Live - for live payments' ); ?></label>
							</div>
						</div>
					</div>
				</div>

				<div class="tp-section">
					<div class="body">
						<div class="postbox">
							<h3><?php _e('View Type') ?> </h3>

							<div class="inside">
								
								<input type="radio" id="ct_condensed" name="Cointent[view_type]" value="condensed" <?php if ($options['view_type'] ==  "condensed" ) { echo "checked"; }?>/><label for="ct_condensed">&nbsp;<?php _e( 'Condensed' ); ?></label><br>
								<input type="radio" id="ct_full" name="Cointent[view_type]" value="full" <?php if ($options['view_type'] ==  "full" ) { echo "checked"; }?>/><label for="ct_full">&nbsp;<?php _e( 'Full' ); ?></label>
								<p> Condensed </p>
								<img src="<?php echo plugins_url('/images/widget_condensed.png', BASE_DIR); ?>">
								<p> Full </p>
								<img src="<?php echo plugins_url('/images/widget_full.png', BASE_DIR); ?>">
							</div>
						</div>
					</div>
				</div>


				<div class="tp-section">
					<div class="body">
						<div class="postbox">
							<h3><?php _e('Titles for Inside Widget') ?> </h3>

							<div class="inside">
								<p>In the above picture the title is "Read the complete post for $0.10" and the subtitle is "To access this premium post..."</p>
								<p>You can have different messages to the user for before they buy and after than purchase. Messages are limited to 140 characters.</p>
								<label for="ct_widget_title">&nbsp;<?php _e( 'Title Text - Before buying' ); ?></label><input type="text" id="ct_widget_title" name="Cointent[widget_title]" size="80" value="<?php echo $options['widget_title'];?>"/><br>
								<label for="ct_widget_subtitle">&nbsp;<?php _e( 'Subtitle Text - Before buying' ); ?></label><input type="text" id="ct_widget_subtitle" name="Cointent[widget_subtitle]" size="80"  value="<?php echo $options['widget_subtitle'];?>"/><br>
							
								<label for="ct_widget_post_purchase_title">&nbsp;<?php _e( 'Title Text - After buying' ); ?></label><input type="text" id="ct_widget_post_purchase_title" name="Cointent[widget_post_purchase_title]" size="80" value="<?php echo $options['widget_post_purchase_title'];?>"/><br>
								<label for="ct_widget_post_purchase_subtitle">&nbsp;<?php _e( 'Subtitle Text - After buying' ); ?></label><input type="text" id="ct_widget_post_purchase_subtitle" name="Cointent[widget_post_purchase_subtitle]" size="80"  value="<?php echo $options['widget_post_purchase_subtitle'];?>"/><br>
							</div>
						</div>
					</div>
				</div>


				<div class="tp-section">

					<div class="body">
						<div class="postbox">
							<h3><?php _e('Locked Categories') ?> </h3>
							<div class="inside">
								<p>You can lock entire categories of posts, a short preview will automatically be shown to users and then the paywall </p>
								<p>Include categories are categories that you want to have a paywall, include is overriden by exclude categories.</p>
								<p>Ex. Post A is categorized by "TV" and "Premium".</p>
								<p></p>
								<table class="form-table">
									<tbody>
										<tr>
											<th scope="row" valign="top"><label><?php _e('Include Categories');?>:</label></th>
											<td>
												<?php
													$activeCat = $options['include_categories'];
													if(!$activeCat) {
														$activeCat = array();
													}
													$categories = get_categories( array( 'hide_empty' => 0 ) );
													echo "<ul>";
													foreach ( $categories as $cat ) {
														$checked = array_key_exists( $cat->term_id, $activeCat ) ? "checked='checked'" : '';
														echo "<li><input name='Cointent[include_categories][{$cat->term_id}]' type='checkbox' value='yes' $checked /> {$cat->name}</li>\n";
													}
													echo "</ul>";
												?>
											</td>
										</tr>
									</tbody>
								</table>
								<table class="form-table">
									<tbody>
										<tr>
											<th scope="row" valign="top"><label><?php _e('Exclude Categories');?>:</label></th>
											<td>
												<?php
													$activeCat = $options['exclude_categories'];
													if(!$activeCat) {
														$activeCat = array();
													}
													$categories = get_categories( array( 'hide_empty' => 0 ) );
													echo "<ul>";
													foreach ( $categories as $cat ) {
														$checked = array_key_exists( $cat->term_id, $activeCat ) ? "checked='checked'" : '';
														echo "<li><input name='Cointent[exclude_categories][{$cat->term_id}]' type='checkbox' value='yes' $checked /> {$cat->name}</li>\n";
													}
													echo "</ul>";
												?>
											</td>
										</tr>
									</tbody>
								</table>

							</div>
						</div>
					</div>
				</div>

				<div class="tp-section">

					<div class="body">
						<div class="postbox">
							<h3><?php _e('Example') ?> </h3>
							<div class="inside">
							<p>Code within your wordpress post</p>
								<pre>
	Put the stuff you want to use to intro the article here, make it enticing!

	[cointent_lockedcontent
		article_title="A Kodak Moment"
		image_url="http://cointent.com/images/bikes.png"
		title="Read the complete post for $0.10"
		subtitle="To access this premium post create and fund your CoinTent account (find out more on the About CoinTent page)."
		post_purchase_title="Thank you for reading!"
		post_purchase_subtitle="Thanks for reading this post and participating in the CoinTent alpha test."]

			Put the rest of your content here you will be off to the races! Lorem ipsum dolor sit amet,
			consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
			Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea
			commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum
			dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in
			culpa qui officia deserunt mollit anim id est laborum.

	[/cointent_lockedcontent]

								</pre>
								<p> How it will be dispalyed before buying: </p>
								<img src="<?php echo plugins_url('/images/beforePurchase.png', BASE_DIR); ?>">
								<p> How it will be dispalyed after buying: </p>
								<img src="<?php echo plugins_url('/images/afterPurchase.png', BASE_DIR);?>">
							</div>
						</div>
					</div>
				</div>



				<div class="tp-section">

					<div class="body">
						<div class="postbox">
							<h3><?php _e('Setup') ?> </h3>
							<div class="inside">
								<p>
									A Wordpress <a target="_blank" href="http://codex.wordpress.org/Shortcode">shortcode</a> should be used to wrap text you want behind a paywall
								</p>
								<p>
									The format for this shortcode is:
								</p>

								<pre>
	[cointent_lockedcontent] CONTENT HERE [/cointent_lockedcontent]
								</pre>
								<p>
									<bold>Optional Arguments:<bold>
									<dl>
										<dt>title</dt><dd>Header on the widget before the user buys your article</dd>
										<dt>subtitle</dt><dd>A message you would like to display to the user </dd>
										<dt> post_purchase_title </dt><dd> Header on the widget after the user buys your article </dd>
										<dt> post_purchase_subtitle </dt><dd> A message you would like to display to the user after they have purchased </dd>
										<dt> article_title </dt><dd> Title used for emails to the user, and for reference in CoinTent's system </dd>
										<dt> image_url </dt><dd> Image to be displayed on the widget </dd>
									</dl>

								</pre>


							</div>
						</div>
					</div>
				</div>


				<div class="tp-section">

					<div class="body">
						<div class="postbox">
							<h3><?php _e('CSS Class to wrap cointent widget In') ?> </h3>
							<div class="inside">
								<p>In case you want to apply your own css to it using a default class you have</p>
								<input type="text" id="ct_wrapper_prepurchase_id" name="Cointent[widget_wrapper_prepurchase]" size="60" value="<?php echo $options['widget_wrapper_prepurchase'];?>"/><label for="ct_widget_wr_prepurchase">&nbsp;<?php _e( 'Before Purchase' ); ?></label><br>
								<input type="text" id="ct_wrapper_postpurchase_id" name="Cointent[widget_wrapper_postpurchase]" size="60"  value="<?php echo $options['widget_wrapper_postpurchase'];?>"/><label for="ct_widget_wr_postpurchase">&nbsp;<?php _e( 'After Purchase' ); ?></label><br>

							</div>
						</div>
					</div>
				</div>


				<div class="clear"></div>
				<?php submit_button(); ?>
			</form>
		</div>
	</div>
<?php
}