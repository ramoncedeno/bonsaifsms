<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 mt-6">
    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Your SMS Consumption</h3>

    <div class="flex flex-wrap gap-4 justify-around">
        <div class="flex-1 min-w-[200px] p-4 bg-gray-100 dark:bg-gray-700 rounded-lg shadow-md text-center">
            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">SMS Available</p>
            <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $currentSmsLimit }}</p>
        </div>
        <div class="flex-1 min-w-[200px] p-4 bg-gray-100 dark:bg-gray-700 rounded-lg shadow-md text-center">
            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">SMS Sent</p>
            <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $smsSent }}</p>
        </div>
        <div class="flex-1 min-w-[200px] p-4 bg-gray-100 dark:bg-gray-700 rounded-lg shadow-md text-center">
            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">SMS Remaining</p>
            <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $smsRemaining }}</p>
        </div>
    </div>
</div>
