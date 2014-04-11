$(document).ready(function() {
	
	// Define page details div
	$details = $('div#page-details');
	
	// Ajax upload form
	$('.ajax-form-upload').ajaxForm({
		beforeSubmit: function showRequest(formData) { 	
			var thumbnailId = formData[2].value;
		
			// Fix the width and height of the containing div and load a ajax loader img	
			$('li#'+thumbnailId+' div.thumbnail').width($('li#'+thumbnailId+' .thumbnail img').width());
			$('li#'+thumbnailId+' div.thumbnail').height($('li#'+thumbnailId+' .thumbnail img').height());
			$('li#'+thumbnailId+' div.thumbnail img').attr('src', BASE_URL + ADDONPATH + 'modules/images/img/ajax-loader.gif');
		},
		complete: function(xhr) {
			// Get the data from responseText
			var data = jQuery.parseJSON(xhr.responseText);
			
			// Load the details box in
			$details.load(SITE_URL + 'admin/images/ajax_page_images/' + data.page);
		}
	}); 
	
	// Delete image
	$('a#delete-image-button').live("click", function(e){
		
		// Fix the width and height of the containing div and load a ajax loader img
		$(this).parent().find('div.thumbnail').width($(this).parent().find('img').width());
		$(this).parent().find('div.thumbnail').height($(this).parent().find('img').height());
		$(this).parent().find('img').attr('src', BASE_URL + ADDONPATH + 'modules/images/img/ajax-loader.gif');
		
		// Get the data from the button
		var data = $(this).data();
		
		// If there is no image, there is no need to delete it
		if(data.image != '' && data.thumnail != '') {
			// Delete the image
			$.post(SITE_URL + 'admin/images/ajax_delete_image/', {
				thumbnail_id: data.thumbnail_id,
				image_file_id: data.image_file_id,
				thumbnail_file_id: data.thumbnail_file_id,
				page: data.page,
				image: data.image,
				thumbnail: data.thumbnail
			},
			function() {
				// Load the details box in
				$details.load(SITE_URL + 'admin/images/ajax_page_images/' + data.page);
			});
		} else {
			// Load the details box in
			$details.load(SITE_URL + 'admin/images/ajax_page_images/' + data.page);	
		}
		return false;
	});  

});