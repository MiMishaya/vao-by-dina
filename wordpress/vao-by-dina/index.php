<?php
/**
 * Modèle par défaut : résultats de recherche, articles, pages sans modèle dédié, 404.
 */

get_header();
?>

<main class="generic-content">
	<?php if ( is_search() ) : ?>
		<h1><?php echo esc_html( sprintf( vao_t( 'Résultats pour « %s »' ), get_search_query() ) ); ?></h1>
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
		<?php the_posts_pagination(); ?>
	<?php elseif ( is_search() ) : ?>
		<p><?php vao_e( 'Aucun résultat.' ); ?> <a href="<?php echo esc_url( vao_url( 'gammes' ) ); ?>"><?php vao_e( 'Découvrez tous nos produits' ); ?></a></p>
	<?php endif; ?>
</main>

<?php
get_footer();
