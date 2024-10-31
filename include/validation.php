<?php 
function scd_validate_post($post) {
	$error = "";
	$scd_options = get_option('scd_options');
	if($post['post_title'] == "" || $post['post_content'] == "" || $post['post_category'] == "" || $post['post_tags'] == "") {
		$error .= "All fields required.</br>";
		return $error;
	}
	$seperate_tags = explode(',', $post['post_tags']);
	$content_stripped = strip_tags($post['post_content']);
	if(str_word_count($post['post_title']) < $scd_options['min_words_title'] || str_word_count($post['post_title']) > $scd_options['max_words_title']) {
		$error .= "Title should be between ".$scd_options['min_words_title']." to ".$scd_options['max_words_title']." words.</br>";
	}
	if(str_word_count($content_stripped) < $scd_options['min_words_content'] || str_word_count($content_stripped) > $scd_options['max_words_content']) {
		$error .= "Post Content should be between ".$scd_options['min_words_content']." to ".$scd_options['max_words_content']." words.</br>";
	}
	if (substr_count($post['post_content'], '</a>') > $scd_options['max_links']) {
		$error .= "Post should not contains more than ".$scd_options['max_links']." links.</br>";
	}
	if(count($seperate_tags) < $scd_options['min_tags']) {
		$error .= "Atleast ".$scd_options['min_tags']." tags required.</br>";
	}
	if(count($seperate_tags) > $scd_options['max_tags']) {
		$error .= "Post has more than ".$scd_options['max_tags']." tags.</br>";
	}
	return $error;
}

 ?>