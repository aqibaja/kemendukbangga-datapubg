<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ZoomAttendanceService;
use Illuminate\Support\Facades\Cache;

class AbsensiZoomController extends Controller
{
    public function index(Request $request, ZoomAttendanceService $service)
    {
        $cacheKey = 'zoom_attendance_data_gsheet';
        
        // If cache is empty and not already syncing, return loading shell
        if (!Cache::has($cacheKey) && !$request->has('_sync')) {
            return view('absensi-zoom-loading');
        }
        // Only increment view count on main page load (no parameters)
        if (empty($request->all())) {
            Cache::increment('native_dashboard_zoom_views');
        }

        $events = $service->getEvents();
        $selectedEvent = $request->get('event');
        
        // Fix for old URLs that might have literal '+' signs encoded as %2B
        if ($selectedEvent) {
            $selectedEvent = str_replace('+', ' ', $selectedEvent);
        }

        $tab = $request->get('tab', 'kegiatan'); // kegiatan | peserta
        
        if (!$selectedEvent && count($events) > 0) {
            $selectedEvent = reset($events);
        }

        $citiesCount = [];
        $rankings = [];
        $searchPerson = $request->get('search_person', '');
        
        $totalAllAttendance = count($service->getAllData());

        if ($tab === 'kegiatan') {
            $citiesCount = $service->getStatsByEvent($selectedEvent);
        } else {
            $allRankings = $service->getOverallRankings();
            
            $rankings = $allRankings;
        }

        return view('absensi-zoom', [
            'title' => 'Dashboard Absensi Zoom',
            'events' => $events,
            'selectedEvent' => $selectedEvent,
            'citiesCount' => $citiesCount,
            'tab' => $tab,
            'rankings' => $rankings,
            'searchPerson' => $searchPerson,
            'totalAllAttendance' => $totalAllAttendance
        ]);
    }

    public function personDetail($name, ZoomAttendanceService $service)
    {
        $details = $service->getPersonDetails(urldecode($name));
        return view('absensi-zoom-person', [
            'title' => 'Detail Peserta: ' . $details['name'],
            'details' => $details
        ]);
    }

    public function cityDetail($city, Request $request, ZoomAttendanceService $service)
    {
        $eventName = urldecode($request->get('event'));
        if (!$eventName) {
            return redirect()->route('absensi-zoom')->with('error', 'Event tidak dipilih');
        }

        $decodedCity = urldecode($city);
        $attendees = $service->getAttendeesByCityAndEvent($decodedCity, $eventName);

        return view('absensi-zoom-city', [
            'title' => 'Detail Kehadiran: ' . $decodedCity,
            'city' => $decodedCity,
            'eventName' => $eventName,
            'attendees' => $attendees
        ]);
    }
}
