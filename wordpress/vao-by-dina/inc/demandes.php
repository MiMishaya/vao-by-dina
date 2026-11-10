<?php
/**
 * Historique des demandes envoyées par les formulaires (menu « Demandes » de l'admin).
 *
 * Stockage dans wp_posts / wp_postmeta (type vao_demande), sans table dédiée.
 * Méta : _vao_type, _vao_fields (libellé => valeur), _vao_lang, _vao_mail (ok|erreur), _vao_files (noms des fichiers joints).
 * Les pièces jointes ne sont pas conservées (seulement leurs noms) : données personnelles (CV) déjà transmises par e-mail.
 * Exception : si l'e-mail a échoué, elles sont gardées (_vao_files_kept, uploads/vao-demandes) et supprimées avec la demande.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Durée de conservation en mois (données personnelles : pas de conservation illimitée).
 */
function vao_demandes_retention_months() {
	return max( 1, (int) apply_filters( 'vao_demandes_retention_months', 12 ) );
}

function vao_register_demandes() {
	// Réservé aux administrateurs : les demandes contiennent des données personnelles.
	$cap = 'manage_options';
	register_post_type(
		'vao_demande',
		array(
			'labels'              => array(
				'name'               => 'Demandes',
				'singular_name'      => 'Demande',
				'menu_name'          => 'Demandes',
				'all_items'          => 'Toutes les demandes',
				'edit_item'          => 'Demande',
				'search_items'       => 'Rechercher une demande',
				'not_found'          => 'Aucune demande.',
				'not_found_in_trash' => 'Aucune demande dans la corbeille.',
			),
			'public'              => false,
			'publicly_queryable'  => false,
			'exclude_from_search' => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => false,
			'show_in_rest'        => false,
			'menu_position'       => 25,
			'menu_icon'           => 'dashicons-email-alt',
			'supports'            => false,
			'rewrite'             => false,
			'query_var'           => false,
			'map_meta_cap'        => false,
			'capabilities'        => array(
				'edit_post'              => $cap,
				'read_post'              => $cap,
				'delete_post'            => $cap,
				'edit_posts'             => $cap,
				'edit_others_posts'      => $cap,
				'edit_published_posts'   => $cap,
				'delete_posts'           => $cap,
				'delete_others_posts'    => $cap,
				'delete_published_posts' => $cap,
				'publish_posts'          => $cap,
				'read_private_posts'     => $cap,
				// Les demandes ne viennent que des formulaires du site.
				'create_posts'           => 'do_not_allow',
			),
		)
	);
}
add_action( 'init', 'vao_register_demandes' );

/**
 * Enregistre une demande. Appelé après l'envoi de l'e-mail, qu'il ait réussi ou non :
 * la demande reste consultable même si le SMTP est en panne.
 *
 * @param string   $type   Identifiant du formulaire (clé de vao_forms()).
 * @param string[] $values Libellé => valeur, déjà nettoyés.
 * @param string   $lang   Langue du formulaire.
 * @param bool     $sent   Résultat de wp_mail().
 * @param string[] $files  Noms des fichiers joints acceptés.
 */
function vao_save_demande( $type, $values, $lang, $sent, $files = array() ) {
	$name  = array();
	$keys  = array( 'Nom complet ou Raison sociale', 'Nom & Prénom du contact', 'Nom', 'Prénoms' );
	foreach ( $keys as $key ) {
		if ( ! empty( $values[ $key ] ) ) {
			$name[] = $values[ $key ];
		}
	}
	$email = isset( $values['E-mail'] ) ? $values['E-mail'] : '';
	$title = $name ? implode( ' ', $name ) : $email;

	// wp_insert_post() et update_post_meta() retirent les antislashs : on les protège.
	return wp_insert_post(
		wp_slash(
			array(
				'post_type'   => 'vao_demande',
				'post_status' => 'publish',
				'post_title'  => mb_substr( $title, 0, 200 ),
				'meta_input'  => array(
					'_vao_type'   => $type,
					'_vao_fields' => $values,
					'_vao_lang'   => $lang,
					'_vao_mail'   => $sent ? 'ok' : 'erreur',
					'_vao_files'  => array_values( $files ),
				),
			)
		),
		true
	);
}

/* -------------------------------------------------------------------------
 * Liste des demandes
 * ---------------------------------------------------------------------- */

function vao_demande_type_label( $type ) {
	$forms = vao_forms();
	return isset( $forms[ $type ] ) ? $forms[ $type ][0] : $type;
}

function vao_demandes_columns() {
	return array(
		'cb'       => '<input type="checkbox">',
		'title'    => 'Nom',
		'vao_type' => 'Formulaire',
		'vao_mail' => 'E-mail du contact',
		'vao_lang' => 'Langue',
		'vao_sent' => 'Envoi',
		'date'     => 'Date',
	);
}
add_filter( 'manage_vao_demande_posts_columns', 'vao_demandes_columns' );

