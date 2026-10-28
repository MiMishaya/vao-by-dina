<?php
/**
 * Page d'accueil.
 * Textes et images : Apparence > Personnaliser > Thème VAO > Page d'accueil (et « Contacts de l'équipe »).
 * Avis clients : menus « Témoignages » et « Avis consommateurs ». Textes fixes : inc/strings/accueil.php.
 */

get_header();

$vao_allowed = array( 'strong' => array() );

// Frise : array( année, titre, texte, icône ). Une étape sur deux a son texte sous la vague.
$vao_steps = array(
	array( '2006', 'Naissance', 'Naissance de SDOI, une aventure familiale malgache.', 'bulb' ),
	array( '2008', 'Essor', 'Essor de la marque VAO et des savons de ménage (Blanc, Jaune, Marron, Or).', 'rocket' ),
	array( '2012', 'Expansion', 'Extension de la gamme : détergents en poudre, savons de toilette, détergents en barres.', 'box' ),
	array( '2021', 'Label', 'Obtention du label « Malagasy Ny Antsika » (SIM / BNM).', 'label' ),
	array( '2026', 'Expertise', '20 ans : +10 marques, +60 références, +3 000 épiceries et +100 distributeurs partenaires.', '20' ),
);

$vao_icons = array(
	'bulb'   => '<path d="M15 33h10M16 37h8M20 4a11 11 0 0 0-6.5 19.9c1.4 1 2.2 2.6 2.2 4.3V29h8.6v-.8c0-1.7.8-3.3 2.2-4.3A11 11 0 0 0 20 4Z"/>',
	'rocket' => '<path d="M23 7c5-3 10-3 12-2 1 2 1 7-2 12L22 28l-10-10L23 7Z"/><circle cx="26" cy="14" r="3"/><path d="M13 19H6l5-6h7M21 27v7l6-5v-7M11 29l-5 5M14 32l-3 3M8 26l-3 3"/>',
	'box'    => '<path d="M6 13l6-7h16l6 7v12M6 13v19a2 2 0 0 0 2 2h14M6 13h28M16 6l-2 7M24 6l2 7"/><path d="M30 26v10M25 31h10"/>',
	'label'  => '<path d="M20 35 7 22a8 8 0 0 1 11.3-11.3L20 12.4l1.7-1.7A8 8 0 0 1 33 22L20 35Z"/><path d="M14 22l4 4 8-8"/>',
);

$vao_engagements = array(
	array( 'Une vraie relation de confiance', "Pour nous, nos distributeurs ne sont pas de simples clients, ce sont nos partenaires. Quand vous nous faites confiance pour approvisionner votre marché, nous tenons parole : nous vous livrons des produits irréprochables, dans le respect strict de nos engagements. Transparence, régularité et écoute : c'est ainsi que nous grandissons ensemble au quotidien." ),
	array( 'Le consommateur au cœur de notre démarche', 'Nos consommateurs sont à l’origine de chacune de nos décisions : sans eux, Savony VAO n’existerait pas. Nous nous engageons à proposer des produits de qualité à des prix accessibles, avec un rapport qualité-prix irréprochable. Leur satisfaction guide l’amélioration continue de nos produits, conçus pour répondre aux besoins réels des familles.' ),
	array( 'Un engagement sincère pour notre planète', "Prendre soin de votre foyer ne doit jamais se faire au détriment de la nature. C'est pourquoi nos procédés de fabrication sont entièrement pensés pour être propres. Et bien sûr, aucun de nos produits n'est testé sur les animaux. La propreté, oui, mais toujours avec cœur et conscience." ),
);

$vao_usages = array(
	'<path d="M15 7 7 11l-3 8 6 2v14h20V21l6-2-3-8-8-4c-1 3-3 4-5 4s-4-1-5-4Z"/><circle cx="16" cy="27" r="1.6"/><circle cx="22" cy="30" r="1.2"/><circle cx="24" cy="25" r="1.6"/>',
	'<path d="M4 20h32v4a8 8 0 0 1-8 8H12a8 8 0 0 1-8-8v-4ZM8 20V9a4 4 0 0 1 7.5-2M11 32l-2 4M29 32l2 4"/><circle cx="20" cy="14" r="2.5"/><circle cx="27" cy="11" r="2"/><circle cx="25" cy="17" r="1.5"/>',
	'<path d="M6 27v-6l4-7h20l4 7v6a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2ZM6 21h28M12 29v3M28 29v3"/><circle cx="12" cy="25" r="1.5"/><circle cx="28" cy="25" r="1.5"/><circle cx="14" cy="7" r="2"/><circle cx="21" cy="5" r="2.5"/><circle cx="27" cy="8" r="1.5"/>',
);

