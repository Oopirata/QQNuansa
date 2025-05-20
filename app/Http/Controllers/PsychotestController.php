<?php

namespace App\Http\Controllers;

use App\Models\PsychotestSession;
use App\Models\AccessCode;
use App\Models\PsychotestParticipant;
use App\Models\PsychotestAnswer;
use App\Models\Question;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class PsychotestController extends Controller
{
    /*** ADMIN SECTION ***/

    // Method untuk halaman daftar sesi psikotest (admin)
    public function index()
    {
        $sessions = PsychotestSession::with('creator')
            ->withCount(['accessCodes as used_codes' => function ($query) {
                $query->whereIn('status', ['active', 'completed']);
            }])
            ->withCount('accessCodes as total_codes')
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('AdminPsychotestIndex', [
            'sessions' => $sessions
        ]);
    }

    // Method untuk form pembuatan sesi psikotest baru (admin)
    public function create()
    {
        return Inertia::render('AdminPsychotestCreate');
    }

    // Method untuk menyimpan sesi psikotest baru (admin)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'max_participants' => 'required|integer|min:1',
        ]);

        // Tentukan status aktif berdasarkan waktu saat ini
        $now = now();
        $start_date = \Carbon\Carbon::parse($validated['start_date']);
        $end_date = \Carbon\Carbon::parse($validated['end_date']);
        $is_active = ($now >= $start_date && $now <= $end_date);

        Log::info('Creating new session', [
            'start_date' => $start_date->toDateTimeString(),
            'end_date' => $end_date->toDateTimeString(),
            'current_time' => $now->toDateTimeString(),
            'is_active' => $is_active
        ]);

        $session = PsychotestSession::create([
            ...$validated,
            'created_by' => Auth::user()->id,
            'is_active' => $is_active // Set status aktif secara eksplisit
        ]);

        // Generate unique access codes
        $codes = [];
        for ($i = 0; $i < $validated['max_participants']; $i++) {
            $code = strtoupper(Str::random(8));
            while (AccessCode::where('code', $code)->exists()) {
                $code = strtoupper(Str::random(8));
            }
            $codes[] = [
                'session_id' => $session->id,
                'code' => $code,
                'status' => 'unused',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        AccessCode::insert($codes);

        // Tambahkan delay kecil untuk memastikan semua data tersimpan
        usleep(500000); // 0.5 detik

        // Redirect dengan pesan sukses
        return redirect()->route('admin.psychotest.index')
            ->with('message', 'Sesi psikotest berhasil dibuat');
    }

    // Method untuk menampilkan detail sesi psikotest (admin)
    public function show($id)
    {
        $session = PsychotestSession::with(['accessCodes' => function ($query) {
            $query->with('participant');
        }])->findOrFail($id);

        return Inertia::render('AdminPsychotestShow', [
            'session' => $session
        ]);
    }

    // Method untuk mengaktifkan/menonaktifkan sesi (admin)
    public function toggleStatus($id)
    {
        $session = PsychotestSession::findOrFail($id);
        $session->update(['is_active' => !$session->is_active]);

        return response()->json(['success' => true]);
    }

    // Method untuk melihat hasil psikotest (admin)
    public function viewResults($sessionId)
    {

        // Pastikan relasi accessCodes dimuat
        $session = PsychotestSession::with('accessCodes')->findOrFail($sessionId);
        // dd($session->accessCodes); // pastikan hasilnya Collection, bukan null

        // Ambil ID peserta dari access codes yang ada (pastikan tidak null)
        $participantIds = $session->accessCodes
            ->pluck('participant_id')
            ->filter() // buang null
            ->unique(); // hilangkan duplikat

        // Ambil data peserta dari tabel psychotest_participants, bukan User!
        $participants = \App\Models\PsychotestParticipant::whereIn('id', $participantIds)->get();

        return Inertia::render('AdminPsychotestResults', [
            'session' => $session,
            'participants' => $participants,
        ]);
    }

    // Method untuk mereset kode akses (admin)
    public function resetCode($id)
    {
        $accessCode = AccessCode::findOrFail($id);

        // Delete participant and answers
        if ($accessCode->participant) {
            $participant = $accessCode->participant;

            // Delete answers first (foreign key constraint)
            $participant->answers()->delete();

            // Delete participant
            $participant->delete();
        }

        // Reset code status
        $accessCode->update(['status' => 'unused']);

        return response()->json(['success' => true]);
    }

    /*** USER SECTION ***/

    // Method untuk menampilkan halaman entry psikotest (user)
    public function showEntryForm()
    {
        return Inertia::render('PsychotestEntry');
    }

    // Method untuk menampilkan halaman test psikotest (user)
    public function showTestForm()
    {
        $user = Auth::user();
        return Inertia::render('FormPage', [
            'user' => $user
        ]);
    }

    // Method untuk menampilkan hasil psikotest (user)
    public function showResults($participantId)
    {
        $participant = PsychotestParticipant::with(['answers.question', 'accessCode.session', 'testResults'])
            ->findOrFail($participantId);
        // dd($participant->testResults);

        return Inertia::render('Results', [
            'participant' => $participant
        ]);
    }


    // Method untuk verifikasi kode akses (user API)

    public function verifyCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string|exists:access_codes,code'
        ]);


        $accessCode = AccessCode::where('code', $request->code)
            ->with(['session', 'participant'])
            ->first();

        // Log::info('Access Code:', ['accessCode' => $accessCode]);
        // Log::info('Current User:', ['user' => Auth::user()]);

        if (!$accessCode) {
            return response()->json([
                'valid' => false,
                'message' => 'Kode akses tidak ditemukan.'
            ], 404);
        }

        // Cek apakah status sudah completed
        if ($accessCode->status === 'completed') {
            return response()->json([
                'valid' => false,
                'message' => 'Kode akses ini sudah digunakan dan test telah selesai.'
            ], 400);
        }

        $this->updateSessionStatus($accessCode->session);
        $session = $accessCode->session->fresh();

        if (!$session->is_active) {
            $now = now();
            $reason = $this->getSessionInactiveReason($session, $now);

            return response()->json([
                'valid' => false,
                'message' => 'Sesi psikotest belum dimulai atau sudah berakhir.',
                'detail' => $reason,
                'debug' => [
                    'now' => $now->toDateTimeString(),
                    'start_date' => $session->start_date,
                    'end_date' => $session->end_date,
                    'is_active' => $session->is_active
                ]
            ], 400);
        }

        // Ambil user login
        $currentUser = Auth::user();

        if ($accessCode->status === 'unused') {
            // Kode belum pernah dipakai, boleh pakai oleh siapa saja yang login
            return response()->json([
                'valid' => true,
                'status' => 'new',
                'sessionData' => [
                    'id' => $session->id,
                    'name' => $session->name
                ]
            ]);
        }

        if ($accessCode->status === 'active' && $accessCode->participant) {
            // Cek apakah peserta terkait kode ini sama dengan user login sekarang
            if ($accessCode->participant->email !== $currentUser->email) {
                return response()->json([
                    'valid' => false,
                    'message' => 'Kode akses ini sudah dipakai oleh peserta lain.'
                ], 403);
            }

            return response()->json([
                'valid' => true,
                'status' => 'resume',
                'participantData' => $accessCode->participant,
                'sessionData' => [
                    'id' => $session->id,
                    'name' => $session->name
                ]
            ]);
        }

        // Fallback
        return response()->json([
            'valid' => false,
            'message' => 'Status kode akses tidak valid',
            'status' => $accessCode->status
        ], 400);
    }


    // Tambahkan helper methods
    private function updateSessionStatus($session)
    {
        $now = now();
        $startDate = \Carbon\Carbon::parse($session->start_date);
        $endDate = \Carbon\Carbon::parse($session->end_date);

        // Force to UTC untuk comparisons yang konsisten
        $nowUtc = $now->copy()->utc();
        $startDateUtc = $startDate->copy()->utc();
        $endDateUtc = $endDate->copy()->utc();

        $shouldBeActive = ($nowUtc->gte($startDateUtc) && $nowUtc->lte($endDateUtc));

        Log::info('Session time check', [
            'session_id' => $session->id,
            'now_utc' => $nowUtc->toDateTimeString(),
            'start_utc' => $startDateUtc->toDateTimeString(),
            'end_utc' => $endDateUtc->toDateTimeString(),
            'now_gte_start' => $nowUtc->gte($startDateUtc),
            'now_lte_end' => $nowUtc->lte($endDateUtc),
            'shouldBeActive' => $shouldBeActive,
            'is_active_current' => $session->is_active
        ]);

        if ($shouldBeActive !== (bool)$session->is_active) {
            Log::info('Updating session status', [
                'from' => $session->is_active,
                'to' => $shouldBeActive
            ]);

            $session->is_active = $shouldBeActive;
            $session->save();
        }
    }

    private function getSessionInactiveReason($session, $now)
    {
        $startDate = \Carbon\Carbon::parse($session->start_date);
        $endDate = \Carbon\Carbon::parse($session->end_date);

        if ($now->lt($startDate)) {
            return 'Sesi belum dimulai. Sesi akan dimulai pada: ' . $startDate->format('d M Y H:i');
        } else if ($now->gt($endDate)) {
            return 'Sesi sudah berakhir. Sesi berakhir pada: ' . $endDate->format('d M Y H:i');
        } else {
            return 'Sesi dinonaktifkan secara manual oleh admin.';
        }
    }

    public function startTest(Request $request)
    {
        $request->validate([
            'code' => 'required|string|exists:access_codes,code',
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'birthdate' => 'required|date',
            'gender' => 'required|string',
            'education' => 'required|string',
            'placeOfBirth' => 'required|string',
            'testDate' => 'required|date',
        ]);

        try {

            return DB::transaction(function () use ($request) {
                // Cari kode akses dan lock untuk mencegah race condition
                $accessCode = AccessCode::where('code', $request->code)->lockForUpdate()->first();
                $existingParticipant = PsychotestParticipant::where('access_code_id', $accessCode->id)
                    ->whereNull('finish_time')
                    ->first();

                if ($existingParticipant) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Test sudah berjalan untuk kode akses ini, silakan lanjutkan test Anda.'
                    ], 400);
                }

                if (!$accessCode) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Kode akses tidak ditemukan.'
                    ], 404);
                }

                // Cek apakah kode akses sudah dipakai participant lain
                $exists = PsychotestParticipant::where('access_code_id', $accessCode->id)->exists();

                if ($exists) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Kode akses sudah dipakai oleh peserta lain.'
                    ], 400);
                }

                // Update status access code jadi aktif
                $accessCode->status = 'active';
                $accessCode->save();

                $sessionToken = Str::uuid();

                // Buat participant baru dan assign access_code_id
                $participant = PsychotestParticipant::create([
                    'access_code_id' => $accessCode->id,
                    'name' => $request->name,
                    'email' => $request->email,
                    'birthdate' => $request->birthdate,
                    'gender' => $request->gender,
                    'education' => $request->education,
                    'test_date' => $request->testDate,
                    'place_of_birth' => $request->placeOfBirth,
                    'start_time' => now(),
                    'session_token' => $sessionToken,
                ]);
                Log::info('Participant created', [
                    'placeOfBirth' => $request->placeOfBirth,
                ]);

                return response()->json([
                    'success' => true,
                    'participant' => [
                        'id' => $participant->id,
                        'name' => $participant->name,
                        'email' => $participant->email,
                        'birthdate' => $participant->birthdate,
                        'gender' => $participant->gender,
                        'education' => $participant->education,
                        'testDate' => $participant->test_date,
                        'placeOfBirth' => $participant->place_of_birth, // mapping manual
                    ],
                    'sessionToken' => $sessionToken,
                ]);
            });
        } catch (\Exception $e) {
            Log::error('Exception in startTest:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }



    // Method untuk mendapatkan jawaban yang tersimpan (user API)
    public function getAnswers($participantId, Request $request)
    {
        $request->validate([
            'sessionToken' => 'required|string'
        ]);

        $participant = PsychotestParticipant::where('id', $participantId)
            ->where('session_token', $request->sessionToken)
            ->firstOrFail();

        $answers = $participant->answers()->get()->mapWithKeys(function ($answer) {
            return [$answer->question_id => $answer->answer];
        });

        return response()->json([
            'answers' => $answers
        ]);
    }

    // Method untuk menyimpan progress jawaban (user API)
    public function saveProgress(Request $request)
    {
        try {
            // Log untuk debug
            Log::info('Saving progress', $request->all());

            $request->validate([
                'participant_id' => 'required|exists:psychotest_participants,id',
                'sessionToken' => 'required|string',
                'answers' => 'required|array'
            ]);

            // Verify session token
            $participant = PsychotestParticipant::where('id', $request->participant_id)
                ->where('session_token', $request->sessionToken)
                ->firstOrFail();

            // Delete existing answers to avoid duplicates
            PsychotestAnswer::where('participant_id', $participant->id)->delete();

            // Save or update answers
            foreach ($request->answers as $questionId => $answer) {
                if (!empty($answer)) {
                    PsychotestAnswer::create([
                        'participant_id' => $participant->id,
                        'question_id' => $questionId,
                        'answer' => $answer
                    ]);
                }
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Error saving progress: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Error saving progress: ' . $e->getMessage()
            ], 500);
        }
    }

    // Method untuk menyelesaikan test (user API)
    public function completeTest(Request $request)
    {
        $request->validate([
            'participant_id' => 'required|exists:psychotest_participants,id',
            'sessionToken' => 'required|string',
            'answers' => 'required|array',
            'birthdate' => 'nullable|date',
            'gender' => 'nullable|string',
            'education' => 'nullable|string',
        ]);

        $participant = PsychotestParticipant::where('id', $request->participant_id)
            ->where('session_token', $request->sessionToken)
            ->firstOrFail();

        // Simpan jawaban
        $this->saveProgress($request);

        // Update info peserta
        $participant->update([
            'birthdate' => $request->birthdate ?? $participant->birthdate,
            'gender' => $request->gender ?? $participant->gender,
            'education' => $request->education ?? $participant->education,
            'finish_time' => now(),
        ]);

        $participant->accessCode->update(['status' => 'completed']);

        // 🧾 Cetak PDF hasil tes
        app(\App\Http\Controllers\TestResultController::class)->generateTestResult($participant->id);

        return response()->json([
            'success' => true,
            'message' => 'Test berhasil diselesaikan',
            'resultUrl' => route('psychotest.result', $participant->id)
        ]);
    }
}
