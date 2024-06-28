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
                                    <h2 class="card-title" style="font-size: 20px;">Tabel Withdraw</h2>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <button id="exportToExcel" class="btn btn-outline-primary" data-toggle="tooltip"
                                        data-placement="top" title="Cetak">
                                        <i class="fas fa-file-excel"></i> Cetak
                                    </button>
                                </div>
                                <div id="uptTable" class="table-responsive mt-4">
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            <tr class="text-center">
                                                <th>Id</th>
                                                <th>Nama</th>
                                                @if (auth()->user()->hasAnyRole(['Upt', 'Admin']))
                                                    <th>Dari Terminal</th>
                                                @endif
                                                <th>Tujuan Rekening</th>
                                                <th>Tipe</th>
                                                <th>Jumlah</th>
                                                @if (auth()->user()->hasRole('Root'))
                                                    <th>Aksi</th>
                                                @endif
                                                <th>Detail</th>
                                                <th>Tanggal Dibuat</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($deposits->isEmpty())
                                                <tr>
                                                    <td colspan="14" class="text-center">Data kosong atau tidak ada data
                                                    </td>
                                                </tr>
                                            @else
                                                @foreach ($deposits as $deposit)
                                                    <tr class="text-center" id="upt_ids{{ $deposit->id }}">
                                                        <td>{{ $deposit->id }}</td>
                                                        <td>{{ $deposit->user->name }}</td>
                                                        @if (auth()->user()->hasAnyRole(['Upt', 'Admin']))
                                                            <td>{{ $deposit->busStation->name }}</td>
                                                        @endif
                                                        <td>{{ $deposit->bank->account_name }}
                                                            ({{ $deposit->bank->account_number }})
                                                        </td>
                                                        <td>
                                                            @if ($deposit->type == 'req')
                                                                <span class="badge badge-primary">Request
                                                                    ({{ $deposit->status }})</span>
                                                            @elseif ($deposit->type == 'send')
                                                                <span class="badge badge-warning">Send</span>
                                                            @endif
                                                        </td>
                                                        <td>{{ $deposit->amount }}</td>

                                                        @if (auth()->user()->hasRole('Root'))
                                                            <td>
                                                                @if ($deposit->type == 'req' && $deposit->status == 'Pending')
                                                                    <div class="btn-group" role="group"
                                                                        aria-label="Basic example">
                                                                        <a href="{{ route('deposits.edit', $deposit->id) }}"
                                                                            class="btn btn-primary" data-toggle="tooltip"
                                                                            data-placement="top" title="Transfer">
                                                                            <i class="fas fa-edit"></i>
                                                                        </a>
                                                                    </div>
                                                                @endif
                                                            </td>
                                                        @endif
                                                        <td>
                                                            <div class="btn-group" role="group"
                                                                aria-label="Basic example">
                                                                <a href="{{ route('deposits.detail', $deposit->id) }}"
                                                                    class="btn btn-warning" data-toggle="tooltip"
                                                                    data-placement="top" title="Detail">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                            </div>
                                                        </td>
                                                        <td>{{ $deposit->created_at }}</td>
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
