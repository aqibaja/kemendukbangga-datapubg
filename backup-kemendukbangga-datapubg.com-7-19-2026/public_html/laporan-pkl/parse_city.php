<?php
$json = file_get_contents('../KODE WILAYAH.json');
$data = json_decode($json, true);
$cityMap = [];
foreach ($data as $item) {
    if (isset($item['NAMA KABUPATEN']['KOTA']) && isset($item['KODE KABUPATEN']['KOTA'])) {
        $cityName = strtoupper($item['NAMA KABUPATEN']['KOTA']);
        $cityCode = str_pad($item['KODE KABUPATEN']['KOTA'], 2, '0', STR_PAD_LEFT);
        $cityMap[$cityName] = $cityCode;
    }
}
print_r($cityMap);
