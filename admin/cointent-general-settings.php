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
	<div id="cointent-admin" class="metabox-holder has-right-sidebar">
		<?php if (!empty($_POST['_Submit'])) : ?>
			<div id="message" class="updated fade"><p><strong><?php _e('Options saved.') ?></strong></p></div>
		<?php endif; ?>


		<div class="intro">

			<div class="intro_box">
				<div class="header">
					<span>Welcome to </span><img src="<?php echo plugins_url('/images/logo_and_name_265x51.png', COINTENT_BASE_DIR)?>"/>
				</div>
				<p>Geting started is easy! For more informtation, see the <a href="//cointent.com/docs/wordpress">documentation</a>. If you have any questions, send us and email at  <a href="mailto:support@cointent.com">support@cointent.com</a></p>
				<div>

					<div class="cointent_step_box">
						<div><img src="<?php echo plugins_url('/images/step_1.png', COINTENT_BASE_DIR)?>"></div>
						<h3>Step 1</h3>
						<p> Get your <a href="https://cointent.com/p/account">publisher account</a> and setup the plugin info below</p>
					</div>
					<div class="cointent_step_box">
						<div><img src="<?php echo plugins_url('/images/step_2.png', COINTENT_BASE_DIR)?>"></div>
						<h3>Step 2</h3>
						<p>Set up your paid posts using <a href="https://en.support.wordpress.com/posts/categories/">categories</a> (or see <a href="https://cointent.com/docs/wordpress/advanced">advanced</a> options for shortcode)</p>
					</div>
					<div class="cointent_step_box">
						<div><img src="<?php echo plugins_url('/images/step_3.png', COINTENT_BASE_DIR)?>"></div>
						<h3>Step 3</h3>
						<p> Post your articles, videos, or content!</p>
					</div>
				</div>
			</div>
		</div>
		<div class="">

			<form action="options.php" method="post" id="cointent-conf">
				 <?php settings_fields( 'cointent-settings-group' ); ?>
				 <?php do_settings_sections( 'cointent-settings-group' ); ?>
				<br>

				<div class="ct-section">

					<div class="body">
						<div class="postbox">
							<h3><?php _e('General Settings') ?> </h3>
							<table class="cointent_main_table">
								<tr>
									<td>
										<h4>Publisher Id</h4>
										<p>Signup to get a publisher ID <a target="_blank" href="https://cointent.com/p/signup">here.</a> </p>
									</td>
									<td>
										<input type="text" id="ct_publisher_id" name="Cointent[publisher_id]" value="<?php echo $options['publisher_id'];?>"/>
									</td>
								</tr>
								<tr>
									<td>
										<h4>Publisher Token</h4>
										<p>Signup to get a publisher token <a target="_blank" href="https://cointent.com/p/signup">here.</a> </p>
									</td>
									<td>
										<input type="text" id="ct_publisher_token" name="Cointent[publisher_token]" value="<?php echo $options['publisher_token'];?>"/>
									</td>
								</tr>
								<tr>
									<td>
										<h4>Full page reload</h4>
										<p>If you are just locking text we suggest partial page reload. If you have interactive content, you may need to do a full page reload to make sure the correct javascript files are included.</p>
									</td>
									<td>
										<ul>
											<li>
												<input type="radio" id="ct_reload_partial_page" name="Cointent[reload_full_page]" value="0" <?php if ($options['reload_full_page'] ==  false ) { echo "checked"; }?>/><label for="ct_reload_partial_page">&nbsp;<?php _e( 'Partial' ); ?></label><br>
											</li>
											<li>
												<input type="radio" id="ct_reload_full_page" name="Cointent[reload_full_page]" value="1" <?php if ($options['reload_full_page'] ==  true ) { echo "checked"; }?>/><label for="ct_reload_full_page">&nbsp;<?php _e( 'Full' ); ?></label><br>
											</li>
										</ul>
									</td>
								</tr>
								<tr>
									<td>
										<h4><?php _e('Locked Categories') ?> </h4>
										<p>You can lock entire categories of posts, a short preview will automatically be shown to users, followed by the paywall. Include categories are categories that you want to have a paywall, include is overriden by exclude categories. Excluded categories will not be gated by a paywall. </p>
											If you would like to lock individual posts check out our <a href="https://cointent.com/docs/js">documentation</a>.</p>
									</td>
									<td>
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
									</td>
								</tr>
								<tr>
									<td>
										<h4><?php _e('Tracking') ?> </h4>
										<p>
											<p class="bold">Please be sure to opt into tracking to get the full value from our offering.</p>
											By opting in, we can assist you by providing full transparency on your content views, clicks, and purchases.
											Tracking these actions allows you to find out how many users are seeing and trying to buy your content, and can help you optimize your flows and content offerings to maximize your results.
											Security and privacy are of the utmost importance to us, and we use <a href="https://www.keen.io">keen.io</a> and <a href="https://mixpanel.com">mixpanel.com</a> to process tracking calls and keep them secure.
										 </p>
									</td>
									<td>
										<ul>
											<li>
												<input type="radio" id="ct_active" name="Cointent[cointent_tracking]" value="1" <?php if (isset($options['cointent_tracking']) && $options['cointent_tracking'] == true ) { echo "checked"; }?>/><label for="ct_tracking_active">&nbsp;<?php _e( 'Active' ); ?></label><br>
											</li>
											<li>
												<input type="radio" id="ct_inactive" name="Cointent[cointent_tracking]" value="0" <?php if (!isset($options['cointent_tracking']) || $options['cointent_tracking']  == false ) { echo "checked"; }?>/><label for="ct_tracking_inactive">&nbsp;<?php _e( 'Inactive' ); ?></label>
											</li>
											<li>
												<input type="hidden" id="ct_tracking_popup" name="Cointent[tracking_popup]" value="<?php echo $options['tracking_popup'] ?>"/>
											</li>
										</ul>
									</td>
								</tr>
								<tr>
									<td>
										<h4><?php _e('Show preview') ?> </h4>
										<p class="bold">
											How long (word count) do you want your preview (0 for no preview)  default is 55
										 </p>
									</td>
									<td>
										<ul>
											<li>
												<input type="text" id="ct_preview_count" name="Cointent[preview_count]" value="<?php echo $options['preview_count'];?>"/>
											</li>

										</ul>
									</td>
								</tr>
							</table>
							<div class="inside">
								<?php submit_button(); ?>
							</div>
						</div>
					</div>
				</div>

				<div class="ct-section">

					<div class="body">
						<div class="postbox">
							<h3><?php _e('Style & View Options') ?> </h3>

							<table class="cointent_main_table">
								<tr>
									<td>
										<h4>View Type</h4>
										<p> Checkout the different view options at our webpage <a href="https://cointent.com/docs/wordpress/views"></a></p>
									</td>
									<td>
										<ul>
											<li>
												<input type="radio" id="ct_condensed" name="Cointent[view_type]" value="condensed" <?php if ($options['view_type'] ==  "condensed" ) { echo "checked"; }?>/><label for="ct_condensed">&nbsp;<?php _e( 'Condensed' ); ?></label><br>
											</li>
											<li>
												<input type="radio" id="ct_full" name="Cointent[view_type]" value="full" <?php if ($options['view_type'] ==  "full" ) { echo "checked"; }?>/><label for="ct_full">&nbsp;<?php _e( 'Full' ); ?></label><br>
											</li>

										</ul>
									</td>
								</tr>
								<tr>
									<td>
										<h4>Message To Readers</h4>
										<p>Limit 140 Characters. Default text for widget (can be overridden by individual shortcode in post)</p>
									</td>
									<td>
										<table>
											<tr>
												<td><label for="ct_widget_title">&nbsp;<?php _e( 'Title, Before Purchase' ); ?></label></td>
												<td><textarea type="text" id="ct_widget_title" name="Cointent[widget_title]" cols="65" rows="3"><?php echo $options['widget_title'];?></textarea></td>
											</tr>
											<tr>
												<td><label for="ct_widget_subtitle">&nbsp;<?php _e( 'Subtitle, Before Purchase' ); ?></label></td>
												<td><textarea type="text" id="ct_widget_subtitle" name="Cointent[widget_subtitle]" cols="65" rows="3" ><?php echo $options['widget_subtitle'];?></textarea></td>
											</tr>
											<tr>
												<td><label for="ct_widget_post_purchase_title">&nbsp;<?php _e( 'Title, After Purchase' ); ?></label></td>
												<td><textarea type="text" id="ct_widget_post_purchase_title" name="Cointent[widget_post_purchase_title]" cols="65" rows="3"><?php echo $options['widget_post_purchase_title'];?></textarea></td>
											</tr>
											<tr>
												<td><label for="ct_widget_post_purchase_subtitle">&nbsp;<?php _e( 'Subtitle, After Purchase' ); ?></label></td>
												<td><textarea type="text" id="ct_widget_post_purchase_subtitle" name="Cointent[widget_post_purchase_subtitle]" cols="65" rows="3"><?php echo $options['widget_post_purchase_subtitle'];?></textarea></td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
							<div class="inside">
								<?php submit_button(); ?>
							</div>
						</div>
					</div>
				</div>

				<div class="ct-section">

					<div class="body">
						<div class="postbox">
							<h3><?php _e('Advanced Settings') ?> </h3>

							<table class="cointent_main_table">
								<tr>
									<td>
										<h4>CSS Wrapper Class</h4>
										<p>In case you want to apply your own css to our widget using a default class you have</p>
									</td>
									<td>
										<ul>
											<li>
												<label for="ct_widget_wr_prepurchase">&nbsp;<?php _e( 'Before Purchase' ); ?></label>
												<input type="text" id="ct_wrapper_prepurchase_id" name="Cointent[widget_wrapper_prepurchase]" size="60" value="<?php echo $options['widget_wrapper_prepurchase'];?>"/>

											</li>
											<li><label for="ct_widget_wr_postpurchase">&nbsp;<?php _e( 'After Purchase' ); ?></label>
												<input type="text" id="ct_wrapper_postpurchase_id" name="Cointent[widget_wrapper_postpurchase]" size="60"  value="<?php echo $options['widget_wrapper_postpurchase'];?>"/>
											</li>
										</ul>
									</td>
								</tr>
								<tr>
									<td>
										<h4>Environment</h4>
										<p>
											Sandbox - This is a testing environment, no real payments will be processed, only use this if you have a dev or staging blog that isn't open to the public</br>
											Live - This is the setting you use to on your site when you are ready for real customers to pay real money!
										</p>
									</td>
									<td>
										<ul>
											<li>
												<input type="radio" id="ct_sandbox" name="Cointent[environment]" value="sandbox" <?php if ($options['environment'] ==  "sandbox" ) { echo "checked"; }?>/>
												<label for="ct_sandbox">&nbsp;<?php _e( 'Sandbox - for testing only' ); ?></label>
											</li>
											<li>
												<input type="radio" id="ct_production" name="Cointent[environment]" value="production" <?php if ($options['environment'] ==  "production" ) { echo "checked"; }?>/>
												<label for="ct_production">&nbsp;<?php _e( 'Live - for live payments' ); ?></label>
											</li>
										</ul>
									</td>
								</tr>
							</table>
							<div class="inside">
								<?php submit_button(); ?>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
<?php
}