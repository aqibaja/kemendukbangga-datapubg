<?php
$file = '/Users/aqib/Library/Mobile Documents/com~apple~CloudDocs/BKKBN/APP/kemendukbangga-datapubg/laporan-pkl/app/Services/ZoomAttendanceService.php';
$content = file_get_contents($file);

// Patch getOverallRankings
$content = str_replace(
    "'city' => \$item['city'],\n                    'events_attended' => [],",
    "'city' => \$item['city'],\n                    'unsur' => strtoupper(trim(\$item['unsur'] ?? 'Tidak Diketahui')),\n                    'events_attended' => [],",
    $content
);

$content = str_replace(
    "'city' => \$personData['city'],\n                'attended_count' => \$attendedCount,",
    "'city' => \$personData['city'],\n                'unsur' => \$personData['unsur'],\n                'attended_count' => \$attendedCount,",
    $content
);

// Patch getPersonDetails
$content = preg_replace(
    "/\\\$city = 'Tidak Diketahui';/",
    "\$city = 'Tidak Diketahui';\n        \$unsur = 'Tidak Diketahui';",
    $content
);

$content = preg_replace(
    "/\\\$city = \\\$item\\['city'\\]; \\/\\/ Assign last known city/",
    "\$city = \$item['city'];\n                \$unsur = strtoupper(trim(\$item['unsur'] ?? 'Tidak Diketahui'));",
    $content
);

$content = str_replace(
    "'city' => \$city,\n            'attended_count' => \$attendedCount,",
    "'city' => \$city,\n            'unsur' => \$unsur,\n            'attended_count' => \$attendedCount,",
    $content
);

file_put_contents($file, $content);
