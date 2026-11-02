<?php
/**
 * Site bilingue français / malagasy avec l'extension gratuite Polylang.
 *
 * Sans Polylang, le site fonctionne normalement en français.
 * Avec Polylang et la langue malagasy ajoutée (Langues > Langues) :
 * - les pages du thème reçoivent automatiquement leur version malagasy ;
 * - tous les textes apparaissent dans Langues > Traductions des chaînes, pré-remplis en malagasy
 *   (traduction à faire relire par une personne parlant malagasy) ;
 * - la bannière et les témoignages se traduisent élément par élément (images avec du texte) ;
 * - les produits et logos sont communs aux deux langues (poids et conditionnement traduits).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* -------------------------------------------------------------------------
 * Helpers
 * ---------------------------------------------------------------------- */

/**
 * Traduit un texte du thème dans la langue courante.
 */
function vao_t( $text ) {
	if ( ! function_exists( 'pll__' ) || '' === (string) $text ) {
		return $text;
	}
	$translated = pll__( $text );
	// Chaîne pas encore traduite dans Langues > Traductions des chaînes : malagasy proposé par le thème.
	if ( $translated === $text && vao_is_mg() ) {
		$dictionary = vao_i18n_flat();
		if ( isset( $dictionary[ $text ] ) && '' !== $dictionary[ $text ] ) {
			return $dictionary[ $text ];
		}
	}
	return $translated;
}

/**
 * Toutes les traductions proposées par le thème : array( français => malagasy ).
 */
function vao_i18n_flat() {
	static $flat = null;
	if ( null === $flat ) {
		// Les traductions du thème priment sur les noms de produits/gammes (souvent sans malagasy).
		$flat = vao_product_strings();
		foreach ( vao_i18n() as $strings ) {
			foreach ( $strings as $fr => $mg ) {
				if ( '' !== $mg || ! isset( $flat[ $fr ] ) ) {
					$flat[ $fr ] = $mg;
				}
			}
		}
	}
	return $flat;
}

/**
 * Affiche un texte du thème traduit et échappé.
 */
function vao_e( $text ) {
	echo esc_html( vao_t( $text ) );
}

/**
 * Slug de la langue courante (ex. "fr", "mg"), ou "" sans Polylang.
 */
function vao_lang() {
	return function_exists( 'pll_current_language' ) ? (string) pll_current_language() : '';
}

/**
 * Vrai si la page est affichée en malagasy.
 */
function vao_is_mg() {
	return function_exists( 'pll_current_language' ) && 0 === strpos( (string) pll_current_language( 'locale' ), 'mg' );
}

/**
 * Types de contenu traduits élément par élément (leurs images contiennent du texte).
 */
function vao_translated_types() {
	return array( 'vao_banniere', 'vao_temoignage', 'vao_collaborateur', 'vao_rse', 'vao_offre' );
}

/**
 * Sélecteur de langue FR | MG (affiché seulement si Polylang est actif).
 */
function vao_language_switcher() {
	$languages = function_exists( 'pll_the_languages' )
		? pll_the_languages(
			array(
				'raw'           => 1,
				'hide_if_empty' => 0,
			)
		)
		: array();
	if ( ! $languages || count( $languages ) < 2 ) {
		// Polylang absent ou malagasy pas encore configuré : FR | MG reste affiché comme sur la maquette, MG inactif.
		echo '<nav class="lang-switch" aria-label="' . esc_attr( vao_t( 'Langue' ) ) . '">'
			. '<a href="' . esc_url( home_url( '/' ) ) . '" lang="fr" class="current" aria-current="true">FR</a>'
			. '<span class="lang-off" lang="mg" title="Malagasy">MG</span>'
			. '</nav>';
		return;
	}
	echo '<nav class="lang-switch" aria-label="' . esc_attr( vao_t( 'Langue' ) ) . '">';
	foreach ( $languages as $language ) {
		printf(
			'<a href="%s" lang="%s" hreflang="%s"%s>%s</a>',
			esc_url( $language['url'] ),
			esc_attr( $language['locale'] ),
			esc_attr( $language['slug'] ),
			$language['current_lang'] ? ' class="current" aria-current="true"' : '',
			esc_html( strtoupper( $language['slug'] ) )
		);
	}
	echo '</nav>';
}

/* -------------------------------------------------------------------------
 * Textes du thème : français => malagasy (pré-remplissage)
 * ---------------------------------------------------------------------- */

