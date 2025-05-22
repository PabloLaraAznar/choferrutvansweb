@extends('adminlte::page')

@section('title', 'Localidades')

@section('content_header')
    <h1 class="text-center text-primary">Administra tus Localidades</h1>
@endsection

@section('content')
<div class="card shadow-lg rounded-lg border-0">
    <div class="card-header d-flex justify-content-between align-items-center bg-light border-bottom">
        <h3 class="badge bg-success text-white p-3 rounded-pill shadow-sm">
            <i class="fas fa-map-marker-alt me-2"></i> Total de Localidades: {{ $localidadesCount }}
        </h3>
    </div>
    
    <div class="card-body p-4">
        <div class="row">
            <!-- Contenedor del mapa -->
            <div class="col-md-7">
                <div id="map" class="border border-primary rounded-3 shadow-sm flex-grow-1" style="width: 100%; height: 500px; border-radius: 10px;"></div>
            </div>

            <!-- Formulario de localidades -->
            <div class="col-md-5">
                <h4 class="text-center mb-3">Formulario de Localidad</h4>
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        @include('localidades.create')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
    <link href="https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .swal-toast {
            font-size: 14px;
            border-radius: 8px;
            padding: 10px;
            background-color: #007BFF;
            color: white;
        }

        .mapboxgl-popup {
            max-width: 300px;
            font-size: 14px;
            text-align: left;
            color: #333;
        }

        .mapboxgl-popup-content {
            padding: 15px;
        }

        /* Estilo para el marcador personalizado */
        .mapboxgl-marker {
            border-radius: 50%;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }
    </style>
@endsection

