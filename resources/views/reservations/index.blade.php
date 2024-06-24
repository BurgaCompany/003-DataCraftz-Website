    @extends('layouts/main')

    @section('container')
    <div class="lime-container">
        <div class="lime-body">
            <div class="container">
                <div class="row">
                    <div class="col-xl">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h2 class="card-title" style="font-size: 20px;">Tabel Data Pemesanan</h2>
                                    <div class="alert alert-info mb-0" role="alert">
                                        <strong>Saldo:</strong> <span id="available-chairs">{{ $totalSaldo }}</span>
                                    </div>
                                </div>

                                <!-- Form input untuk setor tunai ke UPT -->
                                <form action="" method="POST">
                                    @csrf
                                    <div class="row mb-4">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="user_id">User ID</label>
                                                <input type="text" class="form-control" id="user_id" name="user_id" placeholder="Masukkan User ID" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="bus_station_id">Bus Station ID</label>
                                                <input type="text" class="form-control" id="bus_station_id" name="bus_station_id" placeholder="Masukkan Bus Station ID" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="amount">Jumlah Setor</label>
                                                <input type="number" class="form-control" id="amount" name="amount" placeholder="Masukkan Jumlah Setor" required>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Setor Tunai</button>
                                </form>

                                <div id="uptTable" class="table-responsive mt-4">
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            <tr class="text-center">
                                                <th>Id Pemesanan</th>
                                                <th>Nama Bus</th>
                                                <th>Perusahaan Otobus</th>
                                                <th>Terminal Berangkat</th>
                                                <th>Terminal Tujuan</th>
                                                <th>Harga</th>
                                                <th>Tanggal Keberangkatan</th>
                                                <th>Jam Berangkat</th>
                                                <th>Status</th>
                                                <th>Nama</th>
                                                <th>Jumlah Tiket</th>
                                                <th>Metode Pembayaran</th>
                                                <th>Nomor Handphone</th>
                                                <th>Tanggal Dibuat</th> 
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($reservations->isEmpty())
                                            <tr>
                                                <td colspan="14" class="text-center">Data kosong atau tidak ada data</td>
                                            </tr>
                                            @else
                                            @foreach($reservations as $reservation)
                                            <tr class="text-center" id="upt_ids{{ $reservation->id }}">
                                                <td>{{ $reservation->id }}</td>
                                                <td>{{ $reservation->schedule->bus->name }} ({{ $reservation->schedule->bus->license_plate_number }})</td>
                                                <td>{{$reservation->schedule->bus->po->name }}</td>
                                                <td>{{ $reservation->schedule->fromStation->name }}</td>
                                                <td>{{ $reservation->schedule->toStation->name }}</td>
                                                <td>{{ $reservation->schedule->price }}</td>
                                                <td>{{ $reservation->date_departure }}</td>
                                                <td>{{ $reservation->schedule->time_start }}</td>
                                                <td>
                                                    @if ($reservation->status == 'Berhasil Dibayar')
                                                        <span class="badge badge-primary"> Berhasil Dibayar</span>
                                                    @elseif ($reservation->status == 'Pembayaran Pending')
                                                        <span class="badge badge-warning">Pembayaran Pending</span>
                                                    @endif
                                                </td>
                                                <td>{{ $reservation->user->name }}</td>
                                                <td>{{ $reservation->tickets_booked}}</td>
                                                <td>{{ $reservation->payment_method }}</td>
                                                <td>{{ $reservation->user->phone_number }}</td>
                                                <td>{{ $reservation->created_at }}</td>
                                            </tr>
                                            @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection
