<div class="modal fade" id="createHorarioModal" tabindex="-1" aria-labelledby="createHorarioModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="createHorarioForm" action="{{ route('horarios.store') }}" method="POST" class="needs-validation" novalidate>
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="createHorarioModalLabel">
                        <i class="fas fa-clock me-1"></i> Crear Horario
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="horaLlegada" class="form-label">Hora de Llegada</label>
                        <input type="time" class="form-control" id="horaLlegada" name="horaLlegada" required>
                        <div class="invalid-feedback">Este campo es obligatorio.</div>
                    </div>
                    <div class="mb-3">
                        <label for="horaSalida" class="form-label">Hora de Salida</label>
                        <input type="time" class="form-control" id="horaSalida" name="horaSalida" required>
                        <div class="invalid-feedback">Este campo es obligatorio.</div>
                    </div>
                    <div class="mb-3">
                        <label for="dia" class="form-label">Día</label>
                        <select class="form-select" id="dia" name="dia" required>
                            <option value="" disabled selected>Seleccione un día</option>
                            <option>Lunes</option>
                            <option>Martes</option>
                            <option>Miércoles</option>
                            <option>Jueves</option>
                            <option>Viernes</option>
                            <option>Sábado</option>
                            <option>Domingo</option>
                        </select>
                        <div class="invalid-feedback">Seleccione un día.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Horario</button>
                </div>
            </div>
        </form>
    </div>
</div>
