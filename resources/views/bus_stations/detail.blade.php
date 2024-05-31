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
                            <div class="clearfix">
                                <a href="#" id="editButton" class="btn btn-primary float-right mb-3 mr-2" data-toggle="tooltip" data-placement="top" title="Ubah">
                                    <i class="fas fa-edit"></i> Ubah
                                </a>
                                <button id="cancelButton" class="btn btn-danger float-right mb-3" style="display: none;">Batal</button>
                            </div>
                            <form method="POST" action="{{ route('bus_stations.update', $busStation->id) }}" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="name">Nama</label>
                                        <input type="text" class="form-control" name="name" id="name" placeholder="Masukkan Nama" required value="{{ old('name', $busStation->name) }}" disabled>
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
                                        <select class="js-states form-control" name="id_admin[]" id="admins" style="width: 100%" multiple="multiple">
                                            @if($admins->isEmpty())
                                            <option disabled selected>Belum Ada Admin</option>
                                        @endif
                                            @foreach($admins as $admin)
                                                @if(in_array($admin->id, $selectedAdmins))
                                                    <option value="{{ $admin->id }}" selected>{{ $admin->name }}</option>
                                                @else
                                                    <option value="{{ $admin->id }}">{{ $admin->name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <div class="input-group-append">
                                            <span class="ml-2 text-primary" style="font-size: 12px; cursor: pointer;" onclick="location.href='{{ route('admins.create') }}'">
                                                klik disini untuk menambah admin
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Button trigger modal -->
                                <button id="saveButton" type="button" class="btn btn-success float-left mr-2" data-toggle="modal" data-target="#exampleModal" style="display: none;">
                                    Simpan Perubahan
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
                                        <button type="submit" class="btn btn-success">Simpan Perubahan</button>
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
                                                <a href="{{ route('bus_stations.index') }}" class="btn btn-primary">Ya, Kembali</a>
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
