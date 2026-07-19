<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\QrAttendance;
use App\Models\QrSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class QrAttendanceController extends Controller
{
    public function scan(Request $request)
    {
        $token = $request->query('token');
        
        if (!$token) {
            return $this->errorView('Token tidak ditemukan atau tidak valid.');
        }

        $decoded = base64_decode($token);
        $parts = explode('|', $decoded);
        
        if (count($parts) !== 3) {
            return $this->errorView('Format QR Code tidak valid.');
        }

        $sessionId = $parts[0];
        $expiresAt = $parts[1];
        $signature = $parts[2];

        // Verify Signature
        $expectedSignature = hash_hmac('sha256', $sessionId . '|' . $expiresAt, config('app.key'));
        if (!hash_equals($expectedSignature, $signature)) {
            return $this->errorView('QR Code ini palsu atau telah dimodifikasi.');
        }

        // Verify Expiry
        if (now()->timestamp > $expiresAt) {
            return $this->errorView('QR Code ini sudah kedaluwarsa. Silakan scan ulang QR Code terbaru di layar.');
        }

        $session = QrSession::find($sessionId);
        if (!$session || !$session->is_active) {
            return $this->errorView('Sesi presensi ini tidak ditemukan atau sudah ditutup.');
        }

        if ($session->end_time && now()->greaterThanOrEqualTo($session->end_time)) {
            return $this->errorView('Sesi presensi ini sudah berakhir pada waktu yang telah ditentukan.');
        }

        // Check if device already attended this session
        $cookieName = 'qr_attendance_session_' . $session->id;
        if ($request->hasCookie($cookieName)) {
            return $this->errorView('Perangkat ini sudah digunakan untuk mengisi presensi pada sesi ini.');
        }

        $employees = Employee::orderBy('nama')->get();

        return view('qr_attendance.scan', [
            'session' => $session,
            'employees' => $employees,
            'token' => $token
        ]);
    }

    public function submit(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'employee_id' => 'required|exists:employees,id',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        // Re-verify token
        $decoded = base64_decode($request->token);
        $parts = explode('|', $decoded);
        if (count($parts) !== 3) {
            return response()->json(['success' => false, 'message' => 'Token tidak valid.']);
        }

        $sessionId = $parts[0];
        $expiresAt = $parts[1];
        $signature = $parts[2];

        if (now()->timestamp > $expiresAt) {
            return response()->json(['success' => false, 'message' => 'Token kedaluwarsa. Silakan scan ulang QR di layar.']);
        }

        $session = QrSession::find($sessionId);
        if (!$session || !$session->is_active) {
            return response()->json(['success' => false, 'message' => 'Sesi presensi ditutup.']);
        }

        if ($session->end_time && now()->greaterThanOrEqualTo($session->end_time)) {
            return response()->json(['success' => false, 'message' => 'Sesi presensi sudah berakhir.']);
        }

        // Check Cookie again
        $cookieName = 'qr_attendance_session_' . $session->id;
        if ($request->hasCookie($cookieName)) {
            return response()->json(['success' => false, 'message' => 'Perangkat ini sudah dipakai untuk absen sesi ini.']);
        }

        // Calculate Distance
        $distance = $this->haversineGreatCircleDistance(
            $session->latitude, $session->longitude,
            $request->latitude, $request->longitude
        );

        $employee = Employee::find($request->employee_id);

        if ($distance > $session->radius_meters) {
            // Log failure
            QrAttendance::create([
                'qr_session_id' => $session->id,
                'employee_id' => $employee->id,
                'status' => 'out_of_range',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json([
                'success' => false, 
                'message' => 'Anda berada di luar jangkauan area presensi. Jarak terdeteksi: ' . round($distance) . ' meter. Batas maksimal: ' . $session->radius_meters . ' meter.'
            ]);
        }

        // Success! Generate a unique cookie ID
        $deviceCookieId = Str::uuid()->toString();

        QrAttendance::create([
            'qr_session_id' => $session->id,
            'employee_id' => $employee->id,
            'status' => 'success',
            'device_cookie_id' => $deviceCookieId,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Send to Google Sheets directly
        try {
            $apiUrl = env('QR_ATTENDANCE_SCRIPT_URL');
            if (!empty($apiUrl)) {
                Http::post($apiUrl, [
                    'action' => 'add_attendance',
                    'event_name' => $session->title,
                    'employee_name' => $employee->nama,
                    'employee_unsur' => $employee->unsur ?? 'Tidak Diketahui',
                    'employee_city' => $employee->kabupaten_kota ?? 'Tidak Diketahui',
                    'timestamp' => now()->timezone('Asia/Jakarta')->toDateTimeString()
                ]);
            }
        } catch (\Exception $e) {
            // We ignore errors here so the user still gets a success message
            Log::error('Failed to send attendance to GSheet: ' . $e->getMessage());
        }

        // Set Cookie (expires in 12 hours = 720 minutes)
        Cookie::queue($cookieName, $deviceCookieId, 720);

        return response()->json([
            'success' => true,
            'message' => 'Presensi berhasil dicatat!'
        ]);
    }

    private function errorView($message)
    {
        return view('qr_attendance.error', ['message' => $message]);
    }

    /**
     * Calculates the great-circle distance between two points, with
     * the Haversine formula.
     * @return float Distance in meters
     */
    private function haversineGreatCircleDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo)
    {
        $earthRadius = 6371000; // Radius in meters
        
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
            
        return $angle * $earthRadius;
    }
}
