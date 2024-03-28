@extends('layouts/dashboard', ['pageTitle' => 'Edit Jobsite'])

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/jobsite') }}">Jobsite</a></li>
    <li class="breadcrumb-item active">Edit</a></li>
@endsection

@push('button')
    <a href="{{ url('/jobsite') }}" class="btn btn-sm btn-success fw-bold">
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
                <form class="row g-3 pt-3" method="POST" action="{{ url("jobsite/$jobsite->id") }}">
                    @csrf
                    @method('PUT')
                    <div class="col-12">
                        <label for="nama" class="form-label">Jobsite</label>
                        <input type="text" class="form-control" id="nama" name="nama_jobsite" placeholder="Jobsite"
                            required value="{{ $jobsite->nama_jobsite }}">
                    </div>
                    <div class="col-12">
                        <label for="alamat" class="form-label">Alamat</label>
                        <textarea class="form-control" placeholder="Address" id="alamat" name="alamat" style="height: 100px;" required>{{ $jobsite->alamat }}</textarea>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn fw-bold btn-warning">Edit</button>
                    </div>
                </form>
                <!-- End floating Labels Form -->
            </div>
        </div>

    </div>
@endsection
