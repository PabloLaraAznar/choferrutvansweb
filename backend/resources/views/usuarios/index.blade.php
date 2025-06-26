@extends('adminlte::page')

@section('title', 'Gestión de Usuarios')

@section('content_header')
    <div class="rutvans-content-header rutvans-fade-in">
        <div class="container-fluid">
            <h1>
                <i class="fas fa-users me-2"></i> Gestión de Usuarios
            </h1>
            <p class="subtitle">Administra todos los usuarios del sistema</p>
        </div>
    </div>
@endsection

@section('content')
    <div class="rutvans-card rutvans-hover-lift rutvans-fade-in">
        <div class="rutvans-card-header d-flex justify-content-between align-items-center">
            <h3 class="m-0">
                <i class="fas fa-user-cog me-2"></i> Administración de Usuarios
            </h3>
            <button type="button" class="rutvans-btn rutvans-btn-primary" data-bs-toggle="modal" data-bs-target="#createUsuarioModal">
                <i class="fas fa-user-plus"></i> Crear Usuario
            </button>
        </div>
        
        <div class="rutvans-card-body">
            <div class="table-responsive">
                <table id="usuariosTable" class="table rutvans-table table-striped table-hover align-middle">
                    <thead>
                        <tr>
                            <th><i class="fas fa-hashtag me-1"></i> ID</th>
                            <th><i class="fas fa-user me-1"></i> Nombre</th>
                            <th><i class="fas fa-envelope me-1"></i> Email</th>
                            <th><i class="fas fa-phone me-1"></i> Teléfono</th>
                            <th><i class="fas fa-map-marker-alt me-1"></i> Dirección</th>
                            <th><i class="fas fa-calendar-plus me-1"></i> Creado</th>
                            <th><i class="fas fa-calendar-check me-1"></i> Actualizado</th>
                            <th class="text-center"><i class="fas fa-cogs me-1"></i> Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($usuarios as $usuario)
                            <tr>
                                <td>
                                    <span class="rutvans-badge rutvans-badge-primary">{{ $usuario->id }}</span>
                                </td>
                                <td>
                                    <i class="fas fa-user-circle text-muted me-2"></i>
                                    {{ e($usuario->name) }}
                                </td>
                                <td>
                                    <i class="fas fa-at text-muted me-2"></i>
                                    {{ e($usuario->email) }}
                                </td>
                                <td>
                                    <i class="fas fa-mobile-alt text-muted me-2"></i>
                                    {{ e($usuario->phone_number) }}
                                </td>
                                <td>
                                    <i class="fas fa-home text-muted me-2"></i>
                                    {{ e($usuario->address) }}
                                </td>
                                <td>
                                    <small class="text-muted">{{ $usuario->created_at->format('d/m/Y H:i') }}</small>
                                </td>
                                <td>
                                    <small class="text-muted">{{ $usuario->updated_at->format('d/m/Y H:i') }}</small>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <button class="rutvans-btn rutvans-btn-warning rutvans-btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editUsuarioModal"
                                            data-id="{{ $usuario->id }}"
                                            data-name="{{ e($usuario->name) }}"
                                            data-email="{{ e($usuario->email) }}"
                                            data-phone="{{ e($usuario->phone_number) }}"
                                            data-address="{{ e($usuario->address) }}">
                                            <i class="fas fa-edit"></i> Editar
                                        </button>
                                        
                                        <button class="rutvans-btn rutvans-btn-danger rutvans-btn-sm" onclick="confirmDelete({{ $usuario->id }})">
                                            <i class="fas fa-trash-alt"></i> Eliminar
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @include('usuarios.create')
    @include('usuarios.edit')
@endsection

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="{{ asset('css/rutvans-admin.css') }}" rel="stylesheet">
    <style>
        body {
            background-color: var(--rutvans-background);
        }
        
        .content-wrapper {
            background-color: var(--rutvans-background);
        }
    </style>

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