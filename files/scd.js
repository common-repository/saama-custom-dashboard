jQuery(document).ready(function ($) { 
	$('#scd-update-post').click(function(){
		$("#scd-update-post").attr("disabled", true);
		$('#scd-error-box').hide();
		$('#scd-error-box').empty();
		$('#scd-success-box').hide();
		$('#scd-success-box').empty();
		tinyMCE.triggerSave();
		var scdnonce = $('#scdnonce').val();
		var postID = $('#scd-post-id').val();
		var postTitlte = $('#scd-post-title').val();
		var postContent = $('#scd-post-content').val();
		var category = $('#scd-category').val();
		var tags = $('#scd-tags').val();
		check = validate(postTitlte,postContent,category,tags);
		if(check != "") {
			$('#scd-error-box').show();
			$('#scd-error-box').append(check);
			$('html, body').animate({
				scrollTop: $("#scd-main-container").offset().top
			}, 100);
			$("#scd-update-post").attr("disabled", false);
			return;
		}
		$.ajax({
			type: 'POST',
			url: scdajaxcall.ajaxurl,
			data: {
				action: 'scd_update_post',
				post_title: postTitlte,
				post_content: postContent,
				post_category: category,
				post_tags: tags,
				post_id: postID,
				post_nonce: scdnonce
			},
			success: function (data) {
				var data = $.parseJSON(data);
				if(data.success == true) {
					$('#scd-success-box').show();
					$('#scd-success-box').append(data.message);
					$('html, body').animate({
						scrollTop: $("#scd-main-container").offset().top
					}, 100);
					$("#scd-update-post").attr("disabled", false);
				}
				else {
					$('#scd-error-box').show();
					$('#scd-error-box').append(data.message);
					$('html, body').animate({
						scrollTop: $("#scd-main-container").offset().top
					}, 100);
					$("#scd-update-post").attr("disabled", false);
				}
			},
			error: function (MLHttpRequest, textStatus, errorThrown) {
				alert(errorThrown);
			}
		});
		
	});


	$('#scd-submit-post').click(function(){
		$("#scd-submit-post").attr("disabled", true);
		$('#scd-error-box').hide();
		$('#scd-error-box').empty();
		$('#scd-success-box').hide();
		$('#scd-success-box').empty();
		tinyMCE.triggerSave();
		var scdnonce = $('#scdnonce').val();
		var postTitlte = $('#scd-post-title').val();
		var postContent = $('#scd-post-content').val();
		var category = $('#scd-category').val();
		var tags = $('#scd-tags').val();
		check = validate(postTitlte,postContent,category,tags);
		if(check != "") {
			$('#scd-error-box').show();
			$('#scd-error-box').append(check);
			$('html, body').animate({
				scrollTop: $("#scd-main-container").offset().top
			}, 100);
			$("#scd-submit-post").attr("disabled", false);
			return;
		}
		$.ajax({
			type: 'POST',
			url: scdajaxcall.ajaxurl,
			data: {
				action: 'scd_submit_post',
				post_title: postTitlte,
				post_content: postContent,
				post_category: category,
				post_tags: tags,
				post_nonce: scdnonce
			},
			success: function (data) {
				var data = $.parseJSON(data);
				if(data.success == true) {
					$('#scd-success-box').show();
					$('#scd-success-box').append(data.message);
					$('html, body').animate({
						scrollTop: $("#scd-main-container").offset().top
					}, 100);
					window.location.href = data.redirect;
				}
				else {
					$('#scd-error-box').show();
					$('#scd-error-box').append(data.message);
					$('html, body').animate({
						scrollTop: $("#scd-main-container").offset().top
					}, 100);
					$("#scd-submit-post").attr("disabled", false);
				}
			},
			error: function (MLHttpRequest, textStatus, errorThrown) {
				alert(errorThrown);
			}
		});
		
	});


	$('.scd-del-post').click(function(){
		var postID = $(this).attr('id');
		var scdnonce = $('#scddelnonce').val();
		$('#scd-del-row-'+postID).css({ background: "#ecc1c1" });
		confirmation = confirm("Are you sure?");
		if(!confirmation) {
			$('#scd-del-row-'+postID).css({ background: "#ffffff" });
			return;
		}
		$.ajax({
			type: 'POST',
			url: scdajaxcall.ajaxurl,
			data: {
				action: 'scd_del_post',
				post_id: postID,
				post_nonce: scdnonce
			},
			success: function (data) {
				var data = $.parseJSON(data);
				if(data.success == true) {
					$('#scd-del-row-'+postID).remove();
				}
				else {
					$('#scd-del-row-'+postID).css({ background: "#ffffff" });
					alert(data.message);
				}
			},
			error: function (MLHttpRequest, textStatus, errorThrown) {
				alert(errorThrown);
			}
		});
		
	});


	function substr_count(string, sub_string) {
		var regex = new RegExp(sub_string, 'g');
		if (!string.match(regex) || !string || !sub_string)
			return 0;
		var count = string.match(regex);
		return count.length;
	}

	function str_word_count(string) {
		if (!string.length)
			return 0;
		string = string.replace(/(^\s*)|(\s*$)/gi, "");
		string = string.replace(/[ ]{2,}/gi, " ");
		string = string.replace(/\n /, "\n");
		return string.split(' ').length;
	}

	function count_tags(string) {
		if (!string.length)
			return 0;
		return string.split(',').length;
	}

	function validate(title, content, category, tags) {
		var error = "";
		if(title == "" || (str_word_count(title) < scd_options.min_words_title || str_word_count(title) > scd_options.max_words_title)) {
			error += "Title should be between "+scd_options.min_words_title+" to "+scd_options.max_words_title+" words.</br>";
		}
		content_striped = content.replace(/(<([^>]+)>)/ig, "");
		if(content == "" || (str_word_count(content_striped) < scd_options.min_words_content || str_word_count(content_striped) > scd_options.max_words_content)) {
			error += "Post Content should be between "+scd_options.min_words_content+" to "+scd_options.max_words_content+" words.</br>";
		}
		if (substr_count(content, '</a>') > scd_options.max_links) {
			error += "Your post contains more than  "+scd_options.max_links+" links.</br>";
		}
		if(category == "" || category == "None") {
			error += "Select Category.</br>";
		}
		if (tags == '' || count_tags(tags) < scd_options.min_tags) {
			error += "Atleast "+scd_options.min_tags+" tags required.</br>";
		}
		if (count_tags(tags) > scd_options.max_tags) {
			error += "Your post contains more than "+scd_options.max_tags+" tags.</br>";
		}
		return error;
	}


	$('#scd_user_login').addClass( "form-control" );
	$('#scd_user_pass').addClass( "form-control" );



});