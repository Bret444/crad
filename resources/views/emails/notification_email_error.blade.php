<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Error - CRAD</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="min-height:100vh;">
    <div class="card shadow text-center p-5" style="max-width:520px;width:100%;">
        <div class="mb-3">
            <i class="bi bi-x-circle-fill text-danger" style="font-size:4rem;"></i>
        </div>
        <h3 class="fw-bold text-danger">Error</h3>
        <p class="text-muted mt-2">{{ $error }}</p>
    </div>
</body>
</html>
