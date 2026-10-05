<?php
/**
 * Fonctions utilitaires du thème.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * URL d'une image livrée avec le thème (assets/img/...). Encode les espaces et accents.
 * Les photos sans transparence sont converties en .jpg à la construction (build.ps1) :
 * si le .png demandé n'existe pas, on utilise le .jpg du même nom.
 */
function vao_img( $path ) {
	$base = get_template_directory() . '/assets/img/';
	if ( ! file_exists( $base . $path ) ) {
		$jpg = preg_replace( '/\.png$/i', '.jpg', $path );
		if ( file_exists( $base . $jpg ) ) {
			$path = $jpg;
		}
	}
	$segments = array_map( 'rawurlencode', explode( '/', $path ) );
	return get_template_directory_uri() . '/assets/img/' . implode( '/', $segments );
}

/**
 * Pages du site : slug => array( titre, modèle ).
 */
function vao_pages() {
	return array(
		'accueil'           => array( 'Accueil', '' ),
		'gammes'            => array( 'Tous nos gammes', 'template-gammes.php' ),
		'devenir-revendeur' => array( 'Devenir revendeur', 'template-revendeur.php' ),
		'formulaires'       => array( "Demande d'échantillon & Candidature", 'template-formulaires.php' ),
		'chargement'        => array( 'Chargement', 'template-chargement.php' ),
	);
}

/**
 * URL d'une page du site par son slug, avec ancre optionnelle.
 */
function vao_url( $slug, $fragment = '' ) {
	if ( 'accueil' === $slug ) {
		$url = home_url( '/' );
	} else {
		$page = get_page_by_path( $slug );
		$url  = $page ? get_permalink( $page ) : home_url( '/' . $slug . '/' );
	}
	return $fragment ? $url . '#' . $fragment : $url;
}

/**
 * Fait passer un lien par l'écran de chargement.
 */
function vao_via_loading( $url ) {
	return add_query_arg( 'to', rawurlencode( $url ), vao_url( 'chargement' ) );
}

/**
 * Lien vers la page des gammes (ancre = slug de la gamme) en passant par l'écran de chargement.
 */
function vao_gamme_url( $fragment = '' ) {
	return vao_via_loading( vao_url( 'gammes', $fragment ) );
}

/**
 * Identifiant de la vue courante, utilisé pour charger le CSS/JS de la page.
 */
function vao_view() {
	if ( is_front_page() ) {
		return 'home';
	}
	if ( is_page_template( 'template-gammes.php' ) ) {
		return 'gammes';
	}
	if ( is_page_template( 'template-revendeur.php' ) || is_page_template( 'template-formulaires.php' ) ) {
		return 'formulaires';
	}
	if ( is_page_template( 'template-chargement.php' ) ) {
		return 'chargement';
	}
	return '';
}

/**
 * Éléments publiés d'un type de contenu, dans l'ordre choisi dans l'admin (champ « Ordre »).
 */
function vao_items( $post_type, $args = array() ) {
	return get_posts(
		array_merge(
			array(
				'post_type'   => $post_type,
				'numberposts' => -1,
				'orderby'     => array( 'menu_order' => 'ASC', 'date' => 'ASC' ),
			),
			$args
		)
	);
}

/**
 * Image d'un élément : l'image choisie dans l'admin, sinon l'image d'origine livrée avec le thème.
 */
function vao_post_img( $post, $size = 'full' ) {
	$post = get_post( $post );
	if ( ! $post ) {
		return '';
	}
	$url = get_the_post_thumbnail_url( $post, $size );
	if ( ! $url ) {
		$file = get_post_meta( $post->ID, '_vao_theme_img', true );
		$url  = $file ? vao_img( $file ) : '';
	}
	return $url;
}

/**
 * Texte alternatif de l'image d'un élément.
 */
function vao_post_alt( $post ) {
	$post = get_post( $post );
	$alt  = get_post_meta( $post->ID, '_vao_alt', true );
	return $alt ? $alt : get_the_title( $post );
}

/**
 * Images de la bannière (carrousel) : array( array( 'src' => ..., 'alt' => ... ), ... ).
 */
function vao_hero_slides() {
	return array_map(
		function ( $slide ) {
			return array( 'src' => vao_post_img( $slide ), 'alt' => get_the_title( $slide ) );
		},
		vao_items( 'vao_banniere' )
	);
}

/**
 * Liens d'un emplacement de menu (Apparence > Menus), ou les liens par défaut si aucun menu n'est attribué.
 *
 * @param string $location Emplacement du menu.
 * @param array  $fallback Liens par défaut : array( array( titre, url ), ... ).
 * @return array array( array( titre, url ), ... )
 */
function vao_menu_links( $location, $fallback ) {
	$locations = get_nav_menu_locations();
	if ( empty( $locations[ $location ] ) ) {
		return $fallback;
	}
	$items = wp_get_nav_menu_items( $locations[ $location ] );
	if ( ! $items ) {
		return $fallback;
	}
	$links = array();
	foreach ( $items as $item ) {
		if ( ! $item->menu_item_parent ) {
			$links[] = array( $item->title, $item->url );
		}
	}
	return $links;
}

/**
 * Affiche une liste de liens <a>. Les liens vers la page des gammes passent par l'écran de chargement.
 */
function vao_print_links( $links ) {
	$gammes = vao_url( 'gammes' );
	foreach ( $links as $link ) {
		list( $title, $url ) = $link;
		if ( 0 === strpos( $url, $gammes ) ) {
			$url = vao_via_loading( $url );
		}
		printf( '<a href="%s">%s</a>', esc_url( $url ), esc_html( $title ) );
	}
}

/**
 * Lien "tel:" à partir d'un numéro affiché.
 */
function vao_tel_link( $number ) {
	return 'tel:' . preg_replace( '/[^0-9+]/', '', $number );
}
