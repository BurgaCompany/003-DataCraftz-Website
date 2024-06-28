@extends('layouts/main')

@section('container')
    <div class="lime-container">
        <div class="lime-body">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xl-4">
                        <div class="card">
                            <div class="card-body">
                                <h2 class="card-title mb-4" style="font-size: 20px; text-align: center;">Tiket Pemesanan</h2>

                                <div class="ticket-details">
                                    <div class="ticket-details text-center mb-4">
                                        <p>{{ $reservation->created_at->format('d M Y') }}</p>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 text-center">
                                            <p>{{ $reservation->schedule->fromStation->code_name }}</p>
                                            <p>{{ $fromStation }}</p>
                                        </div>
                                        <div class="col-md-4 text-center">
                                            <p>
                                                @php
                                                    $timeArrive = \Carbon\Carbon::parse($reservation->time_arrive);
                                                    $timeStart = \Carbon\Carbon::parse(
                                                        $reservation->schedule->time_start,
                                                    );
                                                    $diff = $timeArrive->diff($timeStart);
                                                    $hours = $diff->h;
                                                    $minutes = $diff->i;
                                                    $estimatedTime = "{$hours} jam {$minutes} menit";
                                                @endphp
                                                {{ $estimatedTime }}
                                            </p>
                                        </div>
                                        <div class="col-md-4 text-center">
                                            <p>{{ $reservation->schedule->toStation->code_name }}</p>
                                            <p>{{ $toStation }}</p>
                                        </div>
                                    </div>



                                    <div class="row">
                                        <div class="col-md-4 text-center">
                                            <p>Nama Pemesan</p>
                                            <p>{{ $reservation->user->name }}</p>
                                        </div>
                                        <div class="col-md-4 text-center">
                                            <p>Tipe Bus</p>
                                            <p>{{ $reservation->schedule->bus->class }}</p>
                                        </div>
                                        <div class="col-md-4 text-center">
                                            <p>Plat Nomor Otobus</p>
                                            <p>{{ $reservation->schedule->bus->license_plate_number }}</p>
                                        </div>
                                    </div>



                                    <div class="row">
                                        <div class="col-md-12 text-center">
                                            <p>Jam Berangkat</p>
                                            <p>{{ $reservation->schedule->time_start }}</p>
                                        </div>
                                    </div>



                                    <div class="row">
                                        <div class="col-md-6 text-center">
                                            <p>Jumlah Tiket</p>
                                            {{ $reservation->tickets_booked }}
                                        </div>
                                        <div class="col-md-6 text-center">
                                            <p>Harga</p>
                                            <p>Rp. {{ number_format($reservation->total_price) }}</p>
                                        </div>
                                    </div>


                                    <div class="row mt-4">
                                        <div class="col-md-12 text-center">
                                            <canvas id="barcodeCanvas"></canvas>
                                        </div>
                                    </div>
                                    <div class="row mt-4">
                                        <div class="col-md-12 text-center">
                                            <button id="printButton" class="btn btn-primary">Print</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script untuk printThis -->
@endsection
