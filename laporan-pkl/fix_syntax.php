<?php
$file = '/Users/mhusnulaqib/Developer/kemendukbangga-datapubg/laporan-pkl/resources/views/laporan-capaian.blade.php';
$content = file_get_contents($file);

$bad_js = <<<JS
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
                    }
                });
JS;

$good_js = <<<JS
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

$content = str_replace($bad_js, $good_js, $content);
file_put_contents($file, $content);
echo "Syntax fixed.\n";
