
<html>
<head>
	<?php wp_head(); ?>
<head>
<body>
	<?php get_template_part( 'templates/header' ); ?>
	<main id="main" role="document">
		<?php require \Boilerplate\template_path(); ?>
	</main>
	<?php get_template_part( 'templates/footer' ); ?>
</body>
</html>
