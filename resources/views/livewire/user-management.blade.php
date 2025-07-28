<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg min-h-[600px]">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight mb-4">
                    {{ __('User Management') }}
                </h2>

                @if (session()->has('message'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('message') }}</span>
                    </div>
                @endif

                <button wire:click="create()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded my-3">Create New User</button>

                @if($isOpen)
                    @include('livewire.create-user')
                @endif

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    {{ __('Name') }}
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    {{ __('Email') }}
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    {{ __('Email Verified') }}
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    {{ __('Roles') }}
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    {{ __('Status') }}
                                </th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    {{ __('Actions') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($users as $user)
                                <tr>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $user->name }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-500 dark:text-gray-300">{{ $user->email }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($user->email_verified_at)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-200 text-green-800 dark:bg-green-600 dark:text-white">
                                                Verified
                                            </span>
                                        @else
                                            <button wire:click="sendVerificationEmail({{ $user->id }})" wire:loading.attr="disabled" wire:target="sendVerificationEmail({{ $user->id }})" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-200 text-red-800 dark:bg-red-600 dark:text-white cursor-pointer">
                                                <span wire:loading.remove wire:target="sendVerificationEmail({{ $user->id }})">Send Verification</span>
                                                <span wire:loading wire:target="sendVerificationEmail({{ $user->id }})">Processing...</span>
                                            </button>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @foreach ($user->roles as $role)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100">
                                                {{ $role->name }}
                                            </span>
                                        @endforeach
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user->status === App\Models\User::STATUS_ACTIVE ? 'bg-green-200 text-green-800 dark:bg-green-600 dark:text-white' : 'bg-red-200 text-red-800 dark:bg-red-600 dark:text-white' }}">
                                            {{ ucfirst($user->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                        <x-dropdown align="right" width="48">
                                            <x-slot name="trigger">
                                                <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v.01M12 12v.01M12 18v.01"></path>
                                                    </svg>
                                                </button>
                                            </x-slot>

                                            <x-slot name="content">
                                                @if($user->status === App\Models\User::STATUS_SUSPENDED)
                                                    <x-dropdown-link wire:click="activateUser({{ $user->id }})" class="cursor-pointer">
                                                        {{ __('Activate') }}
                                                    </x-dropdown-link>
                                                @else
                                                    <x-dropdown-link wire:click="suspendUser({{ $user->id }})" class="cursor-pointer">
                                                        {{ __('Suspend') }}
                                                    </x-dropdown-link>
                                                @endif
                                                <x-dropdown-link wire:click="edit({{ $user->id }})" class="cursor-pointer">
                                                    {{ __('Edit') }}
                                                </x-dropdown-link>
                                                <x-dropdown-link wire:click="delete({{ $user->id }})" class="cursor-pointer">
                                                    {{ __('Delete') }}
                                                </x-dropdown-link>
                                                @if(!$user->hasVerifiedEmail())
                                                    <x-dropdown-link wire:click="sendVerificationEmail({{ $user->id }})" class="cursor-pointer">
                                                        {{ __('Send Verification') }}
                                                    </x-dropdown-link>
                                                @endif
                                                <x-dropdown-link wire:click="sendPasswordReset({{ $user->id }})" class="cursor-pointer">
                                                    {{ __('Send Reset Password') }}
                                                </x-dropdown-link>
                                            </x-slot>
                                        </x-dropdown>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
