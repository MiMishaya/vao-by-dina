<?php
/**
 * Réglages modifiables dans Apparence > Personnaliser > Thème VAO.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Sections du Personnaliseur.
 */
function vao_customizer_sections() {
	return array(
		'vao_general'     => 'Logos et coordonnées',
		'vao_accueil'     => "Page d'accueil",
		'vao_formulaires' => 'Formulaires',
		'vao_chargement'  => 'Écran de chargement',
		'vao_mg'          => 'Images en malagasy (facultatif)',
	);
}

/**
 * Réglages : clé => array( section, libellé, type, valeur par défaut ).
 * Types : text, textarea, url, email, image.
 */
function vao_customizer_fields() {
	static $fields = null;
	if ( null !== $fields ) {
		return $fields;
	}
	$fields = vao_customizer_base_fields();

	// Pour chaque image : une version malagasy facultative (images contenant du texte).
	foreach ( $fields as $key => $field ) {
		if ( 'image' === $field[2] ) {
			$fields[ $key . '_mg' ] = array( 'vao_mg', $field[1] . ' — version malagasy', 'image', '' );
		}
	}
	return $fields;
}

/**
 * Réglages qui ne se traduisent pas (coordonnées, liens, noms propres).
 */
function vao_mod_is_translatable( $key ) {
	$fields = vao_customizer_fields();
	return isset( $fields[ $key ] )
		&& in_array( $fields[ $key ][2], array( 'text', 'textarea' ), true )
		&& ! in_array( $key, array( 'telephone', 'fb1_nom', 'fb2_nom', 'fb3_nom' ), true );
}

