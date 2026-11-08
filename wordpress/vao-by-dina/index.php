<?php
/**
 * Modèle par défaut : résultats de recherche, articles, pages sans modèle dédié, 404.
 */

get_header();
?>

<main class="generic-content">
	<?php
	$vao_catalogue = is_search() ? vao_search_catalogue( get_search_query( false ) ) : array();
	$vao_sections  = array(
		'gammes'   => 'Nos Marques',
		'produits' => 'Nos produits VAO',
	);
	?>
	<?php if ( is_search() ) : ?>
		<h1><?php echo esc_html( sprintf( vao_t( 'Résultats pour « %s »' ), get_search_query( false ) ) ); ?></h1>
		<?php foreach ( $vao_sections as $vao_key => $vao_heading ) : ?>
			<?php if ( ! empty( $vao_catalogue[ $vao_key ] ) ) : ?>
				<h2><?php vao_e( $vao_heading ); ?></h2>
				<ul class="search-catalogue">
					<?php foreach ( $vao_catalogue[ $vao_key ] as $vao_hit ) : ?>
						<li><a href="<?php echo esc_url( $vao_hit[1] ); ?>"><?php echo esc_html( $vao_hit[0] ); ?></a></li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		<?php endforeach; ?>
	<?php elseif ( is_404() ) : ?>
		<h1><?php vao_e( 'Page introuvable' ); ?></h1>
		<p><a href="<?php echo esc_url( vao_url( 'accueil' ) ); ?>"><?php vao_e( "Retour à l'accueil" ); ?></a></p>
	<?php endif; ?>

	<?php if ( have_posts() ) : ?>
		<?php while ( have_posts() ) : the_post(); ?>
			<article <?php post_class(); ?>>
				<?php if ( is_singular() ) : ?>
					<h1><?php the_title(); ?></h1>
					<?php the_content(); ?>
				<?php else : ?>
					<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
					<?php the_excerpt(); ?>
				<?php endif; ?>
			</article>
		<?php endwhile; ?>
		<?php
		// Libellés WordPress absents en malagasy : on passe ceux du dictionnaire du thème.
		the_posts_pagination(
			array(
				'prev_text'          => vao_t( 'Précédent' ),
				'next_text'          => vao_t( 'Suivant' ),
				'screen_reader_text' => vao_t( 'Pages' ),
			)
		);
		?>
	<?php elseif ( is_search() && ! array_filter( $vao_catalogue ) ) : ?>
		<p><?php vao_e( 'Aucun résultat.' ); ?> <a href="<?php echo esc_url( vao_url( 'gammes' ) ); ?>"><?php vao_e( 'Découvrez tous nos produits' ); ?></a></p>
	<?php endif; ?>
</main>

<?php
get_footer();
