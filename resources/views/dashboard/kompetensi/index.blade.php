@extends('layouts/dashboard', ['pageTitle' => 'Data Kompetensi'])

@section('breadcrumb')
    <li class="breadcrumb-item active"><a>Kompetensi</a></li>
@endsection

@push('button')
    <a href="{{ url('/komp/create') }}" class="btn btn-sm btn-primary fw-bold">Tambah</a>
@endpush

@section('content')
    <div class="col-lg-12">
        {{-- Alert --}}
        @if (session()->has('berhasil'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-1"></i>
                {{ session('berhasil') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @elseif(session()->has('gagal'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-octagon me-1"></i>
                {{ session('gagal') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        {{-- Alert End --}}

        <div class="card">
            <div class="card-body">

                <!-- Table with stripped rows -->
                <div class="table-responsive pt-3">
                    <table class="table table-striped table-hover table-bordered" id="table-data">
                        <thead class="table-primary">
                            <tr>
                                <th class="text-center text-nowrap">No.</th>
                                <th class="text-center text-nowrap">Nama</th>
                                <th class="text-center text-nowrap">Kode</th>
                                <th class="text-center text-nowrap">Jenis</th>
                                <th class="text-center text-nowrap">Sertifikasi</th>
                                <th class="text-center text-nowrap">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($kompetensis as $kompetensi)
                                <tr>
                                    <td class="text-center text-nowrap">{{ $loop->iteration }}</td>
                                    <td class="text-center text-nowrap">{{ $kompetensi->nama }}</td>
                                    <td class="text-center text-nowrap">{{ $kompetensi->kode }}</td>
                                    <td class="text-center text-nowrap">{{ $kompetensi->jenis }}</td>
                                    <td class="text-center text-nowrap">{{ $kompetensi->sertifikasi }}</td>
                                    <td class="text-center text-nowrap">
                                        <a href="komp/{{ $kompetensi->id }}/edit"
                                            class="btn fw-bold btn-sm btn-warning d-inline">Edit</a>
                                        <form id="deleteForm_" action="komp/{{ $kompetensi->id }}" method="post"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn fw-bold btn-sm btn-danger d-inline" type="submit"
                                                onclick="confirmDelete(event)">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- End Table with stripped rows -->

                <div class="row">
                    <div class="col text-center pt-3">
                        <div class="row">
                            <div class="col-6 text-end pe-1">
                                <button data-bs-toggle="modal" data-bs-target="#import" type="button"
                                    class="btn btn-sm btn-warning fw-bold">
                                    Import Excel
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal --}}
        <div class="modal fade" id="import" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">IMPORT DATA EXCEL</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ url('komp/import/excel') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label>PILIH FILE</label>
                                <input type="file" name="file" class="form-control" required>
                            </div>
                            <div class="text-center mt-2">
                                <small class="fst-italic">
                                    <a class="btn btn-sm btn-danger" href="{{ url('/images/excel2.png') }}"
                                        target="_blank">
                                        Lihat contoh format isi excel
                                    </a>
                                </small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">TUTUP</button>
                            <button type="submit" class="btn btn-primary">IMPORT</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @push('scripts')
            <script>
                function confirmDelete(event) {
                    var confirmation = confirm("Anda yakin ingin menghapus?");

                    if (confirmation) {
                        event.submit();
                    } else {
                        event.preventDefault();
                    }
                }
            </script>
        @endpush
    </div>
@endsection
