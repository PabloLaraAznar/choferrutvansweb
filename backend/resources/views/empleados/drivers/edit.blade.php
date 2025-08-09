<div class="modal fade" id="modalEditDriver" tabindex="-1" aria-labelledby="modalEditDriverLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form method="POST" id="formEditDriver" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="driver_id" id="edit_driver_id" value="">

                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title">
                        <i class="fas fa-user-edit me-2"></i>
                        Editar Conductor
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="edit_name" class="form-label fw-bold">Nombre</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_email" class="form-label fw-bold">Email</label>
                            <input type="email" class="form-control" id="edit_email" name="email" required>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_license" class="form-label fw-bold">Licencia</label>
                            <input type="text" class="form-control" id="edit_license" name="license" required>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_photo" class="form-label fw-bold">Nueva foto (opcional)</label>
                            <input type="file" class="form-control" id="edit_photo" name="photo" accept="image/*">
                            <small class="form-text text-muted">Si no seleccionas una imagen, se mantendrá la
                                actual.</small>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_password" class="form-label fw-bold">Contraseña (opcional)</label>
                            <input type="password" class="form-control" id="edit_password" name="password"
                                autocomplete="new-password">
                                     <small class="form-text text-muted">Si no desea cambiar la contraseña, se mantendrá la
                                actual.</small>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_password_confirmation" class="form-label fw-bold">Confirmar
                                Contraseña</label>
                            <input type="password" class="form-control" id="edit_password_confirmation"
                                name="password_confirmation" autocomplete="new-password">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Foto actual</label><br>
                            <img id="current_photo_preview" src="" alt="Foto actual"
                                style="width: 70px; height: 70px; border-radius: 50%; object-fit: cover; border: 3px solid #ff6600;">
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-warning text-white">
                        <i class="fas fa-save me-2"></i>Actualizar Conductor
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
