<?php
/**
 * Traduction malagasy depuis l'admin, à côté du texte français :
 * - Personnaliser : chaque texte a son champ « en malagasy » juste en dessous ;
 * - Gammes : nom et description en malagasy dans la fiche de la gamme ;
 * - Collaborateurs et actions RSE : champs malagasy dans l'encadré « Informations ».
 * Le malagasy saisi ici prime sur Langues > Traductions des chaînes et sur le dictionnaire du thème,
 * et reste en place si le texte français est modifié ensuite.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Traduction malagasy actuellement affichée pour un texte français (Polylang, sinon dictionnaire du thème),
 * pour pré-remplir les champs malagasy de l'admin. "" si aucune.
 */
function vao_mg_current( $fr ) {
	$fr = (string) $fr;
	if ( '' === $fr ) {
		return '';
	}
	$mg = vao_pll_mg_language();
	if ( $mg && function_exists( 'pll_translate_string' ) ) {
		$translated = pll_translate_string( $fr, $mg->slug );
		if ( $translated !== $fr ) {
			return $translated;
		}
	}
	$dictionary = vao_i18n_flat();
	return isset( $dictionary[ $fr ] ) && $dictionary[ $fr ] !== $fr ? $dictionary[ $fr ] : '';
}

/**
 * Champs malagasy des fiches : type => array( clé du champ malagasy => source française ).
 * Source : clé de méta, ou « post_title » / « post_content ».
 */
function vao_mg_post_fields() {
	return array(
		'vao_collaborateur' => array(
			'_vao_poste_mg'      => '_vao_poste',
			'_vao_anciennete_mg' => '_vao_anciennete',
			'_vao_texte_mg'      => '_vao_texte',
		),
		'vao_rse'           => array(
			'_vao_titre_mg' => 'post_title',
			'_vao_texte_mg' => 'post_content',
		),
	);
}

/**
 * Texte français d'une fiche correspondant à un champ malagasy.
 */
function vao_mg_source( $post, $source ) {
	return in_array( $source, array( 'post_title', 'post_content' ), true ) ? (string) $post->$source : (string) get_post_meta( $post->ID, $source, true );
}

/**
 * Toutes les traductions saisies dans l'admin : array( texte français => malagasy ).
 */
function vao_mg_overrides() {
	static $map = null;
	if ( null !== $map ) {
		return $map;
	}
	$map = array();

	// Personnaliser.
	foreach ( vao_customizer_fields() as $key => $field ) {
		if ( vao_mod_is_translatable( $key ) ) {
			$mg = (string) get_theme_mod( 'vao_' . $key . '_mgtxt', '' );
			if ( '' !== $mg ) {
				$map[ (string) get_theme_mod( 'vao_' . $key, $field[3] ) ] = $mg;
			}
		}
	}

	// Gammes.
	foreach ( vao_gammes( false ) as $term ) {
		foreach ( array( 'name' => 'vao_nom_mg', 'description' => 'vao_description_mg' ) as $prop => $meta ) {
			$mg = (string) get_term_meta( $term->term_id, $meta, true );
			if ( '' !== $mg && '' !== (string) $term->$prop ) {
				$map[ $term->$prop ] = $mg;
			}
		}
	}

	// Collaborateurs, actions RSE.
	foreach ( vao_mg_post_fields() as $type => $fields ) {
		foreach ( get_posts( array( 'post_type' => $type, 'numberposts' => -1, 'lang' => '' ) ) as $post ) {
			foreach ( $fields as $mg_key => $source ) {
				$mg = (string) get_post_meta( $post->ID, $mg_key, true );
				$fr = vao_mg_source( $post, $source );
				if ( '' !== $mg && '' !== $fr ) {
					$map[ $fr ] = $mg;
				}
			}
		}
	}
	return $map;
}

/* -------------------------------------------------------------------------
 * Personnaliser : un champ « en malagasy » sous chaque texte
 * ---------------------------------------------------------------------- */

