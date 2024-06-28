@extends('layouts.main')

@section('container')
    <div class="lime-body">
        <div class="container">
            <div class="row mb-4">
                <div class="col-md-8">
                    <div class="card bg-info text-white h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="dashboard-info row w-100">
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
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <canvas id="passengersChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row mb-4">
                <div class="col-md-6">
                    <a href="">
                        <div class="card stat-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="card-title"><i class="fas fa-user-shield fa-lg mr-2"></i>Total Penumpang Naik
                                    </h5>
                                </div>
                                <h2 class="float-right">{{ $passengersOn }}</h2>
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar bg-warning" role="progressbar"
                                        style="width: {{ $passengersOn }}%" aria-valuenow="{{ $passengersOn }}"
                                        aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-6">
                    <a href="">
                        <div class="card stat-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="card-title"><i class="fas fa-bus-alt fa-lg mr-2"></i>Total Penumpang Turun
                                    </h5>
                                </div>
                                <h2 class="float-right">{{ $passengersOff }}</h2>
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar bg-warning" role="progressbar"
                                        style="width: {{ $passengersOff }}%" aria-valuenow="{{ $passengersOff }}"
                                        aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>


            <div class="row mb-4">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Jadwal</h5>
                            <div id="uptTable" class="table-responsive">
                                <table class="table" id="scheduleTable">
                                    <thead>
                                        <tr class="text-center">
                                            <th>Terminal Berangkat</th>
                                            <th>Terminal Tujuan</th>
                                            <th>Nama Bus</th>
                                            <th data-sort="asc">Berangkat<i class="fas fa-sort"></i></th>
                                            <th data-sort="asc">Tiba<i class="fas fa-sort"></i></th>
                                            <th>Rentang Harga</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($schedules->isEmpty())
                                            <tr>
                                                <td colspan="6" class="text-center">Data kosong atau tidak ada data</td>
                                            </tr>
                                        @else
                                            @foreach ($schedules as $schedule)
                                                <tr class="text-center" id="upt_ids{{ $schedule->id }}">
                                                    <td>{{ $schedule->fromStation->name }}</td>
                                                    <td>{{ $schedule->toStation->name }}</td>
                                                    <td>{{ $schedule->bus->name }}
                                                        ({{ $schedule->bus->license_plate_number }})
                                                    </td>
                                                    <td>{{ $schedule->time_start }}</td>
                                                    <td>{{ $schedule->time_arrive }}</td>
                                                    <td>{{ number_format($schedule->min_price, 0, ',', '.') }} -
                                                        {{ number_format($schedule->max_price, 0, ',', '.') }}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Admin Yang Bertugas di {{ $adminBusStation }}</h5>
                            <ul>
                                @foreach ($admins as $admin)
                                    <li>{{ $admin->name }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var ctx = document.getElementById('passengersChart').getContext('2d');

            var passengersChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Penumpang Naik', 'Penumpang Turun'],
                    datasets: [{
                        label: 'Total Penumpang',
                        data: [{{ $passengersOn ?? 0 }}, {{ $passengersOff ?? 0 }}],
                        backgroundColor: [
                            'rgba(54, 162, 235, 0.5)',
                            'rgba(255, 99, 132, 0.5)',
                        ],
                        borderColor: [
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 99, 132, 1)',
                        ],
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
                    }
                }
            });

            // Sorting functionality
            const scheduleTable = document.getElementById('scheduleTable');
            const headers = scheduleTable.querySelectorAll('th[data-sort]');

            headers.forEach(header => {
                header.addEventListener('click', () => {
                    const sortDirection = header.getAttribute('data-sort') === 'asc' ? 'desc' :
                        'asc';
                    header.setAttribute('data-sort', sortDirection);

                    // Update sort icon
                    headers.forEach(h => h.querySelector('i').className = 'fas fa-sort');
                    if (sortDirection === 'asc') {
                        header.querySelector('i').className = 'fas fa-sort-up';
                    } else {
                        header.querySelector('i').className = 'fas fa-sort-down';
                    }

                    const column = Array.from(header.parentElement.children).indexOf(header);
                    const rows = Array.from(scheduleTable.querySelector('tbody').children);

                    rows.sort((rowA, rowB) => {
                        const cellA = rowA.children[column].innerText;
                        const cellB = rowB.children[column].innerText;

                        if (sortDirection === 'asc') {
                            return cellA.localeCompare(cellB);
                        } else {
                            return cellB.localeCompare(cellA);
                        }
                    });

                    rows.forEach(row => scheduleTable.querySelector('tbody').appendChild(row));
                });
            });
        });
    </script>
@endsection
