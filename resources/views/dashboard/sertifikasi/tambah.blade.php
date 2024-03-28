@extends('layouts/dashboard', ['pageTitle' => 'Tambah Sertifikasi Karyawan ' . $jobsite])

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url("sertifikasi/$jobsite") }}">Sertifikasi Karyawan {{ $jobsite }}</a></li>
    <li class="breadcrumb-item active">Tambah</a></li>
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

                <form class="row g-3 pt-4" method="POST" action="{{ url("sertifikasi/$jobsite") }}"
                    enctype="multipart/form-data">
                    <div class="col-12">
                        <div class="row mt-2">
                            @csrf
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="nrp" class="form-label">NRP</label>
                                        <select class="form-control select2" id="nrp" name="nrp" required>
                                            <option value="">NRP</option>
                                            @foreach ($karyawans as $karyawan)
                                                <option value="{{ $karyawan->nrp }}">{{ $karyawan->nrp }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="nama_karyawan" class="form-label pt-2">Nama</label>
                                        <input type="text" class="form-control" id="nama_karyawan" name="nama_karyawan"
                                            placeholder="nama karyawan" readonly required>
                                    </div>
                                    <div class="col-12">
                                        <label for="nama_kompetensi" class="form-label pt-2">Kompetensi</label>
                                        <select class="form-control select2" name="nama_kompetensi" id="nama_kompetensi"
                                            required>
                                            <option value="">Kompetensi</option>
                                            @foreach ($kompetensis as $kompetensi)
                                                <option value="{{ $kompetensi->nama }}">{{ $kompetensi->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="kode" class="form-label pt-2">Kode</label>
                                        <input type="text" class="form-control" id="kode" name="kode"
                                            placeholder="kode" required readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="tgl_terbit" class="form-label pt-2">Tanggal Terbit</label>
                                        <input type="date" class="form-control" id="tgl_terbit" name="tgl_terbit">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="tgl_exp" class="form-label pt-2">Tanggal Expired</label>
                                        <input type="date" class="form-control" id="tgl_exp" name="tgl_exp">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="harga" class="form-label pt-2">Harga</label>
                                        <input type="number" class="form-control" id="harga" name="harga">
                                        <small class="fst-italic">
                                            Harga dapat diisi, ketika tanggal terbit sudah diisi!.
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" name="pic_input" value="{{ Auth::user()->nama }}">
                            <input type="hidden" name="jobsite" value="{{ $jobsite }}">

                            {{-- Sertifikat --}}
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-12">
                                        <label for="sertifikat" class="form-label pt-2 pt-md-0">Sertifikat</label>
                                        <input class="form-control" type="file" id="sertifikat" name="sertifikat"
                                            onchange="updatePreview(this);" required>
                                        <small class="fst-italic">
                                            File bisa foto atau pdf.
                                        </small>
                                    </div>

                                    <!-- Pratinjau gambar -->
                                    <div class="col-12 text-center">
                                        <div class="pt-3">
                                            <img id="imagePreview" src="#" alt="Preview"
                                                class="img-fluid img-thumbnail"
                                                style="display: none; max-width: 100%; max-height: 400px; margin: 0 auto;">
                                        </div>
                                    </div>

                                    <!-- Pratinjau PDF -->
                                    <div class="col-12 text-center">
                                        <iframe class="pt-3" style="display: none; margin: 0 auto;" id="pdfIframe"
                                            frameborder="0" height="400px" width="100%"></iframe>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" class="btn fw-bold btn-primary">Tambah</button>
                        <button type="reset" class="btn fw-bold btn-danger">Hapus</button>
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
                $('#nrp').change(function() {

                    var nrp = $(this).val();

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

                    var csrfToken = $('meta[name="csrf-token"]').attr('content');

                    $.ajax({
                        url: url,
                        type: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        },
                        success: function(data) {
                            $('#nama_karyawan').val(data.nama);

                            if (currentURL.includes(keywordToCheck)) {
                                var url = "/devppabib/public/get-ikatan-dinas/" + nrp;
                            } else {
                                var url = "/get-ikatan-dinas/" + nrp;
                            }

                            // Ambil Ikatan Dinas
                            $.ajax({
                                url: url,
                                type: 'GET',
                                headers: {
                                    'X-CSRF-TOKEN': csrfToken
                                },
                                success: function(data) {

                                    // Mengosongkan opsi pada elemen select dengan ID 'id_ikatan'
                                    $("#id_ikatan_dinas").empty();

                                    // Loop melalui data yang diterima dari respon success
                                    data.forEach(function(ikatan) {

                                        // Membuat string HTML untuk setiap opsi
                                        var element = '<option value="' +
                                            ikatan.id + '">ID:' + ikatan.id +
                                            ' | NRP: ' + ikatan.nrp +
                                            ' | Kode: ' + ikatan.kode +
                                            ' | Tanggal Akhir: ' + ikatan
                                            .tgl_akhir + '</option>';

                                        // Menambahkan opsi ke elemen select
                                        $("#id_ikatan_dinas")
                                            .append(element);
                                    });
                                },
                                error: function() {
                                    console.error('Gagal mengambil data ikatan dinas.');
                                }
                            });
                        },
                        error: function() {
                            console.error('Gagal mengambil data karyawan.');
                        }
                    });
                });

                $('#nama_kompetensi').change(function() {
                    var nama_kompetensi = $(this).val();

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

                    var csrfToken = $('meta[name="csrf-token"]').attr('content');

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
