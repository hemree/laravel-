<!DOCTYPE html>
<html>
<head>
    <title>Dosya Yükleme</title>
</head>
<body>
<h1>Dosya Yükleme Formu</h1>
<form action="{{ route('upload') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <label for="file">Dosya Seçin:</label>
    <input type="file" name="file" id="file" required>
    <button type="submit">Yükle</button>
</form>
@if(session('success'))
    <p>{{ session('success') }}</p>
@endif
@if(session('error'))
    <p>{{ session('error') }}</p>
@endif
</body>
</html>
