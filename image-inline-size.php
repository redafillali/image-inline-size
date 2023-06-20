<?php
/*
Plugin Name: Image Inline Size
Plugin URI: https://github.com/redafillali/image-inline-size.git
Description: This plugin adds inline sizes to images in post content to improve CLS (Cumulative Layout Shift) for lazy-loaded images.
Version: 1.0
Author: Reda El Fillali
Author URI: https://github.com/redafillali
Text Domain: image-inline-size
Domain Path: /languages
*/

function add_image_inline_size($content) {
	// Define a callback function to handle each match.
	$callback = function($matches) {
		// Get ID from the image class wp-image-386332
		preg_match('/wp-image-([0-9]+)/i', $matches[0], $class_id);
		// Check if we successfully got an ID.
		if (isset($class_id[1])) {
			// Get attachment meta data.
			$attach_data = wp_get_attachment_metadata($class_id[1]);
			// Check if we got valid attachment data.
			if (is_array($attach_data) && isset($attach_data['width']) && isset($attach_data['height'])) {
				// Get image width and height.
				$image_width = $attach_data['width'];
				$image_height = $attach_data['height'];
				// Add inline style to the image.
				return str_replace('wp-image-' . $class_id[1].' size-full', 'wp-image-' . $class_id[1] . ' size-full" style="width:' . $image_width . 'px;height:' . $image_height . 'px;', $matches[0]);
			}
		}
		// If we couldn't add the style, return the original string.
		return $matches[0];
	};
	// Apply the callback to each image in the post content.
	$content = preg_replace_callback('/<img[^>]+>/i', $callback, $content);
	return $content;
}

add_filter('the_content', 'add_image_inline_size');
