@extends('layouts.main')

@section('container')
    <div class="lime-container">
        <div class="lime-body">
            <div class="container">
                <div class="row">
                    <div class="col-xl">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h2 class="card-title" style="font-size: 20px;">Detail Transfer</h2>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="amount">Jumlah Setor</label>
                                            <input type="number" class="form-control" id="amount" name="amount"
                                                value="{{ $deposit->amount }}" readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="account_name">Nama Rekening</label>
                                            <input type="text" class="form-control" id="account_name" name="account_name"
                                                value="{{ $bank->account_name }}" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="type_bank">Tipe Bank</label>
                                            <input type="text" class="form-control" id="type_bank" name="type_bank"
                                                value="{{ $bank->type_bank }}" readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="account_number">Nomor Rekening</label>
                                            <input type="text" class="form-control" id="account_number"
                                                name="account_number" value="{{ $bank->account_number }}" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <h5 class="mb-3">Bukti Transfer</h5>
                                        @php
                                            $imagePath = $deposit->images ? asset('storage/' . $deposit->images) : '';
                                        @endphp
                                        @if ($imagePath)
                                            <div class="d-flex justify-content-center">
                                                <img src="{{ $imagePath }}" class="img-fluid" alt="Bukti Transfer"
                                                    style="max-width: 300px;">
                                            </div>
                                        @else
                                            <p class="text-muted">Tidak ada gambar yang diunggah</p>
                                        @endif
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
