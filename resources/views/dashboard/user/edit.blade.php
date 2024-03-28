@extends('layouts/dashboard', ['pageTitle' => 'Edit User'])

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/user') }}">User</a></li>
    <li class="breadcrumb-item active">Edit</a></li>
@endsection

@push('button')
    <a href="{{ url('/user') }}" class="btn btn-sm btn-success fw-bold">
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
                <form class="row g-3 pt-3" method="POST" action="{{ url("user/$user->id") }}"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" value="{{ $user->id }}">
                    <div class="col">
                        <div class="row">
                            <div class="col-12">
                                <label class="form-label" for="nrp">NRP</label>
                                <input type="text" class="form-control" id="nrp" name="nrp" placeholder="nrp"
                                    required value="{{ $user->nrp }}">
                            </div>
                            <div class="col-12">
                                <label for="nama" class="form-label pt-2">Nama</label>
                                <input type="text" class="form-control" id="nama" name="nama" placeholder="nama"
                                    required value="{{ $user->nama }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label pt-2" for="jobsite">Jobsite</label>
                                <select class="form-control select2" id="jobsite" name="jobsite">
                                    @foreach ($jobsites as $jobsite)
                                        <option value="{{ $jobsite->nama_jobsite }}"
                                            {{ $jobsite->nama_jobsite == $user->jobsite ? 'selected' : '' }}>
                                            {{ $jobsite->nama_jobsite }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12">
                                <hr class="mb-2 mt-4">
                                <label class="form-label pt-2" for="password_lama">Password Lama</label>
                                <input type="password" class="form-control" id="password_lama" name="password_lama"
                                    placeholder="password lama">
                                <small class="fst-italic">Isi bagian ini, jika ingin mengubah password</small>
                            </div>
                            <div class="col-12">
                                <label class="form-label pt-2" for="password_baru">Password Baru</label>
                                <input type="password" class="form-control" id="password_baru" name="password_baru"
                                    placeholder="password baru">
                                <small class="fst-italic">Isi bagian ini, jika ingin mengubah password.</small>
                            </div>
                        </div>
                    </div>

                    {{-- Foto --}}
                    <div class="col">
                        <div class="row">
                            <div class="col-12">
                                <label for="foto" class="form-label">Foto Profil</label>
                                <input class="form-control" type="file" id="foto" name="foto" accept="image/*"
                                    onchange="previewImage(this);">
                                <small class="fst-italic">Pilih foto profil, jika ingin mengubah.</small>
                            </div>

                            <div class="col-12 text-center pt-3">
                                <img id="imagePreview" src="{{ asset('storage/foto_profil/' . $user->foto) }}"
                                    alt="Preview" class="img-fluid img-thumbnail"
                                    style="max-width: 100%; max-height: 300px; margin: 0 auto;">
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn fw-bold btn-warning">Edit</button>
                    </div>
                </form>
                <!-- End floating Labels Form -->
            </div>
        </div>

    </div>

    <script>
        function previewImage(input) {
            var preview = document.getElementById('imagePreview');
            var file = input.files[0];
            var reader = new FileReader();

            reader.onloadend = function() {
                preview.src = reader.result;
                preview.style.display = 'block';
            };

            if (file) {
                reader.readAsDataURL(file);
            } else {
                preview.src = '#';
                preview.style.display = 'none';
            }
        }
    </script>
@endsection
