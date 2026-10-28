<?php
/**
 * Types de contenu modifiables dans l'admin :
 * Produits (+ Gammes), Témoignages, Bannière, Logos de marques,
 * Avis (captures), Collaborateurs, Actions RSE, Offres d'emploi, Distributeurs.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Types de contenu du thème.
 */
function vao_content_types() {
	return array( 'vao_produit', 'vao_temoignage', 'vao_banniere', 'vao_marque', 'vao_avis', 'vao_collaborateur', 'vao_rse', 'vao_offre', 'vao_distributeur' );
}

/**
 * Types ajoutés en v2 : array( singulier, pluriel, icône, champs supportés ).
 */
function vao_extra_types() {
	return array(
		'vao_avis'          => array( 'Avis consommateur', 'Avis consommateurs', 'dashicons-format-chat', array( 'title', 'thumbnail', 'page-attributes' ) ),
		'vao_collaborateur' => array( 'Collaborateur', 'Collaborateurs', 'dashicons-groups', array( 'title', 'thumbnail', 'page-attributes' ) ),
		'vao_rse'           => array( 'Action RSE', 'Actions RSE', 'dashicons-heart', array( 'title', 'editor', 'page-attributes' ) ),
		'vao_offre'         => array( "Offre d'emploi", "Offres d'emploi", 'dashicons-businessperson', array( 'title', 'editor', 'thumbnail', 'page-attributes' ) ),
		'vao_distributeur'  => array( 'Ville (distributeurs)', 'Distributeurs', 'dashicons-store', array( 'title', 'page-attributes' ) ),
	);
}

/**
 * Champs supplémentaires par type : clé de méta => array( libellé, type (text|textarea|select), choix ).
 */
function vao_meta_fields() {
	return array(
		'vao_collaborateur' => array(
			'_vao_poste'      => array( 'Poste (ex. : Responsable Marketing)', 'text' ),
			'_vao_anciennete' => array( 'Ancienneté (ex. : 3 ans chez SDOI)', 'text' ),
			'_vao_texte'      => array( 'Témoignage', 'textarea' ),
		),
		'vao_offre'         => array(
			'_vao_couleur' => array( 'Couleur du titre', 'select', array( 'orange' => 'Orange', 'bleu' => 'Bleu', 'violet' => 'Violet' ) ),
		),
		'vao_distributeur'  => array(
			'_vao_points' => array( 'Points de vente (un par ligne, ex. : MITSINJO : Ampasampito)', 'textarea' ),
		),
	);
}

function vao_labels( $singular, $plural ) {
	return array(
		'name'                  => $plural,
		'singular_name'         => $singular,
		'menu_name'             => $plural,
		'all_items'             => $plural,
		'add_new'               => 'Ajouter',
		'add_new_item'          => 'Ajouter',
		'edit_item'             => 'Modifier',
		'new_item'              => 'Nouveau',
		'view_item'             => 'Voir',
		'search_items'          => 'Rechercher',
		'not_found'             => 'Aucun élément',
		'not_found_in_trash'    => 'Aucun élément dans la corbeille',
		'featured_image'        => 'Image',
		'set_featured_image'    => "Choisir l'image",
		'remove_featured_image' => "Retirer l'image",
		'use_featured_image'    => 'Utiliser comme image',
	);
}

