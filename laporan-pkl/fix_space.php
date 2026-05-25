<?php
$file = '/Users/mhusnulaqib/Developer/kemendukbangga-datapubg/laporan-pkl/resources/views/laporan-capaian.blade.php';
$content = file_get_contents($file);

$old_onclone = <<<JS
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

$new_onclone = <<<JS
                const canvas = await html2canvas(target, { 
                    scale: 2, 
                    useCORS: true,
                    backgroundColor: null,
                    logging: false,
                    windowWidth: 1200, // Force a consistent width for export
                    onclone: function(clonedDoc) {
                        // Fix text centering
                        const texts = clonedDoc.querySelectorAll('.pill-text');
                        texts.forEach(t => {
                            t.style.top = '-4px'; // Shift text up inside the box
                        });
                        
                        // Fix excessive space
                        const poster = clonedDoc.getElementById('posterContent');
                        if (poster) {
                            poster.classList.remove('min-h-screen', 'sm:mx-4', 'mt-4');
                            poster.style.width = '1200px';
                            poster.style.margin = '0';
                            poster.style.borderRadius = '0'; // Make edges sharp for export
                        }
                    }
                });
JS;

$content = str_replace($old_onclone, $new_onclone, $content);
file_put_contents($file, $content);
echo "Space fix applied.\n";