function vao_i18n() {
	static $all = null;
	if ( null !== $all ) {
		return $all;
	}
	$all = vao_i18n_base();
	// Textes propres à chaque page : inc/strings/*.php renvoient array( groupe => array( français => malagasy ) ).
	// Malagasy vide = à traduire (le français s'affiche en attendant).
	foreach ( glob( get_template_directory() . '/inc/strings/*.php' ) as $file ) {
		foreach ( (array) include $file as $group => $strings ) {
			$all[ $group ] = isset( $all[ $group ] ) ? array_merge( $all[ $group ], $strings ) : $strings;
		}
	}
	return $all;
}

function vao_i18n_base() {
	return array(
		'Navigation'    => array(
			'Accueil'                => 'Fandraisana',
			'Qui sommes-nous'        => 'Iza izahay',
			'Nos marques'            => 'Ny marikanay',
			'Nos Marques'            => 'Ny Marikanay',
			'Nous contacter'         => 'Hifandray aminay',
			'Notre engagement'       => 'Ny fanoloran-tenanay',
			'Notre gage de qualité'  => "Ny antoka ho an'ny kalitao",
			'Nos valeurs'            => 'Ny soatoavinay',
			'Nos actions RSE'        => 'Ny asa RSE ataonay',
			'Menu'                   => 'Menio',
			'Rechercher'             => 'Hikaroka',
			'Langue'                 => 'Fiteny',
			'Catalogue'              => 'Katalaogy',
			'Précédent'              => 'Teo aloha',
			'Suivant'                => 'Manaraka',
			'Fermer'                 => 'Hidy',
			'Retour en haut'         => 'Hiverina any ambony',
			'Détergent en poudre'    => 'Detergent vovoka',
			'Détergent en barre'     => 'Detergent bara',
			'Savon de toilette'      => 'Savony fandroana',
			'Savon barre'            => 'Savony bara',
			'Savon de ménage'        => 'Savony fanasan-damba',
			'Devenir distributeur'   => 'Ho mpaninjara',
			"Demande d'informations" => 'Fangatahana fanazavana',
			"Nos offres d'emploi"    => 'Ireo asa atolotray',
			'Candidature spontanée'  => 'Fangatahana asa',
		),
		'Pied de page'  => array(
			'Siège social'                                => 'Foibe',
			'Nom'                                         => 'Anarana',
			'Mail'                                        => 'Mailaka',
			'Téléphone'                                   => 'Finday',
			"J'accepte la politique de confidentialité"  => "Ekeko ny politika momba ny tsiambaratelo",
			"S'abonner"                                   => 'Hisoratra anarana',
			'Inscription à la newsletter'                 => 'Fisoratana anarana hahazoana vaovao',
			'Savons et Détergents de l’Océan Indien'      => '',
			'Politique de confidentialité'                => 'Politika momba ny tsiambaratelo',
			'Conditions générales'                        => 'Fepetra ankapobeny',
		),
		'Gammes'        => array(
			'Détergent'             => 'Detergent',
			'Savon Bar'             => 'Savony bara',
			'Voir plus de produits' => 'Hijery vokatra hafa',
		),
		'Recherche'     => array(
			'Résultats pour « %s »'       => "Valin'ny fikarohana « %s »",
			'Page introuvable'            => 'Tsy hita ny pejy',
			"Retour à l'accueil"          => "Hiverina amin'ny fandraisana",
			'Aucun résultat.'             => 'Tsy nisy valiny.',
			'Découvrez tous nos produits' => 'Jereo ny vokatray rehetra',
		),
		'Formulaires'   => array(
			'CANDIDATURE SPONTANÉE – SDOI'                           => 'FANGATAHANA ASA – SDOI',
			'1. Informations personnelles'                           => '1. Mombamomba anao',
			'Nom complet ou Raison sociale :'                        => "Anarana feno na anaran'ny orinasa :",
			'Nom du responsable :'                                   => "Anaran'ny tompon'andraikitra :",
			'E-mail :'                                               => 'Mailaka :',
			'Téléphone :'                                            => 'Finday :',
			'2. Localisation'                                        => '2. Toerana',
			'Adresse exacte :'                                       => 'Adiresy marina :',
			'Ville :'                                                => 'Tanàna :',
			'Pays :'                                                 => 'Firenena :',
			"3. Informations sur l'activité"                         => '3. Mombamomba ny asa',
			"Type d'activités :"                                     => 'Karazana asa :',
			'Libre-service'                                          => 'Libre-service',
			'Épicerie'                                               => 'Epicerie',
			'Grossiste'                                              => 'Mpivarotra ambongadiny',
			'Nom du magasin :'                                       => "Anaran'ny fivarotana :",
			"Année d'expérience :"                                   => 'Taona niasana :',
			"Produits d'intérêt :"                                   => 'Vokatra mahaliana anao :',
			'Capacité de stockage :'                                 => 'Fahafahana mitahiry entana :',
			'Volume estimé mensuel :'                                => 'Habetsahana tombanana isam-bolana :',
			'4. Informations légales'                                => '4. Mombamomba ara-dalàna',
			'N° RCS :'                                               => 'Laharana RCS :',
			'N° NIF :'                                               => 'Laharana NIF :',
			'N° STAT :'                                              => 'Laharana STAT :',
			'5. Pièces à fournir'                                    => '5. Antontan-taratasy omena',
			'Veuillez joindre les documents suivants :'              => 'Ampiarahonao ireto antontan-taratasy ireto :',
			'Copie NIF'                                              => "Kopian'ny NIF",
			'Copie STAT'                                             => "Kopian'ny STAT",
			'Copie RCS'                                              => "Kopian'ny RCS",
			'Importer un fichier'                                    => 'Hampiditra rakitra',
			'Envoyer'                                                => 'Alefa',
			'Autre'                                                  => 'Hafa',
			'Raison sociale :'                                       => "Anaran'ny orinasa ara-dalàna :",
			'Quantité souhaitée :'                                   => 'Habetsahana tadiavina :',
			'Message complémentaire'                                 => 'Hafatra fanampiny',
			'Nom *'                                                  => 'Anarana *',
			'E-mail *'                                               => 'Mailaka *',
			'Téléphone *'                                            => 'Finday *',
			'Adresse :'                                              => 'Adiresy :',
			'2. Profil professionnel'                                => '2. Mombamomba ara-matihanina',
			'Poste souhaité :'                                       => 'Toerana tadiavina :',
			"Domaine d'expertise :"                                  => 'Sehatra fahaizana :',
			"Années d'expérience :"                                  => 'Taona niasana :',
			'Disponibilité :'                                        => 'Fotoana ahafahana manomboka :',
			'3. Motivation'                                          => '3. Antony',
			'Pourquoi souhaitez-vous rejoindre SDOI ?'               => "Nahoana ianao no te hiditra ao amin'ny SDOI ?",
			'4. Pièces jointes'                                      => '4. Antontan-taratasy miaraka',
			'CV'                                                     => 'CV',
			'Lettre de motivation'                                   => 'Taratasy fanehoana faniriana',
			'Autres'                                                 => 'Hafa',
			'Merci, votre demande a bien été envoyée.'               => 'Misaotra, voaray soa aman-tsara ny fangatahanao.',
			"Une erreur est survenue lors de l'envoi. Merci de réessayer ou de nous écrire à %s." => "Nisy olana nandritra ny fandefasana. Andramo indray azafady, na manorata aminay amin'ny %s.",
		),
		// Valeurs par défaut de Apparence > Personnaliser > Thème VAO.
		'Personnaliser' => array(
			'Une identité ancrée'                             => 'Maha-izy anay miorim-paka',
			"Plus de 20 ans au service de l'hygiène à Madagascar." => 'Maherin\'ny 20 taona manompo ny fahadiovana eto Madagasikara.',
			'Des savons pensés pour le quotidien des Malagasy' => "Savony noforonina ho an'ny fiainana andavanandron'ny Malagasy",
			'Produits recommandés'                            => 'Vokatra atolotray',
			'Découvrir'                                       => 'Hijery',
			'Accédez à nos formulaires'                       => 'Ireo taratasy fangatahana',
			'Vous souhaitez rejoindre nos équipes ? Envoyez-nous votre candidature spontanée et partagez avec nous votre profil et vos motivations.' => "Te hiditra ao amin'ny ekipanay ve ianao ? Alefaso aminay ny fangatahanao asa ary ampahafantaro anay ny momba anao sy ny antony tianao hiarahana miasa aminay.",
			'Cliquez ici'                                     => 'Tsindrio eto',
			'DEVENIR REVENDEUR'                               => 'HO MPIVAROTRA',
			'Vous souhaitez distribuer nos produits ? Remplissez ce formulaire pour nous présenter votre projet et devenir l’un de nos revendeurs.' => "Te hizara ny vokatray ve ianao ? Fenoy ity taratasy ity hampahafantarana anay ny tetikasanao ary ho iray amin'ireo mpivarotra anay.",
			'DEMANDE D’ÉCHANTILLON'                           => 'FANGATAHANA SANTIONANY',
			'Vous souhaitez découvrir nos produits ? Faites votre demande d’échantillon en remplissant simplement ce formulaire.' => 'Te hahafantatra ny vokatray ve ianao ? Angataho ny santionany amin\'ny famenoana fotsiny ity taratasy ity.',
			"Depuis plus de 20 ans, SDOI s'inscrit comme un acteur engagé dans le développement de l'industrie de l'hygiène et de l'entretien à Madagascar. Portée par une volonté forte de valoriser le « Vita Malagasy », l'entreprise place la production locale et l'innovation industrielle au cœur de sa stratégie." => "Nandritra ny 20 taona mahery, ny SDOI dia mpandray anjara mavitrika amin'ny fampandrosoana ny indostrian'ny fahadiovana sy ny fikarakarana eto Madagasikara. Entanin'ny faniriana mafy hanome lanja ny « Vita Malagasy », ny orinasa dia mametraka ny famokarana eto an-toerana sy ny fanavaozana ara-indostria ho fototry ny paikady arahiny.",
			'Nos produits'                                    => 'Ny vokatray',
		),
	);
}

