<?php

namespace App\Http\Controllers;

use App\Models\License;
use App\Models\LicenseProvider;
use Illuminate\Http\Request;

class LicenseController extends Controller
{
    /**
     * Fetch license data for the given host.
     */
    public function getByHost($host)
    {
        $license = License::where('host', $host)->first();

        if (!$license) {
            return response()->json([
                'message' => 'License not found for the given host.',
            ], 404);
        }

        return response()->json($this->withProvider($license));
    }

    /**
     * Report live usage for a host's license (called by the licensed
     * application itself — not the admin panel). Updates and returns the
     * license record.
     */
    public function reportUsage(Request $request, $host)
    {
        $license = License::where('host', $host)->first();

        if (!$license) {
            return response()->json([
                'message' => 'License not found for the given host.',
            ], 404);
        }

        $data = $request->validate([
            'total_active_user' => ['required', 'integer', 'min:0'],
            'current_users' => ['required', 'integer', 'min:0'],
        ]);

        $license->update($data);

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
