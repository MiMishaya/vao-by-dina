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
	if ( ! get_option( 'vao_content_installed' ) ) {
		vao_install_content();
		update_option( 'vao_content_installed', VAO_VERSION );
	}
	vao_upgrade();
}
// Uniquement à l'activation réelle : l'aperçu en direct (Personnaliser) ne doit rien modifier sur le site.
add_action( 'after_switch_theme', 'vao_install' );

/**
 * Mise à jour du contenu pour un site déjà installé (thème remplacé sans réactivation).
 */
function vao_maybe_upgrade() {
	if ( get_option( 'vao_content_installed' ) && ! get_option( 'vao_content_v2' ) && current_user_can( 'manage_options' ) ) {
		vao_create_pages();
		vao_upgrade();
	}
}
add_action( 'admin_init', 'vao_maybe_upgrade' );

/**
 * Contenu v2 (refonte octobre 2026) : 5 gammes, avis, collaborateurs, actions RSE, offres d'emploi, distributeurs.
 * Une seule fois : ensuite, tout se gère dans l'admin.
 */
function vao_upgrade() {
	if ( get_option( 'vao_content_v2' ) ) {
		return;
	}
	vao_install_gammes_v2();
	vao_install_content_v2();
	update_option( 'vao_content_v2', VAO_VERSION );
}

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
 * 5 gammes de l'organigramme : le détergent en barre (VAO Magic) devient une gamme à part.
 * Le texte de présentation de chaque gamme est sa « Description » (Produits > Gammes).
 */
function vao_install_gammes_v2() {
	$gammes = array(
		'menage'          => array( 'Savon de ménage', '<p><strong>Le bon vieux réflexe de nos mamans, l’efficacité en plus !</strong></p><p>Que ce soit pour la grande lessive du week-end ou le petit coup de propre du soir, nos savons Blanc, Mavo, Marron et Or sont là pour vous simplifier la vie. Ils moussent bien, attrapent la saleté sans effort et s’adaptent à toutes les tâches du foyer.</p>' ),
		'savonbar'        => array( 'Savon barre', '<p><strong>Trois références, à chacun son usage.</strong></p><p>Parce que chaque famille a ses habitudes, nous avons imaginé trois alliés pour votre quotidien :</p><ul><li>Vao Citron : grand format, parfum citron intense.</li><li>Vao Lemon : l’essentiel du quotidien, efficace et frais, au parfum citronné.</li><li>Vao Line Barre : format rectangulaire pratique, senteur douce pour toute la famille.</li></ul>' ),
		'toilette'        => array( 'Savon de toilette', '<p><strong>Un doux moment de fraîcheur pour toute la famille.</strong></p><p>Parce que la peau de toute la famille mérite ce qu’il y a de plus doux, nos savons accompagnent vos petits et grands gestes du quotidien. De la toilette toute délicate de bébé au débarbouillage express des grands après une longue journée, profitez d’une mousse onctueuse et d’un parfum frais qui fait du bien.</p>' ),
		'detergent'       => array( 'Détergent en poudre', '<p><strong>Faites briller vos vêtements et sentez la différence.</strong></p><p>Avec notre sélection de poudres lavantes, vous redonnez un coup d’éclat à vos habits préférés dès le premier lavage :</p><ul><li>Vao Line et ses 4 parfums gourmands</li><li>Vao Lemon (30g) pour une fraîcheur citronnée express</li><li>MAX (25g) et ses 5 senteurs pour varier les plaisirs à mini prix</li></ul>' ),
		'detergent-barre' => array( 'Détergent en barre', '<p><strong>Le coup de pouce magique pour vos lessives à la main.</strong></p><p>Vous avez une tache tenace qui refuse de partir ? Ne cherchez plus ! En format 140g ou 220g, le Vao Magic frotte fort sans abîmer vos tissus ni assécher vos mains. Il dure longtemps au lavoir et vous fait gagner un temps précieux.</p>' ),
	);

	$order = 0;
	foreach ( $gammes as $slug => $gamme ) {
		$term = term_exists( $slug, 'vao_gamme' );
		if ( $term ) {
			wp_update_term( (int) $term['term_id'], 'vao_gamme', array( 'name' => $gamme[0], 'description' => $gamme[1] ) );
		} else {
			$term = wp_insert_term( $gamme[0], 'vao_gamme', array( 'slug' => $slug, 'description' => $gamme[1] ) );
		}
		if ( ! is_wp_error( $term ) ) {
			update_term_meta( (int) $term['term_id'], 'vao_ordre', ++$order );
		}
	}

	$barre = get_term_by( 'slug', 'detergent-barre', 'vao_gamme' );
	$magic = get_posts(
		array(
			'post_type'   => 'vao_produit',
			'title'       => 'VAO Magic',
			'numberposts' => 1,
			'tax_query'   => array( array( 'taxonomy' => 'vao_gamme', 'field' => 'slug', 'terms' => 'detergent' ) ),
		)
	);
	if ( $barre && $magic ) {
		wp_set_object_terms( $magic[0]->ID, (int) $barre->term_id, 'vao_gamme' );
	}
}

