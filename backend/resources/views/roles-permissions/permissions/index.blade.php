@extends('adminlte::page')

@section('title', 'Permisos')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="m-0">
            <i class="fas fa-lock me-2 text-primary"></i> Gestión de Permisos
        </h1>
    </div>
@endsection

@section('content')
    <div class="card shadow rounded-3">
        <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
            <h3 class="card-title m-0">🔐 Administra tus permisos</h3>
            <button type="button" class="btn btn-light text-primary fw-bold" data-bs-toggle="modal"
                data-bs-target="#createPermissionModal">
                <i class="fas fa-plus-circle me-1"></i> Agregar Permiso
            </button>
        </div>

        <div class="card-body">
            <table id="permissionsTable" class="table table-striped table-hover table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width: 10%">ID</th>
                        <th>Nombre</th>
                        <th class="text-center" style="width: 20%">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($permissions as $permission)
                        <tr>
                            <td>{{ $permission->id }}</td>
                            <td>{{ $permission->name }}</td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal"
                                        data-bs-target="#editPermissionModal" data-permission-id="{{ $permission->id }}"
                                        data-permission-name="{{ $permission->name }}">
                                        <i class="fas fa-edit me-1"></i>
                                    </button>

                                    <button class="btn btn-sm btn-outline-danger" data-permission-id="{{ $permission->id }}"
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

    @include('roles-permissions.permissions.edit')
    @include('roles-permissions.permissions.create')
@endsection

@section('css')
    <!-- SweetAlert2 & DataTables -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery (necesario para DataTables) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
                    url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
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
                    fetch(`/permissions/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content'),
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            console.log("Respuesta del servidor:", data);

                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: data.message,
                                    toast: true,
                                    position: 'bottom-end',
                                    showConfirmButton: false,
                                    timer: 2000,
                                    width: '300px'
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: data.message,
                                    showConfirmButton: true,
                                    width: '500px'
                                });
                            }
                        })
                        .catch(error => {
                            console.error("Error eliminando permiso:", error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Hubo un problema al eliminar el permiso',
                                showConfirmButton: true,
                                width: '500px'
                            });
                        });
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
