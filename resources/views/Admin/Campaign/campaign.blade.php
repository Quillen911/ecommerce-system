<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
</head>
<body>
    <h1>Kampanya Ayarları</h1>

    <table border="1" width="100%" style="text-align: center; margin-bottom: 20px;">
        <thead>
            <tr>
                <th>ID</th>
                <th>Kampanya Adı</th>
                <th>Kampanya Tipi</th>
                <th>Kampanya Açıklaması</th>
                <th>Kampanya Önceliği</th>
                <th>Kalan Limit</th>
                <th>Kullanıcı Kullanım Limit</th>
                <th>Kampanya Başlangıç Tarihi</th>
                <th>Kampanya Bitiş Tarihi</th>
                <th>Kampanya Aktiflik Durumu</th>
                <th>Düzenle</th>
            </tr>
        </thead>
        <tbody>
        @foreach($campaigns as $campaign)
            <tr>
                <td>{{ $campaign->id }}</td>
                <td>{{ $campaign->name }}</td>
                <td>{{ $campaign->type }}</td>
                <td>{{ $campaign->description }}</td>
                <td>{{ $campaign->priority ?? 'Yok' }}</td>
                <td>{{ $campaign->usage_limit }}</td> 
                <td>{{ $campaign->usage_limit_for_user }}</td>
                <td>{{ $campaign->starts_at }}</td>
                <td>{{ $campaign->ends_at }}</td>
                <td>{{ $campaign->is_active ? 'Aktif' : 'Pasif' }}</td>
                <td>
                    <a href="{{ route('admin.editCampaign', $campaign->id) }}">Düzenle</a>
                </td>
            </tr>
        @endforeach
    </tbody>
    </table>
    <br>
    
    <a href="{{ route('admin.storeCampaign') }}">Kampanya Ekle</a><br>
    <a href="{{ route('admin') }}">Geri Dön</a> 
</body>
</html>