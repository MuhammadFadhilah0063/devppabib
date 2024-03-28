@extends('layouts/dashboard', ['pageTitle' => 'Edit Kompetensi'])

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/komp') }}">Kompetensi</a></li>
    <li class="breadcrumb-item active">Edit</a></li>
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
                <form class="row g-3 pt-3" method="POST" action="{{ url("komp/$kompetensi->id") }}">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" value="{{ $kompetensi->id }}">
                    <div class="col-md-6">
                        <label class="form-label" for="nama">Nama</label>
                        <input type="text" class="form-control" id="nama" name="nama" placeholder="nama" required
                            value="{{ $kompetensi->nama }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="kode">Kode</label>
                        <input type="text" class="form-control" id="kode" name="kode" placeholder="kode" required
                            value="{{ $kompetensi->kode }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="jenis">Jenis</label>
                        <input type="text" class="form-control" id="sertifikasi" name="jenis" placeholder="jenis"
                            required value="{{ $kompetensi->jenis }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="sertifikasi">Sertifikasi</label>
                        <input value="{{ $kompetensi->sertifikasi }}" type="text" class="form-control" id="sertifikasi"
                            name="sertifikasi" placeholder="sertifikasi" required>
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
