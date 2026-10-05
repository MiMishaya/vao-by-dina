<?php
/**
 * Bandeau défilant des logos de marques (accueil + gammes). Contenu : menu « Logos de marques ».
 * La liste est affichée deux fois pour un défilement en boucle continu.
 */

$vao_brands = vao_items( 'vao_marque' );
if ( ! $vao_brands ) {
	return;
}
?>
<div class="brand-strip">
	<div class="brand-track">
		<?php foreach ( $vao_brands as $vao_brand ) : ?>
			<img src="<?php echo esc_url( vao_post_img( $vao_brand, 'medium' ) ); ?>" alt="<?php echo esc_attr( get_the_title( $vao_brand ) ); ?>">
		<?php endforeach; ?>
		<?php foreach ( $vao_brands as $vao_brand ) : ?>
			<img src="<?php echo esc_url( vao_post_img( $vao_brand, 'medium' ) ); ?>" alt="" aria-hidden="true">
		<?php endforeach; ?>
	</div>
</div>
