<?php
/**
 * Installation : crée les pages, importe le contenu d'origine (produits, témoignages,
 * bannière, logos) et les menus. Le contenu n'est importé qu'une seule fois : ensuite,
 * tout se gère dans l'admin. Pour relancer l'import, supprimer l'option
 * « vao_content_installed » puis réactiver le thème.
 *
 * Les images d'origine restent dans le thème (champ _vao_theme_img) : elles s'affichent
 * tant qu'aucune image n'a été choisie dans la médiathèque.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function vao_install() {
	vao_create_pages();
	if ( get_option( 'vao_content_installed' ) ) {
		return;
	}
	vao_install_content();
	vao_install_menus();
	update_option( 'vao_content_installed', VAO_VERSION );
}
// Uniquement à l'activation réelle : l'aperçu en direct (Personnaliser) ne doit rien modifier sur le site.
add_action( 'after_switch_theme', 'vao_install' );

function vao_create_pages() {
	foreach ( vao_pages() as $slug => $page ) {
		list( $title, $template ) = $page;

		$existing = get_page_by_path( $slug );
		if ( $existing ) {
			$id = $existing->ID;
		} else {
			$id = wp_insert_post(
				array(
					'post_title'  => $title,
					'post_name'   => $slug,
					'post_status' => 'publish',
					'post_type'   => 'page',
				)
			);
		}
		if ( ! $id || is_wp_error( $id ) ) {
			continue;
		}

		if ( $template ) {
			update_post_meta( $id, '_wp_page_template', $template );
		}
		if ( 'accueil' === $slug ) {
			update_option( 'show_on_front', 'page' );
			update_option( 'page_on_front', $id );
		}
	}
}

/**
 * Crée un élément avec son image d'origine.
 */
function vao_insert_item( $post_type, $title, $order, $theme_img, $meta = array() ) {
	$meta['_vao_theme_img'] = $theme_img;
	return wp_insert_post(
		array(
			'post_type'   => $post_type,
			'post_title'  => $title,
			'post_status' => 'publish',
			'menu_order'  => $order,
			'meta_input'  => $meta,
		)
	);
}

