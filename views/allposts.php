<?php 
include('header.php');
 ?>
<div class="container-fluid display-table">
    <div class="row display-table-row">
        <div class="col-md-12 col-sm-11 display-table-cell v-align">





        	<div class="panel panel-default">
        		<div class="panel-heading">All Posts</div>
        		<div class="panel-body">

        			<?php 
        			$pageno = isset($_GET['scd_page']) ? $_GET['scd_page'] : 1;
        			$per_page = (isset($scd_options['posts_per_page']) && is_numeric($scd_options['posts_per_page'])) ? $scd_options['posts_per_page'] : 10;
        			$args = array(
        				'post_type' => 'post',
        				'posts_per_page' => $per_page,
        				'paged' => $pageno,
        				'orderby' => 'DESC',
                        'post_status' => 'any',
                        'author' => $current_user->ID
        				);
        			$allpages = new WP_Query( $args );
        			$old_exist = ($pageno * $per_page) < $allpages->found_posts;
        			$new_exist = $pageno > 1; ?>
                    <?php 
                    if($allpages->post_count == 0 ) {
                        $scd_pages = get_option('scd_pages');
                        $link = get_permalink($scd_pages['dashboard_id']) . '?scd_type=newpost';
                        echo '<div style="text-align:center;">You dont have any post! <a href="'.$link.'">Add New Post Here</a></div>';
                    }
                    else { ?>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            while ($allpages->have_posts()) : $allpages->the_post();
                            $pageid = get_the_ID();
                            ?>
                            <tr id="scd-del-row-<?php echo $pageid; ?>">
                                <td>
                                    <?php the_title();  ?>
                                    <div style="font-size: 10px;"><a href="<?php the_permalink();  ?>" target="_blank">View</a> | <a href="?scd_type=editpost&post_id=<?= $pageid ?>">Edit</a> | <a class="scd-del-post" id="<?php echo $pageid; ?>" style="color:red;" href="#">Delete</a></div>
                                </td>
                                <td><?php _e(get_post_status($pageid)) ?></td>
                                <td><?php echo get_the_date(); ?></td>
                            </tr>
                        <?php endwhile;  ?>
                    </tbody>
                </table>
                <?php wp_nonce_field('scddelnonce_field', 'scddelnonce'); ?>
                <div class="">
                    <?php if ($new_exist): ?>
                        <a class="" href="?scd_type=posts&scd_page=<?= ($pageno - 1) ?>">
                            &#10094; Newer Posts</a>
                        <?php endif; ?>
                        <?php if ($old_exist): ?>
                            <a class="pull-right"
                            href="?scd_type=posts&scd_page=<?= ($pageno + 1) ?>">Older Posts
                            &#10095;</a>
                        <?php endif; ?>
                    <div style="clear:both;"></div>
                </div>
                    <?php } 
                    ?>






      </div>
  </div>
</div>
