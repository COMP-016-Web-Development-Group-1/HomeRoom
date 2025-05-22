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


        <div class="mt-4">
            <x-input.label for="profile">Profile Picture</x-input.label>
            <x-input.file id="profile_picture" name="profile_picture" accept="image/png,image/jpeg" :circle="true"
                help="PNG or JPG (Max 2MB)" />
            <x-input.error for="profile_picture" />
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

        <div class="mt-4">
            <x-input.label for="code">Room Code</x-input.label>
            <x-input.text id="code" type="text" name="code" :value="old('code')" />
            <x-input.error for="code" />
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
