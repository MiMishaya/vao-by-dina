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
 * inc/demandes.php    : historique des demandes reçues (menu « Demandes » de l'admin, export CSV, purge à 12 mois)
 * inc/admin-mg.php    : champs « en malagasy » à côté du français (Personnaliser, gammes, collaborateurs, RSE)
 * inc/admin-guide.php : encadré « Guide du site » du tableau de bord
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'VAO_VERSION', '2.5.3' );

require get_template_directory() . '/inc/helpers.php';
require get_template_directory() . '/inc/i18n.php';
require get_template_directory() . '/inc/content.php';
require get_template_directory() . '/inc/customizer.php';
require get_template_directory() . '/inc/install.php';
require get_template_directory() . '/inc/forms.php';
require get_template_directory() . '/inc/demandes.php';
require get_template_directory() . '/inc/admin-mg.php';
require get_template_directory() . '/inc/admin-guide.php';

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
		wp_enqueue_style( 'vao-fonts', "$uri/fonts/fonts.css", array(), VAO_VERSION );
		wp_enqueue_style( 'vao-chargement', "$uri/css/chargement.css", array( 'vao-fonts' ), VAO_VERSION );
		wp_enqueue_script( 'vao-chargement', "$uri/js/chargement.js", array(), VAO_VERSION, true );
		wp_localize_script( 'vao-chargement', 'VAO_LOADING', array( 'fallback' => vao_url( 'gammes' ) ) );
		return;
	}

	// Polices hébergées avec le thème (plus rapide qu'un serveur externe, pas d'IP visiteur envoyée à Google).
	wp_enqueue_style( 'vao-fonts', "$uri/fonts/fonts.css", array(), VAO_VERSION );
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
			'valid' => array(
				'required' => vao_t( 'Merci de remplir ce champ.' ),
				'check'    => vao_t( 'Merci de cocher cette case pour continuer.' ),
				'select'   => vao_t( 'Merci de choisir une option.' ),
				'email'    => vao_t( 'Merci de saisir une adresse e-mail valide.' ),
			),
		)
	);

	if ( $view ) {
		wp_enqueue_style( "vao-$view", "$uri/css/$view.css", array( 'vao-main' ), VAO_VERSION );
		wp_enqueue_script( "vao-$view", "$uri/js/$view.js", array( 'vao-ui' ), VAO_VERSION, true );
	}
}
add_action( 'wp_enqueue_scripts', 'vao_assets' );

/**
 * Précharge la police du texte courant (Inter, alphabet latin) : évite le changement de police visible au chargement.
 */
function vao_preload_font() {
	printf(
		'<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
		esc_url( get_template_directory_uri() . '/assets/fonts/inter-400-latin.woff2' )
	);
}
add_action( 'wp_head', 'vao_preload_font', 1 );

/*
 * Émojis WordPress : script et styles chargés sur chaque page, inutiles ici
 * (les navigateurs actuels affichent les émojis nativement).
 */
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'admin_print_styles', 'print_emoji_styles' );

/**
 * Titre de l'onglet des pages de recherche et 404 : WordPress n'a pas de traduction malagasy
 * (et l'anglais s'affiche si le site n'est pas réglé en français).
 */
function vao_document_title( $parts ) {
	if ( is_search() ) {
		$parts['title'] = sprintf( vao_t( 'Résultats pour « %s »' ), get_search_query( false ) );
	} elseif ( is_404() ) {
		$parts['title'] = vao_t( 'Page introuvable' );
	}
	return $parts;
}
add_filter( 'document_title_parts', 'vao_document_title' );