function vao_install_content() {
	// Bannière.
	$slides = array(
		array( 'Web project 02 Header only_Header 01.png', "Propre à l'œil, mais encore plus frais au nez" ),
		array( 'Web project 02 Header only-04.png', 'Mousse généreuse pour une propreté éclatante' ),
		array( 'Web project 02 Header only-05.png', 'Votre linge, propre comme au premier jour' ),
		array( 'Web project 02 Header only-06.png', 'La propreté qui se voit, la fraîcheur qui se ressent' ),
		array( 'Web project 02 Header only-07.png', 'Puissance contre les taches, fraîcheur sur votre linge' ),
		array( 'Web project 02 Header only_Header 02.png', 'La fraîcheur qui voyage avec vous' ),
		array( 'Web project 02 Header only-03.png', 'Douceur au toucher, fraîcheur au quotidien' ),
	);
	foreach ( $slides as $i => $slide ) {
		vao_insert_item( 'vao_banniere', $slide[1], $i + 1, 'header/' . $slide[0] );
	}

	// Témoignages.
	$testimonials = array(
		array( 'élement-08.png', 'Distributeur - Haja, Caissier' ),
		array( 'élement-02.png', 'Grossiste - Razery, Vendeuse' ),
		array( 'élement-03.png', 'Client - Robert, père de famille' ),
		array( 'élement-04.png', 'Épicerie - Mme Harisoa, Vendeuse' ),
		array( 'élement_Témoignage.png', 'Client - Fara, Mpanasa lamba' ),
		array( 'élement-05.png', 'Client - Noro, Renim-pianakaviana' ),
		array( 'élement-06.png', 'Client - Fara, Mécanicien' ),
		array( 'élement-07.png', 'Client - Fara, Entrepreneuse' ),
	);
	foreach ( $testimonials as $i => $testimonial ) {
		vao_insert_item( 'vao_temoignage', $testimonial[1], $i + 1, 'temoignage/Web project 02 ' . $testimonial[0] );
	}

	// Logos de marques.
	$brands = array(
		array( '004 VLN', 'VAO Line' ),
		array( '005 VBB', 'VAO Bébé' ),
		array( '006 VLX', 'VAO Lux' ),
		array( '007 VCTR', 'VAO Citron' ),
		array( '008 VLM', 'VAO Lemon' ),
		array( '009 VMG', 'VAO Magic' ),
		array( '010 VHT', 'VAO Hôtel' ),
		array( '011 VATQ', 'VAO Antiseptique' ),
		array( '012 Sblanc', 'Savon de ménage Blanc' ),
		array( '013 SJaune', 'Savon de ménage Jaune' ),
		array( '014 SOR', 'Savon de ménage Or' ),
		array( '015 SMarron', 'Savon de ménage Marron' ),
	);
	foreach ( $brands as $i => $brand ) {
		vao_insert_item( 'vao_marque', $brand[1], $i + 1, 'Render element 02/Web project Element 03_' . $brand[0] . '.png' );
	}

	// Gammes et produits : array( n° image, nom, poids, conditionnement, description image, recommandé ).
	$gammes = array(
		'detergent' => array(
			'name'     => 'Détergent',
			'titre'    => 'blanc',
			'bouton'   => 'orange',
			'products' => array(
				array( '001', 'VAO Line', '30g / unité', '48 sachets / 150 sachets', '', 'blue' ),
				array( '002', 'VAO Magic', '140g / unité', '24 morceaux' ),
				array( '003', 'VAO Lemon', '30g / unité', '48 sachets / 150 sachets' ),
			),
		),
		'savonbar'  => array(
			'name'     => 'Savon Bar',
			'titre'    => 'orange',
			'bouton'   => 'orange',
			'products' => array(
				array( '004', 'VAO Citron', '1kg / unité', '6 morceaux', '', 'yellow' ),
				array( '005', 'VAO Lemon', '1kg / unité', '6 morceaux' ),
				array( '006', 'VAO Line', '800g / unité', '6 morceaux' ),
			),
		),
		'toilette'  => array(
			'name'     => 'Savon de toilette',
			'titre'    => 'orange',
			'bouton'   => 'blue',
			'products' => array(
				array( '007', 'VAO Bébé', '70g / unité', '24 morceaux' ),
				array( '008', 'U1', '80g / unité', '36 morceaux' ),
				array( '009', 'SM', '180g / unité', '12 morceaux' ),
				array( '010', 'VAO', '70g / unité', '24 morceaux', 'VAO Antiseptique' ),
				array( '011', 'VAO', '70g / unité', '24 morceaux', 'VAO Germicide' ),
				array( '012', 'VAO Hôtel', '20g / unité', '24 morceaux' ),
				array( '013', 'VAO Lux', '70g / unité', '24 morceaux' ),
				array( '014', 'VAO Lux', '70g / unité', '24 morceaux' ),
				array( '015', 'VAO Lux', '70g / unité', '24 morceaux', '', 'pink' ),
			),
		),
		'menage'    => array(
			'name'     => 'Savon de ménage',
			'titre'    => 'blanc',
			'bouton'   => 'blue',
			'products' => array(
				array( '016', 'U30+', '180g / unité', '36 morceaux' ),
				array( '017', 'V3+', '110g / unité', '30 morceaux' ),
				array( '018', 'O20+', '110g / unité', '24 morceaux' ),
				array( '019', 'Z27', '125g / unité', '36 morceaux' ),
				array( '020', 'U30', '180g / unité', '36 morceaux' ),
				array( '021', 'UP3', '60g / unité', '30 morceaux' ),
				array( '022', 'O27', '110g / unité', '36 morceaux' ),
				array( '023', 'O30', '120g / unité', '24 morceaux' ),
				array( '024', 'Z27', '110g / unité', '24 morceaux' ),
				array( '025', 'UR20', '80g / unité', '36 morceaux' ),
				array( '026', 'V3', '110g / unité', '30 morceaux' ),
				array( '027', 'U27', '150g / unité', '36 morceaux' ),
			),
		),
	);

	$order = 0;
	foreach ( $gammes as $slug => $gamme ) {
		$term = term_exists( $slug, 'vao_gamme' );
		if ( ! $term ) {
			$term = wp_insert_term( $gamme['name'], 'vao_gamme', array( 'slug' => $slug ) );
		}
		if ( is_wp_error( $term ) ) {
			continue;
		}
		$term_id = (int) $term['term_id'];
		update_term_meta( $term_id, 'vao_ordre', ++$order );
		update_term_meta( $term_id, 'vao_titre_couleur', $gamme['titre'] );
		update_term_meta( $term_id, 'vao_bouton_couleur', $gamme['bouton'] );

		foreach ( $gamme['products'] as $i => $p ) {
			$recommended = isset( $p[5] ) ? $p[5] : '';
			$id          = vao_insert_item(
				'vao_produit',
				$p[1],
				$i + 1,
				'savon/Web project Savon élement_' . $p[0] . '.png',
				array(
					'_vao_poids'           => $p[2],
					'_vao_conditionnement' => $p[3],
					'_vao_alt'             => isset( $p[4] ) ? $p[4] : '',
					'_vao_recommande'      => $recommended ? '1' : '',
					'_vao_couleur'         => $recommended ? $recommended : 'blue',
				)
			);
			if ( $id && ! is_wp_error( $id ) ) {
				wp_set_object_terms( $id, $term_id, 'vao_gamme' );
			}
		}
	}
}