/**
 * Traduction malagasy proposée pour les poids et conditionnements des produits.
 */
function vao_mg_units( $text ) {
	return strtr(
		$text,
		array(
			'unité'    => 'isa',
			'morceaux' => 'vongany',
			'sachets'  => 'fonosana',
		)
	);
}

/* -------------------------------------------------------------------------
 * Polylang
 * ---------------------------------------------------------------------- */

/**
 * La bannière et les témoignages sont traduisibles (Polylang les gère comme des pages).
 */
function vao_pll_post_types( $types ) {
	foreach ( vao_translated_types() as $type ) {
		$types[ $type ] = $type;
	}
	return $types;
}
add_filter( 'pll_get_post_types', 'vao_pll_post_types' );

/**
 * Textes des produits et des gammes (communs aux deux langues, seul le texte est traduit).
 */
function vao_product_strings() {
	$strings = array();
	foreach ( get_posts( array( 'post_type' => 'vao_produit', 'numberposts' => -1 ) ) as $product ) {
		foreach ( array( '_vao_poids', '_vao_conditionnement' ) as $key ) {
			$value = get_post_meta( $product->ID, $key, true );
			if ( $value ) {
				$strings[ $value ] = vao_mg_units( $value );
			}
		}
	}
	foreach ( vao_gammes( false ) as $term ) {
		$strings[ $term->name ] = $term->name;
		if ( $term->description ) {
			$strings[ $term->description ] = '';
		}
	}
	return $strings;
}

