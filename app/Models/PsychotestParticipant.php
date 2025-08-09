<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Log;

class PsychotestParticipant extends Model
{
    use HasFactory;

    protected $fillable = [
        'access_code_id',
        'name',
        'email',
        'place_of_birth',
        'birthdate',
        'gender',
        'education',
        'test_date',
        'start_time',
        'finish_time',
        'session_token'
    ];

    /**
     * Ini akan membuat 'test_duration' selalu ada saat model dikirim sebagai JSON/array.
     *
     * @var array
     */
    protected $appends = ['test_duration'];

    /**
     * Casts untuk memastikan kolom tanggal adalah objek Carbon.
     */
    protected $casts = [
        'start_time' => 'datetime',
        'finish_time' => 'datetime',
        'birthdate' => 'date',
        'test_date' => 'date',
    ];

    public function accessCode(): BelongsTo
    {
        return $this->belongsTo(AccessCode::class, 'access_code_id');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(PsychotestAnswer::class, 'participant_id');
    }

    public function testResults()
    {
        return $this->hasOne(TestResult::class, 'participant_id');
    }

    /**
     * Accessor untuk menghitung durasi tes dalam menit.
     * Nama method 'getTestDurationAttribute' akan membuat atribut 'test_duration' tersedia.
     *
     * @return int|null
     */
    public function getTestDurationAttribute(): ?int
    {
        // Pastikan kedua kolom tidak null
        if ($this->start_time && $this->finish_time) {
            // Gunakan abs() untuk memastikan hasil selalu positif
            // dan pastikan urutan parameter benar (dari start ke finish)
            $duration = $this->start_time->diffInMinutes($this->finish_time);

            // Untuk debugging, bisa tambahkan log
            Log::info('Duration calculation:', [
                'participant_id' => $this->id,
                'start_time' => $this->start_time->toDateTimeString(),
                'finish_time' => $this->finish_time->toDateTimeString(),
                'duration' => $duration
            ]);

            // Kembalikan nilai absolut untuk memastikan tidak negatif
            return abs($duration);
        }

        return null; // Kembalikan null jika salah satu waktu tidak ada
    }
}
