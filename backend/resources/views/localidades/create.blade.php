<form action="{{ route('localidades.store') }}" method="POST">
    @csrf
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="longitude">Longitud:</label>
                <input type="text" id="longitude" name="longitude" class="form-control" readonly>
            </div>

            <div class="form-group">
                <label for="latitude">Latitud:</label>
                <input type="text" id="latitude" name="latitude" class="form-control" readonly>
            </div>

            <div class="form-group">
                <label for="locality">Localidad:</label>
                <input type="text" id="locality" name="locality" class="form-control" readonly>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="street">Calle:</label>
                <input type="text" id="street" name="street" class="form-control" readonly>
            </div>

            <div class="form-group">
                <label for="postal_code">Código Postal:</label>
                <input type="text" id="postal_code" name="postal_code" class="form-control" readonly>
            </div>

            <button type="submit" class="btn btn-primary btn-block mt-4">Guardar Ubicación</button>
        </div>
    </div>
</form>
