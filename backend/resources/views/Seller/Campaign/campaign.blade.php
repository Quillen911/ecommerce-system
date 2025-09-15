<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kampanya Yönetimi - Satıcı Paneli</title>
    <style>
        :root{
            --bg:#1E293B; --text:#F1F5F9; --muted:#94A3B8; --line:#334155;
            --accent:#3B82F6; --success:#22C55E; --warn:#F59E0B; --danger:#EF4444;
            --header:#0F172A; --card:#334155;
        }
        *{box-sizing:border-box}
        html,body{margin:0;padding:0;background:var(--bg);color:var(--text)}
        body{font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Inter,"Helvetica Neue",Arial,sans-serif;letter-spacing:.2px;line-height:1.4}
        .shell{max-width:1400px;margin:0 auto;padding:24px 16px 80px}
        
        /* Header */
        .header{background:var(--header);color:var(--text);padding:20px 0;margin:-24px -16px 24px;border-radius:0 0 16px 16px;box-shadow:0 4px 6px rgba(0,0,0,0.3)}
        .header-content{max-width:1400px;margin:0 auto;padding:0 16px;display:flex;justify-content:space-between;align-items:center}
        .header h1{font-size:24px;font-weight:700;margin:0;letter-spacing:2px;text-transform:uppercase;color:var(--text)}
        .header-stats{display:flex;gap:24px;align-items:center}
        .stat{text-align:center}
        .stat-number{font-size:18px;font-weight:600;display:block;color:var(--text)}
        .stat-label{font-size:11px;opacity:0.8;text-transform:uppercase;letter-spacing:1px;color:var(--muted)}
        
        /* Toolbar */
        .toolbar{display:flex;justify-content:space-between;align-items:center;gap:16px;margin-bottom:24px;flex-wrap:wrap}
        .btn{border:1px solid var(--accent);background:var(--accent);color:#fff;padding:12px 20px;border-radius:8px;cursor:pointer;text-transform:uppercase;letter-spacing:1px;font-size:12px;font-weight:600;transition:all .2s ease;text-decoration:none;display:inline-flex;align-items:center;gap:8px}
        .btn:hover{background:#2563EB;border-color:#2563EB;transform:translateY(-1px);box-shadow:0 4px 12px rgba(59,130,246,0.4)}
        .btn.outline{background:transparent;color:var(--accent);border-color:var(--accent)}
        .btn.outline:hover{background:var(--accent);color:#fff}
        .btn.danger{background:var(--danger);border-color:var(--danger)}
        .btn.danger:hover{background:#DC2626;box-shadow:0 4px 12px rgba(239,68,68,0.4)}
        .btn.success{background:var(--success);border-color:var(--success)}
        .btn.success:hover{background:#16A34A;box-shadow:0 4px 12px rgba(34,197,94,0.4)}
        .btn-sm{padding:6px 12px;font-size:10px;border-radius:6px}
        
        /* Campaigns Table */
        .campaigns-container{background:var(--card);border-radius:12px;overflow:hidden;box-shadow:0 4px 6px rgba(0,0,0,0.3)}
        .campaigns-table{width:100%;border-collapse:collapse}
        .campaigns-table th{background:var(--header);padding:16px 12px;text-align:left;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--muted);border-bottom:2px solid var(--line)}
        .campaigns-table td{padding:16px 12px;border-bottom:1px solid var(--line);vertical-align:middle;color:var(--text)}
        .campaigns-table tr:hover{background:rgba(59,130,246,0.1)}
        .campaigns-table tr:last-child td{border-bottom:none}
        
        /* Badges */
        .badge{padding:4px 8px;border-radius:4px;font-size:10px;font-weight:600;text-transform:uppercase;letter-spacing:.5px}
        .badge.success{background:rgba(34,197,94,0.2);color:var(--success);border:1px solid var(--success)}
        .badge.danger{background:rgba(239,68,68,0.2);color:var(--danger);border:1px solid var(--danger)}
        .badge.warning{background:rgba(245,158,11,0.2);color:var(--warn);border:1px solid var(--warn)}
        .badge.info{background:rgba(59,130,246,0.2);color:var(--accent);border:1px solid var(--accent)}
        
        /* Campaign Status */
        .status{font-weight:600;font-size:12px}
        .status.active{color:var(--success)}
        .status.inactive{color:var(--danger)}
        
        /* Priority */
        .priority{font-weight:600;font-size:14px;color:var(--warn)}
        
        /* Countdown */
        .countdown{font-size:12px;font-weight:600}
        .countdown.active{color:var(--success)}
        .countdown.warning{color:var(--warn)}
        .countdown.expired{color:var(--danger)}
        
        /* Actions */
        .actions{display:flex;gap:8px}
        
        /* Empty State */
        .empty-state{text-align:center;padding:60px 20px;color:var(--muted)}
        .empty-state svg{margin-bottom:16px;opacity:0.5}
        .empty-state h3{font-size:16px;margin-bottom:8px;color:var(--text)}
        .empty-state p{margin-bottom:16px;color:var(--muted)}
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar{width:8px}
        ::-webkit-scrollbar-track{background:var(--bg)}
        ::-webkit-scrollbar-thumb{background:var(--line);border-radius:4px}
        ::-webkit-scrollbar-thumb:hover{background:var(--muted)}
        
        @media (max-width:1200px){
            .campaigns-table{font-size:12px}
            .campaigns-table th,.campaigns-table td{padding:8px 6px}
        }
        
        @media (max-width:768px){
            .header-content{flex-direction:column;gap:12px;text-align:center}
            .header-stats{justify-content:center}
            .toolbar{flex-direction:column;align-items:stretch}
        }
    </style>
</head>
<body>
<div class="shell">
    <div class="header">
        <div class="header-content">
            <h1>Kampanya Yönetimi</h1>
            <div class="header-stats">
                <div class="stat">
                    <span class="stat-number">{{ count($campaigns) }}</span>
                    <span class="stat-label">Kampanya</span>
                </div>
                <div class="stat">
                    <span class="stat-number">{{ $campaigns->where('is_active', 1)->count() }}</span>
                    <span class="stat-label">Aktif</span>
                </div>
            </div>
        </div>
    </div>

    <div class="toolbar">
        <div class="actions">
            <a href="{{ route('seller.storeCampaign') }}" class="btn success">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                Yeni Kampanya Oluştur
            </a>
        </div>
        <div class="actions">
            <a href="{{ route('seller') }}" class="btn outline">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m12 19-7-7 7-7"/><path d="M19 12H5"/>
                </svg>
                Ana Panel
            </a>
        </div>
    </div>

    @if(count($campaigns) > 0)
        <div class="campaigns-container">
            <table class="campaigns-table">
                <thead>
                    <tr>
                        <th>Kampanya</th>
                        <th>Tip</th>
                        <th>Öncelik</th>
                        <th>Kullanım</th>
                        <th>Tarih Aralığı</th>
                        <th>Durum</th>
                        <th>İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($campaigns as $campaign)
                        @php
                            $now = \Carbon\Carbon::now();
                            $endsAt = \Carbon\Carbon::parse($campaign->ends_at);
                            $remainingDays = floor($now->diffInDays($endsAt, false));
                            $isExpired = $remainingDays < 0;
                            $isActive = $campaign->is_active && !$isExpired;
                        @endphp
                        <tr>
                            <td>
                                <div>
                                    <div style="font-weight:600;color:var(--text);margin-bottom:4px;">{{ $campaign->name }}</div>
                                    <div style="font-size:12px;color:var(--muted);">{{ $campaign->description ?: 'Açıklama yok' }}</div>
                                </div>
                            </td>
                            <td>
                                <span class="badge {{ $campaign->type == 'percentage' ? 'info' : ($campaign->type == 'fixed' ? 'success' : 'warning') }}">
                                    @if($campaign->type == 'percentage')
                                        Yüzde
                                    @elseif($campaign->type == 'fixed')
                                        Sabit
                                    @else
                                        X Al Y Öde
                                    @endif
                                </span>
                            </td>
                            <td>
                                <span class="priority">{{ $campaign->priority ?? 'Yok' }}</span>
                            </td>
                            <td>
                                <div style="font-size:12px;">
                                    <div>Toplam: {{ $campaign->usage_limit }}</div>
                                    <div>Kişi: {{ $campaign->usage_limit_for_user }}</div>
                                </div>
                            </td>
                            <td>
                                <div style="font-size:12px;">
                                    <div style="color:var(--text);">{{ \Carbon\Carbon::parse($campaign->starts_at)->format('d.m.Y') }}</div>
                                    <div style="color:var(--muted);">{{ \Carbon\Carbon::parse($campaign->ends_at)->format('d.m.Y') }}</div>
                                    @if($campaign->usage_limit == 0)
                                        <div class="countdown expired">Kullanım Tükendi</div>
                                    @else
                                        @php
                                            $endsAtTimestamp = \Carbon\Carbon::parse($campaign->ends_at)->timestamp;
                                        @endphp
                                        <div class="countdown {{ $remainingDays <= 3 ? 'warning' : 'active' }}" id="countdown-{{ $campaign->id }}">
                                            Yükleniyor...
                                        </div>
                                        <script>
                                            (function() {
                                                function updateCountdown{{ $campaign->id }}() {
                                                    const now = Math.floor(Date.now() / 1000);
                                                    const endTime = {{ $endsAtTimestamp }};
                                                    const timeLeft = endTime - now;
                                                    
                                                    const countdownElement = document.getElementById('countdown-{{ $campaign->id }}');
                                                    
                                                    if (timeLeft <= 0) {
                                                        countdownElement.innerHTML = 'Kampanya sona erdi';
                                                        countdownElement.className = 'countdown expired';
                                                        return;
                                                    }
                                                    
                                                    const days = Math.floor(timeLeft / 86400);
                                                    const hours = Math.floor((timeLeft % 86400) / 3600);
                                                    const minutes = Math.floor((timeLeft % 3600) / 60);
                                                    const seconds = timeLeft % 60;
                                                    
                                                    let timeString = '';
                                                    if (days > 0) timeString += days + ' gün ';
                                                    if (hours > 0) timeString += hours + ' saat ';
                                                    if (minutes > 0) timeString += minutes + ' dk ';
                                                    timeString += seconds + ' sn';
                                                    
                                                    countdownElement.innerHTML = timeString;
                                                    
                                                    // Renk güncelleme
                                                    if (days <= 1) {
                                                        countdownElement.className = 'countdown warning';
                                                    } else {
                                                        countdownElement.className = 'countdown active';
                                                    }
                                                }
                                                
                                                updateCountdown{{ $campaign->id }}();
                                                setInterval(updateCountdown{{ $campaign->id }}, 1000);
                                            })();
                                        </script>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="status {{ $isActive ? 'active' : 'inactive' }}">
                                    {{ $isActive ? 'Aktif' : ($isExpired ? 'Süresi Dolmuş' : 'Pasif') }}
                                </span>
                            </td>
                            <td>
                                <div class="actions">
                                    <a href="{{ route('seller.editCampaign', $campaign->id) }}" class="btn btn-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/>
                                        </svg>
                                        Düzenle
                                    </a>
                                    <form action="{{ route('seller.deleteCampaign', $campaign->id) }}" method="POST" style="margin:0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm danger" onclick="return confirm('{{ $campaign->name }} kampanyasını silmek istediğinize emin misiniz?')">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c0 1 1 2 2 2v2"/>
                                            </svg>
                                            Sil
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="campaigns-container">
            <div class="empty-state">
                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/>
                </svg>
                <h3>Henüz kampanya yok</h3>
                <p>İlk kampanyanızı oluşturmak için aşağıdaki butona tıklayın.</p>
                <a href="{{ route('seller.storeCampaign') }}" class="btn success">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    İlk Kampanyamı Oluştur
                </a>
            </div>
        </div>
    @endif
</div>
</body>
</html>