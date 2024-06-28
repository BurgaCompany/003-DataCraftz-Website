@extends('layouts/main')

@section('container')
    <div class="lime-container">
        <div class="lime-body">
            <div class="container">
                <div class="row">
                    <div class="col-xl">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Detail Terminal</h5>
                                <form method="POST" action="{{ route('bus_stations.update', $busStation->id) }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="row">


                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="name">Nama</label>
                                                <input type="text" class="form-control" name="name" id="name"
                                                    placeholder="Masukkan Nama" required disabled
                                                    value="{{ old('name', $busStation->name) }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="code_name">Kode Nama</label>
                                                <input type="text" class="form-control" name="code_name" id="code_name"
                                                    placeholder="Masukkan Kode Nama" required disabled
                                                    value="{{ old('code_name', $busStation->code_name) }}">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="city">Kota</label>
                                                <input type="text" class="form-control" name="city" id="city"
                                                    placeholder="Masukkan Kota" required disabled
                                                    value="{{ old('city', $busStation->city) }}">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="address">Alamat</label>
                                                <textarea type="text" class="form-control" name="address" id="address" placeholder="Alamat" required disabled>{{ old('address', $busStation->address) }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="admins">Admin</label>
                                                <select class="js-states form-control" name="id_admin[]" id="admins"
                                                    style="width: 100%" multiple="multiple" disabled>
                                                    @if ($admins->isEmpty())
                                                        <option disabled selected>Belum Ada Admin</option>
                                                    @endif
                                                    @foreach ($admins as $admin)
                                                        @if (in_array($admin->id, $selectedAdmins))
                                                            <option value="{{ $admin->id }}" selected>{{ $admin->name }}
                                                            </option>
                                                        @else
                                                            <option value="{{ $admin->id }}">{{ $admin->name }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <!-- Leaflet map container -->
                                        <div class="col-md-12">
                                            <div id="map" style="height: 400px;"></div>
                                        </div>
                                    </div>
                                    <br>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="latitude">Latitude</label>
                                                <input type="text" class="form-control" name="latitude" id="latitude"
                                                    placeholder="Latitude" readonly required
                                                    value="{{ old('latitude', $busStation->latitude) }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="longitude">Longitude</label>
                                                <input type="text" class="form-control" name="longitude" id="longitude"
                                                    placeholder="Longitude" readonly required
                                                    value="{{ old('longitude', $busStation->longitude) }}">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Button trigger modal -->
                                    <button id="saveButton" type="button" class="btn btn-success float-left mr-2"
                                        data-toggle="modal" data-target="#exampleModal" style="display: none;">
                                        Simpan Perubahan
                                    </button>

                                    <!-- Button trigger modal -->
                                    <button type="button" class="btn btn-secondary float-left" data-toggle="modal"
                                        data-target="#exampleModalback">
                                        Kembali
                                    </button>
                                    <!-- Modal -->
                                    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Konfirmasi Perubahan
                                                        Data</h5>
                                                </div>
                                                <div class="modal-body">
                                                    Apakah Anda yakin ingin mengubah data ini?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-success">Simpan
                                                        Perubahan</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Modal -->
                                    <div class="modal fade" id="exampleModalback" tabindex="-1" role="dialog"
                                        aria-labelledby="exampleModalLabelback" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabelback">Konfirmasi Kembali
                                                    </h5>
                                                </div>
                                                <div class="modal-body">
                                                    Apakah Anda yakin ingin kembali?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Batal</button>
                                                    <a href="{{ route('bus_stations.index') }}"
                                                        class="btn btn-primary">Ya, Kembali</a>
                                                </div>
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
@endsection
