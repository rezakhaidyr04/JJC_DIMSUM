<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Traits\HasCustomId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasCustomId;

    protected static ?string $resolvedPrimaryKey = null;

    protected $primaryKey = 'id_user';

    public $incrementing = true;

    protected $keyType = 'int';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'whatsapp',
        'role',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'registration_otp_expires_at' => 'datetime',
        'registration_otp_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function getKeyName(): string
    {
        if (static::$resolvedPrimaryKey !== null) {
            return static::$resolvedPrimaryKey;
        }

        static::$resolvedPrimaryKey = Schema::hasColumn($this->getTable(), 'id_user') ? 'id_user' : 'id';

        return static::$resolvedPrimaryKey;
    }

    public function isOwner(): bool
    {
        return $this->role === 'owner';
    }

    public function isKaryawan(): bool
    {
        return $this->role === 'karyawan';
    }

    public function hasPendingRegistrationOtp(): bool
    {
        return filled($this->registration_otp_hash) && $this->registration_otp_verified_at === null;
    }

    public function registrationOtpMatches(string $otp): bool
    {
        if (! $this->hasPendingRegistrationOtp() || ! $this->registration_otp_expires_at) {
            return false;
        }

        if ($this->registration_otp_expires_at->isPast()) {
            return false;
        }

        return Hash::check($otp, $this->registration_otp_hash);
    }

    public function storeRegistrationOtp(string $otp, int $expiresInMinutes = 15): void
    {
        $this->forceFill([
            'registration_otp_hash' => Hash::make($otp),
            'registration_otp_expires_at' => now()->addMinutes($expiresInMinutes),
            'registration_otp_verified_at' => null,
        ])->save();
    }

    public function markRegistrationOtpAsVerified(): void
    {
        $this->forceFill([
            'registration_otp_hash' => null,
            'registration_otp_expires_at' => null,
            'registration_otp_verified_at' => now(),
            'email_verified_at' => now(),
        ])->save();
    }

    public function cabangDistribusis(): HasMany
    {
        return $this->hasMany(CabangDistribusi::class, 'user_id', $this->getKeyName());
    }
}