$vao_selection = array(
	array( 1, 'detergent', 'Détergent en poudre', 655 ),
	array( 2, 'savonbar', 'Savon barre', 1001 ),
	array( 3, 'toilette', 'Savon de toilette', 655 ),
);

$vao_cta = array(
	array( 1, 'orange', vao_url( 'nous-contacter', 'distributeur' ) ),
	array( 2, 'pink', vao_url( 'nous-contacter', 'information' ) ),
	array( 3, 'navy', vao_url( 'nous-contacter', 'candidature' ) ),
);

$vao_services = array(
	array( 'marketing', 'Service Marketing', 'pink' ),
	array( 'commercial', 'Service Commercial', 'yellow' ),
	array( 'grandcompte', 'Service Grand Compte', 'orange' ),
);

$vao_testimonials = vao_items( 'vao_temoignage' );
$vao_reviews      = vao_items( 'vao_avis' );
?>

<!-- SDOI -->
<section class="home-sdoi" id="sdoi">
	<div class="container home-sdoi-inner">
		<div class="home-sdoi-brand">
			<p class="home-sdoi-surtitre"><?php echo esc_html( vao_mod( 'about_surtitre' ) ); ?></p>
			<img src="<?php echo esc_url( vao_mod( 'logo_sdoi' ) ); ?>" alt="<?php echo esc_attr( 'SDOI – ' . vao_t( 'Savons et Détergents de l’Océan Indien' ) ); ?>" width="495" height="209">
		</div>
		<div class="home-sdoi-text">
			<h2><?php echo esc_html( vao_mod( 'about_titre' ) ); ?></h2>
			<p><?php echo nl2br( esc_html( vao_mod( 'about_texte' ) ) ); ?></p>
		</div>
	</div>
</section>

<!-- NOTRE HISTOIRE -->
<section class="home-histoire" id="histoire">
	<div class="container">
		<h2 class="section-title"><?php vao_e( 'Notre histoire' ); ?></h2>

		<div class="timeline" role="list" aria-label="<?php echo esc_attr( vao_t( 'Les grandes étapes' ) ); ?>">
			<svg class="timeline-wave" viewBox="0 0 1000 100" preserveAspectRatio="none" aria-hidden="true" focusable="false">
				<defs>
					<linearGradient id="vao-timeline-gradient" gradientUnits="userSpaceOnUse" x1="0" y1="0" x2="1000" y2="0">
						<stop offset="0" stop-color="#7b3fc4"/>
						<stop offset="0.4" stop-color="#d0368d"/>
						<stop offset="0.72" stop-color="#ef7a2c"/>
						<stop offset="1" stop-color="#f9d36b"/>
					</linearGradient>
				</defs>
				<path d="M14 50C40 74 70 85 100 85S220 15 300 15S420 85 500 85S620 15 700 15S820 85 900 85S970 68 986 50"/>
			</svg>
			<?php
			foreach ( $vao_steps as $vao_i => $vao_step ) :
				list( $vao_year, $vao_label, $vao_text, $vao_icon ) = $vao_step;
				$vao_n    = $vao_i + 1;
				$vao_down = 1 === $vao_i % 2;
				?>
				<div class="tl-text tl-<?php echo $vao_down ? 'bottom' : 'top'; ?> tl-s<?php echo (int) $vao_n; ?>" role="listitem">
					<h3><span class="tl-year"><?php echo esc_html( $vao_year ); ?></span> <?php vao_e( $vao_label ); ?></h3>
					<p><?php vao_e( $vao_text ); ?></p>
				</div>
				<div class="tl-icon tl-<?php echo $vao_down ? 'top' : 'bottom'; ?> tl-s<?php echo (int) $vao_n; ?>" aria-hidden="true">
					<?php if ( '20' === $vao_icon ) : ?>
						<span class="tl-twenty">20<small><?php vao_e( 'ans' ); ?></small></span>
					<?php else : ?>
						<svg viewBox="0 0 40 40" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" focusable="false"><?php echo $vao_icons[ $vao_icon ]; // phpcs:ignore WordPress.Security.EscapeOutput -- SVG fixe du thème. ?></svg>
					<?php endif; ?>
				</div>
				<span class="tl-link tl-s<?php echo (int) $vao_n; ?>" aria-hidden="true"></span>
			<?php endforeach; ?>
		</div>

		<div class="home-histoire-text">
			<h3><?php vao_e( 'Une conviction familiale au service de Madagascar, née en 2006' ); ?></h3>
			<p><?php echo wp_kses( vao_t( 'L’histoire de la SDOI commence en 2006 avec une conviction familiale forte : <strong>fabriquer localement</strong> des savons et détergents aussi <strong>sûrs et performants</strong> que les marques importées. Depuis deux décennies, nous concrétisons cette vision en concevant, au cœur de Madagascar, les <strong>produits d’hygiène indispensables au quotidien de millions de foyers</strong>.' ), $vao_allowed ); ?></p>
			<p><?php echo wp_kses( vao_t( "Modeste savonnerie à ses débuts, la SDOI est devenue un pilier majeur de l’industrie malgache. Grâce à des investissements continus et au développement de nos équipes, notre savoir-faire s'est étendu du savon de ménage traditionnel aux savons de toilette et détergents en poudre ou en barres. L'ensemble de ces innovations est aujourd'hui réuni sous une marque référence : <strong>VAO</strong>." ), $vao_allowed ); ?></p>
			<p><?php echo wp_kses( vao_t( "<strong>Profondément attachés au « Vita Malagasy »</strong>, nous maîtrisons l'intégralité de nos procédés et produisons nous-mêmes nos matières premières stratégiques. Cette indépendance industrielle garantit une qualité irréprochable, une production fiable et un prix juste pour chaque foyer." ), $vao_allowed ); ?></p>
			<p><?php echo wp_kses( vao_t( 'Au-delà des usines, la SDOI est avant tout une aventure humaine. Chimistes, équipes de terrain, distributeurs et des milliers d’épiciers de quartier forment une chaîne de confiance engagée chaque jour à faire rayonner la marque VAO à travers toute l’île.' ), $vao_allowed ); ?></p>
		</div>
	</div>
