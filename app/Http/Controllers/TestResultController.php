<?php

namespace App\Http\Controllers; // Hanya satu deklarasi namespace

use App\Models\PsychotestParticipant;
use App\Models\TestResult;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

class TestResultController extends Controller
{
    /**
     * Kunci jawaban SPM berdasarkan Set dan Nomor Item.
     * PASTIKAN INI LENGKAP DAN AKURAT SESUAI DOKUMEN ANDA.
     */
    private function getSpmKeys(): array
    {
        return [
            'A' => [1 => 4, 2 => 5, 3 => 1, 4 => 2, 5 => 6, 6 => 3, 7 => 6, 8 => 2, 9 => 1, 10 => 3, 11 => 4, 12 => 5],
            'B' => [1 => 2, 2 => 6, 3 => 1, 4 => 2, 5 => 1, 6 => 3, 7 => 5, 8 => 6, 9 => 4, 10 => 3, 11 => 4, 12 => 5],
            'C' => [1 => 8, 2 => 2, 3 => 3, 4 => 8, 5 => 7, 6 => 4, 7 => 5, 8 => 1, 9 => 7, 10 => 6, 11 => 1, 12 => 2],
            'D' => [1 => 3, 2 => 4, 3 => 3, 4 => 7, 5 => 8, 6 => 6, 7 => 5, 8 => 4, 9 => 1, 10 => 2, 11 => 5, 12 => 6],
            'E' => [1 => 7, 2 => 6, 3 => 8, 4 => 2, 5 => 1, 6 => 5, 7 => 1, 8 => 6, 9 => 3, 10 => 2, 11 => 4, 12 => 5],
        ];
    }

    /**
     * Maps a global question ID (1-60) to its SPM Set and Item Number.
     */
    private function mapGlobalIdToSmpSetItem(int $globalQuestionId): ?array
    {
        if ($globalQuestionId >= 1 && $globalQuestionId <= 12) {
            return ['set' => 'A', 'item' => $globalQuestionId];
        } elseif ($globalQuestionId >= 13 && $globalQuestionId <= 24) {
            return ['set' => 'B', 'item' => $globalQuestionId - 12];
        } elseif ($globalQuestionId >= 25 && $globalQuestionId <= 36) {
            return ['set' => 'C', 'item' => $globalQuestionId - 24];
        } elseif ($globalQuestionId >= 37 && $globalQuestionId <= 48) {
            return ['set' => 'D', 'item' => $globalQuestionId - 36];
        } elseif ($globalQuestionId >= 49 && $globalQuestionId <= 60) {
            return ['set' => 'E', 'item' => $globalQuestionId - 48];
        }
        return null; // ID tidak valid
    }

    /**
     * Mengkonversi Skor Benar (Raw Score) ke detail Klasifikasi SPM sesuai norma baru yang lengkap.
     *
     * @param int $rawScore
     * @return array ['level' => string, 'classification' => string]
     */
    private function getSmpClassificationFromNewNorm(int $rawScore): array
    {
        $level = '';
        $classification = '';

        if ($rawScore >= 56 && $rawScore <= 60) { // Asumsi skor >58 masuk kategori tertinggi
            $level = 'I';
            $classification = 'INTELLECTUALLY SUPERIOR';
        } elseif ($rawScore >= 53) { // 53-55
            $level = 'II+';
            $classification = 'DEFINITELY ABOVE THE AVERAGE INTELLECTUAL CAPACITY';
        } elseif ($rawScore >= 51) { // 51-52
            $level = 'II';
            $classification = 'DEFINITELY ABOVE THE AVERAGE INTELLECTUAL CAPACITY';
        } elseif ($rawScore >= 46) { // 46-50
            $level = 'III+';
            $classification = 'INTELLECTUALLY AVERAGE';
        } elseif ($rawScore >= 35) { // 35-45
            $level = 'III';
            $classification = 'INTELLECTUALLY AVERAGE';
        } elseif ($rawScore >= 28) { // 28-34
            $level = 'III-';
            $classification = 'DEFINITELY BELOW THE AVERAGE INTELLECTUAL CAPACITY';
        } elseif ($rawScore >= 12) { // 12-27
            $level = 'IV';
            $classification = $rawScore >= 21 ? 'DEFINITELY BELOW THE AVERAGE INTELLECTUAL CAPACITY' : 'INTELLECTUALITY DEFECTIVE';
        } else { // 0-11
            $level = 'V';
            $classification = 'INTELLECTUALITY DEFECTIVE';
        }

        return [
            'level' => $level,
            'classification' => $classification,
        ];
    }

