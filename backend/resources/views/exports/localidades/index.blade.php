@extends('adminlte::page')

@section('title', 'Datos Localidades')

@section('content_header')
    <h1>Datos de Localidades</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Lista de Localidades</h3>
            <!-- Botones de exportación -->
            <div class="float-right">
                <label for="filter-date" class="mr-2">Fecha específica:</label>
                <input type="date" id="filter-date" class="form-control mr-2" style="display: inline-block; width: auto;">

                <a href="#" id="export-pdf" class="btn btn-danger">
                    <i class="fas fa-file-pdf"></i> Exportar PDF
                </a>
                <a href="#" id="export-excel" class="btn btn-success">
                    <i class="fas fa-file-excel"></i> Exportar Excel
                </a>
            </div>

        </div>
        <div class="card-body">
            <!-- Tabla de Localidades -->
            <table id="localidades-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Longitud</th>
                        <th>Latitud</th>
                        <th>Localidad</th>
                        <th>Calle</th>
                        <th>Código Postal</th>
                        <th>Fecha</th> <!-- Nueva columna para la fecha -->
                    </tr>
                </thead>
                <tbody>
                    @foreach ($localidades as $localidad)
                        <tr>
                            <td>{{ $localidad->id }}</td>
                            <td>{{ $localidad->longitude }}</td>
                            <td>{{ $localidad->latitude }}</td>
                            <td>{{ $localidad->locality }}</td>
                            <td>{{ $localidad->street }}</td>
                            <td>{{ $localidad->postal_code }}</td>
                            <td>{{ \Carbon\Carbon::parse($localidad->created_at)->format('Y-m-d') }}</td>
                            <!-- Formato solo de fecha -->
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
    </div>
    <!-- Paginación -->
    <div class="d-flex justify-content-center mt-3">
        {{ $localidades->links() }}
    </div>
@stop

@section('css')
    <!-- Estilos de DataTables con Bootstrap -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
@stop

@section('js')
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            const table = $('#localidades-table').DataTable({
                "responsive": true,
                "autoWidth": false,
                "language": {
                    "url": "/assets/datatables/Spanish.json" // Ruta local del archivo de idioma
                },
                "paging": false, // Desactivamos la paginación de DataTables porque Laravel la maneja
                "searching": true, // Habilitar búsqueda dinámica
                "ordering": true // Habilitar ordenamiento
            });

            // Filtrar por fecha específica
            $('#filter-date').on('change', function() {
                const selectedDate = $(this).val(); // Obtener la fecha seleccionada en formato YYYY-MM-DD
                if (selectedDate) {
                    table.column(6).search('^' + selectedDate, true, false)
                        .draw(); // Filtrar buscando al inicio de la columna Fecha
                } else {
                    table.column(6).search('').draw(); // Reiniciar el filtro si se borra la fecha
                }
            });


            // Exportar PDF con el filtro activo
            $('#export-pdf').on('click', function() {
                const search = table.search(); // Obtiene el término de búsqueda
                const selectedDate = $('#filter-date').val(); // Obtiene la fecha seleccionada

                // Construye la URL con parámetros
                let url = "{{ route('exports.pdf.localidades') }}";

                if (search) {
                    url += "?search=" + encodeURIComponent(search);
                }
                if (selectedDate) {
                    url += (search ? "&" : "?") + "start_date=" + encodeURIComponent(selectedDate);
                }

                window.location.href = url; // Redirige al controlador con los parámetros
            });
            $('#export-excel').on('click', function() {
                const search = table.search(); // Obtiene el término de búsqueda
                const selectedDate = $('#filter-date').val(); // Obtiene la fecha seleccionada

                // Construye la URL con parámetros
                let url = "{{ route('exports.excel.localidades') }}";

                if (search) {
                    url += "?search=" + encodeURIComponent(search);
                }
                if (selectedDate) {
                    url += search ? "&" : "?";
                    url += "start_date=" + encodeURIComponent(selectedDate);
                }

                window.location.href = url; // Redirige al controlador con los parámetros
            });

            // SweetAlert: Toasts para mensajes de éxito y error
            @if (session('success'))
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: "{{ session('success') }}",
                    showConfirmButton: false,
                    timer: 3000
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: "{{ session('error') }}",
                    showConfirmButton: false,
                    timer: 3000
                });
            @endif
        });
    </script>
@stop