function vao_register_content() {
	$common = array(
		'public'              => false,
		'show_ui'             => true,
		'show_in_nav_menus'   => false,
		'exclude_from_search' => true,
		'publicly_queryable'  => false,
		'has_archive'         => false,
		'rewrite'             => false,
		'supports'            => array( 'title', 'thumbnail', 'page-attributes' ),
	);

	register_post_type(
		'vao_produit',
		array_merge(
			$common,
			array(
				'labels'        => vao_labels( 'Produit', 'Produits' ),
				'menu_icon'     => 'dashicons-cart',
				'menu_position' => 20,
			)
		)
	);
	register_post_type(
		'vao_temoignage',
		array_merge(
			$common,
			array(
				'labels'        => vao_labels( 'Témoignage', 'Témoignages' ),
				'menu_icon'     => 'dashicons-format-quote',
				'menu_position' => 21,
			)
		)
	);
	register_post_type(
		'vao_banniere',
		array_merge(
			$common,
			array(
				'labels'        => vao_labels( 'Image de bannière', 'Bannière' ),
				'menu_icon'     => 'dashicons-images-alt2',
				'menu_position' => 22,
			)
		)
	);
	register_post_type(
		'vao_marque',
		array_merge(
			$common,
			array(
				'labels'        => vao_labels( 'Logo de marque', 'Logos de marques' ),
				'menu_icon'     => 'dashicons-awards',
				'menu_position' => 23,
			)
		)
	);

	$position = 24;
	foreach ( vao_extra_types() as $type => $def ) {
		list( $singular, $plural, $icon, $supports ) = $def;
		register_post_type(
			$type,
			array_merge(
				$common,
				array(
					'labels'        => vao_labels( $singular, $plural ),
					'menu_icon'     => $icon,
					'menu_position' => $position++,
					'supports'      => $supports,
				)
			)
		);
	}

	register_taxonomy(
		'vao_gamme',
		'vao_produit',
		array(
			'labels'            => array(
				'name'          => 'Gammes',
				'singular_name' => 'Gamme',
				'menu_name'     => 'Gammes',
				'all_items'     => 'Toutes les gammes',
				'add_new_item'  => 'Ajouter une gamme',
				'edit_item'     => 'Modifier la gamme',
				'search_items'  => 'Rechercher',
				'not_found'     => 'Aucune gamme',
			),
			'public'            => false,
			'show_ui'           => true,
			'show_in_nav_menus' => true,
			'show_admin_column' => true,
			'hierarchical'      => true,
			'rewrite'           => false,
		)
	);
}
add_action( 'init', 'vao_register_content' );

/**
 * Une gamme dans un menu pointe vers son ancre sur la page des gammes (ex. /gammes/#detergent).
 */
function vao_gamme_term_link( $url, $term, $taxonomy ) {
	return 'vao_gamme' === $taxonomy ? vao_url( 'gammes', $term->slug ) : $url;
}
add_filter( 'term_link', 'vao_gamme_term_link', 10, 3 );

/**
 * Gammes dans l'ordre choisi (champ « Ordre d'affichage »).
 */
function vao_gammes( $hide_empty = true ) {
	$terms = get_terms(
		array(
			'taxonomy'   => 'vao_gamme',
			'hide_empty' => $hide_empty,
		)
	);
	if ( is_wp_error( $terms ) ) {
		return array();
	}
	usort(
		$terms,
		function ( $a, $b ) {
			return (int) get_term_meta( $a->term_id, 'vao_ordre', true ) - (int) get_term_meta( $b->term_id, 'vao_ordre', true );
		}
	);
	return $terms;
}

/* -------------------------------------------------------------------------
 * Aide à la saisie
 * ---------------------------------------------------------------------- */

function vao_title_placeholder( $text, $post ) {
	$placeholders = array(
		'vao_produit'    => 'Nom du produit (ex. : VAO Citron)',
		'vao_temoignage' => "Description de l'image (ex. : Client - Robert, père de famille)",
		'vao_banniere'   => "Texte de l'image (ex. : La fraîcheur qui voyage avec vous)",
		'vao_marque'     => 'Nom de la marque',
		'vao_avis'       => "Auteur de l'avis (ex. : Nilah Rissa)",
		'vao_collaborateur' => 'Prénom et nom',
		'vao_rse'        => "Titre de l'action (ex. : VAO, au cœur de la solidarité à Noël)",
		'vao_offre'      => 'Intitulé du poste',
		'vao_distributeur' => 'Ville ou région (ex. : ANTSIRABE)',
	);
	return isset( $placeholders[ $post->post_type ] ) ? $placeholders[ $post->post_type ] : $text;
}
add_filter( 'enter_title_here', 'vao_title_placeholder', 10, 2 );

/**
 * Listes de l'admin : colonne image + tri par ordre d'affichage.
 */
function vao_admin_columns() {
	foreach ( vao_content_types() as $post_type ) {
		add_filter(
			"manage_{$post_type}_posts_columns",
			function ( $columns ) {
				return array_slice( $columns, 0, 1, true ) + array( 'vao_image' => 'Image' ) + array_slice( $columns, 1, null, true );
			}
		);
		add_action(
			"manage_{$post_type}_posts_custom_column",
			function ( $column, $post_id ) {
				if ( 'vao_image' === $column ) {
					$url = vao_post_img( $post_id, 'thumbnail' );
					if ( $url ) {
						printf( '<img src="%s" alt="" style="width:60px;height:60px;object-fit:contain;background:#f0f0f1">', esc_url( $url ) );
					}
				}
			},
			10,
			2
		);
	}
}
add_action( 'admin_init', 'vao_admin_columns' );

