<x-guest-layout>
    <div class="min-h-screen flex flex-col md:flex-row bg-black text-white">

        {{-- Left side: Formula 1 image --}}
        <div class="hidden md:block md:w-1/2 relative">
            <img src="{{ asset('loginimage.jpg') }}" alt="login image"
                 class="object-cover w-full h-full brightness-90">
            <div class="absolute bottom-10 left-10 text-white">
                <h1 class="text-4xl font-extrabold tracking-tight">Join the Grid</h1>
                <p class="text-gray-300 mt-2 text-sm">Be part of the Formula 1 community and feel the speed.</p>
                <a href="{{ route('login') }}"
                   class="inline-block mt-4 px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-full">
                   Back to Login
                </a>
            </div>
        </div>

        {{-- Right side: Registration form --}}
        <div class="w-full md:w-1/2 flex flex-col justify-center px-8 md:px-16 py-10 bg-black">
            <div class="max-w-md w-full mx-auto space-y-8">
                {{-- Branding --}}
                <div class="flex items-center justify-between">
                    <h2 class="text-2xl font-bold tracking-wide uppercase">
                        F1Hub<span class="text-red-600">Register</span>
                    </h2>
                </div>

                {{-- Registration Form --}}
                <form method="POST" action="{{ route('register') }}" class="space-y-6">
                    @csrf

                    {{-- Name --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-200 mb-1">Name</label>
                        <input id="name" name="name" type="text" required autofocus
                               class="w-full bg-transparent border border-gray-700 focus:border-red-600 focus:ring-0 rounded-md px-3 py-2 text-white"
                               placeholder="Your Name" value="{{ old('name') }}">
                        <x-input-error :messages="$errors->get('name')" class="mt-2 text-red-400 text-sm" />
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-200 mb-1">Email</label>
                        <input id="email" name="email" type="email" required
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

                    {{-- Confirm Password --}}
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-200 mb-1">Confirm Password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required
                               class="w-full bg-transparent border border-gray-700 focus:border-red-600 focus:ring-0 rounded-md px-3 py-2 text-white"
                               placeholder="••••••••">
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-400 text-sm" />
                    </div>

                    {{-- Submit Button --}}
                    <div class="space-y-3">
                        <button type="submit"
                                class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-2 rounded-md transition">
                            {{ __('Register') }}
                        </button>

                        <a href="{{ route('login') }}"
                           class="w-full block text-center bg-white hover:bg-gray-200 text-black font-semibold py-2 rounded-md transition">
                            Already Registered? Login
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
