<div class="modal fade" id="modalEditDriver" tabindex="-1" aria-labelledby="modalEditDriverLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" id="formEditDriver" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" name="driver_id" id="edit_driver_id" value="">

            <div class="modal-content" style="border-radius: 12px; box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);">
                <div class="modal-header" style="background: linear-gradient(135deg, #ff6600, #e55a00); color: white; border-radius: 12px 12px 0 0; padding: 1.5rem;">
                    <h5 class="modal-title" id="modalEditDriverLabel" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                        <i class="fas fa-user-edit me-2"></i>Editar Conductor
                    </h5>
                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Cerrar" style="opacity: 1; filter: brightness(0) invert(1);"></button>
                </div>
                <div class="modal-body" style="padding: 2rem;">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label fw-bold" style="color: #495057; font-family: 'Poppins', sans-serif;">
                            <i class="fas fa-user me-2"></i>Nombre
                        </label>
                        <input type="text" class="form-control" id="edit_name" name="name" required 
                               style="border: 2px solid #ff6600; border-radius: 8px; padding: 0.75rem; font-size: 0.95rem;">
                    </div>
                    <div class="mb-3">
                        <label for="edit_email" class="form-label fw-bold" style="color: #495057; font-family: 'Poppins', sans-serif;">
                            <i class="fas fa-envelope me-2"></i>Email
                        </label>
                        <input type="email" class="form-control" id="edit_email" name="email" required 
                               style="border: 2px solid #ff6600; border-radius: 8px; padding: 0.75rem; font-size: 0.95rem;">
                    </div>
                    <div class="mb-3">
                        <label for="edit_license" class="form-label fw-bold" style="color: #495057; font-family: 'Poppins', sans-serif;">
                            <i class="fas fa-id-card me-2"></i>Licencia
                        </label>
                        <input type="text" class="form-control" id="edit_license" name="license" required 
                               style="border: 2px solid #ff6600; border-radius: 8px; padding: 0.75rem; font-size: 0.95rem;">
                    </div>
                    <div class="mb-3">
                        <label for="edit_password" class="form-label fw-bold" style="color: #495057; font-family: 'Poppins', sans-serif;">
                            <i class="fas fa-lock me-2"></i>Contraseña (dejar vacía para no cambiar)
                        </label>
                        <input type="password" class="form-control" id="edit_password" name="password" autocomplete="new-password" 
                               style="border: 2px solid #ff6600; border-radius: 8px; padding: 0.75rem; font-size: 0.95rem;">
                    </div>
                    <div class="mb-3">
                        <label for="edit_password_confirmation" class="form-label fw-bold" style="color: #495057; font-family: 'Poppins', sans-serif;">
                            <i class="fas fa-lock me-2"></i>Confirmar Contraseña
                        </label>
                        <input type="password" class="form-control" id="edit_password_confirmation" name="password_confirmation" autocomplete="new-password" 
                               style="border: 2px solid #ff6600; border-radius: 8px; padding: 0.75rem; font-size: 0.95rem;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold" style="color: #495057; font-family: 'Poppins', sans-serif;">
                            <i class="fas fa-image me-2"></i>Foto actual
                        </label><br>
                        <img id="current_photo_preview" src="" alt="Foto actual"
                            style="width: 70px; height: 70px; border-radius: 50%; object-fit: cover; border: 3px solid #ff6600;">
                    </div>
                    <div class="mb-3">
                        <label for="edit_photo" class="form-label fw-bold" style="color: #495057; font-family: 'Poppins', sans-serif;">
                            <i class="fas fa-camera me-2"></i>Nueva foto (opcional)
                        </label>
                        <input type="file" class="form-control" id="edit_photo" name="photo" accept="image/*" 
                               style="border: 2px solid #ff6600; border-radius: 8px; padding: 0.75rem; font-size: 0.95rem;">
                    </div>
                </div>
                <div class="modal-footer" style="padding: 1.5rem; background-color: #f8f9fa; border-radius: 0 0 12px 12px;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" 
                            style="border-radius: 8px; padding: 0.5rem 1.5rem; font-weight: 500;">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </button>
                    <button type="submit" class="btn" 
                            style="background: linear-gradient(135deg, #ff6600, #e55a00); border-color: #ff6600; color: white; border-radius: 8px; padding: 0.5rem 1.5rem; font-weight: 600; font-family: 'Poppins', sans-serif;">
                        <i class="fas fa-save me-2"></i>Actualizar Conductor
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
