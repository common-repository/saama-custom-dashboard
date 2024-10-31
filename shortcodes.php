<?php 
function scd_home()
{
	$scd_pages = get_option('scd_pages');
	if (!is_user_logged_in()) {
		wp_redirect(get_permalink($scd_pages['login_id']));
		exit();
	}
	$scd_options = get_option('scd_options');
	$current_user = wp_get_current_user();
	$capabilities = $current_user->allcaps;
	ob_start();
	if( current_user_can('scd_role') ) {
		include('routes.php');
	}
	else {
		wp_redirect(admin_url());
	}
	return ob_get_clean();
}
add_shortcode('scd_dashboard', 'scd_home');


function scd_login()
{
	if (!is_user_logged_in()) {
		echo '<section class="scd_loginForm">';

		if(isset($_GET['login']) && $_GET['login'] == "invalid_credentials") {
			include(dirname(__FILE__) . '/views/header.php');
			echo '<div class="alert alert-danger" id="">Invalid username or password</div>';
		}
		if(isset($_GET['registration']) && $_GET['registration'] == "done") {
			include(dirname(__FILE__) . '/views/header.php');
			echo '<div class="alert alert-success" id="">Account has been registered! Login to continue</div>';
		}
		if(isset($_GET['username'])) {
			$username = $_GET['username'];
		}
		else {
			$username = NULL;
		}
		include(dirname(__FILE__) . '/views/header.php');
		$scd_options = get_option('scd_options');
		$scd_pages = get_option('scd_pages');
		$args = array(
			'echo'           => true,
			'redirect'       => get_permalink($scd_pages['dashboard_id']), 
			'form_id'        => 'scd_loginform',
			'label_username' => __( 'Username' ),
			'label_password' => __( 'Password' ),
			'label_remember' => __( 'Remember Me' ),
			'label_log_in'   => __( 'Log In' ),
			'id_username'    => 'scd_user_login',
			'id_password'    => 'scd_user_pass',
			'id_remember'    => 'rememberme',
			'id_submit'      => 'wp-submit',
			'remember'       => true,
			'value_username' => $username,
			'value_remember' => true
			); 
		wp_login_form( $args );
		if($scd_options['allow_registration'] == "" || $scd_options['allow_registration'] == '0') {
			//
		}
		else {
			$link = get_permalink($scd_pages['registration_id']);
			echo 'Dont have an account? <a href="'.$link.'">Register Here</a>';
		}
		$link = get_permalink($scd_pages['reset_password_id']);
		echo '</br><a href="'.$link.'">Forgot Your Password?</a>';
		echo '</section>';
	}
	else {
		$scd_pages = get_option('scd_pages');
		wp_redirect(get_permalink($scd_pages['dashboard_id']));
		exit();
	}
}
add_shortcode('scd_login', 'scd_login');


