<?php
/**
 * Template Name: Gammes de produits
 *
 * Page « Nos marques » : catalogue des produits, gamme par gamme (menu « Nos marques » et ses ancres).
 * Gammes, textes de présentation (Description de la gamme) et produits : menu « Produits ».
 * Les 3 premiers produits de chaque gamme sont visibles, les suivants se déplient avec le bouton « + ».
 * Un produit qui porte exactement le nom d'un logo de marque (ex. « VAO Citron ») affiche ce logo à la place du nom.
 */

get_header();

/**
 * Balise <img> avec ses dimensions réelles (image de la médiathèque ou image d'origine du thème).
 */
$vao_img_tag = function ( $post, $size, $alt, $class = '' ) {
	$src    = vao_post_img( $post, $size );
	$width  = 0;
	$height = 0;
	if ( ! $src ) {
		return '';
	}
	if ( has_post_thumbnail( $post ) ) {
		$data = wp_get_attachment_image_src( get_post_thumbnail_id( $post ), $size );
		if ( $data ) {
			list( , $width, $height ) = $data;
		}
	} else {
		// Même règle que vao_img() : le .png livré peut avoir été converti en .jpg.
		$file = get_template_directory() . '/assets/img/' . get_post_meta( $post->ID, '_vao_theme_img', true );
		if ( ! is_file( $file ) ) {
			$file = preg_replace( '/\.png$/i', '.jpg', $file );
		}
		$data = is_file( $file ) ? getimagesize( $file ) : false;
		if ( $data ) {
			list( $width, $height ) = $data;
		}
	}
	return sprintf(
		'<img%s src="%s" alt="%s"%s loading="lazy">',
		$class ? ' class="' . esc_attr( $class ) . '"' : '',
		esc_url( $src ),
		esc_attr( $alt ),
		$width && $height ? sprintf( ' width="%d" height="%d"', (int) $width, (int) $height ) : ''
	);
};

// Logos de marques indexés par leur nom, pour remplacer le nom des produits homonymes.
$vao_logos = array();
foreach ( vao_items( 'vao_marque' ) as $vao_logo ) {
	$vao_logos[ trim( get_the_title( $vao_logo ) ) ] = $vao_logo;
}

$vao_product_row = function ( $products, $after ) use ( $vao_img_tag, $vao_logos ) {
	echo '<div class="prod-row"><div class="prod-grid">';
	foreach ( $products as $product ) {
		$title = trim( get_the_title( $product ) );
		$poids = get_post_meta( $product->ID, '_vao_poids', true );
		$cond  = get_post_meta( $product->ID, '_vao_conditionnement', true );
		?>
		<article class="prod-card">
			<div class="prod-thumb"><?php echo $vao_img_tag( $product, 'medium_large', vao_post_alt( $product ) ); // Balise construite et échappée par $vao_img_tag. ?></div>
			<div class="prod-info">
				<h3 class="prod-name">
					<?php
					if ( isset( $vao_logos[ $title ] ) ) {
						echo $vao_img_tag( $vao_logos[ $title ], 'medium', $title, 'prod-logo' ); // Balise échappée.
					} else {
						echo esc_html( $title );
					}
					?>
				</h3>
				<?php if ( $poids || $cond ) : ?>
					<p class="prod-meta">
						<?php if ( $poids ) : ?>
							<span><?php vao_e( $poids ); ?></span>
						<?php endif; ?>
						<?php if ( $cond ) : ?>
							<strong><?php vao_e( $cond ); ?></strong>
						<?php endif; ?>
					</p>
				<?php endif; ?>
			</div>
		</article>
		<?php
	}
	echo '</div>';
	echo $after; // HTML construit et échappé par ce modèle.
	echo '</div>';
};
?>

<main class="gammes-page">
	<h1 class="gammes-title"><?php vao_e( 'NOS GAMMES de PRODUITS' ); ?></h1>

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
		$vao_first   = array_shift( $vao_rows );
		$vao_extra   = 'gamme-plus-' . $vao_gamme->slug;
		$vao_spacer  = '<div class="row-plus-spacer" aria-hidden="true"></div>';
		$vao_plus    = $vao_rows
			? '<button type="button" class="row-plus" aria-expanded="false" aria-controls="' . esc_attr( $vao_extra ) . '" aria-label="' . esc_attr( vao_t( 'Voir plus de produits' ) ) . '"><span aria-hidden="true">+</span></button>'
			: $vao_spacer;
		$vao_text    = vao_t( $vao_gamme->description );
		if ( $vao_text && false === stripos( $vao_text, '<p' ) ) {
			$vao_text = wpautop( $vao_text );
		}
		?>
		<section class="gamme gamme--<?php echo esc_attr( $vao_gamme->slug ); ?>" id="<?php echo esc_attr( $vao_gamme->slug ); ?>">
			<h2 class="gamme-title"><?php vao_e( $vao_gamme->name ); ?></h2>
			<?php if ( $vao_text ) : ?>
				<div class="gamme-text"><?php echo wp_kses_post( $vao_text ); ?></div>
			<?php endif; ?>

			<?php $vao_product_row( $vao_first, $vao_plus ); ?>
			<?php if ( $vao_rows ) : ?>
				<div class="extra-rows" id="<?php echo esc_attr( $vao_extra ); ?>">
					<div class="extra-rows-inner">
						<?php
						foreach ( $vao_rows as $vao_row ) {
							$vao_product_row( $vao_row, $vao_spacer );
						}
						?>
					</div>
				</div>
			<?php endif; ?>
		</section>
	<?php endforeach; ?>
</main>

<?php
get_template_part( 'template-parts/brand-strip', null, array( 'muted' => true ) );
get_footer();
