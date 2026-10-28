<?php
/**
 * Formulaires : envoi par e-mail via admin-post.php.
 *
 * Les champs sont envoyés sous la forme name="vao[Libellé français]" (le libellé sert d'intitulé dans l'e-mail,
 * il reste en français quelle que soit la langue affichée). Pièces jointes : name="vao_file[]".
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Formulaires du site : identifiant => array( sujet, slug de la page, ancre, destinataires par défaut, champs obligatoires ).
 * Un slug vide renvoie vers la page d'où le formulaire a été envoyé (newsletter du pied de page).
 */
function vao_forms() {
	return array(
		'distributeur' => array( 'Devenir distributeur', 'nous-contacter', 'distributeur', 'grandcompte@vao.mg, contact@vao.mg, direction@vao.mg', array( 'Nom complet ou Raison sociale', 'E-mail', 'Téléphone' ) ),
		'information'  => array( "Demande d'informations", 'nous-contacter', 'information', 'contact@vao.mg', array( 'Nom & Prénom du contact', 'E-mail' ) ),
		'candidature'  => array( 'Candidature', 'nous-contacter', 'candidature', 'rh@vao.mg, contact@vao.mg, direction@vao.mg', array( 'Nom', 'Prénoms', 'E-mail', 'Téléphone' ) ),
		'newsletter'   => array( 'Inscription à la newsletter', '', 'newsletter', 'contact@vao.mg', array( 'Nom', 'E-mail', 'Consentement' ) ),
	);
}

/**
 * Adresses qui reçoivent un formulaire (Personnaliser > Thème VAO > Formulaires, sinon e-mail admin).
 *
 * @return string[]
 */
function vao_form_recipients( $type ) {
	$emails = array_filter( array_map( 'sanitize_email', explode( ',', (string) vao_mod( 'dest_' . $type ) ) ), 'is_email' );
	if ( ! $emails ) {
		$emails = array( get_option( 'admin_email' ) );
	}
	return apply_filters( 'vao_form_recipients', array_values( $emails ), $type );
}

/**
 * Champs cachés à placer dans chaque <form>.
 */
function vao_form_fields( $type ) {
	echo '<input type="hidden" name="action" value="vao_form">';
	echo '<input type="hidden" name="vao_form" value="' . esc_attr( $type ) . '">';
	echo '<input type="hidden" name="vao_lang" value="' . esc_attr( vao_lang() ) . '">';
	echo '<input type="hidden" name="vao_back" value="' . esc_url( is_singular() ? get_permalink() : home_url( '/' ) ) . '">';
	wp_nonce_field( 'vao_form_' . $type, '_vao_nonce' );
	// Piège anti-robots : doit rester vide.
	echo '<input type="text" name="vao_website" value="" tabindex="-1" autocomplete="off" class="visually-hidden" aria-hidden="true">';
}

/**
 * Message affiché après l'envoi d'un formulaire.
 */
function vao_form_notice( $type ) {
	if ( ! isset( $_GET['envoi'], $_GET['form'] ) || $type !== $_GET['form'] ) {
		return;
	}
	$status = sanitize_key( wp_unslash( $_GET['envoi'] ) );
	if ( 'ok' === $status ) {
		echo '<div class="form-notice ok" role="status">' . esc_html( vao_t( 'Merci, votre demande a bien été envoyée.' ) ) . '</div>';
	} elseif ( 'incomplet' === $status ) {
		echo '<div class="form-notice erreur" role="alert">' . esc_html( vao_t( 'Merci de remplir les champs obligatoires avec une adresse e-mail valide.' ) ) . '</div>';
	} else {
		echo '<div class="form-notice erreur" role="alert">'
			. esc_html( sprintf( vao_t( "Une erreur est survenue lors de l'envoi. Merci de réessayer ou de nous écrire à %s." ), vao_mod( 'email' ) ) )
			. '</div>';
	}
}

/**
 * Fichiers envoyés dans vao_file[] : array( array( name, type, tmp_name, error, size ), ... ).
 */
function vao_form_files() {
	if ( empty( $_FILES['vao_file']['name'] ) ) {
		return array();
	}
	$raw   = $_FILES['vao_file']; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput -- vérifié par wp_handle_upload().
	$files = array();
	foreach ( (array) $raw['name'] as $i => $name ) {
		if ( '' === $name ) {
			continue;
		}
		$files[] = array(
			'name'     => $name,
			'type'     => is_array( $raw['type'] ) ? $raw['type'][ $i ] : $raw['type'],
			'tmp_name' => is_array( $raw['tmp_name'] ) ? $raw['tmp_name'][ $i ] : $raw['tmp_name'],
			'error'    => is_array( $raw['error'] ) ? $raw['error'][ $i ] : $raw['error'],
			'size'     => is_array( $raw['size'] ) ? $raw['size'][ $i ] : $raw['size'],
		);
	}
	return $files;
}

