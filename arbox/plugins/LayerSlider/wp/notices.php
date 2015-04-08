<?php

// Update notice
if(strpos($_SERVER['REQUEST_URI'], '?page=layerslider') !== false) {
	add_action('admin_notices', 'layerslider_update_notice');
	add_action('admin_notices', 'layerslider_dependency_notice');
	if( get_option('ls-show-support-notice', 1) && !get_option('layerslider-validated', null) && !get_option('layerslider-authorized-site', null) ) {
		add_action('admin_notices', 'layerslider_premium_support');
	}
}

// Storage notice
if(get_option('layerslider-slides') !== false) {

	global $pagenow;
	if($pagenow == 'plugins.php' || $pagenow == 'index.php' || strpos($_SERVER['REQUEST_URI'], 'layerslider')) {
		add_action('admin_notices', 'layerslider_compatibility_notice');
	}
}

// License notification under the plugin row on the Plugins screen
if(!get_option('layerslider-authorized-site', null)) {
	add_action('after_plugin_row_'.LS_PLUGIN_BASE, 'layerslider_plugins_purchase_notice', 10, 3 );
}

function layerslider_update_notice() {

	// Get plugin updates
	$updates = get_plugin_updates();

	// Check for update
	if(isset($updates[LS_PLUGIN_BASE]) && isset($updates[LS_PLUGIN_BASE]->update)) {
		$update = $updates[LS_PLUGIN_BASE];
		add_thickbox();
		?>
		<div class="layerslider_notice">
			<img src="<?php echo LS_ROOT_URL.'/static/img/ls_80x80.png' ?>" alt="LayerSlider icon">
			<h1><?php _e('An update is available for LayerSlider WP!', 'LayerSlider') ?></h1>
			<p>
				<?php echo sprintf(__('You have version %1$s. Update to version %2$s.', 'LayerSlider'), $update->Version, $update->update->new_version); ?><br>
				<i><?php echo $update->update->upgrade_notice ?></i>
				<a href="plugin-install.php?tab=plugin-information&amp;plugin=LayerSlider&amp;section=changelog&amp;TB_iframe=true&amp;width=773&amp;height=793" class="thickbox" title="LayerSlider WP">
					<?php _e('Review changes & Install', 'LayerSlider') ?>
				</a>
			</p>
			<div class="clear"></div>
		</div>
		<?php
	}
}


function layerslider_compatibility_notice() { ?>
	<div class="layerslider_notice">
		<img src="<?php echo LS_ROOT_URL.'/static/img/ls_80x80.png' ?>" alt="LayerSlider icon">
		<h1><?php _e('The new version of LayerSlider WP is almost ready!', 'LayerSlider') ?></h1>
		<p>
			<?php _e('For a faster and more reliable solution, LayerSlider WP needs to convert your data associated with the plugin. Your sliders and settings will remain still, and it only takes a click on this button.', 'LayerSlider') ?>

			<a href="<?php echo wp_nonce_url('?page=layerslider&action=convert', 'convertoldsliders') ?>">
				<?php _e('Convert Data', 'LayerSlider') ?>
			</a>
		</p>
		<div class="clear"></div>
	</div>
<?php }

function layerslider_dependency_notice() {
	if(version_compare(PHP_VERSION, '5.0.0', '<') || !class_exists('DOMDocument')) {
	?>
	<div class="layerslider_notice">
		<img src="<?php echo LS_ROOT_URL.'/static/img/ls_80x80.png' ?>" alt="LayerSlider icon">
		<h1><?php _e('Server configuration issues detected!', 'LayerSlider') ?></h1>
		<p>
			<?php _e('phpQuery, an external library in LayerSlider, have unmet dependencies. It requires PHP5 with the following extensions installed: PHP DOM extension, PHP Multibyte String extension. Please contact with your hosting provider to resolve these dependencies, as it will likely prevent LayerSlider from functioning properly.', 'LayerSlider') ?>
			<strong><?php _e('This issue could result a blank page in slider builder.', 'LayerSlider') ?></strong>
		</p>
		<div class="clear"></div>
	</div>
<?php } }

function layerslider_premium_support() {
	if(get_user_meta(get_current_user_id(), 'layerslider_help_wp_pointer', true)) {
?>	

<div class="layerslider_notice">
	<img src="<?php echo LS_ROOT_URL.'/static/img/ls_80x80.png' ?>" alt="LayerSlider icon">
		<h1><?php _e('Would you like to receive automatic updates and premium support?', 'LayerSlider') ?></h1>
		<p>
			<?php _e("By activating the plugin with your Item Purchase Code you can receive update notifications with one-click installation and better support services. This is optional and not needed if you've received LayerSlider bundled with a theme.", "LayerSlider") ?>
			<a href="<?php echo wp_nonce_url('?page=layerslider&action=hide-support-notice', 'hide-support-notice') ?>" class="">Hide this message</a>
		</p>
	<div class="clear"></div>
</div>

<?php } } 


function layerslider_plugins_purchase_notice($plugin_file, $plugin_data, $status){
	$table = _get_list_table('WP_Plugins_List_Table');
	?>
	<tr class="plugin-update-tr"><td colspan="<?php echo $table->get_column_count(); ?>" class="plugin-update colspanchange">
		<div class="update-message ls-plugins-screen-notice">
		<?php 
			printf(__('You need to authorize this site in order to get upgrades or support for this plugin. %sPurchase a license%s or %senter an existing purchase code%s.', 'installer'), 
				'<a href="http://codecanyon.net/item/layerslider-responsive-wordpress-slider-plugin-/1362246" target="_blank">', '</a>', '<a href="'.admin_url('admin.php?page=layerslider').'">', '</a>');
		?>
		</div>
	</tr>
<?php } ?>