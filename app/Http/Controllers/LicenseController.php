<?php

namespace App\Http\Controllers;

use App\Models\License;
use App\Models\LicenseProvider;
use Illuminate\Http\Request;

class LicenseController extends Controller
{
    /**
     * Fetch license data for the given host. The licensed application also
     * uses this same call to report its live usage — total_users (enabled +
     * disabled accounts) and active_users (enabled only) are optional query
     * params; when given, they're persisted before the record is returned.
     * A plain lookup (no params) is unaffected.
     */
    public function getByHost(Request $request, $host)
    {
        $license = License::where('host', $host)->first();

        if (!$license) {
            return response()->json([
                'message' => 'License not found for the given host.',
            ], 404);
        }

        $usage = $request->validate([
            'total_users' => ['sometimes', 'integer', 'min:0'],
            'active_users' => ['sometimes', 'integer', 'min:0'],
        ]);

        if (!empty($usage)) {
            $license->update($usage);
        }

        return response()->json($this->withProvider($license));
    }

    /**
     * License data plus the one global provider record (name/email) — the
     * provider is shared across every license, not stored per-license.
     */
    private function withProvider(License $license): array
    {
        $provider = LicenseProvider::current();

        return [
            ...$license->toArray(),
            'provider' => [
                'name' => $provider->name,
                'email' => $provider->email,
            ],
        ];
    }
}
