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
	$email  = vao_mod( 'email' );
	if ( 'ok' === $status ) {
		echo '<div class="form-notice ok" role="status">' . esc_html( vao_t( 'Merci, votre demande a bien été envoyée.' ) ) . '</div>';
	} elseif ( 'incomplet' === $status ) {
		echo '<div class="form-notice erreur" role="alert">' . esc_html( vao_t( 'Merci de remplir les champs obligatoires avec une adresse e-mail valide.' ) ) . '</div>';
	} elseif ( 'lourd' === $status ) {
		echo '<div class="form-notice erreur" role="alert">' . esc_html( vao_t( 'Les fichiers joints sont trop lourds (8 Mo au total au maximum). Merci de réduire leur taille et de renvoyer le formulaire.' ) ) . '</div>';
	} elseif ( $email ) {
		echo '<div class="form-notice erreur" role="alert">'
			. esc_html( sprintf( vao_t( "Une erreur est survenue lors de l'envoi. Merci de réessayer ou de nous écrire à %s." ), $email ) )
			. '</div>';
	} else {
		echo '<div class="form-notice erreur" role="alert">' . esc_html( vao_t( "Une erreur est survenue lors de l'envoi. Merci de réessayer." ) ) . '</div>';
	}
}

/**
 * Adresse d'envoi d'un formulaire. Le type et la langue sont aussi dans l'URL : si les fichiers dépassent
 * la limite du serveur (post_max_size), PHP vide $_POST et on peut quand même répondre au bon formulaire.
 */
function vao_form_action( $type ) {
	return add_query_arg(
		array(
			'action'   => 'vao_form',
			'vao_form' => $type,
			'vao_lang' => vao_lang(),
		),
		admin_url( 'admin-post.php' )
	);
}

/**
 * Dossier des pièces jointes reçues : hors des dossiers datés de la médiathèque, noms de fichiers
 * imprévisibles (le serveur peut ignorer .htaccess, ex. nginx) et listing interdit.
 */
function vao_form_upload_dir( $dirs ) {
	$dirs['subdir'] = '/vao-demandes';
	$dirs['path']   = $dirs['basedir'] . $dirs['subdir'];
	$dirs['url']    = $dirs['baseurl'] . $dirs['subdir'];
	if ( ! is_dir( $dirs['path'] ) && wp_mkdir_p( $dirs['path'] ) ) {
		file_put_contents( $dirs['path'] . '/.htaccess', "Require all denied\nDeny from all\n" ); // phpcs:ignore WordPress.WP.AlternativeFunctions
		file_put_contents( $dirs['path'] . '/index.php', "<?php\n// Silence.\n" ); // phpcs:ignore WordPress.WP.AlternativeFunctions
	}
	return $dirs;
}

