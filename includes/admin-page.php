<?php
/**
 * Admin init
 *
 * @since 1.0
 */
function spin_image_gallery_admin_init() {
	register_setting( 'media', 'spin-image-gallery', 'spin_image_gallery_settings_sanitize' );

	// settings
	add_settings_field( 'header', '<h3 class="title">' . __( 'Spin Image Gallery', 'spin-image-gallery' ) . '</h3>', 'spin_image_gallery_header_callback', 'media', 'default' );
	add_settings_field( 'lightbox', __( 'Lightbox', 'spin-image-gallery' ), 'spin_image_gallery_lightbox_callback', 'media', 'default' );
	add_settings_field( 'post-types', __( 'Post Types', 'spin-image-gallery' ), 'spin_image_gallery_post_types_callback', 'media', 'default' );
}
add_action( 'admin_init', 'spin_image_gallery_admin_init' );

/**
 * Blank header callback
 * @since 1.0.6
 */
function spin_image_gallery_header_callback() {}

/**
 * Lightbox callback
 *
 * @since 1.0
 */
function spin_image_gallery_lightbox_callback() {

	// default option when settings have not been saved
	$defaults['lightbox'] = 'prettyphoto';
	
	$settings = (array) get_option( 'spin-image-gallery', $defaults );

	$lightbox = esc_attr( $settings['lightbox'] );
?>
	<select name="spin-image-gallery[lightbox]">
		<?php foreach ( spin_image_gallery_lightbox() as $key => $label ) { ?>
			<option value="<?php echo $key; ?>" <?php selected( $lightbox, $key ); ?>><?php echo $label; ?></option>
		<?php } ?>
	</select>

	<?php

}

/**
 * Post Types callback
 *
 * @since 1.0
 */

function spin_image_gallery_post_types_callback() {

	// post and page defaults
	$defaults['post_types']['post'] = 'on';
	$defaults['post_types']['page'] = 'on';

	$settings = (array) get_option( 'spin-image-gallery', $defaults );

?>
		<?php foreach ( spin_image_gallery_get_post_types() as $key => $label ) {

		$post_types = isset( $settings['post_types'][ $key ] ) ? esc_attr( $settings['post_types'][ $key ] ) : '';

?>
		<p>
			<input type="checkbox" id="<?php echo $key; ?>" name="spin-image-gallery[post_types][<?php echo $key; ?>]" <?php checked( $post_types, 'on' ); ?>/><label for="<?php echo $key; ?>"> <?php echo $label; ?></label>
		</p>
		<?php } ?>
	<?php

}

/**
 * Sanitization
 *
 * @since 1.0
 */
function spin_image_gallery_settings_sanitize( $input ) {

	// Create our array for storing the validated options
	$output = array();

	// lightbox
	$valid = spin_image_gallery_lightbox();

	if ( array_key_exists( $input['lightbox'], $valid ) )
		$output['lightbox'] = $input['lightbox'];

	// post types
	$post_types = isset( $input['post_types'] ) ? $input['post_types'] : '';

	// only loop through if there are post types in the array
	if ( $post_types ) {
		foreach ( $post_types as $post_type => $value )
			$output[ 'post_types' ][ $post_type ] = isset( $input[ 'post_types' ][ $post_type ] ) ? 'on' : '';	
	}
	


	return apply_filters( 'spin_image_gallery_settings_sanitize', $output, $input );

}

/**
 * Action Links
 *
 * @since 1.0
 */
function spin_image_gallery_plugin_action_links( $links ) {

	$settings_link = '<a href="' . admin_url( 'options-media.php' ) . '">'. __( 'Settings', 'spin-image-gallery' ) .'</a>';
	array_unshift( $links);

	return $links;
}