<div class="modal fade" id="editHorarioModal" tabindex="-1" aria-labelledby="editHorarioModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="editHorarioForm" method="POST" class="needs-validation" novalidate>
            @csrf
            @method('PUT')
            <input type="hidden" id="editHorarioId" name="id">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="editHorarioModalLabel">
                        <i class="fas fa-edit me-1"></i> Editar Horario
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editHoraLlegada" class="form-label">Hora de Llegada</label>
                        <input type="time" class="form-control" id="editHoraLlegada" name="horaLlegada" required>
                        <div class="invalid-feedback">Este campo es obligatorio.</div>
                    </div>
                    <div class="mb-3">
                        <label for="editHoraSalida" class="form-label">Hora de Salida</label>
                        <input type="time" class="form-control" id="editHoraSalida" name="horaSalida" required>
                        <div class="invalid-feedback">Este campo es obligatorio.</div>
                    </div>
                    <div class="mb-3">
                        <label for="editDia" class="form-label">Día</label>
                        <select class="form-select" id="editDia" name="dia" required>
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
                    <button type="submit" class="btn btn-info">Actualizar Horario</button>
                </div>
            </div>
        </form>
    </div>
</div>
