@extends('layouts/main')

@section('container')
<div class="lime-container">
    <div class="lime-body">
        <div class="container">
            <div class="row">
                <div class="col-xl">
                    <div class="card">
                        <div class="card-body">
                            <h2 class="card-title mb-4" style="font-size: 20px;">Tabel Data Sopir</h2>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <form action="{{ route('drivers.search') }}" method="GET">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="search" id="searchInput" placeholder="Masukkan Nama / Alamat" value="" size="30">
                                            <div class="input-group-append">
                                                <button class="btn btn-primary" type="submit">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="d-flex">
                                    <a href="{{ route('drivers.index') }}" id="refreshPage" class="btn btn-outline-info mr-2" data-toggle="tooltip" data-placement="top" title="Segarkan">
                                        <i class="fas fa-sync-alt mr-1"></i>
                                    </a>                                    
                                    @if (Auth::user()->hasRole('Upt'))
                                    <a href="{{ route('drivers.create') }}" class="btn btn-outline-success mr-2" data-toggle="tooltip" data-placement="top" title="Tambah">
                                        <i class="fas fa-plus"></i>
                                    </a>
                                    <a href="#" id="deleteAllSelectedRecord" class="btn btn-outline-danger" data-toggle="modal" data-target="#confirmationModal" data-url="{{ route('drivers.destroy.multi') }}" >
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                    @endif
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
                                <thead >
                                    <tr class="text-center">
                                        <th>
                                            <input type="checkbox" name = "" id="select_all_ids">
                                        </th>                                        
                                        <th>ID</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Alamat</th>
                                        <th>Jenis Kelamin</th>
                                        <th>Nomor Handphone</th>
                                        <th>Tanggal Bergabung</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($drivers->isEmpty())
                                        <tr>
                                            <td colspan="9" class="text-center">Data kosong atau tidak ada data</td>
                                        </tr>
                                    @else
                                    @foreach($drivers as $driver)
                                    <tr class="text-center" id = "upt_ids{{ $driver ->id }}">
                                        <td><input type="checkbox" name="ids" class = "checkbox_ids" id ="{{ $driver->id }}" value="{{ $driver->id }}"></td>
                                        <td>{{ $driver->id }}</td>
                                        <td>{{ $driver->name }}</td>
                                        <td>{{ $driver->email }}</td>
                                        <td>{{ $driver->address }}</td>
                                        <td>
                                            @if ($driver->gender === 'male')
                                                Laki-laki
                                            @elseif ($driver->gender === 'female')
                                                Perempuan
                                            @endif
                                        </td>
                                        <td>{{ $driver->phone_number }}</td>
                                        <td>{{ $driver->created_at->format('d-m-Y') }}<br>{{ $driver->created_at->format('H:i:s') }}</td>
                                        <td>
                                            <div class="btn-group" role="group" aria-label="Basic example">
                                                <a href="{{ route('drivers.detail', $driver->id) }}" class="btn btn-warning" data-toggle="tooltip" data-placement="top" title="Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </div>
                                            @if (Auth::user()->hasRole('Upt'))
                                            <div class="btn-group" role="group" aria-label="Basic example">
                                                <a href="{{ route('drivers.edit', $driver->id) }}" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Ubah">
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

                        <nav aria-label="Page navigation example">
                            <ul class="pagination justify-content-center">
                                @if ($drivers->previousPageUrl())
                                    <li class="page-item"><a class="page-link" href="{{ $drivers->previousPageUrl() }}">Kembali</a></li>
                                @else
                                    <li class="page-item disabled"><span class="page-link">Kembali</span></li>
                                @endif
                        
                                @for ($i = 1; $i <= $drivers->lastPage(); $i++)
                                    <li class="page-item {{ $drivers->currentPage() == $i ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $drivers->url($i) }}">{{ $i }}</a>
                                    </li>
                                @endfor
                        
                                @if ($drivers->nextPageUrl())
                                    <li class="page-item"><a class="page-link" href="{{ $drivers->nextPageUrl() }}">Berikutnya</a></li>
                                @else
                                    <li class="page-item disabled"><span class="page-link">Berikutnya</span></li>
                                @endif
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<script src="../../assets/js/search.js"></script>
@endsection