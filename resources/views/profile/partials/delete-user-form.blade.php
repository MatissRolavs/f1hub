{{-- Delete Account --}}
<section class="bg-white dark:bg-gray-900 shadow rounded-lg p-6">
    <header class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-4">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white flex items-center gap-2">
            <i class="fas fa-user-slash text-red-500"></i> {{ __('Delete Account') }}
        </h2>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    <x-danger-button
        class="px-5 py-2 rounded-lg"
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')">
        {{ __('Delete Account') }}
    </x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 bg-[#1a1a28]">
            @csrf
            @method('delete')

            <h2 class="text-lg font-semibold text-white flex items-center gap-2">
                <i class="fas fa-triangle-exclamation text-red-500"></i>
                {{ __('Are you sure you want to delete your account?') }}
            </h2>

            <p class="mt-2 text-sm text-gray-400">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
            </p>

            <div class="mt-6">
                <label for="password" class="block text-sm font-medium text-gray-300 mb-1">{{ __('Password') }}</label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    placeholder="{{ __('Enter your password') }}"
                    class="block w-full rounded-lg border border-white/10 bg-[#0f0f15] text-white placeholder-gray-500 px-4 py-2.5 focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500"
                />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2 text-red-400 text-sm" />
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button
                    type="button"
                    x-on:click="$dispatch('close')"
                    class="px-4 py-2 rounded-lg border border-white/10 text-sm text-gray-300 hover:bg-white/5 transition"
                >
                    {{ __('Cancel') }}
                </button>

                <button
                    type="submit"
                    class="px-5 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white text-sm font-semibold transition shadow-lg shadow-red-900/30"
                >
                    {{ __('Delete Account') }}
                </button>
            </div>
        </form>
    </x-modal>
</section>
