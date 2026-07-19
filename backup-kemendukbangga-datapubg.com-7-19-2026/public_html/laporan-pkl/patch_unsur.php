<?php
$file = '/Users/aqib/Library/Mobile Documents/com~apple~CloudDocs/BKKBN/APP/kemendukbangga-datapubg/laporan-pkl/app/Services/ZoomAttendanceService.php';
$content = file_get_contents($file);

$newMethod = <<<METHOD

    public function getUnsurStatsByEvent(\$selectedEvent)
    {
        \$data = \$this->getAllData();
        \$unsurCount = [];

        foreach (\$data as \$item) {
            if (\$item['event'] === \$selectedEvent) {
                \$unsur = trim(\$item['unsur'] ?? 'Tidak Diketahui');
                // Normalize case
                \$unsur = strtoupper(\$unsur);
                if (\$unsur === '') \$unsur = 'TIDAK DIKETAHUI';
                
                if (!isset(\$unsurCount[\$unsur])) {
                    \$unsurCount[\$unsur] = 0;
                }
                \$unsurCount[\$unsur]++;
            }
        }

        arsort(\$unsurCount);
        return \$unsurCount;
    }
METHOD;

$content = str_replace("public function getOverallRankings()", $newMethod . "\n\n    public function getOverallRankings()", $content);
file_put_contents($file, $content);
