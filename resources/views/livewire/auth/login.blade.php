<div class="flex flex-col gap-6" style="color: black !important;">
    <x-auth-header class="text-black" :title="__('Log in to your account')" :description="__('Enter your email and password below to log in')" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center text-black" :status="session('status')" />

    <form wire:submit="login" class="flex flex-col gap-6 text-black">
    <!-- Email Address -->
    <div class="flex flex-col">
        <label for="email" class="mb-1 text-black font-medium">Email address</label>
        <input
            id="email"
            wire:model="email"
            type="email"
            required
            placeholder="email@example.com"
            class="w-full border border-blue-900 bg-white text-black placeholder:text-zinc-700 rounded-md px-4 py-2" />
    </div>

    <!-- Password -->
    <div class="flex flex-col">
        <label for="password" class="mb-1 text-black font-medium">Password</label>
        <input
            id="password"
            wire:model="password"
            type="password"
            required
            autocomplete="current-password"
            placeholder="Password"
            class="w-full border border-blue-900 bg-white text-black placeholder:text-zinc-700 rounded-md px-4 py-2" />

        @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}"
                class="mt-2 text-sm text-blue-900 hover:underline self-end">
                {{ __('Forgot your password?') }}
            </a>
        @endif
    </div>

    <!-- Remember Me -->
    <div class="flex items-center gap-2">
        <input
            id="remember"
            type="checkbox"
            wire:model="remember"
            class="text-blue-900 border-blue-900 rounded focus:ring-blue-900" />
        <label for="remember" class="text-sm text-black">Remember me</label>
    </div>

    <!-- Submit Button -->
    <div class="flex items-center justify-end">
        <button
            type="submit"
            class="w-full text-white font-semibold bg-[#FE7F63] border border-[#FE7F63] hover:bg-[#e9745a] hover:border-[#e9745a] px-4 py-2 rounded-md transition-colors duration-150">
            {{ __('Log in') }}
        </button>
    </div>
</form>


    @if (Route::has('register'))
    <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-black">
        <span>{{ __('Don\'t have an account?') }}</span>
        <flux:link :href="route('register')" wire:navigate class="text-black hover:underline">{{ __('Sign up') }}</flux:link>
    </div>
    @endif
</div>