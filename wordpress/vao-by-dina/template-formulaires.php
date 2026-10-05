<?php
/**
 * Template Name: Formulaires - Échantillon et candidature
 *
 * Deux formulaires en onglets : #echantillon (par défaut) et #candidature.
 */

get_header();
?>

<section class="theme-section">
	<div class="tabs">
		<a class="tab" href="<?php echo esc_url( vao_url( 'devenir-revendeur' ) ); ?>">DEVENIR REVENDEUR</a>
		<button type="button" class="tab active" id="tab-echantillon" aria-pressed="true">DEMANDE D'ÉCHANTILLON</button>
		<button type="button" class="tab" id="tab-candidature" aria-pressed="false">CANDIDATURE SPONTANÉE – SDOI</button>
	</div>

	<div class="form-shell">
		<a class="side-arrow" id="arrow-prev" href="<?php echo esc_url( vao_url( 'devenir-revendeur' ) ); ?>" aria-label="Précédent">❮❮</a>

		<!-- DEMANDE D'ÉCHANTILLON -->
		<div class="form-wrapper" id="panel-echantillon">
			<div class="form-image">
				<img src="<?php echo esc_url( vao_mod( 'form_img_echantillon' ) ); ?>" alt="Demande d'échantillon VAO">
			</div>

			<form class="form-content" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<?php vao_form_fields( 'echantillon' ); ?>
				<?php vao_form_notice( 'echantillon' ); ?>

				<div class="form-section-title">1. Informations personnelles</div>
				<div class="form-row">
					<div class="form-group"><label for="e-nom">Nom :</label><input id="e-nom" type="text" name="vao[Nom]"></div>
					<div class="form-group"><label for="e-prenom">Prénom :</label><input id="e-prenom" type="text" name="vao[Prénom]"></div>
				</div>
				<div class="form-row">
					<div class="form-group"><label for="e-email">E-mail :</label><input id="e-email" type="email" name="vao[E-mail]"></div>
					<div class="form-group"><label for="e-telephone">Téléphone :</label><input id="e-telephone" type="tel" name="vao[Téléphone]"></div>
				</div>

				<div class="form-section-title">2. Informations professionnelles</div>
				<div class="form-group full"><label for="e-entreprise">Nom de l'entreprise / magasin :</label><input id="e-entreprise" type="text" name="vao[Entreprise / magasin]"></div>
				<div class="form-group full">
					<span>Type d'activité :</span>
					<div class="checkbox-row">
						<label><input type="checkbox" name="vao[Type d'activité][]" value="Grossiste"> Grossiste</label>
						<label><input type="checkbox" name="vao[Type d'activité][]" value="Détaillant"> Détaillant</label>
						<label><input type="checkbox" name="vao[Type d'activité][]" value="Supermarché"> Supermarché</label>
						<span class="inline-other"><input type="checkbox" name="vao[Type d'activité][]" value="Autre" aria-label="Autre"> Autre : <input type="text" name="vao[Type d'activité (autre)]" aria-label="Autre type d'activité"></span>
					</div>
				</div>
				<div class="form-group full"><label for="e-raison">Raison sociale :</label><input id="e-raison" type="text" name="vao[Raison sociale]"></div>
				<div class="form-row">
					<div class="form-group"><label for="e-ville">Ville / Région :</label><input id="e-ville" type="text" name="vao[Ville / Région]"></div>
					<div class="form-group"><label for="e-adresse">Adresse complète :</label><input id="e-adresse" type="text" name="vao[Adresse]"></div>
				</div>

				<div class="form-section-title">3. Demande d'échantillon</div>
				<div class="form-group full">
					<span>Produits souhaités :</span>
					<div class="checkbox-row">
						<label><input type="checkbox" name="vao[Produits souhaités][]" value="Savon de ménage"> Savon de ménage</label>
						<label><input type="checkbox" name="vao[Produits souhaités][]" value="Savon de toilette"> Savon de toilette</label>
						<label><input type="checkbox" name="vao[Produits souhaités][]" value="Détergent poudre"> Détergent poudre</label>
						<label><input type="checkbox" name="vao[Produits souhaités][]" value="Détergent barre"> Détergent barre</label>
						<span class="inline-other"><input type="checkbox" name="vao[Produits souhaités][]" value="Autre" aria-label="Autre"> Autre : <input type="text" name="vao[Produits souhaités (autre)]" aria-label="Autre produit"></span>
					</div>
				</div>
				<div class="form-group full">
					<span>Quantité souhaitée :</span>
					<div class="checkbox-row">
						<label><input type="checkbox" name="vao[Quantité souhaitée][]" value="Test (petit volume)"> Test (petit volume)</label>
						<label><input type="checkbox" name="vao[Quantité souhaitée][]" value="Évaluation commerciale"> Évaluation commerciale</label>
						<span class="inline-other"><input type="checkbox" name="vao[Quantité souhaitée][]" value="Autre" aria-label="Autre"> Autre : <input type="text" name="vao[Quantité souhaitée (autre)]" aria-label="Autre quantité"></span>
					</div>
				</div>
				<div class="form-group full">
					<span>Objectif de la demande :</span>
					<div class="checkbox-row">
						<label><input type="checkbox" name="vao[Objectif de la demande][]" value="Test (petit volume)"> Test (petit volume)</label>
						<label><input type="checkbox" name="vao[Objectif de la demande][]" value="Évaluation commerciale"> Évaluation commerciale</label>
						<span class="inline-other"><input type="checkbox" name="vao[Objectif de la demande][]" value="Autre" aria-label="Autre"> Autre : <input type="text" name="vao[Objectif de la demande (autre)]" aria-label="Autre objectif"></span>
					</div>
				</div>

				<div class="form-section-title">4. Potentiel commercial (important pour qualification)</div>
				<div class="form-group full"><label for="e-volume">Volume estimé mensuel :</label><input id="e-volume" type="text" name="vao[Volume estimé mensuel]"></div>
				<div class="form-group full"><label for="e-zone">Zone de distribution prévue :</label><input id="e-zone" type="text" name="vao[Zone de distribution prévue]"></div>

				<div class="form-section-title">5. Message complémentaire</div>
				<div class="form-group full"><label for="e-message" class="visually-hidden">Message complémentaire</label><textarea id="e-message" name="vao[Message]"></textarea></div>

				<button type="submit" class="submit-btn">Envoyer</button>
			</form>
		</div>

		<!-- CANDIDATURE SPONTANÉE -->
		<div class="form-wrapper" id="panel-candidature" hidden>
			<div class="form-image">
				<img src="<?php echo esc_url( vao_mod( 'form_img_candidature' ) ); ?>" alt="Candidature spontanée SDOI">
			</div>

			<form class="form-content" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" enctype="multipart/form-data">
				<?php vao_form_fields( 'candidature' ); ?>
				<?php vao_form_notice( 'candidature' ); ?>

				<div class="form-section-title">1. Informations personnelles</div>
				<div class="form-row">
					<div class="form-group"><label for="c-nom">Nom *</label><input id="c-nom" type="text" name="vao[Nom]" required></div>
					<div class="form-group"><label for="c-prenom">Prénom *</label><input id="c-prenom" type="text" name="vao[Prénom]" required></div>
				</div>
				<div class="form-row">
					<div class="form-group"><label for="c-email">E-mail *</label><input id="c-email" type="email" name="vao[E-mail]" required></div>
					<div class="form-group"><label for="c-telephone">Téléphone *</label><input id="c-telephone" type="tel" name="vao[Téléphone]" required></div>
				</div>
				<div class="form-group full"><label for="c-adresse">Adresse :</label><input id="c-adresse" type="text" name="vao[Adresse]"></div>
				<div class="form-row">
					<div class="form-group"><label for="c-ville">Ville :</label><input id="c-ville" type="text" name="vao[Ville]"></div>
					<div class="form-group"><label for="c-pays">Pays :</label><input id="c-pays" type="text" name="vao[Pays]"></div>
				</div>

				<div class="form-section-title">2. Profil professionnel</div>
				<div class="form-group full"><label for="c-poste">Poste souhaité :</label><input id="c-poste" type="text" name="vao[Poste souhaité]"></div>
				<div class="form-group full"><label for="c-domaine">Domaine d'expertise :</label><input id="c-domaine" type="text" name="vao[Domaine d'expertise]"></div>
				<div class="form-group full"><label for="c-experience">Années d'expérience :</label><input id="c-experience" type="text" name="vao[Années d'expérience]"></div>
				<div class="form-group full"><label for="c-dispo">Disponibilité :</label><input id="c-dispo" type="text" name="vao[Disponibilité]"></div>

				<div class="form-section-title">3. Motivation</div>
				<div class="form-group full"><label for="c-motivation">Pourquoi souhaitez-vous rejoindre SDOI ?</label><textarea id="c-motivation" name="vao[Motivation]"></textarea></div>

				<div class="form-section-title">4. Pièces jointes</div>
				<div class="form-group full"><span>Veuillez joindre les documents suivants :</span></div>
				<div class="checkbox-row">
					<label><input type="checkbox" name="vao[Pièces fournies][]" value="CV"> CV</label>
					<label><input type="checkbox" name="vao[Pièces fournies][]" value="Lettre de motivation"> Lettre de motivation</label>
					<label><input type="checkbox" name="vao[Pièces fournies][]" value="Autres"> Autres</label>
				</div>
				<label class="file-upload" for="c-file">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
						<path d="M12 2v14m-6-6 6-6 6 6M4 21h16"/>
					</svg>
					Importer un fichier
				</label>
				<input id="c-file" type="file" name="vao_file" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" hidden>

				<button type="submit" class="submit-btn">Envoyer</button>
			</form>
		</div>

		<a class="side-arrow" id="arrow-next" href="#candidature" aria-label="Suivant">❯❯</a>
	</div>

	<div class="products-section">
		<div class="product-badge"><img src="<?php echo esc_url( vao_mod( 'form_badge' ) ); ?>" alt="Savon de Marseille VAO"></div>
	</div>
</section>

<?php
get_footer();
