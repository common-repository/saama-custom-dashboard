<?php 
/*
Plugin Name: Saama Custom Dashboard
Plugin URI: http://saamaweb.com
Description: Custom dashbaord for guest authors, Authors can register, login, manage their posts, profiles and upload avatars directly from from frontend without wp-admin access.
Version: 1.0
Author: Muhammad Usama Abdullah
License: GPL2 or later
*/


/**
 * Starts output buffer so that wp_redirect can work
 */
function scd_start_output_buffers()
{
	ob_start();
}

add_action('init', 'scd_start_output_buffers');



/**
 * Add plugin options, dashboard, login/regisration/reset password page and role on plugin activation
 */
function scd_initializer() {
	$scd_options = get_option('scd_options');
	$scd_pages = get_option("scd_pages");
	$scd_role = get_option('scd_role');
	if(!$scd_options) {
		$scd_options = array(
		'min_words_title'        => 2,
		'max_words_title'        => 12,
		'min_words_content'      => 2,
		'max_words_content'      => 2000,
		'min_tags'               => 1,
		'max_tags'               => 5,
		'max_links'              => 2,
		'posts_per_page'         => 10,
		'author_can_edit'        => true,
		'author_can_delete'      => false,
		'upload_files'           => false,
		'media_access'           => false,
		'instant_publish'        => true,
		'allow_registration'     => true
		);
		update_option("scd_options", $scd_options);
	}
	if (!$scd_pages['dashboard_id']) {
		$page1 = array(
			'post_title' => "Dashboard",
			'post_name' => "scd_dashboard",
			'post_content' => "[scd_dashboard]",
			'post_status' => "publish",
			'post_type' => "page",
			'comment_status' => 'closed',
			);
		$pageID_1 = wp_insert_post($page1, $error);
		$scd_pages = array('dashboard_id'=> $pageID_1);
		update_option("scd_pages", $scd_pages);
	}
	if(!$scd_pages['login_id']) {
		$page2 = array(
			'post_title' => "Login",
			'post_name' => "scd_login",
			'post_content' => "[scd_login]",
			'post_status' => "publish",
			'post_type' => "page",
			'comment_status' => 'closed',
			);
		$pageID_2 = wp_insert_post($page2, $error);
		$scd_pages = array('dashboard_id'=> $scd_pages['dashboard_id'],'login_id'=> $pageID_2);
		update_option("scd_pages", $scd_pages);
	}
	if(!$scd_pages['registration_id']) {
		$page3 = array(
			'post_title' => "Registration",
			'post_name' => "scd_registration",
			'post_content' => "[scd_registration]",
			'post_status' => "publish",
			'post_type' => "page",
			'comment_status' => 'closed',
			);
		$pageID_3 = wp_insert_post($page3, $error);
		$scd_pages = array('dashboard_id'=> $scd_pages['dashboard_id'],'login_id'=> $scd_pages['login_id'],'registration_id' => $pageID_3);
		update_option("scd_pages", $scd_pages);
	}
	if(!$scd_pages['reset_password_id']) {
		$page4 = array(
			'post_title' => "Password Reset",
			'post_name' => "scd_password_reset",
			'post_content' => "[scd_password_reset]",
			'post_status' => "publish",
			'post_type' => "page",
			'comment_status' => 'closed',
			);
		$pageID_4 = wp_insert_post($page4, $error);
		$scd_pages = array('dashboard_id'=> $scd_pages['dashboard_id'],'login_id'=> $scd_pages['login_id'],'registration_id' => $scd_pages['registration_id'],'reset_password_id' => $pageID_4);
		update_option("scd_pages", $scd_pages);
	}
	if(!$scd_role){
		add_role('scd_role', 'Guest Author', array(
			'read' => true,
			'edit_posts'   => true,
			'delete_posts' => true,
			'edit_published_posts' => true,
			'upload_files' => true
            ));
		update_option('scd_role',true);
	}
}
register_activation_hook(__FILE__, 'scd_initializer');


/**
 * Remove plugin options, pages and role on plugin deactivation
 */