/**
 * Textes du Personnaliser à traduire (valeurs actuelles).
 */
function vao_customizer_strings() {
	$strings = array();
	foreach ( vao_customizer_fields() as $key => $field ) {
		if ( vao_mod_is_translatable( $key ) ) {
			$value = get_theme_mod( 'vao_' . $key, $field[3] );
			if ( $value ) {
				$strings[ $value ] = $field[2];
			}
		}
	}
	return $strings;
}

/**
 * Déclare tous les textes dans Langues > Traductions des chaînes.
 */
function vao_pll_register_strings() {
	if ( ! function_exists( 'pll_register_string' ) || ! is_admin() ) {
		return;
	}
	$registered = array();
	$register   = function ( $text, $group, $multiline = false ) use ( &$registered ) {
		if ( '' === (string) $text || isset( $registered[ $text ] ) ) {
			return;
		}
		$registered[ $text ] = true;
		pll_register_string( wp_html_excerpt( $text, 40, '…' ), $text, 'Thème VAO - ' . $group, $multiline );
	};

	foreach ( vao_customizer_strings() as $text => $type ) {
		$register( $text, 'Personnaliser', 'textarea' === $type );
	}
	foreach ( vao_i18n() as $group => $strings ) {
		foreach ( array_keys( $strings ) as $text ) {
			$register( $text, $group, strlen( $text ) > 80 );
		}
	}
	foreach ( array_keys( vao_product_strings() ) as $text ) {
		$register( $text, 'Produits' );
	}
}
add_action( 'init', 'vao_pll_register_strings', 20 );