function vao_customizer_base_fields() {
	return array(
		// Logos et coordonnées.
		'logo_header'          => array( 'vao_general', "Logo de l'en-tête", 'image', vao_img( 'Render element 02/Element_004-Formulaire 01.png' ) ),
		'logo_sdoi'            => array( 'vao_general', 'Logo SDOI (accueil et pied de page)', 'image', vao_img( 'Render element 02/Element-03.png' ) ),
		'logo_vao'             => array( 'vao_general', 'Logo VAO (pied de page)', 'image', vao_img( 'Render element 02/Element-04.png' ) ),
		'footer_tagline'       => array( 'vao_general', 'Phrase du pied de page', 'textarea', 'Notre équipe dédiée est là pour vous accompagner et vous apporter les solutions adaptées à vos besoins.' ),
		'siege'                => array( 'vao_general', 'Notre siège', 'text', 'En face ex-abattoir - Aranta Mahajanga' ),
		'telephone'            => array( 'vao_general', 'Service client (téléphone)', 'text', '+261 32 11 225 95' ),
		'email'                => array( 'vao_general', 'E-mail de contact affiché', 'email', 'contact@vao.mg' ),
		'fb1_nom'              => array( 'vao_general', 'Facebook 1 - nom', 'text', 'Savony Vao' ),
		'fb1_url'              => array( 'vao_general', 'Facebook 1 - lien (vide = masqué)', 'url', 'https://www.facebook.com/vaomg' ),
		'fb2_nom'              => array( 'vao_general', 'Facebook 2 - nom', 'text', 'Vao Line' ),
		'fb2_url'              => array( 'vao_general', 'Facebook 2 - lien (vide = masqué)', 'url', 'https://www.facebook.com/sdoivaoline' ),
		'fb3_nom'              => array( 'vao_general', 'Facebook 3 - nom', 'text', 'Max' ),
		'fb3_url'              => array( 'vao_general', 'Facebook 3 - lien (vide = masqué)', 'url', 'https://www.facebook.com/profile.php?id=61593476278606' ),

		// Page d'accueil.
		'about_surtitre'       => array( 'vao_accueil', 'À propos - surtitre', 'text', 'Une identité ancrée' ),
		'about_titre'          => array( 'vao_accueil', 'À propos - titre', 'text', "Plus de 20 ans au service de l'hygiène à Madagascar." ),
		'about_texte'          => array( 'vao_accueil', 'À propos - texte', 'textarea', "Depuis plus de 20 ans, SDOI contribue au développement de l'industrie de l'hygiène à Madagascar. L'entreprise conçoit, fabrique et commercialise localement des savons et détergents alliant qualité, efficacité et accessibilité. Engagée dans le « Vita Malagasy », SDOI place l'innovation et la production locale au cœur de sa stratégie, tout en participant durablement au développement économique du pays." ),
		'marques_titre'        => array( 'vao_accueil', 'Titre du bandeau des marques', 'text', 'Des savons pensés pour le quotidien des Malagasy' ),
		'stats_image'          => array( 'vao_accueil', 'Image des chiffres clés', 'image', vao_img( 'Render element 02/Element-09.png' ) ),
		'stats_alt'            => array( 'vao_accueil', "Chiffres clés - description de l'image", 'textarea', "10 Marques, 20 ans D'existence, 200 Collaborateurs, + 50 Distributeurs nationaux, + 60 Références de produits, + 500 Épiceries partenaires" ),
		'temoignages_titre'    => array( 'vao_accueil', 'Titre des témoignages', 'text', "Parce qu'ils ont placé leur confiance en nous" ),
		'recommandes_titre'    => array( 'vao_accueil', 'Titre des produits recommandés', 'text', 'Produits recommandés' ),
		'recommandes_bouton'   => array( 'vao_accueil', 'Produits recommandés - texte du bouton', 'text', 'Découvrir' ),
		'recommandes_lien'     => array( 'vao_accueil', 'Produits recommandés - lien « tout voir »', 'text', 'Tout voir...' ),
		'cta_titre'            => array( 'vao_accueil', 'Titre des formulaires', 'text', 'Accédez à nos formulaires' ),
		'cta1_titre'           => array( 'vao_accueil', 'Carte 1 (revendeur) - titre', 'text', 'DEVENIR REVENDEUR' ),
		'cta1_texte'           => array( 'vao_accueil', 'Carte 1 (revendeur) - texte', 'textarea', "Vous souhaitez distribuer nos produits ? N'hésitez pas à remplir notre formulaire pour élargir votre offre de produits et développer vos revenus." ),
		'cta1_image'           => array( 'vao_accueil', 'Carte 1 (revendeur) - image', 'image', vao_img( 'temoin/Web project 02 élement photos_Témoignage.png' ) ),
		'cta2_titre'           => array( 'vao_accueil', 'Carte 2 (échantillon) - titre', 'text', "DEMANDE D'ÉCHANTILLON" ),
		'cta2_texte'           => array( 'vao_accueil', 'Carte 2 (échantillon) - texte', 'textarea', "Vous souhaitez découvrir nos produits ? Faites votre demande d'échantillon en remplissant simplement le formulaire." ),
		'cta2_image'           => array( 'vao_accueil', 'Carte 2 (échantillon) - image', 'image', vao_img( 'temoin/Web project 02 élement photos-02.png' ) ),
		'cta3_titre'           => array( 'vao_accueil', 'Carte 3 (candidature) - titre', 'text', 'CANDIDATURE SPONTANÉE - SDOI' ),
		'cta3_texte'           => array( 'vao_accueil', 'Carte 3 (candidature) - texte', 'textarea', 'Vous souhaitez rejoindre nos équipes ? Envoyez-nous votre candidature spontanée et partagez avec nous votre profil et vos motivations.' ),
		'cta3_image'           => array( 'vao_accueil', 'Carte 3 (candidature) - image', 'image', vao_img( 'temoin/Web project 02 élement photos-03.png' ) ),
		'cta_lien'             => array( 'vao_accueil', 'Texte du lien des cartes', 'text', 'Cliquez ici' ),

		// Formulaires.
		'form_email'           => array( 'vao_formulaires', "Adresse qui reçoit les formulaires (vide = e-mail de l'administrateur)", 'email', '' ),
		'form_img_revendeur'   => array( 'vao_formulaires', 'Image - Devenir revendeur', 'image', vao_img( 'temoin/Web project 02 élement photos_Témoignage.png' ) ),
		'form_img_echantillon' => array( 'vao_formulaires', "Image - Demande d'échantillon", 'image', vao_img( 'temoin/Web project 02 élement photos-02.png' ) ),
		'form_img_candidature' => array( 'vao_formulaires', 'Image - Candidature spontanée', 'image', vao_img( 'temoin/Web project 02 élement photos-03.png' ) ),
		'form_badge'           => array( 'vao_formulaires', 'Image sous les formulaires', 'image', vao_img( 'temoignage/Web project 02 élement-10.png' ) ),

		// Écran de chargement.
		'loading_texte'        => array( 'vao_chargement', 'Texte', 'text', 'Nos produits' ),
		'loading_fond'         => array( 'vao_chargement', 'Image de fond', 'image', vao_img( 'Render element 02/Web project Element 03_002 BG laoding.png' ) ),
		'loading_logo'         => array( 'vao_chargement', 'Logo (en haut)', 'image', vao_img( 'Render element 02/Web project Element 03_001 20ans.png' ) ),
		'loading_produits'     => array( 'vao_chargement', 'Image des produits (en bas)', 'image', vao_img( 'Render element 02/Web project Element 03_003 PRODUITS.png' ) ),
	);
}

