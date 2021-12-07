<?php
/**
 * Wrapper functions.
 *
 * @link       https://businessandemotions.se/
 * @since      0.0.0
 *
 * @package    batteryloop
 */

namespace Boilerplate;

/**
 * Theme wrapper
 *
 * @link https://roots.io/sage/docs/theme-wrapper/
 * @link http://scribu.net/wordpress/theme-wrappers.html
 */

/**
 * Template path function.
 */
function template_path() {
	return Wrapper::$main_template;
}

/**
 * Sidebar path function.
 */
function sidebar_path() {
	return new Wrapper( 'templates/sidebar.php' );
}

/**
 * The Wrapper class.
 */
class Wrapper {
	/**
	 * Stores the full path to the main template file.
	 *
	 * @var string $main_template the main template.
	 */
	public static $main_template;

	/**
	 * Basename of template file.
	 *
	 * @var slug $slug the slug.
	 */
	public $slug;

	/**
	 * Templates.
	 *
	 * @var arratÿ $templates array of templates.
	 */
	public $templates;

	/**
	 * Stores the base name of the template file; e.g. 'page' for 'page.php' etc.
	 *
	 * @var string $base the base name.
	 */
	public static $base;

	/**
	 * Construct the template.
	 *
	 * @param string $template the template name.
	 */
	public function __construct( $template = 'base.php' ) {
		$this->slug      = basename( $template, '.php' );
		$this->templates = array( $template );

		if ( self::$base ) {
			$str = substr( $template, 0, -4 );
			array_unshift( $this->templates, sprintf( $str . '-%s.php', self::$base ) );
		}
	}

	/**
	 * ToString function.
	 */
	public function __toString() {
		$this->templates = apply_filters( 'sage/wrap_' . $this->slug, $this->templates );
		return locate_template( $this->templates );
	}

	/**
	 * Wrap function.
	 *
	 * @param string $main the main string.
	 */
	public static function wrap( $main ) {
		// Check for other filters returning null.
		if ( ! is_string( $main ) ) {
			return $main;
		}

		self::$main_template = $main;
		self::$base          = basename( self::$main_template, '.php' );

		if ( 'index' === self::$base ) {
			self::$base = false;
		}

		return new Wrapper();
	}
}
add_filter( 'template_include', array( __NAMESPACE__ . '\\Wrapper', 'wrap' ), 109 );
