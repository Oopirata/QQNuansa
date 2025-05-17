<?php

namespace App\Http\Controllers;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\TestResult;
use App\Models\User;

class TestResultController extends Controller
{
    public function generateTestResult($participantId)
    {
        // Ambil data peserta
        $participant = User::find($participantId);
        $testResults = TestResult::where('participant_id', $participantId)->first();

        // Format hasil tes (contoh raw score dan IQ)
        $answers = [
            'raw_score' => $testResults->raw_score,
            'iq' => $testResults->iq,
            'category' => $this->determineCategory($testResults->iq),
        ];

        // Generate HTML dari view
        $html = view('test-result', compact('participant', 'testResults', 'answers'))->render();

        // Inisialisasi Dompdf
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->render();

        // Menyimpan hasil PDF ke server
        $output = $dompdf->output();
        $filePath = 'test_results/test-' . $participantId . '.pdf';
        file_put_contents(storage_path('app/public/' . $filePath), $output);

        // Simpan path ke database
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

    public function downloadTestResult($id)
    {
        $testResult = TestResult::find($id);

        if ($testResult && file_exists(storage_path('app/public/' . $testResult->pdf_path))) {
            return response()->download(storage_path('app/public/' . $testResult->pdf_path));
        } else {
            return response()->json(['error' => 'Hasil tes tidak ditemukan'], 404);
        }
    }
}
