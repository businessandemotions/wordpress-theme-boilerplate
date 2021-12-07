<?php
/**
 * Setup file.
 *
 * @package Boilerplate
 */

namespace Boilerplate;

/**
 * Undocumented function
 *
 * @return void
 */
function theme_setup() {

}
add_action( 'after_theme_setup', __NAMESPACE__ . '\\theme_setup' );

/**
 * Undocumented function
 *
 * @return void
 */
function theme_scripts() {

	$theme = wp_get_theme();

	wp_enqueue_script( 'theme/scripts', get_template_directory_uri() . '/dist/main.min.js', array(), true, $theme->get( 'Version' ) );
	wp_register_style( 'theme/style', get_template_directory_uri() . '/dist/main.min.css', array(), $theme->get( 'Version' ) );

}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\theme_scripts' );
