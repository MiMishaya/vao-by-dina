<?php
/**
 * Template Name: Qui sommes-nous
 *
 * Présentation de SDOI : chiffres clés, distributeurs, gage de qualité, valeurs,
 * témoignages des collaborateurs, actions RSE, marques et visuels.
 * Images : Apparence > Personnaliser > Thème VAO > Page Qui sommes-nous.
 * Distributeurs, collaborateurs et actions RSE : menus du même nom dans l'admin.
 * Textes : inc/strings/apropos.php (traduction malagasy dans Langues > Traductions des chaînes).
 */

get_header();

$vao_icons = array(
	'truck'    => '<path d="M1.5 6h12v10h-12z"/><path d="M13.5 9.5h4.2l3.3 3.6V16h-7.5z"/><circle cx="5.5" cy="17.5" r="1.9"/><circle cx="17" cy="17.5" r="1.9"/>',
	'boxes'    => '<rect x="2.5" y="12" width="9" height="8.5" rx="1"/><rect x="12.5" y="12" width="9" height="8.5" rx="1"/><rect x="7.5" y="3.5" width="9" height="8.5" rx="1"/><path d="M5.5 12v3M15.5 12v3M10.5 3.5v3"/>',
	'shop'     => '<path d="M4 4h16l1.5 5h-19z"/><path d="M2.5 9a3.2 3.2 0 0 0 6.3 0 3.2 3.2 0 0 0 6.4 0 3.2 3.2 0 0 0 6.3 0"/><path d="M4.5 11.5V20h15v-8.5"/><path d="M10 20v-5h4v5"/>',
	'shield'   => '<path d="M12 2.8 4.5 5.8v6c0 4.6 3.2 8.2 7.5 9.4 4.3-1.2 7.5-4.8 7.5-9.4v-6z"/><path d="m8.6 12.2 2.4 2.4 4.6-4.8"/>',
	'sparkle'  => '<path fill="currentColor" stroke="none" d="M10.5 3.5c.7 4.6 2.9 6.8 7.5 7.5-4.6.7-6.8 2.9-7.5 7.5-.7-4.6-2.9-6.8-7.5-7.5 4.6-.7 6.8-2.9 7.5-7.5z"/><circle cx="19.2" cy="4.6" r="1.9"/>',
	'pin'      => '<path d="M12 21.5s-7-6.3-7-11.8a7 7 0 0 1 14 0c0 5.5-7 11.8-7 11.8z"/><circle cx="12" cy="9.7" r="2.8"/>',
	'heart'    => '<path d="M12 20.5s-8-4.8-9.4-10.1C1.7 6.9 4 3.8 7.4 3.8c2 0 3.6 1.1 4.6 2.6 1-1.5 2.6-2.6 4.6-2.6 3.4 0 5.7 3.1 4.8 6.6C20 15.7 12 20.5 12 20.5z"/><path d="m8.5 12 2.5 2.5 4.5-4.5"/>',
	'team'     => '<circle cx="8.5" cy="7.5" r="3.3"/><circle cx="16.2" cy="7.5" r="3.3"/><path d="M2.5 20c0-3.7 2.7-6.2 6-6.2s6 2.5 6 6.2"/><path d="M14.3 14c.6-.1 1.2-.2 1.9-.2 3.3 0 5.3 2.5 5.3 6.2"/>',
	'leaf'     => '<path d="M5 19.5C3.8 11 9 4 20.5 3.5 20.8 15 14 20.6 5 19.5z"/><path d="M5 19.5 14.5 10"/>',
	'chevron'  => '<path d="m5 9 7 7 7-7"/>',
);
$vao_icon = function ( $name, $class = '' ) use ( $vao_icons ) {
	printf(
		'<svg class="%s" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">%s</svg>',
		esc_attr( $class ),
		$vao_icons[ $name ] // SVG statique défini ci-dessus.
	);
};

