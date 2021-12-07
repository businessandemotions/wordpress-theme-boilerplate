
<html>
<head>
	<?php wp_head(); ?>
<head>
<body>
	<?php while ( have_posts() ) : ?>
		<?php the_post(); ?>
		<?php Boilerplate\Builder::Render( 'sections' ); ?>
	<?php endwhile; ?>
</body>
</html>