function vao_admin_order( $query ) {
	if ( is_admin() && $query->is_main_query() && in_array( $query->get( 'post_type' ), vao_content_types(), true ) && empty( $_GET['orderby'] ) ) {
		$query->set( 'orderby', array( 'menu_order' => 'ASC', 'date' => 'ASC' ) );
	}
}
add_action( 'pre_get_posts', 'vao_admin_order' );

/* -------------------------------------------------------------------------
 * Champs des produits
 * ---------------------------------------------------------------------- */

function vao_card_colors() {
	return array(
		'blue'   => 'Bleu',
		'yellow' => 'Jaune',
		'pink'   => 'Rose',
	);
}

function vao_add_meta_boxes() {
	add_meta_box( 'vao_produit_details', 'Détails du produit', 'vao_produit_metabox', 'vao_produit', 'normal', 'high' );
	foreach ( array_keys( vao_meta_fields() ) as $post_type ) {
		add_meta_box( 'vao_details', 'Informations', 'vao_fields_metabox', $post_type, 'normal', 'high' );
	}
	foreach ( vao_content_types() as $post_type ) {
		if ( post_type_supports( $post_type, 'thumbnail' ) ) {
			add_meta_box( 'vao_theme_image', "Image d'origine", 'vao_theme_image_metabox', $post_type, 'side', 'low' );
		}
	}
	add_meta_box( 'vao_rse_galerie', 'Photos', 'vao_rse_metabox', 'vao_rse', 'side', 'low' );
}
add_action( 'add_meta_boxes', 'vao_add_meta_boxes' );

/* -------------------------------------------------------------------------
 * Champs des collaborateurs, offres et distributeurs
 * ---------------------------------------------------------------------- */

function vao_fields_metabox( $post ) {
	$fields = vao_meta_fields();
	wp_nonce_field( 'vao_fields_save', 'vao_fields_nonce' );
	foreach ( $fields[ $post->post_type ] as $key => $field ) {
		$value = get_post_meta( $post->ID, $key, true );
		$id    = ltrim( $key, '_' );
		echo '<p><label for="' . esc_attr( $id ) . '"><strong>' . esc_html( $field[0] ) . '</strong></label><br>';
		if ( 'textarea' === $field[1] ) {
			echo '<textarea id="' . esc_attr( $id ) . '" name="' . esc_attr( $id ) . '" rows="8" class="widefat">' . esc_textarea( $value ) . '</textarea>';
		} elseif ( 'select' === $field[1] ) {
			echo '<select id="' . esc_attr( $id ) . '" name="' . esc_attr( $id ) . '">';
			foreach ( $field[2] as $choice => $label ) {
				echo '<option value="' . esc_attr( $choice ) . '"' . selected( $value, $choice, false ) . '>' . esc_html( $label ) . '</option>';
			}
			echo '</select>';
		} else {
			echo '<input type="text" id="' . esc_attr( $id ) . '" name="' . esc_attr( $id ) . '" value="' . esc_attr( $value ) . '" class="widefat">';
		}
		echo '</p>';
	}
	if ( 'vao_offre' === $post->post_type ) {
		echo '<p class="description">Rédigez les missions et le profil recherché dans l\'éditeur (titres « Missions », « Profil recherché » + listes à puces). L\'image du poste se choisit dans l\'encadré « Image ».</p>';
	}
}

