<?php
/**
 * Réduit les images PNG du thème (appelé par build.ps1) : redimensionne à une taille
 * adaptée à l'affichage et recompresse. Les fichiers gardent leur nom et leur transparence.
 * Les originaux dans ../img ne sont pas touchés.
 *
 * Utilisation : php optimize-images.php <dossier assets/img du thème>
 */

$dir = rtrim( $argv[1] ?? '', '/\\' );
if ( ! is_dir( $dir ) || ! function_exists( 'imagecreatefrompng' ) ) {
	fwrite( STDERR, "Dossier introuvable ou extension GD manquante.\n" );
	exit( 1 );
}

// Plus grand côté autorisé (px), selon le dossier.
$limits = array(
	'header'            => 1920, // bannière pleine largeur
	'Render element 02' => 1600, // fond de chargement, logos, chiffres clés
	'temoin'            => 1000, // images des formulaires
	'temoignage'        => 800,  // cartes témoignages
	'savon'             => 600,  // vignettes produits
);

$before = 0;
$after  = 0;
$files  = new RecursiveIteratorIterator( new RecursiveDirectoryIterator( $dir, FilesystemIterator::SKIP_DOTS ) );

foreach ( $files as $file ) {
	$path = $file->getPathname();
	if ( ! preg_match( '/\.png$/i', $path ) ) {
		continue;
	}
	$before += filesize( $path );

	$folder = basename( dirname( $path ) );
	$max    = isset( $limits[ $folder ] ) ? $limits[ $folder ] : 1600;

	list( $width, $height ) = getimagesize( $path );
	$ratio = min( 1, $max / max( $width, $height ) );
	$new_w = (int) round( $width * $ratio );
	$new_h = (int) round( $height * $ratio );

	$src = imagecreatefrompng( $path );
	$dst = imagecreatetruecolor( $new_w, $new_h );
	imagealphablending( $dst, false );
	imagesavealpha( $dst, true );
	imagecopyresampled( $dst, $src, 0, 0, 0, 0, $new_w, $new_h, $width, $height );

	imagedestroy( $src );

	// Photo sans transparence : en JPEG, bien plus léger (vao_img() trouve le .jpg tout seul).
	if ( ! has_transparency( $dst ) ) {
		$jpg = preg_replace( '/\.png$/i', '.jpg', $path );
		$bg  = imagecreatetruecolor( $new_w, $new_h );
		imagecopy( $bg, $dst, 0, 0, 0, 0, $new_w, $new_h );
		imagejpeg( $bg, $jpg, 82 );
		imagedestroy( $bg );
		imagedestroy( $dst );
		unlink( $path );
		$after += filesize( $jpg );
		continue;
	}

	$tmp = $path . '.tmp';
	imagepng( $dst, $tmp, 9 );
	imagedestroy( $dst );

	// On garde la version la plus légère.
	if ( filesize( $tmp ) < filesize( $path ) ) {
		rename( $tmp, $path );
	} else {
		unlink( $tmp );
	}
	clearstatcache( true, $path );
	$after += filesize( $path );
}

/**
 * Vrai si au moins un pixel n'est pas totalement opaque (échantillonnage tous les 2 px).
 */
function has_transparency( $image ) {
	$w = imagesx( $image );
	$h = imagesy( $image );
	for ( $y = 0; $y < $h; $y += 2 ) {
		for ( $x = 0; $x < $w; $x += 2 ) {
			if ( ( imagecolorat( $image, $x, $y ) >> 24 ) & 0x7F ) {
				return true;
			}
		}
	}
	return false;
}

printf( "Images : %.1f Mo -> %.1f Mo\n", $before / 1048576, $after / 1048576 );
