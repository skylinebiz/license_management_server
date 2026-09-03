<?php

namespace Database\Seeders;

use App\Models\LicenseProvider;
use Illuminate\Database\Seeder;

class LicenseProviderSeeder extends Seeder
{
    /**
     * Seed the one global license provider record with placeholder values.
     *
     * Idempotent: only creates the row if none exists yet, so re-running
     * seeders never overwrites real provider details an admin has since
     * set via /admin/provider.
     */
    public function run(): void
    {
        LicenseProvider::query()->firstOrCreate([], [
            'name' => 'SkylineBiz Pvt. Ltd.',
            'email' => 'info@skylinebiz.in',
        ]);
    }
}
