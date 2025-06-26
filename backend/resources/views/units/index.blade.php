<!-- resources/views/units/index.blade.php -->
@extends('adminlte::page')

@section('title', 'Unidades')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/rutvans-admin.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap4.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" rel="stylesheet" />
@endsection
@endsection

@section('content_header')
    <div class="rutvans-content-header rutvans-fade-in">
        <div class="container-fluid">
            <h1>
                <i class="fas fa-shuttle-van me-2"></i> Gestión de Unidades
            </h1>
            <p class="subtitle">Administra las unidades de transporte del sistema</p>
        </div>
    </div>
@endsection

@section('content')
<div class="rutvans-card rutvans-hover-lift rutvans-fade-in">
    <div class="rutvans-card-header d-flex justify-content-between align-items-center">
        <h3 class="m-0">
            <i class="fas fa-bus me-2"></i> Unidades Registradas
        </h3>
        <button class="rutvans-btn rutvans-btn-primary" data-toggle="modal" data-target="#createUnitModal">
            <i class="fas fa-plus"></i> Nueva Unidad
        </button>
    </div>
    
    <div class="rutvans-card-body">
        <!-- Filtros y búsqueda -->
        <form method="GET" action="{{ route('units.index') }}" class="mb-4">
            <div class="row align-items-end">
                <div class="col-md-4">
                    <div class="rutvans-form-group">
                        <label class="rutvans-form-label">
                            <i class="fas fa-search me-1"></i> Buscar unidad
                        </label>
                        <input type="text" name="search" class="form-control rutvans-form-control" 
                            placeholder="Buscar por nombre o placa" value="{{ request('search') }}">
                    </div>
                </div>
                
                <div class="col-md-8">
                    <div class="d-flex gap-2 flex-wrap">
                        <button type="submit" class="rutvans-btn rutvans-btn-info">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                        <a href="{{ route('units.index') }}" class="rutvans-btn rutvans-btn-secondary">
                            <i class="fas fa-eraser"></i> Limpiar
                        </a>
                        <a href="{{ route('exports.unitsexportexcel', ['search' => request('search')]) }}" class="rutvans-btn rutvans-btn-success">
                            <i class="fas fa-file-excel"></i> Excel
                        </a>
                        <a href="{{ route('exports.unitsexportpdf', ['search' => request('search')]) }}" class="rutvans-btn rutvans-btn-danger">
                            <i class="fas fa-file-pdf"></i> PDF
                        </a>
                    </div>
                </div>
            </div>
        </form>


    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="rutvans-table-container">
                <table id="unitsTable" class="rutvans-table table-hover">
                    <thead class="rutvans-table-header">
                    <tr>
                        <th>Placa</th>
                        <th>Capacidad</th>
                        <th>Foto</th>
                        <th>Choferes Asignados</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($units as $unit)
                    <tr>
                        <td>{{ $unit->plate }}</td>
                        <td>{{ $unit->capacity }} personas</td>
                        <td>
                            @if($unit->photo)
                                <img src="{{ asset('storage/'.$unit->photo) }}"
                                    alt="Foto unidad"
                                    class="img-thumbnail"
                                    width="80">
                            @else
                                <span class="text-muted">Sin imagen</span>
                            @endif
                        </td>
                        <td>
                            @forelse($unit->drivers as $driver)
                                <div class="d-flex justify-content-between align-items-center mb-2 p-2 bg-light rounded">
                                    <div>
                                        <strong>{{ $driver->user->name }}</strong>
                                        <span class="badge badge-info ml-2">{{ $driver->pivot->status }}</span>
                                    </div>
                                    <form action="{{ route('units.removeDriver', [$unit->id, $driver->id]) }}"
                                        method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rutvans-btn rutvans-btn-sm rutvans-btn-outline-danger"
                                                onclick="return confirm('¿Quitar conductor?')">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                </div>
                            @empty
                                <span class="text-muted">Sin conductores asignados</span>
                            @endforelse
                        </td>
                        <td class="text-center">
                            <button class="rutvans-btn rutvans-btn-sm rutvans-btn-warning mr-1"
                                    data-toggle="modal"
                                    data-target="#editUnitModal{{ $unit->id }}">
                                <i class="fas fa-edit"></i>
                            </button>

                            <button class="rutvans-btn rutvans-btn-sm rutvans-btn-success mr-1"
                                    data-toggle="modal"
                                    data-target="#assignDriverModal{{ $unit->id }}">
                                <i class="fas fa-user-plus"></i>
                            </button>

                            <form action="{{ route('units.destroy', $unit->id) }}"
                                method="POST"
                                style="display:inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="rutvans-btn rutvans-btn-sm rutvans-btn-danger"
                                        onclick="return confirm('¿Eliminar unidad?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">No hay unidades registradas</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal para crear unidad -->
