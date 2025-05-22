<!-- Modal para Crear Rol -->
<div class="modal fade" id="createRoleModal" tabindex="-1" aria-labelledby="createRoleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('roles.store') }}" method="POST" class="needs-validation" novalidate autocomplete="off"
            id="createRoleForm">
            @csrf
            <div class="modal-content shadow-lg rounded-3">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title fw-bold" id="createRoleModalLabel">Crear Rol</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="createRoleName" class="form-label fw-semibold">Nombre del Rol</label>
                        <input type="text" class="form-control border-0 shadow-sm" id="createRoleName" name="name"
                            placeholder="Ingrese el nombre del rol" required aria-describedby="createRoleNameFeedback">
                        <div id="createRoleNameFeedback" class="invalid-feedback">
                            Por favor ingresa un nombre válido para el rol.
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="submit" class="btn btn-success shadow-sm fw-semibold">Guardar</button>
                </div>
            </div>
        </form>
    </div>
</div>
