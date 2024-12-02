<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Individual SMS sending') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">

                <!-- Formulario de Enviar SMS -->
                <div>
                    <form id="smsForm" action="{{ route('sms.send', ['phone' => 'PHONE_PLACEHOLDER', 'message' => 'MESSAGE_PLACEHOLDER']) }}" method="POST" class="space-y-4">
                        @csrf
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{ __("Phone number") }}
                        </label>
                        <input
                            type="text"
                            name="phone"
                            placeholder="Phone number"
                            required
                            class="block w-full px-4 py-2 border border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 focus:ring-indigo-500 focus:border-indigo-500"
                        >
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{ __("Message") }}
                        </label>
                        <input
                            type="text"
                            name="message"
                            placeholder="Message"
                            required
                            class="block w-full px-4 py-2 border border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 focus:ring-indigo-500 focus:border-indigo-500"
                        >

                        <div class="flex justify-center items-center">
                            <button
                                type="submit"
                                class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition"
                            >
                                {{ __("Send Message") }}
                            </button>
                        </div>

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

