<?php
/**
 * Icons.
 */

namespace Boilerplate;

class Icons {

	private static $icons = array();

	/**
	 * Add svg to global symbols.
	 *
	 * @param string $url           The url to the svg file.
	 * @param string $identifier    An unique identifiter for this svg.
	 *
	 * @return bool Returns the svg symbol code.
	 */
	public function __construct( $icons ) {
		foreach ( $icons as $identifier => $url ) {
			static::add( $identifier, $url );
		}
	}

	public static function add( $identifier, $url, $args = null ) {
		$path = str_replace( home_url( '/' ), ABSPATH, $url );
		if ( $path === $url ) {
			$path = ABSPATH . $url;
		}
		$path = realpath( $path );
		if ( ! array_key_exists( $identifier, static::$icons ) && file_exists( $path ) ) {
			self::$icons[ strval( $identifier ) ] = preg_replace( '(<\?xml.+\?>)', '', file_get_contents( $path ) );
		}
		return self::get( $identifier, $args );
	}

	/**
	 * Returns symbol html.
	 *
	 * @param string $identifier   The symbol identifier.
	 *
	 * @return string
	 */
	public static function get( $identifier, $args = null ) {
		if ( ! array_key_exists( $identifier, self::$icons ) ) {
			return '';
		}
		$args = merge_args(
			$args ?? array(),
			array(
				'wrapp'   => true,
				'wrapper' => '<i%1$s>%2$s</i>',
				'class'   => null,
			)
		);
		$icon = preg_replace( '/<svg\s(.+?)>(.+?)<\/svg>/is', '<svg $1><use xlink:href="#boilerplate-svg-' . $identifier . '"></use></svg>', self::$icons[ strval( $identifier ) ] );
		if ( ! $args['wrapp'] ) {
			return $icon;
		}
		$args['class'] = array(
			'icon',
			sprintf( 'icon-%s', $identifier ),
			$args['class'],
		);
		$attr          = apply_filters(
			array(
				'boilerplate/icon/attributes',
				sprintf( 'boilerplate/icon/%s/attributes', $identifier ),
			),
			split_args( $args, 'class', 'style' )
		);
		return sprintf( $args['wrapper'], array_to_attr( $attr ), $icon );
	}

	/**
	 * Create symbols from svg files.
	 */
	public static function symbols() {
		$return = '<svg id="boilerplate-svg-symbols" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="0" height="0">';
		foreach ( self::$icons as $id => $svg ) {
			$return .= preg_replace( '/<svg\s(.+?)>(.+?)<\/svg>/is', '<symbol id="boilerplate-svg-' . $id . '" $1>$2</symbol>', $svg );
		}
		$return .= '</svg>';
		echo $return;
	}

}

add_action( 'wp_footer', array( __NAMESPACE__ . '\\Icons', 'symbols' ) );
