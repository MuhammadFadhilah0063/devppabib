@extends('layouts/dashboard', ['pageTitle' => 'Mutasi Karyawan'])

@section('breadcrumb')
    <li class="breadcrumb-item active"><a>Mutasi Karyawan</a></li>
@endsection

@push('button')
    <a href="{{ url('mutasi_karyawan/create') }}" class="btn btn-sm btn-primary fw-bold">Tambah</a>
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
                                <th class="text-center text-nowrap">NRP</th>
                                <th class="text-center text-nowrap">Nama</th>
                                <th class="text-center text-nowrap">Jobsite Lama</th>
                                <th class="text-center text-nowrap">Jobsite Tujuan</th>
                                <th class="text-center text-nowrap">Tanggal Mutasi</th>
                                <th class="text-center text-nowrap">Status</th>
                                <th class="text-center text-nowrap">Disetujui</th>
                                <th class="text-center text-nowrap">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($mutasiKaryawans as $mutasi)
                                <tr class="align-middle">
                                    <td class="text-center text-nowrap">{{ $loop->iteration }}</td>
                                    <td class="text-center text-nowrap">{{ $mutasi->nrp }}</td>
                                    <td class="text-center text-nowrap">{{ $mutasi->nama }}</td>
                                    <td class="text-center text-nowrap">{{ $mutasi->jobsite_lama }}</td>
                                    <td class="text-center text-nowrap">{{ $mutasi->jobsite_tujuan }}</td>
                                    <td class="text-center text-nowrap">{{ formatDate($mutasi->tgl_mutasi) }}</td>
                                    <td class="text-center text-nowrap">
                                        @if ($mutasi->status == 'Menunggu')
                                            <div class="btn btn-sm btn-warning fw-bold">
                                                {{ $mutasi->status }}
                                            </div>
                                        @elseif($mutasi->status == 'Ditolak')
                                            <div class="btn btn-sm btn-danger fw-bold">
                                                {{ $mutasi->status }}
                                            </div>
                                        @else
                                            <div class="btn btn-sm btn-info fw-bold">
                                                {{ $mutasi->status }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="text-center text-nowrap">
                                        @if ($mutasi->disetujui != null)
                                            {{ $mutasi->disetujui }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="text-center text-nowrap">
                                        <a href="mutasi_karyawan/{{ $mutasi->id }}/edit"
                                            class="btn fw-bold btn-sm btn-warning d-inline">Edit</a>
                                        <form id="deleteForm_" action="mutasi_karyawan/{{ $mutasi->id }}" method="post"
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
                    <!-- End Table with stripped rows -->

                </div>

                <div class="row">
                    <div class="col text-center pt-3">
                        <a href="{{ url('mutasi_karyawan/export/excel/') }}" class="btn btn-sm btn-success fw-bold">
                            Export Excel
                        </a>
                    </div>
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
