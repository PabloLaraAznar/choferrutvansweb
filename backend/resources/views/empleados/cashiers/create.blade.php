<div class="modal fade" id="modalCreateCashier" tabindex="-1" aria-labelledby="modalCreateCashierLabel" aria-hidden="true" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <form method="POST" action="{{ route('cashiers.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-content rounded-3 shadow-lg">
                <div class="modal-header text-white"
                    style="background: linear-gradient(135deg, #ff6600, #e55a00); padding: 1rem;">
                    <h5 class="modal-title" id="modalCreateCashierLabel">
                        <i class="fas fa-cash-register me-2"></i>Nuevo Cajero
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Cerrar"></button>
                </div>

                <div class="modal-body">
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">
                                <i class="fas fa-id-badge me-2"></i>
                                Información del Cajero
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label fw-bold">
                                        <i class="fas fa-user me-1"></i>Nombre
                                    </label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="email" class="form-label fw-bold">
                                        <i class="fas fa-envelope me-1"></i>Correo Electrónico
                                    </label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="password" class="form-label fw-bold">
                                        <i class="fas fa-lock me-1"></i>Contraseña
                                    </label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="password_confirmation" class="form-label fw-bold">
                                        <i class="fas fa-lock me-1"></i>Confirmar Contraseña
                                    </label>
                                    <input type="password" class="form-control" id="password_confirmation"
                                        name="password_confirmation" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="photo" class="form-label fw-bold">
                                        <i class="fas fa-camera me-1"></i>Foto
                                    </label>
                                    <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
                                    <div class="text-center mt-2">
                                        <img id="photo-preview" src="#" alt="Preview"
                                            style="display: none; width: 100px; height: 100px; object-fit: cover; border-radius: 50%; box-shadow: 0 2px 6px rgba(0,0,0,0.2);">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info d-flex align-items-center mt-3" role="alert">
                        <i class="fas fa-info-circle me-2"></i>
                        El cajero será asignado automáticamente al mismo sitio que tú.
                    </div>
                </div>

                <div class="modal-footer bg-light rounded-bottom">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </button>
                    <button type="submit" class="btn text-white"
                        style="background: linear-gradient(135deg, #ff6600, #e55a00); font-weight: 600;">
                        <i class="fas fa-save me-2"></i>Crear Cajero
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
