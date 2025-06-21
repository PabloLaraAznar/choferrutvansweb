<!-- Modal para Editar Envío -->
<div class="modal fade" id="editEnvioModal" tabindex="-1" aria-labelledby="editEnvioModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form method="POST" enctype="multipart/form-data" class="needs-validation" novalidate autocomplete="off" id="editEnvioForm">
            @csrf
            @method('PUT')
            <div class="modal-content shadow-lg rounded-3">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title fw-bold" id="editEnvioModalLabel">Editar Envío</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="editEnvioId" name="envio_id">

                    <div class="mb-3">
                        <label for="editSenderName" class="form-label fw-semibold">Nombre del Remitente</label>
                        <input type="text" class="form-control border-0 shadow-sm" id="editSenderName" name="sender_name" required>
                        <div class="invalid-feedback">Por favor ingresa el nombre del remitente.</div>
                    </div>

                    <div class="mb-3">
                        <label for="editReceiverName" class="form-label fw-semibold">Nombre del Destinatario</label>
                        <input type="text" class="form-control border-0 shadow-sm" id="editReceiverName" name="receiver_name" required>
                        <div class="invalid-feedback">Por favor ingresa el nombre del destinatario.</div>
                    </div>

                    <div class="mb-3">
                        <label for="editTotal" class="form-label fw-semibold">Total</label>
                        <input type="number" step="0.01" class="form-control border-0 shadow-sm" id="editTotal" name="total" required>
                        <div class="invalid-feedback">Por favor ingresa el total del envío.</div>
                    </div>

                    <div class="mb-3">
                        <label for="editPhoto" class="form-label fw-semibold">Foto</label>
                        <input type="file" class="form-control border-0 shadow-sm" id="editPhoto" name="photo">
                    </div>

                    <div class="mb-3">
                        <label for="editDescription" class="form-label fw-semibold">Descripción</label>
                        <textarea class="form-control border-0 shadow-sm" id="editDescription" name="description" rows="3" required></textarea>
                        <div class="invalid-feedback">Por favor ingresa una descripción.</div>
                    </div>

                    <div class="mb-3">
                        <label for="editRouteUnitId" class="form-label fw-semibold">Unidad de Ruta</label>
                        <select class="form-select border-0 shadow-sm" id="editRouteUnitId" name="route_unit_id" required>
                            <option value="" disabled selected>Selecciona una unidad</option>
                            <!-- Opciones dinámicas -->
                        </select>
                        <div class="invalid-feedback">Por favor selecciona una unidad de ruta.</div>
                    </div>

                    <div class="mb-3">
                        <label for="editScheduleId" class="form-label fw-semibold">Horario</label>
                        <select class="form-select border-0 shadow-sm" id="editScheduleId" name="schedule_id" required>
                            <option value="" disabled selected>Selecciona un horario</option>
                            <!-- Opciones dinámicas -->
                        </select>
                        <div class="invalid-feedback">Por favor selecciona un horario.</div>
                    </div>

                    <div class="mb-3">
                        <label for="editRouteId" class="form-label fw-semibold">Ruta</label>
                        <select class="form-select border-0 shadow-sm" id="editRouteId" name="route_id" required>
                            <option value="" disabled selected>Selecciona una ruta</option>
                            <!-- Opciones dinámicas -->
                        </select>
                        <div class="invalid-feedback">Por favor selecciona una ruta.</div>
                    </div>

                </div>
                <div class="modal-footer bg-light">
                    <button type="submit" class="btn btn-info shadow-sm fw-semibold text-white">Actualizar</button>
                </div>
            </div>
        </form>
    </div>
</div>
