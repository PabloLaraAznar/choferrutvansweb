@extends('adminlte::page')

@section('title', 'Horarios')

@section('content_header')
    <div class="rutvans-content-header rutvans-fade-in">
        <div class="container-fluid">
            <h1>
                <i class="fas fa-clock me-2"></i> Gestión de Horarios
            </h1>
            <p class="subtitle">Administra los horarios del sistema de transporte</p>
        </div>
    </div>
@endsection

@section('content')
    <div class="rutvans-card rutvans-hover-lift rutvans-fade-in">
        <div class="rutvans-card-header d-flex justify-content-between align-items-center">
            <h3 class="m-0">
                <i class="fas fa-business-time me-2"></i> Horarios del Sistema
            </h3>
            <button type="button" class="rutvans-btn rutvans-btn-primary" data-bs-toggle="modal"
                data-bs-target="#createHorarioModal">
                <i class="fas fa-plus"></i> Crear Horario
            </button>
        </div>

        <div class="rutvans-card-body">
            <div class="table-responsive">
                <table id="horariosTable" class="table rutvans-table table-striped table-hover align-middle">
                    <thead>
                        <tr>
                            <th><i class="fas fa-hashtag me-1"></i> ID</th>
                            <th><i class="fas fa-calendar-day me-1"></i> Día</th>
                            <th><i class="fas fa-clock me-1"></i> Hora Llegada</th>
                            <th><i class="fas fa-clock me-1"></i> Hora Salida</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($horarios as $horario)
                        <tr>
                            <td>{{ $horario->id }}</td>
                            <td>{{ e($horario->dia) }}</td>
                            <td>{{ e($horario->horaLlegada) }}</td>
                            <td>{{ e($horario->horaSalida) }}</td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <button class="btn btn-sm btn-outline-info rounded-circle" data-bs-toggle="modal"
                                        data-bs-target="#editHorarioModal"
                                        data-id="{{ $horario->id }}"
                                        data-dia="{{ $horario->dia }}"
                                        data-llegada="{{ $horario->horaLlegada }}"
                                        data-salida="{{ $horario->horaSalida }}">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <button class="btn btn-sm btn-outline-danger rounded-circle"
                                        onclick="confirmDeleteHorario({{ $horario->id }})">
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

    @include('horarios.create')
    @include('horarios.edit')
@endsection

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">

    <style>
        .transition {
            transition: all 0.3s ease-in-out;
        }
        .btn-outline-light:hover {
            background-color: #ffffff !important;
            color: #4da3ff !important;
            box-shadow: 0 0 0 2px #4da3ff50;
        }
        .btn-outline-info:hover {
            background-color: #4da3ff !important;
            color: #fff !important;
        }
        .btn-outline-danger:hover {
            background-color: #dc3545 !important;
            color: #fff !important;
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
        $(document).ready(function () {
            $('#horariosTable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
                }
            });

            $('#editHorarioModal').on('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const modal = $(this);
                modal.find('#editHorarioId').val(button.getAttribute('data-id'));
                modal.find('#editHorarioDia').val(button.getAttribute('data-dia'));
                modal.find('#editHorarioLlegada').val(button.getAttribute('data-llegada'));
                modal.find('#editHorarioSalida').val(button.getAttribute('data-salida'));
            });
        });

        function confirmDeleteHorario(id) {
            Swal.fire({
                title: '¿Eliminar este horario?',
                text: "No podrás revertir esta acción.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#d33',
                cancelButtonColor: '#4da3ff',
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/horarios/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json'
                        }
                    }).then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('¡Eliminado!', data.message, 'success').then(() => location.reload());
                        } else {
                            Swal.fire('Error', data.message, 'error');
                        }
                    }).catch(() => {
                        Swal.fire('Error', 'No se pudo eliminar el horario.', 'error');
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
                html: `{!! implode('<br>', $errors->all()) !!}`
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
                timer: 2000
            });
        </script>
    @endif
@endsection
