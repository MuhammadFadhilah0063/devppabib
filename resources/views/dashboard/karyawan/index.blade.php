@extends('layouts/dashboard', ['pageTitle' => 'Karyawan ' . $jobsite])

@section('breadcrumb')
    <li class="breadcrumb-item active"><a>Karyawan {{ $jobsite }}</a></li>
@endsection

@push('button')
    <a href="{{ url("karyawan/$jobsite/create") }}" class="btn btn-sm btn-primary fw-bold">Tambah</a>
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
                    <table class="table table-striped table-hover table-bordered" id="table-karyawan">
                        <thead class="table-primary">
                            <tr>
                                <th class="text-center">No.</th>
                                <th class="text-center">NRP</th>
                                <th class="text-center">Nama</th>
                                <th class="text-center">Departemen</th>
                                <th class="text-center">Posisi</th>
                                <th class="text-center text-nowrap">Status</th>
                                <th class="text-center text-nowrap">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    <!-- End Table with stripped rows -->
                </div>

                <div class="row">
                    <div class="col text-center pt-3">
                        <div class="row">
                            <div class="col-6 text-end pe-1">
                                <button data-bs-toggle="modal" data-bs-target="#import" type="button"
                                    class="btn btn-sm btn-warning fw-bold">
                                    Import Excel
                                </button>
                            </div>
                            <div class="col-6 text-start ps-1">
                                <a href="{{ url("karyawan/$jobsite/export") }}" class="btn btn-sm btn-success fw-bold">
                                    Export Excel
                                </a>
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
                        <form action="{{ url("karyawan/$jobsite/import") }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>PILIH FILE</label>
                                    <input type="file" name="file" class="form-control" required>
                                </div>
                                <div class="text-center mt-2">
                                    <small class="fst-italic">
                                        <a class="btn btn-sm btn-danger" href="{{ url('/images/excel.png') }}"
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
                    $(document).ready(function() {
                        $('#table-karyawan').DataTable({
                            processing: true,
                            serverSide: true,
                            ajax: '{{ url()->current() }}?jobsite={{ $jobsite }}',
                            columns: [{
                                    data: 'id',
                                    orderable: false,
                                    searchable: false,
                                    render: function(data, type, row, meta) {
                                        return new Intl.NumberFormat('id-ID').format(meta.row + meta.settings
                                            ._iDisplayStart + 1);
                                    }
                                },
                                {
                                    data: 'nrp',
                                },
                                {
                                    data: 'nama',
                                },
                                {
                                    data: 'departemen',
                                },
                                {
                                    data: 'posisi',
                                },
                                {
                                    data: 'ubah_status',
                                },
                                {
                                    data: 'id',
                                    render: function(data) {
                                        return `<a href="{{ $jobsite }}/${data}/edit"
                                        class="btn fw-bold btn-sm btn-warning d-inline">Edit</a>
                                        <form id="deleteForm_" action="{{ $jobsite }}/${data}" method="post"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn fw-bold btn-sm btn-danger d-inline" type="submit"
                                            onclick="confirmDelete(event)">Hapus</button>
                                    </form>`;
                                    }
                                },
                            ],
                            columnDefs: [{
                                    targets: [0, 1, 5, 6],
                                    className: "text-center align-middle text-capitalize text-nowrap"
                                },
                                {
                                    targets: [2, 3, 4],
                                    className: "align-middle text-capitalize text-nowrap"
                                },
                            ]
                        });
                    });

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
