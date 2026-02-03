<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="{{ asset('img/sms.png') }}">

  <!-- Bootstrap & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

  <style>
    body,
    html {
      height: 100%;
      margin: 0;
      font-family: 'Poppins', sans-serif;
      background-color: #f8f9fa;
    }

    .main-container {
      display: flex;
      flex-direction: column;
      height: 100%;
      width: 100%;
    }

    .login-form-container {
      max-width: 400px;
      margin: auto;
      background: #fff;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
    }

    .login-form-wrapper {
      width: 100%;
      max-width: 380px;
      margin: auto;
    }

    .branding-container {
      display: none;
    }

    .btn-custom-login {
      background-color: #007FFF;
      background-image: linear-gradient(to right, #40b6ed, #447ce4);
      border: none;
      color: white;
      padding: 0.75rem;
      width: 100%;
      font-size: 1rem;
      border-radius: 2rem;
      font-weight: 700;
      transition: background-color 0.3s;
    }

    .btn-custom-login:hover {
      background-image: linear-gradient(to right, #004C99, #40b6ed);
    }

    .btn-custom-login:disabled {
      background-color: #6c757d;
      cursor: not-allowed;
    }

    .form-control {
      border-radius: 0.5rem;
    }

    .form-control:focus {
      border-color: #007FFF;
      box-shadow: 0 0 0 0.25rem rgba(98, 89, 202, 0.25);
    }

    .invalid-feedback {
      font-size: .875em;
    }

    .fw-bold {
      font-weight: 700 !important;
    }

    .password-wrapper {
      position: relative;
    }

    .password-wrapper .form-control {
      padding-right: 2.5rem;
    }

    #togglePassword {
      position: absolute;
      right: 10px;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
      color: #6c757d;
    }

    @media (min-width: 992px) {
      .main-container {
        flex-direction: row;
      }

      .login-form-container {
        width: 50%;
        padding: 4rem;
        order: 1;
        display: flex;
        align-items: center;
        justify-content: center;
      }

      .branding-container {
        width: 50%;
        color: white;
        display: flex;
        align-items: flex-start;
        justify-content: center; 
        flex-direction: column;
        text-align: left;
        background-image: url("{{ asset('img/background.jpg') }}");
        background-size: cover;
        background-position: center;
        padding-left: 4rem;
        order: 2;
      }

      .branding-container h1 {
        font-size: 3.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
      }

      .branding-container p {
        margin-top: 0.5rem;
        margin-bottom: 0.5rem;
        font-size: 1.2rem;
      }
    }

    

  </style>
</head>

<body>
  <div class="main-container">

    <!-- Login Form Column -->
    <div class="login-form-container">
      <div class="login-form-wrapper">
        

        <div class="text-center mb-4">
          <img src="{{ asset('img/sms.png') }}" alt="Logo" style="width: 90px;">
          <h2 class="mt-3 fw-bold">Sign in</h2>
        </div>

        <form method="POST" action="{{ url('/login') }}" id="loginForm">
          @if ($errors->any())
            <div class="alert alert-danger">
              <ul class="mb-0">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif
          @csrf
          <div class="mb-3">
            <label for="email" class="form-label">ID/Username<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="email" name="email" value="{{ old('email') }}" required autofocus>
          </div>

          <div class="mb-4">
            <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
            <div class="password-wrapper">
              <input type="password" class="form-control" id="password" name="password" required>
              <i class="bi bi-eye-slash" id="togglePassword"></i>
            </div>
          </div>

          <button type="submit" id="loginBtn" class="btn btn-custom-login">Sign in</button>
        </form>
      </div>
    </div>

    <!-- Branding Column -->
    <div class="branding-container">
      <h1>School Management<br>System III</h1>
      <p>Center For Research And Development (CRAD)</p>
    </div>
  </div>

  <!-- Success Modal -->
  <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="successModalLabel">Login Successful!</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body text-center">
          <i class="bi bi-check-circle-fill text-success" style="font-size: 3rem;"></i>
          <p class="mt-3">You have successfully logged in to your account.</p>
        </div>
        <div class="modal-footer justify-content-center">
          <button type="button" class="btn btn-primary" id="continueBtn">Continue to Dashboard</button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const emailInput = document.getElementById('email');
    const loginBtn = document.getElementById('loginBtn');
    const loginForm = document.getElementById('loginForm');
    
    let lockoutTimer = null;

    togglePassword.addEventListener('click', function () {
      const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
      passwordInput.setAttribute('type', type);
      this.classList.toggle('bi-eye');
      this.classList.toggle('bi-eye-slash');
    });

    // Check for lockout on page load
    function checkLockout() {
      const email = emailInput.value.trim();
      if (!email) return;
      
      fetch('/check-lockout', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
        },
        body: JSON.stringify({
          email: email
        })
      })
      .then(response => response.json())
      .then(data => {
        if (data.locked && data.seconds > 0) {
          startLockoutTimer(data.seconds);
        } else {
          clearLockout();
        }
      })
      .catch(() => clearLockout());
    }

    // Start lockout timer
    function startLockoutTimer(seconds) {
      clearLockout();
      
      // Create lockout message
      const lockoutDiv = document.createElement('div');
      lockoutDiv.id = 'lockout-message';
      lockoutDiv.className = 'alert alert-danger text-center mt-3';
      loginForm.appendChild(lockoutDiv);
      
      // Disable form
      loginBtn.disabled = true;
      emailInput.disabled = true;
      passwordInput.disabled = true;
      
      // Update timer
      function updateTimer() {
        lockoutDiv.innerHTML = `🔒 Too many attempts. Try again in <strong>${seconds}</strong> seconds.`;
        seconds--;
        if (seconds < 0) {
          clearLockout();
          location.reload();
        }
      }
      
      updateTimer();
      lockoutTimer = setInterval(updateTimer, 1000);
    }

    // Clear lockout
    function clearLockout() {
      if (lockoutTimer) {
        clearInterval(lockoutTimer);
        lockoutTimer = null;
      }
      
      const lockoutDiv = document.getElementById('lockout-message');
      if (lockoutDiv) {
        lockoutDiv.remove();
      }
      
      loginBtn.disabled = false;
      emailInput.disabled = false;
      passwordInput.disabled = false;
    }

    // Form submission
    loginForm.addEventListener('submit', function(e) {
      if (loginBtn.disabled) {
        e.preventDefault();
        return;
      }
      
      if (!emailInput.value.trim() || !passwordInput.value.trim()) {
        e.preventDefault();
        alert('Please enter both username and password.');
        return;
      }
    });

    // Check lockout when email changes
    emailInput.addEventListener('blur', checkLockout);

    // Check on page load
    document.addEventListener('DOMContentLoaded', function() {
      checkLockout();
    });

    // Handle server lockout response immediately
    @if(session('lockout_seconds'))
      startLockoutTimer({{ session('lockout_seconds') }});
    @endif

    // Show success modal if login was successful
    @if(session('login_success'))
      document.addEventListener('DOMContentLoaded', function() {
        const successModal = new bootstrap.Modal(document.getElementById('successModal'));
        successModal.show();
        
        document.getElementById('continueBtn').addEventListener('click', function() {
          window.location.href = '{{ session('redirect_url') }}';
        });
      });
    @endif

    // Handle URL parameters for success modal
    document.addEventListener('DOMContentLoaded', function() {
      const urlParams = new URLSearchParams(window.location.search);
      if (urlParams.get('login_success') === '1') {
        const successModal = new bootstrap.Modal(document.getElementById('successModal'));
        successModal.show();
        
        document.getElementById('continueBtn').addEventListener('click', function() {
          window.location.href = decodeURIComponent(urlParams.get('redirect') || '/std-dashboard');
        });
      }
    });

  </script>

  

</body>
</html>