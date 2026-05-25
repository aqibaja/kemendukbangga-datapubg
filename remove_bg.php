<?php
function removeBackground($filename) {
    if (!file_exists($filename)) {
        echo "File not found: $filename\n";
        return;
    }
    
    // The files are actually JPEGs despite the .png extension
    $img = @imagecreatefromjpeg($filename);
    if (!$img) {
        $img = @imagecreatefrompng($filename); // fallback
    }
    if (!$img) {
        echo "Could not load image: $filename\n";
        return;
    }

    $width = imagesx($img);
    $height = imagesy($img);
    
    // Create a true color transparent image
    $outImg = imagecreatetruecolor($width, $height);
    imagesavealpha($outImg, true);
    $transColor = imagecolorallocatealpha($outImg, 0, 0, 0, 127);
    imagefill($outImg, 0, 0, $transColor);

    for ($x = 0; $x < $width; $x++) {
        for ($y = 0; $y < $height; $y++) {
            $color = imagecolorat($img, $x, $y);
            $r = ($color >> 16) & 0xFF;
            $g = ($color >> 8) & 0xFF;
            $b = $color & 0xFF;
            
            // Check if color is dark background (e.g., #041f1c is r=4, g=31, b=28)
            // Let's use a broader threshold for dark colors
            if ($r < 50 && $g < 70 && $b < 70) {
                // leave transparent
            } else {
                // Keep pixel (it's the golden part)
                $newColor = imagecolorallocatealpha($outImg, $r, $g, $b, 0);
                imagesetpixel($outImg, $x, $y, $newColor);
            }
        }
    }
    
    // Overwrite the file as a true PNG
    imagepng($outImg, $filename);
    imagedestroy($img);
    imagedestroy($outImg);
    echo "Saved $filename as true transparent PNG\n";
}

removeBackground('/Users/mhusnulaqib/Developer/kemendukbangga-datapubg/laporan-pkl/public/image/rumah_aceh.png');
removeBackground('/Users/mhusnulaqib/Developer/kemendukbangga-datapubg/laporan-pkl/public/image/masjid_emas.png');
// And the root ones just in case
removeBackground('/Users/mhusnulaqib/Developer/kemendukbangga-datapubg/public/image/rumah_aceh.png');
removeBackground('/Users/mhusnulaqib/Developer/kemendukbangga-datapubg/public/image/masjid_emas.png');
