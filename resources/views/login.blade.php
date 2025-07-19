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
        <input style="background-color: #000; color: #fff; border-radius: 10px; padding: 10px; border: 1px solid #000; cursor: pointer;" type="email" name="email" placeholder="Email" required><br>
        <input style="background-color: #000; color: #fff; border-radius: 10px; padding: 10px; border: 1px solid #000; cursor: pointer;" type="password" name="password" placeholder="Şifre" required><br>
        <button style="background-color: #000; color: #fff; border-radius: 10px; padding: 10px; border: 1px solid #000; cursor: pointer;" type="submit">Giriş Yap</button>
    </form>

</body>
</html>