function remove_scd() {
	$scd_pages = get_option("scd_pages");
	wp_delete_post($scd_pages['dashboard_id']);
	wp_delete_post($scd_pages['login_id']);
	wp_delete_post($scd_pages['registration_id']);
	wp_delete_post($scd_pages['reset_password_id']);
	remove_role('scd_role');
	delete_option('scd_pages');
	delete_option('scd_options');
	delete_option('scd_role');
}
register_deactivation_hook( __FILE__, 'remove_scd' );



/**
 * Enqueue styles and scripts
 */
 function scd_scripts_styles()
{
	//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css
    wp_register_style('scd-bootstrap', plugins_url( 'files/bootstrap.min.css', __FILE__ ));
    wp_register_style('scd-style', plugins_url( 'files/style.css', __FILE__ ));
    wp_register_script('scd-js', plugins_url( 'files/scd.js', __FILE__ ) , array('jquery') );
    $scdajaxcall = array('ajaxurl' => admin_url('admin-ajax.php'));
    wp_localize_script('scd-js', 'scdajaxcall',$scdajaxcall);
    $scd_options = get_option('scd_options');
    wp_localize_script( 'scd-js', 'scd_options', $scd_options );
}
add_action( 'init', 'scd_scripts_styles' );


/**
 * Remove default link from admin bar, add custom links for guest author role
  */
function scd_admin_bar_render() {
	$scd_pages = get_option('scd_pages');
	if( current_user_can('scd_role') ) {
		global $wp_admin_bar;
		$wp_admin_bar->remove_menu('comments');
		$wp_admin_bar->remove_menu('updates');
		$wp_admin_bar->remove_menu('new-content');
		$wp_admin_bar->remove_menu('edit');
		$wp_admin_bar->remove_menu('wp-logo');
		$wp_admin_bar->remove_menu('site-name');
		$wp_admin_bar->remove_menu('customize');
		$wp_admin_bar->remove_menu('my-account');
		//$wp_admin_bar->remove_menu('my-account');
		$args = array(
            'id' => 'scd-admin-bar-dashboard',
            'title' => '<span class="ab-icon"></span><span class="ab-label">Dashboard</span>',
            'href' => get_permalink($scd_pages['dashboard_id']),
            );
		$wp_admin_bar->add_node($args);
		$args = array(
            'id' => 'scd-admin-bar-posts',
            'title' => '<span class="ab-icon"></span><span class="ab-label">All Posts</span>',
            'href' => get_permalink($scd_pages['dashboard_id']).'?scd_type=posts',
            );
		$wp_admin_bar->add_node($args);
		$args = array(
            'id' => 'scd-admin-bar-new-post',
            'title' => '<span class="ab-icon"></span><span class="ab-label">Add New Post</span>',
            'href' => get_permalink($scd_pages['dashboard_id']).'?scd_type=newpost',
            );
		$wp_admin_bar->add_node($args);
		$current_user = wp_get_current_user();
		$avatarlink = get_permalink($scd_pages['dashboard_id']).'?scd_type=avatarsetting';
		$args = array(
            'id' => 'scd-admin-bar-my-account',
            'parent'=> 'top-secondary',
            'title' => '<a class="ab-item" aria-haspopup="true" href="'.$avatarlink.'"><span class="scd-display-name" style="margin-right: 5px;">'.$current_user->display_name.'</span>'.get_avatar($current_user->ID,26).'</a>'
            );
		$wp_admin_bar->add_node($args);
		$args = array(
            'id' => 'scd-admin-bar-edit-profile',
            'title' => 'Edit Profile',
            'href' => get_permalink($scd_pages['dashboard_id']).'?scd_type=editprofile',
            'parent' => 'scd-admin-bar-my-account'
            );
		$wp_admin_bar->add_node($args);
		$args = array(
            'id' => 'scd-admin-bar-change-avatar',
            'title' => 'Change Avatar',
            'href' => get_permalink($scd_pages['dashboard_id']).'?scd_type=avatarsetting',
            'parent' => 'scd-admin-bar-my-account'
            );
		$wp_admin_bar->add_node($args);
		$args = array(
            'id' => 'scd-admin-bar-change-password',
            'title' => 'Change Password',
            'href' => get_permalink($scd_pages['dashboard_id']).'?scd_type=changepassword',
            'parent' => 'scd-admin-bar-my-account'
            );
		$wp_admin_bar->add_node($args);
		$args = array(
            'id' => 'scd-admin-bar-logout',
            'title' => 'Logout',
            'href' => wp_logout_url( get_permalink($scd_pages['dashboard_id']) ),
            'parent' => 'scd-admin-bar-my-account'
            );
		$wp_admin_bar->add_node($args);



	} 
}
add_action( 'wp_before_admin_bar_render', 'scd_admin_bar_render' );



