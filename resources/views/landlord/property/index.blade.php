<x-app-layout title="Properties">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Properties
        </h2>
    </x-slot>

    <div class="max-w-(--breakpoint-2xl) mx-auto sm:px-6 lg:px-8">
        <input type="file" />
        <div class="mt-4 mb-4">
            <x-input.date maxDate="{{ iso_to_us(now()->toDateString()) }}" :withButtons="true" name="birth_date"
                :value="old('birth_date')" placeholder="Select Date" />
        </div>

        <div class="bg-white shadow-xs sm:rounded-lg">
            <div class="p-6 text-gray-900">
                Properties (Index)
            </div>
        </div>
    </div>

    @pushOnce('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", () => {}
            });
        </script>
    @endPushOnce
</x-app-layout>