/**
 * Crée les menus « Notre gamme » et « Formulaires » (modifiables dans Apparence > Menus).
 */
function vao_install_menus() {
	$locations = get_theme_mod( 'nav_menu_locations', array() );

	if ( empty( $locations['gamme'] ) ) {
		$menu_id = wp_create_nav_menu( 'Notre gamme' );
		if ( ! is_wp_error( $menu_id ) ) {
			$page = get_page_by_path( 'gammes' );
			if ( $page ) {
				wp_update_nav_menu_item(
					$menu_id,
					0,
					array(
						'menu-item-title'     => 'Tous les produits',
						'menu-item-object'    => 'page',
						'menu-item-object-id' => $page->ID,
						'menu-item-type'      => 'post_type',
						'menu-item-status'    => 'publish',
					)
				);
			}
			$items = array(
				array( 'Détergent en poudre', 'detergent' ),
				array( 'Détergent en barre', 'detergent' ),
				array( 'Savon de toilette', 'toilette' ),
				array( 'Savon en barre', 'savonbar' ),
				array( 'Savon translucide', 'savonbar' ),
				array( 'Savon de ménage', 'menage' ),
			);
			foreach ( $items as $item ) {
				$term = get_term_by( 'slug', $item[1], 'vao_gamme' );
				if ( $term ) {
					wp_update_nav_menu_item(
						$menu_id,
						0,
						array(
							'menu-item-title'     => $item[0],
							'menu-item-object'    => 'vao_gamme',
							'menu-item-object-id' => $term->term_id,
							'menu-item-type'      => 'taxonomy',
							'menu-item-status'    => 'publish',
						)
					);
				}
			}
			$locations['gamme'] = $menu_id;
		}
	}

	if ( empty( $locations['formulaires'] ) ) {
		$menu_id = wp_create_nav_menu( 'Formulaires' );
		if ( ! is_wp_error( $menu_id ) ) {
			$page = get_page_by_path( 'devenir-revendeur' );
			if ( $page ) {
				wp_update_nav_menu_item(
					$menu_id,
					0,
					array(
						'menu-item-title'     => 'Devenir revendeur',
						'menu-item-object'    => 'page',
						'menu-item-object-id' => $page->ID,
						'menu-item-type'      => 'post_type',
						'menu-item-status'    => 'publish',
					)
				);
			}
			$items = array(
				array( "Demande d'échantillon", 'echantillon' ),
				array( 'Candidature spontanée', 'candidature' ),
			);
			foreach ( $items as $item ) {
				wp_update_nav_menu_item(
					$menu_id,
					0,
					array(
						'menu-item-title'  => $item[0],
						'menu-item-url'    => vao_url( 'formulaires', $item[1] ),
						'menu-item-type'   => 'custom',
						'menu-item-status' => 'publish',
					)
				);
			}
			$locations['formulaires'] = $menu_id;
		}
	}

	set_theme_mod( 'nav_menu_locations', $locations );
}