/**
 * Restric guest author access to wp-admin
 */
function scd_restrict_wp_admin() {
	$file = basename($_SERVER['PHP_SELF']);
    if ( current_user_can( 'scd_role' ) && $file != 'admin-ajax.php' && $file != 'async-upload.php' ) {
    	$scd_pages = get_option('scd_pages');
    	wp_redirect(get_permalink($scd_pages['dashboard_id']));
        exit();
    }
}
add_action( 'admin_init', 'scd_restrict_wp_admin', 1 );



/**
 * Prevent user to go to default login page incase of wrong credentials
 */
function scd_front_end_login_fail( $username ) {
   $referrer = $_SERVER['HTTP_REFERER'];
   if ( !empty($referrer) && !strstr($referrer,'wp-login') && !strstr($referrer,'wp-admin') ) {
   	$scd_pages = get_option('scd_pages');
      wp_redirect( get_permalink($scd_pages['login_id']) . '?login=invalid_credentials' );
      exit;
   }
}
add_action( 'wp_login_failed', 'scd_front_end_login_fail' ); 


/**
 * Prevent user to go to default login page incase of empty username or password. 
 */
function scd_auth_signon( $user, $username, $password ) {
     if ( empty( $username ) || empty( $password ) ) {
		do_action( 'wp_login_failed', $user );
	}
	return $user;
}
add_filter( 'authenticate', 'scd_auth_signon', 30, 3 );


/**
 * Show local avatar if local avatar exist.
 */
function scd_get_avatar( $avatar = '', $id_or_email, $size = 96, $default = '', $alt = false ) {
	if ( is_numeric( $id_or_email ) )
		$user_id = (int) $id_or_email;
	elseif ( is_string( $id_or_email ) && ( $user = get_user_by( 'email', $id_or_email ) ) )
		$user_id = $user->ID;
	elseif ( is_object( $id_or_email ) && ! empty( $id_or_email->user_id ) )
		$user_id = (int) $id_or_email->user_id;

	if ( empty( $user_id ) )
		return $avatar;

	$local_avatars = get_user_meta( $user_id, 'scd_user_avatar', true );

	if ( empty( $local_avatars ) || empty( $local_avatars['full'] ) )
		return $avatar;

	$size = (int) $size;

	if ( empty( $alt ) )
		$alt = get_the_author_meta( 'display_name', $user_id );

		// Generate a new size
	if ( empty( $local_avatars[$size] ) ) {

		$upload_path      = wp_upload_dir();
		$avatar_full_path = str_replace( $upload_path['baseurl'], $upload_path['basedir'], $local_avatars['full'] );
		$image            = wp_get_image_editor( $avatar_full_path );

		if ( ! is_wp_error( $image ) ) {
			$image->resize( $size, $size, true );
			$image_sized = $image->save();
		}
		$local_avatars[$size] = is_wp_error( $image_sized ) ? $local_avatars[$size] = $local_avatars['full'] : str_replace( $upload_path['basedir'], $upload_path['baseurl'], $image_sized['path'] );
		update_user_meta( $user_id, 'scd_user_avatar', $local_avatars );

	} elseif ( substr( $local_avatars[$size], 0, 4 ) != 'http' ) {
		$local_avatars[$size] = home_url( $local_avatars[$size] );
	}
	$avatar       = "<img alt='" . esc_attr( $alt ) . "' src='" . $local_avatars[$size] . "' class='avatar avatar-{$size}{$author_class} photo' height='{$size}' width='{$size}' />";
	return $avatar;
}
add_filter( 'get_avatar','scd_get_avatar',10,5);






/**
 * Incluce shortcode,validation and admin options scripts.
 */
include('shortcodes.php');
include('include/validation.php');
include('include/scdadmin.php');


/**
 * Submit New Post
 */
