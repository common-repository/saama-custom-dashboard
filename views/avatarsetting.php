<?php
include('header.php');

if ( isset( $_POST['scd_avatar_delete'] ) ) {
	$error = "";
	$success = "";
	if ( ! isset( $_POST['scd_delavatar_nonce'] ) || ! wp_verify_nonce( $_POST['scd_delavatar_nonce'], 'scd_delavatar_nonce' ) ) {
		$error .= "You have failed the security check. Please try again.</br>";
	}
	else {
		$old_avatars = get_user_meta( $current_user->ID, 'scd_user_avatar', true );
		$upload_path = wp_upload_dir();
		if ( is_array( $old_avatars ) ) {
			foreach ( $old_avatars as $old_avatar ) {
				$old_avatar_path = str_replace( $upload_path['baseurl'], $upload_path['basedir'], $old_avatar );
				@unlink( $old_avatar_path );
			}
		}
		delete_user_meta( $current_user->ID, 'scd_user_avatar' );
		$success .= 'Avatar deleted successfully.</br>';
	}
	
}

if ( isset( $_POST['scd_avatar_submit'] ) ) {
	$error = "";
	$success = "";
	if ( ! isset( $_POST['scd_avatar_nonce'] ) || ! wp_verify_nonce( $_POST['scd_avatar_nonce'], 'scd_avatar_nonce' ) ) {
		$error .= "You have failed the security check. Please try again.</br>";
	}
	if ( empty( $_FILES['scd-user-avatar']['name'] ) ) {
		$error .= 'Something Went Wrong.</br>';
	}
	if($scd_options['upload_files'] == "" || $scd_options['upload_files'] == '0') {
		$error .= 'You dont have permission to uplaod avatar.</br>';
	}
	else {
		// Allowed file extensions/types
		$mimes = array(
			'jpg|jpeg|jpe' => 'image/jpeg',
			'gif'          => 'image/gif',
			'png'          => 'image/png',
			);
		if ( ! function_exists( 'wp_handle_upload' ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}
		if ( strstr( $_FILES['scd-user-avatar']['name'], '.php' ) ) {
			$error .= 'For security reasons, the extension ".php" cannot be in your file name.</br>';
		}
		else {
			$avatar = wp_handle_upload( $_FILES['scd-user-avatar'], array( 'mimes' => $mimes, 'test_form' => false ) );
			if ( empty( $avatar['file'] ) ) {
				$error .= 'File type does not meet security guidelines. Try another.</br>';
			}
			else {
				$old_avatars = get_user_meta( $current_user->ID, 'scd_user_avatar', true );
				$upload_path = wp_upload_dir();
				if ( is_array( $old_avatars ) ) {
					foreach ( $old_avatars as $old_avatar ) {
						$old_avatar_path = str_replace( $upload_path['baseurl'], $upload_path['basedir'], $old_avatar );
						@unlink( $old_avatar_path );
					}
				}
				update_user_meta( $current_user->ID, 'scd_user_avatar', array( 'full' => $avatar['url'] ) );
				$success .= 'Avatar updated successfully.</br>';
			}
		}
	}
}
?>


<div class="container-fluid display-table">
	<div class="row display-table-row">
		<div class="col-md-12 col-sm-11 display-table-cell v-align">
			<div class="panel panel-default">
				<div class="panel-heading">Change Avatar</div>
				<div class="panel-body">

					<?php
					if(isset($error) && $error != ""){
						echo '<div class="alert alert-danger" id="">';
						echo $error;
						echo '</div>';
					} 
					if(isset($success) && $success != ""){
						echo '<div class="alert alert-success" id="">';
						echo $success;
						echo '</div>';
					}
					 ?>
				<form method="post" id="scd-userprofile" action="<?php echo get_permalink($scd_pages['dashboard_id']).'?scd_type=avatarsetting'; ?>" enctype="multipart/form-data">

				<?php 
				echo get_avatar($current_user->ID);
				wp_nonce_field( 'scd_avatar_nonce', 'scd_avatar_nonce', false );
				echo '<p><input type="file" name="scd-user-avatar" id="scd-avatar" /></p>';
				 ?>
				<input type="submit" name="scd_avatar_submit" value="Update Avatar" />
				</form>
				<?php
				$local_avatars = get_user_meta( $current_user->ID, 'scd_user_avatar', true );
				if ( !empty( $local_avatars ) && !empty( $local_avatars['full'] ) ) {
				 ?>
				<form method="post" id="scd-userprofile" action="<?php echo get_permalink($scd_pages['dashboard_id']).'?scd_type=avatarsetting'; ?>">

					<?php 
					wp_nonce_field( 'scd_delavatar_nonce', 'scd_delavatar_nonce', false );
					?>
					<input style="margin-top: 5px;" type="submit" name="scd_avatar_delete" value="Remove" />
				</form>
				<?php } ?>



				</div>
			</div>
		</div>
	</div>
</div>