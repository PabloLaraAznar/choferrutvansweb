@extends('adminlte::page')

@section('title', 'Conductores')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="m-0">
            <i class="fas fa-user-shield me-2 text-primary"></i> Gestión de Chóferes
        </h1>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCreateDriver">
            <i class="fas fa-plus me-1"></i> Nuevo Chófer
        </button>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Correo</th>
                        <th>Licencia</th>
                        <th>Foto</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($drivers as $driver)
                        <tr>
                            <td>{{ $driver->id }}</td>
                            <td>{{ $driver->user->name ?? 'Sin usuario' }}</td>
                            <td>{{ $driver->user->email ?? 'Sin correo' }}</td>
                            <td>{{ $driver->license }}</td>
                            <td>
                                @if ($driver->photo)
                                    <img src="{{ asset('storage/' . $driver->photo) }}"
                                        alt="Foto de {{ $driver->user->name ?? '' }}"
                                        style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%;">
                                @else
                                    <span class="text-muted">Sin foto</span>
                                @endif
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-warning btn-edit-driver"
                                    data-id="{{ $driver->id }}" data-name="{{ $driver->user->name ?? '' }}"
                                    data-email="{{ $driver->user->email ?? '' }}" data-license="{{ $driver->license }}"
                                    data-photo="{{ $driver->photo ? asset('storage/' . $driver->photo) : '' }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('drivers.destroy', $driver) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('¿Seguro que quieres eliminar este chófer?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" type="submit">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">No hay chóferes registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @include('empleados.drivers.create')
    @include('empleados.drivers.edit')
@endsection

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
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
