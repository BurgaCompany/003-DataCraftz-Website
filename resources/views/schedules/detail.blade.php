@extends('layouts/main')

@section('container')
<div class="lime-container">
    <div class="lime-body">
        <div class="container">
            <div class="row">
                <div class="col-xl">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Detail Jadwal</h5>
                            <form method="POST" action="" enctype="multipart/form-data">
                                @csrf  
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="Bus">Bus</label>
                                            <select class="js-states form-control" name="busses[]" id="busses" style="width: 100%" disabled>
                                                @foreach($busses as $buss)
                                                <option value="{{ $buss->id }}" {{ $schedules->bus_id == $buss->id ? 'selected' : '' }}>{{ $buss->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="departureTerminal">Terminal Berangkat</label>
                                            <select class="js-states form-control" name="frombusStations[]" id="departureTerminal" style="width: 100%" disabled>
                                                @foreach($busStations as $busStation)
                                                <option value="{{ $busStation->id }}" {{ $schedules->from_station_id == $busStation->id ? 'selected' : '' }}>{{ $busStation->name }}</option>
                                                @endforeach
                                            </select>
                                            <div id="terminal-error-message" class="invalid-feedback" style="display: none;">
                                                Terminal berangkat dan tujuan tidak boleh sama
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="arrivalTerminal">Terminal Tujuan</label>
                                            <select class="js-states form-control" name="tobusStations[]" id="arrivalTerminal" style="width: 100%" disabled>
                                                @foreach($busStations as $busStation)
                                                <option value="{{ $busStation->id }}" {{ $schedules->to_station_id == $busStation->id ? 'selected' : '' }}>{{ $busStation->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="price">Harga (Rupiah)</label>
                                            <input type="number" class="form-control" name="price" id="price" required value="{{ $schedules->price }}" disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="time_start">Jam Berangkat</label>
                                            <input type="time" class="form-control" name="time_start" id="time_start" required value="{{ $schedules->time_start }}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="pwt">Perkiraan Waktu Tempuh</label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" name="hours" id="hours" placeholder="Jam" required value="{{ floor($schedules->pwt / 60) }}" disabled>
                                                <div class="input-group-append">
                                                    <span class="input-group-text">Jam</span>
                                                </div>
                                                <input type="number" class="form-control" name="minutes" id="minutes" placeholder="Menit" required value="{{ $schedules->pwt % 60 }}" min="0" max="59" disabled>
                                                <div class="input-group-append">
                                                    <span class="input-group-text">Menit</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Button trigger modal -->
                                <button type="button" class="btn btn-secondary float-left" data-toggle="modal" data-target="#exampleModalback">
                                    Kembali
                                </button>
                                <!-- Modal -->
                                <div class="modal fade" id="exampleModalback" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabelback" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabelback">Konfirmasi Kembali</h5>
                                            </div>
                                            <div class="modal-body">
                                                Apakah Anda yakin ingin kembali?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                <a href="{{ route('schedules.index') }}" class="btn btn-primary">Ya, Kembali</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection