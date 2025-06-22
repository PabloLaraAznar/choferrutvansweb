@extends('adminlte::page')

@section('title', 'Coordinadores')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="m-0">
            <i class="fas fa-user-shield me-2 text-primary"></i> Gestión de Coordinadores
        </h1>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCreateCoordinate">
            <i class="fas fa-plus me-1"></i> Nuevo Coordinador
        </button>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">Lista de Coordinadores</div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Código de empleado</th>
                        <th>Foto</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($coordinators as $coordinate)
                        <tr>
                            <td>{{ $coordinate->id }}</td>
                            <td>{{ $coordinate->user->name ?? 'Sin usuario' }}</td>
                            <td>{{ $coordinate->user->email ?? 'Sin correo' }}</td>
                            <td>{{ $coordinate->employee_code }}</td>
                            <td>
                                @if ($coordinate->photo)
                                    <img src="{{ asset('storage/' . $coordinate->photo) }}"
                                        alt="Foto de {{ $coordinate->user->name ?? '' }}"
                                        style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%;">
                                @else
                                    <span class="text-muted">Sin foto</span>
                                @endif
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-warning btn-edit-coordinate"
                                    data-id="{{ $coordinate->id }}" data-name="{{ $coordinate->user->name ?? '' }}"
                                    data-email="{{ $coordinate->user->email ?? '' }}"
                                    data-photo="{{ $coordinate->photo ? asset('storage/' . $coordinate->photo) : '' }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('coordinates.destroy', $coordinate) }}" method="POST"
                                    class="d-inline"
                                    onsubmit="return confirm('¿Seguro que quieres eliminar este coordinador?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" type="submit">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @include('empleados.coordinates.create')
    @include('empleados.coordinates.edit')
@endsection

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
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
