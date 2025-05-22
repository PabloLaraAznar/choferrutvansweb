<div class="modal fade" id="editPermissionModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form id="editPermissionForm" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" id="editRoleId" name="role_id">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title">Editar Permisos del Rol</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Selecciona los permisos:</label>
                        <div id="permissionsContainer">
                            <div class="text-muted">Cargando permisos...</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="submit" class="btn btn-info text-white">Actualizar</button>
                </div>
            </div>
        </form>
    </div>
</div>
