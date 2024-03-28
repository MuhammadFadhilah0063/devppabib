@extends('layouts/dashboard', ['pageTitle' => 'Edit Sertifikasi Karyawan ' . $jobsite])

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url("sertifikasi/$jobsite") }}">Sertifikasi Karyawan {{ $jobsite }}</a></li>
    <li class="breadcrumb-item active">Edit</a></li>
@endsection

@push('button')
    <a href="{{ url("/sertifikasi/$jobsite") }}" class="btn btn-sm btn-success fw-bold">
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
                <form class="row g-3 pt-3" method="POST" action="{{ url("sertifikasi/$jobsite/$sertifikasi->id") }}"
                    enctype="multipart/form-data">
                    <div class="col-12">
                        <div class="row mt-2">
                            @csrf
                            @method('PUT')
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-12">
                                        <label for="nrp" class="form-label">NRP</label>
                                        <input class="form-control" id="nrp" name="nrp" placeholder="nrp" readonly
                                            value="{{ $sertifikasi->nrp }}">
                                    </div>
                                    <div class="col-12">
                                        <label for="nama_karyawan" class="form-label pt-2">Nama</label>
                                        <input type="text" class="form-control" id="nama_karyawan" name="nama_karyawan"
                                            placeholder="nama karyawan" readonly required
                                            value="{{ $sertifikasi->nama_karyawan }}">
                                    </div>
                                    <div class="col-12">
                                        <label for="nama_kompetensi" class="form-label pt-2">Kompetensi</label>
                                        <select class="form-control select2" name="nama_kompetensi" id="nama_kompetensi">
                                            @foreach ($kompetensis as $kompetensi)
                                                <option value="{{ $kompetensi->nama }}"
                                                    {{ $kompetensi->id == $sertifikasi->id_kompetensi ? 'selected' : '' }}>
                                                    {{ $kompetensi->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <label for="kode" class="form-label pt-2">Kode</label>
                                        <input type="text" class="form-control" id="kode" name="kode"
                                            placeholder="kode" required value="{{ $sertifikasi->kode }}" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="tgl_terbit" class="form-label pt-2">Tanggal Terbit</label>
                                        <input type="date" class="form-control" id="tgl_terbit" name="tgl_terbit"
                                            value="{{ $sertifikasi->tgl_terbit }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="tgl_exp" class="form-label pt-2">Tanggal Expired</label>
                                        <input type="date" class="form-control" id="tgl_exp" name="tgl_exp"
                                            value="{{ $sertifikasi->tgl_exp }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="harga" class="form-label pt-2">Harga</label>
                                        <input type="number" class="form-control" id="harga" name="harga"
                                            value="{{ $sertifikasi->harga }}">
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" name="pic_input" value="{{ Auth::user()->nama }}">

                            {{-- Sertifikat --}}
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-12">
                                        <label for="sertifikat" class="form-label pt-2 pt-md-0">Sertifikat</label>
                                        <input class="form-control" type="file" id="sertifikat" name="sertifikat"
                                            onchange="updatePreview(this);">
                                        <small class="fst-italic">
                                            Pilih file sertifikat, jika ingin merubah. File bisa foto atau pdf.
                                        </small>
                                    </div>

                                    @if (isset($extSertifikat))
                                        <div class="col-12 text-center">
                                            <!-- Pratinjau PDF -->
                                            <iframe class="pt-3"
                                                style="display: {{ $extSertifikat == 'pdf' ? 'block' : 'none' }}; margin: 0 auto;"
                                                id="pdfIframe" frameborder="0" height="400px" width="100%"
                                                src="{{ $extSertifikat == 'pdf' ? asset('storage/sertifikat/' . $sertifikasi->sertifikat) : '' }}"></iframe>
                                            <!-- Pratinjau gambar -->
                                            <div class="pt-3">
                                                <img src="{{ $extSertifikat == 'pdf' ? '#' : asset('storage/sertifikat/' . $sertifikasi->sertifikat) }}"
                                                    id="imagePreview" alt="{{ $sertifikasi->sertifikat }}"
                                                    class="img-fluid img-thumbnail"
                                                    style="display: {{ $extSertifikat == 'pdf' ? 'none' : 'block' }}; max-width: 100%; max-height: 400px; margin: 0 auto;">
                                            </div>
                                        </div>
                                    @else
                                        <!-- Pratinjau PDF -->
                                        <div class="col-12 text-center">
                                            <iframe class="pt-3" style="display: none; margin: 0 auto;" id="pdfIframe"
                                                frameborder="0" height="400px" width="100%"></iframe>
                                        </div>
                                        <!-- Pratinjau gambar -->
                                        <div class="col-12 text-center">
                                            <div class="pt-3">
                                                <img id="imagePreview" src="#" alt="Preview"
                                                    class="img-fluid img-thumbnail"
                                                    style="display: none; max-width: 100%; max-height: 400px; margin: 0 auto;">
                                            </div>
                                        </div>
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" class="btn fw-bold btn-warning">Edit</button>
                    </div>
                </form>
                <!-- End floating Labels Form -->
            </div>
        </div>

    </div>

    <script>
        function updatePreview(input) {
            var file = input.files[0];

            if (file) {
                if (file.type.startsWith('image/')) {
                    // Jika jenis file adalah gambar
                    updateImagePreview(file);
                } else if (file.type === 'application/pdf') {
                    // Jika jenis file adalah PDF
                    updatePdfPreview(file);
                } else {
                    // Jenis file tidak didukung
                    alert('Jenis file tidak didukung.');
                }
            }
        }

        function updateImagePreview(file) {
            var reader = new FileReader();

            reader.onload = function(e) {
                // Tampilkan pratinjau gambar
                document.getElementById('imagePreview').src = e.target.result;
                document.getElementById('imagePreview').style.display = 'block';

                // Sembunyikan pratinjau PDF
                document.getElementById('pdfIframe').style.display = 'none';
            };

            // Baca file sebagai URL data
            reader.readAsDataURL(file);
        }

        function updatePdfPreview(file) {
            // Dapatkan URL objek blob dari file PDF yang dipilih
            var blobUrl = URL.createObjectURL(file);

            // Atur sumber iframe ke URL objek blob
            document.getElementById('pdfIframe').src = blobUrl;

            // Tampilkan pratinjau PDF
            document.getElementById('pdfIframe').style.display = 'block';

            // Sembunyikan pratinjau gambar
            document.getElementById('imagePreview').style.display = 'none';
        }
    </script>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#nama_kompetensi').change(function() {
                    var nama_kompetensi = $(this).val();

                    var csrfToken = $('meta[name="csrf-token"]').attr('content');

                    // Mendapatkan URL saat ini
                    var currentURL = window.location.href;

                    // Kata kunci yang ingin Anda periksa
                    var keywordToCheck = "public";

                    // Memeriksa apakah kata kunci ada dalam URL
                    if (currentURL.includes(keywordToCheck)) {
                        var url = "/devppabib/public/get-kompetensi/" + nama_kompetensi;
                    } else {
                        var url = "/get-kompetensi/" + nama_kompetensi;
                    }

                    $.ajax({
                        url: url,
                        type: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        },
                        success: function(data) {
                            $('#kode').val(data.kode);
                        },
                        error: function() {
                            console.error('Gagal mengambil data kompetensi.');
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection
