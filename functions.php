<?php
    add_theme_support( 'post-formats', array('aside', 'gallery', 'link', 'image', 'quote', 'status', 'audio', 'chat', 'video')); // Add 3.1 post format theme support.
	add_theme_support( 'post-thumbnails' );
	
	function create_post_type() {
		register_post_type( 'apps',
			array(
				'labels' => array(
					'name' => __( 'Apps' ),
					'singular_name' => __( 'App' ),
					'add_new_item' => __('Add new App'),
					'add_new' => _x('Add New', 'app'),
					'edit_item' => __('Edit App'),
					'new_item' => __('New App'),
					'all_items' => __('All apps'),
					'view_item' => __('View App'),
					'search_items' => __('Search Apps'),
					'not_found' =>  __('No Apps found'),
					'not_found_in_trash' => __('No Apps found in trash'),
					'parent_item_colon' => '',
					'menu_name' => 'Apps'
				),
				'public' => true,
				'has_archive' => true,
				'menu_position'=>5,
				'supports' => array( 'title', 'editor', 'thumbnail' )
			)
		);
	}
	add_action( 'init', 'create_post_type' );
	
	function custom_upload_mimes ( $existing_mimes=array() ) {

		// add your ext => mime to the array
		$existing_mimes['plist'] = 'application/xml';

		// add as many as you like

		// and return the new full result
		return $existing_mimes;
	}
	add_filter('upload_mimes', 'custom_upload_mimes');

function add_custom_meta_boxes() {

	// Define the custom attachments for apps
	add_meta_box(
		'wp_custom_plist_attachment',
		'.PLIST',
		'wp_custom_plist_attachment',
		'apps',
		'side'
	);

} // end add_custom_meta_boxes
add_action('add_meta_boxes', 'add_custom_meta_boxes');


function wp_custom_plist_attachment() {
	wp_nonce_field(plugin_basename(__FILE__), 'wp_custom_plist_attachment_nonce');
	
	$html = '<p class="description">';
		$html .= 'Upload your .PLIST here.';
	$html .= '</p>';
	$html .= '<input type="file" id="wp_custom_plist_attachment" name="wp_custom_plist_attachment" value="" size="25">';
	
	echo $html;

} // end wp_custom_plist_attachment


function save_custom_meta_data($id) {

	/* --- security verification --- */
	if(!wp_verify_nonce($_POST['wp_custom_plist_attachment_nonce'], plugin_basename(__FILE__))) {
	  return $id;
	} // end if
	  
	if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
	  return $id;
	} // end if
	  
	if('page' == $_POST['post_type']) {
	  if(!current_user_can('edit_page', $id)) {
	    return $id;
	  } // end if
	} else {
   		if(!current_user_can('edit_page', $id)) {
	    	return $id;
	   	} // end if
	} // end if
	/* - end security verification - */
	
	// Make sure the file array isn't empty
	if(!empty($_FILES['wp_custom_plist_attachment']['name'])) {
		
		// Setup the array of supported file types. In this case, it's just PDF.
		$supported_types = array('application/ipa');
		
		// Get the file type of the upload
		$arr_file_type = wp_check_filetype(basename($_FILES['wp_custom_plist_attachment']['name']));
		$uploaded_type = $arr_file_type['type'];
		
		// Check if the type is supported. If not, throw an error.
/* 		if(in_array($uploaded_type, $supported_types)) { */

			// Use the WordPress API to upload the file
			$upload = wp_upload_bits($_FILES['wp_custom_plist_attachment']['name'], null, file_get_contents($_FILES['wp_custom_plist_attachment']['tmp_name']));
	
			if(isset($upload['error']) && $upload['error'] != 0) {
				wp_die('There was an error uploading your file. The error is: ' . $upload['error']);
			} else {
				add_post_meta($id, 'wp_custom_plist_attachment', $upload);
				update_post_meta($id, 'wp_custom_plist_attachment', $upload);		
			} // end if/else

/*
		} else {
			wp_die("The file type that you've uploaded is not an IPA.");
		} // end if/else
*/
		
	} // end if
	
} // end save_custom_meta_data
add_action('save_post', 'save_custom_meta_data');

function update_edit_form() {
    echo ' enctype="multipart/form-data"';
} // end update_edit_form
add_action('post_edit_form_tag', 'update_edit_form');

function addUploadMimes($mimes) {
	$mimes = array_merge($mimes, array('ipa' => 'application/octet-stream'));
     return $mimes;
}
add_filter('upload_mimes', 'addUploadMimes');

?>