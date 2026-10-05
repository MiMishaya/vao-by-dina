<?php
/**
 * Formulaires : envoi par e-mail via admin-post.php.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Formulaires du site : identifiant => array( sujet, slug de la page, ancre ).
 */
function vao_forms() {
	return array(
		'revendeur'   => array( 'Devenir revendeur', 'devenir-revendeur', '' ),
		'echantillon' => array( "Demande d'échantillon", 'formulaires', 'echantillon' ),
		'candidature' => array( 'Candidature spontanée', 'formulaires', 'candidature' ),
	);
}

/**
 * Adresse qui reçoit les formulaires (Personnaliser > Thème VAO > Formulaires, sinon e-mail admin).
 */
function vao_form_recipient() {
	$email = vao_mod( 'form_email' );
	return apply_filters( 'vao_form_recipient', $email ? $email : get_option( 'admin_email' ) );
}

/**
 * Champs cachés à placer dans chaque <form>.
 */
function vao_form_fields( $type ) {
	echo '<input type="hidden" name="action" value="vao_form">';
	echo '<input type="hidden" name="vao_form" value="' . esc_attr( $type ) . '">';
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
	if ( 'ok' === $_GET['envoi'] ) {
		echo '<div class="form-notice ok" role="status">Merci, votre demande a bien été envoyée.</div>';
	} else {
		printf(
			'<div class="form-notice erreur" role="alert">Une erreur est survenue lors de l\'envoi. Merci de réessayer ou de nous écrire à %s.</div>',
			esc_html( vao_mod( 'email' ) )
		);
	}
}

function vao_handle_form() {
	$forms = vao_forms();
	$type  = isset( $_POST['vao_form'] ) ? sanitize_key( wp_unslash( $_POST['vao_form'] ) ) : '';
	$nonce = isset( $_POST['_vao_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['_vao_nonce'] ) ) : '';

	if ( ! isset( $forms[ $type ] ) || ! wp_verify_nonce( $nonce, 'vao_form_' . $type ) ) {
		wp_die( 'Requête invalide.', '', array( 'response' => 400 ) );
	}
	list( $subject, $slug, $fragment ) = $forms[ $type ];
	$redirect = add_query_arg( array( 'form' => $type ), vao_url( $slug ) );
	$anchor   = $fragment ? '#' . $fragment : '';

	// Robot détecté : on fait comme si tout s'était bien passé.
	if ( ! empty( $_POST['vao_website'] ) ) {
		wp_safe_redirect( add_query_arg( 'envoi', 'ok', $redirect ) . $anchor );
		exit;
	}

	$fields = isset( $_POST['vao'] ) && is_array( $_POST['vao'] ) ? wp_unslash( $_POST['vao'] ) : array();
	$lines  = array();
	foreach ( $fields as $label => $value ) {
		$value = is_array( $value )
			? implode( ', ', array_map( 'sanitize_text_field', $value ) )
			: sanitize_textarea_field( $value );
		if ( '' !== $value ) {
			$lines[] = sanitize_text_field( $label ) . ' : ' . $value;
		}
	}

	$attachments = array();
	if ( ! empty( $_FILES['vao_file']['name'] ) ) {
		require_once ABSPATH . 'wp-admin/includes/file.php';
		$upload = wp_handle_upload(
			$_FILES['vao_file'],
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

	$reply_to = isset( $fields['E-mail'] ) ? sanitize_email( $fields['E-mail'] ) : '';
	$headers  = $reply_to ? array( 'Reply-To: ' . $reply_to ) : array();

	$sent = wp_mail( vao_form_recipient(), '[VAO] ' . $subject, implode( "\n", $lines ), $headers, $attachments );

	foreach ( $attachments as $file ) {
		wp_delete_file( $file );
	}

	wp_safe_redirect( add_query_arg( 'envoi', $sent ? 'ok' : 'erreur', $redirect ) . $anchor );
	exit;
}
add_action( 'admin_post_nopriv_vao_form', 'vao_handle_form' );
add_action( 'admin_post_vao_form', 'vao_handle_form' );
