<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendAttendanceToGoogleSheets implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Jumlah maksimal percobaan jika job gagal.
     */
    public int $tries = 3;

    /**
     * Timeout per percobaan (detik).
     */
    public int $timeout = 30;

    public function __construct(
        private string $apiUrl,
        private string $eventName,
        private string $employeeName,
        private string $employeeUnsur,
        private string $employeeCity,
        private string $timestamp,
    ) {}

    public function handle(): void
    {
        Http::timeout(25)->post($this->apiUrl, [
            'action'          => 'add_attendance',
            'event_name'      => $this->eventName,
            'employee_name'   => $this->employeeName,
            'employee_unsur'  => $this->employeeUnsur,
            'employee_city'   => $this->employeeCity,
            'timestamp'       => $this->timestamp,
        ]);

        Log::info("GAS absensi terkirim untuk: {$this->employeeName} di event: {$this->eventName}");
    }

    public function failed(\Throwable $exception): void
    {
        Log::error("GAS absensi GAGAL setelah {$this->tries}x percobaan untuk: {$this->employeeName}. Error: " . $exception->getMessage());
    }
}
