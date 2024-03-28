@extends('layouts/dashboard', ['pageTitle' => 'Edit Mutasi Karyawan'])

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('mutasi_karyawan') }}">Mutasi Karyawan</a></li>
    <li class="breadcrumb-item active">Edit</a></li>
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
                <form class="row g-3 pt-3" method="POST" action="{{ url("mutasi_karyawan/$mutasiKaryawan->id") }}">
                    @csrf
                    @method('PUT')
                    <div class="col-12">
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label for="nrp" class="form-label">NRP</label>
                                <input type="text" class="form-control" id="nrp" name="nrp"
                                    value="{{ $mutasiKaryawan->nrp }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="jobsite_lama" class="form-label pt-2 pt-md-0">Jobsite Lama</label>
                                <input type="text" class="form-control" id="jobsite_lama" name="jobsite_lama" readonly
                                    value="{{ $mutasiKaryawan->jobsite_tujuan }}">
                            </div>
                            <div class="col-md-6">
                                <label for="nama" class="form-label pt-2">Nama</label>
                                <input type="text" class="form-control" id="nama" name="nama" readonly
                                    value="{{ $mutasiKaryawan->nama }}">
                            </div>
                            <div class="col-md-6">
                                <label for="jobsite_tujuan" class="form-label pt-2">Jobsite Tujuan</label>
                                <select class="form-control select2" name="jobsite_tujuan" id="jobsite_tujuan">
                                    @foreach ($jobsites as $jobsite)
                                        <option value="{{ $jobsite->nama_jobsite }}">{{ $jobsite->nama_jobsite }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="tgl_mutasi" class="form-label pt-2">Tanggal Mutasi</label>
                                <input type="date" class="form-control" id="tgl_mutasi" name="tgl_mutasi" required
                                    value="{{ $mutasiKaryawan->tgl_mutasi }}">
                            </div>

                            <input type="hidden" name="pic_input" value="{{ Auth::user()->id }}">
                        </div>
                    </div>

                    <div class="text-center pt-2">
                        <button type="submit" class="btn fw-bold btn-warning">Edit</button>
                    </div>
                </form>
                <!-- End floating Labels Form -->
            </div>
        </div>

    </div>
@endsection
