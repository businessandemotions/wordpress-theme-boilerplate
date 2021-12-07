<?php

namespace Boilerplate;

class Builder {

	/**
	 * Undocumented variable
	 *
	 * @var array
	 */
	private static $instances = array();

	/**
	 * Undocumented variable
	 *
	 * @var [type]
	 */
	protected $key;


	/**
	 * Undocumented function
	 *
	 * @param array $layouts   Layouts to render.
	 * @return array
	 */
	private static function layouts( $layouts ) {

		$return_layouts = array();

		foreach ( $layouts as $layout ) {
			$return_layouts[ $layout['key'] ] = $layout;
		}

		return $return_layouts;

	}

	/**
	 * Undocumented function
	 *
	 * @param array $key
	 * @param array $sections
	 * @param array $location
	 */
	public function __construct( $args, $sections, $location ) {
		$this->args                      = merge_args(
			$args,
			array(
				'key'   => 'modules',
				'label' => __( 'Main modules', 'boilerplate' ),
			)
		);
		$this->key                       = $this->args['key'];
		static::$instances[ $this->key ] = $this;
		$this->sections                  = array();
		$this->layouts                   = array();
		foreach ( $sections as $section ) {
			$this->sections[ $section::$name ] = $section;
			$this->layouts[]                   = $section::get_fields( $this->key );
		}

		acf_add_local_field_group(
			array(
				'key'                   => 'group_' . $this->key,
				'title'                 => $this->args['label'],
				'fields'                => array(
					array(
						'key'               => $this->key,
						'label'             => __( 'Modules', 'boilerplate' ),
						'name'              => $this->key,
						'type'              => 'flexible_content',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => '',
							'id'    => '',
						),
						'layouts'           => static::layouts( $this->layouts ),
						'button_label'      => 'Add section',
						'min'               => '',
						'max'               => '',
					),
				),
				'location'              => $location,
				'menu_order'            => 0,
				'position'              => 'normal',
				'style'                 => 'seamless',
				'label_placement'       => 'top',
				'instruction_placement' => 'label',
				'hide_on_screen'        => '',
				'active'                => true,
				'description'           => '',
			)
		);
	}

	/**
	 * Undocumented function
	 *
	 * @param [type] $key
	 * @param string $tag
	 * @param array  $count
	 * @return void
	 */
	public function _render( $tag = 'section', $count = array() ) {
		if ( function_exists( 'get_field' ) ) {
			$sections = empty( $count ) ? get_field( $this->key ) : get_sub_field( $this->key );
			$i        = 0;
			while ( have_rows( $this->key ) ) {
				the_row();
				$section             = $sections[ $i ];
				$section['_key']     = $this->key;
				$section['_count']   = array_values( $count );
				$section['_count'][] = ++$i;
				( new $this->sections[ $section['acf_fc_layout'] ]( $section ) )->render( $tag );
			}
		}
	}

	public static function Render( $key ) {
		if ( array_key_exists( $key, static::$instances ) ) {
			static::$instances[ $key ]->_render();
		}
	}
}
