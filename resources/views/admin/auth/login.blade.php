<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - NumNam</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@500;700;800;900&display=swap">
    <link rel="stylesheet" href="{{ url('assets/admin/css/admin.css') }}">
</head>
<body class="admin-body auth-page">
<section class="auth-box">
    <h1>Admin Login</h1>
    <p>Secure access for NumNam operations team.</p>

    @if($errors->any())
        <div class="admin-error">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('admin.login.submit') }}" class="auth-form">
        @csrf
        <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
        <input type="password" name="password" placeholder="Password" required>
        <label><input type="checkbox" name="remember"> Remember me</label>
        <button type="submit" class="admin-btn">Sign in</button>
    </form>
</section>
</body>
</html>
