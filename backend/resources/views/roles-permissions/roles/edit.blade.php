<!-- Modal para Editar Rol -->
<div class="modal fade" id="editRoleModal" tabindex="-1" aria-labelledby="editRoleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form method="POST" class="needs-validation" novalidate autocomplete="off" id="editRoleForm">
            @csrf
            @method('PUT')
            <div class="modal-content shadow-lg rounded-3">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title fw-bold" id="editRoleModalLabel">Editar Rol</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="editRoleId" name="role_id">
                    <div class="mb-3">
                        <label for="editRoleName" class="form-label fw-semibold">Nombre del Rol</label>
                        <input type="text" class="form-control border-0 shadow-sm" id="editRoleName" name="name"
                            required aria-describedby="editRoleNameFeedback">
                        <div id="editRoleNameFeedback" class="invalid-feedback">
                            Por favor ingresa un nombre válido para el rol.
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="submit" class="btn btn-info shadow-sm fw-semibold text-white">Actualizar</button>
                </div>
            </div>
        </form>
    </div>
</div>
