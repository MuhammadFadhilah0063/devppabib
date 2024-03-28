<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>LOGIN ADMIN KOMPETENSI PPA</title>

    <!-- Favicons -->
    <link href="{{ url('/images/icon/ICONPPA.png') }}" rel="icon">

    {{-- CSRF --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/quill/quill.snow.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/quill/quill.bubble.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/remixicon/remixicon.css') }}" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
</head>

<body>

    <main>
        <div class="container">

            <section
                class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

                            <div class="d-flex justify-content-center py-4">
                                <a href="index.html" class="logo d-flex align-items-center w-auto">
                                    <img src="{{ url('/images/icon/ICONPPA.png') }}" alt="">
                                    <span class="d-none d-lg-block">KOMPETENSI PPA</span>
                                </a>
                            </div><!-- End Logo -->

                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="pt-4 pb-2">
                                        <h5 class="card-title text-center pb-0 pt-0 fs-5">Masuk Ke Akun Admin</h5>
                                    </div>

                                    <form class="row g-3 needs-validation" method="POST"
                                        action="{{ url('login/admin') }}" novalidate>
                                        @csrf
                                        <div class="col-12">
                                            <label for="nrp" class="form-label">NRP</label>
                                            <div class="input-group has-validation">
                                                <input type="text" name="nrp" class="form-control" id="nrp"
                                                    required>
                                                <div class="invalid-feedback">Tolong isi NRP.</div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <label for="password" class="form-label">Password</label>
                                            <input type="password" name="password" class="form-control" id="password"
                                                required>
                                            <div class="invalid-feedback">Tolong isi password.</div>
                                        </div>

                                        <div class="col-12 mt-4 text-center">
                                            <button class="btn btn-primary w-100 fw-bold" type="submit">Masuk</button>
                                            <small class="d-block pt-2">
                                                <a href="/login">Masuk sebagai user</a>
                                            </small>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            {{-- Alert --}}
                            @if (session()->has('gagal'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="bi bi-exclamation-octagon me-1"></i>
                                    {{ session('gagal') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif
                            {{-- Alert End --}}
                        </div>
                    </div>
                </div>

            </section>

        </div>
    </main>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/quill/quill.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/jQuery/jquery-3.7.0.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/php-email-form/validate.js') }}"></script>

    <!-- Template Main JS File -->
    <script src="{{ asset('assets/js/main.js') }}"></script>

</body>

</html>
