<?php
/**
 * Template Name: Formulaire - Devenir revendeur
 *
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
?>

<section class="theme-section">
	<div class="tabs">
		<div class="tab active"><?php vao_e( 'DEVENIR REVENDEUR' ); ?></div>
		<a class="tab" href="<?php echo esc_url( vao_url( 'formulaires', 'echantillon' ) ); ?>"><?php vao_e( "DEMANDE D'ÉCHANTILLON" ); ?></a>
		<a class="tab" href="<?php echo esc_url( vao_url( 'formulaires', 'candidature' ) ); ?>"><?php vao_e( 'CANDIDATURE SPONTANÉE – SDOI' ); ?></a>
	</div>

	<div class="form-shell">
		<a class="side-arrow" href="<?php echo esc_url( vao_url( 'formulaires', 'candidature' ) ); ?>" aria-label="<?php echo esc_attr( vao_t( 'Précédent' ) . ' : ' . vao_t( 'Candidature spontanée' ) ); ?>">❮❮</a>

		<div class="form-wrapper">
			<div class="form-image">
				<img src="<?php echo esc_url( vao_mod( 'form_img_revendeur' ) ); ?>" alt="<?php echo esc_attr( vao_t( 'Devenir revendeur' ) ); ?>">
			</div>

			<form class="form-content" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" enctype="multipart/form-data">
				<?php vao_form_fields( 'revendeur' ); ?>
				<?php vao_form_notice( 'revendeur' ); ?>

				<div class="form-section-title"><?php vao_e( '1. Informations personnelles' ); ?></div>
				<div class="form-row">
					<div class="form-group"><label for="f-raison-sociale"><?php vao_e( 'Nom complet ou Raison sociale :' ); ?></label><input id="f-raison-sociale" type="text" name="vao[Nom complet ou Raison sociale]"></div>
					<div class="form-group"><label for="f-responsable"><?php vao_e( 'Nom du responsable :' ); ?></label><input id="f-responsable" type="text" name="vao[Nom du responsable]"></div>
				</div>
				<div class="form-row">
					<div class="form-group"><label for="f-email"><?php vao_e( 'E-mail :' ); ?></label><input id="f-email" type="email" name="vao[E-mail]"></div>
					<div class="form-group"><label for="f-telephone"><?php vao_e( 'Téléphone :' ); ?></label><input id="f-telephone" type="tel" name="vao[Téléphone]"></div>
				</div>

				<div class="form-section-title"><?php vao_e( '2. Localisation' ); ?></div>
				<div class="form-group full"><label for="f-adresse"><?php vao_e( 'Adresse exacte :' ); ?></label><input id="f-adresse" type="text" name="vao[Adresse]"></div>
				<div class="form-row">
					<div class="form-group"><label for="f-ville"><?php vao_e( 'Ville :' ); ?></label><input id="f-ville" type="text" name="vao[Ville]"></div>
					<div class="form-group"><label for="f-pays"><?php vao_e( 'Pays :' ); ?></label><input id="f-pays" type="text" name="vao[Pays]"></div>
				</div>

				<div class="form-section-title"><?php vao_e( "3. Informations sur l'activité" ); ?></div>
				<div class="form-group full">
					<span><?php vao_e( "Type d'activités :" ); ?></span>
					<div class="checkbox-row">
						<?php
						foreach ( array( 'Libre-service', 'Épicerie', 'Grossiste' ) as $vao_value ) {
							$vao_check( "Type d'activités", $vao_value );
						}
						?>
					</div>
				</div>
				<div class="form-group full"><label for="f-magasin"><?php vao_e( 'Nom du magasin :' ); ?></label><input id="f-magasin" type="text" name="vao[Nom du magasin]"></div>
				<div class="form-group full"><label for="f-experience"><?php vao_e( "Année d'expérience :" ); ?></label><input id="f-experience" type="text" name="vao[Années d'expérience]"></div>
				<div class="form-group full"><label for="f-produits"><?php vao_e( "Produits d'intérêt :" ); ?></label><input id="f-produits" type="text" name="vao[Produits d'intérêt]"></div>
				<div class="form-group full"><label for="f-stockage"><?php vao_e( 'Capacité de stockage :' ); ?></label><input id="f-stockage" type="text" name="vao[Capacité de stockage]"></div>
				<div class="form-group full"><label for="f-volume"><?php vao_e( 'Volume estimé mensuel :' ); ?></label><input id="f-volume" type="text" name="vao[Volume estimé mensuel]"></div>

				<div class="form-section-title"><?php vao_e( '4. Informations légales' ); ?></div>
				<div class="form-group full"><label for="f-rcs"><?php vao_e( 'N° RCS :' ); ?></label><input id="f-rcs" type="text" name="vao[N° RCS]"></div>
				<div class="form-group full"><label for="f-nif"><?php vao_e( 'N° NIF :' ); ?></label><input id="f-nif" type="text" name="vao[N° NIF]"></div>
				<div class="form-group full"><label for="f-stat"><?php vao_e( 'N° STAT :' ); ?></label><input id="f-stat" type="text" name="vao[N° STAT]"></div>

				<div class="form-section-title"><?php vao_e( '5. Pièces à fournir' ); ?></div>
				<div class="form-group full"><span><?php vao_e( 'Veuillez joindre les documents suivants :' ); ?></span></div>
				<div class="checkbox-row">
					<?php
					foreach ( array( 'Copie NIF', 'Copie STAT', 'Copie RCS' ) as $vao_value ) {
						$vao_check( 'Pièces fournies', $vao_value );
					}
					?>
				</div>
				<label class="file-upload" for="f-file">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
						<path d="M12 2v14m-6-6 6-6 6 6M4 21h16"/>
					</svg>
					<?php vao_e( 'Importer un fichier' ); ?>
				</label>
				<input id="f-file" type="file" name="vao_file" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" hidden>

				<button type="submit" class="submit-btn"><?php vao_e( 'Envoyer' ); ?></button>
			</form>
		</div>

		<a class="side-arrow" href="<?php echo esc_url( vao_url( 'formulaires', 'echantillon' ) ); ?>" aria-label="<?php echo esc_attr( vao_t( 'Suivant' ) . ' : ' . vao_t( "Demande d'échantillon" ) ); ?>">❯❯</a>
	</div>

	<div class="products-section">
		<div class="product-badge"><img src="<?php echo esc_url( vao_mod( 'form_badge' ) ); ?>" alt="Savon de Marseille VAO"></div>
	</div>
</section>

<?php
get_footer();
