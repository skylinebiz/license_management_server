<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class License extends Model
{
    protected $fillable = [
        'license_expiry',
        'license_status',
        'max_active_user',
        'max_attachment_size_mb',
        'host',
        'simultaneous_sessions',
        'active_users',
        'total_users',
    ];

    protected $casts = [
        'license_expiry' => 'date',
        'max_active_user' => 'integer',
        'simultaneous_sessions' => 'integer',
        'max_attachment_size_mb' => 'integer',
        'active_users' => 'integer',
        'total_users' => 'integer',
    ];

    protected static function booted(): void
    {
        // license_id is never client-supplied — always assigned here so it
        // can't be spoofed or duplicated via a form submission.
        static::creating(function (License $license) {
            if (! $license->license_id) {
                $license->license_id = static::generateUniqueLicenseId();
            }
        });
    }

    public static function generateUniqueLicenseId(): string
    {
        do {
            $candidate = static::generateLicenseId();
        } while (static::where('license_id', $candidate)->exists());

        return $candidate;
    }

    /**
     * LIC-XXXXX-XXXXX-XXXXX using Crockford's Base32 alphabet, which drops
     * visually ambiguous characters (0/O, 1/I/L, U) so codes are easy to
     * read and retype correctly.
     */
    protected static function generateLicenseId(): string
    {
        $alphabet = '0123456789ABCDEFGHJKMNPQRSTVWXYZ';
        $lastIndex = strlen($alphabet) - 1;

        $groups = [];
        for ($g = 0; $g < 3; $g++) {
            $group = '';
            for ($i = 0; $i < 5; $i++) {
                $group .= $alphabet[random_int(0, $lastIndex)];
            }
            $groups[] = $group;
        }

        return 'LIC-'.implode('-', $groups);
    }
}
