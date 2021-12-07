<?php

namespace Boilerplate;

?>
<section <?php echo array_to_attr( $this->get_attr( array( 'class' => array( 'section-content' ) ) ) ); ?>>
	<div <?php echo array_to_attr( $this->get_container_attr() ); ?>>
		<div <?php echo array_to_attr( $this->get_content_wrapper_attr() ); ?>>
			<div class="grid justify-<?php echo esc_attr( $this->data['align_x'] ); ?>">
				<div class="col medium-<?php echo esc_attr( $this->data['width'] ); ?>">
					<div class="content">
						<?php echo wp_kses_post( $this->data['content'] ); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