/**
 * Valeur d'un réglage, dans la langue courante.
 * Textes : traduits via Langues > Traductions des chaînes.
 * Images : version malagasy si elle est définie ; une image vidée reprend l'image d'origine.
 */
function vao_mod( $key ) {
	$fields = vao_customizer_fields();
	if ( ! isset( $fields[ $key ] ) ) {
		return '';
	}
	list( , , $type, $default ) = $fields[ $key ];
	$value = get_theme_mod( 'vao_' . $key, $default );

	if ( 'image' === $type ) {
		if ( vao_is_mg() && isset( $fields[ $key . '_mg' ] ) ) {
			$mg = get_theme_mod( 'vao_' . $key . '_mg', '' );
			if ( $mg ) {
				return $mg;
			}
		}
		return $value ? $value : $default;
	}
	return vao_mod_is_translatable( $key ) ? vao_t( $value ) : $value;
}

function vao_customize_register( $wp_customize ) {
	$wp_customize->add_panel(
		'vao',
		array(
			'title'    => 'Thème VAO',
			'priority' => 30,
		)
	);
	$priority = 10;
	foreach ( vao_customizer_sections() as $id => $title ) {
		$wp_customize->add_section(
			$id,
			array(
				'title'    => $title,
				'panel'    => 'vao',
				'priority' => $priority += 10,
			)
		);
	}

	$sanitizers = array(
		'text'     => 'sanitize_text_field',
		'textarea' => 'sanitize_textarea_field',
		'url'      => 'esc_url_raw',
		'image'    => 'esc_url_raw',
		'email'    => 'sanitize_email',
	);

	foreach ( vao_customizer_fields() as $key => $field ) {
		list( $section, $label, $type, $default ) = $field;
		$setting = 'vao_' . $key;

		$wp_customize->add_setting(
			$setting,
			array(
				'default'           => $default,
				'sanitize_callback' => $sanitizers[ $type ],
			)
		);

		if ( 'image' === $type ) {
			$wp_customize->add_control(
				new WP_Customize_Image_Control(
					$wp_customize,
					$setting,
					array(
						'label'   => $label,
						'section' => $section,
					)
				)
			);
		} else {
			$wp_customize->add_control(
				$setting,
				array(
					'label'   => $label,
					'section' => $section,
					'type'    => $type,
				)
			);
		}
	}
}
add_action( 'customize_register', 'vao_customize_register' );
