@extends('layouts/dashboard', ['pageTitle' => 'Permintaan Mutasi Karyawan'])

@section('breadcrumb')
    <li class="breadcrumb-item active"><a>Permintaan Mutasi Karyawan</a></li>
@endsection

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
                                <th class="text-center text-nowrap">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($mutasis as $mutasi)
                                <tr class="align-middle">
                                    <td class="text-center text-nowrap">{{ $loop->iteration }}</td>
                                    <td class="text-center text-nowrap">{{ $mutasi->nrp }}</td>
                                    <td class="text-center text-nowrap">{{ $mutasi->nama }}</td>
                                    <td class="text-center text-nowrap">{{ $mutasi->jobsite_lama }}</td>
                                    <td class="text-center text-nowrap">{{ $mutasi->jobsite_tujuan }}</td>
                                    <td class="text-center text-nowrap">{{ formatDate($mutasi->tgl_mutasi) }}</td>
                                    <td class="text-center text-nowrap">
                                        <form id="approve_" action="mutasi_karyawan/{{ $mutasi->id }}" method="post"
                                            class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <button class="btn fw-bold btn-sm btn-primary d-inline" type="submit"
                                                onclick="confirmMessage(event, 'terima mutasi')">Terima</button>
                                        </form>
                                        <form id="reject_" action="reject/mutasi_karyawan/{{ $mutasi->id }}"
                                            method="post" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <button class="btn fw-bold btn-sm btn-danger d-inline" type="submit"
                                                onclick="confirmMessage(event, 'tolak mutasi')">Tolak</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <!-- End Table with stripped rows -->
                </div>
            </div>
        </div>

        @push('scripts')
            <script>
                function confirmMessage(event, $pesan) {
                    var confirmation = confirm("Anda yakin " + $pesan + " ini?");

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
