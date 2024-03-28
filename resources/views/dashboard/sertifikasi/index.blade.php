@extends('layouts/dashboard', ['pageTitle' => 'Sertifikasi Karyawan ' . $jobsite])

@section('breadcrumb')
    <li class="breadcrumb-item active"><a>Sertifikasi Karyawan {{ $jobsite }}</a></li>
@endsection

@push('button')
    <a href="{{ url("sertifikasi/$jobsite/create") }}" class="btn btn-sm btn-primary fw-bold">Tambah</a>
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
                    <table class="table table-striped table-hover table-bordered" id="table-data" width="100%">
                        <thead class="table-primary">
                            <tr class="align-middle">
                                <th class="text-center text-nowrap">No.</th>
                                <th class="text-center text-nowrap">NRP</th>
                                <th class="text-center text-nowrap">Nama Karyawan</th>
                                <th class="text-center text-nowrap">Nama Kompetensi</th>
                                <th class="text-center text-nowrap">Kode</th>
                                <th class="text-center text-nowrap">Tanggal Terbit</th>
                                <th class="text-center text-nowrap">Lama Berlaku</th>
                                <th class="text-center text-nowrap">Sisa Hari Berlaku</th>
                                <th class="text-center text-nowrap">Tanggal Expired</th>
                                <th class="text-center text-nowrap">Harga</th>
                                <th class="text-center text-nowrap">Lama Ikatan Dinas</th>
                                <th class="text-center text-nowrap">Tanggal Akhir Ikatan Dinas</th>
                                <th class="text-center text-nowrap">Status Ikatan Dinas</th>
                                <th class="text-center text-nowrap">User Input</th>
                                <th class="text-center text-nowrap">Sertifikat</th>
                                <th class="text-center text-nowrap">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sertifikasis as $sertifikasi)
                                <tr>
                                    <td class="text-center text-nowrap">{{ $loop->iteration }}</td>
                                    <td class="text-center text-nowrap">{{ $sertifikasi->nrp }}</td>
                                    <td class="text-center text-nowrap">{{ $sertifikasi->nama_karyawan }}</td>
                                    <td class="text-center text-nowrap">{{ $sertifikasi->nama_kompetensi }}</td>
                                    <td class="text-center text-nowrap">{{ $sertifikasi->kode }}</td>
                                    <td class="text-center text-nowrap">{{ formatDate($sertifikasi->tgl_terbit) }}</td>
                                    <td class="text-center text-nowrap">
                                        {{ $sertifikasi->lama_berlaku ? $sertifikasi->lama_berlaku . ' Hari' : '-' }}
                                    </td>
                                    <td class="text-center text-nowrap">
                                        <span
                                            class="
                                        @if (selisihHari($sertifikasi->tgl_exp) == 'Expired Hari Ini') btn fw-bold btn-sm btn-warning d-inline
                                        @elseif (selisihHari($sertifikasi->tgl_exp) == 'Expired')
                                        btn fw-bold btn-sm btn-danger d-inline
                                        @elseif (selisihHari($sertifikasi->tgl_exp) == '-')
                                        @else
                                        btn fw-bold btn-sm btn-secondary d-inline @endif
                                        ">
                                            {{ selisihHari($sertifikasi->tgl_exp) }}
                                        </span>
                                    </td>
                                    <td class="text-center text-nowrap">{{ formatDate($sertifikasi->tgl_exp) }}</td>
                                    <td class="text-center text-nowrap">
                                        @if ($sertifikasi->harga)
                                            Rp. {{ number_format($sertifikasi->harga, 0, ',', '.') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="text-center text-nowrap">
                                        @if ($sertifikasi->lama_ikatan_dinas)
                                            {{ $sertifikasi->lama_ikatan_dinas }} Tahun
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="text-center text-nowrap">
                                        @if ($sertifikasi->ikatanDinas)
                                            {{ formatDate($sertifikasi->ikatanDinas->tgl_akhir) }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="text-center text-nowrap">
                                        @if ($sertifikasi->ikatanDinas)
                                            @if ($sertifikasi->ikatanDinas->status == 1)
                                                <div class="btn fw-bold btn-sm btn-warning">
                                                    Selesai
                                                </div>
                                            @else
                                                <div class="btn fw-bold btn-sm btn-info">
                                                    Belum Selesai
                                                </div>
                                            @endif
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="text-center text-nowrap">{{ $sertifikasi->pic_input }}</td>
                                    <td class="text-center text-nowrap">
                                        @if ($sertifikasi->sertifikat)
                                            <a href="{{ asset('storage/sertifikat/' . $sertifikasi->sertifikat) }}"
                                                target="_blank" class="btn fw-bold btn-sm btn-info d-inline">
                                                Lihat Sertifikat
                                            </a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="text-center text-nowrap">
                                        <a href="{{ $jobsite }}/{{ $sertifikasi->id }}/edit"
                                            class="btn fw-bold btn-sm btn-warning d-inline">Edit</a>
                                        <form id="deleteForm_" action="{{ $jobsite }}/{{ $sertifikasi->id }}"
                                            method="post" class="d-inline">
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
                            <div class="col-6 text-start ps-1">
                                <a href="{{ url("sertifikasi/$jobsite/export") }}" class="btn btn-sm btn-success fw-bold">
                                    Export Excel
                                </a>
                            </div>
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
                <form action="{{ url("sertifikasi/$jobsite/import") }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>PILIH FILE</label>
                            <input type="file" name="file" class="form-control" required>
                        </div>
                        <div class="text-center mt-2">
                            <small class="fst-italic">
                                <a class="btn btn-sm btn-danger" href="{{ url('/images/excel3.png') }}" target="_blank">
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
