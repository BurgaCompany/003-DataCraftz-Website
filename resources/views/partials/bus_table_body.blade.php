@if ($busses->isEmpty())
    <tr>
        <td colspan="10" class="text-center">Data kosong atau tidak ada data</td>
    </tr>
@else
    @foreach($busses as $bus)
        <tr class="text-center" id="upt_ids{{ $bus->id }}">
            <td>{{ $bus->id }}</td>
            <td>{{ $bus->name }}</td>
            <td>
                @if ($bus->status == 1)
                    <span class="badge badge-primary">Belum Berangkat</span>
                @elseif ($bus->status == 2)
                    <span class="badge badge-warning">Bersedia Berangkat</span>
                @elseif ($bus->status == 3)
                    <span class="badge badge-success">Berangkat</span>
                @elseif ($bus->status == 4)
                    <span class="badge badge-danger">Terkendala</span>
                @elseif ($bus->status == 5)
                    <span class="badge badge-info">Tiba di Tujuan</span>
                @endif
            </td>
            <td>{{ $bus->chair }}</td>
            <td>{{ preg_replace('/([a-zA-Z])([0-9]+)/', '$1 $2 ', $bus->license_plate_number) }}</td>
            <td>{{ $bus->class }}</td>
            <td>{{ $bus->information ?: '-' }}</td>
            <td>{{ $bus->driver_name ?: '-'}}</td>
            <td>{{ $bus->conductor_name ?: '-'}}</td>
            @if(auth()->user()->hasAnyRole(['Upt', 'Admin']))
            <td>{{ $bus->po->name }}</td>
        @endif
        </tr>
    @endforeach
@endif
