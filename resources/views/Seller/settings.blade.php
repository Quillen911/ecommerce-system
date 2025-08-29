<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ayarlar</title>
</head>
<body>
    <h1>Satıcı Ayarları</h1>
    <form action="{{ route('settings.store') }}" method="POST">
        @csrf
        <div style="display: flex; flex-direction: column; gap: 10%;">
            <div>
                <label for="api_key">API Key</label>
                <input type="text" id="api_key" name="api_key"> 
                <button type="submit">Kaydet</button>
            </div>
            <div>
                <label for="secret_key">Secret Key</label>
                <input type="text" id="secret_key" name="secret_key">
                    <button type="submit">Kaydet</button>
                </div>
            </div>
        </form>
</body>
</html>