<?php
/**
 * Malagasy des contenus créés à l'installation (inc/install.php) : collaborateurs, actions RSE, bannières.
 * Clé = texte français exact enregistré en base : si le texte est modifié dans l'admin, sa traduction
 * se fait dans Langues > Traductions des chaînes, ou en créant la version malagasy de l'élément.
 * Malagasy : texte client (TEXTE_SITEWEB_03112026_MALAGASY.docx), coquilles évidentes corrigées.
 * Les offres d'emploi restent en français (demande client du 2026-11-08).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Captures des avis consommateurs (texte alternatif « Avis consommateur 01 » à « 20 »).
$vao_avis = array();
for ( $vao_n = 1; $vao_n <= 20; $vao_n++ ) {
	$vao_avis[ sprintf( 'Avis consommateur %02d', $vao_n ) ] = sprintf( 'Hevitry ny mpanjifa %02d', $vao_n );
}

return array(
	'Contenus' => $vao_avis + array(
		// Bannières (texte alternatif ; le texte incrusté dans l'image se change avec une image malagasy).
		"Propre à l'œil, mais encore plus frais au nez"         => 'VAO Citron : fahadiovana hita maso, manitra sy manintona',
		'Mousse généreuse pour une propreté éclatante'           => 'U20 : savony mamory, antoky ny fahadiovana',
		'Votre linge, propre comme au premier jour'              => 'VAO Magic : lamba madio sy mamirapiratra toy ny vaovao',
		'La propreté qui se voit, la fraîcheur qui se ressent'   => 'VAO Lux : fahadiovana, hanitra mamerovero',
		'Puissance contre les taches, fraîcheur sur votre linge' => 'VAO Line : mahery vaika manala tasy, hanitra mamelombelona ny fitafianao',
		'La fraîcheur qui voyage avec vous'                      => 'VAO Hôtel : hanitra mamelombelona, miaraka aminao na aiza na aiza',
		'Douceur au toucher, fraîcheur au quotidien'             => 'VAO Bébé : malefaka, mamelombelona isan’andro',

		// Logos de marques (texte alternatif) : seuls les savons de ménage ont un nom français.
		'Savon de ménage Blanc'  => 'Savony ho an’ny tokantrano Fotsy',
		'Savon de ménage Jaune'  => 'Savony ho an’ny tokantrano Mavo',
		'Savon de ménage Or'     => 'Savony ho an’ny tokantrano Or',
		'Savon de ménage Marron' => 'Savony ho an’ny tokantrano Marron',

		// Cartes témoignages (texte alternatif). Traduction proposée, hors texte client : à faire valider.
		'Distributeur - Haja, Caissier'      => 'Mpaninjara - Haja, Mpitahiry vola',
		'Grossiste - Razery, Vendeuse'       => 'Mpivarotra ambongadiny - Razery, Mpivarotra',
		'Client - Robert, père de famille'   => 'Mpanjifa - Robert, Rain’ny ankohonana',
		'Épicerie - Mme Harisoa, Vendeuse'   => 'Epicerie - Ramatoa Harisoa, Mpivarotra',
		'Client - Fara, Mpanasa lamba'       => 'Mpanjifa - Fara, Mpanasa lamba',
		'Client - Noro, Renim-pianakaviana'  => 'Mpanjifa - Noro, Renim-pianakaviana',
		'Client - Fara, Mécanicien'          => 'Mpanjifa - Fara, Mekanisiana',
		'Client - Fara, Entrepreneuse'       => 'Mpanjifa - Fara, Mpandraharaha',

		// Distributeurs : titre de ville.
		'ANTANANARIVO ET ENVIRONS' => 'ANTANANARIVO SY NY MANODIDINA',

		// Collaborateurs : poste, ancienneté, témoignage.
		'Responsable Marketing'           => 'Tompon’andraikitra amin’ny Marketing',
		'Commercial Grand compte'         => 'Mpandraharaha varotra amin’ny Mpanjifa Lehibe (Grand compte)',
		'Chargé Trade Marketing'          => 'Tompon’andraikitra amin’ny Trade Marketing',
		'Infographiste'                   => 'Infographiste',
		'Chargée Qualité'                 => 'Tompon’andraikitra amin’ny Kalitao',
		'Responsable Ressources Humaines' => 'Tompon’andraikitra amin’ny Loharanon-karena Olombelona',
		'Préparateur de commande'         => 'Mpanomana ny famandrihana (commandes)',
		'Opératrice ligne de production'  => 'Mpiasa amin’ny tsipika famokarana',
		'1 an chez SDOI'                  => '1 taona ao amin’ny SDOI',
		'2 ans chez SDOI'                 => '2 taona ao amin’ny SDOI',
		'3 ans chez SDOI'                 => '3 taona ao amin’ny SDOI',
		"Ce qui me plaît dans mon métier, c'est de faire évoluer une marque malgache en accord avec les attentes des consommateurs. J'aime concevoir des stratégies concrètes qui valorisent nos produits locaux et contribuent au développement de la marque. Voir une idée prendre vie sur le terrain et rencontrer son public, c'est ce qui me motive chaque jour."
			=> 'Ny tena mamporisika ahy amin’ny asako dia ny mandray anjara amin’ny fampandrosoana marika Malagasy, mifanaraka hatrany amin’ny filàna sy ny andrasan’ny mpanjifa. Mahafinaritra ahy ny mamolavola sy manatanteraka paikady ara-barotra izay manasongadina ny vokatra vita eto an-toerana ary mampitombo ny lanjan’ny marika. Ny mahita ny paikady novolavolaina mivaingana eny an-kianja sy ny fifandraisana mivantana amin’ny mpanjifa no manosika ahy hatrany hanome ny tsara indrindra amin’ny asako.',
		"Ce que j'aime chez SDOI, c'est la proximité avec le terrain. Nous sommes en contact direct avec les distributeurs, ce qui nous permet de mieux comprendre le marché et d'améliorer constamment notre approche. Chaque relation construite avec un grand compte est un pas de plus pour rapprocher nos marques des consommateurs. On sent vraiment qu'on participe à quelque chose de concret."
			=> 'Anisan’ny soatoavina fototra ijoroan’ny SDOI ny fifandraisana akaiky sy mivantana amin’ireo mpiara-miombon’antoka eny an-kianja. Miara-miasa akaiky amin’ireo mpaninjara (Distributeurs) izahay mba hahatakarana bebe kokoa ny filàn’izy ireo sy ny fanamby sedrainy eo amin’ny sehatry ny fivarotana savony, ary hanatsarana hatrany ny fomba fiasa sy ny tolotra omenay. Ny fifandraisana sy ny fiaraha-miasa aorina amin’ny mpaninjara tsirairay dia dingana iray lehibe entina hampanakaiky hatrany ny marika VAO amin’ny mpanjifa. Mihoatra noho ny fiaraha-miasa ara-barotra, mino izahay fa ny fahombiazana dia vokatry ny fitomboana iarahana. Izany no antony anoloranay fanohanana sy fanarahana akaiky ireo mpaninjara, mba handraisany anjara mavitrika amin’ny fampandrosoana ny asany sy ny fitomboan’ny tambajotran’ny marika VAO.',
		"Le Trade, c'est le terrain qui donne vie à la stratégie. Derrière chaque action, il y a une équipe, de l'engagement et une même ambition : rapprocher nos marques des consommateurs. Aller à la rencontre des points de vente, observer, ajuster, puis voir les résultats arriver, voilà ce qui me motive. Parce qu'une marque ne se construit pas seulement en bureau, mais surtout sur le terrain, ensemble."
			=> 'Ny asa Trade eny an-kianja no mampisy aina sy endrika mivaingana ny paikady napetraka. Ao ambadiky ny hetsika tsirairay dia misy ekipa mavitrika, fanoloran-tena ary tanjona iombonana iray : ny hampanakaiky hatrany ny marika VAO amin’ny mpanjifa. Ny fifandraisana mivantana amin’ireo toeram-pivarotana, ny fandinihana ny zava-misy eny an-kianja, ny fanitsiana ireo paikady araka izay ilaina ary ny fanombanana ny vokatra azo no anisan’ny mampiavaka sy manosika anay amin’ity asa ity. Mino izahay fa tsy ao amin’ny birao ihany no anorenana sy anandratana ny marika iray, fa indrindra eny an-kianja, amin’ny alalan’ny hetsika mivaingana sy ny fiaraha-miasan’ireo ekipa Trade. Izy ireo no hery manosika ny paikady ho lasa vokatra azo tsapain-tanana ary mampitombo hatrany ny fifandraisan’ny marika VAO amin’ny mpanjifa.',
		"En tant qu'Infographiste, mon quotidien est une véritable immersion au cœur de la création visuelle. Ce que j'aime le plus, c'est traduire des idées et des concepts en univers graphiques percutants : identités visuelles, packagings inspirants, supports de communication engageants. Travailler dans cet environnement dynamique me permet de donner vie à mon imagination et de marquer les esprits."
			=> 'Amin’ny maha-mpamorona sary (Infographiste) ahy, dia mifantoka amin’ny famolavolana ireo fitaovana ara-pijery maneho ny maha-izy sy ny soatoavin’ny SDOI ny asako andavanandro. Ny tena ankafiziko dia ny mandika hevitra sy foto-kevitra ho lasa sary sy endrika manintona, manomboka amin’ny maha-izy ara-pijerin’ny marika ka hatramin’ny famolavolana fonosana miavaka sy mahasarika. Ny tontolon’asa mavitrika sy feno famoronana ao amin’ny SDOI dia manome sehatra ahafahako mampivelatra ny talentako, manatanteraka ireo hevitro ary mamorona endrika maharitra ao an-tsain’ny mpanjifa.',
		"En tant que Chargée Qualité & Chimiste, ma mission est d'assurer l'excellence et la conformité de nos savons et produits détergents. Ce qui me passionne : garantir à nos consommateurs des formules efficaces, sûres et agréables, grâce à des analyses rigoureuses du laboratoire jusqu'à la production. C'est une vraie fierté de veiller chaque jour sur la qualité de nos marques."
			=> 'Amin’ny maha-Tompon’andraikitra misahana ny Kalitao sy Mpahay Simia ahy, dia miantoka ny kalitao sy ny fanarahana ny fenitra takiana amin’ireo savony sy vovoka fanasan-damba aho. Ataoko laharam-pahamehana ny famolavolana vokatra mahomby sy azo antoka, amin’ny alalan’ny fitiliana sy fanaraha-maso hentitra, manomboka eny amin’ny laboratoara ka hatrany amin’ny famokarana. Reharehako ny mandray anjara amin’ny fiantohana ny kalitao sy ny fahatokisan’ny mpanjifa ny vokatra VAO isan’andro.',
		"Exercer mon métier de Responsable des Ressources Humaines est une source de passion quotidienne. J'aime voir mes collaborateurs s'épanouir et grandir au sein d'une entreprise où l'humain est réellement au cœur de chaque décision. Travailler dans un cadre stimulant et bienveillant, avec des équipes dynamiques, donne un vrai sens à mon engagement."
			=> 'Amin’ny maha-Tompon’andraikitra misahana ny Fitantanana ny Mpiasa ahy, dia loharanon’ny fahafaham-po sy ny antony manosika ahy isan’andro ny asako. Mahafaly ahy ny mahita ireo mpiara-miasa mivoatra sy mampivelatra ny fahaiza-manaony ao anatin’ny orinasa iray izay mametraka ny maha-olona ho ivon’ny fanapahan-kevitra rehetra. Ny miasa ao anatin’ny tontolo manome lanja ny tsirairay, mamporisika ny fandrosoana ary mampiroborobo ny fiaraha-miasa no manome lanja sy dikany ny andraikitra sahaniko.',
		"En tant que Magasinier, ma mission est de veiller à une gestion rigoureuse de nos stocks, notamment en assurant que toute la gamme de détergents VAO Line soit parfaitement rangée et classée. Ce qui me passionne, c'est mon rôle clé au cœur de la chaîne logistique : garantir la traçabilité et préparer les commandes avec précision, pour que nos produits arrivent toujours à temps et dans de bonnes conditions."
			=> 'Amin’ny maha-mpitantana ny tahiry ahy, dia miantoka ny fandaminana sy ny fanaraha-maso hentitra ireo vokatra VAO Line aho. Ataoko laharam-pahamehana ny fahamarinan’ny tahiry, ny fanarahana ny fivezivezen’ny entana ary ny fanomanana ara-potoana ny kaomandy, mba hahazoana antoka fa tonga amin’ny toerana tokony halehany ara-potoana sy amin’ny kalitao tsara hatrany ny vokatra.',
		"En tant qu'Opératrice de ligne, le cœur de mon métier est d'assurer la fabrication de nos savons sur la chaîne de production. Ce qui me passionne, c'est ce savoir-faire technique et la rigueur nécessaire pour garantir que chaque savon respecte nos standards d'excellence. Travailler au sein d'une équipe solidaire et dynamique, c'est une vraie fierté : notre travail contribue à l'hygiène et au bien-être de milliers de familles."
			=> 'Amin’ny maha-mpiasa eo amin’ny tsipika famokarana ahy, dia miantoka ny fanamboarana ny savony VAO araka ny fenitra takiana aho. Mitaky fahaiza-manao ara-teknika, fahamailoana ary fahitsiana ny asako, mba hahazoana vokatra manara-penitra hatrany. Reharehako ny miara-miasa amin’ny ekipa mavitrika sy manolo-tena, satria ny asa ataonay dia mandray anjara mivantana amin’ny fahadiovana sy ny fiadanan’ny tokantrano maro.',

		// Actions RSE : titre et texte.
		"SDOI s'engage pour la solidarité et le bien-être des enfants." => 'SDOI, mpiara-miombon’antoka amin’ny firaisankina sy ny fiadanan’ny ankizy',
		"À travers sa marque VAO, SDOI, membre du SIM, a soutenu l'initiative « Mbola Mijoro ho an'ny Zaza Malagasy », portée par C'est du Joely, au bénéfice de MealEspoirs et de l'École associative L'Île aux Enfants à Anosibe. Ce soutien s'est concrétisé par un don de produits d'hygiène et d'entretien VAO, contribuant à garantir un environnement propre et sain aux enfants."
			=> 'Amin’ny alalan’ny marika VAO, ny SDOI, izay mpikambana ao amin’ny SIM, dia nandray anjara tamin’ny hetsika « Mbola Mijoro ho an’ny Zaza Malagasy », nokarakarain’ny C’est du Joely, ho fanohanana ny MealEspoirs sy ny sekoly L’Île aux Enfants, ao Anosibe. Naseho tamin’ny alalan’ny fanomezana vokatra VAO fanadiovana sy fikojakojana izany fanohanana izany, mba handraisana anjara amin’ny fanatsarana ny fahadiovana sy ny fahasalaman’ny ankizy ary ny tontolo ianarany.',
		'SDOI engagée pour le bien-être des communautés locales' => 'Manohana ny vondrom-piarahamonina eny ifotony',
		"Dans le cadre de sa démarche de responsabilité sociétale, SDOI a apporté son soutien aux personnes âgées et aux enfants du fokontany d'Ambanitsena, où est implantée l'usine Savony VAO. Cette initiative s'est concrétisée par un don de savons et de détergents, témoignant de l'engagement de l'entreprise en faveur de la solidarité, de l'hygiène et du bien-être des communautés locales."
			=> 'Ao anatin’ny fanoloran-tena ho an’ny fiarahamonina, dia nanatanteraka hetsika firaisankina ho an’ireo zokiolona sy mpianatra ao amin’ny Fokontany Ambanitsena, toerana misy ny orinasa Savony VAO, ny SDOI. Tamin’ny alalan’ny fanomezana savony sy vokatra fanadiovana no nanehoan’ny orinasa ny fanohanany. Hetsika maneho ny finiavan’ny SDOI hampiroborobo ny firaisankina, ny fahadiovana ary ny fiadanan’ny mponina eny an-toerana izany.',
		'VAO, au cœur de la solidarité à Noël' => 'VAO, mariky ny firaisankina amin’ny fankalazana ny Noely',
		"À l'occasion des fêtes de Noël, SDOI a apporté son soutien aux personnes les plus démunies du fokontany d'Ankorondrano à travers une distribution de calendriers et de produits d'hygiène et d'entretien VAO. Une initiative solidaire qui témoigne de l'engagement de l'entreprise en faveur du bien-être des communautés locales."
			=> 'Nandritra ny fankalazana ny Noely, dia naneho ny firaisankinany tamin’ireo fianakaviana sahirana tao amin’ny Fokontany Ankorondrano ny SDOI, tamin’ny alalan’ny fizarana kalandrie sy vokatra VAO fanadiovana ary fikojakojana. Hetsika maneho ny fanoloran-tenan’ny orinasa amin’ny fifanampiana sy ny fanohanana ny vondrom-piarahamonina eny ifotony, indrindra amin’ny fotoana manan-danja toy ny fankalazana ny Noely.',
	),
);