function vao_fields_save( $post_id, $post ) {
	$fields = vao_meta_fields();
	if ( ! isset( $fields[ $post->post_type ], $_POST['vao_fields_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['vao_fields_nonce'] ) ), 'vao_fields_save' ) ) {
		return;
	}
	if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	foreach ( $fields[ $post->post_type ] as $key => $field ) {
		$name  = ltrim( $key, '_' );
		$value = isset( $_POST[ $name ] ) ? wp_unslash( $_POST[ $name ] ) : '';
		if ( 'textarea' === $field[1] ) {
			$value = sanitize_textarea_field( $value );
		} elseif ( 'select' === $field[1] ) {
			$value = array_key_exists( $value, $field[2] ) ? $value : key( $field[2] );
		} else {
			$value = sanitize_text_field( $value );
		}
		update_post_meta( $post_id, $key, $value );
	}
}
add_action( 'save_post', 'vao_fields_save', 10, 2 );

/**
 * Photos d'une action RSE : les images ajoutées via « Ajouter un média » remplacent celles du thème.
 */
function vao_rse_metabox( $post ) {
	$attached = get_attached_media( 'image', $post->ID );
	$theme    = array_filter( (array) get_post_meta( $post->ID, '_vao_galerie', true ) );
	if ( $attached ) {
		printf( '<p>%d photo(s) ajoutée(s) à cette action sont affichées.</p>', count( $attached ) );
	} elseif ( $theme ) {
		printf( '<p>%d photo(s) livrée(s) avec le thème sont affichées.</p>', count( $theme ) );
	}
	echo '<p>Pour changer les photos : bouton « Ajouter un média » au-dessus du texte, puis téléverser les images. Toutes les images téléversées dans cette action forment la galerie (elles n\'ont pas besoin d\'être insérées dans le texte).</p>';
}

function vao_produit_metabox( $post ) {
	wp_nonce_field( 'vao_produit_save', 'vao_produit_nonce' );
	$poids       = get_post_meta( $post->ID, '_vao_poids', true );
	$cond        = get_post_meta( $post->ID, '_vao_conditionnement', true );
	$alt         = get_post_meta( $post->ID, '_vao_alt', true );
	$recommande  = get_post_meta( $post->ID, '_vao_recommande', true );
	$couleur     = get_post_meta( $post->ID, '_vao_couleur', true );
	?>
	<p>
		<label for="vao_poids"><strong>Poids / unité</strong> (ex. : 70g / unité)</label><br>
		<input type="text" id="vao_poids" name="vao_poids" value="<?php echo esc_attr( $poids ); ?>" class="widefat">
	</p>
	<p>
		<label for="vao_conditionnement"><strong>Conditionnement</strong> (ex. : 24 morceaux)</label><br>
		<input type="text" id="vao_conditionnement" name="vao_conditionnement" value="<?php echo esc_attr( $cond ); ?>" class="widefat">
	</p>
	<p>
		<label for="vao_alt"><strong>Description de l'image</strong> (facultatif, pour l'accessibilité — par défaut : le nom du produit)</label><br>
		<input type="text" id="vao_alt" name="vao_alt" value="<?php echo esc_attr( $alt ); ?>" class="widefat">
	</p>
	<hr>
	<p>
		<label><input type="checkbox" name="vao_recommande" value="1" <?php checked( $recommande, '1' ); ?>> Afficher dans « Produits recommandés » sur l'accueil</label>
	</p>
	<p>
		<label for="vao_couleur">Couleur de la carte sur l'accueil :</label>
		<select id="vao_couleur" name="vao_couleur">
			<?php foreach ( vao_card_colors() as $value => $label ) : ?>
				<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $couleur, $value ); ?>><?php echo esc_html( $label ); ?></option>
			<?php endforeach; ?>
		</select>
	</p>
	<p class="description">La gamme se choisit dans l'encadré « Gammes » à droite. L'ordre d'affichage se règle avec le champ « Ordre » (Attributs).</p>
	<?php
}

