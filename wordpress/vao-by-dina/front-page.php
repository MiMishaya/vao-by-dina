<?php
/**
 * Page d'accueil.
 * Textes et images : Apparence > Personnaliser > Thème VAO > Page d'accueil.
 * Témoignages : menu « Témoignages ». Produits recommandés : case à cocher dans chaque produit.
 */

get_header();

$vao_testimonials = vao_items( 'vao_temoignage' );
$vao_recommended  = vao_items(
	'vao_produit',
	array(
		'meta_key'   => '_vao_recommande',
		'meta_value' => '1',
	)
);

$vao_cta = array(
	array( 'orange', vao_url( 'devenir-revendeur' ), 1 ),
	array( 'pink', vao_url( 'formulaires', 'echantillon' ), 2 ),
	array( 'blue', vao_url( 'formulaires', 'candidature' ), 3 ),
);
?>

<!-- ABOUT / SDOI -->
<section class="about-section" id="about">
	<div class="about-container">
		<div class="about-logo">
			<img src="<?php echo esc_url( vao_mod( 'logo_sdoi' ) ); ?>" alt="SDOI - Savons et Détergents de l'Océan Indien">
		</div>
		<div>
			<div class="about-heading"><?php echo esc_html( vao_mod( 'about_surtitre' ) ); ?></div>
			<h2 class="about-title"><?php echo esc_html( vao_mod( 'about_titre' ) ); ?></h2>
			<p class="about-text"><?php echo nl2br( esc_html( vao_mod( 'about_texte' ) ) ); ?></p>
		</div>
	</div>
</section>

<!-- BRAND STRIP -->
<section class="brand-section">
	<div class="brand-container">
		<div class="brand-heading"><?php echo esc_html( vao_mod( 'marques_titre' ) ); ?></div>
		<?php get_template_part( 'template-parts/brand-strip' ); ?>
	</div>
</section>

<!-- STATS -->
<section class="stats-section">
	<div class="stats-container">
		<img class="stats-image" src="<?php echo esc_url( vao_mod( 'stats_image' ) ); ?>" alt="<?php echo esc_attr( vao_mod( 'stats_alt' ) ); ?>">
	</div>
</section>

<?php if ( $vao_testimonials ) : ?>
<!-- TESTIMONIALS -->
<section class="testimonials-section">
	<div class="testimonials-container">
		<div class="testimonials-title"><?php echo esc_html( vao_mod( 'temoignages_titre' ) ); ?></div>
		<div class="testimonials-shell">
			<button type="button" class="testimonial-arrow" id="testi-prev" aria-label="Précédent">‹</button>
			<div class="testimonials-track" id="testi-track">
				<?php foreach ( $vao_testimonials as $vao_item ) : ?>
					<div class="testimonial-card"><img src="<?php echo esc_url( vao_post_img( $vao_item, 'large' ) ); ?>" alt="<?php echo esc_attr( get_the_title( $vao_item ) ); ?>"></div>
				<?php endforeach; ?>
			</div>
			<button type="button" class="testimonial-arrow" id="testi-next" aria-label="Suivant">›</button>
		</div>
	</div>
</section>
<?php endif; ?>

<?php if ( $vao_recommended ) : ?>
<!-- RECOMMENDED PRODUCTS -->
<section class="recommended-section">
	<div class="recommended-container">
		<h2 class="recommended-title"><?php echo esc_html( vao_mod( 'recommandes_titre' ) ); ?></h2>
		<div class="recommended-grid">
			<?php foreach ( $vao_recommended as $vao_product ) : ?>
				<?php
				$vao_terms  = get_the_terms( $vao_product, 'vao_gamme' );
				$vao_anchor = $vao_terms && ! is_wp_error( $vao_terms ) ? $vao_terms[0]->slug : '';
				$vao_color  = get_post_meta( $vao_product->ID, '_vao_couleur', true );
				?>
				<div class="product-card <?php echo esc_attr( $vao_color ? $vao_color : 'blue' ); ?>">
					<div class="product-card-image"><img src="<?php echo esc_url( vao_post_img( $vao_product, 'medium_large' ) ); ?>" alt="<?php echo esc_attr( vao_post_alt( $vao_product ) ); ?>"></div>
					<div class="product-card-content">
						<h4 class="product-card-title"><?php echo esc_html( get_the_title( $vao_product ) ); ?></h4>
						<a class="btn" href="<?php echo esc_url( vao_gamme_url( $vao_anchor ) ); ?>"><?php echo esc_html( vao_mod( 'recommandes_bouton' ) ); ?></a>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
		<div class="recommended-more"><a href="<?php echo esc_url( vao_gamme_url() ); ?>"><?php echo esc_html( vao_mod( 'recommandes_lien' ) ); ?></a></div>
	</div>
</section>
<?php endif; ?>

<!-- CTA FORMULAIRES -->
<section class="cta-section">
	<div class="cta-container">
		<div class="cta-title"><?php echo esc_html( vao_mod( 'cta_titre' ) ); ?></div>
		<div class="cta-grid">
			<?php foreach ( $vao_cta as $vao_card ) : list( $vao_class, $vao_link, $vao_n ) = $vao_card; ?>
				<a class="cta-card <?php echo esc_attr( $vao_class ); ?>" href="<?php echo esc_url( $vao_link ); ?>">
					<img src="<?php echo esc_url( vao_mod( "cta{$vao_n}_image" ) ); ?>" alt="<?php echo esc_attr( vao_mod( "cta{$vao_n}_titre" ) ); ?>">
					<div class="cta-card-overlay">
						<div class="cta-card-title"><?php echo esc_html( vao_mod( "cta{$vao_n}_titre" ) ); ?></div>
						<div class="cta-card-text"><?php echo esc_html( vao_mod( "cta{$vao_n}_texte" ) ); ?></div>
						<div class="cta-card-link"><?php echo esc_html( vao_mod( 'cta_lien' ) ); ?></div>
					</div>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<?php
get_footer();
