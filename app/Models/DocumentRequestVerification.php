<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DocumentRequestVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'verification_token',
        'request_data',
        'expires_at',
        'is_verified',
        'verified_at'
    ];

    protected $casts = [
        'request_data' => 'array',
        'expires_at' => 'datetime',
        'verified_at' => 'datetime',
        'is_verified' => 'boolean'
    ];

    /**
     * Generate a unique verification token
     */
    public static function generateToken()
    {
        do {
            $token = Str::random(64);
        } while (static::where('verification_token', $token)->exists());

        return $token;
    }

    /**
     * Check if verification has expired
     */
    public function isExpired()
    {
        return $this->expires_at->isPast();
    }

    /**
     * Mark verification as verified
     */
    public function markAsVerified()
    {
        $this->update([
            'is_verified' => true,
            'verified_at' => now()
        ]);
    }

    /**
     * Scope for active (non-expired) verifications
     */
    public function scopeActive($query)
    {
        return $query->where('expires_at', '>', now());
    }

    /**
     * Scope for pending verifications
     */
    public function scopePending($query)
    {
        return $query->where('is_verified', false);
    }
}
