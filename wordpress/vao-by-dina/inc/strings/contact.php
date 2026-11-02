<?php
/**
 * Textes de la page Nous contacter (template-contact.php) : français => malagasy (vide = à traduire).
 * Les libellés déjà présents dans le groupe « Formulaires » de inc/i18n.php ne sont pas redéclarés :
 * une valeur vide ici écraserait leur traduction lors du pré-remplissage.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

return array(
	'Nous contacter' => array(
		// Onglets.
		'DEVENEZ DISTRIBUTEURS'                                                  => 'HO MPANINJARA',
		"DEMANDE D'INFORMATIONS"                                                 => 'FANGATAHANA FANAZAVANA',
		"NOS OFFRES D'EMPLOIS"                                                   => 'IREO ASA ATOLOTRAY',

		// Demande d'informations.
		'1. Informations sur le demandeur'                                       => '1. Mombamomba ny mpangataka',
		'Date de la demande :'                                                   => 'Daty nangatahana :',
		'Nom de la société / Client :'                                           => "Anaran'ny orinasa / Mpanjifa :",
		'Nom & Prénom du contact :'                                              => "Anarana sy fanampin'anaran'ny olona hifandraisana :",
		'Adresse E-mail :'                                                       => 'Adiresy mailaka :',
		'Type de client :'                                                       => 'Karazana mpanjifa :',
		'2. Spécifications du produit recherché'                                 => "2. Antsipirian'ny vokatra tadiavina",
		'Catégorie'                                                              => 'Sokajy',
		'Sélectionner…'                                                          => 'Safidio…',
		'Gamme / Type Format / Parfum'                                           => 'Karazany / Endrika / Hanitra',
		'Quantité souhaitée'                                                     => 'Habetsahana tadiavina',
		'Usage prévu'                                                            => 'Fampiasana kasaina',
		'Commentaires'                                                           => 'Fanamarihana',
		'3. Informations souhaitées'                                             => '3. Fanazavana tadiavina',
		'Grille tarifaire dégressive'                                            => 'Lisitry ny vidiny mihena araka ny habetsahana',
		'Conditions de livraison & Délais'                                       => 'Fepetra sy fotoana fanaterana',
		'Possibilité de personnalisation'                                        => 'Fahafahana manamboatra araka ny filana',
		'Fiche Technique / Compositions (MSDS)'                                  => 'Taratasy teknika / Singa mandrafitra (MSDS)',
		'Échantillons de démonstration'                                          => 'Santionany fanandramana',
		'Certifications & Normes de qualité'                                     => 'Fanamarinana sy fenitra kalitao',
		'4. Message complémentaire'                                              => '4. Hafatra fanampiny',

		// Candidature.
		'Nom * :'                                                                => 'Anarana * :',
		'Prénoms * :'                                                            => "Fanampin'anarana * :",
		'E-mail * :'                                                             => 'Mailaka * :',
		'Téléphone * :'                                                          => 'Finday * :',

		// Pièces jointes.
		'Formats acceptés : PDF, JPG, PNG – 5 Mo maximum par fichier.'           => 'Endrika ekena : PDF, JPG, PNG – 5 Mo farafahabetsany isaky ny rakitra.',
		'Formats acceptés : PDF, DOC, DOCX, JPG, PNG – 5 Mo maximum par fichier.' => 'Endrika ekena : PDF, DOC, DOCX, JPG, PNG – 5 Mo farafahabetsany isaky ny rakitra.',
		'Fichier de plus de 5 Mo non ajouté : %s'                                => 'Tsy nampidirina ny rakitra mihoatra ny 5 Mo : %s',
		'5 fichiers au maximum : les fichiers en trop n’ont pas été ajoutés.'    => 'Rakitra 5 farafahabetsany : tsy nampidirina ireo rakitra tafahoatra.',

		// Message renvoyé par inc/forms.php quand la validation serveur échoue.
		'Merci de remplir les champs obligatoires avec une adresse e-mail valide.' => "Fenoy azafady ireo saha tsy maintsy fenoina, miaraka amin'ny adiresy mailaka marina.",

		// Offres d'emploi.
		'Postuler'                                                               => 'Hangataka',
		"Aucune offre d'emploi n'est publiée pour le moment."                    => "Tsy misy tolotra asa navoaka amin'izao fotoana izao.",
		'Envoyez-nous une candidature spontanée'                                 => 'Alefaso aminay ny fangatahanao asa',
	),
);
