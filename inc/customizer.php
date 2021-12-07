<?php

namespace Boilerplate;

if ( class_exists( 'WP_Customize_Panel' ) ) {

	class WP_Customize_Panel extends \WP_Customize_Panel {

		public $panel;

		public $type = 'boilerplate_panel';

		public function json() {

			$array                   = wp_array_slice_assoc( (array) $this, array( 'id', 'description', 'priority', 'type', 'panel' ) );
			$array['title']          = html_entity_decode( $this->title, ENT_QUOTES, get_bloginfo( 'charset' ) );
			$array['content']        = $this->get_content();
			$array['active']         = $this->active();
			$array['instanceNumber'] = $this->instance_number;

			return $array;

		}

	}

}

if ( class_exists( 'WP_Customize_Section' ) ) {

	class WP_Customize_Section extends \WP_Customize_Section {

		public $section;

		public $type = 'boilerplate_section';

		public function json() {

			$array                   = wp_array_slice_assoc( (array) $this, array( 'id', 'description', 'priority', 'panel', 'type', 'description_hidden', 'section' ) );
			$array['title']          = html_entity_decode( $this->title, ENT_QUOTES, get_bloginfo( 'charset' ) );
			$array['content']        = $this->get_content();
			$array['active']         = $this->active();
			$array['instanceNumber'] = $this->instance_number;

			if ( $this->panel ) {
				$array['customizeAction'] = sprintf( 'Customizing &#9656; %s', esc_html( $this->manager->get_panel( $this->panel )->title ) );
			} else {
				$array['customizeAction'] = 'Customizing';
			}

			return $array;

		}

	}

}

/**
 * Add postMessage support
 *
 * @param customize $wp_customize the customizer args.
 */
function customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
}
add_action( 'customize_register', __NAMESPACE__ . '\\customize_register' );

/**
 * Customizer for colors.
 *
 * @param customize $wp_customize the customizer args.
 */
function customizer( $wp_customize ) {
	$wp_customize->add_panel(
		new WP_Customize_Panel(
			$wp_customize,
			'boilerplate_panel',
			array(
				'title'       => __( 'Boilerplate', 'boilerplate' ),
				'description' => __( 'Settings for the theme, such as colors.', 'boilerplate' ),
				'priority'    => 100,
			)
		)
	);
	$wp_customize->add_panel(
		new WP_Customize_Panel(
			$wp_customize,
			'boilerplate_colors',
			array(
				'title'       => __( 'Colors', 'boilerplate' ),
				'description' => '',
				'panel'       => 'boilerplate_panel',
				'priority'    => 100,
			)
		)
	);

	$colors   = get_default_colors();
	$settings = array(
		'boilerplate_colors_brand'  => array(
			'title'    => __( 'Brand', 'boilerplate' ),
			'priority' => 1,
			'panel'    => 'boilerplate_colors',
			'colors'   => array(
				'primary'   => __( 'Primary', 'boilerplate' ),
				'secondary' => __( 'Secondary', 'boilerplate' ),
				'light'     => __( 'Light', 'boilerplate' ),
				'dark'      => __( 'Dark', 'boilerplate' ),
			),
		),
		'boilerplate_colors_layout' => array(
			'title'    => __( 'Layout', 'boilerplate' ),
			'priority' => 1,
			'panel'    => 'boilerplate_colors',
			'colors'   => array(
				'text'              => __( 'Text color', 'boilerplate' ),
				'background'        => __( 'Background color', 'boilerplate' ),
				'footer_background' => __( 'Footer background color', 'boilerplate' ),
				'footer_text'       => __( 'Footer text color', 'boilerplate' ),
			),
		),
	);

	foreach ( $settings as $section_id => $section ) {
		$wp_customize->add_section( $section_id, $section );

		foreach ( $section['colors'] as $name => $label ) {
			// Add color picker setting.
			$wp_customize->add_setting(
				'boilerplate_' . $name . '_color',
				array(
					'default'           => array_key_exists( $colors[ $name ], $colors ) ? $colors[ $colors[ $name ] ] : $colors[ $name ],
					'sanitize_callback' => 'sanitize_hex_color',
				)
			);
			// Add color picker control.
			$wp_customize->add_control(
				new \WP_Customize_Color_Control(
					$wp_customize,
					'boilerplate_' . $name,
					array(
						'label'    => $label,
						'section'  => $section_id,
						'settings' => 'boilerplate_' . $name . '_color',
					)
				)
			);
		}
	}

	// Add image setting to customizer.
	/* $wp_customize->add_setting(
		'boilerplate_alt_logo',
		array(
			'sanitize_callback' => 'absint',
			'validate_callback' => __NAMESPACE__ . '\\validate_image',
		)
	);
	$wp_customize->add_control(
		new \WP_Customize_Media_Control(
			$wp_customize,
			'boilerplate_alt_logo',
			array(
				'label'     => __( 'Alternative logo', 'boilerplate' ),
				'section'   => 'title_tagline',
				'settings'  => 'boilerplate_alt_logo',
				'mime_type' => 'image',
				'priority'  => 8,
			)
		)
	); */

}
add_action( 'customize_register', __NAMESPACE__ . '\\customizer' );

