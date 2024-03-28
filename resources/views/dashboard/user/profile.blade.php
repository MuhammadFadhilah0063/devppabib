@extends('layouts/dashboard', ['pageTitle' => 'Profil User'])

@section('breadcrumb')
    <li class="breadcrumb-item active">Profil User</a></li>
@endsection

@section('content')
    <section class="section profile">
        <div class="row">
            <div class="col-xl-4">

                <div class="card">
                    <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

                        <img src="{{ asset('storage/foto_profil/' . Auth::user()->foto) }}" alt="Profile"
                            class="rounded-circle img-thumbnail">
                        <h2>{{ Auth::user()->nama }}</h2>
                    </div>
                </div>

            </div>

            <div class="col-xl-8">

                <div class="card">
                    <div class="card-body pt-3">
                        <div class="tab-pane fade show active profile-overview" id="profile-overview">
                            <h5 class="card-title">Detail Profil</h5>

                            <div class="row">
                                <div class="col-lg-3 col-md-4 label ">Nama</div>
                                <div class="col-lg-9 col-md-8">: {{ Auth::user()->nama }}</div>
                            </div>

                            <div class="row">
                                <div class="col-lg-3 col-md-4 label">Jobsite</div>
                                <div class="col-lg-9 col-md-8">: {{ Auth::user()->jobsite }}</div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection
