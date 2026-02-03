<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Verify OTP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('img/sms.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body,
        html {
            height: 100%;
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa; /* Light gray background */
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
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05); /* Softer shadow */
        }

        .login-form-wrapper {
            width: 100%;
            max-width: 320px;
            margin: auto;
        }

        .branding-container {
            display: none;
        }

        .btn-custom-verify {
            background-color: #007FFF;
            background-image: linear-gradient(to right, #40b6ed, #447ce4);
            border: none;
            color: white;
            padding: 0.75rem;
            width: 100%;
            font-size: 1rem;
            border-radius: 2rem;
            font-weight: 700;
            transition: background-image 0.3s;
        }

        .btn-custom-verify:hover {
            background-image: linear-gradient(to right, #004C99, #40b6ed);
        }
        
        .form-control {
            border-radius: 0.5rem;
        }

        .form-control:focus {
            border-color: #007FFF;
            box-shadow: 0 0 0 0.25rem rgba(98, 89, 202, 0.25);
        }

        /* Desktop Layout */
        @media (min-width: 992px) {
            .main-container {
                flex-direction: row;
            }

            .login-form-container {
                width: 45%;
                order: 1;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .branding-container {
                width: 55%;
                color: white;
                display: flex;
                align-items: center;
                justify-content: center;
                flex-direction: column;
                text-align: center;
                background-color: #172b5c; /* Solid blue for simplicity */
                background-image: url("{{ asset('img/background.jpg') }}");
                background-size: cover;
                background-position: center;
                order: 2;
                padding: 4rem;
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
                text-align: center;
            }
        }
    </style>
</head>

<body>
    <div class="main-container">
        <div class="login-form-container">
            <div class="login-form-wrapper">
                <div class="text-center mb-4">
                    <img src="{{ asset('img/sms.png') }}" alt="Logo" style="width: 90px;">
                    <h2 class="mt-3 fw-bold">Verify Your Account</h2>
                </div>

                <div class="text-center mb-4 text-secondary">
                    Your OTP is valid for 10 minutes.
                </div>

                <form method="POST" action="{{ route('otp.verify.submit') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="otp" class="form-label visually-hidden">OTP</label>
                        <input type="text" class="form-control text-center" id="otp" name="otp" required autofocus placeholder="Enter OTP">
                    </div>

                    <button type="submit" class="btn btn-custom-verify mt-4">Verify OTP</button>
                </form>
            </div>
        </div>

        <div class="branding-container">
            <div class="branding-text">
                <h1>School Management<br>System III</h1>
                <p>Center For Research And Development (CRAD)</p>
            </div>
        </div>
    </div>

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
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Continue to Dashboard</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // No countdown timer needed for 3-day OTP
    
        // Get the form and the modal element
        const otpForm = document.querySelector('form');
        const successModal = new bootstrap.Modal(document.getElementById('successModal'));
        const continueBtn = document.querySelector('#successModal .btn-primary');
        
        // Add an event listener for the form submission
        otpForm.addEventListener('submit', async function (event) {
            event.preventDefault(); // Prevent the default form submission (page reload)
    
            // Get the OTP value from the input field
            const otp = document.getElementById('otp').value;
            const csrfToken = this.querySelector('input[name="_token"]').value;
    
            // Create a data object to send to the server
            const formData = new FormData();
            formData.append('otp', otp);
            formData.append('_token', csrfToken);
    
            try {
                // Send the data using Fetch API
                const response = await fetch(this.action, {
                    method: 'POST',
                    body: formData
                });
    
                const data = await response.json();
    
                if (data.success) {
                    // OTP is valid!
                    
                    // Show the success modal
                    successModal.show();
    
                    // Add a click listener to the continue button to redirect
                    continueBtn.addEventListener('click', () => {
                        window.location.href = data.redirect_url || '/dashboard';
                    });
                    
                    // You can also automatically redirect after a few seconds
                    setTimeout(() => {
                        window.location.href = data.redirect_url || '/dashboard';
                    }, 3000); // Redirect after 3 seconds
                } else {
                    // OTP is invalid or expired
                    // Display an error message to the user (e.g., as an alert or on the page)
                    alert(data.message || 'Invalid OTP. Please try again.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred. Please try again later.');
            }
        });
    </script>
</body>

</html>