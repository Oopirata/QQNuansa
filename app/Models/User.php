<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Log; // Tambahkan untuk logging

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'gauth_id',
        'gauth_type',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed', // Pastikan password di-hash secara otomatis
        ];
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles')
            ->withTimestamps();
    }

    public function applicant(): HasOne
    {
        return $this->hasOne(Applicant::class, 'users_id');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }

    public function sentEmails(): HasMany
    {
        return $this->hasMany(Email::class, 'sender_id');
    }

    public function receivedEmails(): HasMany
    {
        return $this->hasMany(Email::class, 'receiver_id');
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted(): void // Atau `protected static function boot()` untuk Laravel < 8
    {
        parent::booted(); // Atau `parent::boot()`

        static::created(function ($user) {
            // Log bahwa event terpicu
            Log::info('User created event triggered.', ['user_id' => $user->id, 'email' => $user->email]);

            // Cek apakah user ini belum memiliki role sama sekali.
            if ($user->roles()->count() === 0) {
                $defaultRoleId = 2; // ID untuk role 'user' (sesuai permintaan Anda)

                // Pastikan role dengan ID tersebut ada sebelum mencoba attach
                $roleExists = Role::where('id', $defaultRoleId)->exists();

                if ($roleExists) {
                    $user->roles()->attach($defaultRoleId);
                    Log::info('Default role (ID: ' . $defaultRoleId . ') assigned to new user.', [
                        'user_id' => $user->id,
                    ]);
                } else {
                    Log::warning('Default role with ID ' . $defaultRoleId . ' not found in database.', [
                        'user_id' => $user->id,
                    ]);
                    // Pertimbangkan tindakan jika role default tidak ditemukan (misalnya, membuat role tersebut jika belum ada,
                    // atau melempar exception tergantung seberapa kritis ini).
                    // Untuk saat ini, kita hanya log peringatan.
                }
            } else {
                Log::info('User already has roles, skipping default role assignment.', ['user_id' => $user->id]);
            }
        });
    }
}
