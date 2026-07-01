<?php
$file = '/Users/aqib/Library/Mobile Documents/com~apple~CloudDocs/BKKBN/APP/kemendukbangga-datapubg/laporan-pkl/app/Services/ZoomAttendanceService.php';
$content = file_get_contents($file);

$content = str_replace(
    "\$attendees = array_unique(\$attendees);\n        sort(\$attendees);",
    "// Remove duplicates manually since it's multidimensional
        \$temp = [];
        foreach (\$attendees as \$a) {
            \$temp[\$a['name']] = \$a;
        }
        \$attendees = array_values(\$temp);
        usort(\$attendees, function(\$a, \$b) {
            return strcmp(\$a['name'], \$b['name']);
        });",
    $content
);

file_put_contents($file, $content);
