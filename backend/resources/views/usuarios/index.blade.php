@extends('adminlte::page')

@section('title', 'Gestión de Usuarios')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="m-0">
            <i class="fas fa-users me-2 text-orange"></i> Gestión de Usuarios
        </h1>
    </div>
@endsection

@section('content')
    <div class="card shadow rounded-3">
        <div class="card-header bg-dark text-white">
            <h3 class="card-title m-0"> Administra los Usuarios</h3>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <button type="button" class="btn btn-orange" data-bs-toggle="modal" data-bs-target="#createUsuarioModal">
                    <i class="fas fa-user-plus me-1"></i> Crear Usuario
                </button>
            </div>
            <table id="usuariosTable" class="table table-striped table-hover table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width: 5%">ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Dirección</th>
                        <th>Creado</th>
                        <th>Actualizado</th>
                        <th class="text-center" style="width: 15%">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($usuarios as $usuario)
                        <tr>
                            <td>{{ $usuario->id }}</td>
                            <td>{{ e($usuario->name) }}</td>
                            <td>{{ e($usuario->email) }}</td>
                            <td>{{ e($usuario->phone_number) }}</td>
                            <td>{{ e($usuario->address) }}</td>
                            <td>{{ $usuario->created_at }}</td>
                            <td>{{ $usuario->updated_at }}</td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center align-items-center" style="gap: 0.5rem;">
                                <button class="btn btn-warning d-flex align-items-center justify-content-center"
                                    style="width: 40px; height: 40px;"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editUsuarioModal"
                                    data-id="{{ $usuario->id }}"
                                    data-name="{{ e($usuario->name) }}"
                                    data-email="{{ e($usuario->email) }}"
                                    data-phone="{{ e($usuario->phone_number) }}"
                                    data-address="{{ e($usuario->address) }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-danger d-flex align-items-center justify-content-center"
                                style="width: 40px; height: 40px;"onclick="confirmDelete({{ $usuario->id }})">
                                <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @include('usuarios.create')
    @include('usuarios.edit')
@endsection

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <style>
        .btn-orange {
            background-color:#FF6700;
            color: white !important;
            border: none !important;
        }

        /* Para mantener el color en todos los estados */
        .btn-orange:hover,
        .btn-orange:focus,
        .btn-orange:active,
        .btn-orange:visited {
            background-color: #FF6700;
            color: white !important;
            border: none !important;
            box-shadow: none !important;
        }

        /* SweetAlert2 custom button styles */
        .swal2-confirm-red {
            background-color: #d33 !important; /* Red */
            color: white !important;
            border: none !important;
            padding: 10px 20px !important; /* Increase padding for size */
            font-size: 1em !important; /* Adjust font size if needed */
            outline: none !important; /* Remove focus outline */
            box-shadow: none !important; /* Ensure no residual shadow */
        }

        .swal2-cancel-black {
            background-color: #000000 !important; /* Black */
            color: white !important;
            border: none !important;
            padding: 10px 20px !important; /* Increase padding for size */
            font-size: 1em !important; /* Adjust font size if needed */
            outline: none !important; /* Remove focus outline */
            box-shadow: none !important; /* Ensure no residual shadow */
        }

        /* Espacio entre botones de SweetAlert2 */
        .swal2-actions {
            gap: 15px; /* Adjust the desired space between buttons, increased slightly */
            margin-top: 20px; /* Add some space above the buttons if they are too close to the text */
        }

        /* Also target the buttons directly to catch any specific pseudo-classes */
        .swal2-confirm-red:focus,
        .swal2-confirm-red:active,
        .swal2-cancel-black:focus,
        .swal2-cancel-black:active {
            outline: none !important;
            box-shadow: none !important;
            border: none !important; /* Redundant but good for safety */
        }
    </style>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            $('#usuariosTable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
                },
                responsive: true,
                autoWidth: false
            });

            document.querySelectorAll('[data-bs-target="#editUsuarioModal"]').forEach(button => {
                button.addEventListener('click', (event) => {
                    const target = event.currentTarget;
                    const form = document.getElementById('editUsuarioForm');

                    form.action = `/usuarios/${target.getAttribute('data-id')}`;
                    form.querySelector('#editUsuarioId').value = target.getAttribute('data-id');
                    form.querySelector('#editUsuarioNombre').value = target.getAttribute('data-name');
                    form.querySelector('#editUsuarioEmail').value = target.getAttribute('data-email');
                    form.querySelector('#editUsuarioTelefono').value = target.getAttribute('data-phone');
                    form.querySelector('#editUsuarioDireccion').value = target.getAttribute('data-address');

                    form.classList.remove('was-validated');
                });
            });

            document.getElementById('createUsuarioModal').addEventListener('hidden.bs.modal', () => {
                const form = document.getElementById('createUsuarioForm');
                form.reset();
                form.classList.remove('was-validated');
            });

            document.getElementById('editUsuarioModal').addEventListener('hidden.bs.modal', () => {
                const form = document.getElementById('editUsuarioForm');
                form.reset();
                form.classList.remove('was-validated');
            });
        });

        function confirmDelete(id) {
            Swal.fire({
                title: '¿Eliminar este usuario?',
                text: "Esta acción no se puede deshacer.",
                icon: "error",
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                buttonsStyling: false, // Disable SweetAlert2's default button styling
                customClass: {
                    confirmButton: 'swal2-confirm-red', // Custom class for the confirm button
                    cancelButton: 'swal2-cancel-black' // Custom class for the cancel button
                },
                width: '500px'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/usuarios/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json'
                        }
                    }).then(response => response.json())
                    .then(data => {
                        Swal.fire({
                            icon: data.success ? 'success' : 'error',
                            title: data.message,
                            toast: true,
                            position: 'bottom-end',
                            showConfirmButton: false,
                            timer: 2000
                        }).then(() => location.reload());
                    });
                }
            });
        }
    </script>
@endsection