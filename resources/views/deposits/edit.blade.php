@extends('layouts/main')

@section('container')
    <div class="lime-container">
        <div class="lime-body">
            <div class="container">
                <div class="row">
                    <div class="col-xl">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h2 class="card-title" style="font-size: 20px;">Transfer</h2>
                                </div>
                                <form action="{{ route('deposits.update', $deposit->id) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="bank_id" id="bank_id" value="{{ $deposit->bank_id }}">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="amount">Jumlah Setor</label>
                                                <input type="number" class="form-control" id="amount" name="amount"
                                                    value="{{ old('amount', $deposit->amount) }}"
                                                    placeholder="Masukkan Jumlah Setor" readonly>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="account_name">Nama Rekening</label>
                                                <input type="text" class="form-control" id="account_name"
                                                    name="account_name"
                                                    value="{{ old('account_name', $bank->account_name) }}"
                                                    placeholder="Masukkan Nama Rekening" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="type_bank">Tipe Bank</label>
                                                <input type="text" class="form-control" id="type_bank" name="type_bank"
                                                    value="{{ old('type_bank', $bank->type_bank) }}"
                                                    placeholder="Masukkan Tipe Bank" readonly>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="account_number">Nomor Rekening</label>
                                                <input type="text" class="form-control" id="account_number"
                                                    name="account_number"
                                                    value="{{ old('account_number', $bank->account_number) }}"
                                                    placeholder="Masukkan Nomor Rekening" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="image">Unggah Bukti</label>
                                                <input type="file" class="form-control" id="image" name="image"
                                                    required>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary"
                                                    style="margin-top: 32px;">Setor Tunai</button>
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
