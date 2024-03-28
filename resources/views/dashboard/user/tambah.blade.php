@extends('layouts/dashboard', ['pageTitle' => 'Tambah User'])

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/user') }}">User</a></li>
    <li class="breadcrumb-item active">Tambah</a></li>
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
                <form class="row g-3 pt-3" method="POST" action="{{ url('user') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="col">
                        <div class="row">
                            <div class="col-12">
                                <label class="form-label" for="nrp">NRP</label>
                                <select class="form-control select2" id="nrp" name="nrp" required>
                                    <option value="">NRP</option>
                                    @foreach ($karyawans as $karyawan)
                                        <option value="{{ $karyawan->nrp }}">{{ $karyawan->nrp }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12">
                                <label for="nama" class="form-label pt-2">Nama</label>
                                <input type="text" class="form-control" id="nama" name="nama" placeholder="nama"
                                    required readonly>
                            </div>
                            <div class="col-12">
                                <label class="form-label pt-2" for="jobsite">Jobsite</label>
                                <input type="text" class="form-control" id="jobsite" name="jobsite"
                                    placeholder="jobsite" required readonly>
                            </div>
                            <div class="col-12">
                                <label class="form-label pt-2" for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="password" required>
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
                                <small class="fst-italic">
                                    Pilih file foto, jika ingin upload foto sendiri.
                                </small>
                            </div>

                            <div class="col-12 text-center pt-3">
                                <img id="imagePreview" src="#" alt="Preview" class="img-fluid img-thumbnail"
                                    style="display:none; max-width: 100%; max-height: 300px; margin: 0 auto;">
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn fw-bold btn-primary">Tambah</button>
                        <button type="reset" class="btn fw-bold btn-danger">Hapus</button>
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
                            $('#nama').val(data.nama);
                            $('#jobsite').val(data.jobsite);
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
