<!-- Modal para Crear Permiso -->
<div class="modal fade" id="createPermissionModal" tabindex="-1" aria-labelledby="createPermissionModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="createPermissionForm" action="{{ route('permissions.store') }}" method="POST" class="needs-validation"
            novalidate autocomplete="off">
            @csrf
            <div class="modal-content shadow-lg rounded-3">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title fw-bold" id="createPermissionModalLabel">Agregar Nuevo Permiso</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="createPermissionName" class="form-label fw-semibold">Nombre del Permiso</label>
                        <input type="text" class="form-control border-0 shadow-sm" id="createPermissionName"
                            name="name" required aria-describedby="createPermissionNameFeedback">
                        <div id="createPermissionNameFeedback" class="invalid-feedback">
                            Por favor ingresa un nombre válido para el permiso.
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="submit" class="btn btn-success shadow-sm fw-semibold text-white">Guardar</button>
                </div>
            </div>
        </form>
    </div>
</div>
