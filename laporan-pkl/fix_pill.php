<?php
$file = '/Users/mhusnulaqib/Developer/kemendukbangga-datapubg/laporan-pkl/resources/views/laporan-capaian.blade.php';
$content = file_get_contents($file);

// Remove the flex and adjust padding to visually center Poppins font
$content = preg_replace(
    '/(bg-yellow-400 px-6) py-2 (rounded-full shadow-\[0_5px_15px_rgba\(255,215,0,0\.4\)\] uppercase border-2 border-white) flex items-center justify-center" style="line-height: 1\.2;"/s',
    '$1 pt-3 pb-1.5 $2 inline-block" style="line-height: 1;"',
    $content
);

file_put_contents($file, $content);
echo "File updated.\n";
