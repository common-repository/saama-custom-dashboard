<?php 
include('header.php');
 ?>
 <div class="container-fluid display-table">
    <div class="row display-table-row">
        <div class="col-md-12 col-sm-11 display-table-cell v-align">





        	<div class="panel panel-default">
        		<div class="panel-heading">Dashboard</div>
        		<div class="panel-body">

                <div class="row">

                    <div class="col-sm-6 scd-dashboard-pg">
                        <span class="dashicons dashicons-admin-post"></span><a href="<?php echo get_permalink($scd_pages['dashboard_id']) . '?scd_type=posts' ?>">All Posts</a>
                    </div>
                    <div class="col-sm-6 scd-dashboard-pg">
                        <span class="dashicons dashicons-plus"></span><a href="<?php echo get_permalink($scd_pages['dashboard_id']) . '?scd_type=newpost' ?>">Add New Post</a>
                    </div>
                    <div class="col-sm-6 scd-dashboard-pg">
                        <span class="dashicons dashicons-id"></span><a href="<?php echo get_permalink($scd_pages['dashboard_id']) . '?scd_type=editprofile' ?>">Edit Profile</a>
                    </div>
                    <div class="col-sm-6 scd-dashboard-pg">
                        <span class="dashicons dashicons-update"></span><a href="<?php echo get_permalink($scd_pages['dashboard_id']) . '?scd_type=avatarsetting' ?>">Change Avatar</a>
                    </div>
                    <div class="col-sm-6 scd-dashboard-pg">
                        <span class="dashicons dashicons-admin-network"></span><a href="<?php echo get_permalink($scd_pages['dashboard_id']) . '?scd_type=changepassword' ?>">Change Password</a>
                    </div>
                    <div class="col-sm-6 scd-dashboard-pg">
                        <span class="dashicons dashicons-migrate"></span><a href="<?php echo wp_logout_url( get_permalink($scd_pages['dashboard_id'])); ?>">Logout</a>
                    </div>
                </div>


        		</div>
            </div>






        </div>
    </div>
</div>
