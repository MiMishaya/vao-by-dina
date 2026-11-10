<?php
/**
 * Bandeau défilant des logos de marques. Contenu : menu « Logos de marques ».
 * La liste est affichée deux fois pour un défilement en boucle continu.
 * get_template_part( 'template-parts/brand-strip', null, array( 'muted' => true ) ) : version grisée.
 */

// Logo sans image (retirée dans l'admin) : ignoré.
$vao_brands = array_filter( vao_items( 'vao_marque' ), 'vao_post_img' );
if ( ! $vao_brands ) {
	return;
}
$vao_muted = ! empty( $args['muted'] );
?>
<div class="brand-strip<?php echo $vao_muted ? ' muted' : ''; ?>">
	<div class="brand-track">
		<?php foreach ( $vao_brands as $vao_brand ) : ?>
			<img src="<?php echo esc_url( vao_post_img( $vao_brand, 'medium' ) ); ?>" alt="<?php echo esc_attr( vao_post_title( $vao_brand ) ); ?>" loading="lazy">
		<?php endforeach; ?>
		<?php foreach ( $vao_brands as $vao_brand ) : ?>
			<img src="<?php echo esc_url( vao_post_img( $vao_brand, 'medium' ) ); ?>" alt="" aria-hidden="true" loading="lazy">
		<?php endforeach; ?>
	</div>
</div>