</section>

<!-- MARQUES -->
<section class="home-marques">
	<div class="container">
		<h2 class="home-marques-title"><?php echo esc_html( vao_mod( 'marques_titre' ) ); ?></h2>
	</div>
	<?php get_template_part( 'template-parts/brand-strip' ); ?>
</section>

<!-- NOTRE ENGAGEMENT -->
<section class="home-engagement" id="engagement">
	<div class="container">
		<p class="home-engagement-kicker"><?php vao_e( 'Savony N° 01 à Madagascar' ); ?></p>
		<h2 class="section-title"><?php vao_e( 'Notre engagement' ); ?></h2>
		<div class="home-engagement-grid">
			<div class="home-engagement-cards">
				<?php foreach ( $vao_engagements as $vao_card ) : ?>
					<article class="engagement-card">
						<h3><?php vao_e( $vao_card[0] ); ?></h3>
						<p><?php vao_e( $vao_card[1] ); ?></p>
					</article>
				<?php endforeach; ?>
			</div>
			<div class="home-engagement-visual">
				<img src="<?php echo esc_url( vao_mod( 'engagement_image' ) ); ?>" alt="<?php echo esc_attr( vao_t( 'Notre engagement' ) ); ?>" width="1345" height="1565" loading="lazy">
				<div class="home-engagement-icons" aria-hidden="true">
					<?php foreach ( $vao_usages as $vao_path ) : ?>
						<span><svg viewBox="0 0 40 40" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" focusable="false"><?php echo $vao_path; // phpcs:ignore WordPress.Security.EscapeOutput -- SVG fixe du thème. ?></svg></span>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
</section>

<?php if ( $vao_testimonials || $vao_reviews ) : ?>
<!-- AVIS CLIENTS -->
<section class="home-avis" id="avis">
	<div class="container">
		<h2 class="section-title home-title-line"><?php vao_e( 'Avis clients' ); ?></h2>

		<?php if ( $vao_testimonials ) : ?>
			<div class="vao-carousel home-temoignages" data-carousel>
				<div class="vao-carousel-track">
					<?php foreach ( $vao_testimonials as $vao_item ) : ?>
						<figure class="temoignage-card">
							<img src="<?php echo esc_url( vao_post_img( $vao_item, 'large' ) ); ?>" alt="<?php echo esc_attr( vao_post_alt( $vao_item ) ); ?>" width="1117" height="1540" loading="lazy">
						</figure>
					<?php endforeach; ?>
				</div>
			</div>
		<?php endif; ?>

		<?php if ( $vao_reviews ) : ?>
			<h3 class="section-subtitle home-avis-subtitle"><?php vao_e( 'Ils en parlent' ); ?></h3>
			<div class="vao-carousel home-reviews" data-carousel data-autoplay="4000">
				<div class="vao-carousel-track">
					<?php foreach ( $vao_reviews as $vao_item ) : ?>
						<figure class="review-card">
							<img src="<?php echo esc_url( vao_post_img( $vao_item, 'large' ) ); ?>" alt="<?php echo esc_attr( vao_post_alt( $vao_item ) ); ?>" loading="lazy">
						</figure>
					<?php endforeach; ?>
				</div>
			</div>
		<?php endif; ?>
	</div>