function scd_registration()
{
	if (!is_user_logged_in()) {
		$scd_pages = get_option('scd_pages');
		$scd_options = get_option('scd_options');
		if($scd_options['allow_registration'] == "" || $scd_options['allow_registration'] == '0') {
			wp_redirect(get_permalink($scd_pages['login_id']));
			exit();
		}
		else {
			include(dirname(__FILE__) . '/views/header.php');

			if(isset($_POST['scd_register'])) {
				$error = "";
				$success = "";
				if($_POST['username'] == "" || $_POST['email'] == "" || $_POST['password'] == "" || $_POST['password_confirmation'] == "") {
					$error = "All fields required </br>";
				}
				else {
					$username = esc_attr($_POST['username']);
					$email = esc_attr($_POST['email']);
					if ( strpos($username, ' ') !== false )
					{   
						$error .= "No spaces allowed in username </br>";
					} 
					if(username_exists($username)) {
						$error .= "Username already exist </br>";
					}
					if (!is_email($email)) {
						$error .= "The email you enter is not valid.</br>";
					}
					if(email_exists($email)) {
						$error .= "Email Already Exist.</br>";
					}
					if(0 === preg_match("/.{6,}/", $_POST['password']))
					{  
						$error .= "Password must be at least six characters.</br>";  
					}   
					if(0 !== strcmp($_POST['password'], $_POST['password_confirmation']))
					{  
						$error .= "Password did not matched.</br>";  
					}
					if($error != "") {
					//
					} 
					else {
						$new_user = wp_create_user( $username, $_POST['password'], $email ); 
						$new_user_role = new WP_User($new_user);
						$new_user_role->set_role('scd_role');
						$scd_pages = get_option('scd_pages');
						wp_redirect( get_permalink($scd_pages['login_id']) . '?username='.$username.'&registration=done' );
						exit;
					}
				}
			}


			echo '<section class="scd_registration_Form">';
			?>
			<?php
			if(isset($error) && $error != ""){
				echo '<div class="alert alert-danger" id="">';
				echo $error;
				echo '</div>';
			} 
			?>
			<form id="wp_signup_form" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">  

			    <div class="form-group">
				<label for="username">Username</label>  
				<input class="form-control" type="text" name="username" id="username"> 
				</div> 

				<div class="form-group">
				<label for="email">Email address</label>  
				<input class="form-control" type="text" name="email" id="email"> 
				</div> 

				<div class="form-group">
				<label for="password">Password</label>  
				<input class="form-control" type="password" name="password" id="password">  
				</div>

				<div class="form-group">
				<label for="password_confirmation">Confirm Password</label>  
				<input class="form-control" type="password" name="password_confirmation" id="password_confirmation"> 
				</div> 


				<input type="submit" style="margin-top: 10px;" name="scd_register" value="Sign Up" />  

			</form> 
			<?php
			$link = get_permalink($scd_pages['login_id']);
			echo 'Already have an account? <a href="'.$link.'">Login Here</a>';
			echo '</section>';
		}
	}
	else {
		$scd_pages = get_option('scd_pages');
		wp_redirect(get_permalink($scd_pages['dashboard_id']));
		exit();
	}
}
add_shortcode('scd_registration', 'scd_registration');


function scd_password_reset()
{
	if (!is_user_logged_in()) {
		$scd_pages = get_option('scd_pages');
		$scd_options = get_option('scd_options');
		include(dirname(__FILE__) . '/views/header.php');

		if(isset($_POST['scd_password_rest'])) {
			$error = "";
			$success = "";
			if($_POST['email'] == "") {
				$error = "Email field is required. </br>";
			}
			else {
				$email = trim($_POST['email']);
				if (!is_email($email)) {
					$error .= "The email you enter is not valid.</br>";
				}
				if($error != "") {
					//
				} 
				else {
					if(email_exists($email)) {
						$random_password = wp_generate_password( 12, false );
						$user = get_user_by( 'email', $email );
						$update_user = wp_update_user( array (
							'ID' => $user->ID,
							'user_pass' => $random_password
							)
						);
						if( $update_user ) {
							$to = $email;
							$subject = 'Your new password';
							$sender = get_bloginfo( 'name' );

							$message = 'Your new password is: '.$random_password;
							$message .= '</br></br>Kindly change your password on first login.';
							$message .= '</br></br></br><small>Regards</small>,';
							$message .= '</br><small>'.$sender.'</small>';
							$message .= '</br><small>'.get_bloginfo( 'url' ).'</small>';

							$headers[] = 'MIME-Version: 1.0' . "\r\n";
							$headers[] = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
							$headers[] = "X-Mailer: PHP \r\n";
							$headers[] = 'From: '.$sender.' < '.get_bloginfo( 'admin_email' ).' >' . "\r\n";

							$mail = wp_mail( $to, $subject, $message, $headers );
						}
					}
					$success .= "Password has been sent to the email address you entered.</br>";
					
					
				}
			}
		}


		echo '<section class="scd-pswd-reset-form">';
		?>
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
		<form id="scd-reset-form" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">  

			<div class="form-group">
				<label for="email">Email</label>  
				<input class="form-control" type="email" name="email" id="email"> 
			</div> 
			<input type="submit" style="margin-top: 10px;" name="scd_password_rest" value="Reset Your Password" />  

		</form> 
		<?php
		$link = get_permalink($scd_pages['login_id']);
		echo '</br>Remember Your Password? <a href="'.$link.'">Login Here</a>';
		echo '</section>';
		
	}
	else {
		$scd_pages = get_option('scd_pages');
		wp_redirect(get_permalink($scd_pages['dashboard_id']));
		exit();
	}
}
add_shortcode('scd_password_reset', 'scd_password_reset');
 ?>