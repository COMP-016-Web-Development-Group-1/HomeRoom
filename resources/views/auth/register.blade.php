<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
        @csrf

        <div class="mt-4">
            <x-input.label for="name">Name</x-input.label>
            <x-input.text id="name" type="text" name="name" :value="old('name')" />
            <x-input.error for="name" />
        </div>

        <div class="mt-4">
            <x-input.label for="email">Email</x-input.label>
            <x-input.text id="email" type="email" name="email" :value="old('email')" />
            <x-input.error for="email" />
        </div>

        <!-- Single file upload with help text -->
        <div class="mt-4">
            <x-input.file id="single_document" name="single_document" :help="'Upload PDF, Word, or Excel files (max 5MB)'" />
        </div>

        <!-- Image upload with help text -->
        <div class="mt-4">
            <x-input.file id="profile_image" name="profile_image" accept="image/*" :help="'Please upload a square image for best results'" />
        </div>

        <!-- Multiple file upload with help text -->
        <div class="mt-4">
            <x-input.file id="project_files" name="project_files[]" multiple :help="'You can select up to 5 files (10MB total)'" />
        </div>


        <div class="mt-4">
            <x-input.label for="password">Password</x-input.label>
            <x-input.text id="password" type="password" name="password" />
            <x-input.error for="password" />
        </div>

        <div class="mt-4">
            <x-input.label for="password_confirmation">Confirm Password</x-input.label>
            <x-input.text id="password_confirmation" type="password" name="password_confirmation" />
            <x-input.error for="password_confirmation" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                href="{{ route('login') }}">
                Already Registered?
            </a>

            <x-primary-button class="ms-4">
                Register
            </x-primary-button>
        </div>
    </form>

    @pushOnce('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const inputElement = document.getElementById('profile_img');

            });
        </script>
    @endPushOnce
</x-guest-layout>
