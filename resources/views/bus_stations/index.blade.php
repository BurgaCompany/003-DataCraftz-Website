@extends('layouts/main')

@section('container')
<div class="lime-container">
    <div class="lime-body">
        <div class="container">
            <div class="row">
                <div class="col-xl">
                    <div class="card">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <h2 class="card-title mb-4" style="font-size: 20px;">Data Terminal</h2>
                            <div>
                                <a href="{{ route('bus_stations.create') }}" class="btn btn-outline-success ml-auto" data-toggle="tooltip" data-placement="top" title="Tambah">
                                    <i class="fas fa-plus"></i>
                                </a>
                                <a href="#" id="deleteAllSelectedRecord" class="btn btn-outline-danger ml-2" data-toggle="modal" data-target="#confirmationModal" data-url="{{ route('bus_stations.destroy.multi') }}">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
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

                          <div class="my-4 mx-4">
                            <div class="row">
                                @if ($userBusStations->isEmpty())
                                <div class="col-md-12 mb-4 text-center">
                                        <p>Data kosong atau tidak ada data</p>
                                    </div>
                                @else
                                    @foreach ($userBusStations as $userBusStations)
                                        <div class="col-md-4 mb-4">
                                            <div class="custom-card" onclick="if(!event.target.classList.contains('form-check-input')) window.location.href = '{{ route('bus_stations.detail', ['id' => $userBusStations->busStation->id]) }}'">
                                                <input type="checkbox" class="form-check-input checkbox_ids" id="{{ $userBusStations->busStation->id }}" value="{{ $userBusStations->busStation->id }}" onclick="event.stopPropagation()">
                                                    <div class="card-body centered-content">
                                                        <span class="icon"><i class="fas fa-bus"></i></span>
                                                        <p class="card-text">{{ $userBusStations->busStation->name }}</p>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
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
</div>
@endsection