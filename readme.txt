==Saama Custom Dashboard==
Contributors: usama12
Tags: guest posting, custom registration, custom login, restrict wp_admin, membership, custom dashboard, register writers, guest authors, frontend publishing, accept guest posts, frontend post submission, frontend manage posts, frontend edit profile, local avatars, upload avatar, authors
Donate link: http://saamaweb.com/
Requires at least: 4.1
Tested up to: 4.8
Stable tag: 2.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Custom dashbaord for guest authors, Authors can register, login, manage their posts, profiles and upload avatars directly from from frontend without wp-admin access.



== Description ==
This plugin allow your user/guest authors to directly register from frontend and submit post, Guest authors can edit, delete their posts. Autors can also edit therir profiles, change password and upload avatars directly from frontend without any access to admin area.

Only guest authors will be able to access frontend dashboard.

In the WordPress admin area you can handle the following:
* Enable/Disable new users registration
* Enable/Disbale Instant Publishing (Post need review from Administrator or editor before publishing)
* Enable/Disbale Media Access to Guest authors (Guest authors wont be able to insert image if media access is disbaled)
* Enable/Disbale upload local avatars
* Enable/Disable Post delete from frontend
* Enable/Disable Post Edit from frontend
* Specify maximum words allowed and minimum words required for title and content
* Specify maximum links allowed in post content


**Usage:**
3 pages and a new role called "Guest Author" will be created automatically with required shortcodes and capanilities on plugin activation.

1) Login Page
shortcode [scd_login]
2) Registration Page
shortcode [scd_registration]
3) Dashboard Page
shortcode [scd_dashboard]
3) Reset Password
shortcode [scd_password_reset]



== Installation ==

1. Use the WordPress plugin installer to upload the plugin. Alternatively you can manually create a new folder called 'SaamaCustomDashboard' in the `/wp-content/plugins/` directory and upload all the files there.
2. Activate the plugin from the 'Plugins' menu in WordPress
3. All the pages with required shortcodes will be created automatically with the activation of the plugin. 
youdomaina/scd_login , yourdomain/scd_registration and yourdomain/scd_dashboard will be Login, registration and dashboard links respectively.


== Screenshots ==
1. Custom Dashboard Control Panel
2. Login Page
3. Registration Page
4. Dashboard Main
5. See All Posts
6. Add New Post
7. Update Avatar
8. Custom Toolbar for guest author
9. Edit Profile





== Changelog ==
= 1.0 =
* Initial release.
= 2.0 =
* Added Forgot password Functionality
