<!-- Modal para Crear Empresa/Sindicato -->
<div class="modal fade" id="createCompanyModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="{{ route('companies.store') }}" method="POST" id="createCompanyForm">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-plus mr-2"></i>
                        Nueva Empresa/Sindicato
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                
                <div class="modal-body">
                    <div class="row">
                        <!-- Información de la Empresa/Sindicato -->
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">
                                        <i class="fas fa-industry mr-2"></i>
                                        Información de la Empresa/Sindicato
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="name" class="required">Nombre</label>
                                                <input type="text" 
                                                       class="form-control" 
                                                       id="name" 
                                                       name="name" 
                                                       required
                                                       placeholder="Ej: Sindicato de Maxcanú">
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="business_name">Razón Social</label>
                                                <input type="text" 
                                                       class="form-control" 
                                                       id="business_name" 
                                                       name="business_name"
                                                       placeholder="Nombre fiscal de la empresa">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="rfc">RFC</label>
                                                <input type="text" 
                                                       class="form-control" 
                                                       id="rfc" 
                                                       name="rfc"
                                                       placeholder="RFC de la empresa"
                                                       maxlength="13">
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="locality_id">Localidad Principal</label>
                                                <select class="form-control" 
                                                        id="locality_id" 
                                                        name="locality_id">
                                                    <option value="">Seleccionar localidad...</option>
                                                    @foreach($localities as $locality)
                                                        <option value="{{ $locality->id }}">
                                                            {{ $locality->locality }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="status" class="required">Estado</label>
                                                <select class="form-control" 
                                                        id="status" 
                                                        name="status" 
                                                        required>
                                                    <option value="active">Activo</option>
                                                    <option value="inactive">Inactivo</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="address" class="required">Dirección</label>
                                                <input type="text" 
                                                       class="form-control" 
                                                       id="address" 
                                                       name="address" 
                                                       required
                                                       placeholder="Dirección completa de la empresa/sindicato">
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="phone" class="required">Teléfono</label>
                                                <input type="text" 
                                                       class="form-control" 
                                                       id="phone" 
                                                       name="phone" 
                                                       required
                                                       placeholder="Ej: +1234567890">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="email">Email de Contacto</label>
                                                <input type="email" 
                                                       class="form-control" 
                                                       id="email" 
                                                       name="email"
                                                       placeholder="contacto@empresa.com">
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="notes">Notas Adicionales</label>
                                                <textarea class="form-control" 
                                                          id="notes" 
                                                          name="notes" 
                                                          rows="3"
                                                          placeholder="Información adicional sobre la empresa/sindicato"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Información del Usuario Admin Principal -->
                        <div class="col-12 mt-3">
                            <div class="card">
                                <div class="card-header bg-info text-white">
                                    <h6 class="mb-0">
                                        <i class="fas fa-user-shield mr-2"></i>
                                        Usuario Administrador Principal
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle mr-2"></i>
                                        Este usuario será el administrador principal de la empresa/sindicato y podrá gestionar todos sus sitios/rutas.
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="admin_name" class="required">Nombre del Admin</label>
                                                <input type="text" 
                                                       class="form-control" 
                                                       id="admin_name" 
                                                       name="admin_name" 
                                                       required
                                                       placeholder="Nombre completo del administrador">
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="admin_email" class="required">Email del Admin</label>
                                                <input type="email" 
                                                       class="form-control" 
                                                       id="admin_email" 
                                                       name="admin_email" 
                                                       required
                                                       placeholder="admin@empresa.com">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="admin_password" class="required">Contraseña</label>
                                                <input type="password" 
                                                       class="form-control" 
                                                       id="admin_password" 
                                                       name="admin_password" 
                                                       required
                                                       minlength="6"
                                                       placeholder="Mínimo 6 caracteres">
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="admin_password_confirmation" class="required">Confirmar Contraseña</label>
                                                <input type="password" 
                                                       class="form-control" 
                                                       id="admin_password_confirmation" 
                                                       name="admin_password_confirmation" 
                                                       required
                                                       minlength="6"
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
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save mr-1"></i>
                        Crear Empresa/Sindicato
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
