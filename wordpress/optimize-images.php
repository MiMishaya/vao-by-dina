<?php
/**
 * Optimise les images du thème (appelé par build.ps1) : redimensionne à une taille adaptée
 * à l'affichage, recompresse, puis crée une copie WebP de chaque image (servie en priorité
 * par vao_img() et par les CSS). Les originaux dans ../img ne sont pas touchés.
 *
 * - PNG opaque => JPEG (vao_img() trouve le .jpg tout seul) ; PNG transparent reste PNG.
 * - Chaque PNG / JPG reçoit un .webp du même nom (transparence conservée).
 * - Variantes réduites « nom-<largeur>.webp » pour certains dossiers (bannière mobile, vignettes RSE).
 *
 * Utilisation : php optimize-images.php <dossier assets/img du thème>
 */

$dir = rtrim( $argv[1] ?? '', '/\\' );
if ( ! is_dir( $dir ) || ! function_exists( 'imagecreatefrompng' ) || ! function_exists( 'imagewebp' ) ) {
	fwrite( STDERR, "Dossier introuvable ou extension GD (avec WebP) manquante.\n" );
	exit( 1 );
}

// Plus grand côté autorisé (px), selon le dossier.
$limits = array(
	'header'            => 1920, // bannière pleine largeur
	'Render element 02' => 1600, // fond de chargement, logos, chiffres clés
	'temoin'            => 1000, // images des formulaires
	'temoignage'        => 800,  // cartes témoignages
	'savon'             => 600,  // vignettes produits
	'logos'             => 800,  // logos de l'en-tête et du pied de page (affichés < 400 px)
	'avis'              => 900,  // captures des avis consommateurs
	'bg'                => 1920, // fonds de page
);

// Variantes réduites supplémentaires : dossier => largeur (px).
$variants = array(
	'header' => 960, // bannière sur mobile / tablette (srcset)
	'rse'    => 640, // vignettes de la galerie RSE (la photo entière s'ouvre au clic)
);

const VAO_JPEG_QUALITY = 82;
const VAO_WEBP_QUALITY = 80;

$before = 0;
$after  = 0;
$paths  = array();
foreach ( new RecursiveIteratorIterator( new RecursiveDirectoryIterator( $dir, FilesystemIterator::SKIP_DOTS ) ) as $file ) {
	if ( preg_match( '/\.(png|jpe?g)$/i', $file->getFilename() ) ) {
		$paths[] = $file->getPathname();
	}
}

foreach ( $paths as $path ) {
	$before += filesize( $path );
	$folder  = basename( dirname( $path ) );
	$max     = isset( $limits[ $folder ] ) ? $limits[ $folder ] : 1600;
	$is_png  = (bool) preg_match( '/\.png$/i', $path );

	$src = $is_png ? imagecreatefrompng( $path ) : imagecreatefromjpeg( $path );
	if ( ! $src ) {
		fwrite( STDERR, "Image illisible ignorée : $path\n" );
		$after += filesize( $path );
		continue;
	}
	$image = vao_resize( $src, $max );
	imagedestroy( $src );

	if ( $is_png && ! vao_has_transparency( $image ) ) {
		// Photo sans transparence : en JPEG, bien plus léger.
		$jpg = preg_replace( '/\.png$/i', '.jpg', $path );
		imagejpeg( vao_flatten( $image ), $jpg, VAO_JPEG_QUALITY );
		unlink( $path );
		$path   = $jpg;
		$is_png = false;
	} else {
		// On garde la version recompressée seulement si elle est plus légère.
		$tmp = $path . '.tmp';
		$is_png ? imagepng( $image, $tmp, 9 ) : imagejpeg( $image, $tmp, VAO_JPEG_QUALITY );
		if ( filesize( $tmp ) < filesize( $path ) ) {
			rename( $tmp, $path );
		} else {
			unlink( $tmp );
		}
	}
	clearstatcache();

	// Copie WebP toujours créée : les CSS y font référence directement.
	$base = preg_replace( '/\.(png|jpe?g)$/i', '', $path );
	vao_save_webp( $image, $base . '.webp', $is_png );
	$after += filesize( $base . '.webp' );

	if ( isset( $variants[ $folder ] ) && imagesx( $image ) > $variants[ $folder ] ) {
		$small = vao_resize( $image, $variants[ $folder ], true );
		vao_save_webp( $small, $base . '-' . $variants[ $folder ] . '.webp', $is_png );
		imagedestroy( $small );
	}
	imagedestroy( $image );
}

/**
 * Copie redimensionnée (plus grand côté, ou largeur si $by_width) ; transparence conservée.
 */
function vao_resize( $src, $max, $by_width = false ) {
	$width  = imagesx( $src );
	$height = imagesy( $src );
	$ratio  = min( 1, $max / ( $by_width ? $width : max( $width, $height ) ) );
	$new_w  = max( 1, (int) round( $width * $ratio ) );
	$new_h  = max( 1, (int) round( $height * $ratio ) );
	$dst    = imagecreatetruecolor( $new_w, $new_h );
	imagealphablending( $dst, false );
	imagesavealpha( $dst, true );
	imagecopyresampled( $dst, $src, 0, 0, 0, 0, $new_w, $new_h, $width, $height );
	return $dst;
}

/**
 * Copie opaque (pour le JPEG, qui ne gère pas la transparence).
 */
function vao_flatten( $image ) {
	$bg = imagecreatetruecolor( imagesx( $image ), imagesy( $image ) );
	imagecopy( $bg, $image, 0, 0, 0, 0, imagesx( $image ), imagesy( $image ) );
	return $bg;
}

function vao_save_webp( $image, $target, $alpha ) {
	imagewebp( $alpha ? $image : vao_flatten( $image ), $target, VAO_WEBP_QUALITY );
}

/**
 * Vrai si au moins un pixel n'est pas totalement opaque (échantillonnage tous les 2 px).
 */
function vao_has_transparency( $image ) {
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

printf( "Images : %.1f Mo -> %.1f Mo servis en WebP\n", $before / 1048576, $after / 1048576 );
