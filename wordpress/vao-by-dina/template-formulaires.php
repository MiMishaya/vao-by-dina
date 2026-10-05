<?php
/**
 * Template Name: Formulaires - Échantillon et candidature
 *
 * Deux formulaires en onglets : #echantillon (par défaut) et #candidature.
 * Libellés traduits (Langues > Traductions des chaînes) ; les noms des champs et les valeurs
 * cochées restent en français pour que l'e-mail reçu soit toujours lisible.
 */

get_header();

$vao_check = function ( $field, $value ) {
	printf(
		'<label><input type="checkbox" name="vao[%s][]" value="%s"> %s</label>',
		esc_attr( $field ),
		esc_attr( $value ),
		esc_html( vao_t( $value ) )
	);
};
$vao_other = function ( $field, $aria ) {
	printf(
		'<span class="inline-other"><input type="checkbox" name="vao[%1$s][]" value="Autre" aria-label="%2$s"> %3$s <input type="text" name="vao[%1$s (autre)]" aria-label="%2$s"></span>',
		esc_attr( $field ),
		esc_attr( vao_t( 'Autre' ) . ' - ' . $aria ),
		esc_html( vao_t( 'Autre :' ) )
	);
};
$vao_text = function ( $id, $label, $field, $type = 'text', $required = false ) {
	printf(
		'<div class="form-group"><label for="%1$s">%2$s</label><input id="%1$s" type="%3$s" name="vao[%4$s]"%5$s></div>',
		esc_attr( $id ),
		esc_html( vao_t( $label ) ),
		esc_attr( $type ),
		esc_attr( $field ),
		$required ? ' required' : ''
	);
};
?>