/*
 * Distributeurs : les villes sont empilées dans des colonnes d'au plus $vao_max_lines lignes
 * (titre et espacement compris), comme sur la maquette ; une ville trop longue continue
 * dans la colonne suivante.
 */
$vao_max_lines = 16;
$vao_columns   = array();
$vao_column    = array();
$vao_used      = 0;
foreach ( vao_items( 'vao_distributeur' ) as $vao_ville ) {
	$vao_points = array();
	foreach ( preg_split( '/\r\n|\r|\n/', (string) get_post_meta( $vao_ville->ID, '_vao_points', true ) ) as $vao_line ) {
		// Les tirets sont ajoutés à l'affichage : on retire ceux saisis dans l'admin.
		$vao_line = trim( preg_replace( '/^[\s\-–•]+/u', '', $vao_line ) );
		if ( '' !== $vao_line ) {
			$vao_points[] = $vao_line;
		}
	}
	$vao_chunks = $vao_points ? array_chunk( $vao_points, $vao_max_lines - 2 ) : array( array() );
	foreach ( $vao_chunks as $vao_k => $vao_chunk ) {
		$vao_need = count( $vao_chunk ) + 2;
		if ( $vao_column && $vao_used + $vao_need > $vao_max_lines ) {
			$vao_columns[] = $vao_column;
			$vao_column    = array();
			$vao_used      = 0;
		}
		$vao_column[] = array(
			'title'  => get_the_title( $vao_ville ),
			'points' => $vao_chunk,
			'cont'   => $vao_k > 0,
		);
		$vao_used    += $vao_need;
	}
}
if ( $vao_column ) {
	$vao_columns[] = $vao_column;
}
$vao_column_colors = array( 'sky', 'green', 'orange' );

/*
 * Témoignages : le bas des cartes suit le dégradé violet → rose → orange,
 * réparti sur les 4 cartes visibles côte à côte sur ordinateur.
 */
$vao_collaborateurs = vao_items( 'vao_collaborateur' );
$vao_mix            = function ( $t ) {
	$stops = array( array( 0x8a, 0x3c, 0xcf ), array( 0xe0, 0x33, 0x8a ), array( 0xf2, 0x7b, 0x2c ) );
	$pos   = max( 0, min( 1, $t ) ) * ( count( $stops ) - 1 );
	$k     = min( (int) floor( $pos ), count( $stops ) - 2 );
	$f     = $pos - $k;
	$rgb   = array();
	for ( $c = 0; $c < 3; $c++ ) {
		$rgb[] = (int) round( $stops[ $k ][ $c ] + ( $stops[ $k + 1 ][ $c ] - $stops[ $k ][ $c ] ) * $f );
	}
	return vsprintf( '#%02x%02x%02x', $rgb );
};
$vao_initials = function ( $name ) {
	$out = '';
	foreach ( array_slice( preg_split( '/\s+/u', trim( $name ), -1, PREG_SPLIT_NO_EMPTY ), 0, 2 ) as $word ) {
		$out .= mb_substr( $word, 0, 1 );
	}
	return function_exists( 'mb_strtoupper' ) ? mb_strtoupper( $out ) : strtoupper( $out );
};

$vao_rse_actions = vao_items( 'vao_rse' );
?>

