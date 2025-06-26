@extends('adminlte::page')

@section('title', 'Conductores')

@section('content_header')
    <div class="rutvans-content-header rutvans-fade-in">
        <div class="container-fluid">
            <h1>
                <i class="fas fa-id-card me-2"></i> Gestión de Conductores
            </h1>
            <p class="subtitle">Administra los conductores del sistema de transporte</p>
        </div>
    </div>
@endsection

@section('content')
    <div class="rutvans-card rutvans-hover-lift rutvans-fade-in">
        <div class="rutvans-card-header d-flex justify-content-between align-items-center">
            <h3 class="m-0">
                <i class="fas fa-steering-wheel me-2"></i> Conductores Registrados
            </h3>
            <button type="button" class="rutvans-btn rutvans-btn-primary" data-bs-toggle="modal" data-bs-target="#modalCreateDriver">
                <i class="fas fa-plus"></i> Nuevo Conductor
            </button>
        </div>
        
        <div class="rutvans-card-body">
            <div class="rutvans-table-responsive">
                <table id="driversTable" class="rutvans-table">
                    <thead>
                        <tr>
                            <th><i class="fas fa-hashtag me-1"></i> ID</th>
                            <th><i class="fas fa-user me-1"></i> Usuario</th>
                            <th><i class="fas fa-envelope me-1"></i> Correo</th>
                            <th><i class="fas fa-id-card me-1"></i> Licencia</th>
                            <th><i class="fas fa-camera me-1"></i> Foto</th>
                            <th><i class="fas fa-cogs me-1"></i> Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($drivers as $driver)
                            <tr>
                                <td>
                                    <span class="rutvans-badge rutvans-badge-primary">{{ $driver->id }}</span>
                                </td>
                                <td>
                                    <i class="fas fa-user-circle text-muted me-2"></i>
                                    {{ $driver->user->name ?? 'Sin usuario' }}
                                </td>
                                <td>
                                    <i class="fas fa-at text-muted me-2"></i>
                                    {{ $driver->user->email ?? 'Sin correo' }}
                                </td>
                                <td>
                                    <i class="fas fa-id-badge text-muted me-2"></i>
                                    {{ $driver->license }}
                                </td>
                                <td>
                                    @if ($driver->photo)
                                        <img src="{{ asset('storage/' . $driver->photo) }}"
                                            alt="Foto de {{ $driver->user->name ?? '' }}"
                                            class="rounded-circle shadow-sm"
                                            style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                        <div class="d-flex align-items-center justify-content-center rounded-circle bg-light"
                                             style="width: 50px; height: 50px;">
                                            <i class="fas fa-user text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <button type="button" class="rutvans-btn rutvans-btn-warning rutvans-btn-sm btn-edit-driver"
                                            data-id="{{ $driver->id }}" 
                                            data-name="{{ $driver->user->name ?? '' }}"
                                            data-email="{{ $driver->user->email ?? '' }}" 
                                            data-license="{{ $driver->license }}"
                                            data-photo="{{ $driver->photo ? asset('storage/' . $driver->photo) : '' }}">
                                            <i class="fas fa-edit"></i> Editar
                                        </button>
                                        
                                        <form action="{{ route('drivers.destroy', $driver) }}" method="POST" class="d-inline"
                                            onsubmit="return confirm('¿Seguro que quieres eliminar este conductor?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="rutvans-btn rutvans-btn-danger rutvans-btn-sm" type="submit">
                                                <i class="fas fa-trash"></i> Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">
                                    <div class="rutvans-alert rutvans-alert-info text-center py-5">
                                        <i class="fas fa-car-side mb-3" style="font-size: 4rem; opacity: 0.5;"></i>
                                        <h4>No hay conductores registrados</h4>
                                        <p class="mb-0">Agrega el primer conductor para comenzar a gestionar el transporte</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @include('empleados.drivers.create')
    @include('empleados.drivers.edit')
@endsection

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/rutvans-admin.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <style>
        body {
            background-color: var(--rutvans-background);
        }
        
        .content-wrapper {
            background-color: var(--rutvans-background);
        }
    </style>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#driversTable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
                },
                responsive: true,
                autoWidth: false
            });
        });

        const modalEditDriver = new bootstrap.Modal(document.getElementById('modalEditDriver'));
        const formEditDriver = document.getElementById('formEditDriver');

        document.querySelectorAll('.btn-edit-driver').forEach(button => {
            button.addEventListener('click', function() {
                const driver = this.dataset;

                document.getElementById('edit_driver_id').value = driver.id;
                document.getElementById('edit_name').value = driver.name;
                document.getElementById('edit_email').value = driver.email;
                document.getElementById('edit_license').value = driver.license;
                document.getElementById('current_photo_preview').src = driver.photo || '';

                formEditDriver.action = "{{ url('drivers') }}/" + driver.id;

                modalEditDriver.show();
            });
        });
    </script>
@endsection
