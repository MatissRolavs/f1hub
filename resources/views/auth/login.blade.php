<x-guest-layout>
    <div class="min-h-screen flex flex-col md:flex-row bg-black text-white">
        {{-- Left side: Formula 1 image --}}
        <div class="hidden md:block md:w-1/2 relative">
            <img src="{{ asset('loginimage.jpg') }}" alt="login image"
                 class="object-cover w-full h-full brightness-90">
            <div class="absolute bottom-10 left-10 text-white">
                <h1 class="text-4xl font-extrabold tracking-tight">Chase the Finish Line</h1>
                <p class="text-gray-300 mt-2 text-sm">Experience the speed, precision, and passion of Formula 1.</p>
                <a href="{{ url('/dashboard') }}" 
                   class="inline-block mt-4 px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-full">
                   Explore
                </a>
            </div>
        </div>

        {{-- Right side: Login Form --}}
        <div class="w-full md:w-1/2 flex flex-col justify-center px-8 md:px-16 py-10 bg-black">
            <div class="max-w-md w-full mx-auto space-y-8">
                {{-- Branding --}}
                <div class="flex items-center justify-between">
                    <h2 class="text-2xl font-bold tracking-wide uppercase">F1Hub<span class="text-red-600">Login</span></h2>
                </div>

                {{-- Session Status --}}
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-200 mb-1">Email</label>
                        <input id="email" name="email" type="email" required autofocus
                               class="w-full bg-transparent border border-gray-700 focus:border-red-600 focus:ring-0 rounded-md px-3 py-2 text-white"
                               placeholder="you@example.com" value="{{ old('email') }}">
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-400 text-sm" />
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-200 mb-1">Password</label>
                        <input id="password" name="password" type="password" required
                               class="w-full bg-transparent border border-gray-700 focus:border-red-600 focus:ring-0 rounded-md px-3 py-2 text-white"
                               placeholder="••••••••">
                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-400 text-sm" />
                    </div>

                    {{-- Remember me --}}
                    <div class="flex items-center justify-between">
                        <label for="remember_me" class="inline-flex items-center text-sm text-gray-400">
                            <input id="remember_me" type="checkbox"
                                   class="rounded border-gray-700 bg-gray-900 text-red-600 focus:ring-red-600">
                            <span class="ml-2">{{ __('Remember me') }}</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a class="text-sm text-gray-400 hover:text-red-500 transition"
                               href="{{ route('password.request') }}">
                                {{ __('Forgot Password?') }}
                            </a>
                        @endif
                    </div>

                    {{-- Buttons --}}
                    <div class="space-y-3">
                        <button type="submit"
                                class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-2 rounded-md transition">
                            {{ __('Log in') }}
                        </button>

                        <a href="{{ url('/dashboard') }}"
                           class="w-full block text-center bg-white hover:bg-gray-200 text-black font-semibold py-2 rounded-md transition">
                            Continue as Guest
                        </a>
                    </div>

                    <p class="text-center text-gray-500 text-sm mt-6">
                        Don’t have an account?
                        <a href="{{ route('register') }}" class="text-red-600 hover:text-red-500 font-semibold">
                            Register now
                        </a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
