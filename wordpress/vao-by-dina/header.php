<?php
/**
 * En-tête du site : <head>, barre de navigation (dégradé) et bannière carrousel.
 * Liens du menu : vao_nav() (inc/helpers.php). Logo et catalogue : Apparence > Personnaliser > Thème VAO.
 * Images de la bannière : menu « Bannière ».
 */

$vao_slides  = vao_hero_slides();
$vao_home    = vao_url( 'accueil' );
$vao_current = vao_current_nav();
$vao_icons   = array(
	'home' => '<path d="M3 11.5 12 4l9 7.5"/><path d="M5 10v10h14V10"/><path d="M10 20v-6h4v6"/>',
	'user' => '<circle cx="12" cy="8" r="4"/><path d="M4 21c0-4.4 3.6-7 8-7s8 2.6 8 7"/>',
	'sun'  => '<circle cx="12" cy="12" r="4"/><path d="M12 2v3M12 19v3M2 12h3M19 12h3M4.9 4.9l2.1 2.1M17 17l2.1 2.1M4.9 19.1 7 17M17 7l2.1-2.1"/>',
	'mail' => '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/>',
);
$vao_catalogue = vao_mod( 'catalogue_url' );

// Bannière affichée en premier selon la page (ordre du menu « Bannière »), comme sur la maquette.
$vao_starts = array( 'home' => 3, 'apropos' => 1, 'gammes' => 4, 'contact' => 4 );
$vao_view   = vao_view();
$vao_start  = isset( $vao_starts[ $vao_view ] ) && $vao_starts[ $vao_view ] < count( $vao_slides ) ? $vao_starts[ $vao_view ] : 0;
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="site-header">
	<a class="header-logo" href="<?php echo esc_url( $vao_home ); ?>">
		<img src="<?php echo esc_url( vao_mod( 'logo_header' ) ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ? get_bloginfo( 'name' ) : 'VAO' ); ?>" width="640" height="242">
	</a>

	<nav class="header-nav" id="site-nav" aria-label="<?php echo esc_attr( vao_t( 'Menu' ) ); ?>">
		<?php foreach ( vao_nav() as $vao_key => $vao_item ) : ?>
			<?php list( $vao_title, $vao_link, $vao_icon, $vao_children ) = $vao_item; ?>
			<div class="nav-item<?php echo $vao_children ? ' has-children' : ''; ?><?php echo $vao_key === $vao_current ? ' current' : ''; ?>">
				<a class="nav-link" href="<?php echo esc_url( 'gammes' === $vao_key ? vao_via_loading( $vao_link ) : $vao_link ); ?>"<?php echo $vao_key === $vao_current ? ' aria-current="page"' : ''; ?>>
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><?php echo $vao_icons[ $vao_icon ]; // SVG statique. ?></svg>
					<?php vao_e( $vao_title ); ?>
				</a>
				<?php if ( $vao_children ) : ?>
					<button type="button" class="nav-sub-toggle" aria-expanded="false" aria-label="<?php echo esc_attr( vao_t( $vao_title ) ); ?>"><span aria-hidden="true">▾</span></button>
					<div class="nav-sub"><?php vao_print_links( $vao_children ); ?></div>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
	</nav>

	<div class="header-tools">
		<div class="header-tools-row">
			<form class="site-search" id="site-search" role="search" method="get" action="<?php echo esc_url( $vao_home ); ?>">
				<input type="search" name="s" class="search-input" id="search-input" placeholder="<?php echo esc_attr( vao_t( 'Rechercher' ) ); ?>" aria-label="<?php echo esc_attr( vao_t( 'Rechercher' ) ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>">
				<button type="button" class="search-toggle" id="search-toggle" aria-expanded="false" aria-label="<?php echo esc_attr( vao_t( 'Rechercher' ) ); ?>">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" aria-hidden="true"><circle cx="11" cy="11" r="6.5"/><path d="m16 16 4.5 4.5"/></svg>
				</button>
			</form>
			<?php vao_language_switcher(); ?>
		</div>
		<?php if ( $vao_catalogue ) : ?>
			<a class="catalogue-btn" href="<?php echo esc_url( $vao_catalogue ); ?>" download>
				<?php vao_e( 'Catalogue' ); ?>
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 4v11M7 10l5 5 5-5M5 20h14"/></svg>
			</a>
		<?php endif; ?>
	</div>

	<button type="button" class="burger" id="burger" aria-expanded="false" aria-controls="site-nav" aria-label="<?php echo esc_attr( vao_t( 'Menu' ) ); ?>">
		<span></span><span></span><span></span>
	</button>
</header>

<div class="hero" id="home" data-start="<?php echo esc_attr( $vao_start ); ?>">
	<?php if ( $vao_slides ) : ?>
		<img class="hero-bg" src="<?php echo esc_url( $vao_slides[ $vao_start ]['src'] ); ?>" alt="<?php echo esc_attr( $vao_slides[ $vao_start ]['alt'] ); ?>" width="6000" height="2518">
	<?php endif; ?>
	<div class="hero-dots"></div>
</div>
