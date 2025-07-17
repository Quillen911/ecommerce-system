<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyOrders</title>
</head>
<body>
    <h1>Siparişlerim</h1>
    @if(isset($success))
        <p>{{$success}}</p>
    @endif
    @if(isset($error))
        <p>{{$error}}</p>
    @endif
    
    @foreach($orders as $order)
        <div style="margin-bottom: 30px; border: 1px solid #333; padding: 10px;">
            <label>Sipariş No: </label>{{ $order->id }}<br>
            <label>Sipariş Tarihi: </label>{{ $order->created_at }}<br>
            <label>Durum: </label>{{ $order->status }}<br>
            <label>Toplam Fiyat: </label>{{ $order->price }}<br>
            <table border="5" cellpadding="8" cellspacing="0">
                <thead>
                    <tr>
                        <th>Ürün</th>
                        <th>Kategori Adı</th>
                        <th>Yazar</th>
                        <th>Ürün Sayısı</th>
                        <th>Fiyat</th>
                        <th>Toplam Fiyat</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->orderItems as $item)
                    <tr>
                        <td>{{ $item->product->title }}</td>
                        <td>{{ $item->product->category?->category_title }}</td>
                        <td>{{ $item->product->author }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ $item->product->list_price }}</td>
                        <td>{{ $item->product->list_price * $item->quantity }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endforeach
    <button onclick="window.location.href='/main'">Ana Sayfaya Dön</button>
</body>
</html>