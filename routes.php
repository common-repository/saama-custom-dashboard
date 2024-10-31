<?php
$display = isset($_GET['scd_type']) ? $_GET['scd_type'] : 'dashboard';
if($display == "dashboard") {
	include(dirname(__FILE__) . '/views/index.php');
}
elseif($display == "posts") {
	include(dirname(__FILE__) . '/views/allposts.php');
}
elseif($display == "editpost") {
	include(dirname(__FILE__) . '/views/editpost.php');
}
elseif($display == "newpost") {
	include(dirname(__FILE__) . '/views/newpost.php');
}
elseif($display == "editprofile") {
	include(dirname(__FILE__) . '/views/editprofile.php');
}
elseif($display == "avatarsetting") {
	include(dirname(__FILE__) . '/views/avatarsetting.php');
}
elseif($display == "changepassword") {
	include(dirname(__FILE__) . '/views/changepassword.php');
}
else{
	include(dirname(__FILE__) . '/views/index.php');
}

 ?>