function scd_submit_post() {
	$current_user = wp_get_current_user();
	$scd_options = get_option('scd_options');
	$scd_pages = get_option('scd_pages');
	if (!wp_verify_nonce($_POST['post_nonce'], 'scdnonce_field')) {
		$data['success'] = false;
		$data['message'] = "You have failed the security check! Try again";
		die(json_encode($data));
	}
	$check = scd_validate_post($_POST);
	if($check != "") {
		$data['success'] = false;
		$data['message'] = $check;
		die(json_encode($data));
	}
	if($scd_options['instant_publish'] == "" || $scd_options['instant_publish'] == '0') {
		$post_status = "Pending";
	}
	else {
		$post_status = "Publish";
	}
	$post = array(
		'post_author' => $current_user->ID,
		'post_title'     => sanitize_text_field($_POST['post_title']),
		'post_category'  => array($_POST['post_category']),
		'tags_input'     => sanitize_text_field($_POST['post_tags']),
		'post_content' => wp_kses_post($_POST['post_content']),
		'comment_status' => get_option('default_comment_status'),
		'post_status' => $post_status
		);
	$submitpost = wp_insert_post($post, true);
	if (is_wp_error($submitpost)){
		$data['success'] = false;
		$data['message'] = "Something Went wrong";
		die(json_encode($data));
	}
	$data['success'] = true;
	$data['message'] = "Post Submited Successfully";
	$data['redirect'] = get_permalink($scd_pages['dashboard_id']).'?scd_type=editpost&post_id='.$submitpost.'&success';
	die(json_encode($data));
}
add_action('wp_ajax_scd_submit_post', 'scd_submit_post');


/**
 * Update post
 */
function scd_update_post() {
	$current_user = wp_get_current_user();
	$scd_options = get_option('scd_options');
	if($scd_options['author_can_edit'] == "" || $scd_options['author_can_edit'] == '0') {
		$data['success'] = false;
		$data['message'] = "You dont have permission to edit this post";
		die(json_encode($data));
	}
	if (!wp_verify_nonce($_POST['post_nonce'], 'scdnonce_field')) {
		$data['success'] = false;
		$data['message'] = "You have failed the security check! Try again";
		die(json_encode($data));
	}
	$get_post_data = get_post($_POST['post_id'], 'ARRAY_A');
	if($get_post_data['post_author'] != $current_user->ID) {
		$data['success'] = false;
		$data['message'] = "You dont have permission to edit this post";
		die(json_encode($data));
	}
	$check = scd_validate_post($_POST);
	if($check != "") {
		$data['success'] = false;
		$data['message'] = $check;
		die(json_encode($data));
	}
	$post = array(
		'ID' => esc_sql($_POST['post_id']),
		'post_title'     => sanitize_text_field($_POST['post_title']),
		'post_category'  => array($_POST['post_category']),
		'tags_input'     => sanitize_text_field($_POST['post_tags']),
		'post_content' => wp_kses_post($_POST['post_content'])
		);
	$updatepost = wp_update_post($post, true);
	if (is_wp_error($updatepost)){
		$data['success'] = false;
		$data['message'] = "Something Went wrong";
		die(json_encode($data));
	}
	$data['success'] = true;
	$data['message'] = "Post Updated Successfully";
	die(json_encode($data));
}
add_action('wp_ajax_scd_update_post', 'scd_update_post');


/**
 * Delete post
 */
function scd_del_post() {
	$current_user = wp_get_current_user();
	$scd_options = get_option('scd_options');
	if($scd_options['author_can_delete'] == "" || $scd_options['author_can_delete'] == '0') {
		$data['success'] = false;
		$data['message'] = "You dont have permission to delete posts";
		die(json_encode($data));
	}
	if (!wp_verify_nonce($_POST['post_nonce'], 'scddelnonce_field')) {
		$data['success'] = false;
		$data['message'] = "You have failed the security check! Try again";
		die(json_encode($data));
	}
	$result = wp_delete_post($_POST['post_id']);
	if($result) {
		$data['success'] = true;
		$data['message'] = "Post Updated Successfully";
		die(json_encode($data));
	}
	else {
		$data['success'] = false;
		$data['message'] = "Something Went Wrong";
		die(json_encode($data));
	}
}
add_action('wp_ajax_scd_del_post', 'scd_del_post');