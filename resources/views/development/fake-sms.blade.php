<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sms</title>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Kargolam için Fake SMS Logları</h4>
                    <div>
                        <span class="badge bg-primary">{{ $smsCount ?? 0 }} SMS</span>
                        @if($smsCount > 0)
                            <form method="POST" action="{{ route('development.fake-sms.clear') }}" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger ms-2">Temizle</button>
                            </form>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    @if(isset($smsLogs) && !empty($smsLogs))
                        <pre class="bg-light p-3 rounded">{{ $smsLogs }}</pre>
                    @else
                        <p class="text-muted">Henüz fake SMS gönderilmemiş.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>