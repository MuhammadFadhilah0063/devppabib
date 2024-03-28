@extends('layouts/dashboard', ['pageTitle' => 'Sertifikasi Expired'])

@section('breadcrumb')
    <li class="breadcrumb-item active"><a>Sertifikasi Expired</a></li>
@endsection

@section('content')
    <div class="col-lg-12">

        <div class="card">
            <div class="card-body">

                <div class="table-responsive pt-3">
                    <!-- Table with stripped rows -->
                    <table class="table table-striped table-hover table-bordered" id="table-data">
                        <thead class="table-primary">
                            <tr>
                                <th class="text-center text-nowrap">No.</th>
                                <th class="text-center text-nowrap">NRP</th>
                                <th class="text-center text-nowrap">Nama Karyawan</th>
                                <th class="text-center text-nowrap">Jobsite</th>
                                <th class="text-center text-nowrap">Nama Kompetensi</th>
                                <th class="text-center text-nowrap">Kode</th>
                                <th class="text-center text-nowrap">Tanggal Terbit</th>
                                <th class="text-center text-nowrap">Expired</th>
                                <th class="text-center text-nowrap">Tanggal Expired</th>
                                <th class="text-center text-nowrap">User Input</th>
                                <th class="text-center text-nowrap">Sertifikat</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sertifikasis as $sertifikasi)
                                <tr>
                                    <td class="text-center text-nowrap">{{ $loop->iteration }}</td>
                                    <td class="text-center text-nowrap">{{ $sertifikasi->nrp }}</td>
                                    <td class="text-center text-nowrap">{{ $sertifikasi->nama_karyawan }}</td>
                                    <td class="text-center text-nowrap">{{ $sertifikasi->jobsite }}</td>
                                    <td class="text-center text-nowrap">{{ $sertifikasi->nama_kompetensi }}</td>
                                    <td class="text-center text-nowrap">{{ $sertifikasi->kode }}</td>
                                    <td class="text-center text-nowrap">{{ formatDate($sertifikasi->tgl_terbit) }}</td>
                                    <td class="text-center">
                                        <span
                                            class="
                                        @if (selisihHari($sertifikasi->tgl_exp) == 'Expired Hari Ini') btn fw-bold btn-sm btn-warning d-inline
                                        @elseif (selisihHari($sertifikasi->tgl_exp) == 'Expired')
                                        btn fw-bold btn-sm btn-danger d-inline
                                        @else
                                        btn fw-bold btn-sm btn-secondary d-inline @endif
                                        ">
                                            {{ selisihHari($sertifikasi->tgl_exp) }}
                                        </span>
                                    </td>
                                    <td class="text-center text-nowrap">{{ formatDate($sertifikasi->tgl_exp) }}</td>
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
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <!-- End Table with stripped rows -->
                </div>

                <div class="row">
                    <div class="col text-center pt-3">
                        <a href="{{ url('expired/sertifikasi/export/excel/') }}" class="btn btn-sm btn-success fw-bold">
                            Export Excel
                        </a>
                    </div>
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
