<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Models\Presence;

class DownloadPresenceMedia extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'presences:download-media';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Unduh file fisik foto dan tanda tangan peserta absensi dari server lama ke local storage';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("=== Memulai Proses Pengunduhan (Crawling) Media Absensi ===");

        // 1. Ambil semua absensi yang memiliki data foto atau tanda tangan
        $presences = Presence::whereNotNull('photo')
            ->orWhereNotNull('signature')
            ->get();

        $total = $presences->count();
        $this->info("Menemukan {$total} data absensi yang akan diperiksa.\n");

        $downloadedPhotos = 0;
        $downloadedSignatures = 0;
        $failedPhotos = 0;
        $failedSignatures = 0;

        foreach ($presences as $index => $presence) {
            $num = $index + 1;
            $this->line("[{$num}/{$total}] Memeriksa absensi: {$presence->name} (NIP: " . ($presence->nip ?? '-') . ")");

            // A. Proses Pengunduhan Foto
            if ($presence->photo) {
                // Periksa apakah foto bertipe path file (bukan data base64)
                if (!str_starts_with($presence->photo, 'data:image')) {
                    $filename = basename($presence->photo);
                    $localPath = $presence->photo; // e.g. presences/photos/filename.jpg

                    if (Storage::exists($localPath)) {
                        $this->line("  - Foto {$filename} sudah ada di local storage. Dilewati.");
                    } else {
                        $url = "https://asustor.malangkota.go.id/pictures/" . $filename;
                        $this->line("  - Mengunduh foto dari: " . $url);

                        try {
                            $response = Http::withoutVerifying()->timeout(10)->get($url);

                            if ($response->successful()) {
                                Storage::put($localPath, $response->body());
                                $this->info("  ✓ Foto berhasil diunduh dan disimpan.");
                                $downloadedPhotos++;
                            } else {
                                $this->error("  ✗ Gagal mengunduh foto. Status HTTP: " . $response->status());
                                $failedPhotos++;
                            }
                        } catch (\Exception $e) {
                            $this->error("  ✗ Terjadi kesalahan koneksi saat mengunduh foto: " . $e->getMessage());
                            $failedPhotos++;
                        }
                    }
                }
            }

            // B. Proses Pengunduhan Tanda Tangan
            if ($presence->signature) {
                // Periksa apakah tanda tangan bertipe path file (bukan data base64)
                if (!str_starts_with($presence->signature, 'data:image')) {
                    $filename = basename($presence->signature);
                    $localPath = $presence->signature; // e.g. presences/signatures/filename.png

                    if (Storage::exists($localPath)) {
                        $this->line("  - Tanda tangan {$filename} sudah ada di local storage. Dilewati.");
                    } else {
                        // Sesuai screenshot database lama, file tanda tangan juga disimpan di folder pictures
                        $url = "https://asustor.malangkota.go.id/signature/" . $filename;
                        $this->line("  - Mengunduh tanda tangan dari: " . $url);

                        try {
                            $response = Http::withoutVerifying()->timeout(10)->get($url);

                            if ($response->successful()) {
                                Storage::put($localPath, $response->body());
                                $this->info("  ✓ Tanda tangan berhasil diunduh dan disimpan.");
                                $downloadedSignatures++;
                            } else {
                                $this->error("  ✗ Gagal mengunduh tanda tangan. Status HTTP: " . $response->status());
                                $failedSignatures++;
                            }
                        } catch (\Exception $e) {
                            $this->error("  ✗ Terjadi kesalahan koneksi saat mengunduh tanda tangan: " . $e->getMessage());
                            $failedSignatures++;
                        }
                    }
                }
            }
            $this->line(""); // Baris kosong pemisah
        }

        $this->info("=== Ringkasan Migrasi Media ===");
        $this->info("Foto Berhasil Diunduh: {$downloadedPhotos}");
        $this->info("Foto Gagal: {$failedPhotos}");
        $this->info("Tanda Tangan Berhasil Diunduh: {$downloadedSignatures}");
        $this->info("Tanda Tangan Gagal: {$failedSignatures}");
        $this->info("=================================");
    }
}
