<?php
/**
 * Page d'accueil.
 * Textes et images : Apparence > Personnaliser > Thème VAO > Page d'accueil (et « Contacts de l'équipe »).
 * Avis clients : menus « Témoignages » et « Avis consommateurs ». Textes fixes : inc/strings/accueil.php.
 */

get_header();

$vao_allowed = array( 'strong' => array() );

// Frise : array( année, titre, texte, liste à puces, icône ). Positions : classes tl-s1..tl-s5 de home.css.
$vao_steps = array(
	array( '2006', 'Naissance', 'Naissance de SDOI, une aventure familiale malgache.', array(), 'bulb' ),
	array( '2008', 'Essor', 'Essor de la marque VAO et des savons de ménage (Blanc, Jaune, Marron, Or).', array(), 'rocket' ),
	array( '2012', 'Expansion', 'Extension de la gamme :', array( 'détergents en poudre', 'savons de toilette', 'détergents en barres' ), 'box' ),
	array( '2021', 'Label', 'Obtention du label «Malagasy Ny Antsika» (SIM / BNM).', array(), 'label' ),
	array( '2026', 'Expertise', '20 ans : +10 marques, +60 références, +3 000 épiceries et +100 distributeurs partenaires.', array(), '20' ),
);

$vao_icons = array(
	'bulb'   => '<path d="M9 16a5 5 0 1 1 6 0 3.5 3.5 0 0 0-1 3 2 2 0 0 1-4 0 3.5 3.5 0 0 0-1-3M9.7 17h4.6"/>',
	'rocket' => '<path d="M4 13a8 8 0 0 1 7 7 6 6 0 0 0 3-5 9 9 0 0 0 6-8 3 3 0 0 0-3-3 9 9 0 0 0-8 6 6 6 0 0 0-5 3"/><path d="M7 14a6 6 0 0 0-3 6 6 6 0 0 0 6-3"/><circle cx="15" cy="9" r="1"/>',
	'box'    => '<path d="M13 20H7a3 3 0 0 1-3-3V8c0-2.5 1.5-4 4-4h8a3 3 0 0 1 3 3v6"/><path d="M4 9c2.5 0 4-1.5 4-5M18 15v6M15 18h6"/>',
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

// Une image envoyée depuis le Personnaliser remplace le collage de photos de la maquette.
$vao_engagement_custom = get_theme_mod( 'vao_engagement_image' );

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
	<div class="home-sdoi-inner">
		<div class="home-sdoi-brand">
			<p class="home-sdoi-surtitre"><?php echo esc_html( vao_mod( 'about_surtitre' ) ); ?></p>
			<?php // Le logo est recoloré en bleu marine par un masque CSS : l'image reste pour le texte alternatif et les dimensions. ?>
			<span class="home-sdoi-logo" style="--logo: url('<?php echo esc_url( vao_mod( 'logo_sdoi' ) ); ?>');">
				<img src="<?php echo esc_url( vao_mod( 'logo_sdoi' ) ); ?>" alt="<?php echo esc_attr( 'SDOI – ' . vao_t( 'Savons et Détergents de l’Océan Indien' ) ); ?>" width="495" height="209">
			</span>
		</div>
		<div class="home-sdoi-text">
			<?php // Titre principal de l'accueil (SEO) : la bannière n'est qu'une image. ?>
			<h1><?php echo esc_html( vao_mod( 'about_titre' ) ); ?></h1>
			<p><?php echo nl2br( esc_html( vao_mod( 'about_texte' ) ) ); ?></p>
		</div>
	</div>
</section>

<!-- NOTRE HISTOIRE -->
<section class="home-histoire" id="histoire">
	<div class="container">
		<h2 class="section-title"><?php vao_e( 'Notre histoire' ); ?></h2>
	</div>

	<?php
	// Titres malagasy des étapes du bas plus longs : descendus pour ne pas toucher la vague (même valeur dans home.css).
	$vao_tl_low = vao_is_mg() ? 18 : 0;
	?>
	<div class="timeline" role="list" aria-label="<?php echo esc_attr( vao_t( 'Les grandes étapes' ) ); ?>">
		<svg class="timeline-art" viewBox="0 0 1440 260" aria-hidden="true" focusable="false">
			<defs>
				<linearGradient id="vao-timeline-gradient" gradientUnits="userSpaceOnUse" x1="20" y1="0" x2="1390" y2="0">
					<stop offset="0" stop-color="#5c63e0"/>
					<stop offset="0.25" stop-color="#a038b0"/>
					<stop offset="0.5" stop-color="#e0367c"/>
					<stop offset="0.7" stop-color="#ec7438"/>
					<stop offset="0.82" stop-color="#f29a1f"/>
					<stop offset="1" stop-color="#fadd7a"/>
				</linearGradient>
			</defs>
			<g class="tl-links" fill="none">
				<path class="tl-c1" d="M128 33V229H112"/>
				<path class="tl-c2" d="M384 36H401V<?php echo (int) ( 166 + $vao_tl_low ); ?>"/>
				<path class="tl-c3" d="M664 35V232H648"/>
				<path class="tl-c4" d="M922 30H939V<?php echo (int) ( 160 + $vao_tl_low ); ?>"/>
				<path class="tl-c5" d="M1220 28V224H1203"/>
			</g>
			<path class="timeline-wave" pathLength="1" d="M32 127C70 160 100 178 130 178C240 178 290 76 400 76C510 76 560 180 670 180C780 180 830 77 940 77C1055 77 1105 180 1220 180C1290 180 1340 155 1370 127"/>
			<g class="tl-dots">
				<circle class="tl-c1" cx="128" cy="33" r="3.5"/><circle class="tl-c1" cx="112" cy="229" r="3.5"/>
				<circle class="tl-c2" cx="384" cy="36" r="3.5"/><circle class="tl-c2" cx="401" cy="<?php echo (int) ( 166 + $vao_tl_low ); ?>" r="3.5"/>
				<circle class="tl-c3" cx="664" cy="35" r="3.5"/><circle class="tl-c3" cx="648" cy="232" r="3.5"/>
				<circle class="tl-c4" cx="922" cy="30" r="3.5"/><circle class="tl-c4" cx="939" cy="<?php echo (int) ( 160 + $vao_tl_low ); ?>" r="3.5"/>
				<circle class="tl-c5" cx="1220" cy="28" r="3.5"/><circle class="tl-c5" cx="1203" cy="224" r="3.5"/>
			</g>
		</svg>
		<?php
		foreach ( $vao_steps as $vao_i => $vao_step ) :
			list( $vao_year, $vao_label, $vao_text, $vao_list, $vao_icon ) = $vao_step;
			?>
			<div class="tl-step tl-s<?php echo (int) ( $vao_i + 1 ); ?>" role="listitem">
				<h3 class="tl-head"><span class="tl-year"><?php echo esc_html( $vao_year ); ?></span> <span class="tl-label"><?php vao_e( $vao_label ); ?></span></h3>
				<div class="tl-text">
					<p><?php vao_e( $vao_text ); ?></p>
					<?php if ( $vao_list ) : ?>
						<ul>
							<?php foreach ( $vao_list as $vao_li ) : ?>
								<li><?php vao_e( $vao_li ); ?></li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
				</div>
				<span class="tl-icon" aria-hidden="true">
					<?php if ( '20' === $vao_icon ) : ?>
						<img src="<?php echo esc_url( vao_img( vao_mg_theme_file( 'accueil/vingt-ans.png' ) ) ); ?>" alt="" width="69" height="54">
					<?php elseif ( 'label' === $vao_icon ) : ?>
						<img src="<?php echo esc_url( vao_img( 'accueil/label-malagasy-ny-antsika.png' ) ); ?>" alt="" width="59" height="45">
					<?php else : ?>
						<svg viewBox="2 2 20 20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" focusable="false"><?php echo $vao_icons[ $vao_icon ]; // phpcs:ignore WordPress.Security.EscapeOutput -- SVG fixe du thème. ?></svg>
					<?php endif; ?>
				</span>
			</div>
		<?php endforeach; ?>
	</div>

	<div class="home-histoire-text">
		<h3><?php vao_e( 'Une conviction familiale au service de Madagascar, née en 2006' ); ?></h3>
		<p><?php echo wp_kses( vao_t( 'L’histoire de la SDOI commence en 2006 avec une conviction familiale forte : <strong>fabriquer localement</strong> des savons et détergents aussi <strong>sûrs et performants</strong> que les marques importées. Depuis deux décennies, nous concrétisons cette vision en concevant, au cœur de Madagascar, les <strong>produits d’hygiène indispensables au quotidien de millions de foyers</strong>.' ), $vao_allowed ); ?></p>
		<p><?php echo wp_kses( vao_t( "Modeste savonnerie à ses débuts, la SDOI est devenue un pilier majeur de l’industrie malgache. Grâce à des investissements continus et au développement de nos équipes, notre savoir-faire s'est étendu du savon de ménage traditionnel aux savons de toilette et détergents en poudre ou en barres. L'ensemble de ces innovations est aujourd'hui réuni sous une marque référence : <strong>VAO</strong>." ), $vao_allowed ); ?></p>
		<p><?php echo wp_kses( vao_t( "<strong>Profondément attachés au « Vita Malagasy »</strong>, nous maîtrisons l'intégralité de nos procédés et produisons nous-mêmes nos matières premières stratégiques. Cette indépendance industrielle garantit une qualité irréprochable, une production fiable et un prix juste pour chaque foyer." ), $vao_allowed ); ?>
		<br><?php echo wp_kses( vao_t( 'Au-delà des usines, la SDOI est avant tout une aventure humaine. Chimistes, équipes de terrain, distributeurs et des milliers d’épiciers de quartier forment une chaîne de confiance engagée chaque jour à faire rayonner la marque VAO à travers toute l’île.' ), $vao_allowed ); ?></p>
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
		<h2 class="section-title"><?php vao_e( 'Notre engagement' ); ?> :</h2>
	</div>
	<div class="home-engagement-grid">
		<div class="home-engagement-cards">
			<?php foreach ( $vao_engagements as $vao_card ) : ?>
				<article class="engagement-card">
					<h3><?php vao_e( $vao_card[0] ); ?></h3>
					<p><?php vao_e( $vao_card[1] ); ?></p>
				</article>
			<?php endforeach; ?>
		</div>
		<div class="home-engagement-visual<?php echo $vao_engagement_custom ? ' is-custom' : ''; ?>">
			<?php if ( $vao_engagement_custom ) : ?>
				<img src="<?php echo esc_url( vao_mod( 'engagement_image' ) ); ?>" alt="<?php echo esc_attr( vao_t( 'Notre engagement' ) ); ?>" width="1345" height="1565" loading="lazy">
			<?php else : ?>
				<?php for ( $vao_n = 1; $vao_n <= 3; $vao_n++ ) : ?>
					<img class="engagement-photo engagement-photo-<?php echo (int) $vao_n; ?>" src="<?php echo esc_url( vao_img( 'accueil/engagement-' . $vao_n . '.jpg' ) ); ?>" alt="<?php echo esc_attr( 1 === $vao_n ? vao_t( 'Notre engagement' ) : '' ); ?>" width="796" height="528" loading="lazy">
				<?php endfor; ?>
			<?php endif; ?>
			<div class="home-engagement-icons" aria-hidden="true">
				<?php foreach ( $vao_usages as $vao_path ) : ?>
					<span><svg viewBox="0 0 40 40" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" focusable="false"><?php echo $vao_path; // phpcs:ignore WordPress.Security.EscapeOutput -- SVG fixe du thème. ?></svg></span>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>

<?php if ( $vao_testimonials || $vao_reviews ) : ?>
<!-- AVIS CLIENTS -->
<section class="home-avis" id="avis">
	<div class="container">
		<h2 class="section-title home-title-line"><?php vao_e( 'Avis clients' ); ?></h2>
	</div>

	<?php if ( $vao_testimonials ) : ?>
		<?php // Carrousel « en éventail » piloté par assets/js/home.js ; sans JS, les cartes défilent horizontalement. ?>
		<div class="home-temoignages" data-coverflow>
			<div class="home-temoignages-stage">
				<?php foreach ( $vao_testimonials as $vao_item ) : ?>
					<?php if ( ! vao_post_img( $vao_item ) ) { continue; } // Carte sans image : ignorée. ?>
					<figure class="temoignage-card">
						<img src="<?php echo esc_url( vao_post_img( $vao_item, 'large' ) ); ?>" alt="<?php echo esc_attr( vao_post_alt( $vao_item ) ); ?>" width="1117" height="1540" loading="lazy">
					</figure>
				<?php endforeach; ?>
			</div>
		</div>
	<?php endif; ?>

	<?php if ( $vao_reviews ) : ?>
		<div class="container">
			<h3 class="section-subtitle home-avis-subtitle"><?php vao_e( 'Ils en parlent' ); ?></h3>
			<div class="vao-carousel home-reviews" data-carousel data-autoplay="5000">
				<div class="vao-carousel-track">
					<?php foreach ( $vao_reviews as $vao_item ) : ?>
						<?php if ( ! vao_post_img( $vao_item ) ) { continue; } ?>
						<figure class="review-card">
							<img src="<?php echo esc_url( vao_post_img( $vao_item, 'large' ) ); ?>" alt="<?php echo esc_attr( vao_post_alt( $vao_item ) ); ?>" loading="lazy">
						</figure>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	<?php endif; ?>
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
					<img src="<?php echo esc_url( vao_mod( "cta{$vao_n}_image" ) ); ?>" alt="" width="724" height="382" loading="lazy">
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
