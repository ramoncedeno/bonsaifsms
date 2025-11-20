<div class="container mx-auto my-5 bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
    <!-- Search and Filter Controls -->
    <div class="mb-4 flex flex-col md:flex-row items-center justify-between space-y-2 md:space-y-0 md:space-x-4">
        <input type="text" wire:model.live="search" placeholder="Buscar..." class="w-full md:w-1/3 p-2 border border-gray-300 dark:border-gray-700 rounded text-gray-900 dark:bg-gray-700 dark:text-gray-100">
        <div class="flex items-center space-x-4">
            <input type="date" wire:model.live="filterDate" class="w-full md:w-auto p-2 border border-gray-300 dark:border-gray-700 rounded text-gray-900 dark:bg-gray-700 dark:text-gray-100">
            <select wire:model.live="filterOption" class="w-full md:w-auto p-2 border border-gray-300 dark:border-gray-700 rounded text-gray-900 dark:bg-gray-700 dark:text-gray-100">
                <option value="mine">{{ __('My Records') }}</option>
                <option value="all">{{ __('All Records') }}</option>
            </select>
        </div>
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
