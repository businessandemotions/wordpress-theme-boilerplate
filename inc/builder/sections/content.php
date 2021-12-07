<?php

namespace Boilerplate\Sections;

class Content extends Section {

	/**
	 * Undocumented variable
	 *
	 * @var [type]
	 */
	public static $name = 'content';

	/**
	 * Undocumented function
	 *
	 * @param [type] $key
	 * @return void
	 */
	protected static function fields( $parent_key ) {
		$key = $parent_key . '_' . static::$name;
		return array(
			'key'        => $parent_key . '_layout_' . static::$name,
			'name'       => static::$name,
			'label'      => __( 'Content', 'boilerplate' ),
			'display'    => 'block',
			'sub_fields' => static::merge_fields(
				array(
					static::get_tab_field( __( 'Content', 'boilerplate' ), $key ),
				),
				static::get_content_fields( $key ),
				array(
					static::get_tab_field( __( 'Settings', 'boilerplate' ), $key . '_settings' ),
					static::get_settings_fields( $key ),
				)
			),
			'min'        => '',
			'max'        => '',
		);
	}

}
