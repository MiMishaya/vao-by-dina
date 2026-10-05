<?php
/**
 * Template Name: Gammes de produits
 *
 * Catalogue des produits, gamme par gamme (menus « Produits » et « Produits > Gammes »).
 * Les 3 premiers produits de chaque gamme sont visibles, les suivants se déplient avec le bouton « + ».
 */

get_header();

$vao_product_row = function ( $products, $after ) {
	echo '<div class="prod-row">';
	foreach ( $products as $product ) {
		$poids = get_post_meta( $product->ID, '_vao_poids', true );
		$cond  = get_post_meta( $product->ID, '_vao_conditionnement', true );
		?>
		<div class="prod-card">
			<div class="prod-thumb"><img src="<?php echo esc_url( vao_post_img( $product, 'medium' ) ); ?>" alt="<?php echo esc_attr( vao_post_alt( $product ) ); ?>" loading="lazy"></div>
			<div class="prod-name"><?php echo esc_html( get_the_title( $product ) ); ?></div>
			<div class="prod-meta"><?php echo esc_html( $poids ); ?><?php echo $poids && $cond ? '<br>' : ''; ?><?php echo esc_html( $cond ); ?></div>
		</div>
		<?php
	}
	echo $after; // HTML statique fourni par ce modèle.
	echo '</div>';
};
?>

<section class="gammes-section">
	<h1 class="gammes-title"><?php the_title(); ?></h1>

	<?php foreach ( vao_gammes() as $vao_gamme ) : ?>
		<?php
		$vao_rows = array_chunk(
			vao_items(
				'vao_produit',
				array(
					'tax_query' => array(
						array(
							'taxonomy' => 'vao_gamme',
							'terms'    => $vao_gamme->term_id,
						),
					),
				)
			),
			3
		);
		if ( ! $vao_rows ) {
			continue;
		}
		$vao_title_class = 'orange' === get_term_meta( $vao_gamme->term_id, 'vao_titre_couleur', true ) ? 'c-orange' : '';
		$vao_button      = get_term_meta( $vao_gamme->term_id, 'vao_bouton_couleur', true );
		$vao_first       = array_shift( $vao_rows );
		$vao_plus        = $vao_rows
			? '<button type="button" class="row-plus ' . esc_attr( $vao_button ? $vao_button : 'orange' ) . '" aria-expanded="false" aria-label="Voir plus de produits">+</button>'
			: '<div class="row-plus-spacer"></div>';
		?>
		<div class="category" id="<?php echo esc_attr( $vao_gamme->slug ); ?>">
			<div class="category-title <?php echo esc_attr( $vao_title_class ); ?>"><?php echo esc_html( $vao_gamme->name ); ?></div>
			<?php $vao_product_row( $vao_first, $vao_plus ); ?>
			<?php if ( $vao_rows ) : ?>
				<div class="extra-rows">
					<?php
					foreach ( $vao_rows as $vao_row ) {
						$vao_product_row( $vao_row, '<div class="row-plus-spacer"></div>' );
					}
					?>
				</div>
			<?php endif; ?>
		</div>
	<?php endforeach; ?>
</section>

<?php
get_template_part( 'template-parts/brand-strip' );
get_footer();
