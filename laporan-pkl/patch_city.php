<?php
$file = '/Users/aqib/Library/Mobile Documents/com~apple~CloudDocs/BKKBN/APP/kemendukbangga-datapubg/laporan-pkl/app/Services/ZoomAttendanceService.php';
$content = file_get_contents($file);

$content = str_replace(
    "\$attendees[] = \$item['name'];",
    "\$attendees[] = ['name' => \$item['name'], 'unsur' => strtoupper(trim(\$item['unsur'] ?? 'Tidak Diketahui'))];",
    $content
);

file_put_contents($file, $content);
