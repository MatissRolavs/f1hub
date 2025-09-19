@push('styles')
    {{-- Google Font: Audiowide --}}
    <link href="https://fonts.googleapis.com/css2?family=Audiowide&display=swap" rel="stylesheet">
    <style>
        .font-audiowide {
            font-family: 'Audiowide', cursive;
        }
    </style>
@endpush

<div class="max-w-3xl mx-auto px-4 py-8 text-gray-900 dark:text-white audiowide-regular">

    {{-- Page Title --}}
    <h1 class="font-audiowide text-3xl mb-8 text-center">Profile Settings</h1>

    {{-- Profile Information --}}
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-6 mb-12">
        @csrf
        @method('patch')

        <div class="flex flex-col items-center gap-4">
            {{-- Profile Picture Placeholder --}}
            <div class="w-24 h-24 rounded-full bg-gray-300 dark:bg-gray-700 flex items-center justify-center text-gray-500 text-3xl">
                <i class="fas fa-user"></i>
            </div>

            {{-- Name --}}
            <div class="w-full">
                <label for="name" class="block text-sm font-medium mb-1">Name</label>
                <input id="name" name="name" type="text"
                    class="w-full rounded-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:ring-indigo-500 focus:border-indigo-500"
                    value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
                <x-input-error class="mt-1 text-red-500" :messages="$errors->get('name')" />
            </div>


            <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white px-6 py-2 rounded-full">
                Save Changes
            </button>
        </div>
    </form>

    {{-- Divider --}}
    <hr class="border-gray-300 dark:border-gray-700 my-8">

    {{-- Update Password --}}
    <form method="post" action="{{ route('password.update') }}" class="space-y-6 mb-12">
        @csrf
        @method('put')

        <h2 class="font-audiowide text-xl mb-4">Change Password</h2>

        <div>
            <label for="update_password_current_password" class="block text-sm font-medium mb-1">Current Password</label>
            <input id="update_password_current_password" name="current_password" type="password"
                class="w-full rounded-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:ring-indigo-500 focus:border-indigo-500"
                autocomplete="current-password">
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-1 text-red-500" />
        </div>

        <div>
            <label for="update_password_password" class="block text-sm font-medium mb-1">New Password</label>
            <input id="update_password_password" name="password" type="password"
                class="w-full rounded-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:ring-indigo-500 focus:border-indigo-500"
                autocomplete="new-password">
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-1 text-red-500" />
        </div>

        <div>
            <label for="update_password_password_confirmation" class="block text-sm font-medium mb-1">Confirm Password</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password"
                class="w-full rounded-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:ring-indigo-500 focus:border-indigo-500"
                autocomplete="new-password">
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-1 text-red-500" />
        </div>

        <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white px-6 py-2 rounded-full">
            Update Password
        </button>
    </form>

    {{-- Divider --}}
    <hr class="border-gray-300 dark:border-gray-700 my-8">

    {{-- Delete Account --}}
    <div>
        <h2 class="font-audiowide text-xl mb-4 text-red-600">Delete Account</h2>
        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
            Once your account is deleted, all of its resources and data will be permanently deleted.
        </p>

        <x-danger-button
            class="px-6 py-2 rounded-full"
            x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')">
            Delete Account
        </x-danger-button>

        <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
            <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
                @csrf
                @method('delete')

                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                    Are you sure you want to delete your account?
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                    Please enter your password to confirm.
                </p>

                <input id="password" name="password" type="password"
                    class="w-full rounded-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:border-red-500 focus:ring-red-500"
                    placeholder="Password">
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2 text-red-500" />

                <div class="mt-6 flex justify-end gap-3">
                    <x-secondary-button x-on:click="$dispatch('close')">
                        Cancel
                    </x-secondary-button>
                    <x-danger-button class="px-5 py-2 rounded-full">
                        Delete Account
                    </x-danger-button>
                </div>
            </form>
        </x-modal>
    </div>

</div>

