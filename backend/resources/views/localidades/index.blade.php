@extends('adminlte::page')

@section('title', 'Localidades')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center mb-4" style="background: linear-gradient(135deg, #ff6600 0%, #ff8533 100%); padding: 1.5rem; border-radius: 15px; box-shadow: 0 8px 25px rgba(255, 102, 0, 0.15); animation: fadeInDown 0.6s ease-out;">
        <div>
            <h1 class="text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 2rem;">
                <i class="fas fa-map-marker-alt me-3"></i> Gestión de Localidades
            </h1>
            <p class="text-white mb-0" style="font-family: 'Poppins', sans-serif; opacity: 0.9; font-size: 1.1rem;">
                Administra las ubicaciones geográficas del sistema
            </p>
        </div>
    </div>
@endsection

@section('content')
<div class="card shadow-sm" style="border: none; border-radius: 15px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1) !important;">
    <div class="card-header d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #ff6600, #e55a00); color: white; border-radius: 15px 15px 0 0; padding: 1.5rem;">
        <h3 class="mb-0" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
            <i class="fas fa-globe-americas me-2"></i> Total de Localidades: <span class="badge bg-light text-dark ms-2">{{ $localidadesCount }}</span>
        </h3>
    </div>
    <div class="card-body" style="padding: 2rem;">
        <div class="row">
            <!-- Contenedor del mapa con buscador arriba y switch 2D/3D dentro del mapa, esquina superior izquierda -->
            <div class="col-md-7">
                <div class="mb-3">
                    <div class="input-group">
                        <input id="search-location" type="text" class="form-control" placeholder="Buscar localidad, calle, municipio, etc...">
                        <button id="search-btn" class="btn btn-primary" type="button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                    <ul id="autocomplete-list" class="list-group position-absolute w-100" style="z-index: 1000;"></ul>
                </div>
                <div class="position-relative" style="width: 100%; height:500px;">
                    <!-- Botones 2D/3D dentro del mapa, esquina superior izquierda -->
                    <div id="map-mode-switch" class="position-absolute" style="top: 16px; left: 16px; z-index: 15;">
                        <div class="btn-group btn-group-toggle shadow rounded-pill" role="group" aria-label="Switch Map Mode">
                            <button id="switch-2d-btn" class="btn btn-light btn-sm active border border-primary" type="button" title="Vista 2D" style="border-radius: 16px 0 0 16px;">
                                <i class="fas fa-map"></i>
                            </button>
                            <button id="switch-3d-btn" class="btn btn-light btn-sm border border-primary" type="button" title="Vista 3D" style="border-radius: 0 16px 16px 0;">
                                <i class="fas fa-cube"></i>
                            </button>
                        </div>
                    </div>
                    <div id="map" class="border border-primary rounded-3 shadow-sm flex-grow-1"
                         style="width: 100%; height: 500px; border-radius: 10px; box-shadow: 0 8px 32px 0 rgba(31,38,135,0.37);"></div>
                </div>
            </div>
            <!-- Formulario de localidades -->
            <div class="col-md-5">
                <h4 class="text-center mb-3" style="color: #ff6600; font-family: 'Poppins', sans-serif; font-weight: 600;">Formulario de Localidad</h4>
                <div class="card" style="border: 2px solid #ff6600; border-radius: 12px;">
                    <div class="card-body" style="padding: 1.5rem;">
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
        body {
            background-color: #f8f9fa;
        }
        
        .content-wrapper {
            background-color: #f8f9fa;
        }
        
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
        .mapboxgl-ctrl {
            border-radius: 12px!important;
            box-shadow: 0 2px 8px rgba(0,0,0,0.16)!important;
            background: rgba(255,255,255,0.95)!important;
        }
        #map {
            background: linear-gradient(140deg, #e0e7ef 0%, #d0e0fc 100%);
        }
        #map-mode-switch .btn.active,
        #map-mode-switch .btn:active,
        #map-mode-switch .btn:focus {
            background: #007bff !important;
            color: white !important;
            border-color: #007bff !important;
        }
        /* Autocomplete */
        #autocomplete-list {
            max-height: 240px;
            overflow-y: auto;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
        }
        #autocomplete-list .list-group-item {
            cursor: pointer;
        }
        #autocomplete-list .list-group-item:hover {
            background: #f1f3f9;
        }
    </style>
