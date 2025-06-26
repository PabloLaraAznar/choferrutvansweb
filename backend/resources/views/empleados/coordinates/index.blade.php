@extends('adminlte::page')

@section('title', 'Coordinadores')

@section('content_header')
    <div class="rutvans-content-header rutvans-fade-in">
        <div class="container-fluid">
            <h1>
                <i class="fas fa-user-tie me-2"></i> Gestión de Coordinadores
            </h1>
            <p class="subtitle">Administra los coordinadores responsables de las operaciones</p>
        </div>
    </div>
@endsection

@section('content')
    <div class="rutvans-card rutvans-hover-lift rutvans-fade-in">
        <div class="rutvans-card-header d-flex justify-content-between align-items-center">
            <h3 class="m-0">
                <i class="fas fa-users-cog me-2"></i> Coordinadores del Sistema
            </h3>
            <button type="button" class="rutvans-btn rutvans-btn-primary" data-bs-toggle="modal" data-bs-target="#modalCreateCoordinate">
                <i class="fas fa-plus"></i> Nuevo Coordinador
            </button>
        </div>
        
        <div class="rutvans-card-body">
            <div class="rutvans-table-responsive">
                <table id="coordinatesTable" class="rutvans-table">
                    <thead>
                        <tr>
                            <th><i class="fas fa-hashtag me-1"></i> ID</th>
                            <th><i class="fas fa-user me-1"></i> Nombre</th>
                            <th><i class="fas fa-envelope me-1"></i> Correo</th>
                            <th><i class="fas fa-id-badge me-1"></i> Código</th>
                            <th><i class="fas fa-camera me-1"></i> Foto</th>
                            <th><i class="fas fa-cogs me-1"></i> Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($coordinators as $coordinate)
                            <tr>
                                <td>
                                    <span class="rutvans-badge rutvans-badge-info">{{ $coordinate->id }}</span>
                                </td>
                                <td>
                                    <i class="fas fa-user-circle text-muted me-2"></i>
                                    {{ $coordinate->user->name ?? 'Sin usuario' }}
                                </td>
                                <td>
                                    <i class="fas fa-at text-muted me-2"></i>
                                    {{ $coordinate->user->email ?? 'Sin correo' }}
                                </td>
                                <td>
                                    <span class="rutvans-badge rutvans-badge-secondary">{{ $coordinate->employee_code }}</span>
                                </td>
                                <td>
                                    @if ($coordinate->photo)
                                        <img src="{{ asset('storage/' . $coordinate->photo) }}"
                                            alt="Foto de {{ $coordinate->user->name ?? '' }}"
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
                                        <button type="button" class="rutvans-btn rutvans-btn-warning rutvans-btn-sm btn-edit-coordinate"
                                            data-id="{{ $coordinate->id }}" 
                                            data-name="{{ $coordinate->user->name ?? '' }}"
                                            data-email="{{ $coordinate->user->email ?? '' }}"
                                            data-photo="{{ $coordinate->photo ? asset('storage/' . $coordinate->photo) : '' }}">
                                            <i class="fas fa-edit"></i> Editar
                                        </button>
                                        
                                        <form action="{{ route('coordinates.destroy', $coordinate) }}" method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('¿Seguro que quieres eliminar este coordinador?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="rutvans-btn rutvans-btn-danger rutvans-btn-sm" type="submit">
                                                <i class="fas fa-trash"></i> Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @include('empleados.coordinates.create')
    @include('empleados.coordinates.edit')
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
            $('#coordinatesTable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
                },
                responsive: true,
                autoWidth: false
            });
        });

        const modalEditCoordinate = new bootstrap.Modal(document.getElementById('modalEditCoordinate'));
        const formEditCoordinate = document.getElementById('formEditCoordinate');

        document.querySelectorAll('.btn-edit-coordinate').forEach(button => {
            button.addEventListener('click', function() {
                const coordinate = this.dataset;

                document.getElementById('edit_coordinate_id').value = coordinate.id;
                document.getElementById('edit_name').value = coordinate.name;
                document.getElementById('edit_email').value = coordinate.email;
                document.getElementById('current_photo_preview').src = coordinate.photo || '';

                formEditCoordinate.action = "{{ url('coordinates') }}/" + coordinate.id;

                modalEditCoordinate.show();
            });
        });
    </script>
@endsection
