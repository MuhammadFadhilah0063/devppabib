@extends('layouts/dashboard', ['pageTitle' => 'Tambah Kompetensi'])

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/komp') }}">Kompetensi</a></li>
    <li class="breadcrumb-item active">Tambah</a></li>
@endsection

@push('button')
    <a href="{{ url('/komp') }}" class="btn btn-sm btn-success fw-bold">
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
                <form class="row g-3 pt-3" method="POST" action="{{ route('storeKomp') }}">
                    @csrf
                    <div class="col-md-6">
                        <label for="nama" class="form-label">Nama</label>
                        <input value="{{ old('nama') }}" type="text" class="form-control" id="nama" name="nama"
                            placeholder="nama" required>
                    </div>
                    <div class="col-md-6">
                        <label for="kode" class="form-label">Kode</label>
                        <input value="{{ old('kode') }}" type="text" class="form-control" id="kode" name="kode"
                            placeholder="kode" required>
                    </div>
                    <div class="col-md-6">
                        <label for="jenis" class="form-label">Jenis</label>
                        <input value="{{ old('jenis') }}" type="text" class="form-control" id="jenis" name="jenis"
                            placeholder="jenis" required>
                    </div>
                    <div class="col-md-6">
                        <label for="sertifikasi" class="form-label">Sertifikasi</label>
                        <input value="{{ old('sertifikasi') }}" type="text" class="form-control" id="sertifikasi"
                            name="sertifikasi" placeholder="sertifikasi" required>
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
