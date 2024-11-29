<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="text-gray-900 dark:text-gray-100 mb-6">
                    {{ __("You're logged in!") }}
                </div>

                <div class="mb-8">
                    <form action="{{ route('sms.import') }}" method="POST" enctype="multipart/form-data" class="flex items-center space-x-4">
                        @csrf
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Subir archivo para importar
                            </label>
                            <input
                                type="file"
                                name="file"
                                required
                                class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100"
                            >
                        </div>
                        <button
                            type="submit"
                            class="bg-green-600 text-white px-6 py-2 rounded-md font-bold hover:bg-indigo-700 transition shadow-md"
                        >
                            Importar
                        </button>
                    </form>
                </div>

                <!-- Formulario de Enviar SMS -->
                <div>
                    <form id="smsForm" action="{{ route('sms.send', ['phone' => 'PHONE_PLACEHOLDER', 'message' => 'MESSAGE_PLACEHOLDER']) }}" method="POST" class="space-y-4">
                        @csrf
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Número de teléfono
                        </label>
                        <input
                            type="text"
                            name="phone"
                            placeholder="Número de teléfono"
                            required
                            class="block w-full px-4 py-2 border border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 focus:ring-indigo-500 focus:border-indigo-500"
                        >
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Mensaje
                        </label>
                        <input
                            type="text"
                            name="message"
                            placeholder="Mensaje"
                            required
                            class="block w-full px-4 py-2 border border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 focus:ring-indigo-500 focus:border-indigo-500"
                        >
                        <button
                            type="submit"
                            class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition"
                        >
                            Enviar SMS
                        </button>
                    </form>
                </div>

                <!-- Script -->
                <script>
                    document.getElementById('smsForm').addEventListener('submit', function(event) {
                        event.preventDefault();
                        const phone = document.querySelector('input[name="phone"]').value;
                        const message = document.querySelector('input[name="message"]').value;
                        const action = this.action
                            .replace('PHONE_PLACEHOLDER', encodeURIComponent(phone))
                            .replace('MESSAGE_PLACEHOLDER', encodeURIComponent(message));
                        this.action = action;
                        this.submit();
                    });
                </script>
            </div>
        </div>
    </div>
</x-app-layout>
