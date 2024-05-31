@extends('layouts/main')

@section('container')
<div class="lime-container">
    <div class="lime-body">
        <div class="container">
            <div class="row">
                <div class="col-xl">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Ubah Bus</h5>
                            <p>Ubah data sesuai kebutuhan</p>
                            <form method="POST" action="{{ route('busses.update', $bus->id) }}" enctype="multipart/form-data">
                                @csrf    
                                @method('PUT')
                            
                                <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Nama</label>
                                        <input type="text" class="form-control" name="name" id="name" placeholder="Masukkan Nama" required value="{{ old('name', $bus->name) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="license_plate_number">Nomor Polisi Kendaraaan</label>
                                        <input type="text" class="form-control @error('license_plate_number') is-invalid @enderror"  name="license_plate_number" id="license_plate_number" placeholder="Masukkan Nomor Polisi Kendaraan" required  maxlength="9" value="{{ old('license_plate_number', $bus->license_plate_number) }}" oninput="this.value = this.value.replace(/\s/g, '');">
                                        @error('license_plate_number')
                                                <div class="invalid-feedback">Nomor Plat Kendaraan sudah terpakai</div>
                                            @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="chair">Kursi</label>
                                        <input oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" type = "number" maxlength = "2" class="form-control" name="chair" id="chair"  placeholder="Masukkan Kursi" required value="{{ $bus->chair ?? old('chair') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="class">Kelas</label>
                                        <select class="js-states form-control" name="class" id="class" style="width: 100%">
                                            <option value="ekonomi" {{ old('class', $bus->class) == 'ekonomi' ? 'selected' : '' }}>Ekonomi</option>
                                            <option value="bisnis" {{ old('class', $bus->class) == 'bisnis' ? 'selected' : '' }}>Bisnis</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select class="js-states form-control" name="status" id="status" style="width: 100%" onchange="showKeterangan()" required>
                                            <option value="1" {{ old('status', $bus->status) == '1' ? 'selected' : '' }}>Belum Berangkat</option>
                                            <option value="2" {{ old('status', $bus->status) == '2' ? 'selected' : '' }}>Bersedia Berangkat</option>
                                            <option value="3" {{ old('status', $bus->status) == '3' ? 'selected' : '' }}>Berangkat</option>
                                            <option value="4" {{ old('status', $bus->status) == '4' ? 'selected' : '' }}>Terkendala</option>
                                            <option value="5" {{ old('status', $bus->status) == '5' ? 'selected' : '' }}>Tiba di Tujuan</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="image">Avatar</label>
                                            <div class="input-group">
                                                <input class="form-control" type="file" name="image" id="image">
                                            @if(old('image'))
                                                <span class="input-group-text">{{ old('image') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                                
                            <div class="row">
                                <div class="col-md-12" id="keteranganField" style="{{ old('status', $bus->status) == '4' ? 'display:block;' : 'display:none;' }}">
                                    <div class="form-group">
                                        <label for="keterangan">Keterangan</label>
                                        <textarea class="form-control" name="keterangan" id="keterangan" placeholder="Masukkan Keterangan">{{ old('keterangan', $bus->information) }}</textarea>
                                    </div>
                                </div>
                            </div>
                                <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="drivers">Sopir</label>
                                        <select class="js-states form-control" name="drivers[]" id="drivers" style="width: 100%"  title="Pilih satu atau lebih sopir">
                                            @if($drivers->isEmpty())
                                            <option disabled selected>Belum Ada Sopir</option>
                                            @endif
                                            @foreach($drivers as $driver)
                                            @if(in_array($driver->id, $assignedDrivers))
                                            <option value="{{ $driver->id }}" selected>{{ $driver->name }}</option>
                                            @else
                                            <option value="{{ $driver->id }}">{{ $driver->name }}</option>
                                            @endif
                                            @endforeach
                                        </select>
                                        @if (Auth::user()->hasRole('Upt'))
                                        <div class="input-group-append">
                                            <span class="ml-2 text-primary" style="font-size: 12px; cursor: pointer;" onclick="location.href='{{ route('drivers.create') }}'">
                                                klik disini untuk menambah Sopir
                                            </span>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="bus_conductors">Kondektur</label>
                                        <select class="js-states form-control" name="bus_conductors[]" id="bus_conductors" style="width: 100%"  title="Pilih satu atau lebih kondektur">
                                            @if($bus_conductors->isEmpty())
                                            <option disabled selected>Belum Ada Kondektur</option>
                                            @endif
                                            @foreach($bus_conductors as $bus_conductor)
                                            @if(in_array($bus_conductor->id, $assignedBusConductors))
                                            <option value="{{ $bus_conductor->id }}" selected>{{ $bus_conductor->name }}</option>
                                            @else
                                            <option value="{{ $bus_conductor->id }}">{{ $bus_conductor->name }}</option>
                                            @endif
                                            @endforeach
                                        </select>
                                        @if (Auth::user()->hasRole('Upt'))
                                        <div class="input-group-append">
                                            <span class="ml-2 text-primary" style="font-size: 12px; cursor: pointer;" onclick="location.href='{{ route('bus_conductors.create') }}'">
                                                klik disini untuk menambah Kondektur
                                            </span>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- <-- Modal Peringatan jika Sopir atau Konduktor Belum Dipilih --> --}}
                            <div class="modal fade" id="exampleModalInvalid" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabelInvalid" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabelInvalid">Peringatan</h5>
                                        </div>
                                        <div class="modal-body">
                                            <p>Anda harus memilih setidaknya satu sopir dan satu konduktor sebelum melakukan perubahan.</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                                 
                            <!-- Button trigger modal -->
                                 <button type="button" class="btn btn-primary float-left mr-2" data-toggle="modal" data-target="#exampleModal">
                                    Ubah
                                </button>
                                <!-- Button trigger modal -->
                                <button type="button" class="btn btn-secondary float-left" data-toggle="modal" data-target="#exampleModalback">
                                    Kembali
                                </button>

                                <!-- Modal -->
                                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Konfirmasi Perubahan Data</h5>
                                        </div>
                                        <div class="modal-body">
                                        Apakah Anda yakin ingin mengubah data ini?
                                        </div>
                                        <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary">Ubah</button>
                                        </div>
                                    </div>
                                    </div>
                                </div>

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
                                                <a href="{{ route('busses.index') }}" class="btn btn-primary">Ya, Kembali</a>
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
