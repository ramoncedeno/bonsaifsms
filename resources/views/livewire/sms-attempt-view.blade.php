<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight mb-4">
                    {{ __('SMS Attempt Records') }}
                </h2>

                {{-- Livewire message display --}}
                @if (session()->has('message'))
                    <div class="alert alert-info bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('message') }}</span>
                    </div>
                @endif

                <div class="container mx-auto p-4">


                    {{-- Livewire Import Form --}}
                    <form wire:submit.prevent="importSms" enctype="multipart/form-data" class="mb-4 bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                        <div class="flex flex-col md:flex-row items-center md:justify-between">
                            <div class="flex items-center space-x-4">
                                <label for="file-upload" class="cursor-pointer px-4 py-2 rounded text-sm font-semibold bg-blue-500 text-white hover:bg-blue-700 dark:bg-blue-600 dark:hover:bg-blue-500">
                                    Choose File
                                </label>
                                <input id="file-upload" type="file" wire:model="file" required class="hidden">
                                <div wire:loading wire:target="file" class="text-sm text-gray-500">Uploading...</div>
                                @if ($file)
                                    <span class="text-sm text-gray-500">{{ $file->getClientOriginalName() }}</span>
                                @endif
                            </div>

                            <button type="submit" wire:loading.attr="disabled" wire:target="importSms" class="mt-4 md:mt-0 w-full md:w-auto px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700 dark:bg-blue-600 dark:hover:bg-blue-500">
                                <span wire:loading.remove wire:target="importSms">Import</span>
                                <span wire:loading wire:target="importSms">Processing...</span>
                            </button>
                        </div>
                        @error('file') <span class="text-red-500 text-xs mt-2">{{ $message }}</span> @enderror
                    </form>
                </div>

                <div class="container mx-auto my-5 bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                    <!-- Search and Filter Controls -->
                    <div class="mb-4 flex flex-col md:flex-row items-center justify-between space-y-2 md:space-y-0 md:space-x-4">
                        <input type="text" wire:model.live="search" placeholder="Buscar..." class="w-full md:w-1/3 p-2 border border-gray-300 dark:border-gray-700 rounded text-gray-900 dark:bg-gray-700 dark:text-gray-100">
                        <select wire:model.live="filterOption" class="w-full md:w-auto p-2 border border-gray-300 dark:border-gray-700 rounded text-gray-900 dark:bg-gray-700 dark:text-gray-100">
                            <option value="mine">{{ __('My Records') }}</option>
                            <option value="all">{{ __('All Records') }}</option>
                        </select>
                    </div>

                    <!-- Top Pagination Bar -->
                    <div class="flex justify-between items-center mt-3">
                        {{ $sendAttempts->links() }}
                    </div>

                    <!-- Records Table -->
                    <div class="overflow-x-auto mt-4">
                        <table class="min-w-full table-auto bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                            <thead class="bg-gray-100 dark:bg-gray-700">
                                <tr>
                                    <th class="py-2 px-4 border-b border-gray-200 dark:border-gray-700 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider">{{ __('Subject') }}</th>
                                    <th class="py-2 px-4 border-b border-gray-200 dark:border-gray-700 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider">{{ __('Sponsor') }}</th>
                                    <th class="py-2 px-4 border-b border-gray-200 dark:border-gray-700 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider">{{ __('ID') }}</th>
                                    <th class="py-2 px-4 border-b border-gray-200 dark:border-gray-700 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider">{{ __('Phone') }}</th>
                                    <th class="py-2 px-4 border-b border-gray-200 dark:border-gray-700 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider">{{ __('Message') }}</th>
                                    <th class="py-2 px-4 border-b border-gray-200 dark:border-gray-700 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider">{{ __('Status') }}</th>
                                    <th class="py-2 px-4 border-b border-gray-200 dark:border-gray-700 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider">{{ __('Response ID') }}</th>
                                    <th class="py-2 px-4 border-b border-gray-200 dark:border-gray-700 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider">{{ __('Created') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($sendAttempts as $sendAttempt)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700 text-gray-800 dark:text-gray-200 text-xs">{{ $sendAttempt->subject }}</td>
                                        <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700 text-gray-800 dark:text-gray-200 text-xs">{{ $sendAttempt->sponsor }}</td>
                                        <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700 text-gray-800 dark:text-gray-200 text-xs">{{ $sendAttempt->identification_id }}</td>
                                        <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700 text-gray-800 dark:text-gray-200 text-xs">{{ $sendAttempt->phone }}</td>
                                        <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700 text-gray-800 dark:text-gray-200 text-xs whitespace-normal">{{ $sendAttempt->message }}</td>
                                        <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700 text-xs">
                                            <span class="px-2 py-1 rounded {{ $sendAttempt->status == 'success' ? 'bg-green-500 text-white' : 'bg-blue-500 text-white' }}">
                                                {{ ucfirst($sendAttempt->status) }}
                                            </span>
                                        </td>
                                        <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700 text-gray-800 dark:text-gray-200 text-xs">{{ $sendAttempt->response_id }}</td>
                                        <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700 text-gray-800 dark:text-gray-200 text-xs">{{ $sendAttempt->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="py-4 px-4 text-sm text-gray-500 dark:text-gray-400 text-center">
                                            {{ __('No records found.') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Bottom Pagination Bar -->
                    <div class="flex justify-between items-center mt-3">
                        {{ $sendAttempts->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
