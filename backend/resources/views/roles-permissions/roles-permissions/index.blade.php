@extends('adminlte::page')

@section('title', 'Asignar Permisos')

@section('content_header')
    <h1>Asignar Permisos a Roles</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title m-0">Administra la asignación de permisos</h3>
        </div>
        <div class="card-body">
            <livewire:roles-permissions-componente />
        </div>
    </div>
@endsection

@section('css')
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
@endsection

@section('js')
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery (necesario para DataTables) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        let dataTable;

        // Función para inicializar DataTables
        function initializeDataTable() {
            if ($.fn.dataTable.isDataTable('#rolesTable')) {
                $('#rolesTable').DataTable().destroy(); // Destruir instancia previa si existe
            }

            dataTable = $('#rolesTable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json' // Traducción al español
                },
                responsive: true, // Hacer la tabla responsiva
                autoWidth: false, // Evitar que se ajuste automáticamente el ancho
            });
        }

        // Inicializar DataTables al cargar la página
        document.addEventListener('DOMContentLoaded', function() {
            initializeDataTable();

            // Reaplicar DataTables después de que Livewire actualice la tabla
            Livewire.on('message.processed', () => {
                initializeDataTable();
            });

            // SweetAlert para alertas normales
            Livewire.on('alert', (data) => {
                const alertData = Array.isArray(data) ? data[0] : data;
                Swal.fire({
                    icon: alertData.icon,
                    title: alertData.message,
                    showConfirmButton: false,
                    timer: 1500,
                });
            });

            // SweetAlert para confirmación de eliminación
            Livewire.on('confirmDelete', ({
                roleId
            }) => {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "¡No podrás deshacer esta acción!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar',
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.emit('deleteConfirmed', {
                            id: roleId
                        }); // Usar emit en lugar de dispatch
                    }
                });
            });
        });
    </script>


@endsection
