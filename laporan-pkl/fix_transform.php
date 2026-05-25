<?php
$file = '/Users/mhusnulaqib/Developer/kemendukbangga-datapubg/laporan-pkl/resources/views/laporan-capaian.blade.php';
$content = file_get_contents($file);

// 1. Remove export-pill from the yellow box div
$content = str_replace(' rounded-full export-pill', ' rounded-full', $content);

// 2. Wrap the text of the yellow boxes in <span class="pill-text inline-block relative">
// The text is always all caps and might have brackets
$content = preg_replace(
    '/(bg-yellow-400 px-6 py-2 rounded-full [^>]*>)\s*([A-Z\s\(\)\-]+)\s*(<\/div>)/',
    '$1<span class="pill-text inline-block relative" style="top: 0px;">$2</span>$3',
    $content
);

// 3. Update the onclone script to use transform
$onclone_code = <<<JS
                const canvas = await html2canvas(target, { 
                    scale: 2, 
                    useCORS: true,
                    backgroundColor: null,
                    logging: false,
                    onclone: function(clonedDoc) {
                        const texts = clonedDoc.querySelectorAll('.pill-text');
                        texts.forEach(t => {
                            t.style.top = '-4px'; // Shift text up inside the box
                        });
                    }
                });
JS;

$content = preg_replace(
    '/const canvas = await html2canvas\(target, \{.*?onclone:.*?\n\s*\}\);/s',
    $onclone_code,
    $content
);

file_put_contents($file, $content);
echo "Transform fix applied.\n";
