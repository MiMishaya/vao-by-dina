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
 * Pages du site : slug français => array( titre, modèle, titre malagasy, slug malagasy ).
 */
function vao_pages() {
	return array(
		'accueil'                      => array( 'Accueil', '', 'Fandraisana', 'fandraisana' ),
		'qui-sommes-nous'              => array( 'Qui sommes-nous', 'template-qui-sommes-nous.php', 'Iza izahay', 'iza-izahay' ),
		'gammes'                       => array( 'Nos marques', 'template-gammes.php', 'Ny marikanay', 'vokatra' ),
		'nous-contacter'               => array( 'Nous contacter', 'template-contact.php', 'Hifandray aminay', 'hifandray' ),
		'chargement'                   => array( 'Chargement', 'template-chargement.php', 'Miandry', 'miandry' ),
		'politique-de-confidentialite' => array( 'Politique de confidentialité', '', 'Politika momba ny tsiambaratelo', 'politika-tsiambaratelo' ),
		'conditions-generales'         => array( 'Conditions générales', '', 'Fepetra ankapobeny', 'fepetra-ankapobeny' ),
	);
}

/**
 * Navigation principale : array( titre, url, icône, sous-liens array( array( titre, url ), ... ) ).
 * Les ancres correspondent aux id des sections des pages.
 */
function vao_nav() {
	return array(
		'accueil'         => array( 'Accueil', vao_url( 'accueil' ), 'home', array() ),
		'qui-sommes-nous' => array(
			'Qui sommes-nous',
			vao_url( 'qui-sommes-nous' ),
			'user',
			array(
				array( 'Notre engagement', vao_url( 'accueil', 'engagement' ) ),
				array( 'Notre gage de qualité', vao_url( 'qui-sommes-nous', 'qualite' ) ),
				array( 'Nos valeurs', vao_url( 'qui-sommes-nous', 'valeurs' ) ),
				array( 'Nos actions RSE', vao_url( 'qui-sommes-nous', 'rse' ) ),
			),
		),
		'gammes'          => array(
			'Nos marques',
			vao_url( 'gammes' ),
			'sun',
			array(
				array( 'Savon de ménage', vao_url( 'gammes', 'menage' ) ),
				array( 'Savon barre', vao_url( 'gammes', 'savonbar' ) ),
				array( 'Savon de toilette', vao_url( 'gammes', 'toilette' ) ),
				array( 'Détergent en poudre', vao_url( 'gammes', 'detergent' ) ),
				array( 'Détergent en barre', vao_url( 'gammes', 'detergent-barre' ) ),
			),
		),
		'nous-contacter'  => array(
			'Nous contacter',
			vao_url( 'nous-contacter' ),
			'mail',
			array(
				array( 'Devenir distributeur', vao_url( 'nous-contacter', 'distributeur' ) ),
				array( "Demande d'informations", vao_url( 'nous-contacter', 'information' ) ),
				array( "Nos offres d'emploi", vao_url( 'nous-contacter', 'offres' ) ),
				array( 'Candidature spontanée', vao_url( 'nous-contacter', 'candidature' ) ),
			),
		),
	);
}

/**
 * Clé de vao_nav() correspondant à la page affichée (entrée active du menu).
 */
function vao_current_nav() {
	$views = array(
		'home'    => 'accueil',
		'apropos' => 'qui-sommes-nous',
		'gammes'  => 'gammes',
		'contact' => 'nous-contacter',
	);
	$view = vao_view();
	return isset( $views[ $view ] ) ? $views[ $view ] : '';
}

/**
 * Images d'une galerie : images ajoutées dans l'admin (médias attachés), sinon images livrées avec le thème.
 *
 * @return array array( array( 'thumb' => url, 'full' => url ), ... )
 */
function vao_gallery( $post ) {
	$post   = get_post( $post );
	$images = array();
	if ( ! $post ) {
		return $images;
	}
	$attached = get_attached_media( 'image', $post->ID );
	foreach ( $attached as $attachment ) {
		$images[] = array(
			'thumb' => wp_get_attachment_image_url( $attachment->ID, 'medium_large' ),
			'full'  => wp_get_attachment_image_url( $attachment->ID, 'full' ),
		);
	}
	if ( ! $images ) {
		foreach ( (array) get_post_meta( $post->ID, '_vao_galerie', true ) as $file ) {
			if ( $file ) {
				$images[] = array(
					'thumb' => vao_img( $file ),
					'full'  => vao_img( $file ),
				);
			}
		}
	}
	return $images;
}

/**
 * URL d'une page du site par son slug français, avec ancre optionnelle.
 * Avec Polylang, renvoie la version de la page dans la langue courante (ou $lang).
 */
function vao_url( $slug, $fragment = '', $lang = '' ) {
	$lang = $lang ? $lang : vao_lang();

	if ( 'accueil' === $slug ) {
		$url = ( $lang && function_exists( 'pll_home_url' ) ) ? pll_home_url( $lang ) : home_url( '/' );
	} else {
		$page = get_page_by_path( $slug );
		if ( $page && $lang && function_exists( 'pll_get_post' ) ) {
			$translated = pll_get_post( $page->ID, $lang );
			if ( $translated ) {
				$page = get_post( $translated );
			}
		}
		$url = $page ? get_permalink( $page ) : home_url( '/' . $slug . '/' );
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
	if ( is_page_template( 'template-qui-sommes-nous.php' ) ) {
		return 'apropos';
	}
	if ( is_page_template( 'template-gammes.php' ) ) {
		return 'gammes';
	}
	if ( is_page_template( 'template-contact.php' ) ) {
		return 'contact';
	}
	if ( is_page_template( 'template-chargement.php' ) ) {
		return 'chargement';
	}
	return '';
}

/**
 * Éléments publiés d'un type de contenu, dans l'ordre choisi dans l'admin (champ « Ordre »).
 * Types traduits (bannière, témoignages) : éléments de la langue courante, ou à défaut
 * ceux de la langue par défaut (le site reste complet tant que la traduction n'est pas faite).
 */
function vao_items( $post_type, $args = array() ) {
	$args = array_merge(
		array(
			'post_type'   => $post_type,
			'numberposts' => -1,
			'orderby'     => array( 'menu_order' => 'ASC', 'date' => 'ASC' ),
		),
		$args
	);

	$lang = vao_lang();
	if ( ! $lang || ! in_array( $post_type, vao_translated_types(), true ) ) {
		return get_posts( $args );
	}

	$items = get_posts( array_merge( $args, array( 'lang' => $lang ) ) );
	if ( ! $items && function_exists( 'pll_default_language' ) && pll_default_language() !== $lang ) {
		$items = get_posts( array_merge( $args, array( 'lang' => pll_default_language() ) ) );
	}
	return $items;
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
 * Affiche une liste de liens <a>. Les liens vers la page des gammes passent par l'écran de chargement.
 */
function vao_print_links( $links ) {
	$gammes = vao_url( 'gammes' );
	foreach ( $links as $link ) {
		list( $title, $url ) = $link;
		if ( 0 === strpos( $url, $gammes ) ) {
			$url = vao_via_loading( $url );
		}
		printf( '<a href="%s">%s</a>', esc_url( $url ), esc_html( vao_t( $title ) ) );
	}
}

/**
 * Lien "tel:" à partir d'un numéro affiché.
 */
function vao_tel_link( $number ) {
	return 'tel:' . preg_replace( '/[^0-9+]/', '', $number );
}
