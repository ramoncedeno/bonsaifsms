<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight mb-4">
                    {{ __('SMS Attempt Records') }}
                </h2>

                {{-- Livewire message display --}}
                @if ($message)
                    <div class="alert alert-info bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ $message }}</span>
                    </div>
                @endif

                <div class="container mx-auto p-4">
                    {{-- Livewire Import Form --}}
                    <form wire:submit.prevent="importSms" enctype="multipart/form-data" class="mb-4 bg-white p-6 rounded-lg shadow-md">
                        <div class="flex flex-col md:flex-row items-center">
                            <input type="file" wire:model="file" required class="block w-full md:w-auto text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            <button type="submit" wire:loading.attr="disabled" wire:target="importSms" class="mt-2 md:mt-0 md:ml-4 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700">
                                <span wire:loading.remove wire:target="importSms">Importar</span>
                                <span wire:loading wire:target="importSms">Procesando...</span>
                            </button>
                        </div>
                        @error('file') <span class="text-red-500 text-xs mt-2">{{ $message }}</span> @enderror
                    </form>
                </div>

                <div class="container mx-auto my-5 bg-white p-6 rounded-lg shadow-md">
                    <!-- Campo de Búsqueda -->
                    <div class="mb-4">
                        <div class="flex flex-col md:flex-row items-center">
                            <input type="text" wire:model.live="search" placeholder="Buscar..." class="w-full md:w-auto p-2 border border-gray-300 rounded text-gray-900 dark:text-gray-800">
                        </div>
                    </div>

                    <!-- Barra de Paginación Superior -->
                    <div class="flex justify-between items-center mt-3">
                        {{ $sendAttempts->links() }}
                    </div>

                    <!-- Tabla de Registros -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200">
                            <thead class="bg-gray-800 dark:bg-gray-900">
                                <tr>
                                    <th class="py-2 px-4 border-b text-white dark:text-gray-100">{{ __('Subject') }}</th>
                                    <th class="py-2 px-4 border-b text-white dark:text-gray-100">{{ __('Sponsor') }}</th>
                                    <th class="py-2 px-4 border-b text-white dark:text-gray-100">{{ __('ID') }}</th>
                                    <th class="py-2 px-4 border-b text-white dark:text-gray-100">{{ __('Phone') }}</th>
                                    <th class="py-2 px-4 border-b text-white dark:text-gray-100">{{ __('Message') }}</th>
                                    <th class="py-2 px-4 border-b text-white dark:text-gray-100">{{ __('Status') }}</th>
                                    <th class="py-2 px-4 border-b text-white dark:text-gray-100">{{ __('Response ID') }}</th>
                                    <th class="py-2 px-4 border-b text-white dark:text-gray-100">{{ __('Created') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sendAttempts as $sendAttempt)
                                    <tr class="hover:bg-gray-50">
                                        <td class="py-2 px-4 border-b text-gray-800 dark:text-gray-900">{{ $sendAttempt->subject }}</td>
                                        <td class="py-2 px-4 border-b text-gray-800 dark:text-gray-900">{{ $sendAttempt->sponsor }}</td>
                                        <td class="py-2 px-4 border-b text-gray-800 dark:text-gray-900">{{ $sendAttempt->identification_id }}</td>
                                        <td class="py-2 px-4 border-b text-gray-800 dark:text-gray-900">{{ $sendAttempt->phone }}</td>
                                        <td class="py-2 px-4 border-b text-gray-800 dark:text-gray-900">{{ $sendAttempt->message }}</td>
                                        <td class="py-2 px-4 border-b">
                                            <span class="px-2 py-1 rounded {{ $sendAttempt->status == 'success' ? 'bg-green-500 text-white' : 'bg-blue-500 text-white' }}">
                                                {{ ucfirst($sendAttempt->status) }}
                                            </span>
                                        </td>
                                        <td class="py-2 px-4 border-b text-gray-800 dark:text-gray-900">{{ $sendAttempt->response_id }}</td>
                                        <td class="py-2 px-4 border-b text-gray-800 dark:text-gray-900">{{ $sendAttempt->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Barra de Paginación Inferior -->
                    <div class="flex justify-between items-center mt-3">
                        {{ $sendAttempts->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>