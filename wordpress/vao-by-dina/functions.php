<?php
/**
 * VAO by Dina - fonctions du thème.
 *
 * inc/helpers.php     : fonctions utilitaires (URL, images, navigation, galeries)
 * inc/i18n.php        : site bilingue français / malagasy (extension Polylang)
 * inc/strings/*.php   : textes de chaque page à traduire en malagasy
 * inc/content.php     : types de contenu (Produits, Témoignages, Bannière, Logos, Avis, Collaborateurs, RSE, Offres, Distributeurs)
 * inc/customizer.php  : réglages modifiables dans Apparence > Personnaliser
 * inc/install.php     : création des pages et du contenu à l'activation (+ mise à jour v2)
 * inc/forms.php       : envoi des formulaires par e-mail
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'VAO_VERSION', '2.0.0' );

require get_template_directory() . '/inc/helpers.php';
require get_template_directory() . '/inc/i18n.php';
require get_template_directory() . '/inc/content.php';
require get_template_directory() . '/inc/customizer.php';
require get_template_directory() . '/inc/install.php';
require get_template_directory() . '/inc/forms.php';

function vao_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'style', 'script' ) );
}
add_action( 'after_setup_theme', 'vao_setup' );

/* -------------------------------------------------------------------------
 * CSS / JS
 * ---------------------------------------------------------------------- */

function vao_assets() {
	$uri  = get_template_directory_uri() . '/assets';
	$view = vao_view();

	if ( 'chargement' === $view ) {
		wp_enqueue_style( 'vao-chargement', "$uri/css/chargement.css", array(), VAO_VERSION );
		wp_enqueue_script( 'vao-chargement', "$uri/js/chargement.js", array(), VAO_VERSION, true );
		wp_localize_script( 'vao-chargement', 'VAO_LOADING', array( 'fallback' => vao_url( 'gammes' ) ) );
		return;
	}

	wp_enqueue_style( 'vao-fonts', 'https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,400;0,500;0,600;0,700;1,600;1,800&family=Poppins:wght@700;800;900&display=swap', array(), null );
	wp_enqueue_style( 'vao-main', "$uri/css/main.css", array( 'vao-fonts' ), VAO_VERSION );
	wp_enqueue_style( 'vao-components', "$uri/css/components.css", array( 'vao-main' ), VAO_VERSION );
	wp_enqueue_script( 'vao-header', "$uri/js/header.js", array(), VAO_VERSION, true );
	wp_enqueue_script( 'vao-hero', "$uri/js/hero-carousel.js", array(), VAO_VERSION, true );
	wp_localize_script( 'vao-hero', 'VAO_HERO', array( 'slides' => vao_hero_slides() ) );
	// Carrousels à flèches et galerie plein écran, partagés par les pages (voir assets/js/ui.js).
	wp_enqueue_script( 'vao-ui', "$uri/js/ui.js", array(), VAO_VERSION, true );
	wp_localize_script(
		'vao-ui',
		'VAO_UI',
		array(
			'prev'  => vao_t( 'Précédent' ),
			'next'  => vao_t( 'Suivant' ),
			'close' => vao_t( 'Fermer' ),
		)
	);

	if ( $view ) {
		wp_enqueue_style( "vao-$view", "$uri/css/$view.css", array( 'vao-main' ), VAO_VERSION );
		wp_enqueue_script( "vao-$view", "$uri/js/$view.js", array( 'vao-ui' ), VAO_VERSION, true );
	}
}
add_action( 'wp_enqueue_scripts', 'vao_assets' );
