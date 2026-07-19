<?php

$csvFile = '../example/PRESENSI ZOOM (Responses)NEW - Form Responses 1.csv';
$lines = file($csvFile);
$header = str_getcsv(array_shift($lines));

$cityMap = [
    'Aceh Selatan' => '01',
    'Aceh Tenggara' => '02',
    'Aceh Timur' => '03',
    'Aceh Tengah' => '04',
    'Aceh Barat' => '05',
    'Aceh Besar' => '06',
    'Pidie' => '07',
    'Aceh Utara' => '08',
    'Simeulue' => '09',
    'Aceh Singkil' => '10',
    'Bireuen' => '11',
    'Aceh Barat Daya' => '12',
    'Gayo Lues' => '13',
    'Aceh Jaya' => '14',
    'Nagan Raya' => '15',
    'Aceh Tamiang' => '16',
    'Bener Meriah' => '17',
    'Pidie Jaya' => '18',
    'Banda Aceh' => '71',
    'Sabang' => '72',
    'Lhokseumawe' => '73',
    'Langsa' => '74',
    'Subulussalam' => '75'
];

$names = [];

foreach ($lines as $line) {
    $rowValues = str_getcsv($line);
    if (count($rowValues) !== count($header)) {
        continue;
    }
    $row = array_combine($header, $rowValues);
    
    $event = trim($row['JUDUL KEGIATAN ZOOM'] ?? '');
    $city = trim($row['Kabupaten / Kota'] ?? 'Tidak Diketahui');
    if ($city === '') $city = 'Tidak Diketahui';
    
    if (!$event) continue;

    $name = '';
    $cityCode = $cityMap[$city] ?? null;

    if ($cityCode) {
        foreach ($row as $key => $value) {
            $keyUpper = strtoupper($key);
            if (strpos($keyUpper, $cityCode . ' ') === 0 && (strpos($keyUpper, 'PESERT') !== false || strpos($keyUpper, 'NAMA') !== false)) {
                if (strpos($keyUpper, 'UNSUR') === false && strpos($keyUpper, 'WHATAPPS') === false && strpos($keyUpper, 'KECAMATAN') === false) {
                    $val = trim($value);
                    if ($val !== '') {
                        $name = $val;
                        break;
                    }
                }
            }
        }
    }

    if ($name !== '') {
        if (strpos(strtoupper($event), 'RADALGRAM JANUARI 2026') !== false) {
            $names[] = $name;
        }
    }
}

$uniqueNames = array_unique($names);
echo "Total unique Radalgram Attendees: " . count($uniqueNames) . "\n";
