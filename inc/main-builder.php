<?php

namespace Boilerplate;

function main_builder() {
	new Builder(
		array(
			'key'   => 'sections',
			'label' => __( 'Main modules', 'boilerplate' ),
		),
		array(
			'\Boilerplate\Sections\Content',
			'\Boilerplate\Sections\ImageText',
		),
		array(
			array(
				array(
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => 'page',
				),
			),
		)
	);
}
add_action( 'acf/init', 'main_builder' );