<div class="modal fade" id="createUnitModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('units.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Registrar Nueva Unidad</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="plate">Placa</label>
                        <input type="text" class="form-control" id="plate" name="plate" required>
                    </div>
                    <div class="form-group">
                        <label for="capacity">Capacidad (personas)</label>
                        <input type="number" class="form-control" id="capacity" name="capacity" min="1" required>
                    </div>
                    <div class="form-group">
                        <label for="photo">Foto de la unidad (opcional)</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="photo" name="photo">
                            <label class="custom-file-label" for="photo">Seleccionar archivo</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Registrar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modales para editar y asignar conductores -->
@foreach($units as $unit)
<!-- Modal Editar Unidad -->
<div class="modal fade" id="editUnitModal{{ $unit->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('units.update', $unit->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title">Editar Unidad: {{ $unit->plate }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="plate">Placa</label>
                        <input type="text" class="form-control" name="plate" value="{{ $unit->plate }}" required>
                    </div>
                    <div class="form-group">
                        <label for="capacity">Capacidad (personas)</label>
                        <input type="number" class="form-control" name="capacity" value="{{ $unit->capacity }}" min="1" required>
                    </div>
                    <div class="form-group">
                        <label>Foto actual:</label>
                        @if($unit->photo)
                            <img src="{{ asset('storage/'.$unit->photo) }}"
                                alt="Foto actual"
                                class="img-fluid mb-2 rounded"
                                style="max-height: 150px">
                        @else
                            <p class="text-muted">Sin imagen registrada</p>
                        @endif
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" name="photo">
                            <label class="custom-file-label">Cambiar foto...</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Asignar Chofer -->
<div class="modal fade" id="assignDriverModal{{ $unit->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('units.assignDriver', $unit->id) }}" method="POST">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Asignar Chofer a: {{ $unit->plate }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label for="driver_id">Seleccionar Chofer</label>
                        <select class="form-control select2" id="driver_id" name="driver_id" required>
                            <option value="">-- Seleccione un conductor --</option>
                            @foreach($drivers as $driver)
                                <option value="{{ $driver->id }}">{{ $driver->user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="status">Estado de la asignación</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="Activo">Activo</option>
                            <option value="En mantenimiento">En mantenimiento</option>
                            <option value="Inactivo">Inactivo</option>
                            <option value="Reserva">Reserva</option>
                        </select>
                    </div>

                    <hr>
                    <h6>Choferes Asignados</h6>
                    @forelse($unit->drivers as $driver)
                        <div class="d-flex justify-content-between align-items-center mb-2 p-2 bg-light rounded">
                            <div>
                                <strong>{{ $driver->user->name }}</strong>
                                <span class="badge badge-info ml-2">{{ $driver->pivot->status }}</span>
                            </div>
                            <form action="{{ route('units.removeDriver', [$unit->id, $driver->id]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('¿Quitar conductor?')">
                                    <i class="fas fa-times"></i>
                                </button>
                            </form>
                        </div>
                    @empty
                        <p>No hay choferes asignados.</p>
                    @endforelse
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Asignar Chofer</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap4.min.js"></script>

<script>
    $(document).ready(function() {
        // Inicializar DataTable
        $('#unitsTable').DataTable({
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

        // Inicializar Select2
        $('.select2').select2({
            theme: 'bootstrap4',
            placeholder: 'Seleccione un conductor'
        });

        // Manejo de archivos
        $('.custom-file-input').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);
        });
    });
</script>
@endpush

@endsection
            