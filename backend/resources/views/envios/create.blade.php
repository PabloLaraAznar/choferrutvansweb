<!-- Modal para Crear Envío -->
<div class="modal fade" id="createEnvioModal" tabindex="-1" aria-labelledby="createEnvioModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('envios.store') }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate autocomplete="off" id="createEnvioForm">
            @csrf
            <div class="modal-content shadow-lg rounded-3">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title fw-bold" id="createEnvioModalLabel">Crear Envío</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">

                    <div class="mb-3">
                        <label for="sender_name" class="form-label fw-semibold">Nombre del remitente</label>
                        <input type="text" class="form-control border-0 shadow-sm" id="sender_name" name="sender_name" required>
                        <div class="invalid-feedback">Por favor ingresa el nombre del remitente.</div>
                    </div>

                    <div class="mb-3">
                        <label for="receiver_name" class="form-label fw-semibold">Nombre del receptor</label>
                        <input type="text" class="form-control border-0 shadow-sm" id="receiver_name" name="receiver_name" required>
                        <div class="invalid-feedback">Por favor ingresa el nombre del receptor.</div>
                    </div>

                    <div class="mb-3">
                        <label for="total" class="form-label fw-semibold">Total</label>
                        <input type="number" step="0.01" class="form-control border-0 shadow-sm" id="total" name="total" required>
                        <div class="invalid-feedback">Por favor ingresa el total.</div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label fw-semibold">Descripción</label>
                        <textarea class="form-control border-0 shadow-sm" id="description" name="description" rows="3" required></textarea>
                        <div class="invalid-feedback">Por favor ingresa una descripción.</div>
                    </div>

                    <div class="mb-3">
                        <label for="photo" class="form-label fw-semibold">Foto</label>
                        <input type="file" class="form-control border-0 shadow-sm" id="photo" name="photo" accept="image/*" required>
                        <div class="invalid-feedback">Por favor sube una imagen.</div>
                    </div>

                    <div class="mb-3">
                        <label for="route_unit_id" class="form-label fw-semibold">Ruta Unidad</label>
                        <select class="form-select border-0 shadow-sm" id="route_unit_id" name="route_unit_id" required>
                            <option value="" disabled selected>Selecciona una unidad</option>
                            @foreach ($rutasUnidades as $unidad)
                                <option value="{{ $unidad->id }}">{{ $unidad->nombre }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">Por favor selecciona una unidad de ruta.</div>
                    </div>

                    <div class="mb-3">
                        <label for="schedule_id" class="form-label fw-semibold">Horario</label>
                        <select class="form-select border-0 shadow-sm" id="schedule_id" name="schedule_id" required>
                            <option value="" disabled selected>Selecciona un horario</option>
                            @foreach ($horarios as $horario)
                                <option value="{{ $horario->id }}">{{ $horario->hora }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">Por favor selecciona un horario.</div>
                    </div>

                    <div class="mb-3">
                        <label for="route_id" class="form-label fw-semibold">Ruta</label>
                        <select class="form-select border-0 shadow-sm" id="route_id" name="route_id" required>
                            <option value="" disabled selected>Selecciona una ruta</option>
                            @foreach ($rutas as $ruta)
                                <option value="{{ $ruta->id }}">{{ $ruta->nombre }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">Por favor selecciona una ruta.</div>
                    </div>

                </div>
                <div class="modal-footer bg-light">
                    <button type="submit" class="btn btn-success shadow-sm fw-semibold">Guardar</button>
                </div>
            </div>
        </form>
    </div>
</div>
