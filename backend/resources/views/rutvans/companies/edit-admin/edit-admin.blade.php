<!-- Modal para Editar Administrador -->
<div class="modal fade" id="editAdminModal" tabindex="-1" aria-labelledby="editAdminModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="editAdminForm" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title" id="editAdminModalLabel">
                        <i class="fas fa-user-edit me-2"></i> Editar Administrador
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <input type="hidden" id="edit_admin_id" name="admin_id">
                            <div class="form-group mb-3">
                                <label for="edit_admin_name" class="required">Nombre Completo</label>
                                <input type="text" class="form-control" id="edit_admin_name" name="admin_name" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="edit_admin_email" class="required">Correo Electrónico</label>
                                <input type="email" class="form-control" id="edit_admin_email" name="admin_email" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="edit_admin_password">Nueva Contraseña</label>
                                <input type="password" class="form-control" id="edit_admin_password" name="admin_password">
                            </div>

                            <div class="form-group mb-3">
                                <label for="edit_admin_password_confirmation">Confirmar Nueva Contraseña</label>
                                <input type="password" class="form-control" id="edit_admin_password_confirmation" name="admin_password_confirmation">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-warning text-white">
                        <i class="fas fa-save me-1"></i> Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
