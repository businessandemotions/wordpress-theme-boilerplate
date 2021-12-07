<?php

namespace Boilerplate\Sections;

class Section {

	/**
	 * Undocumented variable
	 *
	 * @var [type]
	 */
	public static $name = null;

	/**
	 * Undocumented function
	 *
	 * @param [type] $parent_key
	 * @return void
	 */
	protected static function fields( $parent_key ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.Found
		return array();
	}

	/**
	 * Undocumented function
	 *
	 * @param [type] $parent_key
	 * @return void
	 */
	public static function get_fields( $parent_key = '' ) {
		return \Boilerplate\apply_filters( static::hooks( 'fields' ), static::fields( $parent_key ) );
	}

	/**
	 * Undocumented function
	 *
	 * @param [type] $name
	 * @return void
	 */
	protected static function hooks( $name ) {
		return array(
			implode( '/', array( 'sections', $name ) ),
			implode( '/', array( 'sections', static::$name, $name ) ),
		);
	}

	protected static function merge_fields() {
		return \call_user_func_array( 'array_merge', \func_get_args() );
	}

	protected static function get_background_fields( $key, $types = null ) {
		if ( ! is_array( $types ) ) {
			$types = array( 'color', 'image' );
		}
		$types = \Boilerplate\apply_filters( static::hooks( 'background/types' ), $types );
		$group = array(
			'key'               => $key . '_background',
			'label'             => 'Background',
			'name'              => 'background',
			'type'              => 'group',
			'instructions'      => '',
			'required'          => 0,
			'conditional_logic' => 0,
			'wrapper'           => array(
				'width' => '',
				'class' => '',
				'id'    => '',
			),
			'layout'            => 'block',
			'sub_fields'        => array(),
		);
		if ( in_array( 'color', $types, true ) ) {
			$group['sub_fields'][] = array(
				'key'               => $key . '_background_color',
				'label'             => 'Color',
				'name'              => 'color',
				'type'              => 'color_picker',
				'instructions'      => '',
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => array(
					'width' => '50',
					'class' => '',
					'id'    => '',
				),
				'default_value'     => '',
			);
		}
		if ( in_array( 'image', $types, true ) ) {
			array_push(
				$group['sub_fields'],
				array(
					'key'               => $key . '_background_image',
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
					'return_format'     => '',
					'preview_size'      => 'medium',
					'library'           => '',
					'min_width'         => '',
					'min_height'        => '',
					'min_size'          => '',
					'max_width'         => '',
					'max_height'        => '',
					'max_size'          => '',
					'mime_types'        => '',
				),
				array(
					'key'               => $key . '_background_image_size',
					'label'             => 'Image size',
					'name'              => 'size',
					'type'              => 'button_group',
					'instructions'      => '',
					'required'          => 0,
					'conditional_logic' => array(
						array(
							array(
								'field'    => $key . '_background_image',
								'operator' => '!=empty',
							),
						),
					),
					'wrapper'           => array(
						'width' => '50',
						'class' => '',
						'id'    => '',
					),
					'choices'           => array(
						'unset'   => 'Image size',
						'cover'   => 'Cover entire area',
						'contain' => 'Contain entire image',
					),
					'allow_null'        => 0,
					'default_value'     => 'cover',
					'layout'            => 'horizontal',
					'return_format'     => 'value',
				),
				array(
					'key'               => $key . '_background_image_repeat',
					'label'             => 'Image repeat',
					'name'              => 'repeat',
					'type'              => 'button_group',
					'instructions'      => '',
					'required'          => 0,
					'conditional_logic' => array(
						array(
							array(
								'field'    => $key . '_background_image',
								'operator' => '!=empty',
							),
						),
					),
					'wrapper'           => array(
						'width' => '50',
						'class' => '',
						'id'    => '',
					),
					'choices'           => array(
						'no-repeat' => 'No repeat',
						'repeat'    => 'Repeat X & Y',
						'repeat-x'  => 'Repeat X',
						'repeat-y'  => 'Repeat Y',
					),
					'allow_null'        => 0,
					'default_value'     => '',
					'layout'            => 'horizontal',
					'return_format'     => 'value',
				),
				array(
					'key'               => $key . '_background_image_position_x',
					'label'             => 'Image position X',
					'name'              => 'position_x',
					'type'              => 'range',
					'instructions'      => '',
					'required'          => 0,
					'conditional_logic' => array(
						array(
							array(
								'field'    => $key . '_background_image',
								'operator' => '!=empty',
							),
						),
					),
					'wrapper'           => array(
						'width' => '50',
						'class' => '',
						'id'    => '',
					),
					'default_value'     => 50,
					'min'               => 0,
					'max'               => 100,
					'step'              => 1,
					'prepend'           => '',
					'append'            => '%',
				),
				array(
					'key'               => $key . '_background_image_position_y',
					'label'             => 'Image position Y',
					'name'              => 'position_y',
					'type'              => 'range',
					'instructions'      => '',
					'required'          => 0,
					'conditional_logic' => array(
						array(
							array(
								'field'    => $key . '_background_image',
								'operator' => '!=empty',
							),
						),
					),
					'wrapper'           => array(
						'width' => '50',
						'class' => '',
						'id'    => '',
					),
					'default_value'     => 50,
					'min'               => 0,
					'max'               => 100,
					'step'              => 1,
					'prepend'           => '',
					'append'            => '%',
				)
			);
		}
		return $group;
	}

