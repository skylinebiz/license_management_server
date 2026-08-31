<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\License;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class LicenseController extends Controller
{
    /**
     * Columns the license list may be sorted by, via ?sort=&direction=.
     */
    private const SORTABLE_COLUMNS = ['host', 'license_status', 'license_expiry'];

    /**
     * List licenses, with search, sorting and pagination.
     */
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));

        $sort = $request->query('sort');
        $sort = in_array($sort, self::SORTABLE_COLUMNS, true) ? $sort : null;
        $direction = $request->query('direction') === 'desc' ? 'desc' : 'asc';

        $licenses = License::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('license_id', 'like', "%{$search}%")
                        ->orWhere('host', 'like', "%{$search}%")
                        ->orWhere('license_status', 'like', "%{$search}%");
                });
            })
            ->when(
                $sort,
                fn ($query) => $query->orderBy($sort, $direction)->orderByDesc('id'),
                fn ($query) => $query->orderByDesc('id'),
            )
            ->paginate(15)
            ->withQueryString();

        return view('admin.licenses.index', [
            'licenses' => $licenses,
            'search' => $search,
            'sort' => $sort,
            'direction' => $direction,
        ]);
    }

    public function create(): View
    {
        return view('admin.licenses.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateLicense($request);

        $license = License::create($data);

        return redirect()->route('admin.licenses.index')
            ->with('status', "License created: {$license->license_id}");
    }

    public function edit(License $license): View
    {
        return view('admin.licenses.edit', ['license' => $license]);
    }

    public function update(Request $request, License $license): RedirectResponse
    {
        $data = $this->validateLicense($request, $license);

        $license->update($data);

        return redirect()->route('admin.licenses.index')
            ->with('status', "License updated: {$license->license_id}");
    }

    public function destroy(License $license): RedirectResponse
    {
        $licenseId = $license->license_id;

        $license->delete();

        return redirect()->route('admin.licenses.index')
            ->with('status', "License {$licenseId} deleted.");
    }

    public function suspend(License $license): RedirectResponse
    {
        $license->update(['license_status' => 'suspended']);

        return back()->with('status', "License {$license->license_id} suspended.");
    }

    public function activate(License $license): RedirectResponse
    {
        $license->update(['license_status' => 'active']);

        return back()->with('status', "License {$license->license_id} activated.");
    }

    /**
     * @return array<string, mixed>
     */
    private function validateLicense(Request $request, ?License $license = null): array
    {
        return $request->validate([
            'license_expiry' => ['required', 'date'],
            'license_status' => ['required', Rule::in(['active', 'inactive', 'expired', 'suspended'])],
            'max_active_user' => ['required', 'integer', 'min:0'],
            'max_attachment_size_mb' => ['required', 'integer', 'min:0'],
            'host' => [
                'required', 'string', 'max:255',
                Rule::unique('licenses', 'host')->ignore($license?->id),
            ],
        ]);
    }
}
