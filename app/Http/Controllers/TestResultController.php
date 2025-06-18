<?php

namespace App\Http\Controllers;

use App\Models\PsychotestParticipant;
use App\Models\TestResult; // Pastikan model TestResult di-import
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
// use Illuminate\Http\Request; // Tidak digunakan secara langsung di method ini, bisa dikomentari jika tidak ada method lain yang butuh
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View; // Untuk View::exists()
use Illuminate\Support\Str; // Untuk Str::slug()

class TestResultController extends Controller
{
    /**
     * Generates and saves the psychotest result PDF.
     *
     * @param int $participantId
     * @return string|false Absolute path to the saved PDF file, or false on failure.
     */
    public function generateAndSaveTestResult(int $participantId): string|false
    {
        Carbon::setLocale('id'); // Set locale untuk format tanggal Indonesia
        Log::info("[PDF Generation] Starting for participant ID: {$participantId}");

        try {
            // Eager load relasi answers untuk efisiensi jika akan diakses
            $participant = PsychotestParticipant::with('answers')->findOrFail($participantId);

            // Ambil atau buat instance TestResult baru
            $testResults = TestResult::firstOrNew(['participant_id' => $participantId]);

            // --- Logika Perhitungan Skor (Contoh) ---
            // Asumsi $participant->answers sudah di-load dengan eager loading
            $answerCount = $participant->answers->count();
            $rawScore = $answerCount; // Ganti dengan logika skor aktual Anda
            $iq = 80 + ($rawScore * 2); // Ganti dengan formula IQ aktual Anda
            $category = $this->determineCategory($iq);
            // --- Akhir Logika Perhitungan Skor ---

            // Update atau isi field skor di TestResult
            $testResults->raw_score = $rawScore;
            $testResults->iq = $iq;
            $testResults->category = $category;

            // PENTING: Simpan TestResult SEKARANG, SEBELUM dirender ke view.
            // Ini akan memastikan kolom 'created_at' (dan 'updated_at') diisi oleh Eloquent
            // jika ini adalah record baru, atau 'updated_at' diperbarui jika sudah ada.
            $testResults->save();
            Log::info("[PDF Generation] TestResult scores saved/updated for participant ID: {$participantId}", $testResults->withoutRelations()->toArray());
            // $testResults->refresh(); // Opsional: muat ulang model dari DB untuk mendapatkan timestamp yang mungkin di-generate DB (biasanya tidak perlu)


            // --- Persiapan dan Rendering View HTML untuk PDF ---
            $viewName = 'test-result'; // PASTIKAN INI ADALAH NAMA FILE BLADE ANDA (misal: resources/views/test-result.blade.php)

            if (!View::exists($viewName)) {
                Log::error("[PDF Generation] Failed: View '{$viewName}' not found for participant {$participantId}.");
                return false;
            }

            $dataForView = [
                'participant' => $participant,
                'testResults' => $testResults, // $testResults sekarang memiliki created_at yang valid
            ];

            $html = view($viewName, $dataForView)->render();
            Log::info("[PDF Generation] HTML for PDF rendered for participant ID: {$participantId}");


            // --- Konfigurasi dan Inisialisasi DomPDF ---
            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isRemoteEnabled', true);    // Untuk gambar dari URL (meskipun public_path lebih baik untuk lokal)
            $options->set('isPhpEnabled', false);      // Set false untuk keamanan jika tidak ada script PHP di HTML PDF
            $options->set('defaultFont', 'Arial');     // Atau font default yang Anda inginkan

            // Krusial untuk DomPDF agar bisa menemukan file gambar via public_path()
            $projectRootPath = realpath(base_path());
            $options->set('chroot', $projectRootPath);
            Log::info("[PDF Generation] DomPDF chroot set to: " . $projectRootPath);

            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setBasePath(public_path()); // Base path untuk path relatif di HTML (jika ada)
            $dompdf->setPaper('A4', 'portrait'); // Atur ukuran kertas dan orientasi
            Log::info("[PDF Generation] DomPDF initialized, HTML loaded, paper set for participant ID: {$participantId}");

            $dompdf->render();
            Log::info("[PDF Generation] PDF content rendered by DomPDF for participant ID: {$participantId}");


            // --- Penyimpanan File PDF ---
            $fileName = 'test-' . $participantId . '.pdf';
            // Path relatif di dalam disk 'public' (mengarah ke storage/app/public/)
            $directoryPathInDisk = 'test_results';
            $fullPathInDisk = $directoryPathInDisk . '/' . $fileName;

            // Pastikan direktori tujuan ada di storage/app/public/test_results
            if (!Storage::disk('public')->exists($directoryPathInDisk)) {
                Storage::disk('public')->makeDirectory($directoryPathInDisk);
                Log::info("[PDF Generation] Created directory: storage/app/public/{$directoryPathInDisk}");
            }

            // Simpan file PDF ke disk 'public' (storage/app/public/test_results/test-ID.pdf)
            Storage::disk('public')->put($fullPathInDisk, $dompdf->output());
            Log::info("[PDF Generation] PDF saved to disk 'public' at '{$fullPathInDisk}' for participant ID: {$participantId}");

            // Update record TestResult dengan path PDF dan simpan lagi
            $testResults->pdf_path = $fullPathInDisk; // Path seperti 'test_results/test-10.pdf'
            $testResults->save(); // Menyimpan pdf_path dan memperbarui updated_at
            Log::info("[PDF Generation] TestResult (pdf_path) updated and saved for participant ID: {$participantId}", $testResults->withoutRelations()->toArray());

            // Mengembalikan path file sistem absolut ke PDF yang baru disimpan
            return Storage::disk('public')->path($fullPathInDisk);
        } catch (\Exception $e) {
            Log::error("[PDF Generation] Exception for participant ID: {$participantId}. Error: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    /**
     * Determine the IQ category based on the IQ score.
     *
     * @param int|float $iq
     * @return string
     */
    protected function determineCategory($iq): string
    {
        if ($iq >= 130) {
            return 'Very Superior';
        } elseif ($iq >= 120) {
            return 'Superior';
        } elseif ($iq >= 110) {
            return 'High Average';
        } elseif ($iq >= 90) {
            return 'Average';
        } elseif ($iq >= 80) {
            return 'Low Average';
        } elseif ($iq >= 70) {
            return 'Borderline';
        } else {
            return 'Extremely Low';
        }
    }

    /**
     * Handles downloading of the generated test result PDF.
     *
     * @param int $participantId
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Http\RedirectResponse
     */
    public function downloadTestResult(int $participantId)
    {
        Log::info("[PDF Download] Request for participant ID: {$participantId}");
        $testResult = TestResult::where('participant_id', $participantId)->first();

        if (!$testResult) {
            Log::warning("[PDF Download] TestResult record not found for participant ID {$participantId}.");
            return redirect()->back()->with('error', 'Data hasil tes tidak ditemukan.');
        }

        // $testResult->pdf_path menyimpan path relatif dari root disk 'public'
        if ($testResult->pdf_path && Storage::disk('public')->exists($testResult->pdf_path)) {
            $absolutePathToFile = Storage::disk('public')->path($testResult->pdf_path);

            $participant = PsychotestParticipant::find($participantId);
            $downloadName = 'Hasil_Psikotes_' .
                ($participant ? Str::slug($participant->name, '_') : 'participant') .
                '_' . $participantId . '.pdf';
            Log::info("[PDF Download] Attempting to download PDF: {$absolutePathToFile} as {$downloadName}");

            return response()->download($absolutePathToFile, $downloadName);
        }

        Log::warning("[PDF Download] Failed: PDF file not found on disk for participant ID {$participantId} at expected path: " . ($testResult->pdf_path ?? 'N/A'));
        return redirect()->back()->with('error', 'File hasil tes tidak ditemukan di server atau belum selesai diproses.');
    }
}
