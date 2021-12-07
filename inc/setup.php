<?php

namespace Boilerplate;

/**
 * Theme setup
 */
function setup() {
	$logo_args = array(
		'height'      => 60,
		'width'       => 180,
		'flex-height' => true,
		'flex-width'  => true,
		'header-text' => array( 'site-title', 'site-description' ),
	);
	add_theme_support( 'custom-logo', $logo_args );

	// Make theme available for translation
	// Community translations can be found at https://github.com/roots/sage-translations.
	load_theme_textdomain( 'boilerplate', get_template_directory() . '/lang' );

	// Enable plugins to manage the document title
	// http://codex.wordpress.org/Function_Reference/add_theme_support#Title_Tag.
	add_theme_support( 'title-tag' );

	// Register wp_nav_menu() menus
	// http://codex.wordpress.org/Function_Reference/register_nav_menus.
	register_nav_menus(
		array(
			'primary_navigation' => __( 'Primary Navigation', 'boilerplate' ),
			'mobile_navigation'  => __( 'Mobile Navigation', 'boilerplate' ),
		)
	);

	// Enable post thumbnails
	// http://codex.wordpress.org/Post_Thumbnails
	// http://codex.wordpress.org/Function_Reference/set_post_thumbnail_size
	// http://codex.wordpress.org/Function_Reference/add_image_size.
	add_theme_support( 'post-thumbnails' );

	// Enable post formats
	// http://codex.wordpress.org/Post_Formats.
	add_theme_support( 'post-formats', array( 'aside', 'gallery', 'link', 'image', 'quote', 'video', 'audio' ) );

	// Enable HTML5 markup support
	// http://codex.wordpress.org/Function_Reference/add_theme_support#HTML5.
	add_theme_support( 'html5', array( 'caption', 'comment-form', 'comment-list', 'gallery', 'search-form' ) );

	// Use main stylesheet for visual editor
	// To add custom styles edit /assets/styles/layouts/_tinymce.scss.
	add_editor_style( get_stylesheet_directory_uri() . '/dist/main.css' );

	new Icons(
		array(
			// 'example' => get_template_directory_uri() . '/assets/icons/example.svg',
		)
	);

}
add_action( 'after_setup_theme', 'setup' );

/**
 * Register sidebars
 */
function widgets_init() {
	register_sidebar(
		array(
			'name'          => __( 'Header', 'boilerplate' ),
			'id'            => 'header',
			'before_widget' => '<div class="cell cell-auto widget text-center %1$s %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3>',
			'after_title'   => '</h3>',
		)
	);

	register_sidebar(
		array(
			'name'          => __( 'Footer Upper', 'boilerplate' ),
			'id'            => 'footer-upper',
			'before_widget' => '<section class="cell small-12 large-auto widget %1$s %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3>',
			'after_title'   => '</h3>',
		)
	);

	register_sidebar(
		array(
			'name'          => __( 'Footer Lower', 'boilerplate' ),
			'id'            => 'footer-lower',
			'before_widget' => '<section class="cell small-12 large-auto widget %1$s %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<strong>',
			'after_title'   => '</strong>',
		)
	);
}
add_action( 'widgets_init' );

/**
 * Function to ouput a custom set logo.
 */
function the_custom_logo() {
	// Check if we are using WP4.5+.
	if ( function_exists( 'the_custom_logo' ) ) {
		// If a custom logo is set, use it!
		if ( has_custom_logo() ) {
			?>
			<a class="custom-logo display-block" title="<?php bloginfo( 'name' ); ?>" rel="home" href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<?php echo wp_kses_post( get_attachment_image( get_theme_mod( 'custom_logo', null ), 'full' ) ); ?>
			</a>
			<?php
		} else {
			// Else fall back to name.
			?>
			<a class="navbar-brand display-block" title="<?php bloginfo( 'name' ); ?>" rel="home" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a>
			<?php
		}
	} else {
		// If < WP4.5 fall back to the name.
		bloginfo( 'name' );
	}
}

/**
 * Function to output alternative logo
 */
function the_alternative_logo() {
	$logo_id   = get_theme_mod( 'boilerplate_alt_logo' );
	$logo_attr = array(
		'alt'      => get_bloginfo( 'name' ),
		'class'    => 'custom-logo alternative-logo',
		'itemprop' => 'alternative-logo',
	);
	if ( $logo_id > 0 ) {
		?>
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="custom-logo-link custom-logo alternative-logo" rel="home" itemprop="url">
			<?php echo wp_kses_post( get_attachment_image( $logo_id, 'full', false, $logo_attr ) ); ?>
		</a>
		<?php
	} else {
		the_custom_logo();
	}
}

function get_default_colors() {
	$colors = array(
		'primary'           => '#FD5E53',
		'secondary'         => '#4ED396',
		'light'             => '#ffffff',
		'dark'              => '#222221',

		'text'              => 'dark',
		'background'        => 'light',
		'footer_background' => 'primary',
		'footer_text'       => 'light',
	);
	return $colors;
}

/**
 * Function to get colors set in plugin settings.
 */
function get_colors() {
	$colors = get_default_colors();
	foreach ( $colors as $name => $color ) {
		$colors[ $name ] = get_theme_mod( 'boilerplate_' . $name . '_color', \array_key_exists( $color, $colors ) ? $colors[ $color ] : $color );
	}
	return $colors;
}

/**
 * Get site styles.
 *
 * @return string
 */
function get_theme_styles() {
	$colors = get_colors();
	return '
		:root {
			--color-primary: ' . esc_attr( $colors['primary'] ) . ';
			--color-secondary: ' . esc_attr( $colors['secondary'] ) . ';
			--color-light: ' . esc_attr( $colors['light'] ) . ';
			--color-dark: ' . esc_attr( $colors['dark'] ) . ';
		}
		body {
			color: ' . esc_attr( $colors['text'] ) . ';
			background-color: ' . esc_attr( $colors['background'] ) . ';
		}
		footer {
			color: ' . esc_attr( $colors['footer_text'] ) . ';
			background-color: ' . esc_attr( $colors['footer_background'] ) . ';
		}

	';
}


/**
 * Undocumented function
 * * @return void
 */
function assets() {

	$theme = wp_get_theme();

	wp_enqueue_script( 'boilerplate/scripts', get_stylesheet_directory_uri() . '/dist/main.min.js', array(), true, $theme->get( 'Version' ) );
	wp_localize_script(
		'boilerplate/js',
		'ajaxparams',
		array(
			'ajaxurl' => site_url() . '/wp-admin/admin-ajax.php',
		)
	);

	if ( is_single() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	wp_enqueue_style( 'boilerplate/fonts', 'https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i', false, $theme->get( 'Version' ) );
	wp_register_style( 'boilerplate/style', get_stylesheet_directory_uri() . '/dist/main.min.css', array( 'boilerplate/fonts' ), $theme->get( 'Version' ) );
	wp_add_inline_style( 'boilerplate/css', get_theme_styles() );


}
add_action( 'wp_enqueue_scripts', 'assets' );
