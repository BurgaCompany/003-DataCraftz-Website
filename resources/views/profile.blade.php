@extends('layouts/main')

@section('container')
<div class="lime-container">
    <div class="lime-body">
        <div class="container">
            <div class="row">
                <div class="col-xl">
                    <div class="card">
                        <div class="card-body">

                            <form method="POST" action="{{ route('profile.update', $userProfile->id) }}" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="row align-items-center">
                                    <div class="col-md-3 mt-md-0 mt-3">
                                        <div class="profile-image-container">
                                            <form method="post">
                                                <label for="upload_image">
                                                    <img src="{{ asset('storage/' . $userProfile->images) }}" alt="Avatar" class="profile-image" id="uploaded_image" style="width: 100%;">
                                                    <div class="overlay">
                                                        <div class="text">Klik untuk mengubah gambar</div>
                                                    </div>
                                                    <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*" id="upload_image" style="display:none">
                                                </label>
                                            </form>
                                        </div>
                                    </div>

                                    
                                    <!-- Modal -->
                                    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true" data-url="{{ route('profile.update-image') }}">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Potong Gambar Sesuai Keinginan</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="img-container">
                                                        <div class="row">
                                                            <div class="col-md-8">
                                                                <img src="" id="sample_image" class="sample-image">
                                                            </div>
                                                            <div class="col-md-4">
                                                                <!-- Preview container -->
                                                                <div class="preview"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" id="crop" class="btn btn-primary">Simpan</button>
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-9 mt-md-3">
                                        <div class="form-group">
                                            <label for="name">Nama</label>
                                            <input type="text" class="form-control" name="name" id="name" placeholder="Masukkan Nama" value="{{ $userProfile->name }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" id="email" placeholder="Masukkan Email" value="{{ old('email', $userProfile->email) }}" required>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="invalid-feedback" id="email-error-message" style="display: none;">
                                                Email harus berakhiran @gmail.com
                                            </div>
                                        </div>
                                    </div>
                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="password">Password</label>
                                            <div class="input-group">
                                                <input type="password" class="form-control" name="password" id="password" placeholder="Masukkan Password"  minlength="8">
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
                                                <input type="password" class="form-control" name="confirm_password" id="confirm_password" placeholder="Konfirmasi Password"  minlength="8">
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
                                
                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="address">Alamat</label>
                                            <input type="text" class="form-control" name="address" id="address" placeholder="Alamat" value="{{ $userProfile->address }}">
                                        </div>
                                    </div>
                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="gender">Jenis Kelamin</label>
                                            <select class="js-states form-control" name="gender" id="gender" style="width: 100%">
                                                @foreach ($genders as $key => $value)
                                                <option value="{{ $key }}" {{ $userProfile->gender == $key ? 'selected' : '' }}>{{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="phone_number">Nomor Handphone</label>
                                            <input type="text" class="form-control @error('phone_number') is-invalid @enderror" name="phone_number" id="phone_number" placeholder="Nomor Handphone" value="{{ old('phone_number', $userProfile->phone_number) }}" required minlength="10" maxlength="13">
                                            @error('phone_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        </div>
                                    </div>
                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="level">Hak Akses</label>
                                            <select class="js-states form-control" name="level" id="level" style="width: 100%" disabled>
                                                @foreach ($userRoles as $role)
                                                <option value="{{ $role->id }}" @if($role->name === 'Admin') selected @endif>{{ $role->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <button type="button" class="btn btn-primary float-left mr-2" data-toggle="modal" data-target="#exampleModal">
                                        <i class="fas fa-edit"></i> Ubah
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

                                </div>
                            </form>

                        </div>
                    </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
