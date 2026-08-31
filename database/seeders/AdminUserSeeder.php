<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Default super admin credentials for this app.
     * Change this password from /profile immediately after first login.
     */
    private const DEFAULT_EMAIL = 'admin@license-management.local';
    private const DEFAULT_PASSWORD = 'ChangeMe123!';

    /**
     * Seed the default super admin account.
     *
     * Idempotent: only creates the account if it doesn't already exist, so
     * re-running seeders won't clobber a password an admin has since changed.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => self::DEFAULT_EMAIL],
            [
                'name' => 'Super Admin',
                'password' => Hash::make(self::DEFAULT_PASSWORD),
                'email_verified_at' => now(),
            ]
        );
    }
}
