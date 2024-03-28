@extends('layouts/dashboard', ['pageTitle' => 'Data Ikatan Dinas'])

@section('breadcrumb')
    <li class="breadcrumb-item active"><a>Ikatan Dinas</a></li>
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
            <div class="card-header">
                <div class="row text-center text-sm-start">
                    <label for="filter_nrp" class="pb-2 fw-bold">Filter NRP</label>
                </div>
                <div class="row">
                    <div class="col d-flex justify-content-center justify-content-sm-start">
                        <select data-column="1" name="filter_nrp" id="filter_nrp" class="form-control select2"
                            style="max-width: 250px;">
                            <option value="">Pilih Filter</option>
                            @foreach ($users as $user)
                                <option value="{{ ucfirst($user->nrp) }}">
                                    {{ ucfirst($user->nrp) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="card-body">

                <!-- Table with stripped rows -->
                <div class="table-responsive pt-3">
                    <table class="table table-striped table-hover table-bordered" id="table-ikatan">
                        <thead class="table-primary">
                            <tr>
                                <th class="text-center text-nowrap">No.</th>
                                <th class="text-center text-nowrap">NRP</th>
                                <th class="text-center text-nowrap">Nama Karyawan</th>
                                <th class="text-center text-nowrap">Nama Kompetensi</th>
                                <th class="text-center text-nowrap">Kode</th>
                                <th class="text-center text-nowrap">Tanggal Akhir</th>
                                <th class="text-center text-nowrap">Status</th>
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
                            <div class="col">
                                <a href="{{ url('ikatan_dinas/export') }}" class="btn btn-sm btn-success fw-bold">
                                    Export Excel
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @push('scripts')
            <script>
                $(document).ready(function() {
                    var dataTable = $('#table-ikatan').DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: '{{ url()->current() }}',
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
                                data: 'nama_karyawan',
                            },
                            {
                                data: 'nama_kompetensi',
                            },
                            {
                                data: 'kode',
                            },
                            {
                                data: 'tgl_akhir',
                                render: function(data) {
                                    return formatDateToIndonesianWithMonthName(data);
                                }
                            },
                            {
                                data: 'status',
                                render: function(data) {
                                    if (data == 1) {
                                        return "<div class='btn btn-sm btn-success fw-bold'>Selesai</div>";
                                    } else {
                                        return "<div class='btn btn-sm btn-warning fw-bold'>Belum Selesai</div>";
                                    }
                                }
                            },
                        ],
                        columnDefs: [{
                                targets: [0, 1, 4, 5, 6],
                                className: "text-center align-middle text-capitalize text-nowrap"
                            },
                            {
                                targets: [2, 3],
                                className: "align-middle text-capitalize text-nowrap"
                            },
                        ]
                    });

                    // Filter table
                    $('#filter_nrp').change(function() {
                        dataTable.column($(this).data('column')).search($(this).val()).draw();
                    });

                    function formatDateToIndonesianWithMonthName(dateString) {
                        // Array untuk nama bulan dalam Bahasa Indonesia
                        var monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni",
                            "Juli", "Agustus", "September", "Oktober", "November", "Desember"
                        ];

                        // Parsing string tanggal ke objek Date
                        var dateParts = dateString.split("-");
                        var date = new Date(dateParts[0], dateParts[1] - 1, dateParts[2]);

                        // Mendapatkan tanggal, bulan, dan tahun dari objek Date
                        var day = date.getDate();
                        var monthIndex = date.getMonth();
                        var year = date.getFullYear();

                        // Membuat string dengan format tanggal Indonesia (dd MMMM yyyy)
                        var formattedDate = (day < 10 ? '0' : '') + day + ' ' + monthNames[monthIndex] + ' ' + year;

                        return formattedDate;
                    }
                });
            </script>
        @endpush
    </div>
@endsection
