<?php
/**
 * Template Name: Écran de chargement
 *
 * Page plein écran sans en-tête ni pied de page, qui redirige vers ?to=... (ou la page des gammes).
 */

// Feuilles décoratives : fichier, largeur et hauteur réelles, classe de position (chargement.css).
$vao_leaves = array(
	array( 'feuille-3', 160, 160, 'tl' ),
	array( 'feuille-1', 320, 175, 'tr' ),
	array( 'feuille-5', 200, 140, 'r1' ),
	array( 'feuille-3', 160, 160, 'l1' ),
	array( 'feuille-2', 220, 187, 'r2' ),
	array( 'feuille-4', 180, 207, 'bl' ),
	array( 'feuille-2', 220, 187, 'b1' ),
	array( 'feuille-5', 200, 140, 'br' ),
);
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="noindex">
<?php wp_head(); // Polices : assets/fonts/fonts.css, chargé par vao_assets(). ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div class="loading-bg">
	<img class="bg-img" src="<?php echo esc_url( vao_mod( 'loading_fond' ) ); ?>" alt="" width="3000" height="1600">
	<div class="logo-wrap">
		<img class="logo-img" src="<?php echo esc_url( vao_mod( 'loading_logo' ) ); ?>" alt="<?php echo esc_attr( vao_t( 'VAO 20 ans' ) ); ?>" width="1875" height="542">
	</div>
	<div class="products-wrap">
		<img class="products-img" src="<?php echo esc_url( vao_mod( 'loading_produits' ) ); ?>" alt="<?php echo esc_attr( vao_t( 'Nos produits VAO' ) ); ?>" width="1600" height="509">
	</div>
	<?php foreach ( $vao_leaves as $vao_leaf ) : ?>
		<img class="leaf leaf--<?php echo esc_attr( $vao_leaf[3] ); ?>" src="<?php echo esc_url( vao_img( 'gammes/' . $vao_leaf[0] . '.png' ) ); ?>" alt="" width="<?php echo (int) $vao_leaf[1]; ?>" height="<?php echo (int) $vao_leaf[2]; ?>" aria-hidden="true">
	<?php endforeach; ?>
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
