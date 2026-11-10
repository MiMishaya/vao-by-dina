<?php
/**
 * Fonctions utilitaires du thème.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * URL d'une image livrée avec le thème (assets/img/...). Encode les espaces et accents.
 * La construction (build.ps1 / optimize-images.php) crée un .webp de chaque image et convertit
 * les PNG opaques en .jpg : on sert le .webp s'il existe, sinon le .png, sinon le .jpg du même nom.
 */
function vao_img( $path ) {
	$base = get_template_directory() . '/assets/img/';
	if ( preg_match( '/\.(png|jpe?g)$/i', $path ) ) {
		$stem = preg_replace( '/\.(png|jpe?g)$/i', '', $path );
		foreach ( array( $stem . '.webp', $path, $stem . '.jpg' ) as $candidate ) {
			if ( file_exists( $base . $candidate ) ) {
				$path = $candidate;
				break;
			}
		}
	}
	return vao_img_uri( $path );
}

function vao_img_uri( $path ) {
	$segments = array_map( 'rawurlencode', explode( '/', $path ) );
	return get_template_directory_uri() . '/assets/img/' . implode( '/', $segments );
}

/**
 * URL de la variante réduite « nom-<largeur>.webp » d'une image du thème (créée par optimize-images.php
 * pour la bannière et les photos RSE), ou "" si elle n'existe pas.
 */
