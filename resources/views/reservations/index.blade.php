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
                                            <h2 class="card-title" style="font-size: 20px;">Tabel Data Pemesanan</h2>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mb-4">
                                            <button id="exportToExcel" class="btn btn-outline-primary" data-toggle="tooltip"
                                                data-placement="top" title="Cetak">
                                                <i class="fas fa-file-excel"></i> Cetak
                                            </button>
                                            <div class="alert alert-info mb-0" role="alert">
                                                <strong>Saldo:</strong> <span
                                                    id="available-chairs">{{ $totalSaldo }}</span>
                                            </div>
                                        </div>

                                        <!-- Form input untuk setor tunai ke UPT -->
                                        @if (!auth()->user()->hasAnyRole(['PO', 'Root']))
                                            <form action="{{ route('reservations.depo_up') }}" method="POST"
                                                enctype="multipart/form-data">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="amount">Jumlah Setor</label>
                                                            <input type="number" class="form-control" id="amount"
                                                                name="amount" value="{{ $totalSaldo }}"
                                                                placeholder="Masukkan Jumlah Setor" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="bank_account">Akun Bank</label>
                                                            <select class="form-control" id="bank_account"
                                                                name="bank_account" required>
                                                                <option value="">Pilih Akun Bank</option>
                                                                @foreach ($banks as $bank)
                                                                    <option value="{{ $bank->id }}">
                                                                        {{ $bank->user->name }} -
                                                                        {{ $bank->account_number }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="image">Unggah Bukti</label>
                                                            <input type="file" class="form-control" id="image"
                                                                name="image" required>
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
                                        @endif

                                        <!-- Form input untuk request transfer jika role adalah 'PO' -->
                                        @if (auth()->user()->hasRole('PO'))
                                            <form action="{{ route('reservations.depo_up') }}" method="POST">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="amount">Request Transfer</label>
                                                            <input type="number" class="form-control" id="amount"
                                                                name="amount" placeholder="Masukkan Jumlah Transfer"
                                                                value="{{ $amountCount }}" readonly>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="bank_account">Akun Bank Tujuan</label>
                                                            <select class="form-control" id="bank_account"
                                                                name="bank_account" required>
                                                                <option value="">Pilih Akun Bank Tujuan</option>
                                                                @foreach ($banks as $bank)
                                                                    <option value="{{ $bank->id }}">
                                                                        {{ $bank->user->name }} -
                                                                        {{ $bank->account_number }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <button type="submit" class="btn btn-success"
                                                                style="margin-top: 32px;">Request Transfer</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        @endif

                                        <div id="uptTable" class="table-responsive mt-4">
                                            <table class="table table-striped table-bordered">
                                                <thead>
                                                    <tr class="text-center">
                                                        <th>Id Pemesanan</th>
                                                        <th>Nama Bus</th>
                                                        <th>Perusahaan Otobus</th>
                                                        <th>Terminal Berangkat</th>
                                                        <th>Terminal Tujuan</th>
                                                        <th>Harga</th>
                                                        <th>Tanggal Keberangkatan</th>
                                                        <th>Jam Berangkat</th>
                                                        <th>Status</th>
                                                        <th>Nama</th>
                                                        <th>Jumlah Tiket</th>
                                                        <th>Metode Pembayaran</th>
                                                        <th>Nomor Handphone</th>
                                                        <th>Status Transfer</th>
                                                        <th>Tanggal Dibuat</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if ($reservations->isEmpty())
                                                        <tr>
                                                            <td colspan="14" class="text-center">Data kosong atau tidak
                                                                ada data</td>
                                                        </tr>
                                                    @else
                                                        @foreach ($reservations as $reservation)
                                                            <tr class="text-center" id="upt_ids{{ $reservation->id }}">
                                                                <td>{{ $reservation->id }}</td>
                                                                <td>{{ $reservation->schedule->bus->name }}
                                                                    ({{ $reservation->schedule->bus->license_plate_number }})
                                                                </td>
                                                                <td>{{ $reservation->schedule->bus->po->name }}</td>
                                                                <td>{{ $reservation->schedule->fromStation->name }}</td>
                                                                <td>{{ $reservation->schedule->toStation->name }}</td>
                                                                <td>{{ $reservation->schedule->price }}</td>
                                                                <td>{{ $reservation->date_departure }}</td>
                                                                <td>{{ $reservation->schedule->time_start }}</td>
                                                                <td>
                                                                    @if ($reservation->status == 1)
                                                                        <span class="badge badge-primary"> Belum
                                                                            digunakan</span>
                                                                    @elseif ($reservation->status == 2)
                                                                        <span class="badge badge-warning">Sudah
                                                                            digunakan</span>
                                                                    @endif
                                                                </td>
                                                                <td>{{ $reservation->user->name }}</td>
                                                                <td>{{ $reservation->tickets_booked }}</td>
                                                                <td>{{ $reservation->payment_method }}</td>
                                                                <td>{{ $reservation->user->phone_number }}</td>
                                                                <td>
                                                                    @if ($reservation->deposit_status == 'Done')
                                                                        <span class="badge badge-primary"> Done</span>
                                                                    @elseif ($reservation->deposit_status == 'Pending')
                                                                        <span class="badge badge-warning">Pending</span>
                                                                    @endif
                                                                </td>
                                                                <td>{{ $reservation->created_at }}</td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endsection
