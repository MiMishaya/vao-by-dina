<?php
/**
 * Template Name: Nous contacter
 *
 * Quatre onglets : #distributeur, #information, #offres (menu « Offres d'emploi ») et #candidature.
 * L'onglet ouvert suit l'ancre de l'URL (assets/js/contact.js) ; sans JavaScript, tout s'affiche à la suite.
 * Libellés traduits (Langues > Traductions des chaînes) ; les noms des champs et les valeurs cochées
 * restent en français pour que l'e-mail reçu soit toujours lisible (voir inc/forms.php).
 */

get_header();

// Poste prérempli par le lien « Postuler » d'une offre (fonctionne aussi sans JavaScript).
$vao_poste = isset( $_GET['poste'] ) ? sanitize_text_field( wp_unslash( $_GET['poste'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- simple préremplissage.

$vao_tabs = array(
	'distributeur' => 'DEVENEZ DISTRIBUTEURS',
	'information'  => "DEMANDE D'INFORMATIONS",
	'offres'       => "NOS OFFRES D'EMPLOI",
	'candidature'  => 'CANDIDATURE SPONTANÉE – SDOI',
);

/**
 * Ligne libellé / champ. $args : type, required, autocomplete, value.
 */
$vao_field = function ( $id, $label, $name, $args = array() ) {
	$args = array_merge(
		array(
			'type'         => 'text',
			'required'     => false,
			'autocomplete' => '',
			'value'        => '',
		),
		$args
	);
	echo '<div class="contact-row">';
	printf( '<label class="contact-label" for="%s">%s</label>', esc_attr( $id ), esc_html( vao_t( $label ) ) );
	if ( 'textarea' === $args['type'] ) {
		printf(
			'<textarea class="contact-input" id="%1$s" name="vao[%2$s]" rows="4"%3$s>%4$s</textarea>',
			esc_attr( $id ),
			esc_attr( $name ),
			$args['required'] ? ' required' : '',
			esc_textarea( $args['value'] )
		);
	} else {
		printf(
			'<input class="contact-input" id="%1$s" type="%2$s" name="vao[%3$s]" value="%4$s"%5$s%6$s>',
			esc_attr( $id ),
			esc_attr( $args['type'] ),
			esc_attr( $name ),
			esc_attr( $args['value'] ),
			$args['autocomplete'] ? ' autocomplete="' . esc_attr( $args['autocomplete'] ) . '"' : '',
			$args['required'] ? ' required' : ''
		);
	}
	echo '</div>';
};

/**
 * Cases à cocher envoyées dans vao[$name][] (valeur française, libellé traduit).
 */
$vao_checks = function ( $name, $values, $class = '' ) {
	echo '<div class="contact-checks' . ( $class ? ' ' . esc_attr( $class ) : '' ) . '">';
	foreach ( $values as $value ) {
		printf(
			'<label><input type="checkbox" name="vao[%s][]" value="%s"> %s</label>',
			esc_attr( $name ),
			esc_attr( $value ),
			esc_html( vao_t( $value ) )
		);
	}
	echo '</div>';
};

/**
 * Bouton d'import de fichiers (vao_file[]) + liste des fichiers choisis (assets/js/contact.js).
 */
$vao_upload = function ( $id, $accept, $hint ) {
	?>
	<input class="contact-file visually-hidden" id="<?php echo esc_attr( $id ); ?>" type="file" name="vao_file[]" accept="<?php echo esc_attr( $accept ); ?>" multiple aria-describedby="<?php echo esc_attr( $id ); ?>-hint">
	<label class="contact-upload" for="<?php echo esc_attr( $id ); ?>">
		<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 15V4m-5 5 5-5 5 5M4 15v5h16v-5"/></svg>
		<?php vao_e( 'Importer un fichier' ); ?>
	</label>
	<p class="contact-hint" id="<?php echo esc_attr( $id ); ?>-hint"><?php vao_e( $hint ); ?></p>
	<p class="contact-file-error" role="alert" hidden></p>
	<ul class="contact-file-list" aria-live="polite"></ul>
	<?php
};

/**
 * Photo à gauche des formulaires.
 */
$vao_photo = function ( $key, $alt ) {
	printf(
		'<div class="contact-photo"><img src="%s" alt="%s" loading="lazy"></div>',
		esc_url( vao_mod( $key ) ),
		esc_attr( vao_t( $alt ) )
	);
};

$vao_offres = vao_items( 'vao_offre' );
$vao_arrow  = array(
	'prev' => '<svg viewBox="0 0 20 32" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M16 3 4 16l12 13"/></svg>',
	'next' => '<svg viewBox="0 0 20 32" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m4 3 12 13L4 29"/></svg>',
);
?>

<section class="contact" id="contact-page"
	data-msg-size="<?php echo esc_attr( vao_t( 'Fichier de plus de 5 Mo non ajouté : %s' ) ); ?>"
	data-msg-count="<?php echo esc_attr( vao_t( '5 fichiers au maximum : les fichiers en trop n’ont pas été ajoutés.' ) ); ?>">
	<h1 class="visually-hidden"><?php the_title(); ?></h1>

	<nav class="contact-tabs" aria-label="<?php echo esc_attr( vao_t( 'Nous contacter' ) ); ?>">
		<?php foreach ( $vao_tabs as $vao_id => $vao_tab ) : ?>
			<a class="contact-tab contact-tab--<?php echo esc_attr( $vao_id ); ?>" id="tab-<?php echo esc_attr( $vao_id ); ?>" href="#<?php echo esc_attr( $vao_id ); ?>"><?php vao_e( $vao_tab ); ?></a>
		<?php endforeach; ?>
	</nav>

	<div class="contact-stage">
		<div class="contact-arrow-col contact-arrow-col--prev">
			<a class="contact-arrow" data-dir="prev" href="#candidature" aria-label="<?php echo esc_attr( vao_t( 'Précédent' ) ); ?>"><?php echo $vao_arrow['prev']; // SVG statique. ?></a>
		</div>

		<div class="contact-panels">

			<!-- DEVENIR DISTRIBUTEUR -->
			<div class="contact-panel" id="distributeur" aria-labelledby="tab-distributeur">
				<h2 class="visually-hidden"><?php vao_e( 'Devenir distributeur' ); ?></h2>
				<div class="contact-card">
					<?php $vao_photo( 'form_img_distributeur', 'Devenir distributeur' ); ?>
					<form class="contact-form contact-form--distributeur" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" enctype="multipart/form-data">
						<?php vao_form_fields( 'distributeur' ); ?>
						<?php vao_form_notice( 'distributeur' ); ?>

						<fieldset class="contact-section">
							<legend class="contact-legend"><?php vao_e( '1. Informations personnelles' ); ?></legend>
							<?php
							$vao_field( 'd-raison', 'Nom complet ou Raison sociale :', 'Nom complet ou Raison sociale', array( 'required' => true, 'autocomplete' => 'organization' ) );
							$vao_field( 'd-responsable', 'Nom du responsable :', 'Nom du responsable', array( 'autocomplete' => 'name' ) );
							$vao_field( 'd-email', 'E-mail :', 'E-mail', array( 'type' => 'email', 'required' => true, 'autocomplete' => 'email' ) );
							$vao_field( 'd-telephone', 'Téléphone :', 'Téléphone', array( 'type' => 'tel', 'required' => true, 'autocomplete' => 'tel' ) );
							?>
						</fieldset>

						<fieldset class="contact-section">
							<legend class="contact-legend"><?php vao_e( '2. Localisation' ); ?></legend>
							<?php
							$vao_field( 'd-adresse', 'Adresse exacte :', 'Adresse exacte', array( 'autocomplete' => 'street-address' ) );
							$vao_field( 'd-ville', 'Ville :', 'Ville', array( 'autocomplete' => 'address-level2' ) );
							$vao_field( 'd-pays', 'Pays :', 'Pays', array( 'autocomplete' => 'country-name' ) );
							?>
						</fieldset>

						<fieldset class="contact-section">
							<legend class="contact-legend"><?php vao_e( "3. Informations sur l'activité" ); ?></legend>
							<div class="contact-row" role="group" aria-labelledby="d-activite">
								<span class="contact-label" id="d-activite"><?php vao_e( "Type d'activités :" ); ?></span>
								<?php $vao_checks( "Type d'activité", array( 'Libre-service', 'Épicerie', 'Grossiste' ) ); ?>
							</div>
							<?php
							$vao_field( 'd-magasin', 'Nom du magasin :', 'Nom du magasin' );
							$vao_field( 'd-experience', "Année d'expérience :", "Années d'expérience" );
							$vao_field( 'd-produits', "Produits d'intérêt :", "Produits d'intérêt" );
							$vao_field( 'd-stockage', 'Capacité de stockage :', 'Capacité de stockage' );
							$vao_field( 'd-volume', 'Volume estimé mensuel :', 'Volume estimé mensuel' );
							?>
						</fieldset>

						<fieldset class="contact-section">
							<legend class="contact-legend"><?php vao_e( '4. Informations légales' ); ?></legend>
							<?php
							$vao_field( 'd-rcs', 'N° RCS :', 'N° RCS' );
							$vao_field( 'd-nif', 'N° NIF :', 'N° NIF' );
							$vao_field( 'd-stat', 'N° STAT :', 'N° STAT' );
							?>
						</fieldset>

						<fieldset class="contact-section">
							<legend class="contact-legend"><?php vao_e( '5. Pièces à fournir' ); ?></legend>
							<div class="contact-row" role="group" aria-labelledby="d-pieces">
								<span class="contact-label" id="d-pieces"><?php vao_e( 'Veuillez joindre les documents suivants :' ); ?></span>
								<div class="contact-field">
									<?php
									$vao_checks( 'Pièces fournies', array( 'Copie NIF', 'Copie STAT', 'Copie RCS' ) );
									$vao_upload( 'd-file', '.pdf,.jpg,.jpeg,.png', 'Formats acceptés : PDF, JPG, PNG – 5 Mo maximum par fichier.' );
									?>
								</div>
							</div>
						</fieldset>

						<button type="submit" class="contact-submit"><?php vao_e( 'Envoyer' ); ?></button>
					</form>
				</div>
			</div>

			<!-- DEMANDE D'INFORMATIONS -->
			<div class="contact-panel" id="information" aria-labelledby="tab-information">
				<h2 class="visually-hidden"><?php vao_e( "Demande d'informations" ); ?></h2>
				<div class="contact-card">
					<?php $vao_photo( 'form_img_information', "Demande d'informations" ); ?>
					<form class="contact-form contact-form--information" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" enctype="multipart/form-data">
						<?php vao_form_fields( 'information' ); ?>
						<?php vao_form_notice( 'information' ); ?>

						<fieldset class="contact-section">
							<legend class="contact-legend"><?php vao_e( '1. Informations sur le demandeur' ); ?></legend>
							<?php
							$vao_field( 'i-date', 'Date de la demande :', 'Date de la demande', array( 'type' => 'date', 'value' => wp_date( 'Y-m-d' ) ) );
							$vao_field( 'i-societe', 'Nom de la société / Client :', 'Nom de la société / Client', array( 'autocomplete' => 'organization' ) );
							$vao_field( 'i-contact', 'Nom & Prénom du contact :', 'Nom & Prénom du contact', array( 'required' => true, 'autocomplete' => 'name' ) );
							$vao_field( 'i-telephone', 'Téléphone :', 'Téléphone', array( 'type' => 'tel', 'autocomplete' => 'tel' ) );
							$vao_field( 'i-email', 'Adresse E-mail :', 'E-mail', array( 'type' => 'email', 'required' => true, 'autocomplete' => 'email' ) );
							$vao_field( 'i-type', 'Type de client :', 'Type de client' );
							?>
						</fieldset>

						<fieldset class="contact-section">
							<legend class="contact-legend"><?php vao_e( '2. Spécifications du produit recherché' ); ?></legend>
							<div class="contact-row">
								<label class="contact-label" for="i-categorie"><?php vao_e( 'Catégorie :' ); ?></label>
								<select class="contact-input" id="i-categorie" name="vao[Catégorie]">
									<option value=""><?php vao_e( 'Sélectionner…' ); ?></option>
									<?php foreach ( vao_gammes( false ) as $vao_gamme ) : ?>
										<option value="<?php echo esc_attr( $vao_gamme->name ); ?>"><?php vao_e( $vao_gamme->name ); ?></option>
									<?php endforeach; ?>
								</select>
							</div>
							<?php
							$vao_field( 'i-gamme', 'Gamme / Type Format / Parfum :', 'Gamme / Type Format / Parfum' );
							$vao_field( 'i-quantite', 'Quantité souhaitée :', 'Quantité souhaitée' );
							$vao_field( 'i-usage', 'Usage prévu :', 'Usage prévu' );
							$vao_field( 'i-commentaires', 'Commentaires :', 'Commentaires', array( 'type' => 'textarea' ) );
							?>
						</fieldset>

						<fieldset class="contact-section">
							<legend class="contact-legend"><?php vao_e( '3. Informations souhaitées' ); ?></legend>
							<?php
							$vao_checks(
								'Informations souhaitées',
								array(
									'Grille tarifaire dégressive',
									'Fiche Technique / Compositions (MSDS)',
									'Conditions de livraison & Délais',
									'Échantillons de démonstration',
									'Possibilité de personnalisation',
									'Certifications & Normes de qualité',
								),
								'contact-checks--grid'
							);
							?>
						</fieldset>

						<div class="contact-section">
							<div class="contact-row">
								<label class="contact-legend contact-label" for="i-message"><?php vao_e( '4. Message complémentaire' ); ?></label>
								<textarea class="contact-input" id="i-message" name="vao[Message]" rows="5"></textarea>
							</div>
						</div>

						<button type="submit" class="contact-submit"><?php vao_e( 'Envoyer' ); ?></button>
					</form>
				</div>
			</div>

			<!-- OFFRES D'EMPLOI -->
			<div class="contact-panel" id="offres" aria-labelledby="tab-offres">
				<h2 class="visually-hidden"><?php vao_e( "Nos offres d'emploi" ); ?></h2>
				<?php if ( $vao_offres ) : ?>
					<div class="offres">
						<?php foreach ( $vao_offres as $vao_offre ) : ?>
							<?php
							$vao_couleur  = get_post_meta( $vao_offre->ID, '_vao_couleur', true );
							$vao_couleur  = in_array( $vao_couleur, array( 'orange', 'bleu', 'violet' ), true ) ? $vao_couleur : 'orange';
							$vao_titre    = get_the_title( $vao_offre );
							// Titre brut (sans les entités ajoutées par le filtre the_title) pour le champ « Poste souhaité ».
							$vao_intitule = wp_strip_all_tags( $vao_offre->post_title );
							$vao_img      = vao_post_img( $vao_offre, 'large' );
							$vao_postuler = add_query_arg( 'poste', rawurlencode( $vao_intitule ), get_permalink() ) . '#candidature';
							?>
							<article class="offre offre--<?php echo esc_attr( $vao_couleur ); ?>">
								<?php if ( $vao_img ) : ?>
									<div class="offre-photo"><img src="<?php echo esc_url( $vao_img ); ?>" alt="<?php echo esc_attr( vao_post_alt( $vao_offre ) ); ?>" loading="lazy"></div>
								<?php endif; ?>
								<div class="offre-body">
									<h2 class="offre-title"><?php echo esc_html( $vao_titre ); ?></h2>
									<div class="offre-content">
										<?php echo apply_filters( 'the_content', $vao_offre->post_content ); // phpcs:ignore WordPress.Security.EscapeOutput -- contenu de l'éditeur, filtré par WordPress. ?>
									</div>
									<a class="offre-postuler" href="<?php echo esc_url( $vao_postuler ); ?>" data-poste="<?php echo esc_attr( $vao_intitule ); ?>" aria-label="<?php echo esc_attr( vao_t( 'Postuler' ) . ' : ' . $vao_intitule ); ?>"><?php vao_e( 'Postuler' ); ?></a>
								</div>
							</article>
						<?php endforeach; ?>
					</div>
				<?php else : ?>
					<div class="offres-empty">
						<p><?php vao_e( "Aucune offre d'emploi n'est publiée pour le moment." ); ?></p>
						<a href="#candidature"><?php vao_e( 'Envoyez-nous une candidature spontanée' ); ?></a>
					</div>
				<?php endif; ?>
			</div>

			<!-- CANDIDATURE SPONTANÉE -->
			<div class="contact-panel" id="candidature" aria-labelledby="tab-candidature">
				<h2 class="visually-hidden"><?php vao_e( 'Candidature spontanée' ); ?></h2>
				<div class="contact-card">
					<?php $vao_photo( 'form_img_candidature', 'Candidature spontanée' ); ?>
					<form class="contact-form contact-form--candidature" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" enctype="multipart/form-data">
						<?php vao_form_fields( 'candidature' ); ?>
						<?php vao_form_notice( 'candidature' ); ?>

						<fieldset class="contact-section">
							<legend class="contact-legend"><?php vao_e( '1. Informations personnelles' ); ?></legend>
							<?php
							$vao_field( 'c-nom', 'Nom *', 'Nom', array( 'required' => true, 'autocomplete' => 'family-name' ) );
							$vao_field( 'c-prenoms', 'Prénoms *', 'Prénoms', array( 'required' => true, 'autocomplete' => 'given-name' ) );
							$vao_field( 'c-email', 'E-mail *', 'E-mail', array( 'type' => 'email', 'required' => true, 'autocomplete' => 'email' ) );
							$vao_field( 'c-telephone', 'Téléphone *', 'Téléphone', array( 'type' => 'tel', 'required' => true, 'autocomplete' => 'tel' ) );
							$vao_field( 'c-adresse', 'Adresse :', 'Adresse', array( 'autocomplete' => 'street-address' ) );
							$vao_field( 'c-ville', 'Ville :', 'Ville', array( 'autocomplete' => 'address-level2' ) );
							$vao_field( 'c-pays', 'Pays :', 'Pays', array( 'autocomplete' => 'country-name' ) );
							?>
						</fieldset>

						<fieldset class="contact-section">
							<legend class="contact-legend"><?php vao_e( '2. Profil professionnel' ); ?></legend>
							<?php
							$vao_field( 'c-poste', 'Poste souhaité :', 'Poste souhaité', array( 'value' => $vao_poste, 'autocomplete' => 'organization-title' ) );
							$vao_field( 'c-domaine', "Domaine d'expertise :", "Domaine d'expertise" );
							$vao_field( 'c-experience', "Années d'expérience :", "Années d'expérience" );
							$vao_field( 'c-dispo', 'Disponibilité :', 'Disponibilité' );
							?>
						</fieldset>

						<fieldset class="contact-section">
							<legend class="contact-legend"><?php vao_e( '3. Motivation' ); ?></legend>
							<?php $vao_field( 'c-motivation', 'Pourquoi souhaitez-vous rejoindre SDOI ?', 'Motivation', array( 'type' => 'textarea' ) ); ?>
						</fieldset>

						<fieldset class="contact-section">
							<legend class="contact-legend"><?php vao_e( '4. Pièces jointes' ); ?></legend>
							<div class="contact-row" role="group" aria-labelledby="c-pieces">
								<span class="contact-label" id="c-pieces"><?php vao_e( 'Veuillez joindre les documents suivants :' ); ?></span>
								<div class="contact-field">
									<?php
									$vao_checks( 'Pièces fournies', array( 'CV', 'Lettre de motivation', 'Autres' ) );
									$vao_upload( 'c-file', '.pdf,.doc,.docx,.jpg,.jpeg,.png', 'Formats acceptés : PDF, DOC, DOCX, JPG, PNG – 5 Mo maximum par fichier.' );
									?>
								</div>
							</div>
						</fieldset>

						<button type="submit" class="contact-submit"><?php vao_e( 'Envoyer' ); ?></button>
					</form>
				</div>
			</div>

		</div>

		<div class="contact-arrow-col contact-arrow-col--next">
			<a class="contact-arrow" data-dir="next" href="#information" aria-label="<?php echo esc_attr( vao_t( 'Suivant' ) ); ?>"><?php echo $vao_arrow['next']; // SVG statique. ?></a>
		</div>
	</div>

	<?php get_template_part( 'template-parts/brand-strip', null, array( 'muted' => true ) ); ?>
</section>

<?php
get_footer();
