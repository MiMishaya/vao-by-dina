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
		'vao_contacts'    => "Contacts de l'équipe",
		'vao_accueil'     => "Page d'accueil",
		'vao_apropos'     => 'Page Qui sommes-nous',
		'vao_formulaires' => 'Formulaires',
		'vao_chargement'  => 'Écran de chargement',
		'vao_mg'          => 'Images en malagasy (facultatif)',
	);
}

/**
 * Réglages : clé => array( section, libellé, type, valeur par défaut ).
 * Types : text, textarea, url, email, emails (liste séparée par des virgules), image.
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
		&& ! in_array( $key, array( 'telephone', 'siege' ), true )
		&& ! preg_match( '/_(tel|email)$/', $key );
}

function vao_customizer_base_fields() {
	$dests = array();
	foreach ( vao_forms() as $type => $form ) {
		$dests[ 'dest_' . $type ] = array( 'vao_formulaires', 'Destinataires - ' . $form[0] . ' (adresses séparées par des virgules)', 'emails', $form[3] );
	}

	return array_merge(
		array(
			// Logos et coordonnées.
			'logo_header'           => array( 'vao_general', "Logo de l'en-tête", 'image', vao_img( 'Render element 02/Element_004-Formulaire 01.png' ) ),
			'logo_sdoi'             => array( 'vao_general', 'Logo SDOI (accueil)', 'image', vao_img( 'Render element 02/Element-03.png' ) ),
			'logo_vao'              => array( 'vao_general', 'Logo VAO (pied de page, affiché en blanc)', 'image', vao_img( 'Render element 02/Element_004-Formulaire 01.png' ) ),
			'siege'                 => array( 'vao_general', 'Siège social', 'text', 'Aranta - BP 108 Mahajanga 401 - Madagascar' ),
			'telephone'             => array( 'vao_general', 'Service client (téléphone)', 'text', '+261 32 11 225 95' ),
			'email'                 => array( 'vao_general', 'E-mail de contact affiché', 'email', 'contact@vao.mg' ),
			'facebook_url'          => array( 'vao_general', 'Page Facebook (vide = masqué)', 'url', 'https://www.facebook.com/vaomg/' ),
			'linkedin_url'          => array( 'vao_general', 'Page LinkedIn (vide = masqué)', 'url', 'https://www.linkedin.com/company/vao-savons-et-d%C3%A9tergents/posts/' ),
			'catalogue_url'         => array( 'vao_general', 'Catalogue PDF (lien du bouton « Catalogue », vide = masqué)', 'url', vao_img( 'docs/catalogue-2026.pdf' ) ),

			// Contacts de l'équipe (accueil).
			'marketing_tel'         => array( 'vao_contacts', 'Service Marketing - téléphone', 'text', '+261 32 11 226 62' ),
			'marketing_email'       => array( 'vao_contacts', 'Service Marketing - e-mail', 'text', 'marketing@vao.mg' ),
			'commercial_tel'        => array( 'vao_contacts', 'Service Commercial - téléphone', 'text', '+261 32 11 224 93' ),
			'commercial_email'      => array( 'vao_contacts', 'Service Commercial - e-mail', 'text', 'vente directe@vao.mg' ),
			'grandcompte_tel'       => array( 'vao_contacts', 'Service Grand Compte - téléphone', 'text', '+261 32 11 226 92' ),
			'grandcompte_email'     => array( 'vao_contacts', 'Service Grand Compte - e-mail', 'text', 'grandcompte@vao.mg' ),

			// Page d'accueil.
			'about_surtitre'        => array( 'vao_accueil', 'SDOI - surtitre', 'text', 'Une identité ancrée' ),
			'about_titre'           => array( 'vao_accueil', 'SDOI - titre', 'text', "Plus de 20 ans au service de l'hygiène à Madagascar." ),
			'about_texte'           => array( 'vao_accueil', 'SDOI - texte', 'textarea', "Depuis plus de 20 ans, SDOI s'inscrit comme un acteur engagé dans le développement de l'industrie de l'hygiène et de l'entretien à Madagascar. Portée par une volonté forte de valoriser le « Vita Malagasy », l'entreprise place la production locale et l'innovation industrielle au cœur de sa stratégie." ),
			'marques_titre'         => array( 'vao_accueil', 'Titre du bandeau des marques', 'text', 'Des savons pensés pour le quotidien des Malagasy' ),
			'engagement_image'      => array( 'vao_accueil', 'Notre engagement - image', 'image', vao_img( 'maquette/accueil-engagement.jpg' ) ),
			'selection1_image'      => array( 'vao_accueil', 'Produits sélectionnés - image 1 (détergent)', 'image', vao_img( 'maquette/selection-1.jpg' ) ),
			'selection2_image'      => array( 'vao_accueil', 'Produits sélectionnés - image 2 (savon barre)', 'image', vao_img( 'maquette/selection-2.jpg' ) ),
			'selection3_image'      => array( 'vao_accueil', 'Produits sélectionnés - image 3 (savon de toilette)', 'image', vao_img( 'maquette/selection-3.jpg' ) ),
			'cta1_titre'            => array( 'vao_accueil', 'Carte 1 (distributeur) - titre', 'text', 'DEVENIR DISTRIBUTEUR' ),
			'cta1_texte'            => array( 'vao_accueil', 'Carte 1 (distributeur) - texte', 'textarea', 'Vous souhaitez distribuer nos produits ? Remplissez le formulaire pour nous présenter votre projet et devenir l’un de nos distributeurs.' ),
			'cta1_image'            => array( 'vao_accueil', 'Carte 1 (distributeur) - image', 'image', vao_img( 'temoin/Web project 02 élement photos_Témoignage.png' ) ),
			'cta2_titre'            => array( 'vao_accueil', "Carte 2 (demande d'informations) - titre", 'text', "DEMANDE D'INFORMATIONS" ),
			'cta2_texte'            => array( 'vao_accueil', "Carte 2 (demande d'informations) - texte", 'textarea', 'Vous souhaitez découvrir nos produits, nos tarifs ou recevoir des échantillons ? Faites votre demande en remplissant simplement le formulaire.' ),
			'cta2_image'            => array( 'vao_accueil', "Carte 2 (demande d'informations) - image", 'image', vao_img( 'temoin/Web project 02 élement photos-02.png' ) ),
			'cta3_titre'            => array( 'vao_accueil', 'Carte 3 (candidature) - titre', 'text', 'CANDIDATURE SPONTANÉE – SDOI' ),
			'cta3_texte'            => array( 'vao_accueil', 'Carte 3 (candidature) - texte', 'textarea', 'Vous souhaitez rejoindre nos équipes ? Envoyez-nous votre candidature spontanée et partagez avec nous votre profil et vos motivations.' ),
			'cta3_image'            => array( 'vao_accueil', 'Carte 3 (candidature) - image', 'image', vao_img( 'temoin/Web project 02 élement photos-03.png' ) ),
			'cta_lien'              => array( 'vao_accueil', 'Texte du lien des cartes', 'text', 'Cliquez ici' ),

			// Page Qui sommes-nous.
			'label_image'           => array( 'vao_apropos', 'Qualité certifiée - image du label', 'image', vao_img( 'maquette/qsn-label.jpg' ) ),
			'labo_image'            => array( 'vao_apropos', 'Nos valeurs - photo', 'image', vao_img( 'maquette/qsn-labo.jpg' ) ),
			'pub1_image'            => array( 'vao_apropos', 'Visuel bas de page 1', 'image', vao_img( 'maquette/pub-1.jpg' ) ),
			'pub2_image'            => array( 'vao_apropos', 'Visuel bas de page 2', 'image', vao_img( 'maquette/pub-2.jpg' ) ),
			'pub3_image'            => array( 'vao_apropos', 'Visuel bas de page 3', 'image', vao_img( 'maquette/pub-3.jpg' ) ),

			// Formulaires.
			'form_img_distributeur' => array( 'vao_formulaires', 'Image - Devenir distributeur', 'image', vao_img( 'maquette/form-distributeur.jpg' ) ),
			'form_img_information'  => array( 'vao_formulaires', "Image - Demande d'informations", 'image', vao_img( 'maquette/form-information.jpg' ) ),
			'form_img_candidature'  => array( 'vao_formulaires', 'Image - Candidature spontanée', 'image', vao_img( 'maquette/form-candidature.jpg' ) ),
		),
		$dests,
		array(

			// Écran de chargement.
			'loading_texte'    => array( 'vao_chargement', 'Texte', 'text', 'Nos produits' ),
			'loading_fond'     => array( 'vao_chargement', 'Image de fond', 'image', vao_img( 'Render element 02/Web project Element 03_002 BG laoding.png' ) ),
			'loading_logo'     => array( 'vao_chargement', 'Logo (en haut)', 'image', vao_img( 'Render element 02/Web project Element 03_001 20ans.png' ) ),
			'loading_produits' => array( 'vao_chargement', 'Image des produits (en bas)', 'image', vao_img( 'Render element 02/Web project Element 03_003 PRODUITS.png' ) ),
		)
	);
}

/**
 * Liste d'adresses e-mail séparées par des virgules : ne garde que les adresses valides.
 */
function vao_sanitize_emails( $value ) {
	$emails = array_filter( array_map( 'sanitize_email', explode( ',', (string) $value ) ), 'is_email' );
	return implode( ', ', $emails );
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
		'emails'   => 'vao_sanitize_emails',
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
					'type'    => 'emails' === $type ? 'text' : $type,
				)
			);
		}
	}
}
add_action( 'customize_register', 'vao_customize_register' );
