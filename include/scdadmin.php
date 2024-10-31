<?php 

function scd_sidebar_items() {
	$fep_settings_page_hook = add_menu_page(
		'Custom Dashboard',
		'Custom Dashboard',
		'manage_options',
		'scd_settings',
		'scd_settings_page'
		);
}
add_action('admin_menu', 'scd_sidebar_items');


function scd_register_options() {
	add_settings_section('scd_options', null, null, 'scd_settings');

	add_settings_field('min_words_title', '', 'scd_render_settings_field', 'scd_settings', 'scd_options',array('id' => 'min_words_title', 'type' => 'text','label' => 'Min words required for title :'));
	add_settings_field('max_words_title', '', 'scd_render_settings_field', 'scd_settings', 'scd_options',array('id' => 'max_words_title', 'type' => 'text','label' => 'Max words allowed for title :'));
	add_settings_field('min_words_content', '', 'scd_render_settings_field', 'scd_settings', 'scd_options',array('id' => 'min_words_content', 'type' => 'text','label' => 'Min words required for post content :'));
	add_settings_field('max_words_content', '', 'scd_render_settings_field', 'scd_settings', 'scd_options',array('id' => 'max_words_content', 'type' => 'text','label' => 'Max words allowed in post content :'));
	add_settings_field('min_tags', '', 'scd_render_settings_field', 'scd_settings', 'scd_options',array('id' => 'min_tags', 'type' => 'text','label' => 'Min tags required :'));
	add_settings_field('max_tags', '', 'scd_render_settings_field', 'scd_settings', 'scd_options',array('id' => 'max_tags', 'type' => 'text','label' => 'Max tags allowed :'));
	add_settings_field('max_links', '', 'scd_render_settings_field', 'scd_settings', 'scd_options',array('id' => 'max_links', 'type' => 'text','label' => 'Max links allowed in post content :'));
	add_settings_field('posts_per_page', '', 'scd_render_settings_field', 'scd_settings', 'scd_options',array('id' => 'posts_per_page', 'type' => 'text','label' => 'No of posts per page :'));
	add_settings_field('author_can_edit', '', 'scd_render_settings_field', 'scd_settings', 'scd_options',array('id' => 'author_can_edit', 'type' => 'checkbox','label' => 'Author can edit his/her own post? ', 'default' => 'true'));
	add_settings_field('author_can_delete', '', 'scd_render_settings_field', 'scd_settings', 'scd_options',array('id' => 'author_can_delete', 'type' => 'checkbox','label' => 'Author can delete his/her own post? ', 'default' => 'false'));
	add_settings_field('upload_files', '', 'scd_render_settings_field', 'scd_settings', 'scd_options',array('id' => 'upload_files', 'type' => 'checkbox','label' => 'Author can upload avatars? ', 'default' => 'false'));
	add_settings_field('media_access', '', 'scd_render_settings_field', 'scd_settings', 'scd_options',array('id' => 'media_access', 'type' => 'checkbox','label' => 'Author can access media files? </br> <small>Media access required to upload images in posts.</small> ', 'default' => 'false'));
	add_settings_field('instant_publish', '', 'scd_render_settings_field', 'scd_settings', 'scd_options',array('id' => 'instant_publish', 'type' => 'checkbox','label' => 'Author post should publish instantly? ', 'default' => 'true'));
	add_settings_field('allow_registration', '', 'scd_render_settings_field', 'scd_settings', 'scd_options',array('id' => 'allow_registration', 'type' => 'checkbox','label' => 'Enable Registration? ', 'default' => 'true'));
	
	register_setting('scd_settings','scd_options','scd_validation');
}
add_action('admin_init', 'scd_register_options');

function scd_validation($input) {
	return $input;
}

function scd_render_settings_field($arg) {
	$scd_options = get_option('scd_options');
	?>
	<div class="scd-main">
		<table class="form-table">
			<?php if($arg['type'] == "text") { ?>
			<tr>
				<th><label><?php echo $arg['label']; ?></label></th>
				<td>
				<input type="<?php echo $arg['type']; ?>" id="scd_options[<?php echo $arg['id']; ?>]" name="scd_options[<?php echo $arg['id']; ?>]" value="<?php echo (isset($scd_options[$arg['id']])) ? esc_attr($scd_options[$arg['id']]) : ''; ?>">
				</td>
			</tr>	
			<?php }
			elseif($arg['type'] == "checkbox") { ?>
			<tr>
				<th><label><?php echo $arg['label']; ?></label></th>
				<td>
				<input type="hidden" name="scd_options[<?php echo $arg['id']; ?>]" value="0"/>
				<input type="<?php echo $arg['type']; ?>" id="scd_options[<?php echo $arg['id']; ?>]" name="scd_options[<?php echo $arg['id']; ?>]" value="true" <?php if($scd_options[$arg['id']] == "true") { echo "checked"; } ?>>
				</td>
			</tr>
			<?php	} ?>
		</table>
	</div>
	<?php
}


function scd_settings_page() {
?>

<div>
	<h2>Custom Dashboard Setting</h2>
	<?php settings_errors(); ?>
	<div style="background: #ffffff; padding: 20px; margin-right: 0px; border: 1px solid #dbdbdb;">
	<form method="post" action="options.php">
		<?php 
		settings_fields('scd_settings');
		do_settings_fields("scd_settings",'scd_options');
		submit_button(); 
		?>

	</form>
	</div>
</div>


<?php }
 ?>