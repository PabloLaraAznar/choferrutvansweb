@extends('adminlte::page')

@section('title', 'Rutas')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="fw-bold text-dark">
            <i class="fas fa-map-marked-alt me-2 text-primary"></i> Gestión de Rutas
        </h1>
        <button class="btn btn-success rounded-pill shadow-lg px-4 py-2" data-toggle="modal" data-target="#createModal">
            <i class="fas fa-plus me-2"></i> Nueva Ruta
        </button>
    </div>
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="card border-0 shadow rounded-4">
        <div class="card-body p-0">
            <table class="table table-hover text-center align-middle mb-0">
                <thead class="bg-dark text-white">
                    <tr class="fs-6">
                        <th class="py-3">ID</th>
                        <th class="py-3">📍 Inicio</th>
                        <th class="py-3">📌 Final</th>
                        <th class="py-3">🔧 Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @forelse($rutas as $ruta)
                    <tr class="hover-shadow-sm">
                        <td class="fw-semibold">{{ $ruta->id }}</td>
                        <td>{{ $ruta->ubicacionInicio->locality ?? 'N/A' }}</td>
                        <td>{{ $ruta->ubicacionFinal->locality ?? 'N/A' }}</td>
                        <td>
                            <button class="btn btn-outline-warning btn-sm rounded-pill me-2" data-toggle="modal" data-target="#editModal{{ $ruta->id }}">
                                <i class="fas fa-pen"></i>
                            </button>
                            <form action="{{ route('rutas.destroy', $ruta->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar esta ruta?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-outline-danger btn-sm rounded-pill">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
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
