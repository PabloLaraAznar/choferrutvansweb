@extends('adminlte::page')

@section('title', 'Roles')

@section('content_header')
    <h1>Roles</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title m-0">Administra tus roles</h3>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createRoleModal">
                Crear Rol
            </button>
        </div>
        <div class="card-body">
            <table id="rolesTable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($roles as $role)
                        <tr>
                            <td>{{ $role->id }}</td>
                            <td>{{ e($role->name) }}</td>
                            <td class="text-center">
                                <button class="btn btn-info btn-sm text-white" data-bs-toggle="modal"
                                    data-bs-target="#editRoleModal" data-role-id="{{ $role->id }}"
                                    data-role-name="{{ e($role->name) }}" aria-label="Editar rol {{ e($role->name) }}">
                                    Editar
                                </button>

                                <button class="btn btn-danger btn-sm" data-role-id="{{ $role->id }}"
                                    onclick="confirmDelete(this)" aria-label="Eliminar rol {{ e($role->name) }}">
                                    Eliminar
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @include('roles-permissions.roles.edit') <!-- Modales -->
    @include('roles-permissions.roles.create')
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
            // Inicializar DataTables con idioma español
            $('#rolesTable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
                },
                responsive: true,
                autoWidth: false
            });

            // Edición: cargar datos en modal
            document.querySelectorAll('[data-bs-target="#editRoleModal"]').forEach(button => {
                button.addEventListener('click', ({
                    target
                }) => {
                    const id = target.dataset.roleId;
                    const name = target.dataset.roleName;

                    const form = document.getElementById('editRoleForm');
                    form.action = `/roles/${id}`;
                    form.querySelector('#editRoleId').value = id;
                    form.querySelector('#editRoleName').value = name;

                    // Resetear validación visual al abrir modal de editar
                    form.classList.remove('was-validated');
                });
            });

            // Limpiar formulario Crear cuando se cierra el modal
            const createModal = document.getElementById('createRoleModal');
            createModal.addEventListener('hidden.bs.modal', () => {
                const createForm = document.getElementById('createRoleForm');
                createForm.reset();
                createForm.classList.remove('was-validated');
            });

            // Limpiar formulario Editar cuando se cierra el modal
            const editModal = document.getElementById('editRoleModal');
            editModal.addEventListener('hidden.bs.modal', () => {
                const editForm = document.getElementById('editRoleForm');
                editForm.reset();
                editForm.classList.remove('was-validated');
            });

            // Validación Bootstrap para formulario Crear Rol
            const createForm = document.getElementById('createRoleForm');
            createForm.addEventListener('submit', function(event) {
                if (!this.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                this.classList.add('was-validated');
            });

            // Validación Bootstrap para formulario Editar Rol
            const editForm = document.getElementById('editRoleForm');
            editForm.addEventListener('submit', function(event) {
                if (!this.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                this.classList.add('was-validated');
            });
        });

        // Función para confirmar y ejecutar eliminación con SweetAlert2 y fetch
        function confirmDelete(button) {
            const id = button.getAttribute('data-role-id');

            Swal.fire({
                title: '¿Eliminar este rol?',
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
                    fetch(`/roles/${id}`, {
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
                                    // Recargar página para evitar problemas con paginación vacía
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
                            console.error("Error eliminando rol:", error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Hubo un problema al eliminar el rol',
                                showConfirmButton: true,
                                width: '500px'
                            });
                        });
                }
            });
        }
    </script>

    @if ($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error de validación',
                html: `{!! implode('<br>', $errors->all()) !!}`,
                width: '500px'
            });
        </script>
    @endif

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