function vao_produit_save( $post_id ) {
	if ( ! isset( $_POST['vao_produit_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['vao_produit_nonce'] ) ), 'vao_produit_save' ) ) {
		return;
	}
	if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	foreach ( array( 'poids', 'conditionnement', 'alt' ) as $field ) {
		$value = isset( $_POST[ 'vao_' . $field ] ) ? sanitize_text_field( wp_unslash( $_POST[ 'vao_' . $field ] ) ) : '';
		update_post_meta( $post_id, '_vao_' . $field, $value );
	}
	update_post_meta( $post_id, '_vao_recommande', empty( $_POST['vao_recommande'] ) ? '' : '1' );
	$couleur = isset( $_POST['vao_couleur'] ) ? sanitize_key( $_POST['vao_couleur'] ) : 'blue';
	update_post_meta( $post_id, '_vao_couleur', array_key_exists( $couleur, vao_card_colors() ) ? $couleur : 'blue' );
}
add_action( 'save_post_vao_produit', 'vao_produit_save' );

/**
 * Indique quelle image est utilisée tant qu'aucune image n'a été choisie dans la médiathèque.
 */
function vao_theme_image_metabox( $post ) {
	$file = get_post_meta( $post->ID, '_vao_theme_img', true );
	if ( has_post_thumbnail( $post ) ) {
		echo '<p>L\'image choisie dans l\'encadré « Image » est utilisée.</p>';
	} elseif ( $file ) {
		printf( '<p><img src="%s" alt="" style="max-width:100%%;height:auto"></p>', esc_url( vao_img( $file ) ) );
		echo '<p>Image livrée avec le thème. Pour la remplacer, cliquez sur « Choisir l\'image » dans l\'encadré « Image ».</p>';
	} else {
		echo '<p>Choisissez une image dans l\'encadré « Image ».</p>';
	}
}

/* -------------------------------------------------------------------------
 * Champs des gammes
 * ---------------------------------------------------------------------- */

function vao_gamme_options() {
	return array(
		'vao_titre_couleur'  => array(
			'label'   => 'Couleur du titre',
			'choices' => array( 'blanc' => 'Blanc', 'orange' => 'Orange' ),
		),
		'vao_bouton_couleur' => array(
			'label'   => 'Couleur du bouton « + »',
			'choices' => array( 'orange' => 'Orange', 'blue' => 'Bleu', 'yellow' => 'Jaune', 'brown' => 'Marron' ),
		),
	);
}

function vao_gamme_add_fields() {
	?>
	<div class="form-field">
		<label for="vao_ordre">Ordre d'affichage</label>
		<input type="number" id="vao_ordre" name="vao_ordre" value="0">
	</div>
	<?php foreach ( vao_gamme_options() as $key => $option ) : ?>
		<div class="form-field">
			<label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $option['label'] ); ?></label>
			<select id="<?php echo esc_attr( $key ); ?>" name="<?php echo esc_attr( $key ); ?>">
				<?php foreach ( $option['choices'] as $value => $label ) : ?>
					<option value="<?php echo esc_attr( $value ); ?>"><?php echo esc_html( $label ); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
	<?php endforeach; ?>
	<p class="description">Le slug de la gamme sert d'ancre sur la page des gammes (ex. : /gammes/#detergent).</p>
	<?php
}
add_action( 'vao_gamme_add_form_fields', 'vao_gamme_add_fields' );

function vao_gamme_edit_fields( $term ) {
	?>
	<tr class="form-field">
		<th scope="row"><label for="vao_ordre">Ordre d'affichage</label></th>
		<td><input type="number" id="vao_ordre" name="vao_ordre" value="<?php echo esc_attr( (int) get_term_meta( $term->term_id, 'vao_ordre', true ) ); ?>"></td>
	</tr>
	<?php foreach ( vao_gamme_options() as $key => $option ) : $current = get_term_meta( $term->term_id, $key, true ); ?>
		<tr class="form-field">
			<th scope="row"><label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $option['label'] ); ?></label></th>
			<td>
				<select id="<?php echo esc_attr( $key ); ?>" name="<?php echo esc_attr( $key ); ?>">
					<?php foreach ( $option['choices'] as $value => $label ) : ?>
						<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $current, $value ); ?>><?php echo esc_html( $label ); ?></option>
					<?php endforeach; ?>
				</select>
			</td>
		</tr>
	<?php endforeach; ?>
	<?php
}
add_action( 'vao_gamme_edit_form_fields', 'vao_gamme_edit_fields' );

function vao_gamme_save( $term_id ) {
	if ( ! current_user_can( 'manage_categories' ) ) {
		return;
	}
	if ( isset( $_POST['vao_ordre'] ) ) {
		update_term_meta( $term_id, 'vao_ordre', (int) $_POST['vao_ordre'] );
	}
	foreach ( vao_gamme_options() as $key => $option ) {
		if ( isset( $_POST[ $key ] ) ) {
			$value = sanitize_key( $_POST[ $key ] );
			if ( array_key_exists( $value, $option['choices'] ) ) {
				update_term_meta( $term_id, $key, $value );
			}
		}
	}
}
add_action( 'created_vao_gamme', 'vao_gamme_save' );
add_action( 'edited_vao_gamme', 'vao_gamme_save' );
