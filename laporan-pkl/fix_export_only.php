<?php
$file = '/Users/mhusnulaqib/Developer/kemendukbangga-datapubg/laporan-pkl/resources/views/laporan-capaian.blade.php';
$content = file_get_contents($file);

// 1. Revert back to py-2 but add a unique class 'export-pill'
$content = str_replace(
    'bg-yellow-400 px-6 pt-1 pb-3 rounded-full',
    'bg-yellow-400 px-6 py-2 rounded-full export-pill',
    $content
);

// 2. Add onclone callback to html2canvas
$onclone_code = <<<JS
                const canvas = await html2canvas(target, { 
                    scale: 2, 
                    useCORS: true,
                    backgroundColor: null,
                    logging: false,
                    onclone: function(clonedDoc) {
                        const pills = clonedDoc.querySelectorAll('.export-pill');
                        pills.forEach(p => {
                            p.classList.remove('py-2');
                            p.classList.add('pt-1', 'pb-4'); // Make pb even bigger for export to perfectly center it
                        });
                    }
                });
JS;

$content = preg_replace(
    '/const canvas = await html2canvas\(target, \{.*?\}\);/s',
    $onclone_code,
    $content
);

file_put_contents($file, $content);
echo "Export fix applied.\n";
