@extends('adminlte::page')

@section('content')
<div class="container-fluid">
    <div class="row mb-3 align-items-center">
        <div class="col">
            <h1>Asignación de Rutas a Unidades</h1>
        </div>
        <div class="col text-right">
            <button class="btn btn-success" data-toggle="modal" data-target="#createRutaUnidadModal">
                <i class="fas fa-plus"></i> Nueva Asignación
            </button>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="thead-dark">
                    <tr>
                        <th>Ruta</th>
                        <th>Unidad - Chofer</th>
                        <th>Localidad Intermedia</th>
                        <th>Precio</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rutaUnidades as $item)
                        <tr>
                            <td>{{ optional($item->ruta->source)->locality }} → {{ optional($item->ruta->destination)->locality }}</td>
                            <td>{{ optional($item->driverUnit->unit)->plate }} - {{ optional($item->driverUnit->driver->user)->name }}</td>
                            <td>{{ optional($item->intermediateLocation)->locality ?? 'N/A' }}</td>
                            <td>${{ number_format($item->price, 2) }}</td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editModal{{ $item->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('rutas-unidades.update', $item->id) }}" method="POST" style="display:inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Eliminar asignación?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <!-- Modal editar -->
                        <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1" role="dialog">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <form action="{{ route('rutas-unidades.update', $item->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header bg-warning">
                                            <h5 class="modal-title">Editar Asignación</h5>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label>Ruta</label>
                                                <select name="id_route" class="form-control">
                                                    @foreach($rutas as $ruta)
                                                        <option value="{{ $ruta->id }}" {{ $item->id_route == $ruta->id ? 'selected' : '' }}>
                                                            {{ optional($ruta->source)->locality }} → {{ optional($ruta->destination)->locality }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Unidad y Chofer</label>
                                                <select name="id_driver_unit" class="form-control">
                                                    @foreach($driverUnits as $du)
                                                        <option value="{{ $du->id }}" {{ $item->id_driver_unit == $du->id ? 'selected' : '' }}>
                                                            {{ optional($du->unit)->plate }} - {{ optional($du->driver->user)->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Localidad Intermedia</label>
                                                <select name="intermediante_location_id" class="form-control">
                                                    <option value="">Sin localidad</option>
                                                    @foreach($localidades as $loc)
                                                        <option value="{{ $loc->id }}" {{ $item->intermediante_location_id == $loc->id ? 'selected' : '' }}>
                                                            {{ $loc->locality }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Precio</label>
                                                <input type="number" step="0.01" class="form-control" name="price" value="{{ $item->price }}" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-warning">Guardar cambios</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">No hay asignaciones registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal crear -->
<div class="modal fade" id="createRutaUnidadModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('rutaunidad.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Nueva Asignación</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">

                    <!-- Ruta -->
                    <div class="form-group">
                        <label>Ruta</label>
                        <select name="id_route" class="form-control" required>
                            <option value="">Seleccione una ruta</option>
                            @foreach($rutas as $ruta)
                                <option value="{{ $ruta->id }}" {{ old('id_route') == $ruta->id ? 'selected' : '' }}>
                                    {{ optional($ruta->source)->locality }} → {{ optional($ruta->destination)->locality }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Unidad y Chofer -->
                    <div class="form-group">
                        <label>Unidad y Chofer</label>
                        <select name="id_driver_unit" class="form-control" required>
                            <option value="">Seleccione unidad y chofer</option>
                            @foreach($driverUnits as $du)
                                <option value="{{ $du->id }}" {{ old('id_driver_unit') == $du->id ? 'selected' : '' }}>
                                    {{ optional($du->unit)->plate }} - {{ optional($du->driver->user)->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Localidad Intermedia -->
                    <div class="form-group">
                        <label>Localidad Intermedia (opcional)</label>
                        <select name="intermediante_location_id" class="form-control">
                            <option value="">Sin localidad</option>
                            @foreach($localidades as $loc)
                                <option value="{{ $loc->id }}" {{ old('intermediante_location_id') == $loc->id ? 'selected' : '' }}>
                                    {{ $loc->locality }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Precio -->
                    <div class="form-group">
                        <label>Precio</label>
                        <input type="number" step="0.01" class="form-control" name="price" required value="{{ old('price') }}">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
