<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tüm Siparişlerim</title>
</head>
<body>
    <h1>Tüm Siparişlerim</h1>
    
    @foreach($orders as $order)
        <a href="/myorders">Sipariş No  {{$order-> id}} </a><br>
    @endforeach
    <button onclick="window.location.href='/main'">Ana Sayfaya Dön</button>
</body>
</html>