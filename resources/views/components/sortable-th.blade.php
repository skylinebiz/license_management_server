@props(['column', 'sort' => null, 'direction' => 'asc', 'search' => ''])

@php
    $isActive = $sort === $column;
    $nextDirection = $isActive && $direction === 'asc' ? 'desc' : 'asc';
    $url = route('admin.licenses.index', array_filter([
        'q' => $search !== '' ? $search : null,
        'sort' => $column,
        'direction' => $nextDirection,
    ]));
@endphp

<th class="px-4 py-2">
    <a href="{{ $url }}" class="inline-flex items-center gap-1 hover:text-gray-700">
        <span>{{ $slot }}</span>
        <span class="text-gray-400 {{ $isActive ? '' : 'invisible' }}">
            {{ $direction === 'asc' ? '▲' : '▼' }}
        </span>
    </a>
</th>
