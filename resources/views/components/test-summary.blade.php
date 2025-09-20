<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 mt-6">
    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Test Summary</h3>
    <div class="flex flex-wrap gap-4 justify-around">
        <div class="flex-1 min-w-[200px] p-4 bg-gray-100 dark:bg-gray-700 rounded-lg shadow-md text-center">
            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Success Rate</p>
            <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $successPercentage }}%</p>
        </div>
        <div class="flex-1 min-w-[200px] p-4 bg-gray-100 dark:bg-gray-700 rounded-lg shadow-md text-center">
            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Successful Tests</p>
            <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $successCount }}</p>
        </div>
        <div class="flex-1 min-w-[200px] p-4 bg-gray-100 dark:bg-gray-700 rounded-lg shadow-md text-center">
            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Failed Tests</p>
            <p class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $failureCount }}</p>
        </div>
    </div>
</div>