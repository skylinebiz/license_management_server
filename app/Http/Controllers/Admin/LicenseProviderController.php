<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LicenseProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * A single, global "who issues these licenses" record — not per-license.
 * Returned alongside every license in the API.
 */
class LicenseProviderController extends Controller
{
    public function edit(): View
    {
        return view('admin.provider.edit', [
            'provider' => LicenseProvider::current(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
        ]);

        LicenseProvider::current()->update($data);

        return redirect()->route('admin.provider.edit')
            ->with('status', 'Provider details updated.');
    }
}