function vao_form_unique_filename( $dir, $name, $ext ) {
	return wp_generate_password( 24, false ) . '-' . sanitize_file_name( pathinfo( $name, PATHINFO_FILENAME ) ) . $ext;
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

/**
 * Limite le nombre d'envois par adresse IP (10 par tranche de 10 minutes),
 * pour éviter qu'un robot qui passe le piège ne noie les boîtes de réception.
 */
function vao_form_rate_limited() {
	$ip    = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
	$key   = 'vao_form_' . md5( $ip );
	$count = (int) get_transient( $key );
	if ( $count >= 10 ) {
		return true;
	}
	set_transient( $key, $count + 1, 10 * MINUTE_IN_SECONDS );
	return false;
}

function vao_handle_form() {
	$forms = vao_forms();
	// phpcs:disable WordPress.Security.NonceVerification -- type et langue seulement, le nonce est vérifié plus bas.
	$type  = isset( $_REQUEST['vao_form'] ) ? sanitize_key( wp_unslash( $_REQUEST['vao_form'] ) ) : '';
	$lang  = isset( $_REQUEST['vao_lang'] ) ? sanitize_key( wp_unslash( $_REQUEST['vao_lang'] ) ) : '';
	// phpcs:enable
	$nonce = isset( $_POST['_vao_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['_vao_nonce'] ) ) : '';

	if ( ! isset( $forms[ $type ] ) ) {
		wp_die( 'Requête invalide.', '', array( 'response' => 400 ) );
	}
	list( $subject, $slug, $fragment, , $required ) = $forms[ $type ];

	// Envoi plus lourd que post_max_size : PHP a vidé $_POST et $_FILES.
	$too_big = empty( $_POST ) && ! empty( $_SERVER['CONTENT_LENGTH'] );
	// Jeton expiré (page restée en cache plus de 12 h) : bloquant seulement pour un utilisateur connecté.
	// Pour un visiteur, le jeton n'apporte rien (pas de session à protéger) et le piège anti-robots
	// + la limite par IP suffisent ; refuser ferait perdre sa saisie au visiteur.
	if ( ! $too_big && is_user_logged_in() && ! wp_verify_nonce( $nonce, 'vao_form_' . $type ) ) {
		wp_die( 'Requête invalide.', '', array( 'response' => 400 ) );
	}
	if ( $slug ) {
		$back = vao_url( $slug, '', $lang );
	} else {
		$back = isset( $_POST['vao_back'] ) ? esc_url_raw( wp_unslash( $_POST['vao_back'] ) ) : '';
		$back = wp_validate_redirect( remove_query_arg( array( 'envoi', 'form' ), $back ), home_url( '/' ) );
	}
	$redirect = add_query_arg( array( 'form' => $type ), $back );
	$anchor   = '#' . $fragment;

	if ( $too_big ) {
		wp_safe_redirect( add_query_arg( 'envoi', 'lourd', $redirect ) . $anchor );
		exit;
	}

	// Robot détecté : on fait comme si tout s'était bien passé.
	if ( ! empty( $_POST['vao_website'] ) ) {
		wp_safe_redirect( add_query_arg( 'envoi', 'ok', $redirect ) . $anchor );
		exit;
	}

	// Nombre et longueur des champs bornés : les libellés et valeurs viennent du navigateur.
	$fields = isset( $_POST['vao'] ) && is_array( $_POST['vao'] ) ? array_slice( wp_unslash( $_POST['vao'] ), 0, 30, true ) : array();
	$values = array();
	foreach ( $fields as $label => $value ) {
		$label = mb_substr( sanitize_text_field( $label ), 0, 100 );
		$value = is_array( $value )
			? implode( ', ', array_filter( array_map( 'sanitize_text_field', array_slice( $value, 0, 20 ) ) ) )
			: sanitize_textarea_field( $value );
		$value = mb_substr( $value, 0, 5000 );
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

	// Après la validation : un formulaire incomplet ne consomme pas le quota du visiteur.
	if ( vao_form_rate_limited() ) {
		wp_safe_redirect( add_query_arg( 'envoi', 'erreur', $redirect ) . $anchor );
		exit;
	}

	$lines = array();
	foreach ( $values as $label => $value ) {
		$lines[] = $label . ' : ' . $value;
	}

	$attachments = array();
	$file_names  = array();
	$files       = vao_form_files();
	if ( $files ) {
		require_once ABSPATH . 'wp-admin/includes/file.php';
		add_filter( 'upload_dir', 'vao_form_upload_dir' );
	}
	foreach ( array_slice( $files, 0, 5 ) as $file ) {
		if ( $file['size'] > 5 * MB_IN_BYTES ) {
			$lines[] = 'Fichier joint refusé (plus de 5 Mo) : ' . sanitize_file_name( $file['name'] );
			continue;
		}
		$upload = wp_handle_upload(
			$file,
			array(
				'test_form'                => false,
				'unique_filename_callback' => 'vao_form_unique_filename',
				'mimes'                    => array(
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
			$file_names[]  = sanitize_file_name( $file['name'] );
		} else {
			// Message de WordPress dans la langue de l'admin (souvent l'anglais) : l'e-mail reste en français.
			$lines[] = 'Fichier joint refusé (type non autorisé ou fichier invalide) : ' . sanitize_file_name( $file['name'] );
		}
	}
	remove_filter( 'upload_dir', 'vao_form_upload_dir' );

	if ( $lang ) {
		$lines[] = 'Langue du formulaire : ' . strtoupper( $lang );
	}

	$sent = wp_mail( vao_form_recipients( $type ), '[VAO] ' . $subject, implode( "\n", $lines ), array( 'Reply-To: ' . $reply_to ), $attachments );

	$saved = vao_save_demande( $type, $values, $lang, $sent, $file_names );
	$saved = $saved && ! is_wp_error( $saved ) ? (int) $saved : 0;

	if ( ! $sent && $saved && $attachments ) {
		// E-mail en échec : les fichiers (CV…) restent sur le serveur, téléchargeables depuis la demande
		// (menu « Demandes »), et sont supprimés avec elle.
		update_post_meta( $saved, '_vao_files_kept', wp_slash( $attachments ) ); // update_post_meta() retire les antislashs (chemins Windows).
	} else {
		foreach ( $attachments as $file ) {
			wp_delete_file( $file );
		}
	}
	// Demande enregistrée : le visiteur n'a pas à la renvoyer même si l'e-mail a échoué.
	$sent = $sent || $saved;

	wp_safe_redirect( add_query_arg( 'envoi', $sent ? 'ok' : 'erreur', $redirect ) . $anchor );
	exit;
}
add_action( 'admin_post_nopriv_vao_form', 'vao_handle_form' );
add_action( 'admin_post_vao_form', 'vao_handle_form' );
