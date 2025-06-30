<!-- Modal para Editar Sitio/Ruta -->
<div class="modal fade" id="editSiteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="" method="POST" id="editSiteForm">
                @csrf
                @method('PUT')
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title">
                        <i class="fas fa-edit mr-2"></i>
                        Editar Sitio/Ruta
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                
                <div class="modal-body">
                    <div class="row">
                        <!-- Información del Sitio/Ruta -->
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">
                                        <i class="fas fa-route mr-2"></i>
                                        Información del Sitio/Ruta
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="edit_company_id" class="required">Empresa/Sindicato</label>
                                                <select class="form-control" 
                                                        id="edit_company_id" 
                                                        name="company_id" 
                                                        required>
                                                    <option value="">Seleccionar empresa...</option>
                                                    @foreach($companies as $company)
                                                        <option value="{{ $company->id }}">
                                                            {{ $company->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="edit_name" class="required">Nombre del Sitio/Terminal</label>
                                                <input type="text" 
                                                       class="form-control" 
                                                       id="edit_name" 
                                                       name="name" 
                                                       required
                                                       placeholder="Ej: Terminal Central">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="edit_route_name">Ruta Principal (opcional)</label>
                                                <input type="text" 
                                                       class="form-control" 
                                                       id="edit_route_name" 
                                                       name="route_name" 
                                                       placeholder="Ej: Maxcanú-Mérida">
                                                <small class="form-text text-muted">Las rutas específicas se configurarán posteriormente por el admin del sitio</small>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="edit_locality_id" class="required">Localidad donde está ubicado</label>
                                                <select class="form-control" 
                                                        id="edit_locality_id" 
                                                        name="locality_id" 
                                                        required>
                                                    <option value="">Seleccionar localidad...</option>
                                                    @foreach($localities as $locality)
                                                        <option value="{{ $locality->id }}">
                                                            {{ $locality->locality }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="edit_address" class="required">Dirección</label>
                                                <input type="text" 
                                                       class="form-control" 
                                                       id="edit_address" 
                                                       name="address" 
                                                       required
                                                       placeholder="Dirección completa del sitio">
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="edit_phone" class="required">Teléfono</label>
                                                <input type="text" 
                                                       class="form-control" 
                                                       id="edit_phone" 
                                                       name="phone" 
                                                       required
                                                       placeholder="Ej: +1234567890">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="edit_status" class="required">Estado</label>
                                                <select class="form-control" 
                                                        id="edit_status" 
                                                        name="status" 
                                                        required>
                                                    <option value="active">Activo</option>
                                                    <option value="inactive">Inactivo</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Información del Usuario Admin -->
                        <div class="col-12 mt-3">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">
                                        <i class="fas fa-user-shield mr-2"></i>
                                        Usuario Administrador del Sitio
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <!-- Tipo de Admin -->
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label class="required">Tipo de Administrador</label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="admin_type" id="edit_admin_existing" value="existing">
                                                    <label class="form-check-label" for="edit_admin_existing">
                                                        Usar administrador existente
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="admin_type" id="edit_admin_new" value="new">
                                                    <label class="form-check-label" for="edit_admin_new">
                                                        Mantener/Actualizar administrador actual
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Seleccionar Admin Existente -->
                                    <div id="edit-existing-admin-section" class="row" style="display: none;">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="edit_existing_admin_id" class="required">Seleccionar Administrador</label>
                                                <select class="form-control" 
                                                        id="edit_existing_admin_id" 
                                                        name="existing_admin_id">
                                                    <option value="">Seleccionar administrador...</option>
                                                    @foreach($availableAdmins as $admin)
                                                        <option value="{{ $admin->id }}">
                                                            {{ $admin->name }} ({{ $admin->email }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Actualizar Admin Actual -->
                                    <div id="edit-new-admin-section" class="row" style="display: none;">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="edit_admin_name" class="required">Nombre del Admin</label>
                                                <input type="text" 
                                                       class="form-control" 
                                                       id="edit_admin_name" 
                                                       name="admin_name" 
                                                       placeholder="Nombre completo del administrador">
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="edit_admin_email" class="required">Email del Admin</label>
                                                <input type="email" 
                                                       class="form-control" 
                                                       id="edit_admin_email" 
                                                       name="admin_email" 
                                                       placeholder="admin@empresa.com">
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="edit_admin_password">Nueva Contraseña (opcional)</label>
                                                <input type="password" 
                                                       class="form-control" 
                                                       id="edit_admin_password" 
                                                       name="admin_password" 
                                                       placeholder="Dejar vacío para mantener la actual">
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="edit_admin_password_confirmation">Confirmar Nueva Contraseña</label>
                                                <input type="password" 
                                                       class="form-control" 
                                                       id="edit_admin_password_confirmation" 
                                                       name="admin_password_confirmation" 
                                                       placeholder="Repetir nueva contraseña">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i>
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save mr-1"></i>
                        Actualizar Sitio/Terminal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const editAdminExisting = document.getElementById('edit_admin_existing');
    const editAdminNew = document.getElementById('edit_admin_new');
    const editExistingSection = document.getElementById('edit-existing-admin-section');
    const editNewSection = document.getElementById('edit-new-admin-section');
    
    function toggleEditAdminSections() {
        if (editAdminExisting.checked) {
            editExistingSection.style.display = 'block';
            editNewSection.style.display = 'none';
            // Remover required de campos de admin actual
            document.getElementById('edit_admin_name').removeAttribute('required');
            document.getElementById('edit_admin_email').removeAttribute('required');
            // Agregar required a admin existente
            document.getElementById('edit_existing_admin_id').setAttribute('required', 'required');
        } else if (editAdminNew.checked) {
            editExistingSection.style.display = 'none';
            editNewSection.style.display = 'block';
            // Agregar required a campos de admin actual
            document.getElementById('edit_admin_name').setAttribute('required', 'required');
            document.getElementById('edit_admin_email').setAttribute('required', 'required');
            // Remover required de admin existente
            document.getElementById('edit_existing_admin_id').removeAttribute('required');
        }
    }
    
    // Escuchar cambios
    if (editAdminExisting) {
        editAdminExisting.addEventListener('change', toggleEditAdminSections);
    }
    if (editAdminNew) {
        editAdminNew.addEventListener('change', toggleEditAdminSections);
    }
});
</script>

