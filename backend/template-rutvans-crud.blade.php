{{-- 
TEMPLATE RUTVANS ADMIN - VISTA CRUD
Copiar y adaptar este template para todas las vistas CRUD
Colores: #ff6600 (naranja), #000 (negro), #f0f2f5 (fondo)
--}}

@extends('adminlte::page')

@section('title', 'NOMBRE_MODULO')

@section('content_header')
    <div class="rutvans-content-header rutvans-fade-in">
        <div class="container-fluid">
            <h1>
                <i class="fas fa-ICONO me-2"></i> TITULO_PRINCIPAL
            </h1>
            <p class="subtitle">DESCRIPCION_SUBTITULO</p>
        </div>
    </div>
@endsection

@section('content')
    <div class="rutvans-card rutvans-hover-lift rutvans-fade-in">
        <div class="rutvans-card-header d-flex justify-content-between align-items-center">
            <h3 class="m-0">
                <i class="fas fa-ICONO_CARD me-2"></i> TITULO_CARD
            </h3>
            <button type="button" class="rutvans-btn rutvans-btn-primary" data-bs-toggle="modal" data-bs-target="#MODAL_CREAR">
                <i class="fas fa-plus"></i> Nuevo ELEMENTO
            </button>
        </div>
        
        <div class="rutvans-card-body">
            <div class="table-responsive">
                <table id="TABLA_ID" class="table rutvans-table table-striped table-hover align-middle">
                    <thead>
                        <tr>
                            <th><i class="fas fa-hashtag me-1"></i> ID</th>
                            <th><i class="fas fa-ICONO me-1"></i> COLUMNA1</th>
                            <th><i class="fas fa-ICONO me-1"></i> COLUMNA2</th>
                            <th><i class="fas fa-cogs me-1"></i> Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($ELEMENTOS as $elemento)
                            <tr>
                                <td>
                                    <span class="rutvans-badge rutvans-badge-primary">{{ $elemento->id }}</span>
                                </td>
                                <td>
                                    <i class="fas fa-ICONO text-muted me-2"></i>
                                    {{ $elemento->CAMPO }}
                                </td>
                                <td>
                                    <!-- CONTENIDO COLUMNA -->
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <!-- Botón Editar -->
                                        <button class="rutvans-btn rutvans-btn-warning rutvans-btn-sm" 
                                            data-bs-toggle="modal"
                                            data-bs-target="#MODAL_EDITAR"
                                            data-id="{{ $elemento->id }}">
                                            <i class="fas fa-edit"></i> Editar
                                        </button>

                                        <!-- Botón Eliminar -->
                                        <form method="POST" action="{{ route('RUTA.destroy', $elemento->id) }}"
                                            onsubmit="return confirm('¿Estás seguro de eliminar este ELEMENTO?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="rutvans-btn rutvans-btn-danger rutvans-btn-sm" type="submit">
                                                <i class="fas fa-trash-alt"></i> Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="X" class="text-center">
                                    <div class="d-flex flex-column align-items-center py-4">
                                        <i class="fas fa-ICONO_EMPTY text-muted mb-3" style="font-size: 3rem;"></i>
                                        <h5 class="text-muted">No hay ELEMENTOS registrados</h5>
                                        <p class="text-muted">Agrega el primer ELEMENTO para comenzar</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Crear -->
    <div class="modal fade" id="MODAL_CREAR" tabindex="-1">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('RUTA.store') }}" class="rutvans-modal-content needs-validation" novalidate>
                @csrf
                <div class="rutvans-modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-plus-circle me-2"></i> Agregar ELEMENTO
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="rutvans-modal-body">
                    <div class="rutvans-form-group">
                        <label for="CAMPO" class="rutvans-form-label">
                            <i class="fas fa-ICONO me-1"></i> ETIQUETA
                        </label>
                        <input type="text" class="form-control rutvans-form-control" id="CAMPO" name="CAMPO" required>
                        <div class="invalid-feedback">Este campo es obligatorio.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="rutvans-btn rutvans-btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    <button type="submit" class="rutvans-btn rutvans-btn-success">
                        <i class="fas fa-save"></i> Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Editar -->
    <div class="modal fade" id="MODAL_EDITAR" tabindex="-1">
        <div class="modal-dialog">
            <form method="POST" action="" class="rutvans-modal-content needs-validation" novalidate>
                @csrf
                @method('PUT')
                <div class="rutvans-modal-header" style="background: linear-gradient(135deg, #17a2b8, #138496);">
                    <h5 class="modal-title">
                        <i class="fas fa-edit me-2"></i> Editar ELEMENTO
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="rutvans-modal-body">
                    <!-- CAMPOS DEL FORMULARIO -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="rutvans-btn rutvans-btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    <button type="submit" class="rutvans-btn rutvans-btn-info">
                        <i class="fas fa-save"></i> Actualizar
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('css')
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
            // Inicializar DataTable
            $('#TABLA_ID').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
                },
                responsive: true,
                autoWidth: false
            });

            // Lógica para modales de edición
            // ...
        });
    </script>

    {{-- Mensajes con SweetAlert2 --}}
    @if ($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                html: `{!! implode('<br>', $errors->all()) !!}`,
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'Entendido'
            });
        </script>
    @endif

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: '¡Excelente!',
                text: '{{ session('success') }}',
                timer: 3000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        </script>
    @endif

    @if (session('updated'))
        <script>
            Swal.fire({
                icon: 'info',
                title: '¡Actualizado!',
                text: '{{ session('updated') }}',
                timer: 3000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        </script>
    @endif

    @if (session('deleted'))
        <script>
            Swal.fire({
                icon: 'warning',
                title: '¡Eliminado!',
                text: '{{ session('deleted') }}',
                timer: 3000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        </script>
    @endif
@endsection
