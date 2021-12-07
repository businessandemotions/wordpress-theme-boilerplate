
<?php while ( have_posts() ) : ?>
	<?php the_post(); ?>
	<?php Boilerplate\Builder::Render( 'sections' ); ?>
<?php endwhile; ?>
