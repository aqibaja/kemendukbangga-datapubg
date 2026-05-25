<?php
$file = '/Users/mhusnulaqib/Developer/kemendukbangga-datapubg/laporan-pkl/resources/views/laporan-capaian.blade.php';
$content = file_get_contents($file);

// Find the yellow box classes and replace py-2 with pt-1 pb-3
$content = str_replace(
    'bg-yellow-400 px-6 py-2 rounded-full',
    'bg-yellow-400 px-6 pt-1 pb-3 rounded-full',
    $content
);

file_put_contents($file, $content);
echo "Padding updated.\n";
