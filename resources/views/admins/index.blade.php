@extends('layouts/main')

@section('container')
<div class="lime-container">
    <div class="lime-body">
        <div class="container">
            <div class="row">
                <div class="col-xl">
                    <div class="card">
                        <div class="card-body">
                            <h2 class="card-title mb-4" style="font-size: 20px;">Tabel Data Admin</h2>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <form action="{{ route('admins.search') }}" method="GET">
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
                                    <a href="{{ route('admins.index') }}" id="refreshPage" class="btn btn-outline-info mr-2" data-toggle="tooltip" data-placement="top" title="Segarkan">
                                        <i class="fas fa-sync-alt mr-1"></i>
                                    </a>                                    
                                    <a href="{{ route('admins.create') }}" class="btn btn-outline-success mr-2" data-toggle="tooltip" data-placement="top" title="Tambah">
                                        <i class="fas fa-plus"></i>
                                    </a>
                                    <a href="#" id="deleteAllSelectedRecord" class="btn btn-outline-danger" data-toggle="modal" data-target="#confirmationModal" data-url="{{ route('admins.destroy.multi') }}" >
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
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
                                        <th>Penempatan</th>
                                        <th>Tanggal Bergabung</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($admins->isEmpty())
                                        <tr>
                                            <td colspan="9" class="text-center">Data kosong atau tidak ada data</td>
                                        </tr>
                                    @else
                                    @foreach($admins as $admin)
                                    <tr class="text-center" id = "upt_ids{{ $admin ->id }}">
                                        <td><input type="checkbox" name="ids[]" class="checkbox_ids" id="{{ $admin->id }}" value="{{ $admin->id }}"></td>
                                        <td>{{ $admin->id }}</td>
                                        <td>{{ $admin->name }}</td>
                                        <td>{{ $admin->email }}</td>
                                        <td>{{ $admin->address }}</td>
                                        <td>
                                            @if ($admin->gender === 'male')
                                                Laki-laki
                                            @elseif ($admin->gender === 'female')
                                                Perempuan
                                            @endif
                                        </td>
                                        <td>{{ $admin->phone_number }}</td>
                                        <td>{{ $admin->terminal_name ?: '-' }}</td> 
                                        <td>{{ $admin->created_at->format('d-m-Y') }}<br>{{ $admin->created_at->format('H:i:s') }}</td>

                                        <td>
                                            <div class="btn-group" role="group" aria-label="Basic example">
                                                <a href="{{ route('admins.detail', $admin->id) }}" class="btn btn-warning" data-toggle="tooltip" data-placement="top" title="Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </div>
                                            <div class="btn-group" role="group" aria-label="Basic example">
                                                <a href="{{ route('admins.edit', $admin->id) }}" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Ubah">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        <nav aria-label="Page navigation example">
                            <ul class="pagination justify-content-center">
                                @if ($admins->previousPageUrl())
                                    <li class="page-item"><a class="page-link" href="{{ $admins->previousPageUrl() }}">Kembali</a></li>
                                @else
                                    <li class="page-item disabled"><span class="page-link">Kembali</span></li>
                                @endif
                        
                                @for ($i = 1; $i <= $admins->lastPage(); $i++)
                                    <li class="page-item {{ $admins->currentPage() == $i ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $admins->url($i) }}">{{ $i }}</a>
                                    </li>
                                @endfor
                        
                                @if ($admins->nextPageUrl())
                                    <li class="page-item"><a class="page-link" href="{{ $admins->nextPageUrl() }}">Berikutnya</a></li>
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
@endsection