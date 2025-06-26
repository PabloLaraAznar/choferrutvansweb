@extends('adminlte::page')

@section('title', 'Rutas')

@section('content_header')
    <div class="rutvans-content-header rutvans-fade-in">
        <div class="container-fluid">
            <h1>
                <i class="fas fa-route me-2"></i> Gestión de Rutas
            </h1>
            <p class="subtitle">Administra las rutas de transporte del sistema</p>
        </div>
    </div>
@endsection

@section('content')
    <div class="rutvans-card rutvans-hover-lift rutvans-fade-in">
        <div class="rutvans-card-header d-flex justify-content-between align-items-center">
            <h3 class="m-0">
                <i class="fas fa-map-marked-alt me-2"></i> Rutas de Transporte
            </h3>
            <button class="rutvans-btn rutvans-btn-primary" data-toggle="modal" data-target="#createModal">
                <i class="fas fa-plus"></i> Nueva Ruta
            </button>
        </div>
        
        <div class="rutvans-card-body">
            <div class="table-responsive">
                <table class="table rutvans-table table-striped table-hover align-middle">
                    <thead>
                        <tr>
                            <th><i class="fas fa-hashtag me-1"></i> ID</th>
                            <th><i class="fas fa-map-marker-alt me-1"></i> Ubicación Inicio</th>
                            <th><i class="fas fa-flag-checkered me-1"></i> Ubicación Final</th>
                            <th><i class="fas fa-cogs me-1"></i> Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rutas as $ruta)
                            <tr>
                                <td>
                                    <span class="rutvans-badge rutvans-badge-primary">{{ $ruta->id }}</span>
                                </td>
                                <td>
                                    <i class="fas fa-map-pin text-success me-2"></i>
                                    {{ $ruta->ubicacionInicio->locality ?? 'N/A' }}
                                </td>
                                <td>
                                    <i class="fas fa-flag text-danger me-2"></i>
                                    {{ $ruta->ubicacionFinal->locality ?? 'N/A' }}
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <button class="rutvans-btn rutvans-btn-warning rutvans-btn-sm" data-toggle="modal" data-target="#editModal{{ $ruta->id }}">
                                            <i class="fas fa-edit"></i> Editar
                                        </button>
                                        
                                        <form action="{{ route('rutas.destroy', $ruta->id) }}" method="POST" class="d-inline" 
                                            onsubmit="return confirm('¿Eliminar esta ruta?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="rutvans-btn rutvans-btn-danger rutvans-btn-sm" type="submit">
                                                <i class="fas fa-trash"></i> Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                    <!-- Modal Editar -->
                    <div class="modal fade" id="editModal{{ $ruta->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <form action="{{ route('rutas.update', $ruta->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-content rounded-3 shadow-sm">
                                    <div class="modal-header bg-gradient bg-primary text-white">
                                        <h5 class="modal-title"><i class="fas fa-pen me-2"></i>Editar Ruta</h5>
                                        <button type="button" class="btn-close text-white" data-dismiss="modal">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label class="fw-semibold">Ubicación Inicio</label>
                                            <select name="id_location_s" class="form-control rounded" required>
                                                @foreach($localities as $locality)
                                                    <option value="{{ $locality->id }}" {{ $locality->id == $ruta->ubicacionInicio->id ? 'selected' : '' }}>
                                                        {{ $locality->locality }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group mt-3">
                                            <label class="fw-semibold">Ubicación Final</label>
                                            <select name="id_location_f" class="form-control rounded" required>
                                                @foreach($localities as $locality)
                                                    <option value="{{ $locality->id }}" {{ $locality->id == $ruta->ubicacionFinal->id ? 'selected' : '' }}>
                                                        {{ $locality->locality }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer bg-light">
                                        <button type="button" class="btn btn-secondary rounded-pill" data-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn btn-success rounded-pill">Guardar cambios</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="4" class="text-muted">No hay rutas registradas.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Crear -->
<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('rutas.store') }}" method="POST">
            @csrf
            <div class="modal-content rounded-3 shadow-sm">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title"><i class="fas fa-plus-circle me-2"></i>Crear Nueva Ruta</h5>
                    <button type="button" class="btn-close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="fw-semibold">Ubicación Inicio</label>
                        <select name="id_location_s" class="form-control rounded" required>
                            @foreach($localities as $locality)
                                <option value="{{ $locality->id }}">{{ $locality->locality }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mt-3">
                        <label class="fw-semibold">Ubicación Final</label>
                        <select name="id_location_f" class="form-control rounded" required>
                            @foreach($localities as $locality)
                                <option value="{{ $locality->id }}">{{ $locality->locality }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button class="btn btn-outline-secondary rounded-pill" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary rounded-pill">Guardar Ruta</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
