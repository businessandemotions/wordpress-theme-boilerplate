<?php

namespace Boilerplate;

$image      = $this->data['image'];
$grid_class = array( 'grid', 'collapse-medium', 'gutters-2-mobile', 'align-center' );
if ( $this->data['reverse'] ) {
	$grid_class[] = 'reverse-medium';
}
$image_col_class = array( 'col', 'medium-8', 'collapse', 'text-center' );
if ( $this->data['reverse'] ) {
	$image_col_class[] = 'push-medium-1';
}
$content_col_class = array( 'col', 'medium-6', 'push-medium-1' );
?>
<section <?php echo array_to_attr( $this->get_attr( array( 'class' => array( 'section-image-text' ) ) ) ); ?>>
	<div <?php echo array_to_attr( $this->get_container_attr() ); ?>>
		<div <?php echo array_to_attr( $this->get_content_wrapper_attr() ); ?>>
			<div class="<?php echo esc_attr( implode( ' ', $grid_class ) ); ?>">
				<div class="<?php echo esc_attr( implode( ' ', $image_col_class ) ); ?>">
					<?php if ( $image ) : ?>
						<img src="<?php echo esc_attr( $image['url'] ); ?>" alt="<?php echo esc_attr( $image['alt'] ); ?>" style="width: 100%;" />
					<?php endif; ?>
				</div>
				<div class="<?php echo esc_attr( implode( ' ', $content_col_class ) ); ?>">
					<div class="content">
						<?php echo wp_kses_post( $this->data['content'] ); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
