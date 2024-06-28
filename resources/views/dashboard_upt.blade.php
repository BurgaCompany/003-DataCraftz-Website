@extends('layouts.main')

@section('container')
    <div class="lime-body">
        <div class="container">
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card bg-info text-white h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="dashboard-info row ">
                                <div class="info-text col-md-6 d-flex flex-column justify-content-center">
                                    @if (Auth::check())
                                        <h5 class="card-title">Selamat Datang, kembali {{ $user->name }}!</h5>
                                        <p>
                                            Anda telah masuk ke dalam halaman dashboard.
                                        </p>
                                        <br>
                                        <p>
                                            Jangan ragu untuk menjelajahi berbagai fitur yang tersedia dan lakukan tindakan
                                            yang diperlukan untuk mengelola aplikasi Anda dengan baik.
                                        </p>
                                    @endif
                                </div>
                                <div class="info-image col-md-6 d-flex align-items-center justify-content-center">
                                    <!-- Tempatkan gambar Anda di sini -->
                                    <img src="" class="img-fluid">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <canvas id="passengersByTerminalChart" width="100" height="100"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <a href="#">
                        <div class="card stat-card shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="card-title"><i class="fas fa-user-shield fa-lg mr-2"></i>Total Admin</h5>
                                    <h2 class="text-warning">{{ $totalAdmins }}</h2>
                                </div>
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar bg-warning" role="progressbar"
                                        style="width: {{ $totalAdmins }}%" aria-valuenow="{{ $totalAdmins }}"
                                        aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-6">
                    <a href="#">
                        <div class="card stat-card shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="card-title"><i class="fas fa-bus-alt fa-lg mr-2"></i>Total Terminal</h5>
                                    <h2 class="text-warning">{{ $terminals }}</h2>
                                </div>
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar bg-warning" role="progressbar"
                                        style="width: {{ $terminals }}%" aria-valuenow="{{ $terminals }}"
                                        aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title">Peta Terminal</h5>
                            <div id="map" style="height: 500px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {

                var barCtx = document.getElementById('passengersByTerminalChart').getContext('2d');
                var passengersByTerminalChart = new Chart(barCtx, {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode($terminalLabels) !!},
                        datasets: [{
                            label: 'Penumpang per Terminal',
                            data: {!! json_encode($passengerCounts) !!},
                            backgroundColor: 'rgba(75, 192, 192, 0.6)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(tooltipItem) {
                                        return tooltipItem.label + ': ' + tooltipItem.raw.toLocaleString();
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });

                var map = L.map('map').setView([{{ $terminalsLocations[0]->latitude }},
                    {{ $terminalsLocations[0]->longitude }}
                ], 12);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors'
                }).addTo(map);

                var terminalMarkers = [];
                @foreach ($terminalsLocations as $terminal)
                    var marker = L.marker([{{ $terminal->latitude }}, {{ $terminal->longitude }}])
                        .addTo(map)
                        .bindPopup('<b>{{ $terminal->name }}</b><br>Terminal ini terletak di sini.');
                    terminalMarkers.push([{{ $terminal->latitude }}, {{ $terminal->longitude }}]);
                @endforeach


                map.fitBounds(polyline.getBounds());
            });
        </script>
    @endsection
