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
                                    @if (!auth()->user()->hasRole('Admin'))
                                        <form action="{{ route('transits.index') }}" method="GET" class="form-inline">
                                            <div class="form-group mr-2">
                                                <label for="terminal_id" class="mr-2">Terminal</label>
                                                <select name="terminal_id" id="terminal_id" class="form-control">
                                                    <option value="">Pilih Terminal</option>
                                                    @php
                                                        $displayedStations = [];
                                                        $stations = [];
                                                        if (auth()->user()->hasRole('Root')) {
                                                            $stations = \App\Models\BusStation::pluck('id')->toArray();
                                                        } else {
                                                            $stations = $upt_ids;
                                                        }
                                                    @endphp
                                                    @foreach ($stations as $stationId)
                                                        @php
                                                            $station = \App\Models\BusStation::find($stationId);
                                                        @endphp
                                                        @if ($station && !in_array($station->id, $displayedStations))
                                                            <option value="{{ $station->id }}"
                                                                {{ request('terminal_id') == $station->id ? 'selected' : '' }}>
                                                                {{ $station->name }}
                                                            </option>
                                                            @php $displayedStations[] = $station->id; @endphp
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Cari</button>
                                        </form>
                                    @endif

                                    <div class="d-flex">
                                        <a href="{{ route('transits.index') }}" id="refreshPage"
                                            class="btn btn-outline-info mr-2" data-toggle="tooltip" data-placement="top"
                                            title="Segarkan">
                                            <i class="fas fa-sync-alt mr-1"></i>
                                        </a>
                                        <button id="exportToExcel" class="btn btn-outline-primary" data-toggle="tooltip"
                                            data-placement="top" title="Cetak">
                                            <i class="fas fa-file-excel"></i> Cetak
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div id="uptTable" class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr class="text-center">
                                            <th>No</th>
                                            <th>Nama Bus</th>
                                            <th>Terminal Berangkat</th>
                                            <th>Terminal Tujuan</th>
                                            <th>Penumpang Naik</th>
                                            <th>Penumpang Turun</th>
                                            <th>Tanggal Dibuat</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (auth()->user()->hasRole('Admin') || request()->filled('terminal_id'))
                                            @if ($transits->isEmpty())
                                                <tr>
                                                    <td colspan="7" class="text-center">Data kosong atau tidak ada data
                                                    </td>
                                                </tr>
                                            @else
                                                @php
                                                    $displayedBuses = [];
                                                    $index = 1;
                                                @endphp
                                                @foreach ($transits as $transit)
                                                    @php
                                                        $busKey =
                                                            $transit->schedule->bus->name .
                                                            '-' .
                                                            $transit->schedule->bus->license_plate_number;
                                                    @endphp
                                                    @if (!in_array($busKey, $displayedBuses))
                                                        <tr class="text-center" id="upt_ids{{ $transit->id }}">
                                                            <td>{{ $index }}</td>
                                                            <td>{{ $transit->schedule->bus->name }}
                                                                ({{ $transit->schedule->bus->license_plate_number }})
                                                            </td>
                                                            <td>{{ $transit->schedule->fromStation->name }}</td>
                                                            <td>{{ $transit->schedule->toStation->name }}</td>
                                                            <td>{{ $transit->passengers_on > 0 ? $transit->passengers_on : '-' }}
                                                            </td>
                                                            <td>{{ $transit->passengers_off > 0 ? $transit->passengers_off : '-' }}
                                                            </td>
                                                            <td>{{ $transit->date_departure }}</td>
                                                        </tr>
                                                        @php
                                                            $displayedBuses[] = $busKey;
                                                            $index++;
                                                        @endphp
                                                    @endif
                                                @endforeach
                                            @endif
                                        @else
                                            <tr>
                                                <td colspan="7" class="text-center">Harap Pilih Terminal Terlebih Dahulu
                                                </td>
                                            </tr>
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
