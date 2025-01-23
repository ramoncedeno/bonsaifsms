<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMS Attempt View</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    @if (session('success'))
        <div class="alert alert-success bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="container mx-auto p-4">
        <form action="{{ route('sms.import') }}" method="POST" enctype="multipart/form-data" class="mb-4 bg-white p-6 rounded-lg shadow-md">
            @csrf
            <div class="flex flex-col md:flex-row items-center">
                <input type="file" name="file" required class="block w-full md:w-auto text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                <button type="submit" class="mt-2 md:mt-0 md:ml-4 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700">Importar</button>
            </div>
        </form>
    </div>

    <div class="container mx-auto my-5 bg-white p-6 rounded-lg shadow-md">
        <!-- Campo de Búsqueda -->
        <form method="GET" action="{{ route('sms.reg.view') }}" class="mb-4">
            <div class="flex flex-col md:flex-row items-center">
                <input type="text" name="search" placeholder="Buscar..." value="{{ request('search') }}" class="w-full md:w-auto p-2 border border-gray-300 rounded">
                <button type="submit" class="mt-2 md:mt-0 md:ml-4 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700">Buscar</button>
            </div>
        </form>

        <!-- Barra de Paginación Superior -->
        <div class="flex justify-between items-center mt-3">
            {{ $sendAttempts->links() }}
        </div>

        <!-- Tabla de Registros -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="py-2 px-4 border-b">{{ __('Subject') }}</th>
                        <th class="py-2 px-4 border-b">{{ __('Sponsor') }}</th>
                        <th class="py-2 px-4 border-b">{{ __('ID') }}</th>
                        <th class="py-2 px-4 border-b">{{ __('Phone') }}</th>
                        <th class="py-2 px-4 border-b">{{ __('Message') }}</th>
                        <th class="py-2 px-4 border-b">{{ __('Status') }}</th>
                        <th class="py-2 px-4 border-b">{{ __('Response ID') }}</th>
                        <th class="py-2 px-4 border-b">{{ __('Created') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sendAttempts as $sendAttempt)
                        <tr class="hover:bg-gray-50">
                            <td class="py-2 px-4 border-b">{{ $sendAttempt->subject }}</td>
                            <td class="py-2 px-4 border-b">{{ $sendAttempt->sponsor }}</td>
                            <td class="py-2 px-4 border-b">{{ $sendAttempt->identification_id }}</td>
                            <td class="py-2 px-4 border-b">{{ $sendAttempt->phone }}</td>
                            <td class="py-2 px-4 border-b">{{ $sendAttempt->message }}</td>
                            <td class="py-2 px-4 border-b">
                                <span class="px-2 py-1 rounded {{ $sendAttempt->status == 'success' ? 'bg-green-500 text-white' : 'bg-blue-500 text-white' }}">
                                    {{ ucfirst($sendAttempt->status) }}
                                </span>
                            </td>
                            <td class="py-2 px-4 border-b">{{ $sendAttempt->response_id }}</td>
                            <td class="py-2 px-4 border-b">{{ $sendAttempt->created_at->format('d/m/Y H:i') }}</td>
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
</body>
</html>
