<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POSKasir</title>
    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Custom CSS --}}
    <style>
        body {
            background-color: #f9fafb;
        }

        .input-style {
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
            width: 100%;
        }

        .input-style:focus {
            border-color: #2E7D32;
            box-shadow: 0 0 0 0.2rem rgba(0, 130, 86, 0.25);
            outline: none;
        }

        .btn-green {
            background-color: #2E7D32;
            color: white;
        }

        .btn-green:hover {
            background-color: #999;
        }

        .text-green {
            color: #008256;
        }

        .text-green:hover {
            color: #999;
        }

        .form-check-input:checked {
            background-color: #2E7D32;
            /* Hijau */
            border-color: #2E7D32;
        }
    </style>
</head>

<body>
    <div class="container d-flex flex-column justify-content-center py-5 px-4 min-vh-100">
        <div class="mx-auto" style="max-width: 400px;">
            <div class="text-center">
               <div class="app-logo">
                <!-- logo-->
                 <img src="{{ asset('images/logo-poskasir.png') }}" alt="POSKasir Logo" height="40" >
            </div>
                <h2 class="fw-bold">Sign in to your account</h2>
            </div>

            {{-- Pesan Error Login --}}
            @if (session('errorLogin'))
                <div class="alert alert-danger mt-3">
                    {{ session('errorLogin') }}
                </div>
            @endif

            {{-- Validasi --}}
            @if ($errors->any())
                <div class="alert alert-danger mt-3">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white p-4 shadow rounded mt-4">
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input id="username" name="username" type="text" required class="input-style"
                            value="{{ old('username') }}" placeholder="Masukkan username">
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input id="password" name="password" type="password" required class="input-style"
                            placeholder="••••••••">
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember">
                            <label class="form-check-label" for="remember">
                                Remember me
                            </label>
                        </div>

                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-green py-2">
                            Sign in
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
