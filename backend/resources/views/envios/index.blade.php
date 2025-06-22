@extends('adminlte::page')

@section('title', 'Envíos')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="m-0">
            <i class="fas fa-shipping-fast me-2 text-primary"></i> Gestión de Envíos
        </h1>
    </div>
@endsection

@section('content')
    <div class="card shadow rounded-3">
        <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
            <h3 class="card-title m-0">📦 Administra tus envíos</h3>
            <button type="button" class="btn btn-light text-primary fw-bold" data-bs-toggle="modal" data-bs-target="#createEnvioModal">
                <i class="fas fa-plus-circle me-1"></i> Crear Envío
            </button>
        </div>

        <div class="card-body">
            <table id="enviosTable" class="table table-striped table-hover table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width: 10%">ID</th>
                        <th>Destinatario</th>
                        <th>Nombre Receptor</th>
                        <th>Total</th>
                        <th>Descripcion</th>
                        <th>Foto</th>
                        <th>Ruta Unidad</th>
                        <th> horario</th>
                        <th>IdRuta</th>
                        <th class="text-center" style="width: 20%">Acciones</th>
                    </tr>
                </thead>
                <tbody> 
    @foreach ($envios as $envio)
        <tr>
            <td>{{ $envio->id }}</td>
            <td>{{ e($envio->sender_name) }}</td>
            <td>{{ e($envio->receiver_name) }}</td>
            <td>${{ number_format($envio->total, 2) }}</td>
            <td>{{ e($envio->description) }}</td>
            <td>
                @if ($envio->photo)
                    <img src="{{ asset('storage/' . $envio->photo) }}" alt="Foto" width="80">
                @else
                    Sin foto
                @endif
            </td>
            <td>{{ $envio->route_unit_id }}</td>
            <td>{{ $envio->schedule_id }}</td>
            <td>{{ $envio->route_id }}</td>

            <td class="text-center">
                <div class="d-flex justify-content-center gap-2">
                    <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal"
                        data-bs-target="#editEnvioModal"
                        data-envio-id="{{ $envio->id }}"
                        data-envio-sender="{{ e($envio->sender_name) }}"
                        data-envio-receiver="{{ e($envio->receiver_name) }}"
                        data-envio-total="{{ $envio->total }}"
                        data-envio-description="{{ e($envio->description) }}"
                        data-envio-route-unit="{{ $envio->route_unit_id }}"
                        data-envio-schedule="{{ $envio->schedule_id }}"
                        data-envio-route="{{ $envio->route_id }}"
                        aria-label="Editar envío {{ $envio->id }}">
                        <i class="fas fa-edit me-1"></i>
                    </button>

                    <button class="btn btn-sm btn-outline-danger" data-envio-id="{{ $envio->id }}" onclick="confirmDelete(this)" aria-label="Eliminar envío {{ $envio->id }}">
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

    @include('envios.edit') <!-- Modal Editar -->
    @include('envios.create') <!-- Modal Crear -->
@endsection

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            $('#enviosTable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
                },
                responsive: true,
                autoWidth: false
            });
        });

        // Cargar datos al modal editar
        $('#editEnvioModal').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            const id = button.data('envio-id');
            const destinatario = button.data('envio-destinatario');
            const direccion = button.data('envio-direccion');
            const estado = button.data('envio-estado');
            const fecha = button.data('envio-fecha');

            const modal = $(this);
            modal.find('#editEnvioForm').attr('action', `/envios/${id}`);
            modal.find('#editEnvioId').val(id);
            modal.find('#editDestinatario').val(destinatario);
            modal.find('#editDireccion').val(direccion);
            modal.find('#editEstado').val(estado);
            modal.find('#editFecha').val(fecha);

            modal.find('#editEnvioForm').removeClass('was-validated');
        });

        // Validación bootstrap para formularios
        $('#createEnvioForm, #editEnvioForm').on('submit', function(e) {
            if (!this.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }
            $(this).addClass('was-validated');
        });

        // Confirmación para eliminar
        function confirmDelete(button) {
            const envioId = $(button).data('envio-id');

            Swal.fire({
                title: '¿Estás seguro?',
                text: `Eliminar envío #${envioId} es irreversible.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Aquí puedes hacer un submit a un form o una llamada ajax para eliminar
                    // Por ejemplo, crear un form dinámico para eliminar con POST+DELETE

                    const form = $('<form>', {
                        method: 'POST',
                        action: `/envios/${envioId}`
                    });

                    const tokenInput = $('<input>', {
                        type: 'hidden',
                        name: '_token',
                        value: '{{ csrf_token() }}'
                    });
                    const methodInput = $('<input>', {
                        type: 'hidden',
                        name: '_method',
                        value: 'DELETE'
                    });

                    form.append(tokenInput, methodInput);
                    $('body').append(form);
                    form.submit();
                }
            });
        }
    </script>
@endsection
