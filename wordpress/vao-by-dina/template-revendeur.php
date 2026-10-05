<?php
/**
 * Template Name: Formulaire - Devenir revendeur
 */

get_header();
?>

<section class="theme-section">
	<div class="tabs">
		<div class="tab active">DEVENIR REVENDEUR</div>
		<a class="tab" href="<?php echo esc_url( vao_url( 'formulaires', 'echantillon' ) ); ?>">DEMANDE D'ÉCHANTILLON</a>
		<a class="tab" href="<?php echo esc_url( vao_url( 'formulaires', 'candidature' ) ); ?>">CANDIDATURE SPONTANÉE – SDOI</a>
	</div>

	<div class="form-shell">
		<a class="side-arrow" href="<?php echo esc_url( vao_url( 'formulaires', 'candidature' ) ); ?>" aria-label="Précédent : Candidature spontanée">❮❮</a>

		<div class="form-wrapper">
			<div class="form-image">
				<img src="<?php echo esc_url( vao_mod( 'form_img_revendeur' ) ); ?>" alt="Devenir revendeur VAO">
			</div>

			<form class="form-content" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" enctype="multipart/form-data">
				<?php vao_form_fields( 'revendeur' ); ?>
				<?php vao_form_notice( 'revendeur' ); ?>

				<div class="form-section-title">1. Informations personnelles</div>
				<div class="form-row">
					<div class="form-group"><label for="f-raison-sociale">Nom complet ou Raison sociale :</label><input id="f-raison-sociale" type="text" name="vao[Nom complet ou Raison sociale]"></div>
					<div class="form-group"><label for="f-responsable">Nom du responsable :</label><input id="f-responsable" type="text" name="vao[Nom du responsable]"></div>
				</div>
				<div class="form-row">
					<div class="form-group"><label for="f-email">E-mail :</label><input id="f-email" type="email" name="vao[E-mail]"></div>
					<div class="form-group"><label for="f-telephone">Téléphone :</label><input id="f-telephone" type="tel" name="vao[Téléphone]"></div>
				</div>

				<div class="form-section-title">2. Localisation</div>
				<div class="form-group full"><label for="f-adresse">Adresse exacte :</label><input id="f-adresse" type="text" name="vao[Adresse]"></div>
				<div class="form-row">
					<div class="form-group"><label for="f-ville">Ville :</label><input id="f-ville" type="text" name="vao[Ville]"></div>
					<div class="form-group"><label for="f-pays">Pays :</label><input id="f-pays" type="text" name="vao[Pays]"></div>
				</div>

				<div class="form-section-title">3. Informations sur l'activité</div>
				<div class="form-group full">
					<span>Type d'activités :</span>
					<div class="checkbox-row">
						<label><input type="checkbox" name="vao[Type d'activités][]" value="Libre-service"> Libre-service</label>
						<label><input type="checkbox" name="vao[Type d'activités][]" value="Épicerie"> Épicerie</label>
						<label><input type="checkbox" name="vao[Type d'activités][]" value="Grossiste"> Grossiste</label>
					</div>
				</div>
				<div class="form-group full"><label for="f-magasin">Nom du magasin :</label><input id="f-magasin" type="text" name="vao[Nom du magasin]"></div>
				<div class="form-group full"><label for="f-experience">Année d'expérience :</label><input id="f-experience" type="text" name="vao[Années d'expérience]"></div>
				<div class="form-group full"><label for="f-produits">Produits d'intérêt :</label><input id="f-produits" type="text" name="vao[Produits d'intérêt]"></div>
				<div class="form-group full"><label for="f-stockage">Capacité de stockage :</label><input id="f-stockage" type="text" name="vao[Capacité de stockage]"></div>
				<div class="form-group full"><label for="f-volume">Volume estimé mensuel :</label><input id="f-volume" type="text" name="vao[Volume estimé mensuel]"></div>

				<div class="form-section-title">4. Informations légales</div>
				<div class="form-group full"><label for="f-rcs">N° RCS :</label><input id="f-rcs" type="text" name="vao[N° RCS]"></div>
				<div class="form-group full"><label for="f-nif">N° NIF :</label><input id="f-nif" type="text" name="vao[N° NIF]"></div>
				<div class="form-group full"><label for="f-stat">N° STAT :</label><input id="f-stat" type="text" name="vao[N° STAT]"></div>

				<div class="form-section-title">5. Pièces à fournir</div>
				<div class="form-group full"><span>Veuillez joindre les documents suivants :</span></div>
				<div class="checkbox-row">
					<label><input type="checkbox" name="vao[Pièces fournies][]" value="Copie NIF"> Copie NIF</label>
					<label><input type="checkbox" name="vao[Pièces fournies][]" value="Copie STAT"> Copie STAT</label>
					<label><input type="checkbox" name="vao[Pièces fournies][]" value="Copie RCS"> Copie RCS</label>
				</div>
				<label class="file-upload" for="f-file">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
						<path d="M12 2v14m-6-6 6-6 6 6M4 21h16"/>
					</svg>
					Importer un fichier
				</label>
				<input id="f-file" type="file" name="vao_file" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" hidden>

				<button type="submit" class="submit-btn">Envoyer</button>
			</form>
		</div>

		<a class="side-arrow" href="<?php echo esc_url( vao_url( 'formulaires', 'echantillon' ) ); ?>" aria-label="Suivant : Demande d'échantillon">❯❯</a>
	</div>

	<div class="products-section">
		<div class="product-badge"><img src="<?php echo esc_url( vao_mod( 'form_badge' ) ); ?>" alt="Savon de Marseille VAO"></div>
	</div>
</section>

<?php
get_footer();