function vao_customize_register_mg( $wp_customize ) {
	if ( ! vao_pll_mg_language() ) {
		return;
	}
	foreach ( vao_customizer_fields() as $key => $field ) {
		if ( ! vao_mod_is_translatable( $key ) ) {
			continue;
		}
		list( $section, $label, $type, $default ) = $field;
		$fr      = $wp_customize->get_control( 'vao_' . $key );
		$setting = 'vao_' . $key . '_mgtxt';
		$wp_customize->add_setting(
			$setting,
			array(
				// Pré-rempli avec le malagasy affiché aujourd'hui : rien n'est enregistré tant qu'il n'est pas modifié.
				'default'           => vao_mg_current( get_theme_mod( 'vao_' . $key, $default ) ),
				'sanitize_callback' => 'textarea' === $type ? 'sanitize_textarea_field' : 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			$setting,
			array(
				'label'       => '↳ ' . $label . ' — en malagasy',
				'description' => 'Vide = le texte français est affiché sur le site malagasy.',
				'section'     => $section,
				'type'        => $type,
				'priority'    => $fr ? $fr->priority + 1 : 10,
			)
		);
	}
}
add_action( 'customize_register', 'vao_customize_register_mg', 20 );

/* -------------------------------------------------------------------------
 * Gammes : nom et description en malagasy
 * ---------------------------------------------------------------------- */

function vao_gamme_mg_fields( $term ) {
	if ( ! vao_pll_mg_language() ) {
		return;
	}
	$name = get_term_meta( $term->term_id, 'vao_nom_mg', true );
	$desc = get_term_meta( $term->term_id, 'vao_description_mg', true );
	$name = '' !== (string) $name ? $name : vao_mg_current( $term->name );
	$desc = '' !== (string) $desc ? $desc : vao_mg_current( $term->description );
	?>
	<tr class="form-field">
		<th scope="row"><label for="vao_nom_mg">Nom en malagasy</label></th>
		<td><input type="text" id="vao_nom_mg" name="vao_nom_mg" value="<?php echo esc_attr( $name ); ?>"></td>
	</tr>
	<tr class="form-field">
		<th scope="row"><label for="vao_description_mg">Description en malagasy</label></th>
		<td>
			<textarea id="vao_description_mg" name="vao_description_mg" rows="6"><?php echo esc_textarea( $desc ); ?></textarea>
			<p class="description">Vide = le texte français est affiché sur le site malagasy. Mêmes règles que la description française (une ligne qui finit par « : » ouvre une liste à puces).</p>
		</td>
	</tr>
	<?php
}
add_action( 'vao_gamme_edit_form_fields', 'vao_gamme_mg_fields', 20 );

function vao_gamme_mg_save( $term_id ) {
	if ( ! current_user_can( 'manage_categories' ) || ! isset( $_POST['vao_nom_mg'], $_POST['vao_description_mg'] ) ) {
		return;
	}
	// Le nonce du formulaire de gamme est vérifié par WordPress avant les crochets edited_*.
	update_term_meta( $term_id, 'vao_nom_mg', sanitize_text_field( wp_unslash( $_POST['vao_nom_mg'] ) ) );
	update_term_meta( $term_id, 'vao_description_mg', wp_kses_post( wp_unslash( $_POST['vao_description_mg'] ) ) );
}
add_action( 'edited_vao_gamme', 'vao_gamme_mg_save' );

/* -------------------------------------------------------------------------
 * Fiches traduites avec Polylang (bannière, témoignages, offres) : mode d'emploi
 * ---------------------------------------------------------------------- */

function vao_translation_hint( $post ) {
	$hints = array(
		'vao_banniere'      => "Version malagasy : enregistrez d'abord cette bannière, puis cliquez sur « + » (ou le crayon) à côté du drapeau malagasy dans l'encadré « Langues / Languages » à droite, puis choisissez l'image en malagasy dans la fiche qui s'ouvre. Sans version malagasy, cette image est aussi affichée sur le site malagasy.",
		'vao_temoignage'    => "Version malagasy : les cartes livrées avec le thème ont déjà leur version malagasy, affichée automatiquement. Pour une nouvelle carte (ou pour la remplacer) : cliquez sur « + » (ou le crayon) à côté du drapeau malagasy dans l'encadré « Langues / Languages » à droite, puis choisissez l'image en malagasy dans la fiche qui s'ouvre.",
		'vao_collaborateur' => "Version malagasy : remplissez les champs « en malagasy » de l'encadré « Informations » ci-dessous.",
		'vao_rse'           => "Version malagasy : remplissez les champs « en malagasy » de l'encadré « Informations » sous le texte.",
	);
	if ( isset( $hints[ $post->post_type ] ) && vao_pll_mg_language() ) {
		echo '<div class="notice notice-info inline" style="margin:12px 0"><p>' . esc_html( $hints[ $post->post_type ] ) . '</p></div>';
	}
}
add_action( 'edit_form_after_title', 'vao_translation_hint' );
