@extends('layouts/dashboard', ['pageTitle' => 'Dashboard'])

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
    <div class="col">
        <div class="row">

            {{-- Alert --}}
            @if (session()->has('login'))
                <div class="col-12">
                    <div class="alert alert-primary alert-dismissible fade show" role="alert">
                        <i class="bi bi-star me-1"></i>
                        Selamat datang, {{ Auth::user()->nama }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            @endif
            {{-- Alert End --}}

            {{-- Table sertifkasi mau expired --}}
            @if ($sertifikasi3BulanExpired->count() != 0)
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 text-center">
                                    <h5 class="card-title">Sertifikasi dengan masa berlaku sisa 3 bulan atau kurang</h5>
                                </div>
                            </div>

                            <!-- Table with stripped rows -->
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-bordered" id="table-data"
                                    width="100%">
                                    <thead class="table-primary">
                                        <tr class="align-middle">
                                            <th class="text-center">No.</th>
                                            <th class="text-center text-nowrap">NRP</th>
                                            <th class="text-center text-nowrap">Nama Karyawan</th>
                                            <th class="text-center text-nowrap">Nama Kompetensi</th>
                                            <th class="text-center text-nowrap">Kode</th>
                                            <th class="text-center text-nowrap">Jobsite</th>
                                            <th class="text-center text-nowrap">Sisa Hari Berlaku</th>
                                            <th class="text-center text-nowrap">Tanggal Expired</th>
                                            <th class="text-center text-nowrap">Sertifikat</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($sertifikasi3BulanExpired as $sertifikasi)
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td class="text-center text-nowrap">{{ $sertifikasi->nrp }}</td>
                                                <td class="text-center text-nowrap">{{ $sertifikasi->nama_karyawan }}
                                                </td>
                                                <td class="text-center text-nowrap">{{ $sertifikasi->nama_kompetensi }}
                                                </td>
                                                <td class="text-center text-nowrap">{{ $sertifikasi->kode }}</td>
                                                <td class="text-center text-nowrap">{{ $sertifikasi->jobsite }}</td>
                                                <td class="text-center">
                                                    <span
                                                        class="
                                                @if (selisihHari($sertifikasi->tgl_exp) == 'Expired Hari Ini') btn fw-bold btn-sm btn-warning d-inline
                                                @elseif (selisihHari($sertifikasi->tgl_exp) == 'Expired')
                                                btn fw-bold btn-sm btn-danger d-inline
                                                @else
                                                btn fw-bold btn-sm btn-secondary d-inline @endif
                                                ">
                                                        {{ selisihHari($sertifikasi->tgl_exp) }}
                                                    </span>
                                                </td>
                                                <td class="text-center text-nowrap">
                                                    {{ formatDate($sertifikasi->tgl_exp) }}
                                                </td>
                                                <td class="text-center text-nowrap">
                                                    @if ($sertifikasi->sertifikat)
                                                        <a href="{{ asset('storage/sertifikat/' . $sertifikasi->sertifikat) }}"
                                                            target="_blank" class="btn fw-bold btn-sm btn-info d-inline">
                                                            Lihat Sertifikat
                                                        </a>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- End Table with stripped rows -->

                            <div class="row">
                                <div class="col text-center pt-3">
                                    <a href="{{ url('sertifikasi3bulanexpired/export') }}"
                                        class="btn btn-sm btn-success fw-bold">
                                        Export Excel
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if (Auth::user()->level == 'admin')
                <!-- Karyawan Card -->
                <div class="col-12 col-md-6">

                    <div class="card info-card sales-card">

                        <div class="card-body">
                            <h5 class="card-title">Karyawan</h5>

                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-people"></i>
                                </div>
                                <div class="ps-3 ">
                                    <h6>{{ $karyawans }} Karyawan</h6>
                                    <table>
                                        @foreach ($jobsites as $jobsite)
                                            <tr>
                                                <td>
                                                    <span class="text-dark small pt-1 fw-bold">
                                                        {{ $jobsite->nama_jobsite }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="text-dark small ps-2 pt-1 fw-bold">
                                                        &nbsp;: {{ $jumlahKaryawanPerJobsite[$jobsite->nama_jobsite] }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="text-dark small pt-1 fw-bold">
                                                        &nbsp;Karyawan
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
                <!-- End Karyawan Card -->

                <!-- Sertifikasi Card -->
                <div class="col-12 col-md-6">

                    <div class="card info-card revenue-card">

                        <div class="card-body">
                            <h5 class="card-title">Sertifikasi</h5>

                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-file-earmark-post"></i>
                                </div>
                                <div class="ps-3 ">
                                    <h6>{{ $sertifikasis }} Sertifikasi</h6>
                                    <table>
                                        @foreach ($jobsites as $jobsite)
                                            <tr>
                                                <td>
                                                    <span class="text-dark small pt-1 fw-bold">
                                                        {{ $jobsite->nama_jobsite }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="text-dark small ps-2 pt-1 fw-bold">
                                                        &nbsp;: {{ $jumlahSertifikasiPerJobsite[$jobsite->nama_jobsite] }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="text-dark small pt-1 fw-bold">
                                                        &nbsp;Sertifikasi
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
                <!-- End Sertifikasi Card -->

                <!-- Karyawan Card -->
                <div class="col-12 col-md-6">

                    <div class="card info-card no-card">

                        <div class="card-body">
                            <h5 class="card-title">Karyawan Tidak Aktif</h5>

                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-people"></i>
                                </div>
                                <div class="ps-3 ">
                                    <h6>{{ $karyawanTidakAktif }} Karyawan</h6>
                                    <table>
                                        @foreach ($jobsites as $jobsite)
                                            <tr>
                                                <td>
                                                    <span class="text-dark small pt-1 fw-bold">
                                                        {{ $jobsite->nama_jobsite }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="text-dark small ps-2 pt-1 fw-bold">
                                                        &nbsp;:
                                                        {{ $jumlahKaryawanTidakAktifPerJobsite[$jobsite->nama_jobsite] }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="text-dark small pt-1 fw-bold">
                                                        &nbsp;Karyawan
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
                <!-- End Karyawan Card -->

                <!-- Sertifikasi Card -->
                <div class="col-12 col-md-6">

                    <div class="card info-card customers-card">

                        <div class="card-body">
                            <h5 class="card-title">Sertifikasi Expired</h5>

                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-file-earmark-post"></i>
                                </div>
                                <div class="ps-3 ">
                                    <h6>{{ $sertifikasiExpired }} Sertifikasi Expired</h6>
                                    <table>
                                        @foreach ($jobsites as $jobsite)
                                            <tr>
                                                <td>
                                                    <span class="text-dark small pt-1 fw-bold">
                                                        {{ $jobsite->nama_jobsite }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="text-dark small ps-2 pt-1 fw-bold">
                                                        &nbsp;:
                                                        {{ $jumlahSertifikasiExpiredPerJobsite[$jobsite->nama_jobsite] }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="text-dark small pt-1 fw-bold">
                                                        &nbsp;Sertifikasi
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
                <!-- End Sertifikasi Card -->
            @else
                <!-- Karyawan Card -->
                <div class="col-12 col-md-6">
                    <div class="card info-card sales-card">
                        <div class="card-body">
                            <h5 class="card-title">Karyawan {{ Auth::user()->jobsite }}</h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-people"></i>
                                </div>
                                <div class="ps-3 ">
                                    <h6>{{ $jumlahKaryawanPerJobsite[Auth::user()->jobsite] }} Karyawan</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Karyawan Card -->

                <!-- Sertifikasi Card -->
                <div class="col-12 col-md-6">
                    <div class="card info-card revenue-card">
                        <div class="card-body">
                            <h5 class="card-title">Sertifikasi {{ Auth::user()->jobsite }}</h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-file-earmark-post"></i>
                                </div>
                                <div class="ps-3 ">
                                    <h6>{{ $jumlahSertifikasiPerJobsite[Auth::user()->jobsite] }} Sertifikasi</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Sertifikasi Card -->

                <!-- Karyawan Card -->
                <div class="col-12 col-md-6">
                    <div class="card info-card no-card">
                        <div class="card-body">
                            <h5 class="card-title">Karyawan {{ Auth::user()->jobsite }} Tidak Aktif</h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-people"></i>
                                </div>
                                <div class="ps-3 ">
                                    <h6>{{ $jumlahKaryawanTidakAktifPerJobsite[Auth::user()->jobsite] }} Karyawan</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Karyawan Card -->

                <!-- Sertifikasi Card -->
                <div class="col-12 col-md-6">
                    <div class="card info-card customers-card">
                        <div class="card-body">
                            <h5 class="card-title">Sertifikasi {{ Auth::user()->jobsite }} Expired</h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-file-earmark-post"></i>
                                </div>
                                <div class="ps-3 ">
                                    <h6>{{ $jumlahSertifikasiExpiredPerJobsite[Auth::user()->jobsite] }} Sertifikasi
                                        Expired</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Sertifikasi Card -->
            @endif
        </div>
    </div>
@endsection

{{-- Test --}}
