@extends('layouts/main')

@section('container')
<div class="lime-container">
    <div class="lime-body">
        <div class="container">
            <div class="row">
                <div class="col-xl">
                    <div class="card">
                        <div class="card-body">
                            <h2 class="card-title mb-4" style="font-size: 20px;">Tabel Data Pemesanan</h2>
                        </div>
                        <div id="uptTable" class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr class="text-center">
                                        <th>Id Pemesanan</th>
                                        <th>Nama Bus</th>
                                        <th>Terminal Berangkat</th>
                                        <th>Terminal Tujuan</th>
                                        <th>Harga</th>
                                        <th>Tanggal Keberangkatan</th>
                                        <th>Jam Berangkat</th>
                                        <th>Status</th>
                                        <th>Nama</th>
                                        <th>Jumlah Tiket</th>
                                        <th>Nomor Handphone</th>
                                        <th>Tanggal Dibuat</th> 
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($reservations->isEmpty())
                                    <tr>
                                        <td colspan="13" class="text-center">Data kosong atau tidak ada data</td>
                                    </tr>
                                    @else
                                    @foreach($reservations as $reservation)
                                    <tr class="text-center" id="upt_ids{{ $reservation->id }}">
                                        <td>{{ $reservation->id }}</td>
                                        <td>{{ $reservation->schedule->bus->name }} ({{ $reservation->schedule->bus->license_plate_number }})</td>
                                        <td>{{ $reservation->schedule->fromStation->name }}</td>
                                        <td>{{ $reservation->schedule->toStation->name }}</td>

                                        <td>{{ $reservation->schedule->price }}</td>
                                        <td>{{ $reservation->date_departure }}</td>
                                        <td>{{ $reservation->schedule->time_start }}</td>
                                        <td>
                                            @if ($reservation->status == 1)
                                                <span class="badge badge-primary">Belum Acc</span>
                                            @elseif ($reservation->status == 2)
                                                <span class="badge badge-warning">Selesai</span>
                                            @endif
                                        </td>
                                        <td>{{ $reservation->user->name }}</td>
                                        <td>{{ $reservation->tickets_booked}}</td>
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