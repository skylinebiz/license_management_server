@php
    $license = $license ?? null;
@endphp

@if ($license)
    <div>
        <x-input-label for="license_id" value="License ID" />
        <x-text-input id="license_id" type="text" class="mt-1 block w-full bg-gray-50 text-gray-500"
            value="{{ $license->license_id }}" :disabled="true" readonly />
        <p class="mt-1 text-xs text-gray-500">Auto-generated — cannot be changed.</p>
    </div>
@else
    <div class="mb-4 px-4 py-3 rounded-md bg-gray-50 text-gray-600 text-sm">
        The license ID is generated automatically once you save this license.
    </div>
@endif

<div class="mt-4">
    <x-input-label for="host" value="Host" />
    <x-text-input id="host" name="host" type="text" class="mt-1 block w-full"
        value="{{ old('host', $license?->host) }}" placeholder="example.com" required autofocus />
    <x-input-error :messages="$errors->get('host')" class="mt-2" />
</div>

<div class="mt-4">
    <x-input-label for="license_expiry" value="License Expiry" />
    <x-text-input id="license_expiry" name="license_expiry" type="date" class="mt-1 block w-full"
        value="{{ old('license_expiry', optional($license?->license_expiry)->format('Y-m-d')) }}" required />
    <x-input-error :messages="$errors->get('license_expiry')" class="mt-2" />
</div>

<div class="mt-4">
    <x-input-label for="license_status" value="License Status" />
    <select id="license_status" name="license_status"
        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
        @foreach (['active', 'inactive', 'expired', 'suspended'] as $status)
            <option value="{{ $status }}" @selected(old('license_status', $license?->license_status) === $status)>
                {{ ucfirst($status) }}
            </option>
        @endforeach
    </select>
    <x-input-error :messages="$errors->get('license_status')" class="mt-2" />
</div>

<div class="mt-4">
    <x-input-label for="max_active_user" value="Max Active User" />
    <x-text-input id="max_active_user" name="max_active_user" type="number" min="0" class="mt-1 block w-full"
        value="{{ old('max_active_user', $license?->max_active_user) }}" required />
    <x-input-error :messages="$errors->get('max_active_user')" class="mt-2" />
</div>

<div class="mt-4">
    <x-input-label for="simultaneous_sessions" value="Simultaneous Sessions" />
    <x-text-input id="simultaneous_sessions" name="simultaneous_sessions" type="number" min="0" class="mt-1 block w-full"
        value="{{ old('simultaneous_sessions', $license?->simultaneous_sessions ?? 1) }}" required />
    <x-input-error :messages="$errors->get('simultaneous_sessions')" class="mt-2" />
</div>

<div class="mt-4">
    <x-input-label for="max_attachment_size_mb" value="Max Attachment Size (MB)" />
    <x-text-input id="max_attachment_size_mb" name="max_attachment_size_mb" type="number" min="0" class="mt-1 block w-full"
        value="{{ old('max_attachment_size_mb', $license?->max_attachment_size_mb) }}" required />
    <x-input-error :messages="$errors->get('max_attachment_size_mb')" class="mt-2" />
</div>

@if ($license)
    <div class="mt-6 px-4 py-3 rounded-md bg-gray-50 text-sm text-gray-600">
        <p class="font-medium text-gray-700 mb-1">Usage (reported by the licensed app via the API)</p>
        <p>Current users: {{ $license->current_users }} &middot; Total active users: {{ $license->total_active_user }}</p>
    </div>
@endif
