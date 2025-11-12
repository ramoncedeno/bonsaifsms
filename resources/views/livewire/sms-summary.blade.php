<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 mt-6">
    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">SMS Summary</h3>

    <div class="flex flex-wrap gap-4 justify-around">
        @foreach($summaryData as $data)
            @php
                $textColorClass = 'text-gray-800 dark:text-gray-200';
                if ($data['status'] === 'Successful') {
                    $textColorClass = 'text-green-600 dark:text-green-400';
                } elseif ($data['status'] === 'Failed') {
                    $textColorClass = 'text-red-600 dark:text-red-400';
                }
            @endphp
            <div class="flex-1 min-w-[200px] p-4 bg-gray-100 dark:bg-gray-700 rounded-lg shadow-md text-center">
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __($data['status']) }}</p>
                <p class="text-2xl font-bold {{ $textColorClass }}">{{ $data['count'] }}</p>
            </div>
        @endforeach

        @if (count($summaryData) == 0)
            <div class="flex-1 min-w-[200px] p-4 bg-gray-100 dark:bg-gray-700 rounded-lg shadow-md text-center">
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">No Data</p>
                <p class="text-2xl font-bold text-gray-500 dark:text-gray-400">N/A</p>
            </div>
        @endif
    </div>
</div>
