<?php

namespace Boilerplate;

/**
 * Function writes to debug log.
 *
 * @param any $log    What ever to log.
 */
function debug( $log, $name = 'debug', $dir = __DIR__ ) {
	if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
		if ( ! is_string( $log ) ) {
			ob_start();
			var_dump( $log ); // phpcs:ignore
			$log = ob_end_clean();
		}
		error_log( "\n" . '[' . date( 'Y-m-d H:i:s' ) . '] ' . $log, 3, $dir . '/' . $name . '.log' ); // phpcs:ignore
	}
}

/**
 * Add <body> classes
 *
 * @param array $classes array of classes.
 */
function body_class( $classes ) {

	foreach ( $classes as $i => $class ) {
		$classes[ $i ] = str_replace( 'page-template-', '', $class );
	}

	global $is_IE;
	if ( $is_IE ) {
		$classes[] = 'is_IE';
	}

	return $classes;
}
add_filter( 'body_class' );

/**
 * Function to allow svg tags in the_content.
 *
 * @param array $settings Configuration of tinyMCE.
 */
function tinymce_add_pre( $settings ) {
	$ext = 'svg[preserveAspectRatio|style|version|viewbox|xmlns,width,height],defs,clipPath[id],linearGradient[id|x1|y1|z1],path[fill|fill-rule|stroke|stroke-linecap|stroke-linejoin|stroke-width|d|clip-path,opacity],circle[cx|cy|r|fill|stroke|stroke-width|clip-path],g[fill|fill-rule|stroke|stroke-linecap|stroke-linejoin|stroke-width|clip-path|opacity]';

	if ( isset( $settings['extended_valid_elements'] ) ) {
		$settings['extended_valid_elements'] .= ',' . $ext;
	} else {
		$settings['extended_valid_elements'] = $ext;
	}
	return $settings;
}
add_filter( 'tiny_mce_before_init', 'tinymce_add_pre' );

/**
 * Function to add tags like svg tags to allowed kses tags.
 *
 * @param array  $tags    The allready allowed tags.
 * @param string $context The context.
 */
function add_kses_allowed_html( $tags, $context = '' ) {
	if ( ! $context || in_array( $context, array( 'post', 'data', '' ), true ) ) {
		return array_merge(
			$tags,
			array(
				'iframe'         => array(
					'title'           => true,
					'width'           => true,
					'height'          => true,
					'src'             => true,
					'frameborder'     => true,
					'allow'           => true,
					'allowfullscreen' => true,
				),
				'time'           => array(
					'class'    => true,
					'datetime' => true,
				),
				'svg'            => array(
					'id'                  => true,
					'style'               => true,
					'preserveAspectRatio' => true,
					'style'               => true,
					'version'             => true,
					'viewbox'             => true,
					'xmlns'               => true,
					'width'               => true,
					'height'              => true,
				),
				'symbol'         => array(
					'id'                  => true,
					'preserveAspectRatio' => true,
					'style'               => true,
					'version'             => true,
					'viewbox'             => true,
					'xmlns'               => true,
					'width'               => true,
					'height'              => true,
				),
				'defs'           => array(),
				'clipPath'       => array(
					'id'        => true,
					'transform' => true,
				),
				'linearGradient' => array(
					'id' => true,
					'x1' => true,
					'y1' => true,
					'z1' => true,
				),
				'path'           => array(
					'fill'            => true,
					'fill-rule'       => true,
					'stroke'          => true,
					'stroke-linecap'  => true,
					'stroke-linejoin' => true,
					'stroke-width'    => true,
					'd'               => true,
					'clip-path'       => true,
					'opacity'         => true,
					'class'           => true,
					'transform'       => true,
				),
				'circle'         => array(
					'cx'           => true,
					'cy'           => true,
					'r'            => true,
					'fill'         => true,
					'stroke'       => true,
					'stroke-width' => true,
					'clip-path'    => true,
					'class'        => true,
				),
				'rect'           => array(
					'width'        => true,
					'height'       => true,
					'x'            => true,
					'y'            => true,
					'fill'         => true,
					'stroke'       => true,
					'stroke-width' => true,
					'clip-path'    => true,
					'class'        => true,
				),
				'g'              => array(
					'class'           => true,
					'fill'            => true,
					'fill-rule'       => true,
					'stroke'          => true,
					'stroke-linecap'  => true,
					'stroke-linejoin' => true,
					'stroke-width'    => true,
					'clip-path'       => true,
					'opacity'         => true,
				),
				'use'            => array(
					'overflow'   => true,
					'xlink:href' => true,
					'href'       => true,
				),
				'style'          => array(),
			)
		);
	}
	return $tags;
}
add_filter( 'wp_kses_allowed_html', 'add_kses_allowed_html' );

function array_to_attr_value( $value, $key ) {
	switch ( $key ) {
		case 'class':
			$classes = array();
			foreach ( $value as $v ) {
				if ( is_array( $v ) ) {
					$classes = array_merge( $classes, $v );
				} else {
					$classes[] = $v;
				}
			}
			$value = sanitize_class( $classes );
			break;
		case 'style':
			if ( is_array( $value ) ) {
				$string = '';
				foreach ( $value as $prop => $v ) {
					$string .= $prop . ': ' . $v . '; ';
				}
				$value = $string;
			}
			break;

	}
	return $value;
}
add_filter( 'array_to_attr/value', 'array_to_attr_value', 2, 12 );
