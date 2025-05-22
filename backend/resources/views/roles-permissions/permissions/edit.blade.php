<!-- Modal para Editar Permiso -->
<div class="modal fade" id="editPermissionModal" tabindex="-1" aria-labelledby="editPermissionModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="editPermissionForm" method="POST" class="needs-validation" novalidate autocomplete="off">
            @csrf
            @method('PUT')
            <div class="modal-content shadow-lg rounded-3">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title fw-bold" id="editPermissionModalLabel">Editar Permiso</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="editPermissionId" name="permission_id">
                    <div class="mb-3">
                        <label for="editPermissionName" class="form-label fw-semibold">Nombre del Permiso</label>
                        <input type="text" class="form-control border-0 shadow-sm" id="editPermissionName"
                            name="name" required aria-describedby="editPermissionNameFeedback">
                        <div id="editPermissionNameFeedback" class="invalid-feedback">
                            Por favor ingresa un nombre válido para el permiso.
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
