@extends('layouts/main')

@section('container')
<div class="lime-container">
    <div class="lime-body">
        <div class="container">
            <div class="row">
                <div class="col-xl">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Ubah Akun Bank</h5>
                            <p>Ubah data sesuai kebutuhan</p>
                            <form method="POST" action="{{ route('banks.update', $bank->id) }}" enctype="multipart/form-data">
                                @csrf    
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="account_name">Nama Pemilik Rekening</label>
                                            <input type="text" class="form-control" name="account_name" id="account_name" placeholder="Masukkan Nama" required value="{{ old('account_name' , $bank->account_name) }}">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="type_bank">Jenis Bank</label>
                                            <input type="text" class="form-control" name="type_bank" id="type_bank" placeholder="Masukkan Jenis Bank" required value="{{ old('type_bank', $bank->type_bank) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="account_number">Nomor Rekening</label>
                                            <input type="text" class="form-control" name="account_number" id="account_number" placeholder="Masukkan Nomor Rekening" required value="{{ old('account_number', $bank->account_number) }}" maxlength="20">
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
                                                <a href="{{ route('banks.index') }}" class="btn btn-primary">Ya, Kembali</a>
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
