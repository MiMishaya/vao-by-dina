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
	return ( function_exists( 'pll__' ) && '' !== (string) $text ) ? pll__( $text ) : $text;
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
	return array( 'vao_banniere', 'vao_temoignage' );
}

/**
 * Sélecteur de langue FR | MG (affiché seulement si Polylang est actif).
 */
function vao_language_switcher() {
	if ( ! function_exists( 'pll_the_languages' ) ) {
		return;
	}
	$languages = pll_the_languages(
		array(
			'raw'           => 1,
			'hide_if_empty' => 0,
		)
	);
	if ( ! $languages || count( $languages ) < 2 ) {
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
	return array(
		'Navigation'    => array(
			'Accueil'               => 'Fandraisana',
			'Notre gamme'           => 'Ny vokatray',
			'Formulaires'           => 'Fangatahana',
			'Menu'                  => 'Menio',
			'Rechercher'            => 'Hikaroka',
			'Langue'                => 'Fiteny',
			'Précédent'             => 'Teo aloha',
			'Suivant'               => 'Manaraka',
			'Tous les produits'     => 'Ny vokatra rehetra',
			'Détergent en poudre'   => 'Detergent vovoka',
			'Détergent en barre'    => 'Detergent bara',
			'Savon de toilette'     => 'Savony fandroana',
			'Savon en barre'        => 'Savony bara',
			'Savon translucide'     => 'Savony mangarahara',
			'Savon de ménage'       => 'Savony fanasan-damba',
			'Devenir revendeur'     => 'Ho mpivarotra',
			"Demande d'échantillon" => 'Fangatahana santionany',
			'Candidature spontanée' => 'Fangatahana asa',
		),
		'Pied de page'  => array(
			'Notre siège'    => 'Foibenay',
			'Service client' => "Sampan-draharaha ho an'ny mpanjifa",
			'Contact'        => 'Fifandraisana',
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
			'DEVENIR REVENDEUR'                                      => 'HO MPIVAROTRA',
			"DEMANDE D'ÉCHANTILLON"                                  => 'FANGATAHANA SANTIONANY',
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
			'Nom :'                                                  => 'Anarana :',
			'Prénom :'                                               => "Fanampin'anarana :",
			'2. Informations professionnelles'                       => '2. Mombamomba ara-asa',
			"Nom de l'entreprise / magasin :"                        => "Anaran'ny orinasa / fivarotana :",
			"Type d'activité :"                                      => 'Karazana asa :',
			'Détaillant'                                             => 'Mpivarotra antsinjarany',
			'Supermarché'                                            => 'Supermarché',
			'Autre :'                                                => 'Hafa :',
			'Autre'                                                  => 'Hafa',
			'Raison sociale :'                                       => "Anaran'ny orinasa ara-dalàna :",
			'Ville / Région :'                                       => 'Tanàna / Faritra :',
			'Adresse complète :'                                     => 'Adiresy feno :',
			"3. Demande d'échantillon"                               => '3. Fangatahana santionany',
			'Produits souhaités :'                                   => 'Vokatra tadiavina :',
			'Détergent poudre'                                       => 'Detergent vovoka',
			'Détergent barre'                                        => 'Detergent bara',
			'Quantité souhaitée :'                                   => 'Habetsahana tadiavina :',
			'Test (petit volume)'                                    => 'Fitsapana (habetsahana kely)',
			'Évaluation commerciale'                                 => 'Fanombanana ara-barotra',
			'Objectif de la demande :'                               => "Tanjon'ny fangatahana :",
			'4. Potentiel commercial (important pour qualification)' => "4. Fahafahana ara-barotra (zava-dehibe amin'ny fanombanana)",
			'Zone de distribution prévue :'                          => 'Faritra hanaparitahana kasaina :',
			'5. Message complémentaire'                              => '5. Hafatra fanampiny',
			'Message complémentaire'                                 => 'Hafatra fanampiny',
			'Nom *'                                                  => 'Anarana *',
			'Prénom *'                                               => "Fanampin'anarana *",
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
			'Notre équipe dédiée est là pour vous accompagner et vous apporter les solutions adaptées à vos besoins.' => "Vonona hanampy anao ny ekipanay ary hanolotra vahaolana mifanaraka amin'ny filanao.",
			'En face ex-abattoir - Aranta Mahajanga'          => 'Manoloana ny toeram-pamonoana omby taloha - Aranta Mahajanga',
			'Une identité ancrée'                             => 'Maha-izy anay miorim-paka',
			"Plus de 20 ans au service de l'hygiène à Madagascar." => 'Maherin\'ny 20 taona manompo ny fahadiovana eto Madagasikara.',
			"Depuis plus de 20 ans, SDOI contribue au développement de l'industrie de l'hygiène à Madagascar. L'entreprise conçoit, fabrique et commercialise localement des savons et détergents alliant qualité, efficacité et accessibilité. Engagée dans le « Vita Malagasy », SDOI place l'innovation et la production locale au cœur de sa stratégie, tout en participant durablement au développement économique du pays." => "Maherin'ny 20 taona izay no nandraisan'ny SDOI anjara tamin'ny fampandrosoana ny indostrian'ny fahadiovana eto Madagasikara. Mamorona, manamboatra ary mivarotra eto an-toerana savony sy detergent manambatra ny kalitao, ny fahombiazana ary ny vidiny takatry ny rehetra ny orinasa. Mirotsaka amin'ny « Vita Malagasy », apetrakin'ny SDOI ho ivon'ny paikadiny ny fanavaozana sy ny famokarana eto an-toerana, sady mandray anjara maharitra amin'ny fampandrosoana ara-toekarenan'ny firenena.",
			'Des savons pensés pour le quotidien des Malagasy' => "Savony noforonina ho an'ny fiainana andavanandron'ny Malagasy",
			"10 Marques, 20 ans D'existence, 200 Collaborateurs, + 50 Distributeurs nationaux, + 60 Références de produits, + 500 Épiceries partenaires" => 'Marika 10, taona 20 nisiana, mpiara-miasa 200, mpaninjara + 50 manerana ny nosy, karazam-bokatra + 60, epicerie mpiara-miasa + 500',
			"Parce qu'ils ont placé leur confiance en nous"   => 'Satria nametraka ny fitokisany taminay izy ireo',
			'Produits recommandés'                            => 'Vokatra atolotray',
			'Découvrir'                                       => 'Hijery',
			'Tout voir...'                                    => 'Hijery ny rehetra...',
			'Accédez à nos formulaires'                       => 'Ireo taratasy fangatahana',
			"Vous souhaitez distribuer nos produits ? N'hésitez pas à remplir notre formulaire pour élargir votre offre de produits et développer vos revenus." => "Te hivarotra ny vokatray ve ianao ? Aza misalasala mameno ny taratasy fangatahana mba hanitarana ny vokatra atolotrao sy hampitomboana ny fidiram-bolanao.",
			"Vous souhaitez découvrir nos produits ? Faites votre demande d'échantillon en remplissant simplement le formulaire." => 'Te hahafantatra ny vokatray ve ianao ? Fenoy fotsiny ny taratasy fangatahana santionany.',
			'CANDIDATURE SPONTANÉE - SDOI'                    => 'FANGATAHANA ASA - SDOI',
			'Vous souhaitez rejoindre nos équipes ? Envoyez-nous votre candidature spontanée et partagez avec nous votre profil et vos motivations.' => "Te hiditra ao amin'ny ekipanay ve ianao ? Alefaso aminay ny fangatahanao asa ary ampahafantaro anay ny momba anao sy ny antony tianao hiarahana miasa aminay.",
			'Cliquez ici'                                     => 'Tsindrio eto',
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
	$translations = array();
	foreach ( vao_i18n() as $strings ) {
		$translations = array_merge( $translations, $strings );
	}
	$translations = array_merge( $translations, vao_product_strings() );

	$mo = new PLL_MO();
	if ( ! method_exists( $mo, 'import_from_db' ) || ! method_exists( $mo, 'make_entry' ) || ! method_exists( $mo, 'export_to_db' ) ) {
		return;
	}
	$mo->import_from_db( $language );
	foreach ( $translations as $fr => $mg ) {
		if ( $mg !== $fr && $mo->translate( $fr ) === $fr ) {
			$mo->add_entry( $mo->make_entry( $fr, $mg ) );
		}
	}
	$mo->export_to_db( $language );
}