<section class="theme-section">
	<div class="tabs">
		<a class="tab" href="<?php echo esc_url( vao_url( 'devenir-revendeur' ) ); ?>"><?php vao_e( 'DEVENIR REVENDEUR' ); ?></a>
		<button type="button" class="tab active" id="tab-echantillon" aria-pressed="true"><?php vao_e( "DEMANDE D'ÉCHANTILLON" ); ?></button>
		<button type="button" class="tab" id="tab-candidature" aria-pressed="false"><?php vao_e( 'CANDIDATURE SPONTANÉE – SDOI' ); ?></button>
	</div>

	<div class="form-shell">
		<a class="side-arrow" id="arrow-prev" href="<?php echo esc_url( vao_url( 'devenir-revendeur' ) ); ?>" aria-label="<?php echo esc_attr( vao_t( 'Précédent' ) ); ?>">❮❮</a>

		<!-- DEMANDE D'ÉCHANTILLON -->
		<div class="form-wrapper" id="panel-echantillon">
			<div class="form-image">
				<img src="<?php echo esc_url( vao_mod( 'form_img_echantillon' ) ); ?>" alt="<?php echo esc_attr( vao_t( "Demande d'échantillon" ) ); ?>">
			</div>

			<form class="form-content" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<?php vao_form_fields( 'echantillon' ); ?>
				<?php vao_form_notice( 'echantillon' ); ?>

				<div class="form-section-title"><?php vao_e( '1. Informations personnelles' ); ?></div>
				<div class="form-row">
					<?php $vao_text( 'e-nom', 'Nom :', 'Nom' ); ?>
					<?php $vao_text( 'e-prenom', 'Prénom :', 'Prénom' ); ?>
				</div>
				<div class="form-row">
					<?php $vao_text( 'e-email', 'E-mail :', 'E-mail', 'email' ); ?>
					<?php $vao_text( 'e-telephone', 'Téléphone :', 'Téléphone', 'tel' ); ?>
				</div>

				<div class="form-section-title"><?php vao_e( '2. Informations professionnelles' ); ?></div>
				<div class="form-group full"><label for="e-entreprise"><?php vao_e( "Nom de l'entreprise / magasin :" ); ?></label><input id="e-entreprise" type="text" name="vao[Entreprise / magasin]"></div>
				<div class="form-group full">
					<span><?php vao_e( "Type d'activité :" ); ?></span>
					<div class="checkbox-row">
						<?php
						foreach ( array( 'Grossiste', 'Détaillant', 'Supermarché' ) as $vao_value ) {
							$vao_check( "Type d'activité", $vao_value );
						}
						$vao_other( "Type d'activité", vao_t( "Type d'activité :" ) );
						?>
					</div>
				</div>
				<div class="form-group full"><label for="e-raison"><?php vao_e( 'Raison sociale :' ); ?></label><input id="e-raison" type="text" name="vao[Raison sociale]"></div>
				<div class="form-row">
					<?php $vao_text( 'e-ville', 'Ville / Région :', 'Ville / Région' ); ?>
					<?php $vao_text( 'e-adresse', 'Adresse complète :', 'Adresse' ); ?>
				</div>

				<div class="form-section-title"><?php vao_e( "3. Demande d'échantillon" ); ?></div>
				<div class="form-group full">
					<span><?php vao_e( 'Produits souhaités :' ); ?></span>
					<div class="checkbox-row">
						<?php
						foreach ( array( 'Savon de ménage', 'Savon de toilette', 'Détergent poudre', 'Détergent barre' ) as $vao_value ) {
							$vao_check( 'Produits souhaités', $vao_value );
						}
						$vao_other( 'Produits souhaités', vao_t( 'Produits souhaités :' ) );
						?>
					</div>
				</div>
				<div class="form-group full">
					<span><?php vao_e( 'Quantité souhaitée :' ); ?></span>
					<div class="checkbox-row">
						<?php
						foreach ( array( 'Test (petit volume)', 'Évaluation commerciale' ) as $vao_value ) {
							$vao_check( 'Quantité souhaitée', $vao_value );
						}
						$vao_other( 'Quantité souhaitée', vao_t( 'Quantité souhaitée :' ) );
						?>
					</div>
				</div>
				<div class="form-group full">
					<span><?php vao_e( 'Objectif de la demande :' ); ?></span>
					<div class="checkbox-row">
						<?php
						foreach ( array( 'Test (petit volume)', 'Évaluation commerciale' ) as $vao_value ) {
							$vao_check( 'Objectif de la demande', $vao_value );
						}
						$vao_other( 'Objectif de la demande', vao_t( 'Objectif de la demande :' ) );
						?>
					</div>
				</div>

				<div class="form-section-title"><?php vao_e( '4. Potentiel commercial (important pour qualification)' ); ?></div>
				<div class="form-group full"><label for="e-volume"><?php vao_e( 'Volume estimé mensuel :' ); ?></label><input id="e-volume" type="text" name="vao[Volume estimé mensuel]"></div>
				<div class="form-group full"><label for="e-zone"><?php vao_e( 'Zone de distribution prévue :' ); ?></label><input id="e-zone" type="text" name="vao[Zone de distribution prévue]"></div>

				<div class="form-section-title"><?php vao_e( '5. Message complémentaire' ); ?></div>
				<div class="form-group full"><label for="e-message" class="visually-hidden"><?php vao_e( 'Message complémentaire' ); ?></label><textarea id="e-message" name="vao[Message]"></textarea></div>

				<button type="submit" class="submit-btn"><?php vao_e( 'Envoyer' ); ?></button>
			</form>
		</div>

		<!-- CANDIDATURE SPONTANÉE -->
		<div class="form-wrapper" id="panel-candidature" hidden>
			<div class="form-image">
				<img src="<?php echo esc_url( vao_mod( 'form_img_candidature' ) ); ?>" alt="<?php echo esc_attr( vao_t( 'Candidature spontanée' ) ); ?>">
			</div>

			<form class="form-content" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" enctype="multipart/form-data">
				<?php vao_form_fields( 'candidature' ); ?>
				<?php vao_form_notice( 'candidature' ); ?>

				<div class="form-section-title"><?php vao_e( '1. Informations personnelles' ); ?></div>
				<div class="form-row">
					<?php $vao_text( 'c-nom', 'Nom *', 'Nom', 'text', true ); ?>
					<?php $vao_text( 'c-prenom', 'Prénom *', 'Prénom', 'text', true ); ?>
				</div>
				<div class="form-row">
					<?php $vao_text( 'c-email', 'E-mail *', 'E-mail', 'email', true ); ?>
					<?php $vao_text( 'c-telephone', 'Téléphone *', 'Téléphone', 'tel', true ); ?>
				</div>
				<div class="form-group full"><label for="c-adresse"><?php vao_e( 'Adresse :' ); ?></label><input id="c-adresse" type="text" name="vao[Adresse]"></div>
				<div class="form-row">
					<?php $vao_text( 'c-ville', 'Ville :', 'Ville' ); ?>
					<?php $vao_text( 'c-pays', 'Pays :', 'Pays' ); ?>
				</div>

				<div class="form-section-title"><?php vao_e( '2. Profil professionnel' ); ?></div>
				<div class="form-group full"><label for="c-poste"><?php vao_e( 'Poste souhaité :' ); ?></label><input id="c-poste" type="text" name="vao[Poste souhaité]"></div>
				<div class="form-group full"><label for="c-domaine"><?php vao_e( "Domaine d'expertise :" ); ?></label><input id="c-domaine" type="text" name="vao[Domaine d'expertise]"></div>
				<div class="form-group full"><label for="c-experience"><?php vao_e( "Années d'expérience :" ); ?></label><input id="c-experience" type="text" name="vao[Années d'expérience]"></div>
				<div class="form-group full"><label for="c-dispo"><?php vao_e( 'Disponibilité :' ); ?></label><input id="c-dispo" type="text" name="vao[Disponibilité]"></div>

				<div class="form-section-title"><?php vao_e( '3. Motivation' ); ?></div>
				<div class="form-group full"><label for="c-motivation"><?php vao_e( 'Pourquoi souhaitez-vous rejoindre SDOI ?' ); ?></label><textarea id="c-motivation" name="vao[Motivation]"></textarea></div>

				<div class="form-section-title"><?php vao_e( '4. Pièces jointes' ); ?></div>
				<div class="form-group full"><span><?php vao_e( 'Veuillez joindre les documents suivants :' ); ?></span></div>
				<div class="checkbox-row">
					<?php
					foreach ( array( 'CV', 'Lettre de motivation', 'Autres' ) as $vao_value ) {
						$vao_check( 'Pièces fournies', $vao_value );
					}
					?>
				</div>
				<label class="file-upload" for="c-file">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
						<path d="M12 2v14m-6-6 6-6 6 6M4 21h16"/>
					</svg>
					<?php vao_e( 'Importer un fichier' ); ?>
				</label>
				<input id="c-file" type="file" name="vao_file" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" hidden>

				<button type="submit" class="submit-btn"><?php vao_e( 'Envoyer' ); ?></button>
			</form>
		</div>

		<a class="side-arrow" id="arrow-next" href="#candidature" aria-label="<?php echo esc_attr( vao_t( 'Suivant' ) ); ?>">❯❯</a>
	</div>

	<div class="products-section">
		<div class="product-badge"><img src="<?php echo esc_url( vao_mod( 'form_badge' ) ); ?>" alt="Savon de Marseille VAO"></div>
	</div>
</section>

<?php
get_footer();