/**
 * Crée un élément de contenu (sans image d'origine) avec ses champs.
 */
function vao_insert_post( $post_type, $title, $order, $content = '', $meta = array() ) {
	return wp_insert_post(
		array(
			'post_type'    => $post_type,
			'post_title'   => $title,
			'post_content' => $content,
			'post_status'  => 'publish',
			'menu_order'   => $order,
			'meta_input'   => $meta,
		)
	);
}

/**
 * Liste HTML d'une offre d'emploi : titre de rubrique + puces.
 */
function vao_offre_bloc( $titre, $items, $intro = '' ) {
	$html = '<h3>' . $titre . '</h3>';
	if ( $intro ) {
		$html .= '<p>' . $intro . '</p>';
	}
	return $html . '<ul><li>' . implode( '</li><li>', $items ) . '</li></ul>';
}

function vao_install_content_v2() {
	// Avis consommateurs : captures des commentaires Facebook.
	for ( $i = 1; $i <= 20; $i++ ) {
		vao_insert_item( 'vao_avis', sprintf( 'Avis consommateur %02d', $i ), $i, sprintf( 'avis/avis-%02d.jpg', $i ) );
	}

	// Témoignages des collaborateurs. Photos provisoires (extraites de la maquette) : à remplacer par les originaux.
	$collaborateurs = array(
		array( 'Sariaka RAKOTOMANANTSOA', 'Responsable Marketing', '3 ans chez SDOI', 'maquette/collab-1.jpg', "Ce qui me plaît dans mon métier, c'est de faire évoluer une marque malgache en accord avec les attentes des consommateurs. J'aime concevoir des stratégies concrètes qui valorisent nos produits locaux et contribuent au développement de la marque. Voir une idée prendre vie sur le terrain et rencontrer son public, c'est ce qui me motive chaque jour." ),
		array( 'Rija RAJAONERA', 'Commercial Grand compte', '1 an chez SDOI', 'maquette/collab-2.jpg', "Ce que j'aime chez SDOI, c'est la proximité avec le terrain. Nous sommes en contact direct avec les distributeurs, ce qui nous permet de mieux comprendre le marché et d'améliorer constamment notre approche. Chaque relation construite avec un grand compte est un pas de plus pour rapprocher nos marques des consommateurs. On sent vraiment qu'on participe à quelque chose de concret." ),
		array( 'Fabrice RAMAROKOTO', 'Chargé Trade Marketing', '2 ans chez SDOI', 'maquette/collab-3.jpg', "Le Trade, c'est le terrain qui donne vie à la stratégie. Derrière chaque action, il y a une équipe, de l'engagement et une même ambition : rapprocher nos marques des consommateurs. Aller à la rencontre des points de vente, observer, ajuster, puis voir les résultats arriver, voilà ce qui me motive. Parce qu'une marque ne se construit pas seulement en bureau, mais surtout sur le terrain, ensemble." ),
		array( 'Nekena', 'Infographiste', '2 ans chez SDOI', 'maquette/collab-4.jpg', "En tant qu'Infographiste, mon quotidien est une véritable immersion au cœur de la création visuelle. Ce que j'aime le plus, c'est traduire des idées et des concepts en univers graphiques percutants : identités visuelles, packagings inspirants, supports de communication engageants. Travailler dans cet environnement dynamique me permet de donner vie à mon imagination et de marquer les esprits." ),
		array( 'Melissa ROVANIAINA', 'Chargée Qualité', '1 an chez SDOI', '', "En tant que Chargée Qualité & Chimiste, ma mission est d'assurer l'excellence et la conformité de nos savons et produits détergents. Ce qui me passionne : garantir à nos consommateurs des formules efficaces, sûres et agréables, grâce à des analyses rigoureuses du laboratoire jusqu'à la production. C'est une vraie fierté de veiller chaque jour sur la qualité de nos marques." ),
		array( 'Vony RASAMOELY', 'Responsable Ressources Humaines', '2 ans chez SDOI', '', "Exercer mon métier de Responsable des Ressources Humaines est une source de passion quotidienne. J'aime voir mes collaborateurs s'épanouir et grandir au sein d'une entreprise où l'humain est réellement au cœur de chaque décision. Travailler dans un cadre stimulant et bienveillant, avec des équipes dynamiques, donne un vrai sens à mon engagement." ),
		array( 'Joel RATOAVINA', 'Préparateur de commande', '', '', "En tant que Magasinier, ma mission est de veiller à une gestion rigoureuse de nos stocks, notamment en assurant que toute la gamme de détergents VAO Line soit parfaitement rangée et classée. Ce qui me passionne, c'est mon rôle clé au cœur de la chaîne logistique : garantir la traçabilité et préparer les commandes avec précision, pour que nos produits arrivent toujours à temps et dans de bonnes conditions." ),
		array( 'Lalaina RAKOTO', 'Opératrice ligne de production', '', '', "En tant qu'Opératrice de ligne, le cœur de mon métier est d'assurer la fabrication de nos savons sur la chaîne de production. Ce qui me passionne, c'est ce savoir-faire technique et la rigueur nécessaire pour garantir que chaque savon respecte nos standards d'excellence. Travailler au sein d'une équipe solidaire et dynamique, c'est une vraie fierté : notre travail contribue à l'hygiène et au bien-être de milliers de familles." ),
	);
	foreach ( $collaborateurs as $i => $c ) {
		$meta = array(
			'_vao_poste'      => $c[1],
			'_vao_anciennete' => $c[2],
			'_vao_texte'      => $c[4],
		);
		if ( $c[3] ) {
			vao_insert_item( 'vao_collaborateur', $c[0], $i + 1, $c[3], $meta );
		} else {
			vao_insert_post( 'vao_collaborateur', $c[0], $i + 1, '', $meta );
		}
	}

	// Actions RSE.
	$actions = array(
		array( "SDOI s'engage pour la solidarité et le bien-être des enfants.", 'enfants', 16, "À travers sa marque VAO, SDOI, membre du SIM, a soutenu l'initiative « Mbola Mijoro ho an'ny Zaza Malagasy », portée par C'est du Joely, au bénéfice de MealEspoirs et de l'École associative L'Île aux Enfants à Anosibe. Ce soutien s'est concrétisé par un don de produits d'hygiène et d'entretien VAO, contribuant à garantir un environnement propre et sain aux enfants." ),
		array( 'SDOI engagée pour le bien-être des communautés locales', 'communautes', 14, "Dans le cadre de sa démarche de responsabilité sociétale, SDOI a apporté son soutien aux personnes âgées et aux enfants du fokontany d'Ambanitsena, où est implantée l'usine Savony VAO. Cette initiative s'est concrétisée par un don de savons et de détergents, témoignant de l'engagement de l'entreprise en faveur de la solidarité, de l'hygiène et du bien-être des communautés locales." ),
		array( 'VAO, au cœur de la solidarité à Noël', 'noel', 20, "À l'occasion des fêtes de Noël, SDOI a apporté son soutien aux personnes les plus démunies du fokontany d'Ankorondrano à travers une distribution de calendriers et de produits d'hygiène et d'entretien VAO. Une initiative solidaire qui témoigne de l'engagement de l'entreprise en faveur du bien-être des communautés locales." ),
	);
	foreach ( $actions as $i => $a ) {
		$photos = array();
		for ( $n = 1; $n <= $a[2]; $n++ ) {
			$photos[] = sprintf( 'rse/%s-%02d.jpg', $a[1], $n );
		}
		vao_insert_post( 'vao_rse', $a[0], $i + 1, $a[3], array( '_vao_galerie' => $photos ) );
	}

	// Offres d'emploi.
	$offres = array(
		array(
			'Chargé(e) communication',
			'orange',
			'maquette/offre-communication.jpg',
			vao_offre_bloc(
				'Missions',
				array(
					"Développer la notoriété et affirmer l'image de marque de l'entreprise et de ses produits ;",
					"Coordonner les actions de communication interne et externe de l'entreprise ;",
					'Définir et exécuter un plan média (calendrier éditorial, campagnes, publications sponsorisées) sur les différents canaux de communication ;',
					'Veiller à la cohérence et à la qualité visuelle des contenus (correction des créations des infographistes avant publication) ;',
					"Développer l'image de l'entreprise à travers une stratégie digitale globale : mise à jour du site web, animation du compte LinkedIn corporate, amélioration de la présence Facebook et autres supports.",
				),
				'Sous la responsabilité du Responsable Marketing et Communication, le/la Chargé(e) communication aura pour missions principales de :'
			) . vao_offre_bloc(
				'Responsabilités',
				array(
					'Mettre en œuvre le plan de communication suivant la stratégie définie ;',
					"Gérer les actions médias et relations publiques de l'entreprise ;",
					'Collaborer avec les équipes internes (Chargé trade marketing, graphiste, RH…) et les prestataires externes (agences de communication et médias) pour toute action relative à la conception de supports ;',
					'Élaborer un calendrier éditorial mensuel avec le responsable marketing (planning des posts, temps forts, campagnes produit) ;',
					'Relire, corriger et valider les contenus visuels créés par les infographistes avant diffusion ;',
					'Animer, développer et superviser la mise à jour des supports digitaux (site web, LinkedIn, Facebook, etc.) pour garantir une image professionnelle et cohérente ;',
					"Être force de proposition pour renforcer l'identité de la marque et de l'entreprise auprès du public et des partenaires.",
				)
			) . vao_offre_bloc(
				'Profil recherché',
				array(
					'Formation Bac +2/3 en Commerce, Communication ou domaine équivalent ;',
					"Expérience dans la gestion de l'image de marque et dans la stratégie éditoriale en ligne ;",
					'Expérience probante dans un poste similaire, idéalement dans le secteur des produits de grande consommation ;',
					'Aisance sur les plateformes : LinkedIn entreprise, Odoo, Facebook Business Manager ;',
					'Maîtrise des médias, canaux de communication et outils digitaux ;',
					'Excellentes compétences rédactionnelles, relationnelles et sens de la communication ;',
					"Créativité, rigueur, sens de l'organisation et esprit d'analyse ;",
					'Capacité à gérer plusieurs projets simultanément et à travailler en transversal avec différents services ;',
					'Autonomie, dynamisme, proactivité et capacité à travailler en équipe.',
				)
			),
		),
		array(
			'Mécanicien industriel',
			'bleu',
			'maquette/offre-mecanicien.jpg',
			vao_offre_bloc(
				'Missions',
				array(
					'Réaliser les opérations de maintenance curative et préventive des machines industrielles ;',
					"Veiller à la mise en conformité, à la manipulation et à l'exploitation des machines spécifiques ;",
					"Suivre les programmes de maintenance sur l'ensemble des équipements et apporter les mesures correctives idoines.",
				)
			) . vao_offre_bloc(
				'Profil recherché',
				array(
					'Diplôme en génie mécanique industriel ;',
					"3 années d'expérience pratique et probante dans la mécanique industrielle ;",
					'Rigoureux, dynamique, motivé et méticuleux ;',
					'Bonne condition physique ;',
					'Maîtrise de la langue française.',
				)
			) . '<h2>Électricien industriel</h2>' . vao_offre_bloc(
				'Missions',
				array(
					"Assurer l'entretien, la sécurité et la maintenance des installations industrielles existantes ;",
					'Mettre en service les nouveaux équipements électriques ;',
					'Intervenir en maintenance curative et préventive ;',
					'Déterminer des mesures correctives.',
				)
			) . vao_offre_bloc(
				'Profil recherché',
				array(
					"Titulaire d'un diplôme en génie électrique ou équivalent ;",
					'Âgé de plus de 27 ans ;',
					"Minimum 3 ans d'expérience probante dans un poste similaire ;",
					'Maîtrise de la langue française ;',
					'Dynamique.',
				)
			),
		),
		array(
			'Tourneur et/ou fraiseur',
			'orange',
			'maquette/offre-tourneur.jpg',
			vao_offre_bloc(
				'Missions',
				array(
					"Réaliser les opérations d'usinage par tournage et fraisage sur machine ;",
					'Lire et interpréter les plans techniques pour assurer la conformité des pièces ;',
					"Régler les paramètres d'usinage et assurer la mise en route des machines ;",
					'Contrôler la conformité des pièces produites (dimensions, aspect…) ;',
					"Assurer l'entretien des machines et maintenir son poste de travail propre et organisé.",
				)
			) . vao_offre_bloc(
				'Profil recherché',
				array(
					"Bac +2 en mécanique avec 2 années d'expérience minimum ;",
					'Connaissance en commande numérique ;',
					'Rigoureux, précis et minutieux ;',
					'Connaissance des matériaux (acier, inox, alu…).',
				)
			),
		),
	);
	foreach ( $offres as $i => $o ) {
		$id = vao_insert_item( 'vao_offre', $o[0], $i + 1, $o[2], array( '_vao_couleur' => $o[1] ) );
		if ( $id && ! is_wp_error( $id ) ) {
			wp_update_post( array( 'ID' => $id, 'post_content' => $o[3] ) );
		}
	}

	// Distributeurs : uniquement les villes lisibles en entier sur la maquette (liste complète à fournir par le client).
	$distributeurs = array(
		'ANTANANARIVO ET ENVIRONS' => array( 'MITSINJO : Ampasampito', 'VALIHAJA : Itaosy Ambinisoa', 'AINA : Andoharanofotsy', 'HANTASOA : Andohatapenaka', 'TT HAJATIANA : Sabotsy Namehana', 'VONY : Talatamaty', 'MIHAJA : 67 Ha', 'TRANOMBAROTRA NIRINA : Anosibe', 'LISA DISTRIBUTION : Anosizato', 'ZOLY : 67 Ha', 'TRANOMBAROTRA HN : Andavamamba', 'TRANOMBAROTRA VOLA : Andohatapenaka', 'TRANOMBAROTRA AVOTRA : Ambodivona', 'TRANOMBAROTRA NGAMA : Andravoahangy' ),
		'ANTSIRABE'                => array( 'Centre Ankaratra', 'TSODRANO', 'DALLY' ),
		'AMBATOLAMPY'              => array( 'TB VELO : Tsena Ambatolampy' ),
		'AMBATONDRAZAKA'           => array( 'MAGASIN ANNA' ),
		'AMBILOBE'                 => array( 'DOKANY IMPORTÉ' ),
	);
	$order = 0;
	foreach ( $distributeurs as $ville => $points ) {
		vao_insert_post( 'vao_distributeur', $ville, ++$order, '', array( '_vao_points' => implode( "\n", $points ) ) );
	}
}
