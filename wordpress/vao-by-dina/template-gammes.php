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

/**
 * Texte de présentation d'une gamme. Une description saisie sans balises <p>/<ul> (WordPress ne garde que
 * <strong> quand l'auteur n'a pas le droit unfiltered_html) a ses lignes collées : « quotidien :Vao Citron ».
 * On recoupe aux collages (minuscule ou ponctuation suivie d'une majuscule sans espace) puis les lignes
 * qui suivent un « : » deviennent une liste à puces, comme sur la maquette.
 */
$vao_gamme_text = function ( $term ) {
	$text = vao_t( $term->description );
	if ( ! $text || preg_match( '#<(p|ul|br)\b#i', $text ) ) {
		return $text;
	}
	if ( false === strpos( $text, "\n" ) ) {
		$text = preg_replace( '#^(\s*<strong>.*?</strong>)\s*(?=\S)#su', "$1\n", $text, 1 );
		$text = preg_replace( '#(?<=[\p{Ll}.:!?])(?=[\p{Lu}])#u', "\n", $text );
	}
	$html = '';
	$list = false;
	foreach ( array_filter( array_map( 'trim', explode( "\n", $text ) ), 'strlen' ) as $line ) {
		if ( $list ) {
			$html .= '<li>' . $line . '</li>';
			continue;
		}
		$html .= '<p>' . $line . '</p>';
		if ( ':' === substr( wp_strip_all_tags( $line ), -1 ) ) {
			$list  = true;
			$html .= '<ul>';
		}
	}
	return $list ? $html . '</ul>' : $html;
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
		$alt   = trim( (string) get_post_meta( $product->ID, '_vao_alt', true ) );
		// Produits nommés « VAO » (Antiseptique…) : leur texte alternatif donne le logo à afficher.
		$logo  = isset( $vao_logos[ $title ] ) ? $title : ( isset( $vao_logos[ $alt ] ) ? $alt : '' );
		$poids = get_post_meta( $product->ID, '_vao_poids', true );
		$cond  = get_post_meta( $product->ID, '_vao_conditionnement', true );
		?>
		<article class="prod-card prod-card--<?php echo esc_attr( sanitize_title( $logo ? $logo : $title ) ); ?>">
			<div class="prod-thumb"><?php echo $vao_img_tag( $product, 'medium_large', vao_post_alt( $product ) ); // Balise construite et échappée par $vao_img_tag. ?></div>
			<div class="prod-info">
				<h3 class="prod-name">
					<?php
					if ( $logo ) {
						echo $vao_img_tag( $vao_logos[ $logo ], 'medium', $logo, 'prod-logo' ); // Balise échappée.
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

/*
 * Sections affichées. La maquette regroupe le détergent en barre sous « Détergent » (même grille que la poudre),
 * alors que le menu garde deux entrées : la gamme rattachée garde son ancre sur son texte.
 */
$vao_merge    = array( 'detergent-barre' => 'detergent' );
$vao_sections = array();
foreach ( vao_gammes() as $vao_gamme ) {
	$vao_products = vao_items(
		'vao_produit',
		array(
			'tax_query' => array(
				array(
					'taxonomy' => 'vao_gamme',
					'terms'    => $vao_gamme->term_id,
				),
			),
		)
	);
	if ( ! $vao_products ) {
		continue;
	}
	$vao_parent = isset( $vao_merge[ $vao_gamme->slug ] ) ? $vao_merge[ $vao_gamme->slug ] : '';
	if ( $vao_parent && isset( $vao_sections[ $vao_parent ] ) ) {
		$vao_sections[ $vao_parent ]['subs'][]   = $vao_gamme;
		$vao_sections[ $vao_parent ]['products'] = array_merge( $vao_sections[ $vao_parent ]['products'], $vao_products );
		continue;
	}
	$vao_sections[ $vao_gamme->slug ] = array(
		'term'     => $vao_gamme,
		'subs'     => array(),
		'products' => $vao_products,
	);
}
?>

<main class="gammes-page">
	<div class="gammes-inner">
		<h1 class="gammes-title"><?php vao_e( 'NOS GAMMES de PRODUITS' ); ?></h1>

		<?php
		foreach ( $vao_sections as $vao_section ) :
			$vao_gamme  = $vao_section['term'];
			$vao_rows   = array_chunk( $vao_section['products'], 3 );
			$vao_first  = array_shift( $vao_rows );
			$vao_extra  = 'gamme-plus-' . $vao_gamme->slug;
			$vao_spacer = '<div class="row-plus-spacer" aria-hidden="true"></div>';
			$vao_plus   = $vao_rows
				? '<button type="button" class="row-plus" aria-expanded="false" aria-controls="' . esc_attr( $vao_extra ) . '" aria-label="' . esc_attr( vao_t( 'Voir plus de produits' ) ) . '"><span aria-hidden="true">+</span></button>'
				: $vao_spacer; // 3 produits ou moins : rien à déplier, pas de « + ».
			$vao_text   = $vao_gamme_text( $vao_gamme );
			?>
			<section class="gamme gamme--<?php echo esc_attr( $vao_gamme->slug ); ?>" id="<?php echo esc_attr( $vao_gamme->slug ); ?>">
				<h2 class="gamme-title"><?php vao_e( $vao_section['subs'] ? 'Détergent' : $vao_gamme->name ); ?></h2>
				<?php if ( $vao_text ) : ?>
					<div class="gamme-text"><?php echo wp_kses_post( $vao_text ); ?></div>
				<?php endif; ?>
				<?php foreach ( $vao_section['subs'] as $vao_sub ) : ?>
					<div class="gamme-text gamme-text--sub" id="<?php echo esc_attr( $vao_sub->slug ); ?>">
						<p class="gamme-sub-title"><strong><?php echo esc_html( sprintf( vao_t( 'Et nos %s' ), vao_t( $vao_sub->name ) ) ); ?></strong></p>
						<?php echo wp_kses_post( $vao_gamme_text( $vao_sub ) ); ?>
					</div>
				<?php endforeach; ?>

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
	</div>
</main>

<?php
get_template_part( 'template-parts/brand-strip', null, array( 'muted' => true ) );
get_footer();
