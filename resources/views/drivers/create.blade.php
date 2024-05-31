@extends('layouts/main')

@section('container')
<div class="lime-container">
    <div class="lime-body">
        <div class="container">
            <div class="row">
                <div class="col-xl">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Tambah Sopir</h5>
                            <p>Isi data dengan lengkap dan tepat</p>
                            <form method="POST" action="{{ route('drivers.store') }}" enctype="multipart/form-data">
                                @csrf  
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">Nama</label>
                                            <input type="text" class="form-control" name="name" id="name" placeholder="Masukkan Nama" required value="{{ old('name') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="email"  class="form-control @error('email') is-invalid @enderror"  name="email" id="email" placeholder="Masukkan Email" required value="{{ old('email') }}">
                                            @error('email')
                                            <div class="invalid-feedback">  {{ $message }}</div>
                                        @enderror
                                            <div class="invalid-feedback" id="email-error-message" style="display: none;">
                                                Email harus berakhiran @gmail.com
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="password">Password</label>
                                            <div class="input-group">
                                                <input type="password" class="form-control" name="password" id="password" placeholder="Masukkan Password" required value="{{ old('password') }}" minlength="8">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <i id="togglePassword" class="fas fa-eye"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="confirm_password">Konfirmasi Password</label>
                                            <div class="input-group">
                                                <input type="password" class="form-control" name="confirm_password" id="confirm_password" placeholder="Konfirmasi Password" required minlength="8">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <i id="toggleConfirmPassword" class="fas fa-eye"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="invalid-feedback" id="password-feedback" style="display: none;">
                                                Password tidak cocok
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="address">Alamat</label>
                                            <input type="text" class="form-control" name="address" id="address" placeholder="Alamat" required value="{{ old('address') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="gender">Jenis Kelamin</label>
                                            <select class="js-states form-control" name="gender" id="gender" style="width: 100%">
                                                <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Pria</option>
                                                <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Perempuan</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="phone_number">Nomor Handphone</label>
                                            <input type="text" class="form-control @error('phone_number') is-invalid @enderror" name="phone_number" id="phone_number" placeholder="Nomor Handphone" required value="{{ old('phone_number') }}" minlength="10" maxlength="13">
                                            @error('phone_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="level">Hak Akses</label>
                                            <select class="js-states form-control" name="level" id="level" style="width: 100%" disabled>
                                                @foreach ($roles as $role)
                                                    <option value="{{ $role->id }}" @if($role->name === 'Driver') selected @endif>{{ $role->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="image">Avatar</label>
                                    <div class="input-group">
                                        <input class="form-control @error('image') is-invalid @enderror" type="file" name="image" id="image" accept="image/*">
                                        @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    </div> 
                                </div>
                                <!-- Button trigger modal -->
                                <button type="button" class="btn btn-primary float-left mr-2" data-toggle="modal" data-target="#exampleModal">
                                    Tambah
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
                                        <h5 class="modal-title" id="exampleModalLabel">Konfirmasi Penambahan Data</h5>
                                        </div>
                                        <div class="modal-body">
                                        Apakah Anda yakin ingin menambahkan data ini?
                                        </div>
                                        <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary">Tambah</button>
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
                                                <a href="{{ route('drivers.index') }}" class="btn btn-primary">Ya, Kembali</a>
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