<main class="qsn">

	<!-- QUI SOMMES-NOUS -->
	<section class="qsn-intro container">
		<h1 class="section-title"><?php vao_e( 'Qui sommes-nous' ); ?></h1>
		<div class="qsn-intro-text">
			<p class="qsn-lead"><?php echo wp_kses_post( vao_t( '<strong>Depuis plus de 20 ans</strong>, SDOI s’inscrit comme un acteur engagé dans le développement de l’industrie de l’hygiène et de l’entretien à Madagascar. Portée par une volonté forte de valoriser le « <strong>Vita Malagasy</strong> », l’entreprise place la production locale et l’innovation industrielle au cœur de sa stratégie.' ) ); ?></p>
			<p><?php echo wp_kses_post( vao_t( 'SDOI conçoit, fabrique et commercialise une large gamme de produits essentiels du quotidien, comprenant des <strong>savons de ménage et de toilette</strong> ainsi que des <strong>détergents</strong> performants sous différentes formes <strong>(poudre et barres)</strong>. Grâce à une expertise industrielle maîtrisée, l’entreprise s’attache à proposer des solutions alliant efficacité, qualité et accessibilité pour l’ensemble des ménages malgaches.' ) ); ?></p>
			<p><?php echo wp_kses_post( vao_t( 'Au-delà de son rôle de fabricant, SDOI se positionne comme un véritable partenaire de proximité, avec une présence étendue sur tout le territoire national. L’entreprise contribue activement à la structuration du marché local et au rayonnement de l’industrie nationale.' ) ); ?></p>
			<p><?php echo wp_kses_post( vao_t( 'La mission de SDOI est de garantir aux consommateurs des <strong>produits fiables et performants</strong>, tout en participant durablement au développement économique du pays. Sa vision est de devenir une référence incontournable dans son secteur, en s’appuyant sur <strong>l’innovation, la qualité et un engagement fort</strong> envers les communautés locales.' ) ); ?></p>
		</div>
	</section>

	<!-- CHIFFRES CLÉS -->
	<section class="qsn-chiffres container" aria-labelledby="qsn-chiffres-titre">
		<h2 class="visually-hidden" id="qsn-chiffres-titre"><?php vao_e( 'Chiffres clés' ); ?></h2>
		<div class="qsn-big">
			<div class="qsn-big-item">
				<span class="qsn-num">10</span>
				<span class="qsn-big-label"><?php vao_e( 'Marques' ); ?></span>
			</div>
			<div class="qsn-big-item is-main">
				<span class="qsn-num">20<span class="qsn-ans"><?php vao_e( 'ans' ); ?></span></span>
				<span class="qsn-big-label"><?php vao_e( 'D’existence' ); ?></span>
			</div>
			<div class="qsn-big-item">
				<span class="qsn-num">500</span>
				<span class="qsn-big-label"><?php vao_e( 'Collaborateurs' ); ?></span>
			</div>
		</div>
		<div class="qsn-small">
			<div class="qsn-small-item">
				<div class="qsn-small-figure"><?php $vao_icon( 'truck', 'qsn-small-icon' ); ?><span>+&nbsp;100</span></div>
				<span class="qsn-small-label"><?php vao_e( 'Distributeurs nationaux' ); ?></span>
				<?php if ( $vao_columns ) : ?>
					<button type="button" class="qsn-distrib-toggle" aria-expanded="false" aria-controls="distributeurs" aria-label="<?php echo esc_attr( vao_t( 'Voir la liste des distributeurs' ) ); ?>">
						<?php $vao_icon( 'chevron' ); ?>
					</button>
				<?php endif; ?>
			</div>
			<div class="qsn-small-item">
				<div class="qsn-small-figure"><?php $vao_icon( 'boxes', 'qsn-small-icon' ); ?><span>+&nbsp;60</span></div>
				<span class="qsn-small-label"><?php vao_e( 'Références de produits' ); ?></span>
			</div>
			<div class="qsn-small-item">
				<div class="qsn-small-figure"><?php $vao_icon( 'shop', 'qsn-small-icon' ); ?><span>+&nbsp;3&nbsp;000</span></div>
				<span class="qsn-small-label"><?php vao_e( 'Épiceries partenaires' ); ?></span>
			</div>
		</div>
	</section>

	<?php if ( $vao_columns ) : ?>
		<!-- LISTE DES DISTRIBUTEURS (dépliée par le chevron, assets/js/apropos.js) -->
		<section class="qsn-distrib container" id="distributeurs" aria-labelledby="qsn-distrib-titre" hidden>
			<h2 class="visually-hidden" id="qsn-distrib-titre"><?php vao_e( 'Liste des distributeurs' ); ?></h2>
			<div class="vao-carousel" data-carousel>
				<div class="vao-carousel-track">
					<?php foreach ( $vao_columns as $vao_i => $vao_col ) : ?>
						<div class="qsn-distrib-col c-<?php echo esc_attr( $vao_column_colors[ $vao_i % count( $vao_column_colors ) ] ); ?>">
							<?php foreach ( $vao_col as $vao_city ) : ?>
								<div class="qsn-ville">
									<?php if ( $vao_city['cont'] ) : ?>
										<div class="qsn-ville-title" aria-hidden="true">&nbsp;</div>
									<?php else : ?>
										<h3 class="qsn-ville-title"><?php echo esc_html( $vao_city['title'] ); ?></h3>
									<?php endif; ?>
									<?php if ( $vao_city['points'] ) : ?>
										<ul>
											<?php foreach ( $vao_city['points'] as $vao_point ) : ?>
												<li><?php echo esc_html( $vao_point ); ?></li>
											<?php endforeach; ?>
										</ul>
									<?php endif; ?>
								</div>
							<?php endforeach; ?>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<!-- NOTRE GAGE DE QUALITÉ -->
	<section class="qsn-qualite" id="qualite">
		<div class="container">
			<h2 class="section-title light"><?php vao_e( 'Notre gage de qualité' ); ?></h2>
			<div class="qsn-gages">
				<?php
				$vao_gages = array(
					array( 'shield', 'Contrôle & qualité', 'Des savons de ménage aux détergents puissants (VAO Line, Lemon, Magic), chaque produit respecte des standards rigoureux pour garantir la sécurité et la santé des utilisateurs.' ),
					array( 'sparkle', 'Innovation & accessibilité', 'Une gamme sans cesse renouvelée et élargie : du format de poche aux grands conditionnements pour répondre aux exigences de propreté de chaque foyer.' ),
					array( 'pin', 'Proche de vous', 'Présents au cœur des marchés, dans les lavoirs et dans chaque épicerie, nous accompagnons les foyers au quotidien pour un entretien facile et rapide.' ),
				);
				foreach ( $vao_gages as $vao_gage ) :
					?>
					<div class="qsn-gage">
						<?php $vao_icon( $vao_gage[0], 'qsn-gage-icon' ); ?>
						<h3><?php vao_e( $vao_gage[1] ); ?></h3>
						<p><?php vao_e( $vao_gage[2] ); ?></p>
					</div>
				<?php endforeach; ?>
			</div>

			<h3 class="qsn-certif-title"><?php vao_e( 'Qualité certifiée' ); ?></h3>
			<div class="qsn-certif">
				<img src="<?php echo esc_url( vao_mod( 'label_image' ) ); ?>" alt="<?php echo esc_attr( vao_t( 'Label Malagasy Ny Antsika' ) ); ?>" width="927" height="518" loading="lazy">
				<div class="qsn-certif-text">
					<p><?php echo wp_kses_post( vao_t( 'Depuis 2021, la gamme VAO arbore fièrement le label <strong>"Malagasy Ny Antsika"</strong>. Cette distinction certifie l’origine et la transformation locale de nos produits, nous permettant de nous distinguer durablement des offres d’importation. Véritable gage de confiance pour les consommateurs, ce label garantit le respect des standards de qualité les plus stricts, validés par des audits indépendants menés par le <strong>Bureau des Normes de Madagascar</strong> (BNM).' ) ); ?></p>
					<p><?php echo wp_kses_post( vao_t( 'Au-delà de la qualité, cette labellisation incarne notre <strong>engagement envers l’économie nationale</strong>. En privilégiant les produits VAO, nos clients soutiennent directement le tissu <strong>productif local</strong>, la <strong>création d’emplois</strong> et le <strong>développement des communautés</strong> de la Grande île.' ) ); ?></p>
				</div>
			</div>
		</div>
	</section>

	<!-- NOS VALEURS -->
	<section class="qsn-valeurs container" id="valeurs">
		<h2 class="section-title"><?php vao_e( 'Nos valeurs' ); ?></h2>
		<img class="qsn-labo" src="<?php echo esc_url( vao_mod( 'labo_image' ) ); ?>" alt="<?php echo esc_attr( vao_t( 'Laboratoire de contrôle qualité' ) ); ?>" width="2482" height="1064" loading="lazy">
		<div class="qsn-valeurs-grid">
			<?php
			$vao_valeurs = array(
				array( 'heart', 'Satisfaction consommateurs', 'Mettre de la joie et de la simplicité dans vos corvées de nettoyage, c’est notre mission au quotidien. Nous nous engageons à vous offrir le meilleur de la propreté, avec des produits sûrs et fiables qui facilitent la vie de toute la famille.' ),
				array( 'team', 'Esprit d’équipe', 'Derrière chacun de nos savons et détergents, il y a avant tout des femmes et des hommes passionnés qui travaillent main dans la main. Du chimiste en laboratoire jusqu’aux équipes sur le terrain, nous formons une grande famille où règnent l’entraide, le sourire et le respect. C’est cette solidarité au quotidien qui fait notre force et nous pousse à donner le meilleur de nous-mêmes pour vous.' ),
				array( 'leaf', 'Respect de l’environnement', 'Protéger l’environnement est au cœur de nos procédés industriels. Nous réduisons l’impact de notre production en valorisant nos déchets, en préservant les ressources en eau essentielles au savonnage, et en maîtrisant notre énergie grâce au solaire et à la biomasse. La propreté chez nous commence dès la fabrication.' ),
			);
			foreach ( $vao_valeurs as $vao_valeur ) :
				?>
				<div class="qsn-valeur">
					<div class="qsn-pill">
						<?php $vao_icon( $vao_valeur[0], 'qsn-pill-icon' ); ?>
						<h3><?php vao_e( $vao_valeur[1] ); ?></h3>
					</div>
					<p><?php vao_e( $vao_valeur[2] ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>
	</section>

	<?php if ( $vao_collaborateurs ) : ?>
		<!-- TÉMOIGNAGES COLLABORATEURS -->
		<section class="qsn-collab container" id="collaborateurs">
			<h2 class="section-subtitle"><?php vao_e( 'Témoignages collaborateurs' ); ?></h2>
			<div class="vao-carousel" data-carousel>
				<div class="vao-carousel-track">
					<?php foreach ( $vao_collaborateurs as $vao_i => $vao_collab ) : ?>
						<?php
						$vao_name  = get_the_title( $vao_collab );
						$vao_photo = vao_post_img( $vao_collab, 'medium_large' );
						$vao_poste = get_post_meta( $vao_collab->ID, '_vao_poste', true );
						$vao_anc   = get_post_meta( $vao_collab->ID, '_vao_anciennete', true );
						$vao_step  = $vao_i % 4;
						?>
						<article class="qsn-collab-card" style="--c1:<?php echo esc_attr( $vao_mix( $vao_step / 4 ) ); ?>;--c2:<?php echo esc_attr( $vao_mix( ( $vao_step + 1 ) / 4 ) ); ?>">
							<?php if ( $vao_photo ) : ?>
								<img class="qsn-collab-photo" src="<?php echo esc_url( $vao_photo ); ?>" alt="<?php echo esc_attr( vao_post_alt( $vao_collab ) ); ?>" width="685" height="469" loading="lazy">
							<?php else : ?>
								<div class="qsn-collab-photo qsn-collab-avatar" aria-hidden="true"><span><?php echo esc_html( $vao_initials( $vao_name ) ); ?></span></div>
							<?php endif; ?>
							<div class="qsn-collab-body">
								<h3><?php echo esc_html( $vao_name ); ?></h3>
								<p class="qsn-collab-text"><?php echo esc_html( get_post_meta( $vao_collab->ID, '_vao_texte', true ) ); ?></p>
								<?php if ( $vao_poste || $vao_anc ) : ?>
									<p class="qsn-collab-meta">
										<?php echo esc_html( $vao_poste ); ?>
										<?php if ( $vao_poste && $vao_anc ) : ?>
											<br>
										<?php endif; ?>
										<?php echo esc_html( $vao_anc ); ?>
									</p>
								<?php endif; ?>
							</div>
						</article>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<!-- NOS ACTIONS RSE -->
	<section class="qsn-rse container" id="rse">
		<h2 class="section-title no-bar"><?php vao_e( 'Nos actions RSE en quelques images' ); ?></h2>
		<p class="section-subtitle"><?php vao_e( 'Responsabilité sociétale : au cœur de notre engagement.' ); ?></p>
		<p class="qsn-rse-intro"><?php vao_e( 'Au cœur de la vision de la direction SDOI, la solidarité et la responsabilité sociale constituent des piliers fondamentaux. Notre engagement se traduit par un programme d’actions bimestrielles pérennes, dédié à l’amélioration du bien-être des populations les plus vulnérables.' ); ?></p>

		<?php foreach ( $vao_rse_actions as $vao_action ) : ?>
			<?php
			$vao_title  = get_the_title( $vao_action );
			$vao_photos = vao_gallery( $vao_action );
			$vao_total  = count( $vao_photos );
			$vao_group  = 'rse-' . $vao_action->ID;
			?>
			<article class="qsn-rse-item">
				<h3><?php echo esc_html( $vao_title ); ?></h3>
				<div class="qsn-rse-text"><?php echo wpautop( wp_kses_post( $vao_action->post_content ) ); ?></div>
				<?php if ( $vao_photos ) : ?>
					<?php // Les photos au-delà de la 3e restent dans le DOM (masquées) pour que la galerie plein écran les parcoure toutes. ?>
					<div class="qsn-rse-grid">
						<?php foreach ( $vao_photos as $vao_n => $vao_photo ) : ?>
							<?php
							$vao_alt   = $vao_title . ' – ' . sprintf( vao_t( 'Photo %d' ), $vao_n + 1 );
							$vao_more  = ( 2 === $vao_n && $vao_total > 3 ) ? $vao_total - 3 : 0;
							$vao_extra = $vao_n > 2;
							?>
							<a class="qsn-rse-thumb<?php echo $vao_more ? ' has-more' : ''; ?>" href="<?php echo esc_url( $vao_photo['full'] ); ?>" data-lightbox="<?php echo esc_attr( $vao_group ); ?>"<?php echo $vao_extra ? ' hidden' : ''; ?>>
								<img src="<?php echo esc_url( $vao_photo['thumb'] ); ?>" alt="<?php echo esc_attr( $vao_alt ); ?>" width="1344" height="752" loading="lazy">
								<?php if ( $vao_more ) : ?>
									<span class="qsn-rse-more" aria-hidden="true">+<?php echo esc_html( $vao_more ); ?></span>
									<span class="visually-hidden"><?php echo esc_html( sprintf( vao_t( 'Voir les %d photos' ), $vao_total ) ); ?></span>
								<?php endif; ?>
							</a>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</article>
		<?php endforeach; ?>
	</section>

	<!-- DES MARQUES DE CONFIANCE -->
	<section class="qsn-marques">
		<h2 class="section-title no-bar qsn-marques-title"><?php vao_e( 'Des marques de confiance' ); ?></h2>
		<?php get_template_part( 'template-parts/brand-strip' ); ?>
	</section>

	<!-- VISUELS -->
	<div class="qsn-pubs container">
		<?php for ( $vao_p = 1; $vao_p <= 3; $vao_p++ ) : ?>
			<img class="qsn-pub" src="<?php echo esc_url( vao_mod( 'pub' . $vao_p . '_image' ) ); ?>" alt="<?php echo esc_attr( sprintf( vao_t( 'Visuel VAO %d' ), $vao_p ) ); ?>" width="850" height="850" loading="lazy">
		<?php endfor; ?>
	</div>

</main>

<?php
get_footer();