function vao_demandes_column( $column, $post_id ) {
	switch ( $column ) {
		case 'vao_type':
			echo esc_html( vao_demande_type_label( get_post_meta( $post_id, '_vao_type', true ) ) );
			break;
		case 'vao_mail':
			$fields = (array) get_post_meta( $post_id, '_vao_fields', true );
			if ( ! empty( $fields['E-mail'] ) ) {
				echo '<a href="mailto:' . esc_attr( $fields['E-mail'] ) . '">' . esc_html( $fields['E-mail'] ) . '</a>';
			}
			break;
		case 'vao_lang':
			echo esc_html( strtoupper( (string) get_post_meta( $post_id, '_vao_lang', true ) ) );
			break;
		case 'vao_sent':
			if ( 'ok' === get_post_meta( $post_id, '_vao_mail', true ) ) {
				echo 'E-mail envoyé';
			} else {
				echo '<strong style="color:#b32d2e">E-mail non envoyé</strong>';
			}
			break;
	}
}
add_action( 'manage_vao_demande_posts_custom_column', 'vao_demandes_column', 10, 2 );

/**
 * Lignes de la liste : pas de modification rapide, « Modifier » devient « Voir ».
 */
function vao_demandes_row_actions( $actions, $post ) {
	if ( 'vao_demande' !== $post->post_type ) {
		return $actions;
	}
	unset( $actions['inline hide-if-no-js'] );
	if ( isset( $actions['edit'] ) ) {
		$actions['edit'] = '<a href="' . esc_url( get_edit_post_link( $post->ID ) ) . '">Voir</a>';
	}
	return $actions;
}
add_filter( 'post_row_actions', 'vao_demandes_row_actions', 10, 2 );

function vao_demandes_bulk_actions( $actions ) {
	unset( $actions['edit'] );
	return $actions;
}
add_filter( 'bulk_actions-edit-vao_demande', 'vao_demandes_bulk_actions' );

