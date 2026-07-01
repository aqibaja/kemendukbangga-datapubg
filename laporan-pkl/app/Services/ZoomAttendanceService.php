<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class ZoomAttendanceService
{
    public function getAllData()
    {
        $apiUrl = env('GOOGLE_APPS_SCRIPT_URL');

        if (empty($apiUrl)) {
            Log::error('GOOGLE_APPS_SCRIPT_URL is not set in .env');
            return [];
        }

        $cacheKey = 'zoom_attendance_data_gsheet';
        $cacheDuration = 300; // 5 minutes (in seconds)

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $attendees = [];
        
        // Increase memory limit to handle potentially huge JSON payload with empty rows
        ini_set('memory_limit', '512M');
        // Increase execution time to avoid PHP terminating the process while waiting for Google Apps Script
        ini_set('max_execution_time', 120);
        set_time_limit(120);
        
        $cityMap = [
            'Aceh Selatan' => '01', 'Aceh Tenggara' => '02', 'Aceh Timur' => '03',
            'Aceh Tengah' => '04', 'Aceh Barat' => '05', 'Aceh Besar' => '06',
            'Pidie' => '07', 'Aceh Utara' => '08', 'Simeulue' => '09',
            'Aceh Singkil' => '10', 'Bireuen' => '11', 'Aceh Barat Daya' => '12',
            'Gayo Lues' => '13', 'Aceh Jaya' => '14', 'Nagan Raya' => '15',
            'Aceh Tamiang' => '16', 'Bener Meriah' => '17', 'Pidie Jaya' => '18',
            'Banda Aceh' => '71', 'Sabang' => '72', 'Lhokseumawe' => '73',
            'Langsa' => '74', 'Subulussalam' => '75'
        ];

        try {
            $response = Http::timeout(120)->get($apiUrl);
            
            if ($response->successful()) {
                $data = $response->json();
                
                if (is_array($data)) {
                    foreach ($data as $row) {
                        $event = trim($row['JUDUL KEGIATAN ZOOM'] ?? '');
                        $city = trim($row['Kabupaten / Kota'] ?? 'Tidak Diketahui');
                        if ($city === '') $city = 'Tidak Diketahui';
        $unsur = 'Tidak Diketahui';
                        
                        if (!$event) continue;

                        $name = '';
                        $unsur = '';
                        $cityCode = $cityMap[$city] ?? null;

                        if ($cityCode) {
                            foreach ($row as $key => $value) {
                                $keyUpper = strtoupper($key);
                                if (strpos($keyUpper, $cityCode . ' ') === 0) {
                                    if (strpos($keyUpper, 'PESERT') !== false || strpos($keyUpper, 'NAMA') !== false) {
                                        if (strpos($keyUpper, 'UNSUR') === false && strpos($keyUpper, 'WHATAPPS') === false && strpos($keyUpper, 'KECAMATAN') === false) {
                                            $val = trim($value);
                                            if ($val !== '') {
                                                $name = $val;
                                            }
                                        }
                                    }
                                    if (strpos($keyUpper, 'UNSUR') !== false) {
                                        $val = trim($value);
                                        if ($val !== '') {
                                            $unsur = $val;
                                        }
                                    }
                                }
                            }
                        }
                        
                        if ($name === '' && isset($row['NAMA'])) {
                            $name = trim($row['NAMA']);
                        }
                        if ($unsur === '' && isset($row['UNSUR'])) {
                            $unsur = trim($row['UNSUR']);
                        }

                        if ($name !== '') {
                            $attendees[] = [
                                'name' => $name,
                                'event' => $event,
                                'city' => $city,
                                'unsur' => $unsur !== '' ? $unsur : 'Tidak Diketahui'
                            ];
                        }
                    }
                } else {
                    Log::error('Invalid JSON format received from Google Apps Script. Body: ' . substr($response->body(), 0, 500));
                }
            } else {
                Log::error('Failed to fetch data from Google Apps Script. Status: ' . $response->status());
            }
        } catch (\Exception $e) {
            Log::error('Exception when fetching from Google Apps Script: ' . $e->getMessage());
        }

        if (!empty($attendees)) {
            Cache::put($cacheKey, $attendees, $cacheDuration);
        }

        return $attendees;
    }

    public function getEvents()
    {
        $data = $this->getAllData();
        $events = [];
        foreach ($data as $item) {
            $events[$item['event']] = true;
        }
        return array_reverse(array_keys($events));
    }

    public function getStatsByEvent($selectedEvent)
    {
        $data = $this->getAllData();
        $citiesCount = [];

        foreach ($data as $item) {
            if ($item['event'] === $selectedEvent) {
                $city = $item['city'];
                if (!isset($citiesCount[$city])) {
                    $citiesCount[$city] = 0;
                }
                $citiesCount[$city]++;
            }
        }

        arsort($citiesCount);
        return $citiesCount;
    }

    
    public function getUnsurStatsByEvent($selectedEvent)
    {
        $data = $this->getAllData();
        $unsurCount = [];

        foreach ($data as $item) {
            if ($item['event'] === $selectedEvent) {
                $unsur = trim($item['unsur'] ?? 'Tidak Diketahui');
                // Normalize case
                $unsur = strtoupper($unsur);
                if ($unsur === '') $unsur = 'TIDAK DIKETAHUI';
                
                if (!isset($unsurCount[$unsur])) {
                    $unsurCount[$unsur] = 0;
                }
                $unsurCount[$unsur]++;
            }
        }

        arsort($unsurCount);
        return $unsurCount;
    }

    public function getOverallRankings()
    {
        $data = $this->getAllData();
        $events = $this->getEvents();
        $totalEvents = count($events);
        
        $persons = [];

        foreach ($data as $item) {
            $name = $item['name'];
            if (!isset($persons[$name])) {
                $persons[$name] = [
                    'name' => $name,
                    'city' => $item['city'],
                    'unsur' => strtoupper(trim($item['unsur'] ?? 'Tidak Diketahui')),
                    'events_attended' => [],
                ];
            }
            $persons[$name]['events_attended'][$item['event']] = true;
        }

        $rankings = [];
        foreach ($persons as $name => $personData) {
            $attendedCount = count($personData['events_attended']);
            $percentage = $totalEvents > 0 ? round(($attendedCount / $totalEvents) * 100, 1) : 0;
            
            $rankings[] = [
                'name' => $name,
                'city' => $personData['city'],
                'unsur' => $personData['unsur'],
                'attended_count' => $attendedCount,
                'percentage' => $percentage
            ];
        }

        // Sort by attended_count DESC
        usort($rankings, function($a, $b) {
            return $b['attended_count'] <=> $a['attended_count'];
        });

        return $rankings;
    }

    public function getPersonDetails($name)
    {
        $data = $this->getAllData();
        $eventsAttended = [];
        $city = 'Tidak Diketahui';
        $unsur = 'Tidak Diketahui';

        foreach ($data as $item) {
            if (strtolower($item['name']) === strtolower($name)) {
                $eventsAttended[$item['event']] = true;
                $city = $item['city'];
                $unsur = strtoupper(trim($item['unsur'] ?? 'Tidak Diketahui'));
            }
        }
        
        $totalEvents = count($this->getEvents());
        $attendedCount = count($eventsAttended);
        $percentage = $totalEvents > 0 ? round(($attendedCount / $totalEvents) * 100, 1) : 0;

        return [
            'name' => $name,
            'city' => $city,
            'unsur' => $unsur,
            'attended_count' => $attendedCount,
            'total_events' => $totalEvents,
            'percentage' => $percentage,
            'events' => array_keys($eventsAttended)
        ];
    }

    public function getAttendeesByCityAndEvent($city, $eventName)
    {
        $data = $this->getAllData();
        $attendees = [];
        
        foreach ($data as $item) {
            if ($item['event'] === $eventName && $item['city'] === $city) {
                $attendees[] = ['name' => $item['name'], 'unsur' => strtoupper(trim($item['unsur'] ?? 'Tidak Diketahui'))];
            }
        }
        
        // Remove duplicates if any
        // Remove duplicates manually since it's multidimensional
        $temp = [];
        foreach ($attendees as $a) {
            $temp[$a['name']] = $a;
        }
        $attendees = array_values($temp);
        usort($attendees, function($a, $b) {
            return strcmp($a['name'], $b['name']);
        });
        
        return $attendees;
    }
}
