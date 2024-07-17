<!DOCTYPE html>
<html>
<head>
    <title>Veri İçe Aktarma</title>
</head>
<body>
<h1>Veri İçe Aktarma</h1>
<form action="{{ route('import') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" name="file" required>
    <button type="submit">İçe Aktar</button>
</form>
@if (session('success'))
    <p>{{ session('success') }}</p>
@endif
<h2>Veriler</h2>
<table border="1">
    <tr>
        <th>Column 1</th>
        <th>Column 2</th>
        <!-- Diğer kolonlar... -->
    </tr>
    @foreach ($data as $row)
        <tr>
            <td>{{ $row->column1 }}</td>
            <td>{{ $row->column2 }}</td>
            <!-- Diğer kolonlar... -->
        </tr>
    @endforeach
</table>
</body>
</html>
