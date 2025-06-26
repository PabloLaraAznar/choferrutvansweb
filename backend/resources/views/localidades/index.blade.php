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
                <div id="map" class="border border-primary rounded-3 shadow-sm flex-grow-1"
                     style="width: 100%; height: 500px; border-radius: 10px; box-shadow: 0 8px 32px 0 rgba(31,38,135,0.37);"></div>
            </div>
            <!-- Formulario de localidades -->
            <div class="col-md-5">
                <h4 class="text-center mb-3">Formulario de Localidad</h4>
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        {{-- Incluimos el formulario de creación --}}
                        @include('localidades.create')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
    <link href="https://api.mapbox.com/mapbox-gl-js/v3.3.0/mapbox-gl.css" rel="stylesheet" />
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
        .mapboxgl-marker {
            border-radius: 50%;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }
        /* Estilo para el control personalizado */
        .mapboxgl-ctrl {
            border-radius: 12px!important;
            box-shadow: 0 2px 8px rgba(0,0,0,0.16)!important;
            background: rgba(255,255,255,0.95)!important;
        }
        #map {
            background: linear-gradient(140deg, #e0e7ef 0%, #d0e0fc 100%);
        }
    </style>
@endsection

@section('js')
<script src="https://api.mapbox.com/mapbox-gl-js/v3.3.0/mapbox-gl.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Función para suavizar animaciones de la cámara
function flyToLocation(map, lng, lat) {
    map.flyTo({
        center: [lng, lat],
        zoom: 17,
        bearing: map.getBearing(),
        pitch: map.getPitch(),
        speed: 1.4,
        curve: 1.1,
        easing: function (t) { return t; }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    mapboxgl.accessToken = 'pk.eyJ1IjoiYW5nZWwwNDE4IiwiYSI6ImNtOG5idHFybzBob3EyaW85NmkxYXZub3EifQ.m1qJwwbbT_wyOqPtDFGb7A';

    // Mapa con estilo 3D y controles avanzados
    const map = new mapboxgl.Map({
        container: 'map',
        style: 'mapbox://styles/mapbox/satellite-streets-v12',
        center: [-89.5133, 20.9256],
        zoom: 16.5,
        pitch: 65,
        bearing: -30,
        antialias: true
    });

    // Añadir controles 3D y navegación de estilo Google Maps
    map.addControl(new mapboxgl.NavigationControl({ showCompass: true, showZoom: true }), 'top-right');
    map.addControl(new mapboxgl.FullscreenControl(), 'top-right');
    // Añadir control de rotación 3D interactivo
    map.dragRotate.enable();
    map.touchZoomRotate.enableRotation();

    // Control de tipo Street View (Mapbox no tiene streetview, pero puedes simularlo con Mapillary u Openverse)
    // Aquí solo agregamos la sensación de 3D y navegación fluida

    map.on('load', () => {
        map.resize();
        const layers = map.getStyle().layers;
        const labelLayerId = layers.find(
            layer => layer.type === 'symbol' && layer.layout['text-field']
        )?.id;
        // Capa de edificios extruidos para efecto 3D realista
        map.addLayer(
            {
                id: '3d-buildings',
                source: 'composite',
                'source-layer': 'building',
                filter: ['==', 'extrude', 'true'],
                type: 'fill-extrusion',
                minzoom: 15,
                paint: {
                    'fill-extrusion-color': [
                        'interpolate', ['linear'], ['get', 'height'],
                        0, "#d1d5db",
                        20, "#b2bec3",
                        50, "#636e72"
                    ],
                    'fill-extrusion-height': ['get', 'height'],
                    'fill-extrusion-base': ['get', 'min_height'],
                    'fill-extrusion-opacity': 0.85
                }
            },
            labelLayerId
        );
    });

    let currentMarker = null;

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

    const savedLocations = @json($localidades);

    function createCustomMarker() {
        const markerDiv = document.createElement('div');
        markerDiv.style.fontSize = '28px';
        markerDiv.style.color = '#ff006e';
        markerDiv.style.cursor = 'pointer';
        markerDiv.style.filter = 'drop-shadow(0 4px 12px #3336)';
        markerDiv.classList.add('fas', 'fa-map-marker-alt', 'animate__animated', 'animate__bounceInDown');
        return markerDiv;
    }

    // --------- FUNCIONES PARA LOCALIDAD Y CALLE --------
    function getRealLocality(features) {
        for (const feat of features) {
            if (feat.id && feat.id.startsWith('place.')) {
                return feat.text;
            }
            if (feat.context) {
                const ctx = feat.context.find(c => c.id && c.id.startsWith('place.'));
                if (ctx) return ctx.text;
            }
        }
        const types = ['village','town','locality','neighborhood','hamlet','city'];
        for (const type of types) {
            const f = features.find(f => f.place_type && f.place_type.includes(type));
            if (f) return f.text;
        }
        return features[0]?.text || 'N/A';
    }
    function getStreet(features) {
        const f = features.find(f => f.place_type && (f.place_type.includes('address') || f.place_type.includes('street')));
        return f ? f.text : 'N/A';
    }
    // ---------------------------------------------------

    savedLocations.forEach(location => {
        const marker = new mapboxgl.Marker({
                element: createCustomMarker()
            })
            .setLngLat([location.longitude, location.latitude])
            .setPopup(new mapboxgl.Popup().setHTML(`
                <strong>${location.locality}</strong><br>
                Calle: ${location.street || 'N/A'}<br>
                Código Postal: ${location.postal_code || 'N/A'}<br>
                Municipio: ${location.municipality}<br>
                Estado: ${location.state}<br>
                País: ${location.country}<br>
                Tipo: ${location.locality_type}<br><br>
                <button class="btn btn-sm btn-danger delete-btn" data-id="${location.id}">
                    <i class="fas fa-trash-alt"></i> Eliminar
                </button>
            `))
            .addTo(map);

        marker.setDraggable(true);

        marker.getElement().addEventListener('click', () => {
            flyToLocation(map, location.longitude, location.latitude);
            setTimeout(() => {
                const deleteBtn = document.querySelector('.delete-btn');
                if (deleteBtn) {
                    deleteBtn.addEventListener('click', function () {
                        const id = this.dataset.id;
                        Swal.fire({
                            title: '¿Eliminar esta localidad?',
                            text: "Esta acción no se puede deshacer.",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#6c757d',
                            confirmButtonText: 'Sí, eliminar',
                            cancelButtonText: 'Cancelar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                const form = document.createElement('form');
                                form.method = 'POST';
                                form.action = `{{ url('localidades') }}/${id}`;
                                form.innerHTML = `
                                    @csrf
                                    @method('DELETE')
                                `;
                                document.body.appendChild(form);
                                form.submit();
                            }
                        });
                    });
                }
            }, 250);
        });

        marker.on('dragend', async function() {
            const newCoords = marker.getLngLat();
            flyToLocation(map, newCoords.lng, newCoords.lat);
            const url = `https://api.mapbox.com/geocoding/v5/mapbox.places/${newCoords.lng},${newCoords.lat}.json?access_token=${mapboxgl.accessToken}`;
            let updatedStreet     = location.street;
            let updatedLocality   = location.locality;
            let updatedPostalCode = location.postal_code;
            let updatedMunicipality = location.municipality;
            let updatedState        = location.state;
            let updatedCountry      = location.country;
            let updatedLocalityType = location.locality_type;

            try {
                const response = await fetch(url);
                const data = await response.json();
                updatedStreet = getStreet(data.features);
                updatedLocality = getRealLocality(data.features);

                if (data.features.length > 0) {
                    const context = data.features[0].context || [];
                    const postalCodeFeature = context.find(c => c.id.includes('postcode'));
                    if (postalCodeFeature) updatedPostalCode = postalCodeFeature.text;
                    const municipalityFeature = context.find(c => c.id.includes('district'));
                    if (municipalityFeature) updatedMunicipality = municipalityFeature.text;
                    const stateFeature = context.find(c => c.id.includes('region'));
                    if (stateFeature) updatedState = stateFeature.text;
                    const countryFeature = context.find(c => c.id.includes('country'));
                    if (countryFeature) updatedCountry = countryFeature.text;
                    updatedLocalityType = data.features[0].place_type ? data.features[0].place_type.join(', ') : location.locality_type;
                }
            } catch (error) {
                console.error('Error obteniendo datos actualizados:', error);
            }

            Swal.fire({
                title: '¿Estás seguro?',
                html: `
                    <p><b>Localidad Actual:</b> ${location.locality}</p>
                    <p><b>Nueva Localidad:</b> ${updatedLocality}</p>
                    <p><b>Calle Actual:</b> ${location.street || 'N/A'}</p>
                    <p><b>Calle Nueva:</b> ${updatedStreet}</p>
                    <p><b>Código Postal Actual:</b> ${location.postal_code || 'N/A'}</p>
                    <p><b>Código Postal Nuevo:</b> ${updatedPostalCode}</p>
                    <p><b>Municipio Actual:</b> ${location.municipality}</p>
                    <p><b>Municipio Nuevo:</b> ${updatedMunicipality}</p>
                    <p><b>Estado Actual:</b> ${location.state}</p>
                    <p><b>Estado Nuevo:</b> ${updatedState}</p>
                    <p><b>País Actual:</b> ${location.country}</p>
                    <p><b>País Nuevo:</b> ${updatedCountry}</p>
                    <p><b>Tipo Actual:</b> ${location.locality_type}</p>
                    <p><b>Tipo Nuevo:</b> ${updatedLocalityType}</p>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, actualizar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33'
            }).then((result) => {
                if (result.isConfirmed) {
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
                        <input type="hidden" name="municipality" value="${updatedMunicipality}">
                        <input type="hidden" name="state" value="${updatedState}">
                        <input type="hidden" name="country" value="${updatedCountry}">
                        <input type="hidden" name="locality_type" value="${updatedLocalityType}">
                    `;
                    document.body.appendChild(form);
                    form.submit();
                } else {
                    marker.setLngLat([location.longitude, location.latitude]);
                }
            });
        });

        marker.getElement().addEventListener('contextmenu', function(e) {
            e.preventDefault();
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
                    marker.remove();
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

    // Evento: Seleccionar nueva ubicación en el mapa
    map.on('click', async function(e) {
        const coordinates = e.lngLat;
        if (currentMarker) currentMarker.remove();
        currentMarker = new mapboxgl.Marker({ color: '#007BFF' }).setLngLat(coordinates).addTo(map);

        flyToLocation(map, coordinates.lng, coordinates.lat);

        const url = `https://api.mapbox.com/geocoding/v5/mapbox.places/${coordinates.lng},${coordinates.lat}.json?access_token=${mapboxgl.accessToken}`;
        try {
            const response = await fetch(url);
            const data = await response.json();
            let locality = getRealLocality(data.features);
            let street = getStreet(data.features);
            let postalCode = 'N/A', municipality = '', state = '', country = '', locality_type = '';
            if (data.features.length > 0) {
                const context = data.features[0].context || [];
                const postalCodeFeature = context.find(c => c.id.includes('postcode'));
                if (postalCodeFeature) postalCode = postalCodeFeature.text;
                const municipalityFeature = context.find(c => c.id.includes('district'));
                if (municipalityFeature) municipality = municipalityFeature.text;
                const stateFeature = context.find(c => c.id.includes('region'));
                if (stateFeature) state = stateFeature.text;
                const countryFeature = context.find(c => c.id.includes('country'));
                if (countryFeature) country = countryFeature.text;
                locality_type = data.features[0].place_type ? data.features[0].place_type.join(', ') : '';
            }
            document.getElementById('longitude').value = coordinates.lng;
            document.getElementById('latitude').value = coordinates.lat;
            document.getElementById('locality').value = locality;
            document.getElementById('street').value = street;
            document.getElementById('postal_code').value = postalCode;
            document.getElementById('municipality').value = municipality;
            document.getElementById('state').value = state;
            document.getElementById('country').value = country;
            document.getElementById('locality_type').value = locality_type;
        } catch (error) {
            console.error('Error obteniendo la ubicación:', error);
        }
    });
});
</script>
@endsection