    /**
     * Mendapatkan interpretasi dinamis berdasarkan level dan klasifikasi
     */
    private function getInterpretationText(string $level, string $classification): array
    {
        $interpretations = [
            'I' => [
                'level_text' => 'sangat superior',
                'description' => 'Peserta menunjukkan kemampuan intelektual yang sangat superior dengan kemampuan luar biasa dalam memahami pola-pola logis kompleks dan menyelesaikan masalah visual dengan sangat efisien. Peserta dapat mengikuti pelatihan atau pekerjaan yang membutuhkan pemikiran analitis tingkat tinggi dengan sangat baik dan mampu menangani tugas-tugas kompleks dengan mudah.'
            ],
            'II+' => [
                'level_text' => 'di atas rata-rata (tinggi)',
                'description' => 'Peserta menunjukkan kemampuan intelektual yang definitif di atas rata-rata dengan kemampuan baik dalam memahami pola-pola logis dan menyelesaikan masalah visual secara efisien. Peserta dapat mengikuti pelatihan atau pekerjaan yang membutuhkan pemikiran analitis dengan baik dan mampu menangani tugas-tugas yang cukup kompleks.'
            ],
            'II' => [
                'level_text' => 'di atas rata-rata',
                'description' => 'Peserta menunjukkan kemampuan intelektual yang di atas rata-rata dengan kemampuan baik dalam memahami pola-pola logis dan menyelesaikan masalah visual. Peserta dapat mengikuti pelatihan atau pekerjaan yang membutuhkan pemikiran analitis dengan baik.'
            ],
            'III+' => [
                'level_text' => 'rata-rata (tinggi)',
                'description' => 'Peserta menunjukkan kemampuan intelektual rata-rata dengan kecenderungan ke arah yang baik dalam memahami pola-pola logis dan menyelesaikan masalah visual. Peserta dapat mengikuti pelatihan atau pekerjaan yang membutuhkan pemikiran analitis dasar dengan baik.'
            ],
            'III' => [
                'level_text' => 'rata-rata',
                'description' => 'Peserta menunjukkan kemampuan intelektual rata-rata dengan kemampuan memahami pola-pola logis dan menyelesaikan masalah visual secara standar. Peserta dapat mengikuti pelatihan atau pekerjaan yang membutuhkan pemikiran analitis dasar dengan baik, namun mungkin memerlukan waktu tambahan dalam menyelesaikan tugas yang kompleks secara logis.'
            ],
            'III-' => [
                'level_text' => 'di bawah rata-rata',
                'description' => 'Peserta menunjukkan kemampuan intelektual di bawah rata-rata dengan kemampuan terbatas dalam memahami pola-pola logis dan menyelesaikan masalah visual. Peserta dapat mengikuti pelatihan atau pekerjaan dengan panduan tambahan dan memerlukan waktu lebih dalam menyelesaikan tugas yang kompleks.'
            ],
            'IV' => [
                'level_text' => 'definitif di bawah rata-rata',
                'description' => 'Peserta menunjukkan kemampuan intelektual yang definitif di bawah rata-rata dengan kesulitan dalam memahami pola-pola logis dan menyelesaikan masalah visual. Peserta memerlukan bimbingan intensif dan pendekatan pembelajaran yang disesuaikan untuk dapat mengikuti pelatihan atau pekerjaan.'
            ],
            'V' => [
                'level_text' => 'sangat di bawah rata-rata (defektif)',
                'description' => 'Peserta menunjukkan kemampuan intelektual yang sangat di bawah rata-rata dengan kesulitan signifikan dalam memahami pola-pola logis dan menyelesaikan masalah visual. Peserta memerlukan bimbingan khusus dan pendekatan pembelajaran yang sangat disesuaikan dengan kebutuhan individual.'
            ]
        ];

        return $interpretations[$level] ?? [
            'level_text' => 'tidak teridentifikasi',
            'description' => 'Hasil tes memerlukan evaluasi lebih lanjut untuk memberikan interpretasi yang akurat.'
        ];
    }