/**
 * HEX Color sanitization callback.
 *
 * - Sanitization: hex_color
 * - Control: text, WP_Customize_Color_Control
 *
 * Note: sanitize_hex_color_no_hash() can also be used here, depending on whether
 * or not the hash prefix should be stored/retrieved with the hex color value.
 *
 * @see sanitize_hex_color() https://developer.wordpress.org/reference/functions/sanitize_hex_color/
 * @link sanitize_hex_color_no_hash() https://developer.wordpress.org/reference/functions/sanitize_hex_color_no_hash/
 *
 * @param string               $hex_color HEX color to sanitize.
 * @param WP_Customize_Setting $setting   Setting instance.
 * @return string The sanitized hex color if not null; otherwise, the setting default.
 */
function sanitize_hex_color( $hex_color, $setting ) {
	// Sanitize $input as a hex value without the hash prefix.
	$hex_color = sanitize_hex_color_no_hash( $hex_color );
	// If $input is a valid hex value, return it; otherwise, return the default.
	return ( ! is_null( $hex_color ) ? $hex_color : $setting->default );
}

/**
 * Sanitize INTs.
 * Return the default value if sanitation fails.
 *
 * @param int                  $number the number to sanitize.
 * @param WP_Customize_Setting $setting Setting instance.
 */
function sanitize_number_absint( $number, $setting ) {
	// Ensure $number is an absolute integer (whole number, zero or greater).
	$number = absint( $number );

	// If the input is an absolute integer, return it; otherwise, return the default.
	return ( $number ? $number : $setting->default );
}

/**
 * Sanitizes Image Upload.
 *
 * @param string $input potentially dangerous data.
 */
function sanitize_image( $input ) {
	$filetype = wp_check_filetype( $input );
	if ( $filetype['ext'] && wp_ext2type( $filetype['ext'] ) === 'image' ) {
		return esc_url( $input );
	}
	return '';
}

/**
 * Validate that an image file has been uploaded/selected.
 *
 * @param object $validity the object containing messages etc.
 * @param int    $value the image id.
 */
function validate_image( $validity, $value ) {

	// Get the url of the image.
	$file = wp_get_attachment_url( $value );

	// Return an array with file extension and mime_type.
	$filetype = wp_check_filetype( $file );

	if ( $value && ( ! $filetype['ext'] || validate_ext( $filetype['ext'] ) !== 'image' ) ) {
		// If a valid image file extension is not found, instruct user to choose appropriate image.
		$validity->add( 'not_valid', __( 'Please choose a valid image type', 'boilerplate' ) );
	}
	return $validity;
}

/**
 * Function that somewhat extends wp_ext2type to pass SVG file.
 * See https://developer.wordpress.org/reference/functions/wp_ext2type/
 * Copies that function and adds image => svg as allowed extenion.
 *
 * @param string $ext the extension.
 */
function validate_ext( $ext ) {
	$ext  = strtolower( $ext );
	$exts = wp_get_ext_types(); // Get WP defined extension types.
	array_push( $exts['image'], 'svg' ); // Push SVG as image type (WordPress does not allow SVG at all, anywhere, by default).

	foreach ( $exts as $type => $exts ) {
		if ( in_array( $ext, $exts, true ) ) {
			return $type;
		}
	}
}
