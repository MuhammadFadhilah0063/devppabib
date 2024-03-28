@extends('layouts/dashboard', ['pageTitle' => 'Data User'])

@section('breadcrumb')
    <li class="breadcrumb-item active"><a>User</a></li>
@endsection

@push('button')
    <a href="{{ url('/user/create') }}" class="btn btn-sm btn-primary fw-bold">Tambah</a>
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
                <div class="table-responsive pt-3">
                    <!-- Table with stripped rows -->
                    <table class="table table-striped table-hover table-bordered" id="table-data">
                        <thead class="table-primary">
                            <tr>
                                <th class="text-center text-nowrap">No.</th>
                                <th class="text-center text-nowrap">NRP</th>
                                <th class="text-center text-nowrap">Nama</th>
                                <th class="text-center text-nowrap">Jobsite</th>
                                <th class="text-center text-nowrap">Level</th>
                                <th class="text-center text-nowrap">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td class="text-center text-nowrap">{{ $loop->iteration }}</td>
                                    <td class="text-center text-nowrap">{{ $user->nrp }}</td>
                                    <td class="text-center text-nowrap">{{ $user->nama }}</td>
                                    <td class="text-center text-nowrap">{{ $user->jobsite }}</td>
                                    <td class="text-center text-nowrap text-capitalize">
                                        <form action="user/{{ $user->id }}/ubah/level" method="post" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            @if ($user->level == 'admin')
                                                <button class="btn btn-info btn-sm fw-bold" type="submit">
                                                    {{ $user->level }}
                                                </button>
                                            @else
                                                <button class="btn btn-secondary btn-sm fw-bold" type="submit">
                                                    {{ $user->level }}
                                                </button>
                                            @endif
                                        </form>
                                    </td>
                                    <td class="text-center text-nowrap">
                                        <a href="user/{{ $user->id }}/edit"
                                            class="btn fw-bold btn-sm btn-warning d-inline">Edit</a>
                                        <form id="deleteForm_" action="user/{{ $user->id }}" method="post"
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
