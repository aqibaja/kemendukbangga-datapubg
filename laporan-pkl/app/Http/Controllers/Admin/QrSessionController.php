<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QrSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Generator;

class QrSessionController extends Controller
{
    public function index()
    {
        $sessions = QrSession::with('creator')->latest()->get();
        return view('admin.qr_sessions.index', [
            'title' => 'Sesi Presensi QR',
            'sessions' => $sessions
        ]);
    }

    public function create()
    {
        return view('admin.qr_sessions.create', [
            'title' => 'Buat Sesi Presensi Baru'
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius_meters' => 'required|integer|min:1',
            'refresh_time_seconds' => 'required|integer|min:5|max:300',
            'end_time' => 'nullable|date',
        ]);

        QrSession::create([
            'title' => $request->title,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'radius_meters' => $request->radius_meters,
            'refresh_time_seconds' => $request->refresh_time_seconds,
            'end_time' => $request->end_time,
            'is_active' => true,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('admin.qr_sessions.index')->with('success', 'Sesi presensi berhasil dibuat.');
    }

    public function show(QrSession $qr_session)
    {
        return view('admin.qr_sessions.show', [
            'title' => 'Layar QR Presensi',
            'session' => $qr_session
        ]);
    }

    public function generateQr(QrSession $qr_session)
    {
        try {
            if (!$qr_session->is_active) {
                return response()->json(['error' => 'Session is inactive'], 403);
            }

            if ($qr_session->end_time && now()->greaterThanOrEqualTo($qr_session->end_time)) {
                // Sesi sudah mencapai batas waktu berakhir
                return response()->json(['error' => 'Sesi telah berakhir', 'is_ended' => true], 403);
            }

            // Generate a token that expires in refresh_time_seconds.
            // It includes the session ID and the expiry timestamp.
            $expiresAt = now()->addSeconds((int) $qr_session->refresh_time_seconds)->timestamp;
            $data = $qr_session->id . '|' . $expiresAt;
            
            // Simple hash to prevent tampering (HMAC)
            $signature = hash_hmac('sha256', $data, config('app.key'));
            
            $token = base64_encode($data . '|' . $signature);
            
            $url = route('qr_attendance.scan', ['token' => $token]);
            
            $qrCodeSvg = (new Generator)->size(400)->generate($url)->toHtml();
            
            return response()->json([
                'svg' => $qrCodeSvg,
                'url' => $url,
                'expires_at' => $expiresAt
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Server Error: ' . $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    }

    public function toggleActive(QrSession $qr_session)
    {
        $qr_session->update(['is_active' => !$qr_session->is_active]);
        return redirect()->route('admin.qr_sessions.index')->with('success', 'Status sesi berhasil diubah.');
    }

    public function destroy(QrSession $qr_session)
    {
        $qr_session->delete();
        return redirect()->route('admin.qr_sessions.index')->with('success', 'Sesi presensi berhasil dihapus.');
    }
}