function vao_demandes_current_type() {
	$type = isset( $_GET['vao_type'] ) ? sanitize_key( wp_unslash( $_GET['vao_type'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification -- simple filtre d'affichage.
	return isset( vao_forms()[ $type ] ) ? $type : '';
}

/**
 * Filtre par formulaire + bouton d'export CSV au-dessus de la liste.
 */
function vao_demandes_filters( $post_type, $which ) {
	if ( 'vao_demande' !== $post_type || 'top' !== $which ) {
		return;
	}
	$current = vao_demandes_current_type();
	echo '<select name="vao_type"><option value="">Tous les formulaires</option>';
	foreach ( vao_forms() as $type => $form ) {
		echo '<option value="' . esc_attr( $type ) . '"' . selected( $current, $type, false ) . '>' . esc_html( $form[0] ) . '</option>';
	}
	echo '</select>';

	$export = wp_nonce_url(
		add_query_arg(
			array(
				'action'   => 'vao_demandes_csv',
				'vao_type' => $current,
			),
			admin_url( 'admin-post.php' )
		),
		'vao_demandes_csv'
	);
	echo '<a class="button" style="margin-left:4px" href="' . esc_url( $export ) . '">Exporter en CSV</a>';
}
add_action( 'restrict_manage_posts', 'vao_demandes_filters', 10, 2 );

function vao_demandes_filter_query( $query ) {
	if ( ! is_admin() || ! $query->is_main_query() || 'vao_demande' !== $query->get( 'post_type' ) ) {
		return;
	}
	$type = vao_demandes_current_type();
	if ( $type ) {
		$query->set(
			'meta_query',
			array(
				array(
					'key'   => '_vao_type',
					'value' => $type,
				),
			)
		);
	}
}
add_action( 'pre_get_posts', 'vao_demandes_filter_query' );

/* -------------------------------------------------------------------------
 * Fiche d'une demande (lecture seule)
 * ---------------------------------------------------------------------- */

function vao_demande_meta_boxes( $post ) {
	// Pas d'enregistrement possible depuis la fiche : la demande reste telle qu'envoyée.
	remove_meta_box( 'submitdiv', 'vao_demande', 'side' );
	remove_meta_box( 'slugdiv', 'vao_demande', 'normal' );
	add_meta_box( 'vao_demande_detail', 'Détail de la demande', 'vao_demande_detail_box', 'vao_demande', 'normal', 'high' );
}
add_action( 'add_meta_boxes_vao_demande', 'vao_demande_meta_boxes' );

function vao_demande_detail_box( $post ) {
	$fields = (array) get_post_meta( $post->ID, '_vao_fields', true );
	$files  = (array) get_post_meta( $post->ID, '_vao_files', true );
	$rows   = array(
		'Formulaire'           => vao_demande_type_label( get_post_meta( $post->ID, '_vao_type', true ) ),
		'Reçue le'             => get_the_date( 'd/m/Y H:i', $post ),
		'Langue du formulaire' => strtoupper( (string) get_post_meta( $post->ID, '_vao_lang', true ) ),
		'E-mail'               => 'ok' === get_post_meta( $post->ID, '_vao_mail', true ) ? 'envoyé aux destinataires' : 'NON envoyé (vérifier la configuration SMTP)',
	);

	echo '<table class="widefat striped"><tbody>';
	foreach ( $rows as $label => $value ) {
		echo '<tr><th style="width:220px">' . esc_html( $label ) . '</th><td>' . esc_html( $value ) . '</td></tr>';
	}
	foreach ( $fields as $label => $value ) {
		echo '<tr><th>' . esc_html( $label ) . '</th><td>' . nl2br( esc_html( $value ) ) . '</td></tr>';
	}
	$kept = (array) get_post_meta( $post->ID, '_vao_files_kept', true );
	if ( array_filter( $kept ) ) {
		// E-mail en échec : les fichiers ont été gardés sur le serveur.
		echo '<tr><th>Fichiers joints (e-mail non envoyé : fichiers conservés)</th><td>';
		foreach ( array_values( array_filter( $kept ) ) as $n => $path ) {
			$url = wp_nonce_url( admin_url( 'admin-post.php?action=vao_demande_file&id=' . $post->ID . '&n=' . $n ), 'vao_demande_file_' . $post->ID );
			echo '<a class="button" style="margin:0 6px 6px 0" href="' . esc_url( $url ) . '">' . esc_html( isset( $files[ $n ] ) ? $files[ $n ] : basename( $path ) ) . '</a>';
		}
		echo '</td></tr>';
	} elseif ( array_filter( $files ) ) {
		echo '<tr><th>Fichiers joints (transmis par e-mail, non conservés)</th><td>' . esc_html( implode( ', ', $files ) ) . '</td></tr>';
	}
	echo '</tbody></table>';

	echo '<p><a class="button" href="' . esc_url( admin_url( 'edit.php?post_type=vao_demande' ) ) . '">Retour à la liste</a> ';
	$delete = get_delete_post_link( $post->ID );
	if ( $delete ) {
		echo '<a class="button-link-delete" style="margin-left:8px" href="' . esc_url( $delete ) . '">Mettre à la corbeille</a>';
	}
	echo '</p>';
}

/**
 * Téléchargement d'une pièce jointe conservée (administrateurs seulement).
 */
function vao_demande_file() {
	$id = isset( $_GET['id'] ) ? (int) $_GET['id'] : 0;
	$n  = isset( $_GET['n'] ) ? (int) $_GET['n'] : -1;
	if ( ! current_user_can( 'manage_options' ) || ! check_admin_referer( 'vao_demande_file_' . $id ) || 'vao_demande' !== get_post_type( $id ) ) {
		wp_die( 'Accès refusé.', '', array( 'response' => 403 ) );
	}
	$kept  = array_values( array_filter( (array) get_post_meta( $id, '_vao_files_kept', true ) ) );
	$names = (array) get_post_meta( $id, '_vao_files', true );
	$path  = isset( $kept[ $n ] ) ? $kept[ $n ] : '';
	// Uniquement des fichiers du dossier des demandes.
	$base = trailingslashit( wp_get_upload_dir()['basedir'] ) . 'vao-demandes/';
	if ( ! $path || 0 !== strpos( wp_normalize_path( $path ), wp_normalize_path( $base ) ) || ! is_file( $path ) ) {
		wp_die( 'Fichier introuvable.', '', array( 'response' => 404 ) );
	}
	$name = isset( $names[ $n ] ) ? sanitize_file_name( $names[ $n ] ) : basename( $path );
	nocache_headers();
	header( 'Content-Type: application/octet-stream' );
	header( 'Content-Disposition: attachment; filename="' . $name . '"' );
	header( 'Content-Length: ' . filesize( $path ) );
	readfile( $path ); // phpcs:ignore WordPress.WP.AlternativeFunctions
	exit;
}
add_action( 'admin_post_vao_demande_file', 'vao_demande_file' );

/**
 * Suppression d'une demande (corbeille vidée, purge à 12 mois) : ses fichiers conservés aussi.
 */
function vao_demande_delete_files( $post_id ) {
	if ( 'vao_demande' !== get_post_type( $post_id ) ) {
		return;
	}
	foreach ( array_filter( (array) get_post_meta( $post_id, '_vao_files_kept', true ) ) as $path ) {
		if ( is_file( $path ) ) {
			wp_delete_file( $path );
		}
	}
}
add_action( 'before_delete_post', 'vao_demande_delete_files' );

/* -------------------------------------------------------------------------
 * Export CSV
 * ---------------------------------------------------------------------- */

/**
 * Neutralise les formules (=, +, -, @) qu'Excel exécuterait à l'ouverture du fichier.
 */
function vao_csv_cell( $value ) {
	$value = (string) $value;
	return preg_match( '/^[=+\-@\t\r]/', $value ) ? "'" . $value : $value;
}

function vao_demandes_csv() {
	if ( ! current_user_can( 'manage_options' ) || ! check_admin_referer( 'vao_demandes_csv' ) ) {
		wp_die( 'Accès refusé.', '', array( 'response' => 403 ) );
	}
	$type = vao_demandes_current_type();
	$args = array(
		'post_type'      => 'vao_demande',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
		'orderby'        => 'date',
		'order'          => 'DESC',
		'no_found_rows'  => true,
	);
	if ( $type ) {
		$args['meta_query'] = array( array( 'key' => '_vao_type', 'value' => $type ) ); // phpcs:ignore WordPress.DB.SlowDBQuery -- export ponctuel.
	}
	$posts = get_posts( $args );

	// Colonnes = union des libellés de champs, dans l'ordre d'apparition.
	$labels = array();
	$rows   = array();
	foreach ( $posts as $post ) {
		$fields = (array) get_post_meta( $post->ID, '_vao_fields', true );
		$labels = array_unique( array_merge( $labels, array_keys( $fields ) ) );
		$rows[] = array( $post, $fields );
	}

	$filename = 'demandes-' . ( $type ? $type . '-' : '' ) . gmdate( 'Y-m-d' ) . '.csv';
	nocache_headers();
	header( 'Content-Type: text/csv; charset=utf-8' );
	header( 'Content-Disposition: attachment; filename="' . $filename . '"' );

	$out = fopen( 'php://output', 'w' );
	// BOM + point-virgule : ouverture directe dans un Excel français.
	fwrite( $out, "\xEF\xBB\xBF" );
	// Les libellés viennent du navigateur (clés des champs) : neutralisés comme les valeurs.
	fputcsv( $out, array_map( 'vao_csv_cell', array_merge( array( 'Date', 'Formulaire', 'Langue', 'E-mail envoyé' ), $labels, array( 'Fichiers joints' ) ) ), ';', '"', '' );
	foreach ( $rows as $row ) {
		list( $post, $fields ) = $row;
		$line = array(
			get_the_date( 'd/m/Y H:i', $post ),
			vao_demande_type_label( get_post_meta( $post->ID, '_vao_type', true ) ),
			strtoupper( (string) get_post_meta( $post->ID, '_vao_lang', true ) ),
			'ok' === get_post_meta( $post->ID, '_vao_mail', true ) ? 'oui' : 'non',
		);
		foreach ( $labels as $label ) {
			$line[] = isset( $fields[ $label ] ) ? $fields[ $label ] : '';
		}
		$line[] = implode( ', ', (array) get_post_meta( $post->ID, '_vao_files', true ) );
		fputcsv( $out, array_map( 'vao_csv_cell', $line ), ';', '"', '' );
	}
	fclose( $out );
	exit;
}
add_action( 'admin_post_vao_demandes_csv', 'vao_demandes_csv' );

/* -------------------------------------------------------------------------
 * Purge automatique après la durée de conservation
 * ---------------------------------------------------------------------- */

function vao_demandes_schedule_purge() {
	if ( ! wp_next_scheduled( 'vao_demandes_purge' ) ) {
		wp_schedule_event( time() + HOUR_IN_SECONDS, 'daily', 'vao_demandes_purge' );
	}
}
add_action( 'init', 'vao_demandes_schedule_purge' );

function vao_demandes_purge() {
	$ids = get_posts(
		array(
			'post_type'      => 'vao_demande',
			// La corbeille aussi : une demande supprimée ne doit pas survivre au délai.
			'post_status'    => array( 'publish', 'trash' ),
			'posts_per_page' => 500,
			'fields'         => 'ids',
			'no_found_rows'  => true,
			'date_query'     => array(
				array( 'before' => vao_demandes_retention_months() . ' months ago' ),
			),
		)
	);
	foreach ( $ids as $id ) {
		wp_delete_post( $id, true );
	}
}
add_action( 'vao_demandes_purge', 'vao_demandes_purge' );

function vao_demandes_unschedule_purge() {
	wp_clear_scheduled_hook( 'vao_demandes_purge' );
}
add_action( 'switch_theme', 'vao_demandes_unschedule_purge' );
