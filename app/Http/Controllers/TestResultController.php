<?php

namespace App\Http\Controllers;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\PsychotestParticipant;
use Illuminate\Support\Facades\Storage;
use App\Models\TestResult;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class TestResultController extends Controller
{
    public function generateTestResult($participantId)
    {
        Carbon::setLocale('id');
        $participant = PsychotestParticipant::findOrFail($participantId);

        // Jika belum ada TestResult, buat kosong (sementara) agar tidak error
        $testResults = TestResult::firstOrNew(['participant_id' => $participantId]);

        if (!$testResults->exists) {
            $testResults->raw_score = 0;
            $testResults->iq = 0;
            $testResults->category = '-';
            $testResults->save();

            $testResults->refresh();
        }

        // Hitung skor (contoh logika dummy: total jawaban yang tidak kosong)
        $answerCount = $participant->answers()->count();
        $rawScore = $answerCount;
        $iq = 80 + ($rawScore * 2); // Contoh logika konversi IQ
        $category = $this->determineCategory($iq);
        Log::info('IQ Category: ' . $category);
        Log::info('IQ Score: ' . $iq);
        Log::info('Raw Score: ' . $rawScore);

        // Update nilai
        $testResults->raw_score = $rawScore;
        $testResults->iq = $iq;
        $testResults->category = $category;
        $testResults->save();
        Log::info('Test Results Updated: ' . json_encode($testResults));

        // Generate view
        $html = view('test-result', [
            'participant' => $participant,
            'testResults' => $testResults,
            'answers' => [
                'raw_score' => $rawScore,
                'iq' => $iq,
                'category' => $category,
            ],
        ])->render();

        // Konfigurasi DomPDF untuk mendukung background dan gambar
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('isPhpEnabled', true);
        $options->set('defaultFont', 'Arial');

        // Inisialisasi DomPDF dengan options
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);

        // Set base path untuk gambar
        $dompdf->setBasePath(public_path());

        // Set paper size dan orientation
        $dompdf->setPaper('A4', 'portrait');

        // Render PDF
        $dompdf->render();

        // Simpan file PDF
        $filePath = 'test_results/test-' . $participantId . '.pdf';
        Storage::disk('public')->put($filePath, $dompdf->output());

        // Simpan path ke DB
        $testResults->pdf_path = $filePath;
        $testResults->save();

        return $filePath;
    }

    /**
     * Determine the IQ category based on the IQ score.
     *
     * @param int|float $iq
     * @return string
     */
    protected function determineCategory($iq)
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

    public function downloadTestResult($participantId)
    {
        // Cari test result berdasarkan participant_id
        $testResult = TestResult::where('participant_id', $participantId)->first();

        if ($testResult && file_exists(storage_path('app/public/' . $testResult->pdf_path))) {
            return response()->download(storage_path('app/public/' . $testResult->pdf_path));
        }

        return response()->json(['error' => 'Hasil tes tidak ditemukan'], 404);
    }
}
