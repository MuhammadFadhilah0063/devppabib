<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>KOMPETENSI PPA</title>

    <!-- Favicons -->
    <link href="{{ url('/images/icon/ICONPPA.png') }}" rel="icon">

    {{-- CSRF --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/quill/quill.snow.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/quill/quill.bubble.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/DataTables/datatables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/select2/dist/css/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/vendor/select2-bootstrap/dist/select2-bootstrap.min.css') }}" rel="stylesheet" />

    <!-- Template Main CSS File -->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
</head>

<body>

    <!-- ======= Header ======= -->
    <header id="header" class="header fixed-top d-flex align-items-center">

        <div class="d-flex align-items-center justify-content-between">
            <a href="index.html" class="logo d-flex align-items-center">
                <img src="{{ url('/images/icon/ICONPPA.png') }}" alt="">
                <span class="d-none d-lg-block">PPA</span>
            </a>
            <i class="bi bi-list toggle-sidebar-btn"></i>
        </div><!-- End Logo -->

        <nav class="header-nav ms-auto">
            <ul class="d-flex align-items-center">

                <li class="nav-item dropdown pe-3">

                    <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#"
                        data-bs-toggle="dropdown">
                        <img src="{{ url('storage/foto_profil/' . Auth::user()->foto) }}" alt="Profile"
                            class="rounded-circle">
                        <span class="d-none d-md-block dropdown-toggle ps-2">{{ Auth::user()->nama }}</span>
                    </a><!-- End Profile Iamge Icon -->

                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                        <li class="dropdown-header">
                            <h6>{{ Auth::user()->nama }}</h6>
                            <span>{{ Auth::user()->level }}</span>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="{{ url('profile') }}">
                                <i class="bi bi-person"></i>
                                <span>Profil Saya</span>
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="{{ url('logout') }}">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Keluar</span>
                            </a>
                        </li>

                    </ul><!-- End Profile Dropdown Items -->
                </li><!-- End Profile Nav -->

            </ul>
        </nav><!-- End Icons Navigation -->

    </header>
    <!-- End Header -->

    <!-- ======= Sidebar ======= -->
    <aside id="sidebar" class="sidebar">

        <ul class="sidebar-nav" id="sidebar-nav">

            <li class="nav-item">
                <a class="nav-link {{ Request::is('/') ? '' : 'collapsed' }}" href="{{ url('/') }}">
                    <i class="bi bi-grid"></i>
                    <span>Dashboard</span>
                </a>
            </li><!-- End Dashboard Nav -->

            <li class="nav-item">
                <a class="nav-link {{ Request::is('sertifikasi*') || Request::is('mutasi_sertifikasi*') || Request::is('ikatan_dinas*') ? '' : 'collapsed' }}"
                    data-bs-target="#sertifikasi-nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-menu-button-wide"></i><span>Input Sertifikasi</span><i
                        class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="sertifikasi-nav"
                    class="nav-content {{ Request::is('sertifikasi*') || Request::is('mutasi_sertifikasi*') || Request::is('ikatan_dinas*') ? '' : 'collapse' }}"
                    data-bs-parent="#sidebar-nav">
                    @if (Auth::user()->level == 'admin')
                        @foreach ($jobsites as $jobsite)
                            <li>
                                <a href="{{ url('sertifikasi/' . $jobsite->nama_jobsite) }}"
                                    class="{{ Request::is('sertifikasi' . '/' . $jobsite->nama_jobsite . '*') ? 'active' : '' }}">
                                    <i class="bi bi-circle"></i><span>Data Sertifikasi
                                        {{ $jobsite->nama_jobsite }}</span>
                                </a>
                            </li>
                        @endforeach
                    @else
                        <li>
                            <a href="{{ url('sertifikasi/' . Auth::user()->jobsite) }}"
                                class="{{ Request::is('sertifikasi' . '/' . Auth::user()->jobsite . '*') ? 'active' : '' }}">
                                <i class="bi bi-circle"></i><span>Data Sertifikasi
                                    {{ Auth::user()->jobsite }}</span>
                            </a>
                        </li>
                    @endif

                    <li>
                        <a href="{{ url('mutasi_sertifikasi') }}"
                            class="{{ Request::is('mutasi_sertifikasi*') ? 'active' : '' }}">
                            <i class="bi bi-circle"></i><span>Mutasi Sertifikasi</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ url('ikatan_dinas') }}"
                            class="{{ Request::is('ikatan_dinas*') ? 'active' : '' }}">
                            <i class="bi bi-circle"></i><span>Ikatan Dinas</span>
                        </a>
                    </li>
                </ul>
            </li><!-- End Components Nav -->

            <li class="nav-item">
                <a class="nav-link {{ Request::is('karyawan/*') || Request::is('mutasi_karyawan*') || Request::is('approve*') ? '' : 'collapsed' }}"
                    data-bs-target="#karyawan-nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-journal-text"></i><span>Data Karyawan</span><i
                        class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="karyawan-nav"
                    class="nav-content {{ Request::is('karyawan/*') || Request::is('mutasi_karyawan*') || Request::is('approve*') ? '' : 'collapse' }}"
                    data-bs-parent="#sidebar-nav">
                    @if (Auth::user()->level == 'admin')
                        @foreach ($jobsites as $jobsite)
                            <li>
                                <a href="{{ url('karyawan/' . $jobsite->nama_jobsite) }}"
                                    class="{{ Request::is('karyawan' . '/' . $jobsite->nama_jobsite . '*') ? 'active' : '' }}">
                                    <i class="bi bi-circle"></i><span>Data Karyawan
                                        {{ $jobsite->nama_jobsite }}</span>
                                </a>
                            </li>
                        @endforeach
                    @else
                        <li>
                            <a href="{{ url('karyawan/' . Auth::user()->jobsite) }}"
                                class="{{ Request::is('karyawan' . '/' . Auth::user()->jobsite . '*') ? 'active' : '' }}">
                                <i class="bi bi-circle"></i><span>Data Karyawan
                                    {{ Auth::user()->jobsite }}</span>
                            </a>
                        </li>
                    @endif

                    <li>
                        <a href="{{ url('mutasi_karyawan') }}"
                            class="{{ Request::is('mutasi_karyawan*') ? 'active' : '' }}">
                            <i class="bi bi-circle"></i><span>Mutasi Karyawan</span>
                        </a>
                    </li>

                    @if (Auth::user()->level == 'admin')
                        <li>
                            <a href="{{ url('approve/mutasi_karyawan') }}"
                                class="{{ Request::is('approve/mutasi_karyawan*') ? 'active' : '' }}">
                                <i class="bi bi-circle"></i><span>Permintaan Mutasi Karyawan</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </li><!-- End Forms Nav -->

            <li class="nav-item">
                <a class="nav-link {{ Request::is('expired*') ? '' : 'collapsed' }}" data-bs-target="#exp-nav"
                    data-bs-toggle="collapse" href="#">
                    <i class="bi bi-layout-text-window-reverse"></i><span>Sertifikasi Expired</span><i
                        class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="exp-nav" class="nav-content {{ Request::is('expired*') ? '' : 'collapse' }}"
                    data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="{{ url('expired/sertifikasi') }}"
                            class="{{ Request::is('expired*') ? 'active' : '' }}">
                            <i class="bi bi-circle"></i><span>Monitoring Expired</span>
                        </a>
                    </li>
                </ul>
            </li><!-- End Tables Nav -->

            @if (Auth::user()->level == 'admin')
                <li class="nav-heading">Master Data</li>

                <li class="nav-item">
                    <a class="nav-link {{ Request::is('komp*') ? '' : 'collapsed' }}" href="{{ url('/komp') }}">
                        <i class="bi bi-menu-button-wide"></i>
                        <span>Data Kompetensi</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('jobsite*') ? '' : 'collapsed' }}"
                        href="{{ url('/jobsite') }}">
                        <i class="bi bi-menu-button-wide"></i>
                        <span>Data Jobsite</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('user*') ? '' : 'collapsed' }}" href="{{ url('/user') }}">
                        <i class="bi bi-person"></i>
                        <span>Data User</span>
                    </a>
                </li>
            @endif

            <li class="nav-heading">Pages</li>

            <li class="nav-item">
                <a class="nav-link {{ Request::is('profile*') ? '' : 'collapsed' }}" href="{{ url('profile') }}">
                    <i class="bi bi-person"></i>
                    <span>Profil Saya</span>
                </a>
            </li><!-- End Profile Page Nav -->

            <li class="nav-item">
                <a class="nav-link collapsed" href="{{ url('/logout') }}">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Keluar</span>
                </a>
            </li><!-- End Logout Page Nav -->
        </ul>

    </aside>
    <!-- End Sidebar-->

    <!-- ======= Main ======= -->
    <main id="main" class="main">

        <!-- ======= Page Title ======= -->
        <div class="row">
            <div class="col-9">
                <div class="pagetitle">
                    <h1>{{ $pageTitle }}</h1>
                    <nav>
                        <ol class="breadcrumb">
                            @yield('breadcrumb')
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-3 d-flex align-items-center justify-content-end">
                @stack('button')
            </div>
        </div>
        <!-- End Page Title -->

        <section class="section dashboard">
            <div class="row">
                <!-- ======= Content ======= -->
                @yield('content')
                <!-- End Content -->
            </div>
        </section>
    </main>
    <!-- End Main -->

    <!-- ======= Footer ======= -->
    <footer id="footer" class="footer">
        <div class="copyright">
            &copy; Copyright <strong><span>PPA {{ date('Y') }}</span></strong>
        </div>
    </footer>
    <!-- End Footer -->

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/quill/quill.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/jQuery/jquery-3.7.0.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/DataTables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/DataTables/DataTables-1.13.8/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/php-email-form/validate.js') }}"></script>
    <script src="{{ asset('assets/vendor/select2/dist/js/select2.min.js') }}"></script>

    <!-- Template Main JS File -->
    <script src="{{ asset('assets/js/main.js') }}"></script>

    <!-- DataTables and Select2 -->
    <script>
        $(document).ready(() => {
            $('#table-data').DataTable({});
            $('.select2').select2({
                theme: 'bootstrap',
            });
        });
    </script>

    @stack('scripts')
</body>

</html>
