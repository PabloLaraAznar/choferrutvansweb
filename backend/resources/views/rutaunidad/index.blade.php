@extends('adminlte::page')

@section('title', 'Asignación de Rutas a Unidades')

@section('content_header')
    <div class="rutvans-content-header rutvans-fade-in">
        <div class="container-fluid">
            <h1>
                <i class="fas fa-route me-2"></i> Asignación de Rutas a Unidades
            </h1>
            <p class="subtitle">Gestiona las asignaciones de rutas y unidades de transporte</p>
        </div>
    </div>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/rutvans-admin.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap4.min.css">
@endsection

@section('content')
<div class="rutvans-card rutvans-hover-lift rutvans-fade-in">
    <div class="rutvans-card-header d-flex justify-content-between align-items-center">
        <h3 class="m-0">
            <i class="fas fa-map-marked-alt me-2"></i> Asignaciones Registradas
        </h3>
        <button class="rutvans-btn rutvans-btn-primary" data-toggle="modal" data-target="#createRutaUnidadModal">
            <i class="fas fa-plus"></i> Nueva Asignación
        </button>
    </div>
    
    <div class="rutvans-card-body">
        <div class="rutvans-table-container">
            <table id="rutaUnidadTable" class="rutvans-table table-hover">
                <thead class="rutvans-table-header">
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
                            <td class="rutvans-price">${{ number_format($item->price, 2) }}</td>
                            <td class="text-center">
                                <button class="rutvans-btn rutvans-btn-sm rutvans-btn-warning" data-toggle="modal" data-target="#editModal{{ $item->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('rutas-unidades.update', $item->id) }}" method="POST" style="display:inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rutvans-btn rutvans-btn-sm rutvans-btn-danger" onclick="return confirm('¿Está seguro de eliminar esta asignación?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <!-- Modal editar -->
                        <div class="rutvans-modal fade" id="editModal{{ $item->id }}" tabindex="-1" role="dialog">
                            <div class="modal-dialog" role="document">
                                <div class="rutvans-modal-content">
                                    <form action="{{ route('rutas-unidades.update', $item->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="rutvans-modal-header">
                                            <h5 class="modal-title">
                                                <i class="fas fa-edit me-2"></i> Editar Asignación
                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
                                        <div class="rutvans-modal-body">
                                            <div class="rutvans-form-group">
                                                <label class="rutvans-form-label">Ruta</label>
                                                <select name="id_route" class="form-control rutvans-form-control">
                                                    @foreach($rutas as $ruta)
                                                        <option value="{{ $ruta->id }}" {{ $item->id_route == $ruta->id ? 'selected' : '' }}>
                                                            {{ optional($ruta->source)->locality }} → {{ optional($ruta->destination)->locality }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="rutvans-form-group">
                                                <label class="rutvans-form-label">Unidad y Chofer</label>
                                                <select name="id_driver_unit" class="form-control rutvans-form-control">
                                                    @foreach($driverUnits as $du)
                                                        <option value="{{ $du->id }}" {{ $item->id_driver_unit == $du->id ? 'selected' : '' }}>
                                                            {{ optional($du->unit)->plate }} - {{ optional($du->driver->user)->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="rutvans-form-group">
                                                <label class="rutvans-form-label">Localidad Intermedia</label>
                                                <select name="intermediante_location_id" class="form-control rutvans-form-control">
                                                    <option value="">Sin localidad</option>
                                                    @foreach($localidades as $loc)
                                                        <option value="{{ $loc->id }}" {{ $item->intermediante_location_id == $loc->id ? 'selected' : '' }}>
                                                            {{ $loc->locality }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="rutvans-form-group">
                                                <label class="rutvans-form-label">Precio</label>
                                                <input type="number" step="0.01" class="form-control rutvans-form-control" name="price" value="{{ $item->price }}" required>
                                            </div>
                                        </div>
                                        <div class="rutvans-modal-footer">
                                            <button type="submit" class="rutvans-btn rutvans-btn-warning">
                                                <i class="fas fa-save me-1"></i> Guardar cambios
                                            </button>
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
<div class="rutvans-modal fade" id="createRutaUnidadModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="rutvans-modal-content">
            <form action="{{ route('rutaunidad.store') }}" method="POST">
                @csrf
                <div class="rutvans-modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-plus me-2"></i> Nueva Asignación
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="rutvans-modal-body">
                    <!-- Ruta -->
                    <div class="rutvans-form-group">
                        <label class="rutvans-form-label">Ruta</label>
                        <select name="id_route" class="form-control rutvans-form-control" required>
                            <option value="">Seleccione una ruta</option>
                            @foreach($rutas as $ruta)
                                <option value="{{ $ruta->id }}" {{ old('id_route') == $ruta->id ? 'selected' : '' }}>
                                    {{ optional($ruta->source)->locality }} → {{ optional($ruta->destination)->locality }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Unidad y Chofer -->
                    <div class="rutvans-form-group">
                        <label class="rutvans-form-label">Unidad y Chofer</label>
                        <select name="id_driver_unit" class="form-control rutvans-form-control" required>
                            <option value="">Seleccione unidad y chofer</option>
                            @foreach($driverUnits as $du)
                                <option value="{{ $du->id }}" {{ old('id_driver_unit') == $du->id ? 'selected' : '' }}>
                                    {{ optional($du->unit)->plate }} - {{ optional($du->driver->user)->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Localidad Intermedia -->
                    <div class="rutvans-form-group">
                        <label class="rutvans-form-label">Localidad Intermedia (opcional)</label>
                        <select name="intermediante_location_id" class="form-control rutvans-form-control">
                            <option value="">Sin localidad</option>
                            @foreach($localidades as $loc)
                                <option value="{{ $loc->id }}" {{ old('intermediante_location_id') == $loc->id ? 'selected' : '' }}>
                                    {{ $loc->locality }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Precio -->
                    <div class="rutvans-form-group">
                        <label class="rutvans-form-label">Precio</label>
                        <input type="number" step="0.01" class="form-control rutvans-form-control" name="price" required value="{{ old('price') }}">
                    </div>
                </div>

                <div class="rutvans-modal-footer">
                    <button type="submit" class="rutvans-btn rutvans-btn-primary">
                        <i class="fas fa-save me-1"></i> Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('js')
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap4.min.js"></script>

<script>
$(document).ready(function() {
    $('#rutaUnidadTable').DataTable({
        responsive: true,
        language: {
            "url": "https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json"
        },
        pageLength: 25,
        order: [[0, 'asc']],
        columnDefs: [
            { orderable: false, targets: [4] }
        ]
    });
});
</script>
@endpush

@endsection
