﻿<!-- Modal para Crear Sitio/Ruta -->
<div class="modal fade" id="createSiteModal" tabindex="-1" aria-labelledby="createSiteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="{{ route('clients.store') }}" method="POST" id="createSiteForm">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="createSiteModalLabel">
                        <i class="fas fa-plus me-2"></i>
                        Nuevo Sitio o Ruta
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Cerrar"></button>
                </div>

                <div class="modal-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <h6><i class="fas fa-exclamation-triangle"></i> Errores de validación:</h6>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
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
                                        <i class="fas fa-route me-2"></i>
                                        Información del Sitio o Ruta
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="company_id" class="required">Empresa/Sindicato</label>
                                                <select class="form-control @error('company_id') is-invalid @enderror"
                                                    id="company_id" name="company_id" required>
                                                    <option value="">Seleccionar empresa...</option>
                                                    @foreach ($companies as $company)
                                                        <option value="{{ $company->id }}"
                                                            {{ old('company_id') == $company->id ? 'selected' : '' }}>
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
                                                <label for="name" class="required">Nombre del Sitio</label>
                                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                    id="name" name="name" value="{{ old('name') }}" required
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
                                                <input type="text" class="form-control @error('route_name') is-invalid @enderror"
                                                    id="route_name" name="route_name" value="{{ old('route_name') }}"
                                                    placeholder="Ej: Maxcanú-Mérida">
                                                @error('route_name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="locality_id" class="required">Localidad</label>
                                                <select class="form-control @error('locality_id') is-invalid @enderror"
                                                    id="locality_id" name="locality_id" required>
                                                    <option value="">Seleccionar localidad...</option>
                                                    @foreach ($localities as $locality)
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
                                                <input type="text" class="form-control @error('address') is-invalid @enderror"
                                                    id="address" name="address" value="{{ old('address') }}" required
                                                    placeholder="Dirección completa del sitio">
                                                @error('address')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="phone" class="required">Teléfono</label>
                                                <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                                    id="phone" name="phone" value="{{ old('phone') }}" required
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
                                                    id="status" name="status" required>
                                                    <option value="">Seleccionar estado...</option>
                                                    <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>
                                                        Activo
                                                    </option>
                                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>
                                                        Inactivo
                                                    </option>
                                                </select>
                                                @error('status')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div> <!-- card-body -->
                            </div> <!-- card -->
                        </div> <!-- col-12 -->
                    </div> <!-- row -->
                </div> <!-- modal-body -->

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Cancelar">
                        <i class="fas fa-times me-1"></i>
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-success" aria-label="Crear Sitio">
                        <i class="fas fa-save me-1"></i>
                        Crear Sitio
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
