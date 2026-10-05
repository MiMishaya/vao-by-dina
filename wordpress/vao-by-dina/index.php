<?php
/**
 * Modèle par défaut : résultats de recherche, articles, pages sans modèle dédié, 404.
 */

get_header();
?>

<main class="generic-content">
	<?php if ( is_search() ) : ?>
		<h1>Résultats pour « <?php echo esc_html( get_search_query() ); ?> »</h1>
	<?php elseif ( is_404() ) : ?>
		<h1>Page introuvable</h1>
		<p><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Retour à l'accueil</a></p>
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
		<p>Aucun résultat. Découvrez <a href="<?php echo esc_url( vao_url( 'gammes' ) ); ?>">tous nos produits</a>.</p>
	<?php endif; ?>
</main>

<?php
get_footer();
