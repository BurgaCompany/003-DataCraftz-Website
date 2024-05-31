@extends('layouts/main')

@section('container')
<div class="lime-container">
    <div class="lime-body">
        <div class="container">
            <div class="row">
                <div class="col-xl">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Detail Kondektur Bus</h5>
                            <form method="POST" action="" enctype="multipart/form-data">
                                @csrf
                                <div class="row align-items-center">
                                    <div class="col-md-3 mt-md-0 mt-3"> <!-- Tambahkan mt-md-0 dan mt-3 -->
                                        @if($bus_conductor->images)
                                        <div class="profile-image-container">
                                            <img src="{{ asset('storage/' . $bus_conductor->images) }}" alt="Avatar" class="profile-image">
                                        </div>
                                        @endif
                                    </div>
                                    <div class="col-md-9 mt-md-3"> <!-- Tambahkan mt-md-3 -->
                                        <div class="form-group">
                                            <label for="name">Nama</label>
                                            <input type="text" class="form-control" name="name" id="name" placeholder="Masukkan Nama" value="{{ $bus_conductor->name }}" disabled>
                                        </div>
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="email" class="form-control" name="email" id="email" placeholder="Masukkan Email" value="{{ $bus_conductor->email }}" disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="password">Password</label>
                                            <div class="input-group">
                                                <input type="password" class="form-control" name="password" id="password" placeholder="Masukkan Password" value ="**********" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="confirm_password">Konfirmasi Password</label>
                                            <div class="input-group">
                                                <input type="password" class="form-control" name="confirm_password" id="confirm_password" placeholder="Konfirmasi Password" value ="**********" disabled>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="address">Alamat</label>
                                            <input type="text" class="form-control" name="address" id="address" placeholder="Alamat" value="{{ $bus_conductor->address }}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="gender">Jenis Kelamin</label>
                                            <select class="js-states form-control" name="gender" id="gender" style="width: 100%" disabled>
                                                @foreach ($genders as $key => $value)
                                                    <option value="{{ $key }}" {{ $bus_conductor->gender == $key ? 'selected' : '' }}>{{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="phone_number">Nomor Handphone</label>
                                            <input type="text" class="form-control" name="phone_number" id="phone_number" placeholder="Nomor Handphone" value="{{ $bus_conductor->phone_number }}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="level">Hak Akses</label>
                                            <select class="js-states form-control" name="level" id="level" style="width: 100%" disabled>
                                                @foreach ($roles as $role)
                                                    <option value="{{ $role->id }}" @if($role->name === 'Bus_Condectur') selected @endif>{{ $role->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <a href="{{ route('bus_conductors.index') }}" class="btn btn-secondary float-left">Kembali</a>
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
