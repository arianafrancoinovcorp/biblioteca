<div class="flex flex-col gap-6">
    <x-auth-header :title="__('Create an account')" :description="__('Enter your details below to create your account')" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form wire:submit="register" class="flex flex-col gap-6">
        <!-- Name -->
        <div class="flex flex-col">
            <label for="name" class="mb-1 text-black font-medium">Name</label>
            <input
                id="name"
                wire:model="name"
                type="text"
                required
                autofocus
                autocomplete="name"
                placeholder="Full name"
                class="w-full border border-blue-900 bg-white text-black placeholder:text-zinc-700 rounded-md px-4 py-2" />
        </div>

        <!-- Email Address -->
        <div class="flex flex-col">
            <label for="email" class="mb-1 text-black font-medium">Email address</label>
            <input
                id="email"
                wire:model="email"
                type="email"
                required
                autocomplete="email"
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
                autocomplete="new-password"
                placeholder="Password"
                class="w-full border border-blue-900 bg-white text-black placeholder:text-zinc-700 rounded-md px-4 py-2" />
        </div>

        <!-- Confirm Password -->
        <div class="flex flex-col">
            <label for="password_confirmation" class="mb-1 text-black font-medium">Confirm password</label>
            <input
                id="password_confirmation"
                wire:model="password_confirmation"
                type="password"
                required
                autocomplete="new-password"
                placeholder="Confirm password"
                class="w-full border border-blue-900 bg-white text-black placeholder:text-zinc-700 rounded-md px-4 py-2" />
        </div>

        <div class="flex items-center justify-end">
            <button
                type="submit"
                class="w-full text-white font-semibold bg-[#FE7F63] border border-[#FE7F63] hover:bg-[#e9745a] hover:border-[#e9745a] px-4 py-2 rounded-md transition-colors duration-150">
                {{ __('Create account') }}
            </button>
        </div>
    </form>

    <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-600">
        <span>{{ __('Already have an account?') }}</span>
        <flux:link :href="route('login')" wire:navigate>{{ __('Log in') }}</flux:link>
    </div>
</div>