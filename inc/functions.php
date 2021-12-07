<?php
/**
 * Custom theme functions.
 *
 * @package batteryloop
 * @since   0.0.0
 */

namespace Boilerplate;

// Define needed constants.
if ( ! defined( 'STRING' ) ) {
	define( 'STRING', 'STRING' );
}

/**
 * A add_action wrapper function for namespace prepend.
 *
 * @param string|array $hook            Hook name.
 * @param mixed        $callback        Callback function.
 * @param integer      $priority        Priority.
 * @param integer      $accepted_args   Number of accepted arguments.
 *
 * @return void
 */
function add_action( $hook, $callback = null, $priority = 10, $accepted_args = 1 ) {
	if ( ! isset( $callback ) ) {
		$callback = $hook;
	}
	if ( is_string( $callback ) && ! function_exists( $callback ) ) {
		$callback = __NAMESPACE__ . '\\' . $callback;
	}
	if ( is_array( $hook ) ) {
		foreach ( $hook as $h ) {
			\add_action( $h, $callback, $priority, $accepted_args );
		}
	} else {
		\add_action( $hook, $callback, $priority, $accepted_args );
	}
}

/**
 * A add_filter wrapper function for namespace prepend.
 *
 * @param string|array $hook            Hook name.
 * @param mixed        $callback        Callback function.
 * @param integer      $priority        Priority.
 * @param integer      $accepted_args   Number of accepted arguments.
 *
 * @return void
 */
function add_filter( $hook, $callback = null, $priority = 10, $accepted_args = 1 ) {
	if ( ! isset( $callback ) ) {
		$callback = $hook;
	}
	if ( is_string( $callback ) && ! function_exists( $callback ) ) {
		$callback = __NAMESPACE__ . '\\' . $callback;
	}
	if ( is_array( $hook ) ) {
		foreach ( $hook as $h ) {
			\add_filter( $h, $callback, $priority, $accepted_args );
		}
	} else {
		\add_filter( $hook, $callback, $priority, $accepted_args );
	}
}

function apply_filters() {
	$args  = func_get_args();
	$hook  = array_shift( $args );
	$value = array_shift( $args );
	if ( is_array( $hook ) ) {
		foreach ( $hook as $h ) {
			$value = call_user_func_array( 'apply_filters', array_merge( array( $h, $value ), $args ) );
		}
		return $value;
	} else {
		return call_user_func_array( 'apply_filters', array_merge( array( $hook, $value ), $args ) );
	}
}

/**
 * A add_shortcode wrapper for simplified shortcode registration.
 *
 * @param string $name   Shortcode name.
 * @param mixed  $fn     Render callback function.
 *
 * @return void
 */
function add_shortcode( $name, $fn = null ) {
	if ( ! isset( $fn ) || ! function_exists( $fn ) ) {
		$fn = __NAMESPACE__ . '\\shortcode_' . str_replace( '-', '_', $name );
	}
	if ( function_exists( $fn ) ) {
		\add_shortcode( $name, $fn );
	}
}

/**
 * Function to sanitize classes.
 *
 * @param string $classes A string of classes.
 */
function sanitize_class( $classes ) {
	if ( $classes ) {
		if ( is_string( $classes ) ) {
			$classes = explode( ' ', $classes );
		}
		$classes = array_unique( array_filter( $classes ) );
		foreach ( $classes as $class ) {
			$class = sanitize_html_class( $class );
		}
		return implode( ' ', $classes );
	}
	return '';
}

/**
 * Wrapper function for wp_get_attachment_image for nice svg output
 *
 * @param int    $attachment_id The attachment ID.
 * @param string $size          The desired size.
 * @param bool   $icon          Treat as icon.
 * @param any    $attr          Image attributes.
 * @param bool   $force_img     Force image to use img-tag.
 */
