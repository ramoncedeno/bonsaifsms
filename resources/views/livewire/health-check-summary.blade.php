<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 mt-6" wire:init="loadHealthChecks">
    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Health Check Summary</h3>

    @if ($ready)
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

        {{-- Detailed Check Results --}}
        @if (!empty($detailedChecks))
            <div class="mt-8">
                <h4 class="text-md font-medium text-gray-900 dark:text-gray-100 mb-4">Latest Check Details</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach ($detailedChecks as $service => $details)
                        @php
                            $statusColor = 'bg-gray-500'; // Default for SKIPPED or other statuses
                            if ($details['status'] === 'OK') {
                                $statusColor = 'bg-green-500';
                            } elseif ($details['status'] === 'ERROR') {
                                $statusColor = 'bg-red-500';
                            } elseif ($details['status'] === 'WARNING') {
                                $statusColor = 'bg-yellow-500';
                            }
                        @endphp
                        <div class="bg-gray-100 dark:bg-gray-700 rounded-lg shadow-md p-4 flex flex-col justify-between">
                            <div class="flex justify-between items-start">
                                <h5 class="font-semibold text-gray-800 dark:text-gray-200 capitalize">{{ str_replace('_', ' ', $service) }}</h5>
                                <span class="px-2 py-1 text-xs font-bold text-white rounded-full {{ $statusColor }}">
                                    {{ $details['status'] }}
                                </span>
                            </div>
                            <div class="mt-2 text-right">
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $details['duration_ms'] ?? 'N/A' }} ms</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    @else
        <div class="flex justify-center items-center p-4">
            <p class="text-gray-500 dark:text-gray-400">Loading health check data...</p>
        </div>
    @endif
</div>
