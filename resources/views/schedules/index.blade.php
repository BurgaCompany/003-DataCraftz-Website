@extends('layouts/main')

@section('container')
<div class="lime-container">
    <div class="lime-body">
        <div class="container">
            <div class="row">
                <div class="col-xl">
                    <div class="card">
                        <div class="card-body">
                            <h2 class="card-title mb-4" style="font-size: 20px;">Tabel Data Jadwal</h2>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <form action="{{ route('schedules.search') }}" method="GET">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="search" id="searchInput" placeholder="Masukkan Bus / Terminal Tujuan" value="" size="30">
                                            <div class="input-group-append">
                                                <button class="btn btn-primary" type="submit">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="d-flex">
                                    <a href="{{ route('schedules.index') }}" id="refreshPage" class="btn btn-outline-info mr-2" data-toggle="tooltip" data-placement="top" title="Segarkan">
                                        <i class="fas fa-sync-alt mr-1"></i>
                                    </a>
                                    @if (Auth::user()->hasRole('Root'))
                                    <a href="{{ route('schedules.create') }}" class="btn btn-outline-success mr-2" data-toggle="tooltip" data-placement="top" title="Tambah">
                                        <i class="fas fa-plus"></i>
                                    </a>
                                    <a href="#" id="deleteAllSelectedRecord" class="btn btn-outline-danger mr-2" data-toggle="modal" data-target="#confirmationModal" data-url="{{ route('schedules.destroy.multi') }}">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                        @endif
                                        <button id="exportToExcel" class="btn btn-outline-primary" data-toggle="tooltip" data-placement="top" title="Cetak">
                                            <i class="fas fa-file-excel"></i> Cetak
                                        </button>
                                </div>
                                
                            </div>
                        </div>
                        <div id="confirmationModal" class="modal fade" role="dialog">
                            <div class="modal-dialog">
                              <!-- Konten modal -->
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h4 class="modal-title">Peringatan</h4>
                                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body">
                                  <p>Apakah Anda yakin ingin menghapus yang dipilih?</p>
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                                  <button type="button" class="btn btn-danger" id="confirmDelete">Ya, Hapus</button>
                                </div>
                              </div>
                            </div>
                          </div>
                        <div id="uptTable" class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr class="text-center">
                                        <th>
                                            <input type="checkbox" name="" id="select_all_ids">
                                        </th>
                                        <th>ID</th>
                                        <th>Nama Bus</th>
                                        <th>Terminal Berangkat</th>
                                        <th>Terminal Tujuan</th>
                                        <th>Harga</th>
                                        <th>Jam Berangkat</th>
                                        <th>Perkiraan Waktu Tempuh</th>
                                        <th>Tanggal Dibuat</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($schedules->isEmpty())
                                    <tr>
                                        <td colspan="9" class="text-center">Data kosong atau tidak ada data</td>
                                    </tr>
                                    @else
                                    @foreach($schedules as $schedule)
                                    <tr class="text-center" id="upt_ids{{ $schedule->id }}">
                                        <td><input type="checkbox" name="ids" class="checkbox_ids" id="{{ $schedule->id }}" value="{{ $schedule->id }}"></td>
                                        <td>{{ $schedule->id }}</td>
                                        <td>{{ $schedule->bus->name }} ({{ $schedule->bus->license_plate_number }})</td>
                                        <td>{{ $schedule->fromStation->name }}</td>
                                        <td>{{ $schedule->toStation->name }}</td>
                                        <td>{{ $schedule->price }}</td>
                                        <td>{{ $schedule->time_start }}</td>
                                        <td>{{ $schedule->duration }}</td>
                                        <td>{{ $schedule->created_at }}</td>
                                        <td>
                                            <div class="btn-group" role="group" aria-label="Basic example">
                                                <a href="{{ route('schedules.detail', $schedule->id) }}" class="btn btn-warning" data-toggle="tooltip" data-placement="top" title="Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </div>
                                            @if (Auth::user()->hasRole('Root'))
                                            <div class="btn-group" role="group" aria-label="Basic example">
                                                <a href="{{ route('schedules.edit', $schedule->id) }}" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Ubah">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </div>
                                            @endif
                                        </td>
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