function get_attachment_image( $attachment_id, $size = 'thumbnail', $icon = false, $attr = '', $force_img = false ) {
	global $is_IE;
	$mime = get_post_mime_type( $attachment_id );
	if ( ! $is_IE && 'image/svg+xml' === $mime && ! $force_img ) {
		$img = wp_get_attachment_image_src( $attachment_id, 'full' );
		if ( $img && $img[0] ) {
			return Icons::add( $attachment_id, $img[0], array( 'wrapp' => ! ! $icon ) );
		}
	}
	return wp_get_attachment_image( $attachment_id, $size, $icon, $attr );
}

function get_attachment_icon( $attachment_id, $attr = null ) {
	global $is_IE;
	$mime = get_post_mime_type( $attachment_id );
	if ( ! $is_IE && 'image/svg+xml' === $mime ) {
		$img = wp_get_attachment_image_src( $attachment_id, 'full' );
		if ( $img && $img[0] ) {
			return Icons::add( $attachment_id, $img[0], $attr );
		}
	}
	return '';
}

/**
 * Converts an accosiative array to an html attributes string.
 *
 * @param array $attr    The array to convert.
 *
 * @return string
 */
function array_to_attr( $attr, $remove_empty = true ) {
	$attr_string = '';
	foreach ( $attr as $key => $val ) {
		$key = apply_filters( 'array_to_attr/key', $key, $val );
		if ( is_array( $val ) ) {
			$val = array_filter( $val );
		}
		if ( empty( $key ) || ! isset( $val ) || ( $remove_empty && empty( $val ) ) ) {
			continue;
		}
		$val          = apply_filters( 'array_to_attr/value', $val, $key );
		$attr_string .= ' ' . $key . '="' . esc_attr( $val ) . '"';
	}
	return $attr_string;
}

function merge_args() {
	$args   = func_get_args();
	$return = array();
	$nulls  = array();
	foreach ( $args as $i => $arr ) {
		foreach ( $arr as $key => $value ) {
			if ( ! array_key_exists( $key, $return ) ) {
				$return[ $key ] = $value;
			}
		}
	}
	return $return;
}

function merge_args_recursive() {
	$args   = func_get_args();
	$return = array();
	$arrays = array();
	foreach ( $args as $i => $arr ) {
		foreach ( $arr as $key => $value ) {
			if ( is_array( $value ) ) {
				if ( ! array_key_exists( $key, $arrays ) ) {
					$arrays[ $key ] = array( $value );
				} else {
					$arrays[ $key ][] = $value;
				}
			} elseif ( ! array_key_exists( $key, $return ) ) {
				$return[ $key ] = $value;
			}
		}
	}
	foreach ( $arrays as $key => $values ) {
		$return[ $key ] = call_user_func_array( __FUNCTION__, $values );
	}
	return $return;
}

/**
 * Undocumented function
 *
 * @return void
 */
function split_args() {
	$args  = func_get_args();
	$all   = array_shift( $args );
	$split = array();
	foreach ( $args as $key ) {
		if ( array_key_exists( $key, $all ) ) {
			$split[ $key ] = $all[ $key ];
		}
	}
	return $split;
}

/**
 * Increases or decreases the brightness of a color by a percentage of the current brightness.
 *
 * @source https://stackoverflow.com/questions/3512311/how-to-generate-lighter-darker-color-with-php Stolen from SO.
 *
 * @param   string $hex      Supported formats: `#FFF`, `#FFFFFF`, `FFF`, `FFFFFF`.
 * @param   float  $percent  A number between -1 and 1. E.g. 0.3 = 30% lighter; -0.4 = 40% darker.
 *
 * @return  string
 */
function color_brightness( $hex, $percent ) {
	$hex = ltrim( $hex, '#' );

	if ( 3 === strlen( $hex ) ) {
		$hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
	}

	$hex = array_map( 'hexdec', str_split( $hex, 2 ) );

	foreach ( $hex as & $color ) {
		$limit  = $percent < 0 ? $color : 255 - $color;
		$amount = ceil( $limit * $percent );

		$color = str_pad( dechex( $color + $amount ), 2, '0', STR_PAD_LEFT );
	}

	return '#' . implode( $hex );
}

