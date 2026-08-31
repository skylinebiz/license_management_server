<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Licenses') }}
            </h2>
            <a href="{{ route('admin.licenses.create') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                {{ __('Add License') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('status'))
                <div class="mb-4 px-4 py-3 rounded-md bg-green-100 text-green-800 text-sm">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="GET" action="{{ route('admin.licenses.index') }}" class="mb-6 flex gap-2">
                    <input type="text" name="q" value="{{ $search }}"
                        placeholder="Search by license ID, host or status"
                        class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" />
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 transition">
                        {{ __('Search') }}
                    </button>
                    @if ($search !== '')
                        <a href="{{ route('admin.licenses.index') }}"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 transition">
                            {{ __('Clear') }}
                        </a>
                    @endif
                </form>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead>
                            <tr class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <th class="px-4 py-2">License ID</th>
                                <x-sortable-th column="host" :sort="$sort" :direction="$direction" :search="$search">Host</x-sortable-th>
                                <x-sortable-th column="license_expiry" :sort="$sort" :direction="$direction" :search="$search">Expiry</x-sortable-th>
                                <x-sortable-th column="license_status" :sort="$sort" :direction="$direction" :search="$search">Status</x-sortable-th>
                                <th class="px-4 py-2">Max Users</th>
                                <th class="px-4 py-2">Max Attachment (MB)</th>
                                <th class="px-4 py-2 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($licenses as $license)
                                <tr>
                                    <td class="px-4 py-2 font-medium text-gray-900">{{ $license->license_id }}</td>
                                    <td class="px-4 py-2">{{ $license->host }}</td>
                                    <td class="px-4 py-2">{{ $license->license_expiry->format('Y-m-d') }}</td>
                                    <td class="px-4 py-2">
                                        @php
                                            $statusColors = [
                                                'active' => 'bg-green-100 text-green-800',
                                                'inactive' => 'bg-gray-100 text-gray-800',
                                                'expired' => 'bg-red-100 text-red-800',
                                                'suspended' => 'bg-yellow-100 text-yellow-800',
                                            ];
                                        @endphp
                                        <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $statusColors[$license->license_status] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ ucfirst($license->license_status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2">{{ $license->max_active_user }}</td>
                                    <td class="px-4 py-2">{{ $license->max_attachment_size_mb }}</td>
                                    <td class="px-4 py-2">
                                        <div class="flex items-center justify-end gap-3">
                                            <a href="{{ route('admin.licenses.edit', $license) }}"
                                                class="text-indigo-600 hover:text-indigo-800" title="Edit">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                                                    <path d="m5.433 13.917 1.262-3.155A4 4 0 0 1 7.58 9.42l6.92-6.918a2.121 2.121 0 0 1 3 3l-6.92 6.918c-.383.383-.84.685-1.343.886l-3.154 1.262a.5.5 0 0 1-.65-.65Z" />
                                                    <path d="M3.5 5.75c0-.69.56-1.25 1.25-1.25H10A.75.75 0 0 0 10 3H4.75A2.75 2.75 0 0 0 2 5.75v9.5A2.75 2.75 0 0 0 4.75 18h9.5A2.75 2.75 0 0 0 17 15.25V10a.75.75 0 0 0-1.5 0v5.25c0 .69-.56 1.25-1.25 1.25h-9.5c-.69 0-1.25-.56-1.25-1.25v-9.5Z" />
                                                </svg>
                                                <span class="sr-only">{{ __('Edit') }}</span>
                                            </a>

                                            <form method="POST" action="{{ route('admin.licenses.destroy', $license) }}"
                                                onsubmit="return confirm('Delete license {{ $license->license_id }}? This cannot be undone.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800" title="Delete">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                                                        <path fill-rule="evenodd" d="M8.75 1A2.75 2.75 0 0 0 6 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 1 0 .23 1.482l.149-.022.841 10.518A2.75 2.75 0 0 0 7.596 19h4.807a2.75 2.75 0 0 0 2.742-2.53l.841-10.52.149.023a.75.75 0 0 0 .23-1.482A41.03 41.03 0 0 0 14 4.193V3.75A2.75 2.75 0 0 0 11.25 1h-2.5ZM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4ZM8.58 7.72a.75.75 0 0 0-1.5.06l.3 7.5a.75.75 0 1 0 1.5-.06l-.3-7.5Zm4.34.06a.75.75 0 1 0-1.5-.06l-.3 7.5a.75.75 0 1 0 1.5.06l.3-7.5Z" clip-rule="evenodd" />
                                                    </svg>
                                                    <span class="sr-only">{{ __('Delete') }}</span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-6 text-center text-gray-500">
                                        {{ __('No licenses found.') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $licenses->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
