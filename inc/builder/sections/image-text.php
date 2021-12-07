<?php

namespace Boilerplate\Sections;

class ImageText extends Section {

	/**
	 * Undocumented variable
	 *
	 * @var [type]
	 */
	public static $name = 'image_text';

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
			'label'      => __( 'Image - Text', 'boilerplate' ),
			'display'    => 'block',
			'sub_fields' => static::merge_fields(
				array(
					static::get_tab_field( __( 'Image - Text', 'boilerplate' ), $key ),
					array(
						'key'               => $key . '_image',
						'label'             => 'Image',
						'name'              => 'image',
						'type'              => 'image',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '50',
							'class' => '',
							'id'    => '',
						),
						'return_format'     => 'array',
						'preview_size'      => 'medium',
						'library'           => 'all',
						'min_width'         => '',
						'min_height'        => '',
						'min_size'          => '',
						'max_width'         => '',
						'max_height'        => '',
						'max_size'          => '',
						'mime_types'        => '',
					),
					array(
						'key'               => $key . '_reverse',
						'label'             => 'Reverse',
						'name'              => 'reverse',
						'type'              => 'true_false',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '50',
							'class' => '',
							'id'    => '',
						),
						'message'           => '',
						'default_value'     => 0,
						'ui'                => 1,
						'ui_on_text'        => '',
						'ui_off_text'       => '',
					),
					array(
						'key'               => $key . '_content',
						'label'             => 'Content',
						'name'              => 'content',
						'type'              => 'wysiwyg',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => '',
							'id'    => '',
						),
						'default_value'     => '',
						'tabs'              => 'all',
						'toolbar'           => 'full',
						'media_upload'      => 1,
						'delay'             => 0,
					),
					static::get_tab_field( __( 'Settings', 'boilerplate' ), $key . '_settings' ),
					static::get_settings_fields( $key ),
				)
			),
			'min'        => '',
			'max'        => '',
		);
	}



}
