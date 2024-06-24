@extends('layouts.main')

@section('container')
<div class="lime-container">
    <div class="lime-body">
        <div class="container">
            <div class="row">
                <div class="col-xl">
                    <div class="card">
                        <div class="card-body">
                            <h2 class="card-title mb-4" style="font-size: 20px;">Tabel Data Transit</h2>
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <!-- Form pencarian terminal -->
                                <form action="{{ route('transits.index') }}" method="GET" class="form-inline">
                                    <div class="form-group mr-2">
                                        <label for="terminal_id" class="mr-2">Terminal</label>
                                        <select name="terminal_id" id="terminal_id" class="form-control">
                                            <option value="">Pilih Terminal</option>
                                            @php
                                                $displayedStations = []; // Inisialisasi array untuk menyimpan stasiun yang sudah ditampilkan
                                            @endphp
                                            @foreach($transits as $transit)
                                                @if (!in_array($transit->schedule->fromStation->id, $displayedStations))
                                                    <option value="{{ $transit->schedule->fromStation->id }}" {{ request('terminal_id') == $transit->schedule->fromStation->id ? 'selected' : '' }}>
                                                        {{ $transit->schedule->fromStation->name }}
                                                    </option>
                                                    @php $displayedStations[] = $transit->schedule->fromStation->id; @endphp
                                                @endif

                                                @if (!in_array($transit->schedule->toStation->id, $displayedStations))
                                                    <option value="{{ $transit->schedule->toStation->id }}" {{ request('terminal_id') == $transit->schedule->toStation->id ? 'selected' : '' }}>
                                                        {{ $transit->schedule->toStation->name }}
                                                    </option>
                                                    @php $displayedStations[] = $transit->schedule->toStation->id; @endphp
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Cari</button>
                                </form>

                                <div class="d-flex">
                                    <a href="{{ route('transits.index') }}" id="refreshPage" class="btn btn-outline-info mr-2" data-toggle="tooltip" data-placement="top" title="Segarkan">
                                        <i class="fas fa-sync-alt mr-1"></i>
                                    </a>
                                    <button id="exportToExcel" class="btn btn-outline-primary" data-toggle="tooltip" data-placement="top" title="Cetak">
                                        <i class="fas fa-file-excel"></i> Cetak
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div id="uptTable" class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr class="text-center">
                                        <th>ID</th>
                                        <th>Nama Bus</th>
                                        <th>Terminal Berangkat</th>
                                        <th>Terminal Tujuan</th>
                                        <th>Penumpang Naik</th>
                                        <th>Penumpang Turun</th>
                                        <th>Tanggal Dibuat</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($transits->isEmpty())
                                    <tr>
                                        <td colspan="9" class="text-center">Data kosong atau tidak ada data</td>
                                    </tr>
                                    @else
                                    @foreach($transits as $transit)
                                        <tr class="text-center" id="upt_ids{{ $transit->id }}">
                                            <td>{{ $transit->id }}</td>
                                            <td>{{ $transit->bus->name }} ({{ $transit->bus->license_plate_number }})</td>
                                            <td>{{ $transit->schedule->fromStation->name }}</td>
                                            <td>{{ $transit->schedule->toStation->name }}</td>
                                            <td>
                                                @php
                                                $passengersOn = 0;
                                                @endphp
                                                @foreach($transits_on as $transit_on)
                                                    @if($transit_on->schedule_id == $transit->schedule_id)
                                                        @php
                                                        $passengersOn += $transit_on->passengers_on;
                                                        @endphp
                                                    @endif
                                                @endforeach
                                                {{ $passengersOn > 0 ? $passengersOn : '-' }}
                                            </td>
                                            
                                            <td>
                                                @php
                                                $passengersOff = 0;
                                                @endphp
                                                @foreach($transits_off as $transit_off)
                                                    @if($transit_off->schedule_id == $transit->schedule_id)
                                                        @php
                                                        $passengersOff += $transit_off->passengers_off;
                                                        @endphp
                                                    @endif
                                                @endforeach
                                                {{ $passengersOff > 0 ? $passengersOff : '-' }}
                                            </td>
                                            
                                            <td>{{ $transit->created_at }}</td>
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
@endsection
