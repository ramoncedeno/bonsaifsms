<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight mb-4">
                    {{ __('Bulk mailing') }}
                </h2>

                {{-- Livewire message display --}}
                @if (session()->has('message'))
                    <div class="alert alert-info bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('message') }}</span>
                    </div>
                @endif

                  {{-- User SMS Consumption Component --}}
                <livewire:user-sms-consumption />


                {{-- SMS Import Form Component --}}
                <livewire:sms-import-form />




            </div>
        </div>
    </div>
</div>
