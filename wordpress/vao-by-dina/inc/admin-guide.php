<?php
/**
 * Encadré « Guide du site » en tête du tableau de bord : où modifier quoi, et comment traduire en malagasy.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function vao_dashboard_guide_register() {
	if ( ! current_user_can( 'edit_posts' ) ) {
		return;
	}
	wp_add_dashboard_widget( 'vao_guide', 'Guide du site VAO', 'vao_dashboard_guide' );

	// En premier dans la colonne de gauche.
	global $wp_meta_boxes;
	$normal = $wp_meta_boxes['dashboard']['normal']['core'];
	$guide  = array( 'vao_guide' => $normal['vao_guide'] );
	unset( $normal['vao_guide'] );
	$wp_meta_boxes['dashboard']['normal']['core'] = $guide + $normal; // phpcs:ignore WordPress.WP.GlobalVariablesOverride -- réordonnancement documenté du tableau de bord.
}
add_action( 'wp_dashboard_setup', 'vao_dashboard_guide_register' );

function vao_dashboard_guide() {
	$list = function ( $type ) {
		return admin_url( 'edit.php?post_type=' . $type );
	};
	$new  = function ( $type ) {
		return admin_url( 'post-new.php?post_type=' . $type );
	};
	$customize = function ( $focus ) {
		return admin_url( 'customize.php?autofocus[' . $focus . ']' );
	};
	$links = array(
		'Produits'                      => array(
			array( 'Ajouter un produit', $new( 'vao_produit' ) ),
			array( 'Tous les produits', $list( 'vao_produit' ) ),
			array( 'Gammes (ordre, textes, malagasy)', admin_url( 'edit-tags.php?taxonomy=vao_gamme&post_type=vao_produit' ) ),
		),
		'Images et contenus des pages'  => array(
			array( 'Bannière (carrousel)', $list( 'vao_banniere' ) ),
			array( 'Témoignages', $list( 'vao_temoignage' ) ),
			array( 'Logos de marques', $list( 'vao_marque' ) ),
			array( 'Avis consommateurs', $list( 'vao_avis' ) ),
			array( 'Collaborateurs', $list( 'vao_collaborateur' ) ),
			array( 'Actions RSE', $list( 'vao_rse' ) ),
			array( "Offres d'emploi", $list( 'vao_offre' ) ),
			array( 'Distributeurs', $list( 'vao_distributeur' ) ),
		),
		'Textes, logos et coordonnées'  => array(
			array( 'Personnaliser > Thème VAO', $customize( 'panel]=vao' ) ),
		),
		'Formulaires'                   => array(
			array( 'Demandes reçues (historique, export CSV)', $list( 'vao_demande' ) ),
			array( 'Adresses qui reçoivent les formulaires', $customize( 'section]=vao_formulaires' ) ),
		),
	);
	?>
	<style>
		#vao_guide h3 { margin: 1.2em 0 .4em; font-size: 14px; }
		#vao_guide h3:first-child { margin-top: 0; }
		#vao_guide ul { margin: 0 0 0 1.2em; list-style: disc; }
		#vao_guide li { margin: .2em 0; }
		#vao_guide .vao-guide-mg { background: #f6f7f7; border-left: 4px solid #d63638; padding: 8px 12px; margin-top: 1em; }
	</style>
	<?php foreach ( $links as $title => $items ) : ?>
		<h3><?php echo esc_html( $title ); ?></h3>
		<ul>
			<?php foreach ( $items as $item ) : ?>
				<li><a href="<?php echo esc_url( $item[1] ); ?>"><?php echo esc_html( $item[0] ); ?></a></li>
			<?php endforeach; ?>
		</ul>
	<?php endforeach; ?>
	<p class="description">L'ordre d'affichage se règle avec le champ « Ordre » de chaque fiche (le plus petit en premier).</p>

	<div class="vao-guide-mg">
		<h3>Traduire en malagasy</h3>
		<ul>
			<li><strong>Textes des pages</strong> : dans Personnaliser, chaque texte a un champ « en malagasy » juste en dessous.</li>
			<li><strong>Gammes, collaborateurs, actions RSE</strong> : champs « en malagasy » dans la fiche, à côté du français.</li>
			<li><strong>Bannière et témoignages</strong> (texte dans l'image) : dans la fiche, encadré « Langues » à droite, cliquez sur « + » à côté de MG puis choisissez l'image en malagasy.</li>
			<li><strong>Menus, boutons, titres de sections</strong> : <a href="<?php echo esc_url( admin_url( 'admin.php?page=mlang_strings' ) ); ?>">Langues &gt; Traductions des chaînes</a> (utilisez la recherche).</li>
		</ul>
		<p>Un champ malagasy laissé vide affiche le texte français. Les offres d'emploi restent en français.</p>
	</div>
	<?php
}