@section('js')
    <script src="https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            mapboxgl.accessToken = 'pk.eyJ1IjoiYW5nZWwwNDE4IiwiYSI6ImNtOG5idHFybzBob3EyaW85NmkxYXZub3EifQ.m1qJwwbbT_wyOqPtDFGb7A';
        
            const map = new mapboxgl.Map({
                container: 'map',
                style: 'mapbox://styles/mapbox/streets-v11',
                center: [-89.5133, 20.9256],
                zoom: 8
            });
        
            let currentMarker = null;
        
            // Mostrar mensaje de éxito o error desde el controlador
            const successMessage = '{{ session('success') }}';
            const errorMessage = '{{ session('error') }}';
        
            if (successMessage) {
                Swal.fire({
                    icon: 'success',
                    title: successMessage,
                    toast: true,
                    position: 'bottom-end',
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        
            if (errorMessage) {
                Swal.fire({
                    icon: 'error',
                    title: errorMessage,
                    toast: true,
                    position: 'bottom-end',
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        
            // Agregar marcadores para las localidades guardadas
            const savedLocations = @json($localidades);
        
            savedLocations.forEach(location => {
                // Crear el marcador con FontAwesome
                const marker = new mapboxgl.Marker({
                        element: createCustomMarker()  // Método para crear un marcador con FontAwesome
                    })
                    .setLngLat([location.longitude, location.latitude])
                    .setPopup(new mapboxgl.Popup().setText(
                        `${location.locality}, ${location.street}, ${location.postal_code}`
                    ))
                    .addTo(map);
        
                // Función para crear un marcador con FontAwesome
                function createCustomMarker() {
                    const markerDiv = document.createElement('div');
                    markerDiv.style.fontSize = '24px';  // Tamaño del ícono
                    markerDiv.style.color = '#FF5733';  // Color del ícono
                    markerDiv.style.cursor = 'pointer';  // Para indicar que es interactivo
                    markerDiv.classList.add('fas', 'fa-map-marker-alt');  // Clases de FontAwesome (ícono de marcador de mapa)
                    return markerDiv;
                }

                // Hacer que el marcador sea arrastrable
                marker.setDraggable(true);
        
                // Evento: Cuando el marcador deja de arrastrarse
                marker.on('dragend', async function() {
                    const newCoords = marker.getLngLat();
                    console.log(`Marcador movido. ID: ${location.id}, Nueva posición:`, newCoords);
        
                    // Obtener datos nuevos para localidad
                    const url =
                        `https://api.mapbox.com/geocoding/v5/mapbox.places/${newCoords.lng},${newCoords.lat}.json?access_token=${mapboxgl.accessToken}`;
                    let updatedStreet = location.street;
                    let updatedLocality = location.locality;
                    let updatedPostalCode = location.postal_code;
        
                    try {
                        const response = await fetch(url);
                        const data = await response.json();
                        if (data.features.length > 0) {
                            const place = data.features[0];
                            updatedStreet = place.text || location.street;
                            updatedLocality = place.context?.find(c => c.id.includes('place'))
                                ?.text || location.locality;
                            updatedPostalCode = place.context?.find(c => c.id.includes(
                                'postcode'))?.text || location.postal_code;
                        }
                    } catch (error) {
                        console.error('Error obteniendo datos actualizados:', error);
                    }
        
                    // Mostrar la alerta de confirmación con los valores actuales y nuevos
                    Swal.fire({
                        title: '¿Estás seguro?',
                        html: `
                            <p><b>Localidad Actual:</b> ${location.locality}</p>
                            <p><b>Nueva Localidad:</b> ${updatedLocality}</p>
                            <p><b>Calle Actual:</b> ${location.street}</p>
                            <p><b>Calle Nueva:</b> ${updatedStreet}</p>
                            <p><b>Código Postal Actual:</b> ${location.postal_code}</p>
                            <p><b>Código Postal Nuevo:</b> ${updatedPostalCode}</p>
                        `,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Sí, actualizar',
                        cancelButtonText: 'Cancelar',
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Enviar los datos al servidor
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = `{{ url('localidades') }}/${location.id}`;
                            form.innerHTML = `
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="longitude" value="${newCoords.lng}">
                                <input type="hidden" name="latitude" value="${newCoords.lat}">
                                <input type="hidden" name="locality" value="${updatedLocality}">
                                <input type="hidden" name="street" value="${updatedStreet}">
                                <input type="hidden" name="postal_code" value="${updatedPostalCode}">
                            `;
                            document.body.appendChild(form);
                            form.submit();
                        } else {
                            marker.setLngLat([location.longitude, location.latitude]);
                        }
                    });
                });
        
                // Evento: Eliminar marcador con clic derecho
                marker.getElement().addEventListener('contextmenu', function(e) {
                    e.preventDefault(); // Prevenir el menú contextual del navegador
                    Swal.fire({
                        title: '¿Estás seguro de que deseas eliminar este marcador?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar',
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Eliminar marcador del mapa
                            marker.remove();
        
                            // Eliminar también desde el servidor
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = `{{ url('localidades') }}/${location.id}`;
                            form.innerHTML = `
                                @csrf
                                @method('DELETE')
                            `;
                            document.body.appendChild(form);
                            form.submit();
                        }
                    });
                });
            });
        
            // Evento: Seleccionar una nueva ubicación en el mapa
            map.on('click', async function(e) {
                const coordinates = e.lngLat;
            
                // Eliminar marcador previo si existe
                if (currentMarker) {
                    currentMarker.remove();
                }
            
                // Crear un nuevo marcador en la nueva ubicación
                currentMarker = new mapboxgl.Marker({
                        color: '#007BFF'
                    })
                    .setLngLat(coordinates)
                    .addTo(map);
            
                const url =
                    `https://api.mapbox.com/geocoding/v5/mapbox.places/${coordinates.lng},${coordinates.lat}.json?access_token=${mapboxgl.accessToken}`;
            
                try {
                    const response = await fetch(url);
                    const data = await response.json();
            
                    if (data.features.length > 0) {
                        const place = data.features[0];
                        let street = 'N/A';  // Valor por defecto para la calle
                        let locality = 'N/A';  // Valor por defecto para la localidad
                        let postalCode = 'N/A';  // Valor por defecto para el código postal
            
                        // Buscar la calle, si existe
                        const streetFeature = data.features.find(feature => feature.place_type.includes('address'));
                        if (streetFeature) {
                            street = streetFeature.text;
                        }
            
                        // Buscar la localidad, si existe
                        const localityFeature = place.context?.find(c => c.id.includes('place'));
                        if (localityFeature) {
                            locality = localityFeature.text;
                        }
            
                        // Buscar el código postal, si existe
                        const postalCodeFeature = place.context?.find(c => c.id.includes('postcode'));
                        if (postalCodeFeature) {
                            postalCode = postalCodeFeature.text;
                        }
            
                        // Asignar los valores correctos a los campos del formulario
                        document.getElementById('longitude').value = coordinates.lng;
                        document.getElementById('latitude').value = coordinates.lat;
                        document.getElementById('locality').value = locality;  // Asignar localidad correctamente
                        document.getElementById('street').value = street;  // Asignar calle correctamente
                        document.getElementById('postal_code').value = postalCode;  // Asignar código postal correctamente
                    }
                } catch (error) {
                    console.error('Error obteniendo la ubicación:', error);
                }
            });
            
        });
    </script>
@endsection
