<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add License') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('admin.licenses.store') }}">
                    @csrf
                    @include('admin.licenses._form')

                    <div class="flex items-center gap-4 mt-6">
                        <x-primary-button>{{ __('Save License') }}</x-primary-button>
                        <a href="{{ route('admin.licenses.index') }}" class="text-sm text-gray-600 underline">
                            {{ __('Cancel') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
