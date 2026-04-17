<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Email Confirmed - CRAD</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="min-height:100vh;">
    <div class="card shadow text-center p-5" style="max-width:520px;width:100%;">
        <div class="mb-3">
            <i class="bi bi-check-circle-fill text-success" style="font-size:4rem;"></i>
        </div>
        <h3 class="fw-bold text-success">Email Confirmed!</h3>
        <p class="text-muted mt-2">Your email has been successfully confirmed. You will now receive notifications from the CRAD system.</p>
        @if(isset($error))
        <div class="alert alert-warning mt-3 text-start">
            <small>{{ $error }}</small>
        </div>
        @endif
        @if(isset($password))
        <div class="alert alert-info mt-3 text-start">
            <strong><i class="bi bi-key-fill"></i> Admin Login Credentials:</strong>
            <hr>
            <p class="mb-1"><strong>Email:</strong> {{ $email ?? 'N/A' }}</p>
            <p class="mb-0"><strong>Password:</strong> <code class="text-dark fs-6">{{ $password }}</code></p>
            <small class="text-danger d-block mt-2">⚠️ Save this password securely. It will not be shown again.</small>
        </div>
        @endif
    </div>
</body>
</html>
