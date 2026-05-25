<?php
$file = '/Users/mhusnulaqib/Developer/kemendukbangga-datapubg/laporan-pkl/resources/views/laporan-capaian.blade.php';
$content = file_get_contents($file);

// Fix yellow box alignment and vertical centering
$content = preg_replace(
    '/<div class="text-center font-bold (text-sm sm:text-[a-z]+) mb-6 text-teal-900 bg-yellow-400 inline-block px-6 py-2 rounded-full mx-auto block w-max shadow-\[0_5px_15px_rgba\(255,215,0,0\.4\)\] uppercase border-2 border-white">(.*?)<\/div>/s',
    '<div class="flex justify-center mb-6">
                            <div class="text-center font-bold $1 text-teal-900 bg-yellow-400 px-6 py-2 rounded-full shadow-[0_5px_15px_rgba(255,215,0,0.4)] uppercase border-2 border-white flex items-center justify-center" style="line-height: 1.2;">$2</div>
                        </div>',
    $content
);

// Fix dark green card headers vertical centering
$content = str_replace(
    '<div class="text-white font-bold mb-2 text-center border-b border-teal-700 pb-2">',
    '<div class="text-white font-bold mb-2 text-center border-b border-teal-700 pb-2" style="line-height: 1.2;">',
    $content
);

// Fix data rows vertical centering
$content = preg_replace(
    '/<div class="flex justify-between items-center (mb-[0-9\.]+|mt-[0-9]+ pt-[0-9]+ [^"]+)"(>.*?<\/div>)/s',
    '<div class="flex justify-between items-center $1" style="line-height: 1.2;"$2',
    $content
);

file_put_contents($file, $content);
echo "File updated.\n";
