@extends('layouts/main')

@section('container')
    <div class="lime-body">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="dashboard-info row">
                                <div class="info-text col-md-6">
                                    @if(Auth::check())
                                    <h5 class="card-title">Selamat Datang, kembali {{ $user->name }}!</h5>
                                    <p>
                                        Anda telah masuk ke dalam halaman dashboard. 
                                    </p>
                                    <br>
                                    <p>
                                        Jangan ragu untuk menjelajahi berbagai fitur yang tersedia dan lakukan tindakan yang diperlukan untuk mengelola aplikasi Anda dengan baik.
                                    </p>
                                @endif
                                </div>
                                <div class="info-image col-md-6"></div>
                            </div>
                        </div>
                    </div>
                </div>
                @if (Auth::user()->hasAnyRole(['Upt', 'Admin']))
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Grafik Pemesanan Bus</h5>
                            <canvas id="reservationsChart" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>
                @endif

            </div>
            
            @if (Auth::user()->hasRole('Upt'))
            <div class="row">
                <div class="col-md-4">
                    <a href="{{ route('admins.index') }}">
                        <div class="card stat-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="card-title"><i class="fas fa-user-shield fa-lg mr-2"></i>Total Admin</h5>
                                </div>
                                <h2 class="float-right">{{ $totalAdmins }}</h2>
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $totalAdmins }}%" aria-valuenow="{{ $totalAdmins }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="{{ route('bus_stations.index') }}">
                        <div class="card stat-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="card-title"> <i class="fas fa-bus-alt fa-lg mr-2"></i>Total Terminal</h5>
                                </div>
                                <h2 class="float-right">{{ $totalBusStations }}</h2>
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $totalBusStations }}%" aria-valuenow="{{ $totalBusStations }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="{{ route('busses.index') }}">
                        <div class="card stat-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="card-title"> <i class="fas fa-bus fa-lg mr-2"></i>Total Bus</h5>
                                </div>
                                <h2 class="float-right">{{ $totalBusses }}</h2>
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $totalBusses }}%" aria-valuenow="{{ $totalBusses }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            @endif
            
            @if (Auth::user()->hasRole('Upt'))
            <div class="row">
                <div class="col-md-4">
                    <a href="{{ route('admins.index') }}">
                        <div class="card stat-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="card-title"><i class="fas fa-user-shield fa-lg mr-2"></i>Total Admin</h5>
                                </div>
                                <h2 class="float-right">{{ $totalAdmins }}</h2>
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $totalAdmins }}%" aria-valuenow="{{ $totalAdmins }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="{{ route('bus_stations.index') }}">
                        <div class="card stat-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="card-title"> <i class="fas fa-bus-alt fa-lg mr-2"></i>Total Terminal</h5>
                                </div>
                                <h2 class="float-right">{{ $totalBusStations }}</h2>
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $totalBusStations }}%" aria-valuenow="{{ $totalBusStations }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="{{ route('busses.index') }}">
                        <div class="card stat-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="card-title"> <i class="fas fa-bus fa-lg mr-2"></i>Total Bus</h5>
                                </div>
                                <h2 class="float-right">{{ $totalBusses }}</h2>
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $totalBusses }}%" aria-valuenow="{{ $totalBusses }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            @endif
            
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="form-group mr-3 mb-0">
                                        <label for="status">Status</label>
                                        <select class="js-states form-control" name="status" id="status" style="width: 200px;" onchange="filterBusses()" required>
                                            <option value="" {{ request('status') == '' ? 'selected' : '' }}>Semua</option>
                                            <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Belum Berangkat</option>
                                            <option value="2" {{ request('status') == '2' ? 'selected' : '' }}>Bersedia Berangkat</option>
                                            <option value="3" {{ request('status') == '3' ? 'selected' : '' }}>Berangkat</option>
                                            <option value="4" {{ request('status') == '4' ? 'selected' : '' }}>Terkendala</option>
                                            <option value="5" {{ request('status') == '5' ? 'selected' : '' }}>Tiba di Tujuan</option>
                                        </select>
                                    </div>
                                    <h4 class="mb-0 align-middle">
                                        <span class="badge badge-info">Data Bus</span>
                                    </h4>
                                </div>
                            </div>
                            <div id="uptTable" class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr class="text-center">
                                            <th>ID</th>
                                            <th>Nama</th>
                                            <th>Status</th>
                                            <th>Jumlah Kursi</th>
                                            <th>Nomor Plat</th>
                                            <th>Kelas</th>
                                            <th>Informasi</th>
                                            <th>Sopir</th>
                                            <th>Kondektur</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="busTableBody">
                                        @include('partials.bus_table_body', ['busses' => $busses])
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
        @endsection
 