	protected static function get_content_fields( $key ) {
		return array(
			array(
				'key'               => $key . '_content_width',
				'label'             => 'Width',
				'name'              => 'width',
				'type'              => 'select',
				'instructions'      => '',
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => array(
					'width' => '50',
					'class' => '',
					'id'    => '',
				),
				'choices'           => array(
					16     => '100%',
					15     => '15/16',
					14     => '14/16',
					13     => '13/16',
					12     => '12/16',
					11     => '11/16',
					10     => '10/16',
					9      => '9/16',
					8      => '8/16',
					'auto' => 'auto',
				),
				'allow_null'        => 1,
				'default_value'     => '',
				'layout'            => 'horizontal',
				'return_format'     => 'value',
			),
			array(
				'key'               => $key . '_content_align_x',
				'label'             => __( 'Horizontal alignment', 'boilerplate' ),
				'name'              => 'align_x',
				'type'              => 'button_group',
				'instructions'      => '',
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => array(
					'width' => '50',
					'class' => '',
					'id'    => '',
				),
				'choices'           => array(
					'left'   => 'Left',
					'center' => 'Center',
					'right'  => 'Right',
				),
				'allow_null'        => 0,
				'default_value'     => 'center',
				'layout'            => 'horizontal',
				'return_format'     => 'value',
			),
			array(
				'key'               => $key . '_content_wysiwyg',
				'label'             => __( 'Content', 'boilerplate' ),
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
		);
	}

	protected static function get_settings_fields( $key, $backgrounds = null ) {
		return array(
			'key'               => $key . '_settings',
			'label'             => __( 'Settings', 'boilerplate' ),
			'name'              => 'settings',
			'type'              => 'group',
			'instructions'      => '',
			'required'          => 0,
			'conditional_logic' => 0,
			'wrapper'           => array(
				'width' => '',
				'class' => '',
				'id'    => '',
			),
			'layout'            => 'block',
			'sub_fields'        => array(
				array(
					'key'               => $key . '_settings_section_id',
					'label'             => __( 'Section ID', 'boilerplate' ),
					'name'              => 'section_id',
					'type'              => 'text',
					'instructions'      => '',
					'required'          => 0,
					'conditional_logic' => 0,
					'wrapper'           => array(
						'width' => '',
						'class' => '',
						'id'    => '',
					),
					'default_value'     => '',
					'placeholder'       => '',
					'prepend'           => '',
					'append'            => '',
					'maxlength'         => '',
				),
				array(
					'key'               => $key . '_settings_layout',
					'label'             => 'Layout',
					'name'              => 'layout',
					'type'              => 'select',
					'instructions'      => '',
					'required'          => 0,
					'conditional_logic' => 0,
					'wrapper'           => array(
						'width' => '',
						'class' => '',
						'id'    => '',
					),
					'choices'           => array(
						'full-background' => 'Full background',
						'full'            => 'Full width',
					),
					'default_value'     => false,
					'allow_null'        => 1,
					'multiple'          => 0,
					'ui'                => 0,
					'return_format'     => 'value',
					'ajax'              => 0,
					'placeholder'       => '',
				),
				static::get_background_fields( $key . '_settings', $backgrounds ),
				array(
					'key'               => $key . '_settings_remove_margin_top',
					'label'             => 'Remove margin top',
					'name'              => 'no_margin_top',
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
					'key'               => $key . '_settings_remove_margin_bottom',
					'label'             => 'Remove margin bottom',
					'name'              => 'no_margin_bottom',
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
			),
		);
	}

	protected static function get_tab_field( $label, $key ) {
		return array(
			'key'               => $key . '_tab',
			'label'             => $label,
			'name'              => '',
			'type'              => 'tab',
			'instructions'      => '',
			'required'          => 0,
			'conditional_logic' => 0,
			'wrapper'           => array(
				'width' => '',
				'class' => '',
				'id'    => '',
			),
			'placement'         => 'top',
			'endpoint'          => 0,
		);
	}

	protected static function get_accordion_field( $label, $key, $endpoint = false ) {
		return array(
			'key'               => $key . '_accordion',
			'label'             => $label,
			'name'              => '',
			'type'              => 'accordion',
			'instructions'      => '',
			'required'          => 0,
			'conditional_logic' => 0,
			'wrapper'           => array(
				'width' => '',
				'class' => '',
				'id'    => '',
			),
			'open'              => 0,
			'multi_expand'      => 0,
			'endpoint'          => $endpoint ? 1 : 0,
		);
	}

	/**
	 * Undocumented function
	 *
	 * @param [type] $data
	 */
	public function __construct( $data ) {
		$this->key = $data['_key'] . '_' . static::$name;
		unset( $data['_key'] );
		$this->index = $data['_count'];
		unset( $data['_count'] );
		$this->settings = $data['settings'];
		unset( $data['settings'] );
		$this->data = $data;
	}

	public function unique() {
		return $this->key . '_' . implode( '-', $this->index );
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function render() {
		$template = str_replace( '_', '-', $this->data['acf_fc_layout'] );
		include \Boilerplate\apply_filters( static::hooks( 'template' ), get_template_directory() . "/templates/sections/{$template}.php" );
	}

	/**
	 * Undocumented function
	 *
	 * @return string
	 */
	protected function render_button( $args = array() ) {
		$args   = \Boilerplate\merge_args_recursive(
			$args,
			array(
				'class'   => '',
				'wrapper' => array(
					'class' => '',
				),
			)
		);
		$button = $this->data['button'];
		if ( ! isset( $button['link'] ) || empty( $button['link'] ) ) {
			return '';
		}
		$wrapper_attr  = array(
			'class' => array(
				'left' !== $button['align'] ? sprintf( 'text-%s', $button['align'] ) : '',
				$args['wrapper']['class'],
			),
		);
		$attr          = \Boilerplate\rename_args( $button['link'], array( 'url' => 'href' ) );
		$attr['class'] = array(
			$button['type'],
			$args['class'],
			'section-button',
		);
		$attr['title'] = strip_shortcodes( $attr['title'] );
		return do_shortcode( sprintf( '<div %1$s>[button %2$s]%3$s[/button]</div>', \Boilerplate\array_to_attr( $wrapper_attr ), \Boilerplate\array_to_attr( $attr ), do_shortcode( $button['link']['title'] ) ) );
	}

	/**
	 * Function to check wether a section has a background or not.
	 *
	 * @return bool
	 */
	public function has_background() {
		return $this->settings['background'] && ( $this->settings['background']['color'] || $this->settings['background']['image'] );
	}

	/**
	 * Generate section background styles.
	 *
	 * @return string
	 */
	public function get_background() {
		$style = '';
		if ( $this->settings['background'] && $this->settings['background']['color'] ) {
			$style .= "background-color: {$this->settings['background']['color']};";
		}
		if ( $this->settings['background'] && $this->settings['background']['image'] ) {
			$image = wp_get_attachment_image_src( $this->settings['background']['image'], 'full' );
			if ( $image ) {
				$style .= "background-image: url({$image[0]});";
				if ( array_key_exists( 'size', $this->settings['background'] ) && $this->settings['background']['size'] ) {
					$style .= "background-size: {$this->settings['background']['size']};";
				}
				if ( array_key_exists( 'repeat', $this->settings['background'] ) && $this->settings['background']['repeat'] ) {
					$style .= "background-repeat: {$this->settings['background']['repeat']};";
				}
				if ( array_key_exists( 'position_x', $this->settings['background'] ) && 50 !== $this->settings['background']['position_x'] ) {
					$style .= "background-position-x: {$this->settings['background']['position_x']}%;";
				}
				if ( array_key_exists( 'position_y', $this->settings['background'] ) && 50 !== $this->settings['background']['position_y'] ) {
					$style .= "background-position-y: {$this->settings['background']['position_y']}%;";
				}
			}
		}
		return $style;
	}

	/**
	 * Undocumented function
	 *
	 * @param array $attr   Array of attributes.
	 * @return array
	 */
	public function get_attr( $attr = array() ) {
		$attr['id']    = $this->settings['section_id'];
		$attr['class'] = $attr['class'] ?? array();
		if ( $this->settings['layout'] ) {
			if ( in_array( $this->settings['layout'], array( 'full', 'full-background' ), true ) ) {
				$attr['class'][] = 'section-full';
			}
			if ( 'full' !== $this->settings['layout'] ) {
				$attr['class'][] = $this->settings['layout'];
			}
		}
		if ( $this->settings['layout'] && in_array( $this->settings['layout'], array( 'full-background' ), true ) && $this->has_background() ) {
			$attr['class'][] = 'section-background';

			$attr['style']  = $attr['style'] ?? '';
			$attr['style'] .= $this->get_background();

		}
		if ( $this->settings['no_margin_top'] ) {
			$attr['class'][] = 'no-margin-top';
		}
		if ( $this->settings['no_margin_bottom'] ) {
			$attr['class'][] = 'no-margin-bottom';
		}
		return \Boilerplate\apply_filters(
			static::hooks( 'attributes' ),
			$attr,
			$this
		);
	}

	/**
	 * Undocumented function
	 *
	 * @param array $attr  Array of attributes.
	 *
	 * @return array
	 */
	public function get_container_attr( $attr = array() ) {
		$attr['class'] = $attr['class'] ?? array();
		if ( ! $this->settings['layout'] || ! in_array( $this->settings['layout'], array( 'full' ), true ) ) {
			$attr['class'][] = 'container';
		}
		return \Boilerplate\apply_filters(
			static::hooks( 'container/attributes' ),
			$attr,
			$this
		);
	}

	/**
	 * Undocumented function
	 *
	 * @param array $attr  Array of attributes.
	 *
	 * @return array
	 */
	public function get_content_wrapper_attr( $attr = array() ) {
		$attr['class']   = $attr['class'] ?? array();
		$attr['class'][] = 'section-inner';
		if ( ( ! $this->settings['layout'] || ! in_array( $this->settings['layout'], array( 'full-background' ), true ) ) && $this->has_background() ) {
			$attr['class'][] = 'section-background';

			$attr['style']  = $attr['style'] ?? '';
			$attr['style'] .= $this->get_background();
		}
		return \Boilerplate\apply_filters(
			static::hooks( 'content-wrapper/attributes' ),
			$attr,
			$this
		);
	}

}
