<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendVotingToGoogleSheets implements ShouldQueue
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
        private string $voterName,
        private string $voterType,
        private string $candidate1Name,
        private string $candidate2Name,
        private string $candidate3Name,
        private string $ipAddress,
        private string $timestamp,
    ) {}

    public function handle(): void
    {
        Http::timeout(25)->post($this->apiUrl, [
            'action'               => 'submit_vote',
            'voter_name'           => $this->voterName,
            'voter_type'           => $this->voterType,
            'candidate_golongan_1' => $this->candidate1Name,
            'candidate_golongan_2' => $this->candidate2Name,
            'candidate_golongan_3' => $this->candidate3Name,
            'ip_address'           => $this->ipAddress,
            'timestamp'            => $this->timestamp,
        ]);

        Log::info("GAS voting terkirim untuk voter: {$this->voterName}");
    }

    public function failed(\Throwable $exception): void
    {
        Log::error("GAS voting GAGAL setelah {$this->tries}x percobaan untuk voter: {$this->voterName}. Error: " . $exception->getMessage());
    }
}
