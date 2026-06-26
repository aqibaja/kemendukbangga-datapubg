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
        
        try {
            $response = Http::timeout(30)->get($apiUrl);
            
            if ($response->successful()) {
                $data = $response->json();
                
                if (is_array($data)) {
                    foreach ($data as $row) {
                        $event = trim($row['JUDUL KEGIATAN ZOOM'] ?? '');
                        $city = trim($row['Kabupaten / Kota'] ?? 'Tidak Diketahui');
                        if ($city === '') $city = 'Tidak Diketahui';
                        
                        if (!$event) continue;

                        foreach ($row as $key => $value) {
                            if (strpos(strtoupper($key), 'NAMA PESERTA ZOOM') !== false || strtoupper($key) === 'NAMA') {
                                $name = trim($value);
                                if ($name !== '') {
                                    $attendees[] = [
                                        'name' => $name,
                                        'event' => $event,
                                        'city' => $city
                                    ];
                                }
                            }
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

        foreach ($data as $item) {
            if (strtolower($item['name']) === strtolower($name)) {
                $eventsAttended[$item['event']] = true;
                $city = $item['city']; // Assign last known city
            }
        }
        
        $totalEvents = count($this->getEvents());
        $attendedCount = count($eventsAttended);
        $percentage = $totalEvents > 0 ? round(($attendedCount / $totalEvents) * 100, 1) : 0;

        return [
            'name' => $name,
            'city' => $city,
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
                $attendees[] = $item['name'];
            }
        }
        
        // Remove duplicates if any
        $attendees = array_unique($attendees);
        sort($attendees);
        
        return $attendees;
    }
}
