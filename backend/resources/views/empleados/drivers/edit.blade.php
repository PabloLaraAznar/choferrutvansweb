<div class="modal fade" id="modalEditDriver" tabindex="-1" aria-labelledby="modalEditDriverLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" id="formEditDriver" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" name="driver_id" id="edit_driver_id" value="">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditDriverLabel">Editar Chófer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="edit_email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_license" class="form-label">Licencia</label>
                        <input type="text" class="form-control" id="edit_license" name="license" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_password" class="form-label">Contraseña (dejar vacía para no cambiar)</label>
                        <input type="password" class="form-control" id="edit_password" name="password" autocomplete="new-password">
                    </div>
                    <div class="mb-3">
                        <label for="edit_password_confirmation" class="form-label">Confirmar Contraseña</label>
                        <input type="password" class="form-control" id="edit_password_confirmation" name="password_confirmation" autocomplete="new-password">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Foto actual</label><br>
                        <img id="current_photo_preview" src="" alt="Foto actual"
                            style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;">
                    </div>
                    <div class="mb-3">
                        <label for="edit_photo" class="form-label">Nueva foto (opcional)</label>
                        <input type="file" class="form-control" id="edit_photo" name="photo" accept="image/*">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                </div>
            </div>
        </form>
    </div>
</div>
