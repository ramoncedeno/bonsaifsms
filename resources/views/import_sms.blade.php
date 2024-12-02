<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Mass SMS sending') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-8 space-y-6">
                <h3 class="text-lg font-medium text-gray-800 dark:text-gray-200">
                    {{ __('Upload Your File') }}
                </h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    {{ __('Check that your file contains the correct data before importing.') }}
                </p>

                <form action="{{ route('sms.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    <!-- File Input -->
                    <div>
                        <label for="file" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{ __('Choose File') }}
                        </label>
                        <input
                            type="file"
                            name="file"
                            id="file"
                            class="mt-1 block w-full text-sm text-gray-900 dark:text-gray-300 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-green-500 focus:border-green-500"
                            required
                        >
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-center items-center">
                        <button
                            type="submit"
                            class="w-full sm:w-auto px-6 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                        >
                            {{ __('Import File') }}
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>
