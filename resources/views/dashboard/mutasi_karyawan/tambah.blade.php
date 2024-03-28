@extends('layouts/dashboard', ['pageTitle' => 'Tambah Mutasi Karyawan'])

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('mutasi_karyawan') }}">Mutasi Karyawan</a></li>
    <li class="breadcrumb-item active">Tambah</a></li>
@endsection

@push('button')
    <a href="{{ url('/mutasi_karyawan') }}" class="btn btn-sm btn-success fw-bold">
        Kembali
    </a>
@endpush

@section('content')
    <div class="col-lg-12">
        {{-- Alert --}}
        @if (session()->has('gagal'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-octagon me-1"></i>
                {{ session('gagal') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        {{-- Alert End --}}

        <div class="card">
            <div class="card-body">

                <!-- Floating Labels Form -->
                <form class="row g-3 pt-3" method="POST" action="{{ url('mutasi_karyawan') }}">
                    @csrf
                    <div class="col-12">
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label for="nrp" class="form-label">NRP</label>
                                <select class="form-control select2" height="200px" id="nrp" name="nrp" required>
                                    <option value="">NRP</option>
                                    @foreach ($karyawans as $karyawan)
                                        <option value="{{ $karyawan->nrp }}">{{ $karyawan->nrp }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="jobsite_tujuan" class="form-label pt-2 pt-md-0">Jobsite Tujuan</label>
                                <select class="form-control select2" name="jobsite_tujuan" id="jobsite_tujuan">
                                    @foreach ($jobsites as $jobsite)
                                        <option value="{{ $jobsite->nama_jobsite }}">{{ $jobsite->nama_jobsite }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="nama" class="form-label pt-2">Nama</label>
                                <input type="text" class="form-control" id="nama" name="nama" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="jobsite_lama" class="form-label pt-2">Jobsite Lama</label>
                                <input type="text" class="form-control" id="jobsite_lama" name="jobsite_lama" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="tgl_mutasi" class="form-label pt-2">Tanggal Mutasi</label>
                                <input type="date" class="form-control" id="tgl_mutasi" name="tgl_mutasi" required
                                    value="{{ now()->format('Y-m-d') }}">
                            </div>

                            <input type="hidden" name="pic_input" value="{{ Auth::user()->id }}">
                        </div>
                    </div>

                    <div class="text-center pt-2">
                        <button type="submit" class="btn fw-bold btn-primary">Tambah</button>
                        <button type="reset" class="btn fw-bold btn-danger">Hapus</button>
                    </div>
                </form>
                <!-- End floating Labels Form -->
            </div>
        </div>

    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#nrp').change(function() {
                    var nrp = $(this).val();

                    var csrfToken = $('meta[name="csrf-token"]').attr('content');

                    // Mendapatkan URL saat ini
                    var currentURL = window.location.href;

                    // Kata kunci yang ingin Anda periksa
                    var keywordToCheck = "public";

                    // Memeriksa apakah kata kunci ada dalam URL
                    if (currentURL.includes(keywordToCheck)) {
                        var url = "/devppabib/public/get-karyawan/" + nrp;
                    } else {
                        var url = "/get-karyawan/" + nrp;
                    }

                    $.ajax({
                        url: url,
                        type: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        },
                        success: function(data) {
                            $('#jobsite_lama').val(data.jobsite);
                            $('#nama').val(data.nama);
                        },
                        error: function() {
                            console.error('Gagal mengambil data jobsite.');
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection
