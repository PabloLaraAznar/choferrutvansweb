@extends('adminlte::page')

@section('title', 'Permisos')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center p-3 mb-3"
        style="background: linear-gradient(135deg, #ff6600, #e55a00); color: white; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <div>
            <h1 class="mb-1"
                style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 1.8rem;">
                <i class="fas fa-lock me-2"></i> Gestión de Permisos
            </h1>
            <p class="mb-0" style="opacity: 0.9; font-size: 0.95rem;">Administra los permisos del sistema
                Rutvans</p>
        </div>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm"
                    style="border: none; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1) !important;">
                    <div class="card-header d-flex justify-content-between align-items-center"
                        style="background: linear-gradient(135deg, #ff6600, #e55a00); color: white; border-radius: 12px 12px 0 0; padding: 1.5rem;">
                        <h3 class="mb-0" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                            <i class="fas fa-lock me-2"></i> Permisos del Sistema
                        </h3>
                        <div class="ml-auto">
                            <button type="button" class="btn btn-light" data-bs-toggle="modal"
                                data-bs-target="#createPermissionModal" style="font-weight: 600;">
                                <i class="fas fa-plus-circle me-1"></i> Agregar Permiso
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert"
                                style="border-radius: 8px;">
                                <i class="fas fa-check-circle mr-2"></i>
                                {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert">
                                    <span>&times;</span>
                                </button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert"
                                style="border-radius: 8px;">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                {{ session('error') }}
                                <button type="button" class="close" data-dismiss="alert">
                                    <span>&times;</span>
                                </button>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table id="permissionsTable"
                                class="table table-bordered table-striped table-hover"
                                style="border-radius: 8px; overflow: hidden;">
                                <thead style="background: linear-gradient(135deg, #6c757d, #495057); color: white;">
                                    <tr>
                                        <th style="width: 10%">ID</th>
                                        <th>Nombre</th>
                                        <th class="text-center" style="width: 20%">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($permissions as $permission)
                                        <tr>
                                            <td class="text-center">{{ $permission->id }}</td>
                                            <td><strong>{{ $permission->name }}</strong></td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <button class="btn btn-sm btn-outline-info"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editPermissionModal"
                                                        data-permission-id="{{ $permission->id }}"
                                                        data-permission-name="{{ $permission->name }}">
                                                        <i class="fas fa-edit me-1"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-danger"
                                                        data-permission-id="{{ $permission->id }}"
                                                        onclick="confirmDelete(this)">
                                                        <i class="fas fa-trash-alt me-1"></i>
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
            </div>
        </div>
    </div>
    @include('roles-permissions.permissions.create')
    @include('roles-permissions.permissions.edit')
@endsection

@section('css')
    <!-- SweetAlert2 & DataTables -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
@endsection

@section('js')
    <!-- jQuery (necesario para DataTables) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap 5 debe cargarse después de jQuery si ambos se usan -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializar DataTables
            $('#permissionsTable').DataTable({
                language: {
                    url: '/datatables/es-ES.json'
                },
                responsive: true,
                autoWidth: false
            });

            // Mostrar datos en modal Editar
            document.querySelectorAll('[data-bs-target="#editPermissionModal"]').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-permission-id');
                    const name = this.getAttribute('data-permission-name');

                    document.getElementById('editPermissionId').value = id;
                    document.getElementById('editPermissionName').value = name;
                    document.getElementById('editPermissionForm').action = `/permissions/${id}`;
                });
            });

            // Validación Bootstrap para formularios
            // Crear
            const createForm = document.getElementById('createPermissionForm');
            createForm.addEventListener('submit', function(event) {
                if (!this.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                this.classList.add('was-validated');
            });

            // Editar
            const editForm = document.getElementById('editPermissionForm');
            editForm.addEventListener('submit', function(event) {
                if (!this.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                this.classList.add('was-validated');
            });

            // Limpiar formulario Crear al cerrar modal
            const createModal = document.getElementById('createPermissionModal');
            createModal.addEventListener('hidden.bs.modal', () => {
                createForm.reset();
                createForm.classList.remove('was-validated');
            });

            // Limpiar formulario Editar al cerrar modal
            const editModal = document.getElementById('editPermissionModal');
            editModal.addEventListener('hidden.bs.modal', () => {
                editForm.reset();
                editForm.classList.remove('was-validated');
            });
        });

        function confirmDelete(button) {
            const id = button.getAttribute('data-permission-id');

            Swal.fire({
                title: '¿Eliminar este permiso?',
                text: "Esta acción no se puede revertir.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                width: '500px'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Crear formulario tradicional para DELETE
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/permissions/${id}`;

                    // Token CSRF
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    form.appendChild(csrfToken);

                    // Método DELETE
                    const methodField = document.createElement('input');
                    methodField.type = 'hidden';
                    methodField.name = '_method';
                    methodField.value = 'DELETE';
                    form.appendChild(methodField);

                    // Agregar al DOM y enviar formulario
                    document.body.appendChild(form);
                    form.addEventListener('submit', async function(event) {
                        event.preventDefault();

                        try {
                            const response = await fetch(form.action, {
                                method: 'POST',
                                body: new FormData(form),
                            });

                            const result = await response.json();

                            if (result.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: result.message,
                                    toast: true,
                                    position: 'bottom-end',
                                    showConfirmButton: false,
                                    timer: 2000,
                                    width: '300px'
                                }).then(() => location.reload());
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: result.message,
                                    toast: true,
                                    position: 'bottom-end',
                                    showConfirmButton: false,
                                    timer: 2000,
                                    width: '300px'
                                });
                            }
                        } catch (error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error al eliminar el permiso.',
                                toast: true,
                                position: 'bottom-end',
                                showConfirmButton: false,
                                timer: 2000,
                                width: '300px'
                            });
                        }
                    });
                    form.submit();
                }
            });
        }
    </script>

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: '{{ session('success') }}',
                toast: true,
                position: 'bottom-end',
                showConfirmButton: false,
                timer: 2000,
                width: '300px'
            });
        </script>
    @endif

@endsection
