<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giriş Yap</title>
</head>
<body>
    <h1>Giriş</h1>
    @if(isset($error))
        {{ $error }}
    @endif
    @if(isset($success) && $success)
        {{success}}
    @endif
    <form action="{{ route('postlogin') }}" method="POST">
        @csrf
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="password" name="password" placeholder="Şifre" required><br>
        <button type="submit">Giriş Yap</button>
    </form>

</body>
</html>