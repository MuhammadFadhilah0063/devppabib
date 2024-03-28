@extends('layouts/dashboard', ['pageTitle' => 'Tambah Karyawan ' . $namaJobsite])

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url("/karyawan/$jobsite") }}">Karyawan {{ $jobsite }}</a></li>
    <li class="breadcrumb-item active">Tambah</a></li>
@endsection

@push('button')
    <a href="{{ url("/karyawan/$jobsite") }}" class="btn btn-sm btn-success fw-bold">
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

        {{-- Error start --}}
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                @foreach ($errors->all() as $error)
                    <i class="bi bi-exclamation-octagon me-1"></i>
                    {{ $error }}
                    </br>
                @endforeach
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        {{-- Error end --}}

        <div class="card">
            <div class="card-body">

                <!-- Floating Labels Form -->
                <form class="row g-3 pt-3" method="POST" action="{{ url("/karyawan/$jobsite/create") }}">
                    @csrf
                    <div class="col-md-6">
                        <label for="nrp" class="form-label">NRP</label>
                        <input type="text" class="form-control" id="nrp" name="nrp" placeholder="nrp" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="posisi">Posisi</label>
                        <input type="text" class="form-control" id="posisi" name="posisi" placeholder="posisi"
                            required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="nama">Nama</label>
                        <input type="text" class="form-control" id="nama" name="nama" placeholder="nama"
                            required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="departemen">Departemen</label>
                        <input type="text" class="form-control" id="departemen" name="departemen"
                            placeholder="departemen" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label" for="nama_jobsite">Jobsite</label>
                        <input type="text" class="form-control" id="nama_jobsite" name="nama_jobsite"
                            placeholder="nama_jobsite" required readonly value="{{ $namaJobsite }}">
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
@endsection