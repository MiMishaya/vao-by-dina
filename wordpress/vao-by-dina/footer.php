<?php
/**
 * Pied de page du site. Coordonnées, réseaux sociaux : Apparence > Personnaliser > Thème VAO.
 * Inscription à la newsletter : envoyée par e-mail (destinataires réglables dans Personnaliser > Formulaires).
 */

$vao_phone    = vao_mod( 'telephone' );
$vao_email    = vao_mod( 'email' );
$vao_facebook = vao_mod( 'facebook_url' );
$vao_linkedin = vao_mod( 'linkedin_url' );
?>
<footer class="site-footer">
	<svg class="footer-wave" viewBox="0 0 1440 60" preserveAspectRatio="none" aria-hidden="true"><path d="M0 40C180 10 360 0 540 18s360 42 540 30 270-38 360-30v42H0z"/></svg>
	<div class="footer-main">
		<div class="footer-brand">
			<div class="footer-logos">
				<img class="footer-logo-vao" src="<?php echo esc_url( vao_mod( 'logo_vao' ) ); ?>" alt="VAO" width="640" height="242">
				<span class="footer-logo-sdoi"><strong>SDOI</strong><small><?php vao_e( 'Savons et Détergents de l’Océan Indien' ); ?></small></span>
			</div>
			<p class="footer-siege"><?php vao_e( 'Siège social' ); ?> : <?php echo esc_html( vao_mod( 'siege' ) ); ?></p>
			<div class="footer-social">
				<?php if ( $vao_facebook ) : ?>
					<a href="<?php echo esc_url( $vao_facebook ); ?>" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
						<svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M22 12a10 10 0 1 0-11.6 9.9v-7H7.9V12h2.5V9.8c0-2.5 1.5-3.9 3.8-3.9 1.1 0 2.2.2 2.2.2v2.5h-1.3c-1.2 0-1.6.8-1.6 1.6V12h2.8l-.4 2.9h-2.4v7A10 10 0 0 0 22 12"/></svg>
					</a>
				<?php endif; ?>
				<?php if ( $vao_linkedin ) : ?>
					<a href="<?php echo esc_url( $vao_linkedin ); ?>" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn">
						<svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="11" fill="currentColor"/><path fill="#8e4fd8" d="M7.4 10h2.1v6.8H7.4zm1-3.3a1.2 1.2 0 1 1 0 2.4 1.2 1.2 0 0 1 0-2.4M10.8 10h2v.9c.3-.5 1-1.1 2.1-1.1 2.2 0 2.6 1.4 2.6 3.3v3.7h-2.1v-3.3c0-.8 0-1.8-1.1-1.8s-1.3.9-1.3 1.8v3.3h-2.1z"/></svg>
					</a>
				<?php endif; ?>
			</div>
		</div>

		<form class="footer-newsletter" id="newsletter" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<?php vao_form_fields( 'newsletter' ); ?>
			<p class="visually-hidden"><?php vao_e( 'Inscription à la newsletter' ); ?></p>
			<?php vao_form_notice( 'newsletter' ); ?>
			<div class="footer-newsletter-grid">
				<input type="text" name="vao[Nom]" placeholder="<?php echo esc_attr( vao_t( 'Nom' ) ); ?>" aria-label="<?php echo esc_attr( vao_t( 'Nom' ) ); ?>" autocomplete="name" required>
				<input type="email" name="vao[E-mail]" placeholder="<?php echo esc_attr( vao_t( 'Mail' ) ); ?>" aria-label="<?php echo esc_attr( vao_t( 'Mail' ) ); ?>" autocomplete="email" required>
				<input type="tel" name="vao[Téléphone]" placeholder="<?php echo esc_attr( vao_t( 'Téléphone' ) ); ?>" aria-label="<?php echo esc_attr( vao_t( 'Téléphone' ) ); ?>" autocomplete="tel">
				<label class="footer-consent">
					<input type="checkbox" name="vao[Consentement]" value="Oui" required>
					<a href="<?php echo esc_url( vao_url( 'politique-de-confidentialite' ) ); ?>"><?php vao_e( "J'accepte la politique de confidentialité" ); ?></a>
				</label>
			</div>
			<button type="submit" class="footer-submit"><?php vao_e( "S'abonner" ); ?></button>
		</form>

		<ul class="footer-links">
			<?php foreach ( vao_nav() as $vao_key => $vao_item ) : ?>
				<li><a href="<?php echo esc_url( 'gammes' === $vao_key ? vao_via_loading( $vao_item[1] ) : $vao_item[1] ); ?>"><?php vao_e( $vao_item[0] ); ?></a></li>
			<?php endforeach; ?>
		</ul>
	</div>

	<div class="footer-bottom">
		<p>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php vao_e( 'Savons et Détergents de l’Océan Indien' ); ?></p>
		<p class="footer-contact">
			<a href="<?php echo esc_url( vao_tel_link( $vao_phone ) ); ?>">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2"/></svg>
				<?php echo esc_html( $vao_phone ); ?>
			</a>
			<a href="<?php echo esc_url( 'mailto:' . $vao_email ); ?>">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>
				<?php echo esc_html( $vao_email ); ?>
			</a>
		</p>
		<p class="footer-legal">
			<a href="<?php echo esc_url( vao_url( 'politique-de-confidentialite' ) ); ?>"><?php vao_e( 'Politique de confidentialité' ); ?></a>
			<span aria-hidden="true">|</span>
			<a href="<?php echo esc_url( vao_url( 'conditions-generales' ) ); ?>"><?php vao_e( 'Conditions générales' ); ?></a>
		</p>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
