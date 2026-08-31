<?php

namespace App\Http\Controllers;

use App\Models\License;
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

        return response()->json($license);
    }
}