function vao_img_variant( $path, $width ) {
	$variant = preg_replace( '/\.(png|jpe?g|webp)$/i', '', $path ) . '-' . (int) $width . '.webp';
	return file_exists( get_template_directory() . '/assets/img/' . $variant ) ? vao_img_uri( $variant ) : '';
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
			'Nos Marques',
			vao_url( 'gammes' ),
			'sun',
			vao_nav_gammes(),
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
 * Sous-menu « Nos marques » : une entrée par gamme existante, dans l'ordre des gammes.
 * Les gammes d'origine gardent le libellé de la maquette ; une gamme ajoutée par le client prend son nom,
 * une gamme supprimée disparaît du menu.
 */
function vao_nav_gammes() {
	$labels = array(
		'menage'          => 'Savon de ménage',
		'savonbar'        => 'Savon barre',
		'toilette'        => 'Savon de toilette',
		'detergent'       => 'Détergent en poudre',
		'detergent-barre' => 'Détergent en barre',
	);
	$links  = array();
	foreach ( vao_gammes() as $term ) {
		$label   = isset( $labels[ $term->slug ] ) ? $labels[ $term->slug ] : $term->name;
		$links[] = array( $label, vao_url( 'gammes', $term->slug ) );
	}
	return $links;
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
				$full     = vao_img( $file );
				$thumb    = vao_img_variant( $file, 640 );
				$images[] = array(
					'thumb' => $thumb ? $thumb : $full,
					'full'  => $full,
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
		$page = vao_theme_page( $slug );
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
 * Page française du thème par son slug ; si le client a changé le slug, retrouvée par son modèle
 * (pages à modèle dédié : gammes, contact, qui-sommes-nous, chargement). null si introuvable.
 */
function vao_theme_page( $slug ) {
	static $cache = array();
	if ( array_key_exists( $slug, $cache ) ) {
		return $cache[ $slug ];
	}
	$page = get_page_by_path( $slug );
	if ( $page && 'publish' !== $page->post_status ) {
		$page = null;
	}
	$pages = vao_pages();
	if ( ! $page && ! empty( $pages[ $slug ][1] ) ) {
		$found = get_posts(
			array(
				'post_type'   => 'page',
				'numberposts' => 1,
				'meta_key'    => '_wp_page_template', // phpcs:ignore WordPress.DB.SlowDBQuery -- repli rare (slug modifié).
				'meta_value'  => $pages[ $slug ][1], // phpcs:ignore WordPress.DB.SlowDBQuery
				'lang'        => function_exists( 'pll_default_language' ) ? pll_default_language() : '',
			)
		);
		$page = $found ? $found[0] : null;
	}
	$cache[ $slug ] = $page;
	return $page;
}

/**
 * Fait passer un lien par l'écran de chargement (lien direct si la page de chargement a été supprimée).
 */
function vao_via_loading( $url ) {
	if ( ! vao_theme_page( 'chargement' ) ) {
		return $url;
	}
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

	$default = function_exists( 'pll_default_language' ) ? pll_default_language() : '';
	if ( ! $default || $default === $lang || ! function_exists( 'pll_get_post' ) ) {
		return get_posts( array_merge( $args, array( 'lang' => $lang ) ) );
	}

	// Élément par élément : sa traduction si elle existe, sinon l'original. Traduire une seule
	// bannière ne doit pas faire disparaître les autres de la version malagasy.
	$items = array();
	$used  = array();
	foreach ( get_posts( array_merge( $args, array( 'lang' => $default ) ) ) as $item ) {
		$id = pll_get_post( $item->ID, $lang );
		if ( $id && 'publish' === get_post_status( $id ) ) {
			$items[]     = get_post( $id );
			$used[ $id ] = true;
		} else {
			// Pas de traduction, ou traduction en brouillon : l'original reste affiché.
			$items[] = $item;
		}
	}
	// Éléments créés directement en malagasy (sans original français).
	foreach ( get_posts( array_merge( $args, array( 'lang' => $lang ) ) ) as $item ) {
		if ( ! isset( $used[ $item->ID ] ) ) {
			$items[] = $item;
		}
	}
	// Tri par « Ordre » ; à ordre égal, l'ordre d'origine est conservé (usort n'est pas stable en PHP 7).
	$rank = array_flip( array_map( 'spl_object_hash', $items ) );
	usort(
		$items,
		function ( $a, $b ) use ( $rank ) {
			return ( $a->menu_order - $b->menu_order ) ?: ( $rank[ spl_object_hash( $a ) ] - $rank[ spl_object_hash( $b ) ] );
		}
	);
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
		$file = vao_mg_theme_file( get_post_meta( $post->ID, '_vao_theme_img', true ) );
		$url  = $file ? vao_img( $file ) : '';
	}
	return $url;
}

/**
 * Image livrée avec le thème dans la langue affichée : en malagasy, la version « nom-mg »
 * (texte incrusté traduit : bannières, cartes témoignages) si elle existe, sinon l'image française.
 */
function vao_mg_theme_file( $file ) {
	if ( ! $file || ! vao_is_mg() ) {
		return $file;
	}
	$stem = preg_replace( '/\.(png|jpe?g)$/i', '', $file ) . '-mg';
	foreach ( array( '.webp', '.png', '.jpg' ) as $ext ) {
		if ( file_exists( get_template_directory() . '/assets/img/' . $stem . $ext ) ) {
			return $stem . '.png';
		}
	}
	return $file;
}

/**
 * Texte alternatif de l'image d'un élément.
 */
function vao_post_alt( $post ) {
	$post = get_post( $post );
	$alt  = get_post_meta( $post->ID, '_vao_alt', true );
	return $alt ? vao_t( $alt ) : vao_post_title( $post );
}

/**
 * Titre d'un élément traduit par le dictionnaire du thème (inc/strings/contenus.php).
 * La clé est le titre brut : get_the_title() remplace les apostrophes et ne correspondrait plus.
 */
function vao_post_title( $post ) {
	$post       = get_post( $post );
	$translated = vao_t( $post->post_title );
	return $translated === $post->post_title ? get_the_title( $post ) : $translated;
}

/**
 * srcset d'une image de bannière, pour qu'un mobile charge la version 960 px au lieu de 1920 px.
 * Image de la médiathèque : tailles générées par WordPress ; image du thème : variante -960.webp.
 */
function vao_hero_srcset( $slide, $file = '' ) {
	$thumb = get_post_thumbnail_id( $slide );
	if ( $thumb ) {
		return (string) wp_get_attachment_image_srcset( $thumb, 'full' );
	}
	$file  = $file ? $file : get_post_meta( $slide->ID, '_vao_theme_img', true );
	$small = $file ? vao_img_variant( $file, 960 ) : '';
	return $small ? $small . ' 960w, ' . vao_img( $file ) . ' 1920w' : '';
}

/**
 * Image du thème d'une bannière dans la langue affichée (voir vao_mg_theme_file()).
 */
function vao_hero_file( $slide ) {
	return vao_mg_theme_file( get_post_meta( $slide->ID, '_vao_theme_img', true ) );
}

/**
 * Images de la bannière (carrousel) : array( array( 'src' => ..., 'srcset' => ..., 'alt' => ... ), ... ).
 */
function vao_hero_slides() {
	// Appelée par header.php et par wp_localize_script : une seule requête par page.
	static $slides = null;
	if ( null !== $slides ) {
		return $slides;
	}
	$slides = array_map(
		function ( $slide ) {
			// Image choisie dans la médiathèque : prioritaire (traduite via Polylang).
			$file = get_post_thumbnail_id( $slide ) ? '' : vao_hero_file( $slide );
			return array(
				'src'    => $file ? vao_img( $file ) : vao_post_img( $slide ),
				'srcset' => vao_hero_srcset( $slide, $file ),
				'alt'    => vao_post_title( $slide ),
			);
		},
		vao_items( 'vao_banniere' )
	);
	// Bannière sans image (image retirée dans l'admin) : ignorée plutôt qu'une diapositive vide.
	$slides = array_values(
		array_filter(
			$slides,
			function ( $slide ) {
				return '' !== (string) $slide['src'];
			}
		)
	);
	return $slides;
}

/**
 * Recherche dans le catalogue (gammes et produits), en français et en malagasy, sans tenir compte
 * des accents : les produits ne sont pas des pages publiques, la recherche WordPress ne les voit pas.
 *
 * @return array array( 'gammes' => array( array( titre, url ) ), 'produits' => array( array( titre, url ) ) )
 */
function vao_search_catalogue( $query ) {
	$norm  = function ( $text ) {
		return mb_strtolower( remove_accents( wp_strip_all_tags( (string) $text ) ) );
	};
	$query = trim( $norm( $query ) );
	$found = array(
		'gammes'   => array(),
		'produits' => array(),
	);
	if ( mb_strlen( $query ) < 2 ) {
		return $found;
	}
	$match = function ( $texts ) use ( $norm, $query ) {
		foreach ( $texts as $text ) {
			if ( '' !== (string) $text && false !== mb_strpos( $norm( $text ), $query ) ) {
				return true;
			}
		}
		return false;
	};
	foreach ( vao_gammes() as $term ) {
		if ( $match( array( $term->name, vao_t( $term->name ) ) ) ) {
			$found['gammes'][] = array( vao_t( $term->name ), vao_url( 'gammes', $term->slug ) );
		}
	}
	foreach ( vao_items( 'vao_produit' ) as $product ) {
		$terms = get_the_terms( $product, 'vao_gamme' );
		$term  = $terms && ! is_wp_error( $terms ) ? $terms[0] : null;
		$alt   = (string) get_post_meta( $product->ID, '_vao_alt', true );
		// Nom du produit seulement : une gamme trouvée est déjà listée au-dessus.
		if ( $match( array( $product->post_title, $alt ) ) ) {
			$label               = trim( get_the_title( $product ) . ( $term ? ' – ' . vao_t( $term->name ) : '' ) );
			$found['produits'][] = array( $label, vao_url( 'gammes', $term ? $term->slug : '' ) );
		}
	}
	return $found;
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