@endsection

@section('js')
<script src="https://api.mapbox.com/mapbox-gl-js/v3.3.0/mapbox-gl.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Guardar variables de estado
let is3D = false; // 2D default
let map = null;
let mapLoaded = false;
let currentStyle3D = 'mapbox://styles/mapbox/satellite-streets-v12';
let currentStyle2D = 'mapbox://styles/mapbox/streets-v12';
let lastCenter = [-89.5133, 20.9256];
let lastZoom = 16.5;
let lastPitch = 45; // 2D con rotación (pitch)
let lastBearing = -17.6;
let savedMarkers = [];
let currentMarker = null;

function createMap({style, pitch, bearing, zoom, center}) {
    if (map) {
        map.remove();
        map = null;
    }
    map = new mapboxgl.Map({
        container: 'map',
        style: style,
        center: center,
        zoom: zoom,
        pitch: pitch,
        bearing: bearing,
        antialias: true
    });
    map.addControl(new mapboxgl.NavigationControl({ showCompass: true, showZoom: true }), 'top-right');
    map.addControl(new mapboxgl.FullscreenControl(), 'top-right');
    // Siempre con rotación y pitch habilitado (como pediste)
    map.dragRotate.enable();
    map.touchZoomRotate.enableRotation();
    map.on('load', () => {
        mapLoaded = true;
        map.resize();
        // Edificios 3D solo si está en modo 3D
        if (is3D) {
            const layers = map.getStyle().layers;
            const labelLayerId = layers.find(
                layer => layer.type === 'symbol' && layer.layout['text-field']
            )?.id;
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
        }
        // Restaurar marcadores guardados
        for (const { marker } of savedMarkers) {
            marker.addTo(map);
        }
        if (currentMarker) {
            currentMarker.addTo(map);
        }
    });
    // Escuchar click en mapa para seleccionar nueva ubicación
    map.on('click', async function(e) {
        const coordinates = e.lngLat;
        if (currentMarker) currentMarker.remove();
        currentMarker = new mapboxgl.Marker({ color: '#007BFF' }).setLngLat(coordinates).addTo(map);

        flyToLocation(map, coordinates.lng, coordinates.lat);

        // Usar Mapbox Places para el formulario (puedes cambiar a Nominatim si quieres)
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
}

function flyToLocation(map, lng, lat) {
    map.flyTo({
        center: [lng, lat],
        zoom: map.getZoom(),
        bearing: map.getBearing(),
        pitch: map.getPitch(),
        speed: 1.4,
        curve: 1.1,
        easing: function (t) { return t; }
    });
}
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

document.addEventListener('DOMContentLoaded', function() {
    mapboxgl.accessToken = 'pk.eyJ1IjoiYW5nZWwwNDE4IiwiYSI6ImNtOG5idHFybzBob3EyaW85NmkxYXZub3EifQ.m1qJwwbbT_wyOqPtDFGb7A';

    // Inicializa mapa en modo 2D con rotación
    is3D = false;
    createMap({
        style: currentStyle2D,
        pitch: lastPitch,
        bearing: lastBearing,
        zoom: lastZoom,
        center: lastCenter
    });

    // Mostrar alertas si existen
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

    // Crear y guardar marcadores para restaurar en cambio de mapa
    savedMarkers = [];
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
            `));
        savedMarkers.push({ marker, location });

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

    // ===================
    // BUSCADOR NOMINATIM
    // ===================
    const searchInput = document.getElementById('search-location');
    const searchBtn = document.getElementById('search-btn');
    const autocompleteList = document.getElementById('autocomplete-list');
    let debounceTimer;

    function showAutocomplete(suggestions) {
        autocompleteList.innerHTML = '';
        suggestions.forEach(place => {
            const item = document.createElement('li');
            item.classList.add('list-group-item');
            item.textContent = place.display_name;
            item.addEventListener('click', function() {
                flyToLocation(map, parseFloat(place.lon), parseFloat(place.lat));
                if (currentMarker) currentMarker.remove();
                currentMarker = new mapboxgl.Marker({ color: '#0d6efd' })
                    .setLngLat([parseFloat(place.lon), parseFloat(place.lat)])
                    .addTo(map);
                autocompleteList.innerHTML = '';
                searchInput.value = place.display_name;

                if (document.getElementById('longitude')) document.getElementById('longitude').value = place.lon;
                if (document.getElementById('latitude')) document.getElementById('latitude').value = place.lat;
                if (document.getElementById('locality')) document.getElementById('locality').value =
                    place.address.city ||
                    place.address.town ||
                    place.address.village ||
                    place.address.locality || '';
                if (document.getElementById('street')) document.getElementById('street').value =
                    place.address.road ||
                    place.address.street ||
                    place.address.neighbourhood ||
                    place.address.suburb ||
                    '';
                if (document.getElementById('postal_code')) document.getElementById('postal_code').value = place.address.postcode || '';
                if (document.getElementById('municipality')) document.getElementById('municipality').value = place.address.municipality || '';
                if (document.getElementById('state')) document.getElementById('state').value = place.address.state || '';
                if (document.getElementById('country')) document.getElementById('country').value = place.address.country || '';
                if (document.getElementById('locality_type')) document.getElementById('locality_type').value = place.type || '';
            });
            autocompleteList.appendChild(item);
        });
    }

    async function searchPlaces(query) {
        if (!query) {
            autocompleteList.innerHTML = '';
            return;
        }
        const url = `https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(query + ', Yucatán, México')}&format=json&addressdetails=1&countrycodes=mx&limit=8`;
        try {
            const res = await fetch(url, { headers: { 'Accept-Language': 'es' } });
            const data = await res.json();
            if (data && data.length > 0) {
                showAutocomplete(data);
            } else {
                autocompleteList.innerHTML = '';
            }
        } catch (err) {
            autocompleteList.innerHTML = '';
        }
    }

    searchInput.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        const value = searchInput.value.trim();
        if (value.length < 2) {
            autocompleteList.innerHTML = '';
            return;
        }
        debounceTimer = setTimeout(() => searchPlaces(value), 250);
    });

    searchInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && autocompleteList.firstChild) {
            autocompleteList.firstChild.click();
            e.preventDefault();
        }
    });

    searchBtn.addEventListener('click', function() {
        if (autocompleteList.firstChild) {
            autocompleteList.firstChild.click();
        } else {
            searchPlaces(searchInput.value);
        }
    });

    document.addEventListener('click', function(e) {
        if (!autocompleteList.contains(e.target) && e.target !== searchInput) {
            autocompleteList.innerHTML = '';
        }
    });

    // =============================
    // Botones Switch 2D / 3D
    // =============================
    document.getElementById('switch-2d-btn').addEventListener('click', function() {
        if (is3D) {
            is3D = false;
            lastCenter = map.getCenter().toArray();
            lastZoom = map.getZoom();
            lastPitch = map.getPitch();
            lastBearing = map.getBearing();
            createMap({
                style: currentStyle2D,
                pitch: lastPitch, // Mantiene el pitch, así puedes rotar en 2D
                bearing: lastBearing,
                zoom: lastZoom,
                center: lastCenter
            });
            this.classList.add('active');
            document.getElementById('switch-3d-btn').classList.remove('active');
        }
    });
    document.getElementById('switch-3d-btn').addEventListener('click', function() {
        if (!is3D) {
            is3D = true;
            lastCenter = map.getCenter().toArray();
            lastZoom = map.getZoom();
            lastPitch = 65;
            lastBearing = -30;
            createMap({
                style: currentStyle3D,
                pitch: 65,
                bearing: -30,
                zoom: lastZoom,
                center: lastCenter
            });
            this.classList.add('active');
            document.getElementById('switch-2d-btn').classList.remove('active');
        }
    });
});

function createCustomMarker() {
    const markerDiv = document.createElement('div');
    markerDiv.style.fontSize = '28px';
    markerDiv.style.color = '#ff006e';
    markerDiv.style.cursor = 'pointer';
    markerDiv.style.filter = 'drop-shadow(0 4px 12px #3336)';
    markerDiv.classList.add('fas', 'fa-map-marker-alt', 'animate__animated', 'animate__bounceInDown');
    return markerDiv;
}
</script>
@endsection