/**
 * Undocumented function
 *
 * @param [type]  $hex
 * @param boolean $alpha
 * @return void
 */
function hex_to_rgb( $hex, $alpha = null, $return_format = STRING ) {
	$hex      = str_replace( '#', '', $hex );
	$length   = strlen( $hex );
	$rgb['r'] = hexdec( 6 === $length ? substr( $hex, 0, 2 ) : ( 3 === $length ? str_repeat( substr( $hex, 0, 1 ), 2 ) : 0 ) );
	$rgb['g'] = hexdec( 6 === $length ? substr( $hex, 2, 2 ) : ( 3 === $length ? str_repeat( substr( $hex, 1, 1 ), 2 ) : 0 ) );
	$rgb['b'] = hexdec( 6 === $length ? substr( $hex, 4, 2 ) : ( 3 === $length ? str_repeat( substr( $hex, 2, 1 ), 2 ) : 0 ) );
	if ( isset( $alpha ) ) {
		$rgb['a'] = floatval( $alpha );
	}
	switch ( $return_format ) {
		case STRING:
			return ( isset( $alpha ) ? 'rgba' : 'rgb' ) . '(' . implode( ', ', array_values( $rgb ) ) . ')';
		case ARRAY_A:
			return $rgb;
		default:
			return;
	}
}

/**
 * Check if current template matches given templates.
 *
 * @param array|string $templates
 * @return boolean
 */
function is_page_template_slug( $templates ) {
	$t = basename( get_page_template_slug(), '.php' );
	return is_page() && ( is_array( $templates ) ? in_array( $t, $templates, true ) : ( $t === $templates ) );
}

function shortcode_tag_attr( $tag, $attr ) {
	$tag_attr = array();
	switch ( $tag ) {
		case 'a':
			$tag_attr = merge_args( $tag_attr, split_args( $attr, 'href', 'target', 'rel' ) );
			break;
	}
	return $tag_attr;
}

function shortcode_interpolation( $interpolate, $replace ) {
	$return = $interpolate;
	if ( is_string( $interpolate ) ) {
		preg_match_all( '/%([^%]*)%/', $interpolate, $matches );
		if ( 1 < count( $matches ) && 0 < count( $matches[1] ) ) {
			foreach ( $matches[1] as $m ) {
				if ( array_key_exists( $m, $replace ) ) {
					$return = preg_replace( "/%$m%/", $replace[ $m ], $interpolate );
				}
			}
		}
	} elseif ( is_array( $interpolate ) ) {
		foreach ( $interpolate as $key => $value ) {
			$return[ $key ] = shortcode_interpolation( $value, $replace );
		}
	}
	return $return;
}

function shortcode_query_args( $args ) {
	$query_args = split_args( $args, 'post_type', 'post_parent', 'post_status', 'include', 'exclude', 'posts_per_page', 'paged', 'cat', 'orderby', 'order' );
	if ( isset( $query_args['include'] ) ) {
		$query_args['include'] = array_map( 'intval', explode( ',', str_replace( ', ', ',', $query_args['include'] ) ) );
	}
	if ( isset( $query_args['exclude'] ) ) {
		$query_args['exclude'] = array_map( 'intval', explode( ',', str_replace( ', ', ',', $query_args['exclude'] ) ) );
	}
	$rename     = array(
		'include' => 'post__in',
		'exclude' => 'post__not_in',
	);
	$query_args = rename_args( $query_args, $rename );
	if ( isset( $query_args['orderby'] ) && in_array( $query_args['orderby'], array( 'include', 'exclude' ), true ) ) {
		$query_args['orderby'] = $rename[ $query_args['orderby'] ];
	}
	return array_filter( $query_args );
}

function rename_args( $args, $rename ) {
	foreach ( $rename as $old => $new ) {
		$args[ $new ] = $args[ $old ];
		unset( $args[ $old ] );
	}
	return $args;
}

function get_blog_link() {
	$page_for_posts = get_option( 'page_for_posts' );
	if ( $page_for_posts ) {
		return get_permalink( $page_for_posts );
	}
	return home_url();
}
