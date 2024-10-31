<?php
include('header.php');
wp_enqueue_media();
if(!isset($_GET['post_id']) || $_GET['post_id'] == "") {
	wp_redirect(get_permalink($scd_pages['dashboard_id']).'?scd_type=posts');
}
if($scd_options['author_can_edit'] == "" || $scd_options['author_can_edit'] == '0') {
	$error = "You dont have permssion to edit this post.";
	wp_redirect(get_permalink($scd_pages['dashboard_id']).'?scd_type=posts&error='.$error);
}
$post_id = $_GET['post_id'];
$get_post_data = get_post($post_id, 'ARRAY_A');
if($get_post_data['post_author'] != $current_user->ID) {
	$error = "You dont have permssion to edit this post.";
	wp_redirect(get_permalink($scd_pages['dashboard_id']).'?scd_type=posts&error='.$error);
}
$category = get_the_category($post_id);
$tags = wp_get_post_tags($post_id, array('fields' => 'names'));
$post = array(
	'title'            => $get_post_data['post_title'],
	'content'          => $get_post_data['post_content'],
	'about_the_author' => get_post_meta($post_id, 'about_the_author', true)
	);
if (isset($category[0]) && is_array($category)) {
$post['category'] = $category[0]->cat_ID;
}
if (isset($tags) && is_array($tags)) {
$post['tags'] = implode(', ', $tags);
}
?>


<div class="container-fluid display-table" id="scd-main-container">
	<div class="row display-table-row">
		<div class="col-md-12 col-sm-11 display-table-cell v-align">
			<div class="panel panel-default">
				<div class="panel-heading">Edit Post</div>
				<div class="panel-body">

				<div class="alert alert-danger" id="scd-error-box"></div>
				<div class="alert alert-success" id="scd-success-box"></div>


				<div class="pull-right"><small>Post Status :
				<?php 
				if(get_post_status($post_id) == "pending") {
					echo '<span style="color:red;">Pending Review</span>';
				}
				elseif(get_post_status($post_id) == "publish") {
					echo '<span style="color:green;">Published</span>';
				}
				else {
					echo get_post_status($post_id);
				}
				?>
				</small></div>


				<div class="form-group">
				<label class="scd-labels" for="scd-post-title">Title</label>
				<input type="text" class="form-control" name="post_title" id="scd-post-title" value="<?php echo ($post) ? $post['title'] : ''; ?>">
				</div>



				<div class="form-group">
				<label class="scd-labels" for="scd-post-content">Content</label>
				<?php 
				if($scd_options['media_access'] == "" || $scd_options['media_access'] == '0') {
					$media_access = 0;
				}
				else {
					$media_access = 1;
				}
				wp_editor($post['content'], 'scd-post-content', $settings = array('textarea_name' => 'post_content', 'textarea_rows' => 12, 'media_buttons' => $media_access));
				?>
				</div>



				<div class="form-group">
				<label class="scd-labels" for="scd-category">Categories</label>
				<?php wp_dropdown_categories(array('id' => 'scd-category', 'class' => 'form-control', 'name' => 'post_category', 'hide_empty' => 0, 'orderby' => 'name', 'selected' => $post['category'], 'hierarchical' => true)); ?>
				</div>



				<div class="form-group">
				<label class="scd-labels" for="scd-tags">Tags</label>
				<input type="text" class="form-control" name="post_tags" id="scd-tags" value="<?php echo ($post) ? $post['tags'] : ''; ?>">
				</div>



				<input type="hidden" name="post_id" id="scd-post-id" value="<?= $post_id ?>">
				<?php wp_nonce_field('scdnonce_field', 'scdnonce'); ?>
				<input type="button" id="scd-update-post" class="pull-right" value="Update">




				</div>
			</div>
		</div>
	</div>
</div>