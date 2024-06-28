@extends('layouts/main')

@section('container')
    <div class="lime-container">
        <div class="lime-body">
            <div class="container">
                <div class="row">
                    <div class="col-xl">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Pemesanan Tiket</h5>
                                <div class="d-flex justify-content-between align-items-start">
                                    <p>Isi data dengan lengkap dan tepat</p>
                                    <div class="alert alert-info mb-0" role="alert">
                                        <strong>Kursi Tersedia:</strong> <span
                                            id="available-chairs">{{ $availableChairs }}</span>
                                    </div>
                                </div>
                                <form method="POST" action="{{ route('reservations.store') }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="schedule_id" value="{{ $scheduleId }}">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="phone_number">Nomor Handphone</label>
                                                <div class="input-group">
                                                    <input type="text"
                                                        class="form-control @error('phone_number') is-invalid @enderror"
                                                        name="phone_number" id="phone_number" placeholder="Nomor Handphone"
                                                        required value="{{ old('phone_number') }}" minlength="10"
                                                        maxlength="13">
                                                    <div class="input-group-append">
                                                        <button class="btn btn-outline-secondary" type="button"
                                                            id="search_by_phone">Cari</button>
                                                    </div>
                                                    @error('phone_number')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="name">Nama</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="name" id="name"
                                                        placeholder="Masukkan Nama" required
                                                        value="{{ old('name') ?: (isset($userData) ? $userData->name : '') }}">
                                                    <div class="input-group-append">
                                                        <button class="btn btn-outline-secondary" type="button"
                                                            id="search_by_name">Cari</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="address">Alamat</label>
                                                <input type="text" class="form-control" name="address" id="address"
                                                    placeholder="Alamat" required
                                                    value="{{ old('address') ?: (isset($userData) ? $userData->address : '') }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="from_station">Terminal Berangkat</label>
                                                <input type="text" class="form-control" name="from_station"
                                                    id="from_station" placeholder="From Station" required
                                                    value="{{ $fromStation }}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="to_station">Terminal Tujuan</label>
                                                <input type="text" class="form-control" name="to_station" id="to_station"
                                                    placeholder="To Station" required value="{{ $toStation }}" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="tickets_booked">Jumlah Tiket</label>
                                                <input type="number" class="form-control" name="tickets_booked"
                                                    id="tickets_booked" placeholder="Jumlah Tiket" required
                                                    value="{{ old('tickets_booked') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="total_price">Total Harga</label>
                                                <input type="text" class="form-control" name="total_price"
                                                    id="total_price" placeholder="Total Harga" required
                                                    value="{{ old('total_price') }}" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Button trigger modal -->
                                    <button type="button" class="btn btn-primary float-left mr-2" data-toggle="modal"
                                        data-target="#exampleModal">
                                        Tambah
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
                                                    <h5 class="modal-title" id="exampleModalLabel">Konfirmasi Penambahan
                                                        Data</h5>
                                                </div>
                                                <div class="modal-body">
                                                    Apakah Anda yakin ingin menambahkan data ini?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary">Tambah</button>
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
                                                    <a href="{{ route('reservations.index') }}"
                                                        class="btn btn-primary">Ya, Kembali</a>
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