function vao_handle_form() {
	$forms = vao_forms();
	$type  = isset( $_POST['vao_form'] ) ? sanitize_key( wp_unslash( $_POST['vao_form'] ) ) : '';
	$nonce = isset( $_POST['_vao_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['_vao_nonce'] ) ) : '';

	if ( ! isset( $forms[ $type ] ) || ! wp_verify_nonce( $nonce, 'vao_form_' . $type ) ) {
		wp_die( 'Requête invalide.', '', array( 'response' => 400 ) );
	}
	list( $subject, $slug, $fragment, , $required ) = $forms[ $type ];
	$lang = isset( $_POST['vao_lang'] ) ? sanitize_key( wp_unslash( $_POST['vao_lang'] ) ) : '';
	if ( $slug ) {
		$back = vao_url( $slug, '', $lang );
	} else {
		$back = isset( $_POST['vao_back'] ) ? esc_url_raw( wp_unslash( $_POST['vao_back'] ) ) : '';
		$back = wp_validate_redirect( remove_query_arg( array( 'envoi', 'form' ), $back ), home_url( '/' ) );
	}
	$redirect = add_query_arg( array( 'form' => $type ), $back );
	$anchor   = '#' . $fragment;

	// Robot détecté : on fait comme si tout s'était bien passé.
	if ( ! empty( $_POST['vao_website'] ) ) {
		wp_safe_redirect( add_query_arg( 'envoi', 'ok', $redirect ) . $anchor );
		exit;
	}

	$fields = isset( $_POST['vao'] ) && is_array( $_POST['vao'] ) ? wp_unslash( $_POST['vao'] ) : array();
	$values = array();
	foreach ( $fields as $label => $value ) {
		$label = sanitize_text_field( $label );
		$value = is_array( $value )
			? implode( ', ', array_filter( array_map( 'sanitize_text_field', $value ) ) )
			: sanitize_textarea_field( $value );
		if ( '' !== $label && '' !== $value ) {
			$values[ $label ] = $value;
		}
	}

	$reply_to = isset( $values['E-mail'] ) ? sanitize_email( $values['E-mail'] ) : '';
	$missing  = array_diff( $required, array_keys( $values ) );
	if ( $missing || ! is_email( $reply_to ) ) {
		wp_safe_redirect( add_query_arg( 'envoi', 'incomplet', $redirect ) . $anchor );
		exit;
	}

	$lines = array();
	foreach ( $values as $label => $value ) {
		$lines[] = $label . ' : ' . $value;
	}

	$attachments = array();
	$files       = vao_form_files();
	if ( $files ) {
		require_once ABSPATH . 'wp-admin/includes/file.php';
	}
	foreach ( array_slice( $files, 0, 5 ) as $file ) {
		if ( $file['size'] > 5 * MB_IN_BYTES ) {
			$lines[] = 'Fichier joint refusé (plus de 5 Mo) : ' . sanitize_file_name( $file['name'] );
			continue;
		}
		$upload = wp_handle_upload(
			$file,
			array(
				'test_form' => false,
				'mimes'     => array(
					'pdf'      => 'application/pdf',
					'jpg|jpeg' => 'image/jpeg',
					'png'      => 'image/png',
					'doc'      => 'application/msword',
					'docx'     => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
				),
			)
		);
		if ( empty( $upload['error'] ) ) {
			$attachments[] = $upload['file'];
		} else {
			$lines[] = 'Fichier joint refusé : ' . $upload['error'];
		}
	}

	if ( $lang ) {
		$lines[] = 'Langue du formulaire : ' . strtoupper( $lang );
	}

	$sent = wp_mail( vao_form_recipients( $type ), '[VAO] ' . $subject, implode( "\n", $lines ), array( 'Reply-To: ' . $reply_to ), $attachments );

	foreach ( $attachments as $file ) {
		wp_delete_file( $file );
	}

	wp_safe_redirect( add_query_arg( 'envoi', $sent ? 'ok' : 'erreur', $redirect ) . $anchor );
	exit;
}
add_action( 'admin_post_nopriv_vao_form', 'vao_handle_form' );
add_action( 'admin_post_vao_form', 'vao_handle_form' );
