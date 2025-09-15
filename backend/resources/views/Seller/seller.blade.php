<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Satıcı Paneli</title>
  <style>
    :root{
      --bg:#1E293B; --text:#F1F5F9; --muted:#94A3B8; --line:#334155;
      --accent:#3B82F6; --success:#22C55E; --warn:#F59E0B; --danger:#EF4444;
      --header:#0F172A; --card:#334155;
    }
    *{box-sizing:border-box}
    html,body{margin:0;padding:0;background:var(--bg);color:var(--text)}
    body{font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Inter,"Helvetica Neue",Arial,sans-serif;letter-spacing:.2px;line-height:1.4}
    .shell{max-width:1060px;margin:0 auto;padding:24px 16px 80px}
    h1{font-size:22px;font-weight:600;text-transform:uppercase;letter-spacing:4px;margin:20px 0 10px;text-align:center}
    .actions{display:flex;gap:12px;flex-wrap:wrap;justify-content:center;margin-top:20px}
    .btn{border:1px solid var(--accent);background:var(--accent);color:#fff;padding:10px 16px;border-radius:28px;cursor:pointer;text-transform:uppercase;letter-spacing:1.2px;font-size:12px;transition:filter .15s ease;text-decoration:none;display:inline-flex;align-items:center;gap:8px}
    .btn.outline{background:transparent;color:var(--accent)}
    .btn.danger{background:var(--danger);border-color:var(--danger)}
    .btn:hover{filter:brightness(0.9)}
  </style>
</head>
<body>
<div class="shell">
  <h1>Satıcı Paneli</h1>
  <div class="actions">
    <a href="{{ route('seller.product') }}" class="btn">Ürünler</a>
    <a href="{{ route('seller.campaign') }}" class="btn outline">Kampanyalar</a>
    <a href="{{ route('seller.order') }}" class="btn outline">Siparişler</a>
    <a href="{{ route('settings.index') }}" class="btn outline">Ayarlar</a>
    <form action="{{ route('seller.logout') }}" method="POST" style="display:inline-block;margin:0">
      @csrf
      <button type="submit" class="btn danger">Çıkış Yap</button>
    </form>
  </div>
  <div class="seller-info" style="margin-top: 60px;" align="center">
    <h2>Satıcı Bilgileri</h2>
    <p>Satıcı ID: {{ $sellerInfo->id }}</p>
    <p>Satıcı Adı: {{ $sellerInfo->seller_name}}</p>
    <p>Satıcı Mağaza Adı: {{ $sellerInfo->name }}</p>
    <p>Satıcı Email: {{ $sellerInfo->seller->email }}</p>
  </div>
</div>
</body>
</html>
