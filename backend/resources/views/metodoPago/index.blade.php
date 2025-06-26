@extends('adminlte::page')

@section('title', 'Métodos de Pago')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="m-0">
            <i class="fas fa-money-check-alt me-2 text-success"></i> Métodos de Pago
        </h1>
    </div>
@endsection

@section('content')
    <div class="card shadow rounded-3">
        <div class="card-header d-flex justify-content-between align-items-center bg-success text-white">
            <h3 class="card-title m-0">💳 Administra los métodos de pago</h3>
            <button type="button" class="btn btn-light text-success fw-bold" data-bs-toggle="modal"
                data-bs-target="#crearMetodoModal">
                <i class="fas fa-plus-circle me-1"></i> Nuevo Método
            </button>
        </div>

        <div class="card-body">
            <table id="tablaMetodos" class="table table-striped table-hover table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($metodos as $metodo)
                        <tr>
                            <td>{{ $metodo->id }}</td>
                            <td>{{ $metodo->name }}</td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal"
                                        data-bs-target="#editarMetodoModal"
                                        data-id="{{ $metodo->id }}"
                                        data-nombre="{{ $metodo->name }}">
                                        <i class="fas fa-edit me-1"></i> Editar
                                    </button>

                                    <form method="POST" action="{{ route('metodoPago.destroy', $metodo->id) }}"
                                        onsubmit="return confirm('¿Estás seguro de eliminar este método de pago?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" type="submit">
                                            <i class="fas fa-trash-alt me-1"></i> Eliminar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Crear -->
    <div class="modal fade" id="crearMetodoModal" tabindex="-1" aria-labelledby="crearMetodoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('metodoPago.store') }}" class="modal-content needs-validation" novalidate id="formCrearMetodo">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="crearMetodoModalLabel">Agregar Método de Pago</h5>
                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nombreMetodo" class="form-label">Nombre del método</label>
                        <input type="text" class="form-control" id="nombreMetodo" name="name" required>
                        <div class="invalid-feedback">Este campo es obligatorio.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Editar -->
    <div class="modal fade" id="editarMetodoModal" tabindex="-1" aria-labelledby="editarMetodoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="" class="modal-content needs-validation" novalidate id="formEditarMetodo">
                @csrf
                @method('PUT')
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="editarMetodoModalLabel">Editar Método de Pago</h5>
                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="editarMetodoId">
                    <div class="mb-3">
                        <label for="editarNombreMetodo" class="form-label">Nombre del método</label>
                        <input type="text" class="form-control" id="editarNombreMetodo" name="name" required>
                        <div class="invalid-feedback">Este campo es obligatorio.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-info">Actualizar</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('css')
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
@endsection

@section('js')
    <!-- Scripts base -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            $('#tablaMetodos').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
                },
                responsive: true,
                autoWidth: false
            });

            // Editar: llenar formulario
            const editarModal = document.getElementById('editarMetodoModal');
            editarModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-id');
                const nombre = button.getAttribute('data-nombre');

                const form = document.getElementById('formEditarMetodo');
                form.action = `/metodoPago/${id}`;
                document.getElementById('editarNombreMetodo').value = nombre;
            });

            // Validación formularios
            ['formCrearMetodo', 'formEditarMetodo'].forEach(function (formId) {
                const form = document.getElementById(formId);
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                });
            });
        });
    </script>

    {{-- Mensajes con SweetAlert2 --}}
    @if ($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                html: `{!! implode('<br>', $errors->all()) !!}`,
                confirmButtonColor: '#d33'
            });
        </script>
    @endif

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: '¡Agregado!',
                text: '{{ session('success') }}',
                timer: 2500,
                showConfirmButton: false
            });
        </script>
    @endif

    @if (session('updated'))
        <script>
            Swal.fire({
                icon: 'info',
                title: '¡Actualizado!',
                text: '{{ session('updated') }}',
                timer: 2500,
                showConfirmButton: false
            });
        </script>
    @endif

    @if (session('deleted'))
        <script>
            Swal.fire({
                icon: 'warning',
                title: '¡Eliminado!',
                text: '{{ session('deleted') }}',
                timer: 2500,
                showConfirmButton: false
            });
        </script>
    @endif
@endsection
