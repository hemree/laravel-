<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Veri Yükleme</title>
</head>
<body>
<h1>Veri Yükleme</h1>
<form action="{{ route('upload') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" name="file">
    <button type="submit">Yükle</button>
</form>

<h2>Yüklenen Veriler</h2>
<table border="1">
    <tr>
        @if(count($data) > 0)
            @foreach(array_keys($data[0]) as $column)
                <th>{{ $column }}</th>
            @endforeach
        @endif
    </tr>
    @foreach($data as $row)
        <tr>
            @foreach($row as $cell)
                <td>{{ $cell }}</td>
            @endforeach
        </tr>
    @endforeach
</table>
</body>
</html>
