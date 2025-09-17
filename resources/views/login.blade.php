<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POSKasir</title>
    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    {{-- Custom CSS --}}
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <style>
        .validation-message {
            display: none;
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
    </style>
</head>

<body>
    <div class="container d-flex flex-column justify-content-center py-5 px-4 min-vh-100">
        <div class="mx-auto" style="max-width: 400px;">
            <div class="text-center mb-4">
                <img src="{{ asset('images/logo-poskasir.png') }}" alt="POSKasir Logo" height="40">
                <h2 class="fw-bold mt-3">Sign in to your account</h2>
            </div>

            {{-- Hanya tampilkan error umum di sini --}}
            @if ($errors->has('login'))
                <div class="alert alert-danger">
                    {{ $errors->first('login') }}
                </div>
            @endif

            <div class="bg-white p-4 shadow rounded">
                <form method="POST" action="{{ route('login') }}" id="loginForm" novalidate>
                    @csrf

                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input id="username" name="username" type="text" required class="input-style @error('username') is-invalid @enderror"
                               value="{{ old('username') }}" placeholder="Masukkan username">
                        @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div id="usernameRequired" class="validation-message">Username wajib diisi.</div>
                        <div id="usernameMinLength" class="validation-message">Username minimal 4 karakter.</div>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="position-relative">
                            <input id="password" name="password" type="password" required class="input-style pe-5 @error('password') is-invalid @enderror"
                                   placeholder="Masukkan Password">
                            <span class="position-absolute top-50 end-0 translate-middle-y me-3 toggle-password"
                                  onclick="togglePassword()" style="cursor: pointer;">
                                <i class="bi bi-eye"></i>
                            </span>
                        </div>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div id="passwordRequired" class="validation-message">Password wajib diisi.</div>
                        <div id="passwordMinLength" class="validation-message">Password minimal 8 karakter.</div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember">
                            <label class="form-check-label" for="remember">Remember me</label>
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-green py-2">Sign in</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Toggle Password & Validasi --}}
    <script>
        function togglePassword() {
            const passwordField = document.getElementById('password');
            const toggleIcon = document.querySelector('.toggle-password i');

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.classList.remove('bi-eye');
                toggleIcon.classList.add('bi-eye-slash');
            } else {
                passwordField.type = 'password';
                toggleIcon.classList.remove('bi-eye-slash');
                toggleIcon.classList.add('bi-eye');
            }
        }

        // Variabel untuk melacak apakah form sudah pernah disubmit
        let formSubmitted = false;

        document.addEventListener('DOMContentLoaded', function() {
            const usernameField = document.getElementById('username');
            const passwordField = document.getElementById('password');
            const usernameRequired = document.getElementById('usernameRequired');
            const usernameMinLength = document.getElementById('usernameMinLength');
            const passwordRequired = document.getElementById('passwordRequired');
            const passwordMinLength = document.getElementById('passwordMinLength');
            const loginForm = document.getElementById('loginForm');

            // Validasi saat form disubmit
            loginForm.addEventListener('submit', function(event) {
                formSubmitted = true;
                let isValid = true;

                if (!validateUsername()) isValid = false;
                if (!validatePassword()) isValid = false;

                if (!isValid) {
                    event.preventDefault();
                    event.stopPropagation();
                }
            });

            // Validasi saat kehilangan fokus (hanya jika form sudah pernah disubmit)
            usernameField.addEventListener('blur', function() {
                if (formSubmitted) {
                    validateUsername();
                }
            });

            passwordField.addEventListener('blur', function() {
                if (formSubmitted) {
                    validatePassword();
                }
            });

            // Sembunyikan error saat user mulai mengetik
            usernameField.addEventListener('input', function() {
                if (formSubmitted) {
                    validateUsername();
                } else {
                    hideValidationMessages();
                }
            });

            passwordField.addEventListener('input', function() {
                if (formSubmitted) {
                    validatePassword();
                } else {
                    hideValidationMessages();
                }
            });

            function validateUsername() {
                const value = usernameField.value.trim();
                let isValid = true;

                // Sembunyikan semua pesan error
                usernameRequired.style.display = 'none';
                usernameMinLength.style.display = 'none';
                usernameField.classList.remove('is-invalid');

                // Validasi required
                if (value === '') {
                    usernameRequired.style.display = 'block';
                    usernameField.classList.add('is-invalid');
                    isValid = false;
                }
                // Validasi min length
                else if (value.length < 4) {
                    usernameMinLength.style.display = 'block';
                    usernameField.classList.add('is-invalid');
                    isValid = false;
                }

                return isValid;
            }

            function validatePassword() {
                const value = passwordField.value.trim();
                let isValid = true;

                // Sembunyikan semua pesan error
                passwordRequired.style.display = 'none';
                passwordMinLength.style.display = 'none';
                passwordField.classList.remove('is-invalid');

                // Validasi required
                if (value === '') {
                    passwordRequired.style.display = 'block';
                    passwordField.classList.add('is-invalid');
                    isValid = false;
                }
                // Validasi min length
                else if (value.length < 8) {
                    passwordMinLength.style.display = 'block';
                    passwordField.classList.add('is-invalid');
                    isValid = false;
                }

                return isValid;
            }

            function hideValidationMessages() {
                // Sembunyikan semua pesan validasi
                usernameRequired.style.display = 'none';
                usernameMinLength.style.display = 'none';
                passwordRequired.style.display = 'none';
                passwordMinLength.style.display = 'none';
                usernameField.classList.remove('is-invalid');
                passwordField.classList.remove('is-invalid');
            }
        });
    </script>
</body>
</html>