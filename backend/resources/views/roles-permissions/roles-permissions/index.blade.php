@extends('adminlte::page')

@section('title', 'Asignar Permisos')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="m-0">
            <i class="fas fa-tasks me-2 text-primary"></i> Asignar Permisos a Roles
        </h1>
    </div>
@endsection

@section('content')
    <div class="card shadow rounded-3">
        <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
            <h3 class="card-title m-0">
                🔧 Administra la asignación de permisos
            </h3>
        </div>

        <div class="card-body">
            <table id="rolesPermissionsTable" class="table table-striped table-hover table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width: 25%">Rol</th>
                        <th>Permisos Asignados</th>
                        <th class="text-center" style="width: 15%">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($roles as $role)
                        <tr>
                            <td class="fw-bold">{{ $role->name }}</td>
                            <td>
                                @forelse ($role->permissions as $permission)
                                    <span class="badge bg-success mb-1">{{ $permission->name }}</span>
                                @empty
                                    <span class="text-muted">Sin permisos asignados</span>
                                @endforelse
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal"
                                    data-bs-target="#editPermissionModal" data-role-id="{{ $role->id }}">
                                    <i class="fas fa-pen me-1"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @include('roles-permissions.roles-permissions.edit') <!-- Modal para edición de permisos -->
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
            $('#rolesPermissionsTable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
                },
                responsive: true,
                autoWidth: false
            });

            document.querySelectorAll('[data-bs-target="#editPermissionModal"]').forEach(button => {
                button.addEventListener('click', function() {
                    const roleId = this.getAttribute('data-role-id');
                    const form = document.getElementById('editPermissionForm');
                    const permissionsContainer = document.getElementById('permissionsContainer');

                    // Configura acción del formulario
                    form.action = `/roles-permissions/${roleId}`;
                    document.getElementById('editRoleId').value = roleId;

                    // Limpia contenedor y muestra cargando
                    permissionsContainer.innerHTML =
                        '<div class="text-muted">Cargando permisos...</div>';

                    // AJAX para obtener datos
                    fetch(`/roles-permissions/${roleId}/edit`)
                        .then(response => response.json())
                        .then(data => {
                            permissionsContainer.innerHTML = ''; // Limpia contenedor

                            data.permissions.forEach(permission => {
                                const isChecked = data.assigned_permissions.includes(
                                    permission.id);
                                const checkbox = `
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" value="${permission.id}" ${isChecked ? 'checked' : ''}>
                                    <label class="form-check-label">${permission.name}</label>
                                </div>
                            `;
                                permissionsContainer.insertAdjacentHTML('beforeend',
                                    checkbox);
                            });
                        })
                        .catch(error => {
                            console.error('Error al cargar permisos:', error);
                            permissionsContainer.innerHTML =
                                '<div class="text-danger">Error al cargar permisos.</div>';
                        });
                });
            });

            // Limpieza al cerrar modal
            document.getElementById('editPermissionModal').addEventListener('hidden.bs.modal', function() {
                document.getElementById('permissionsContainer').innerHTML = '';
            });
        });
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
