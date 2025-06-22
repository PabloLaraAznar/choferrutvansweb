<form action="{{ route('localidades.store') }}" method="POST">
    @csrf

    <div class="row">
        <!-- Longitud y Latitud (readonly, se llenan desde JS) -->
        <div class="col-md-6 mb-3">
            <label for="longitude" class="form-label">Longitud:</label>
            <input type="text" id="longitude" name="longitude" class="form-control" readonly required>
        </div>

        <div class="col-md-6 mb-3">
            <label for="latitude" class="form-label">Latitud:</label>
            <input type="text" id="latitude" name="latitude" class="form-control" readonly required>
        </div>

        <!-- Localidad -->
        <div class="col-md-12 mb-3">
            <label for="locality" class="form-label">Localidad:</label>
            <input type="text" id="locality" name="locality" class="form-control" readonly required>
        </div>

        <!-- Calle -->
        <div class="col-md-12 mb-3">
            <label for="street" class="form-label">Calle:</label>
            <input type="text" id="street" name="street" class="form-control" readonly>
        </div>

        <!-- Código Postal -->
        <div class="col-md-12 mb-3">
            <label for="postal_code" class="form-label">Código Postal:</label>
            <input type="text" id="postal_code" name="postal_code" class="form-control" readonly>
        </div>

       <!-- ### NUEVOS CAMPOS OCULTOS ### -->

<!-- Municipio -->
<input type="hidden" id="municipality" name="municipality">

<!-- Estado -->
<input type="hidden" id="state" name="state">

<!-- País -->
<input type="hidden" id="country" name="country">

<!-- Tipo de Localidad -->
<input type="hidden" id="locality_type" name="locality_type">


        <!-- Botón Guardar -->
        <div class="col-md-12 mt-3">
            <button type="submit" class="btn btn-primary w-100">Guardar Ubicación</button>
        </div>
    </div>
</form>
