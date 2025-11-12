<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 mt-6">
    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Health Check Summary</h3>

    <div class="flex flex-wrap gap-4 justify-around">
        {{-- App Life Percentage Card --}}
        <div class="flex-1 min-w-[200px] p-4 bg-gray-100 dark:bg-gray-700 rounded-lg shadow-md text-center">
            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">App Life %</p>
            <p class="text-2xl font-bold {{ $appLifePercentage >= 80 ? 'text-green-600 dark:text-green-400' : ($appLifePercentage >= 50 ? 'text-yellow-600 dark:text-yellow-400' : 'text-red-600 dark:text-red-400') }}">
                {{ $appLifePercentage }}%
            </p>
        </div>

        {{-- Total Checks Card --}}
        <div class="flex-1 min-w-[200px] p-4 bg-gray-100 dark:bg-gray-700 rounded-lg shadow-md text-center">
            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Checks</p>
            <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $totalChecks }}</p>
        </div>

        {{-- Last Check Card --}}
        <div class="flex-1 min-w-[200px] p-4 bg-gray-100 dark:bg-gray-700 rounded-lg shadow-md text-center">
            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Last Check</p>
            <p class="text-md font-bold text-gray-800 dark:text-gray-200">{{ $lastCheckTime }}</p>
        </div>

        {{-- Individual Status Cards --}}
        @foreach ($statusCounts as $status => $count)
            @php
                $textColorClass = 'text-gray-800 dark:text-gray-200';
                if ($status === 'OK') {
                    $textColorClass = 'text-green-600 dark:text-green-400';
                } elseif ($status === 'ERROR') {
                    $textColorClass = 'text-red-600 dark:text-red-400';
                } elseif ($status === 'WARNING') {
                    $textColorClass = 'text-yellow-600 dark:text-yellow-400';
                }
            @endphp
            <div class="flex-1 min-w-[200px] p-4 bg-gray-100 dark:bg-gray-700 rounded-lg shadow-md text-center">
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">
                    @if ($status === 'OK')
                        Successful
                    @else
                        {{ ucfirst(strtolower($status)) }}
                    @endif
                </p>
                <p class="text-2xl font-bold {{ $textColorClass }}">{{ $count }}</p>
            </div>
        @endforeach

        @if (count($statusCounts) == 0 && $totalChecks == 0)
            <div class="flex-1 min-w-[200px] p-4 bg-gray-100 dark:bg-gray-700 rounded-lg shadow-md text-center">
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">No Data</p>
                <p class="text-2xl font-bold text-gray-500 dark:text-gray-400">N/A</p>
            </div>
        @endif
    </div>
</div>
