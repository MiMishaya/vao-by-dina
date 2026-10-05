<?php
/**
 * VAO by Dina - fonctions du thème.
 *
 * inc/helpers.php     : fonctions utilitaires (URL, images, menus)
 * inc/content.php     : types de contenu (Produits, Témoignages, Bannière, Logos de marques)
 * inc/customizer.php  : réglages modifiables dans Apparence > Personnaliser
 * inc/install.php     : création des pages, du contenu et des menus à l'activation
 * inc/forms.php       : envoi des formulaires par e-mail
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'VAO_VERSION', '1.1.0' );

require get_template_directory() . '/inc/helpers.php';
require get_template_directory() . '/inc/content.php';
require get_template_directory() . '/inc/customizer.php';
require get_template_directory() . '/inc/install.php';
require get_template_directory() . '/inc/forms.php';

function vao_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'style', 'script' ) );

	register_nav_menus(
		array(
			'gamme'       => 'Menu « Notre gamme »',
			'formulaires' => 'Menu « Formulaires »',
		)
	);
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

	wp_enqueue_style( 'vao-main', "$uri/css/main.css", array(), VAO_VERSION );
	wp_enqueue_script( 'vao-header', "$uri/js/header.js", array(), VAO_VERSION, true );
	wp_enqueue_script( 'vao-hero', "$uri/js/hero-carousel.js", array(), VAO_VERSION, true );
	wp_localize_script( 'vao-hero', 'VAO_HERO', array( 'slides' => vao_hero_slides() ) );

	if ( $view ) {
		wp_enqueue_style( "vao-$view", "$uri/css/$view.css", array( 'vao-main' ), VAO_VERSION );
		wp_enqueue_script( "vao-$view", "$uri/js/$view.js", array(), VAO_VERSION, true );
	}
	if ( 'formulaires' === $view ) {
		wp_localize_script( 'vao-formulaires', 'VAO_FORMS', array( 'revendeurUrl' => vao_url( 'devenir-revendeur' ) ) );
	}
}
add_action( 'wp_enqueue_scripts', 'vao_assets' );

/**
 * Classe de thème de couleur sur <body> pour les pages formulaires.
 */
function vao_body_class( $classes ) {
	if ( is_page_template( 'template-revendeur.php' ) ) {
		$classes[] = 'theme-revendeur';
	} elseif ( is_page_template( 'template-formulaires.php' ) ) {
		$classes[] = 'theme-echantillon';
	}
	return $classes;
}
add_filter( 'body_class', 'vao_body_class' );
