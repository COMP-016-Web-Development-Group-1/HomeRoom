<x-guest-layout title="Login">
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <x-input.label for="email">Email</x-input.label>
            <x-input.text id="email" type="email" name="email" :value="old('email')" autofocus />
            <x-input.error for="email" />
        </div>

        <div class="mt-4">
            <x-input.label for="password">Password</x-input.label>
            <x-input.text id="password" type="password" name="password" />
            <x-input.error for="password" />
        </div>


        <div class="mt-4">
            <x-input.label for="remember_me">
                <x-input.checkbox id="remember_me" name="remember" />
                <span class="ms-1 text-sm text-gray-600">Remember Me</span>
            </x-input.label>
        </div>

        <div class="flex items-center justify-end mt-4 gap-x-3">
            @if (Route::has('password.request'))
                <x-a href="{{ route('password.request') }}">Forgot your password?</x-a>
            @endif

            <x-button>Log In</x-button>
        </div>
    </form>
</x-guest-layout>