    /**
     * Generates and saves the psychotest result PDF.
     *
     * @param int $participantId
     * @return string|false Absolute path to the saved PDF file, or false on failure.
     */
    public function generateAndSaveTestResult(int $participantId): string|false
    {
        Carbon::setLocale('id');
        Log::info("[PDF Generation] Starting for participant ID: {$participantId}");

        try {
            $participant = PsychotestParticipant::with('answers')->findOrFail($participantId);
            $testResults = TestResult::firstOrNew(['participant_id' => $participantId]);

            // --- 1. Hitung Skor Mentah (Raw Score) ---
            $rawScore = 0;
            $smpKeys = $this->getSpmKeys();
            foreach ($participant->answers as $participantAnswer) {
                $globalQuestionId = $participantAnswer->question_id;
                $smpSetItem = $this->mapGlobalIdToSmpSetItem($globalQuestionId);
                if ($smpSetItem) {
                    $set = $smpSetItem['set'];
                    $itemNumber = $smpSetItem['item'];
                    if (isset($smpKeys[$set][$itemNumber]) && (string)$participantAnswer->answer == (string)$smpKeys[$set][$itemNumber]) {
                        $rawScore++;
                    }
                }
            }
            Log::info("[PDF Generation] Raw Score calculated: {$rawScore}");

            // --- 2. Dapatkan Klasifikasi Lengkap dari Norma Baru ---
            $classificationDetails = $this->getSmpClassificationFromNewNorm($rawScore);
            Log::info("[PDF Generation] Classification determined: ", $classificationDetails);

            // --- 3. Dapatkan Interpretasi Dinamis ---
            $interpretationData = $this->getInterpretationText($classificationDetails['level'], $classificationDetails['classification']);

            // --- 4. Update TestResult ---
            $testResults->raw_score = $rawScore;
            $testResults->level = $classificationDetails['level'];
            $testResults->category = $classificationDetails['classification'];
            $testResults->save();
            Log::info("[PDF Generation] TestResult saved/updated: ", $testResults->withoutRelations()->toArray());

            // --- 5. Render View HTML untuk PDF ---
            $viewName = 'test-result';
            if (!View::exists($viewName)) {
                Log::error("[PDF Generation] Failed: View '{$viewName}' not found for participant {$participantId}.");
                return false;
            }

            // Pass data interpretasi ke view
            $html = view($viewName, [
                'participant' => $participant,
                'testResults' => $testResults,
                'interpretationData' => $interpretationData
            ])->render();

            // --- 6. Konfigurasi dan Generate PDF dengan DomPDF ---
            $options = new Options();
            $options->set('chroot', realpath(base_path()));
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isRemoteEnabled', true);
            $options->set('isPhpEnabled', false);
            $options->set('defaultFont', 'Arial');

            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setBasePath(public_path());
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            // --- 7. Simpan File PDF ---
            $fileName = 'test-' . $participantId . '.pdf';
            $directoryPathInDisk = 'test_results';
            $fullPathInDisk = $directoryPathInDisk . '/' . $fileName;

            if (!Storage::disk('public')->exists($directoryPathInDisk)) {
                Storage::disk('public')->makeDirectory($directoryPathInDisk);
            }
            Storage::disk('public')->put($fullPathInDisk, $dompdf->output());

            // --- 8. Update Path PDF di Database ---
            $testResults->pdf_path = $fullPathInDisk;
            $testResults->save();
            Log::info("[PDF Generation] PDF path updated for participant ID: {$participantId}");

            return Storage::disk('public')->path($fullPathInDisk);
        } catch (\Exception $e) {
            Log::error("[PDF Generation] Exception for participant ID: {$participantId}. Error: " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return false;
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
        $testResult = TestResult::where('participant_id', $participantId)->first();

        if (!$testResult || !$testResult->pdf_path || !Storage::disk('public')->exists($testResult->pdf_path)) {
            Log::warning("[PDF Download] Failed: PDF record or file not found for participant ID {$participantId}.");
            return redirect()->back()->with('error', 'File hasil tes tidak ditemukan atau belum selesai diproses.');
        }

        $absolutePathToFile = Storage::disk('public')->path($testResult->pdf_path);
        $participant = PsychotestParticipant::find($participantId);
        $downloadName = 'Hasil_Psikotes_' .
            ($participant ? Str::slug($participant->name, '_') : 'participant') .
            '_' . $participantId . '.pdf';
        Log::info("[PDF Download] Attempting to download PDF: {$absolutePathToFile} as {$downloadName}");

        return response()->download($absolutePathToFile, $downloadName);
    }
}
