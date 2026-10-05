<?php
/**
 * Pied de page du site. Textes, coordonnées et liens : Apparence > Personnaliser > Thème VAO.
 */

$vao_fb_icon = '<svg class="fb-icon" viewBox="0 0 24 24" fill="black" aria-hidden="true"><path d="M22 12a10 10 0 1 0-11.6 9.9v-7H7.9V12h2.5V9.8c0-2.5 1.5-3.9 3.8-3.9 1.1 0 2.2.2 2.2.2v2.5h-1.3c-1.2 0-1.6.8-1.6 1.6V12h2.8l-.4 2.9h-2.4v7A10 10 0 0 0 22 12"/></svg>';
$vao_phone   = vao_mod( 'telephone' );
$vao_email   = vao_mod( 'email' );
?>
<footer class="site-footer" id="contact">
	<div class="footer-container">
		<p class="footer-tagline"><?php echo esc_html( vao_mod( 'footer_tagline' ) ); ?></p>
		<div class="footer-main">
			<div class="footer-sdoi">
				<img src="<?php echo esc_url( vao_mod( 'logo_sdoi' ) ); ?>" alt="SDOI">
			</div>
			<div>
				<h4><?php vao_e( 'Notre siège' ); ?></h4>
				<p><?php echo esc_html( vao_mod( 'siege' ) ); ?></p>
			</div>
			<div>
				<h4><?php vao_e( 'Service client' ); ?></h4>
				<p><a href="<?php echo esc_url( vao_tel_link( $vao_phone ) ); ?>"><?php echo esc_html( $vao_phone ); ?></a></p>
			</div>
			<div>
				<h4><?php vao_e( 'Contact' ); ?></h4>
				<p><a href="<?php echo esc_url( 'mailto:' . $vao_email ); ?>"><?php echo esc_html( $vao_email ); ?></a></p>
			</div>
			<div class="footer-vao-logo">
				<img src="<?php echo esc_url( vao_mod( 'logo_vao' ) ); ?>" alt="VAO">
			</div>
		</div>
		<div class="footer-social">
			<?php for ( $vao_i = 1; $vao_i <= 3; $vao_i++ ) : ?>
				<?php $vao_link = vao_mod( "fb{$vao_i}_url" ); ?>
				<?php if ( $vao_link ) : ?>
					<a href="<?php echo esc_url( $vao_link ); ?>" target="_blank" rel="noopener noreferrer"><?php echo $vao_fb_icon; // SVG statique. ?> <?php echo esc_html( vao_mod( "fb{$vao_i}_nom" ) ); ?></a>
				<?php endif; ?>
			<?php endfor; ?>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