/**
 * Langue malagasy configurée dans Polylang (objet PLL_Language), ou null.
 */
function vao_pll_mg_language() {
	if ( ! function_exists( 'PLL' ) || ! isset( PLL()->model ) ) {
		return null;
	}
	foreach ( PLL()->model->get_languages_list() as $language ) {
		if ( 0 === strpos( $language->locale, 'mg' ) ) {
			return $language;
		}
	}
	return null;
}

/**
 * Dès que la langue malagasy existe : attribue le français au contenu existant,
 * crée les pages malagasy et pré-remplit les traductions. Une seule fois.
 */
function vao_pll_setup() {
	if ( get_option( 'vao_pll_setup' ) || ! current_user_can( 'manage_options' ) ) {
		return;
	}
	if ( ! function_exists( 'pll_default_language' ) || ! function_exists( 'pll_save_post_translations' ) ) {
		return;
	}
	$mg      = vao_pll_mg_language();
	$default = pll_default_language();
	if ( ! $mg || ! $default || $mg->slug === $default ) {
		return;
	}

	// Contenu sans langue => langue par défaut (français).
	$posts = get_posts(
		array(
			'post_type'   => array_merge( array( 'page' ), vao_translated_types() ),
			'numberposts' => -1,
			'post_status' => 'any',
			'lang'        => '',
		)
	);
	foreach ( $posts as $post ) {
		if ( ! pll_get_post_language( $post->ID ) ) {
			pll_set_post_language( $post->ID, $default );
		}
	}

	// Pages du thème en malagasy.
	foreach ( vao_pages() as $slug => $page ) {
		$fr = get_page_by_path( $slug );
		if ( ! $fr || pll_get_post( $fr->ID, $mg->slug ) ) {
			continue;
		}
		$id = wp_insert_post(
			array(
				'post_title'  => $page[2],
				'post_name'   => $page[3],
				'post_status' => 'publish',
				'post_type'   => 'page',
			)
		);
		if ( ! $id || is_wp_error( $id ) ) {
			continue;
		}
		if ( $page[1] ) {
			update_post_meta( $id, '_wp_page_template', $page[1] );
		}
		pll_set_post_language( $id, $mg->slug );
		$translations              = pll_get_post_translations( $fr->ID );
		$translations[ $default ]  = $fr->ID;
		$translations[ $mg->slug ] = $id;
		pll_save_post_translations( $translations );
	}

	vao_pll_prefill( $mg );
	update_option( 'vao_pll_setup', $mg->slug );
}
add_action( 'admin_init', 'vao_pll_setup' );

/**
 * Pré-remplit les traductions malagasy encore vides (Langues > Traductions des chaînes).
 */
function vao_pll_prefill( $language ) {
	if ( ! class_exists( 'PLL_MO' ) ) {
		return;
	}
	$translations = vao_i18n_flat();

	$mo = new PLL_MO();
	if ( ! method_exists( $mo, 'import_from_db' ) || ! method_exists( $mo, 'make_entry' ) || ! method_exists( $mo, 'export_to_db' ) ) {
		return;
	}
	$mo->import_from_db( $language );
	foreach ( $translations as $fr => $mg ) {
		if ( '' !== $mg && $mg !== $fr && $mo->translate( $fr ) === $fr ) {
			$mo->add_entry( $mo->make_entry( $fr, $mg ) );
		}
	}
	$mo->export_to_db( $language );
}

/**
 * Après une mise à jour du thème apportant de nouvelles traductions, les ajoute dans Polylang
 * (seulement les chaînes encore vides : les traductions saisies dans l'admin sont conservées).
 */
function vao_pll_refresh_prefill() {
	if ( ! get_option( 'vao_pll_setup' ) || ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$mg = vao_pll_mg_language();
	if ( ! $mg ) {
		return;
	}
	$hash = md5( serialize( vao_i18n_flat() ) );
	if ( get_option( 'vao_pll_prefill_hash' ) === $hash ) {
		return;
	}
	vao_pll_prefill( $mg );
	update_option( 'vao_pll_prefill_hash', $hash );
}
add_action( 'admin_init', 'vao_pll_refresh_prefill', 20 );
