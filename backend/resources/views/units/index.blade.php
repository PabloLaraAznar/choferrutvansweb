<!-- resources/views/units/index.blade.php -->
@extends('adminlte::page')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col">
            <h1>Unidades Registradas</h1>
        </div>
        <div class="col text-right">
            <button class="btn btn-primary" data-toggle="modal" data-target="#createUnitModal">
                <i class="fas fa-plus"></i> Nueva Unidad
            </button>
        </div>
    </div>

        <form method="GET" action="{{ route('units.index') }}" class="mb-3">
    <div class="form-row">
        {{-- Campo de búsqueda --}}
        <div class="col-md-4">
            <input type="text" name="search" class="form-control" placeholder="Buscar por nombre o placa" value="{{ request('search') }}">
        </div>

        <div class="col-md-8 mt-2 mt-md-0">
            <button type="submit" class="btn btn-info mr-2">
                <i class="fas fa-search"></i> Buscar
            </button>
            <a href="{{ route('units.index') }}" class="btn btn-secondary">
                Limpiar
            </a>
        </div>
    </div>

    <div class="form-row mt-3">
        <div class="col-md-12">
            <div class="btn-group" role="group" aria-label="Export buttons">
                <a href="{{ route('exports.unitsexportexcel', ['search' => request('search')]) }}" class="btn btn-success mr-2">
                    <i class="fas fa-file-excel"></i> Exportar Excel
                </a>
                <a href="{{ route('exports.unitsexportpdf', ['search' => request('search')]) }}" class="btn btn-danger">
                    <i class="fas fa-file-pdf"></i> Exportar PDF
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
            <table class="table table-hover">
                <thead class="thead-dark">
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
                                        <button type="submit" class="btn btn-sm btn-outline-danger"
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
                            <button class="btn btn-sm btn-warning mr-1"
                                    data-toggle="modal"
                                    data-target="#editUnitModal{{ $unit->id }}">
                                <i class="fas fa-edit"></i>
                            </button>

                            <button class="btn btn-sm btn-success mr-1"
                                    data-toggle="modal"
                                    data-target="#assignDriverModal{{ $unit->id }}">
                                <i class="fas fa-user-plus"></i>
                            </button>

                            <form action="{{ route('units.destroy', $unit->id) }}"
                                method="POST"
                                style="display:inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"
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
<script>
    $(document).ready(function() {
        $('.select2').select2({
            theme: 'bootstrap4',
            placeholder: 'Seleccione un conductor'
        });

        $('.custom-file-input').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);
        });
    });
</script>
@endpush

@endsection
            