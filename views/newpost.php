<?php
include('header.php');
wp_enqueue_media();
?>
<div class="container-fluid display-table" id="scd-main-container">
	<div class="row display-table-row">
		<div class="col-md-12 col-sm-11 display-table-cell v-align">
			<div class="panel panel-default">
				<div class="panel-heading">Add New Post</div>
				<div class="panel-body">

				<div class="alert alert-danger" id="scd-error-box"></div>
				<div class="alert alert-success" id="scd-success-box"></div>


				<div class="form-group">
				<label class="scd-labels" for="scd-post-title">Title</label>
				<input type="text" class="form-control" name="post_title" id="scd-post-title" value="">
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
				wp_editor('','scd-post-content', $settings = array('textarea_name' => 'post_content', 'textarea_rows' => 12, 'media_buttons' => $media_access));
				?>
				</div>



				<div class="form-group">
				<label class="scd-labels" for="scd-category">Categories</label>
				<?php wp_dropdown_categories(array('id' => 'scd-category', 'class' => 'form-control', 'name' => 'post_category', 'hide_empty' => 0, 'orderby' => 'name', 'hierarchical' => true)); ?>
				</div>



				<div class="form-group">
				<label class="scd-labels" for="scd-tags">Tags</label>
				<input type="text" class="form-control" name="post_tags" id="scd-tags" value="">
				<?php wp_nonce_field('scdnonce_field', 'scdnonce'); ?>
				</div>




				<input type="button" id="scd-submit-post" class="pull-right" value="Submit Post">




				</div>
			</div>
		</div>
	</div>
</div>