</section>
<?php endif; ?>

<!-- PRODUITS SÉLECTIONNÉS -->
<section class="home-selection">
	<div class="container">
		<h2 class="section-title no-bar"><?php vao_e( 'Produits sélectionnés pour vous' ); ?></h2>
		<div class="home-selection-grid">
			<?php foreach ( $vao_selection as $vao_sel ) : list( $vao_n, $vao_gamme, $vao_name, $vao_w ) = $vao_sel; ?>
				<a class="selection-item selection-<?php echo (int) $vao_n; ?>" href="<?php echo esc_url( vao_gamme_url( $vao_gamme ) ); ?>">
					<img src="<?php echo esc_url( vao_mod( "selection{$vao_n}_image" ) ); ?>" alt="<?php echo esc_attr( vao_t( $vao_name ) ); ?>" width="<?php echo (int) $vao_w; ?>" height="698" loading="lazy">
				</a>
			<?php endforeach; ?>
		</div>
		<div class="home-selection-more">
			<a class="btn-sky" href="<?php echo esc_url( vao_gamme_url() ); ?>"><?php vao_e( 'Découvrir plus' ); ?></a>
		</div>
	</div>
</section>

<!-- FORMULAIRES -->
<section class="home-forms">
	<div class="container">
		<h2 class="section-title no-bar"><?php vao_e( 'Accédez à nos formulaires' ); ?></h2>
		<div class="home-forms-grid">
			<?php foreach ( $vao_cta as $vao_card ) : list( $vao_n, $vao_color, $vao_link ) = $vao_card; ?>
				<a class="form-card <?php echo esc_attr( $vao_color ); ?>" href="<?php echo esc_url( $vao_link ); ?>">
					<img src="<?php echo esc_url( vao_mod( "cta{$vao_n}_image" ) ); ?>" alt="" width="1000" height="1000" loading="lazy">
					<span class="form-card-body">
						<strong class="form-card-title"><?php echo esc_html( vao_mod( "cta{$vao_n}_titre" ) ); ?></strong>
						<span class="form-card-text"><?php echo esc_html( vao_mod( "cta{$vao_n}_texte" ) ); ?></span>
						<span class="form-card-link"><?php echo esc_html( vao_mod( 'cta_lien' ) ); ?></span>
					</span>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<!-- CONTACTEZ L'ÉQUIPE -->
<section class="home-team">
	<div class="container">
		<h2 class="section-title no-bar"><?php vao_e( "Contactez l'équipe" ); ?></h2>
		<p class="home-team-intro"><?php vao_e( 'Notre équipe est à votre disposition pour répondre à vos questions et vous accompagner dans vos démarches. Retrouvez ci-dessous les coordonnées de chaque responsable.' ); ?></p>
		<div class="home-team-grid">
			<?php
			foreach ( $vao_services as $vao_service ) :
				list( $vao_key, $vao_name, $vao_color ) = $vao_service;
				$vao_tel   = vao_mod( $vao_key . '_tel' );
				$vao_email = vao_mod( $vao_key . '_email' );
				?>
				<div class="team-card">
					<h3 class="<?php echo esc_attr( $vao_color ); ?>"><?php vao_e( $vao_name ); ?></h3>
					<?php if ( $vao_tel ) : ?>
						<a href="<?php echo esc_url( vao_tel_link( $vao_tel ) ); ?>"><?php echo esc_html( $vao_tel ); ?></a>
					<?php endif; ?>
					<?php if ( $vao_email && is_email( $vao_email ) ) : ?>
						<a href="<?php echo esc_url( 'mailto:' . $vao_email ); ?>"><?php echo esc_html( $vao_email ); ?></a>
					<?php elseif ( $vao_email ) : ?>
						<span><?php echo esc_html( $vao_email ); ?></span>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<?php
get_footer();
