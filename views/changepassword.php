<?php
include('header.php');
if ( isset( $_POST['scd_update_password'] ) ) {
	$error = "";
	$success = "";
	if ( ! isset( $_POST['scd_password_nonce'] ) || ! wp_verify_nonce( $_POST['scd_password_nonce'], 'scd_password_nonce' ) ) {
		$error .= "You have failed the security check. Please try again.</br>";
	}
	if(empty($_POST['c-password']) || empty($_POST['n-password']) || empty($_POST['cn-password']) ) {
		$error .= "Fill out all required fields.</br>";
	}
	if ( !wp_check_password( $_POST['c-password'], $current_user->user_pass , $current_user->ID  ) )
	{
		$error .= "Invalid current password.</br>";
	}
	if($_POST['n-password'] != $_POST['cn-password']) {
		$error .= "Password didnt matched.</br>";
	}
	if($error != "") {
		//
	}
	else {
		$hashpassword = wp_hash_password( $_POST['n-password'] );
		wp_update_user( array( 'ID' => $current_user->ID, 'user_pass' => $_POST['n-password'] ) );
		wp_redirect(get_permalink($scd_pages['dashboard_id']).'?scd_type=changepassword&success');
	}
}

?>

<div class="container-fluid display-table">
	<div class="row display-table-row">
		<div class="col-md-12 col-sm-11 display-table-cell v-align">
			<div class="panel panel-default">
				<div class="panel-heading">Change Password</div>
				<div class="panel-body">
					<?php
					if(isset($error) && $error != ""){
						echo '<div class="alert alert-danger" id="">';
						echo $error;
						echo '</div>';
					} 
					if(isset($_GET['success'] )){
						echo '<div class="alert alert-success" id="">';
						echo "Password Updated successfully";
						echo '</div>';
					}
					?>



				<div class="alert alert-danger" id="scd-error-box"></div>


				<form method="post" id="scd-userprofile" action="<?php echo get_permalink($scd_pages['dashboard_id']).'?scd_type=changepassword'; ?>">


				<div class="form-group">
				<label class="scd-labels" for="c-password">Current Password <small>*</small></label>
				<input class="form-control" name="c-password" type="password" id="c-password" value="" />
				</div>


				<div class="form-group">
				<label class="scd-labels" for="n-password">New Password <small>*</small></label>
				<input class="form-control" name="n-password" type="password" id="n-password" value="" />
				</div>


				<div class="form-group">
				<label class="scd-labels" for="cn-password">Confirm New Password <small>*</small><small></small></label>
				<input class="form-control" name="cn-password" type="password" id="cn-password" value="" />
				</div>

				<?php wp_nonce_field( 'scd_password_nonce', 'scd_password_nonce', false ); ?>
				<input style="margin-top: 5px;" type="submit" name="scd_update_password" value="Update Password" />
				</form>
				</div>
			</div>
		</div>
	</div>
</div>
