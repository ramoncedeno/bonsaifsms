<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('View SMS sending') }}
        </h2>
    </x-slot>

    <div class="mt-6 flex flex-col items-center">

        <!-- Pagination Controls -->
        <nav class="inline-flex space-x-1" aria-label="Pagination">
            <!-- Previous button -->
            @if ($sendAttempts->onFirstPage())
                <span class="px-3 py-1 bg-gray-300 dark:bg-gray-600 text-gray-500 dark:text-gray-400 rounded-lg cursor-not-allowed">
                    &laquo; Previous
                </span>
            @else
                <a href="{{ $sendAttempts->previousPageUrl() }}" class="px-3 py-1 bg-blue-600 text-white hover:bg-blue-700 rounded-lg">
                    &laquo; Previous
                </a>
            @endif

            <!-- Page numbers -->
            @foreach ($sendAttempts->getUrlRange(1, $sendAttempts->lastPage()) as $page => $url)
                @if ($page == $sendAttempts->currentPage())
                    <span class="px-3 py-1 bg-blue-600 text-white rounded-lg">
                        {{ $page }}
                    </span>
                @else
                    <a href="{{ $url }}" class="px-3 py-1 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-gray-300 hover:bg-gray-400 dark:hover:bg-gray-500 rounded-lg">
                        {{ $page }}
                    </a>
                @endif
            @endforeach

            <!-- Next button -->
            @if ($sendAttempts->hasMorePages())
                <a href="{{ $sendAttempts->nextPageUrl() }}" class="px-3 py-1 bg-blue-600 text-white hover:bg-blue-700 rounded-lg">
                    Next &raquo;
                </a>
            @else
                <span class="px-3 py-1 bg-gray-300 dark:bg-gray-600 text-gray-500 dark:text-gray-400 rounded-lg cursor-not-allowed">
                    Next &raquo;
                </span>
            @endif
        </nav>
    </div>



    <div class="py-12">
        <div class="max-w-full  mx-auto sm:px-8 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">

                    <!-- View submitted records -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 table-auto">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wide">{{ __('Subject') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wide">{{ __('Sponsor') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wide">{{ __('ID') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wide">{{ __('Phone') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wide">{{ __('Message') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wide">{{ __('Status') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wide">{{ __('Response ID') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wide">{{ __('Created') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($sendAttempts as $sendAttempt)
                                    <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-300">{{ $sendAttempt->subject }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-300">{{ $sendAttempt->sponsor }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-300">{{ $sendAttempt->identification_id }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-300">{{ $sendAttempt->phone }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-300">{{ $sendAttempt->message }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-300">{{ $sendAttempt->status }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-300">{{ $sendAttempt->response_id }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-300">{{ $sendAttempt->created_at }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="mt-6 flex flex-col items-center">

        <!-- Pagination Controls -->
        <nav class="inline-flex space-x-1" aria-label="Pagination">
            <!-- Previous button -->
            @if ($sendAttempts->onFirstPage())
                <span class="px-3 py-1 bg-gray-300 dark:bg-gray-600 text-gray-500 dark:text-gray-400 rounded-lg cursor-not-allowed">
                    &laquo; Previous
                </span>
            @else
                <a href="{{ $sendAttempts->previousPageUrl() }}" class="px-3 py-1 bg-blue-600 text-white hover:bg-blue-700 rounded-lg">
                    &laquo; Previous
                </a>
            @endif

            <!-- Page numbers -->
            @foreach ($sendAttempts->getUrlRange(1, $sendAttempts->lastPage()) as $page => $url)
                @if ($page == $sendAttempts->currentPage())
                    <span class="px-3 py-1 bg-blue-600 text-white rounded-lg">
                        {{ $page }}
                    </span>
                @else
                    <a href="{{ $url }}" class="px-3 py-1 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-gray-300 hover:bg-gray-400 dark:hover:bg-gray-500 rounded-lg">
                        {{ $page }}
                    </a>
                @endif
            @endforeach

            <!-- Next button -->
            @if ($sendAttempts->hasMorePages())
                <a href="{{ $sendAttempts->nextPageUrl() }}" class="px-3 py-1 bg-blue-600 text-white hover:bg-blue-700 rounded-lg">
                    Next &raquo;
                </a>
            @else
                <span class="px-3 py-1 bg-gray-300 dark:bg-gray-600 text-gray-500 dark:text-gray-400 rounded-lg cursor-not-allowed">
                    Next &raquo;
                </span>
            @endif
        </nav>
    </div>

</x-app-layout>
