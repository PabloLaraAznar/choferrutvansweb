<div class="modal fade" id="modalCreateCoordinate" tabindex="-1" aria-labelledby="modalCreateCoordinateLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="{{ route('coordinates.store') }}" enctype="multipart/form-data">
      @csrf
      <div class="modal-content" style="border-radius: 12px; box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);">
        <div class="modal-header" style="background: linear-gradient(135deg, #ff6600, #e55a00); color: white; border-radius: 12px 12px 0 0; padding: 1.5rem;">
          <h5 class="modal-title" id="modalCreateCoordinateLabel" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
            <i class="fas fa-user-tie me-2"></i>Nuevo Coordinador
          </h5>
          <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Cerrar" style="opacity: 1; filter: brightness(0) invert(1);"></button>
        </div>
        <div class="modal-body" style="padding: 2rem;">
          <div class="mb-3">
            <label for="name" class="form-label fw-bold" style="color: #495057; font-family: 'Poppins', sans-serif;">
              <i class="fas fa-user me-2"></i>Nombre
            </label>
            <input type="text" class="form-control" id="name" name="name" required 
                   style="border: 2px solid #ff6600; border-radius: 8px; padding: 0.75rem; font-size: 0.95rem;">
          </div>

          <div class="mb-3">
            <label for="email" class="form-label fw-bold" style="color: #495057; font-family: 'Poppins', sans-serif;">
              <i class="fas fa-envelope me-2"></i>Correo Electrónico
            </label>
            <input type="email" class="form-control" id="email" name="email" required 
                   style="border: 2px solid #ff6600; border-radius: 8px; padding: 0.75rem; font-size: 0.95rem;">
          </div>

          <div class="mb-3">
            <label for="password" class="form-label fw-bold" style="color: #495057; font-family: 'Poppins', sans-serif;">
              <i class="fas fa-lock me-2"></i>Contraseña
            </label>
            <input type="password" class="form-control" id="password" name="password" required 
                   style="border: 2px solid #ff6600; border-radius: 8px; padding: 0.75rem; font-size: 0.95rem;">
          </div>

          <div class="mb-3">
            <label for="password_confirmation" class="form-label fw-bold" style="color: #495057; font-family: 'Poppins', sans-serif;">
              <i class="fas fa-lock me-2"></i>Confirmar Contraseña
            </label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required 
                   style="border: 2px solid #ff6600; border-radius: 8px; padding: 0.75rem; font-size: 0.95rem;">
          </div>

          <div class="mb-3">
            <label for="photo" class="form-label fw-bold" style="color: #495057; font-family: 'Poppins', sans-serif;">
              <i class="fas fa-camera me-2"></i>Foto
            </label>
            <input type="file" class="form-control" id="photo" name="photo" accept="image/*" 
                   style="border: 2px solid #ff6600; border-radius: 8px; padding: 0.75rem; font-size: 0.95rem;">
          </div>
        </div>
        <div class="modal-footer" style="padding: 1.5rem; background-color: #f8f9fa; border-radius: 0 0 12px 12px;">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" 
                  style="border-radius: 8px; padding: 0.5rem 1.5rem; font-weight: 500;">
            <i class="fas fa-times me-2"></i>Cancelar
          </button>
          <button type="submit" class="btn" 
                  style="background: linear-gradient(135deg, #ff6600, #e55a00); border-color: #ff6600; color: white; border-radius: 8px; padding: 0.5rem 1.5rem; font-weight: 600; font-family: 'Poppins', sans-serif;">
            <i class="fas fa-save me-2"></i>Crear Coordinador
          </button>
        </div>
      </div>
    </form>
  </div>
</div>
