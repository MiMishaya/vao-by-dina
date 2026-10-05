<?php
/**
 * En-tête du site : <head>, bannière carrousel et navigation superposée.
 * Menus : Apparence > Menus. Logo : Apparence > Personnaliser > Thème VAO. Images : menu « Bannière ».
 */

$vao_slides = vao_hero_slides();

$vao_gamme_links = vao_menu_links(
	'gamme',
	array(
		array( 'Tous les produits', vao_url( 'gammes' ) ),
		array( 'Détergent en poudre', vao_url( 'gammes', 'detergent' ) ),
		array( 'Détergent en barre', vao_url( 'gammes', 'detergent' ) ),
		array( 'Savon de toilette', vao_url( 'gammes', 'toilette' ) ),
		array( 'Savon en barre', vao_url( 'gammes', 'savonbar' ) ),
		array( 'Savon translucide', vao_url( 'gammes', 'savonbar' ) ),
		array( 'Savon de ménage', vao_url( 'gammes', 'menage' ) ),
	)
);
$vao_form_links = vao_menu_links(
	'formulaires',
	array(
		array( 'Devenir revendeur', vao_url( 'devenir-revendeur' ) ),
		array( "Demande d'échantillon", vao_url( 'formulaires', 'echantillon' ) ),
		array( 'Candidature spontanée', vao_url( 'formulaires', 'candidature' ) ),
	)
);
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div class="site-top">
	<div class="hero" id="home">
		<?php if ( $vao_slides ) : ?>
			<img class="hero-bg" src="<?php echo esc_url( $vao_slides[0]['src'] ); ?>" alt="<?php echo esc_attr( $vao_slides[0]['alt'] ); ?>">
		<?php endif; ?>
		<div class="hero-dots"></div>
	</div>

	<header class="site-header">
		<div class="header-left">
			<div class="header-home">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="Accueil">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<path d="M3 11.5 12 4l9 7.5"/>
						<path d="M5 10v10h14V10"/>
						<path d="M10 20v-6h4v6"/>
					</svg>
				</a>
			</div>
			<div class="header-nav">
				<div class="nav-dropdown">
					<button type="button" class="dropdown-toggle" id="gamme-toggle" aria-expanded="false">Notre gamme ▼</button>
					<div class="nav-dropdown-panel" id="gamme-panel"><?php vao_print_links( $vao_gamme_links ); ?></div>
				</div>
				<div class="nav-dropdown">
					<button type="button" class="dropdown-toggle" id="formulaire-toggle" aria-expanded="false">Formulaires ▼</button>
					<div class="nav-dropdown-panel" id="formulaire-panel"><?php vao_print_links( $vao_form_links ); ?></div>
				</div>
			</div>
		</div>
		<div class="header-logo">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<img src="<?php echo esc_url( vao_mod( 'logo_header' ) ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ? get_bloginfo( 'name' ) : 'VAO' ); ?>">
			</a>
		</div>
		<div class="header-right">
			<form class="site-search" id="site-search" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<input type="text" name="s" class="search-inline-input" id="search-panel-input" placeholder="Rechercher" aria-label="Rechercher" value="<?php echo esc_attr( get_search_query() ); ?>">
				<button type="button" class="header-menu-icon" id="search-toggle" aria-expanded="false" aria-label="Rechercher">
					<img src="<?php echo esc_url( vao_img( 'Render element 02/Element-05.png' ) ); ?>" alt="">
				</button>
			</form>
			<div class="site-menu">
				<button type="button" class="header-menu-icon" id="site-menu-toggle" aria-expanded="false" aria-label="Menu">
					<img src="<?php echo esc_url( vao_img( 'Render element 02/Element-06.png' ) ); ?>" alt="">
				</button>
				<div class="nav-dropdown-panel align-right" id="site-menu-panel"><?php vao_print_links( $vao_form_links ); ?></div>
			</div>
			<div class="mobile-menu">
				<button type="button" class="header-menu-icon burger-icon" id="mobile-menu-toggle" aria-expanded="false" aria-label="Menu">
					<span></span><span></span><span></span>
				</button>
				<div class="nav-dropdown-panel align-right" id="mobile-menu-panel">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>">Accueil</a>
					<div class="mobile-nav-section">
						<button type="button" class="mobile-nav-toggle" id="mnav-gamme-toggle" aria-expanded="false">
							Notre gamme <span class="mobile-nav-caret">▾</span>
						</button>
						<div class="mobile-nav-submenu" id="mnav-gamme-submenu"><?php vao_print_links( $vao_gamme_links ); ?></div>
					</div>
					<div class="mobile-nav-section">
						<button type="button" class="mobile-nav-toggle" id="mnav-formulaire-toggle" aria-expanded="false">
							Formulaires <span class="mobile-nav-caret">▾</span>
						</button>
						<div class="mobile-nav-submenu" id="mnav-formulaire-submenu"><?php vao_print_links( $vao_form_links ); ?></div>
					</div>
				</div>
			</div>
		</div>
	</header>
</div>
