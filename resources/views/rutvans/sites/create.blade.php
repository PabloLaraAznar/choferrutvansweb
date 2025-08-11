<!-- Modal para Crear Sitio/Ruta -->
<div class="modal fade" id="createSiteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="{{ route('clients.store') }}" method="POST" id="createSiteForm">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-plus mr-2"></i>
                        Crear Nuevo Sitio/Ruta
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                
                <div class="modal-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <h6><i class="fas fa-exclamation-triangle"></i> Errores de validación:</h6>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
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
                                                <label for="company_id" class="required">Empresa/Sindicato</label>
                                                <select class="form-control @error('company_id') is-invalid @enderror" 
                                                        id="company_id" 
                                                        name="company_id" 
                                                        required>
                                                    <option value="">Seleccionar empresa...</option>
                                                    @foreach($companies as $company)
                                                        <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>
                                                            {{ $company->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('company_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="name" class="required">Nombre del Sitio/Terminal</label>
                                                <input type="text" 
                                                       class="form-control @error('name') is-invalid @enderror" 
                                                       id="name" 
                                                       name="name" 
                                                       value="{{ old('name') }}" 
                                                       required
                                                       placeholder="Ej: Terminal Central">
                                                @error('name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="route_name">Ruta Principal (opcional)</label>
                                                <input type="text" 
                                                       class="form-control @error('route_name') is-invalid @enderror" 
                                                       id="route_name" 
                                                       name="route_name" 
                                                       value="{{ old('route_name') }}" 
                                                       placeholder="Ej: Maxcanú-Mérida">
                                                <small class="form-text text-muted">Las rutas específicas se configurarán posteriormente por el admin del sitio</small>
                                                @error('route_name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="locality_id" class="required">Localidad donde está ubicado</label>
                                                <select class="form-control @error('locality_id') is-invalid @enderror" 
                                                        id="locality_id" 
                                                        name="locality_id" 
                                                        required>
                                                    <option value="">Seleccionar localidad...</option>
                                                    @foreach($localities as $locality)
                                                        <option value="{{ $locality->id }}" 
                                                                {{ old('locality_id') == $locality->id ? 'selected' : '' }}>
                                                            {{ $locality->locality }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('locality_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="address" class="required">Dirección</label>
                                                <input type="text" 
                                                       class="form-control @error('address') is-invalid @enderror" 
                                                       id="address" 
                                                       name="address" 
                                                       value="{{ old('address') }}" 
                                                       required
                                                       placeholder="Dirección completa del sitio">
                                                @error('address')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="phone" class="required">Teléfono</label>
                                                <input type="text" 
                                                       class="form-control @error('phone') is-invalid @enderror" 
                                                       id="phone" 
                                                       name="phone" 
                                                       value="{{ old('phone') }}" 
                                                       required
                                                       placeholder="Ej: +1234567890">
                                                @error('phone')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="status" class="required">Estado</label>
                                                <select class="form-control @error('status') is-invalid @enderror" 
                                                        id="status" 
                                                        name="status" 
                                                        required>
                                                    <option value="">Seleccionar estado...</option>
                                                    <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Activo</option>
                                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactivo</option>
                                                </select>
                                                @error('status')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
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
                                                    <input class="form-check-input" type="radio" name="admin_type" id="admin_existing" value="existing" {{ old('admin_type', 'existing') == 'existing' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="admin_existing">
                                                        Usar administrador existente (mismo de la compañía)
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="admin_type" id="admin_new" value="new" {{ old('admin_type') == 'new' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="admin_new">
                                                        Crear nuevo administrador
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Seleccionar Admin Existente -->
                                    <div id="existing-admin-section" class="row" style="display: none;">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="existing_admin_id" class="required">Seleccionar Administrador</label>
                                                <select class="form-control @error('existing_admin_id') is-invalid @enderror" 
                                                        id="existing_admin_id" 
                                                        name="existing_admin_id">
                                                    <option value="">Seleccionar administrador...</option>
                                                    @foreach($availableAdmins as $admin)
                                                        <option value="{{ $admin->id }}" {{ old('existing_admin_id') == $admin->id ? 'selected' : '' }}>
                                                            {{ $admin->name }} ({{ $admin->email }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('existing_admin_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Crear Nuevo Admin -->
                                    <div id="new-admin-section" class="row" style="display: none;">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="admin_name" class="required">Nombre del Admin</label>
                                                <input type="text" 
                                                       class="form-control @error('admin_name') is-invalid @enderror" 
                                                       id="admin_name" 
                                                       name="admin_name" 
                                                       value="{{ old('admin_name') }}" 
                                                       placeholder="Nombre completo del administrador">
                                                @error('admin_name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="admin_email" class="required">Email del Admin</label>
                                                <input type="email" 
                                                       class="form-control @error('admin_email') is-invalid @enderror" 
                                                       id="admin_email" 
                                                       name="admin_email" 
                                                       value="{{ old('admin_email') }}" 
                                                       placeholder="admin@empresa.com">
                                                @error('admin_email')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="admin_password" class="required">Contraseña</label>
                                                <input type="password" 
                                                       class="form-control @error('admin_password') is-invalid @enderror" 
                                                       id="admin_password" 
                                                       name="admin_password" 
                                                       placeholder="Mínimo 6 caracteres">
                                                @error('admin_password')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="admin_password_confirmation" class="required">Confirmar Contraseña</label>
                                                <input type="password" 
                                                       class="form-control" 
                                                       id="admin_password_confirmation" 
                                                       name="admin_password_confirmation" 
                                                       placeholder="Repetir contraseña">
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
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i>
                        Crear Sitio/Terminal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const adminExisting = document.getElementById('admin_existing');
    const adminNew = document.getElementById('admin_new');
    const existingSection = document.getElementById('existing-admin-section');
    const newSection = document.getElementById('new-admin-section');
    
    function toggleAdminSections() {
        if (adminExisting.checked) {
            existingSection.style.display = 'block';
            newSection.style.display = 'none';
            
            // Auto-seleccionar el primer admin disponible
            const adminSelect = document.getElementById('existing_admin_id');
            if (adminSelect && adminSelect.value === '') {
                for (let i = 1; i < adminSelect.options.length; i++) {
                    if (adminSelect.options[i].value !== '') {
                        adminSelect.value = adminSelect.options[i].value;
                        break;
                    }
                }
            }
            
            // Configurar required
            document.getElementById('admin_name').removeAttribute('required');
            document.getElementById('admin_email').removeAttribute('required');
            document.getElementById('admin_password').removeAttribute('required');
            document.getElementById('admin_password_confirmation').removeAttribute('required');
            document.getElementById('existing_admin_id').setAttribute('required', 'required');
            
        } else if (adminNew.checked) {
            existingSection.style.display = 'none';
            newSection.style.display = 'block';
            
            // Configurar required
            document.getElementById('admin_name').setAttribute('required', 'required');
            document.getElementById('admin_email').setAttribute('required', 'required');
            document.getElementById('admin_password').setAttribute('required', 'required');
            document.getElementById('admin_password_confirmation').setAttribute('required', 'required');
            document.getElementById('existing_admin_id').removeAttribute('required');
        }
    }
    
    // Inicializar
    toggleAdminSections();
    
    // Escuchar cambios
    adminExisting.addEventListener('change', toggleAdminSections);
    adminNew.addEventListener('change', toggleAdminSections);
});

// Mostrar modal si hay errores
@if($errors->any())
document.addEventListener('DOMContentLoaded', function() {
    $('#createSiteModal').modal('show');
});
@endif
</script>

