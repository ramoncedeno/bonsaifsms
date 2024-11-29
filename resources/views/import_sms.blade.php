<form action="{{ route('sms.import') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" name="file" required>
    <button type="submit">Importar</button>
</form>


<table>
    <thead>
        <tr>
            <th>Numero</th>
            <th>Estatus</th>
            <th>Mensaje</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $sms)
            <tr>
                <td>{{ $sms->id }}</td>
                <td>{{ $sms->status }}</td>
                <td>{{ $sms->message }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

{{ $data->links('pagination::bootstrap-4') }}
