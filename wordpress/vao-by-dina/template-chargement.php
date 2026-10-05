<?php
/**
 * Template Name: Écran de chargement
 *
 * Page plein écran sans en-tête ni pied de page, qui redirige vers ?to=... (ou la page des gammes).
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="noindex">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div class="loading-bg">
	<img class="bg-img" src="<?php echo esc_url( vao_mod( 'loading_fond' ) ); ?>" alt="">
	<div class="logo-wrap">
		<img class="logo-img" src="<?php echo esc_url( vao_mod( 'loading_logo' ) ); ?>" alt="VAO 20 taona">
	</div>
	<div class="products-wrap">
		<img class="products-img" src="<?php echo esc_url( vao_mod( 'loading_produits' ) ); ?>" alt="Nos produits VAO">
	</div>
</div>

<div class="loading-block">
	<div class="loading-text"><?php echo esc_html( vao_mod( 'loading_texte' ) ); ?></div>
	<div class="progress-section">
		<div class="progress-bar-bg">
			<div class="progress-bar-fill" id="progress-bar-fill"></div>
		</div>
	</div>
</div>

<?php wp_footer(); ?>
</body>
</html>
