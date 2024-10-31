<?php
include('header.php');
if ( isset( $_POST['scd_update_profile'] ) ) {
	$error = "";
	$success = "";
	if ( ! isset( $_POST['scd_profile_nonce'] ) || ! wp_verify_nonce( $_POST['scd_profile_nonce'], 'scd_profile_nonce' ) ) {
		$error .= "You have failed the security check. Please try again.</br>";
	}
	if(empty($_POST['dname']) || empty($_POST['email']) ) {
		$error .= "Fill out all required fields.</br>";
	}
	$email = sanitize_email( $_POST['email'] );
	if (!is_email( $email )) {
		$error .= "The email you enter is not valid.</br>";
	}
	if(($email != $current_user->user_email) && email_exists($email)) {
			$error .= "Email already used by another user.</br>";
	}
	if($error != "") {
	 	//
	}
	else {
		$first_name = isset($_POST['first-name']) ? $_POST['first-name'] : '';
		$last_name =  isset($_POST['last-name']) ? $_POST['last-name'] : '';
		$description =  isset($_POST['user_bio']) ? $_POST['user_bio'] : '';
		$website =  isset($_POST['website']) ? $_POST['website'] : '';
		$first_name = sanitize_text_field($first_name);
		$last_name =  sanitize_text_field($last_name);
		$description =  sanitize_textarea_field($description);
		$display_name = sanitize_text_field($_POST['dname']);
		$website =  sanitize_text_field($website);
		wp_update_user( array( 'ID' => $current_user->ID, 'user_url' => esc_url_raw( $website ) , 'user_email' => $email , 'display_name' => $display_name ) );
		update_user_meta( $current_user->ID, 'first_name', $first_name );
		update_user_meta( $current_user->ID, 'last_name', $last_name );
		update_user_meta( $current_user->ID, 'description', $description );
		wp_redirect(get_permalink($scd_pages['dashboard_id']).'?scd_type=editprofile&success');
	}
}

?>

<div class="container-fluid display-table">
	<div class="row display-table-row">
		<div class="col-md-12 col-sm-11 display-table-cell v-align">
			<div class="panel panel-default">
				<div class="panel-heading">Edit Profile</div>
				<div class="panel-body">
					<?php
					if(isset($error) && $error != ""){
						echo '<div class="alert alert-danger" id="">';
						echo $error;
						echo '</div>';
					} 
					if(isset($_GET['success'] )){
						echo '<div class="alert alert-success" id="">';
						echo "Profile Updated successfully";
						echo '</div>';
					}
					?>



				<div class="alert alert-danger" id="scd-error-box"></div>


				<form method="post" id="scd-userprofile" action="<?php echo get_permalink($scd_pages['dashboard_id']).'?scd_type=editprofile'; ?>">

				<div class="form-group">
				<label class="scd-labels" for="first-name">First Name</label>
				<input class="form-control" name="first-name" type="text" id="first-name" value="<?php echo esc_html( $current_user->first_name ); ?>" />
				</div>

				<div class="form-group">
				<label class="scd-labels" for="last-name">Last Name</label>
				<input class="form-control" name="last-name" type="text" id="last-name" value="<?php echo esc_html( $current_user->last_name ); ?>" />
				</div>


				<div class="form-group">
				<label class="scd-labels" for="dname">Display Name <small>(Required)</small></label>
				<input class="form-control" name="dname" type="text" id="dname" value="<?php echo esc_html( $current_user->display_name ); ?>" />
				</div>
				<div class="form-group">
				<label class="scd-labels" for="email">Email <small>(Required)</small></label>
				<input class="form-control" name="email" type="text" id="email" value="<?php echo esc_html( $current_user->user_email ); ?>"/>
				</div>

				<div class="form-group">
				<label class="scd-labels" for="website">Website</label>
				<input class="form-control" name="website" type="text" id="website" value="<?php echo esc_url(  $current_user->user_url ); ?>" />
				</div>


				<div class="form-group">
				<label class="scd-labels" for="user_bio">Bio</label>
				<?php $userdata = get_user_meta( $current_user->ID ); ?>
				<textarea class="form-control" id="user_bio" name="user_bio" id="user_bio"><?php echo esc_textarea( $userdata['description'][0] ); ?></textarea>
				</div>
				<?php wp_nonce_field( 'scd_profile_nonce', 'scd_profile_nonce', false ); ?>
				<input style="margin-top: 5px;" type="submit" name="scd_update_profile" value="Update" />
				</form>
				






				</div>
			</div>
		</div>
	</div>
</div>