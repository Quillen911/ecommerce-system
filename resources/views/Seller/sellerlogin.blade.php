<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Satıcı Giriş</title>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h1>Satıcı Giriş</h1>
                <form action="{{ route('seller.postlogin') }}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="password">Şifre</label>
                        <input type="password" name="password" id="password" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary">Giriş Yap</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
