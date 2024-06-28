@extends('layouts/main')

@section('container')
    <div class="lime-container">
        <div class="lime-body">
            <div class="container">
                <div class="row">
                    <div class="col-xl">
                        <div class="card">
                            <div class="card-body">
                                <h2 class="card-title mb-4" style="font-size: 20px;">Tabel Data PO</h2>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <form action="{{ route('otobuses.search') }}" method="GET">
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="search" id="searchInput"
                                                    placeholder="Masukkan Nama PO / Alamat" value="" size="30">
                                                <div class="input-group-append">
                                                    <button class="btn btn-primary" type="submit">
                                                        <i class="fas fa-search"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="d-flex">
                                        <a href="{{ route('otobuses.index') }}" id="refreshPage"
                                            class="btn btn-outline-info mr-2" data-toggle="tooltip" data-placement="top"
                                            title="Segarkan">
                                            <i class="fas fa-sync-alt mr-1"></i>
                                        </a>
                                        <a href="{{ route('otobuses.create') }}" class="btn btn-outline-success mr-2"
                                            data-toggle="tooltip" data-placement="top" title="Tambah">
                                            <i class="fas fa-plus"></i>
                                        </a>
                                        <a href="#" id="deleteAllSelectedRecord" class="btn btn-outline-danger"
                                            data-toggle="modal" data-target="#confirmationModal"
                                            data-url="{{ route('otobuses.destroy.multi') }}">
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
                                            <button type="button" class="btn btn-default"
                                                data-dismiss="modal">Batal</button>
                                            <button type="button" class="btn btn-danger" id="confirmDelete">Ya,
                                                Hapus</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="uptTable" class="table-responsive">
                                <table class="table">
                                    <thead>
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
                                        @if ($otobuses->isEmpty())
                                            <tr>
                                                <td colspan="9" class="text-center">Data kosong atau tidak ada data</td>
                                            </tr>
                                        @else
                                            @foreach ($otobuses as $otobus)
                                                <tr class="text-center" id = "upt_ids{{ $otobus->id }}">
                                                    <td><input type="checkbox" name="ids" class = "checkbox_ids"
                                                            id ="{{ $otobus->id }}" value="{{ $otobus->id }}"></td>
                                                    <td>{{ $otobus->id }}</td>
                                                    <td>{{ $otobus->name }}</td>
                                                    <td>{{ $otobus->email }}</td>
                                                    <td>{{ $otobus->address }}</td>
                                                    <td>
                                                        @if ($otobus->gender === 'male')
                                                            Laki-laki
                                                        @elseif ($otobus->gender === 'female')
                                                            Perempuan
                                                        @endif
                                                    </td>
                                                    <td>{{ $otobus->phone_number }}</td>
                                                    <td>{{ $otobus->created_at->format('d-m-Y') }}<br>{{ $otobus->created_at->format('H:i:s') }}
                                                    </td>
                                                    <td>
                                                        <div class="btn-group" role="group" aria-label="Basic example">
                                                            <a href="{{ route('otobuses.detail', $otobus->id) }}"
                                                                class="btn btn-warning" data-toggle="tooltip"
                                                                data-placement="top" title="Detail">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                        </div>
                                                        <div class="btn-group" role="group" aria-label="Basic example">
                                                            <a href="{{ route('otobuses.edit', $otobus->id) }}"
                                                                class="btn btn-primary" data-toggle="tooltip"
                                                                data-placement="top" title="Ubah">
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
                                    @if ($otobuses->previousPageUrl())
                                        <li class="page-item"><a class="page-link"
                                                href="{{ $otobuses->previousPageUrl() }}">Kembali</a></li>
                                    @else
                                        <li class="page-item disabled"><span class="page-link">Kembali</span></li>
                                    @endif

                                    @for ($i = 1; $i <= $otobuses->lastPage(); $i++)
                                        <li class="page-item {{ $otobuses->currentPage() == $i ? 'active' : '' }}">
                                            <a class="page-link" href="{{ $otobuses->url($i) }}">{{ $i }}</a>
                                        </li>
                                    @endfor

                                    @if ($otobuses->nextPageUrl())
                                        <li class="page-item"><a class="page-link"
                                                href="{{ $otobuses->nextPageUrl() }}">Berikutnya</a></li>
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
