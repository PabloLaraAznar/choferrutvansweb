@extends('adminlte::page')

@section('title', 'Localidades')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center p-3 mb-3" style="background: linear-gradient(135deg, #ff6600, #e55a00); color: white; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <div>
            <h1 class="mb-1" style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 1.8rem;">
                <i class="fas fa-map-marker-alt me-2"></i> Gestión de Localidades
            </h1>
            <p class="mb-0" style="opacity: 0.9; font-size: 0.95rem;">Administra las ubicaciones geográficas del sistema Rutvans</p>
        </div>
    </div>
@endsection

@section('content')
<div class="card shadow-sm" style="border: none; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1) !important;">
    <div class="card-header d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #ff6600, #e55a00); color: white; border-radius: 12px 12px 0 0; padding: 1.5rem;">
        <h3 class="mb-0" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
            <i class="fas fa-globe-americas me-2"></i> Gestión de Localidades
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
        
        <!-- Lista de Localidades Creadas -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card shadow-sm" style="border: none; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1) !important;">
                    <div class="card-header" style="background: linear-gradient(135deg, #ff6600, #e55a00); color: white; border-radius: 12px 12px 0 0; padding: 1.5rem;">
                        <h5 class="mb-0" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                            <i class="fas fa-list-ul me-2"></i> Localidades Registradas ({{ $localidadesCount }})
                        </h5>
                    </div>
                    <div class="card-body" style="padding: 2rem;">
                        @if($localidades->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover" style="border-radius: 8px; overflow: hidden;">
                                    <thead style="background: linear-gradient(135deg, #6c757d, #495057); color: white;">
                                        <tr>
                                            <th style="width: 10%">#</th>
                                            <th><i class="fas fa-map-marker-alt me-1"></i> Localidad</th>
                                            <th><i class="fas fa-road me-1"></i> Calle</th>
                                            <th><i class="fas fa-building me-1"></i> Municipio</th>
                                            <th><i class="fas fa-map-pin me-1"></i> Estado</th>
                                            <th><i class="fas fa-envelope me-1"></i> C.P.</th>
                                            <th style="width: 15%" class="text-center"><i class="fas fa-cog me-1"></i> Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($localidades as $index => $localidad)
                                            <tr>
                                                <td style="font-weight: 500;">{{ $index + 1 }}</td>
                                                <td>
                                                    <strong style="color: #333;">{{ $localidad->locality }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $localidad->locality_type }}</small>
                                                </td>
                                                <td>{{ $localidad->street ?: 'N/A' }}</td>
                                                <td style="font-weight: 500; color: #ff6600;">{{ $localidad->municipality }}</td>
                                                <td>{{ $localidad->state }}</td>
                                                <td>{{ $localidad->postal_code ?: 'N/A' }}</td>
                                                <td class="text-center">
                                                    <div class="btn-group" role="group">
                                                        <button type="button" class="btn btn-warning btn-sm zoom-to-location" 
                                                                data-lng="{{ $localidad->longitude }}" 
                                                                data-lat="{{ $localidad->latitude }}"
                                                                title="Ver en mapa">
                                                            <i class="fas fa-search-plus"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-danger btn-sm" 
                                                                onclick="confirmDelete({{ $localidad->id }})"
                                                                title="Eliminar">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center">
                                <div class="py-4">
                                    <i class="fas fa-map-marker-alt fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">No hay localidades registradas</h5>
                                    <p class="text-muted">Haz clic en el mapa para agregar tu primera localidad.</p>
                                </div>
                            </div>
                        @endif
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

        // Mostrar indicador de carga
        const loadingToast = Swal.fire({
            title: 'Consultando ubicación...',
            text: 'Obteniendo datos precisos con Geoapify',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        try {
            // Usar Geoapify como API principal
            const results = await getLocationFromMultipleAPIs(coordinates.lng, coordinates.lat);
            const bestResult = getBestLocationResult(results);
            
            loadingToast.close();
            
            if (bestResult) {
                // Llenar formulario con los mejores datos
                document.getElementById('longitude').value = coordinates.lng;
                document.getElementById('latitude').value = coordinates.lat;
                document.getElementById('locality').value = bestResult.locality;
                document.getElementById('street').value = bestResult.street;
                document.getElementById('postal_code').value = bestResult.postal_code;
                document.getElementById('municipality').value = bestResult.municipality;
                document.getElementById('state').value = bestResult.state;
                document.getElementById('country').value = bestResult.country;
                document.getElementById('locality_type').value = bestResult.locality_type;
                
                // Actualizar el campo de display
                const displayText = `${bestResult.locality}${bestResult.municipality ? ', ' + bestResult.municipality : ''}${bestResult.state ? ', ' + bestResult.state : ''}${bestResult.country ? ', ' + bestResult.country : ''}`;
                document.getElementById('locality_display').value = displayText;
                
                // Mostrar API usada
                const apiUsed = results[0]?.api || 'Desconocida';
                Swal.fire({
                    icon: 'success',
                    title: 'Ubicación encontrada',
                    html: `
                        <div style="text-align: left;">
                            <strong>📍 Localidad:</strong> ${bestResult.locality}<br>
                            <strong>🏛️ Municipio:</strong> ${bestResult.municipality}<br>
                            <strong>📮 Código Postal:</strong> ${bestResult.postal_code}<br>
                            <strong>🗺️ Estado:</strong> ${bestResult.state}<br>
                            <small style="color: #666;">API: ${apiUsed}</small>
                        </div>
                    `,
                    timer: 4000,
                    timerProgressBar: true,
                    toast: true,
                    position: 'bottom-end',
                    showConfirmButton: false
                });
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Ubicación no encontrada',
                    text: 'No se pudo obtener información de esta ubicación',
                    timer: 2000,
                    toast: true,
                    position: 'bottom-end',
                    showConfirmButton: false
                });
            }
        } catch (error) {
            loadingToast.close();
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Hubo un problema al obtener la ubicación',
                timer: 2000,
                toast: true,
                position: 'bottom-end',
                showConfirmButton: false
            });
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

// Base de datos de comisarías de Yucatán para mejor detección
const yucatanComisarias = {
    'Kanachen': 'Maxcanú',
    'Kanachén': 'Maxcanú', // Variante con acento
    'Chocholá': 'Kopomá', 
    'Samahil': 'Umán',
    'Texán de Palomeque': 'Hunucmá',
    'Santa Rosa': 'Maxcanú',
    'Xcunyá': 'Umán',
    'Sinanché': 'Hunucmá',
    'Tetiz': 'Tetiz', // Es municipio
    'Kinchil': 'Kinchil', // Es municipio
    'Kopomá': 'Kopomá', // Es municipio
    'Maxcanú': 'Maxcanú', // Es municipio
    'Umán': 'Umán', // Es municipio
    'Hunucmá': 'Hunucmá', // Es municipio
    // Agregar más comisarías conocidas
    'Nohpat': 'Maxcanú',
    'Xanila': 'Maxcanú',
    'San Antonio Hool': 'Kopomá',
    'Mucuyché': 'Maxcanú',
    'Chablekal': 'Mérida',
    'Dzityá': 'Mérida',
    'Komchén': 'Mérida',
    'Cholul': 'Mérida'
};

function getMunicipalityFromComisaria(localityName) {
    if (!localityName) return null;
    
    // Normalizar el nombre (quitar acentos y convertir a minúsculas)
    const normalize = (str) => str.toLowerCase()
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '');
    
    const normalizedLocality = normalize(localityName);
    
    // Verificar si la localidad es una comisaría conocida
    for (const [comisaria, municipio] of Object.entries(yucatanComisarias)) {
        const normalizedComisaria = normalize(comisaria);
        
        // Buscar coincidencia exacta o parcial
        if (normalizedLocality.includes(normalizedComisaria) || 
            normalizedComisaria.includes(normalizedLocality)) {
            return municipio;
        }
    }
    return null;
}

// Sistema mejorado con Geoapify API - MUY PRECISA PARA MÉXICO
async function getLocationFromGeoapify(lng, lat) {
    // REEMPLAZA por tu API key real de Geoapify (obtén una gratis en geoapify.com)
    const apiKey = '1b268500dc844f61a822f0663bb76584'; // 👈 CLAVE TEMPORAL DE PRUEBA
    
    try {
        const url = `https://api.geoapify.com/v1/geocode/reverse?lat=${lat}&lon=${lng}&apiKey=${apiKey}&format=json`;
        const response = await fetch(url);
        const data = await response.json();
        
        if (data.results && data.results.length > 0) {
            const result = data.results[0];
            
            return {
                api: 'Geoapify',
                locality: result.city || result.village || result.town || result.locality || '',
                street: result.street || result.address_line1 || '',
                municipality: result.county || result.municipality || result.city || '',
                state: result.state || result.region || '',
                country: result.country || 'México',
                postal_code: result.postcode || '',
                confidence: result.rank?.confidence || 0.8,
                formatted: result.formatted || ''
            };
        }
    } catch (error) {
        // Error silencioso
    }
    
    return null;
}

// Función simplificada que usa Geoapify como principal
async function getLocationFromMultipleAPIs(lng, lat) {
    const results = [];
    
    // 1. Intentar con Geoapify primero (más precisa)
    const geoapifyResult = await getLocationFromGeoapify(lng, lat);
    if (geoapifyResult) {
        results.push(geoapifyResult);
    }
    
    // 2. Si Geoapify falla, usar Nominatim como backup
    if (results.length === 0) {
        try {
            const nominatimUrl = `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&addressdetails=1&accept-language=es`;
            const nominatimResponse = await fetch(nominatimUrl);
            const nominatimData = await nominatimResponse.json();
            
            if (nominatimData && nominatimData.address) {
                results.push({
                    api: 'Nominatim (Backup)',
                    locality: nominatimData.address.village || nominatimData.address.town || nominatimData.address.city || nominatimData.address.locality || '',
                    street: nominatimData.address.road || nominatimData.address.street || '',
                    municipality: nominatimData.address.municipality || nominatimData.address.county || nominatimData.address.city || '',
                    state: nominatimData.address.state || '',
                    country: nominatimData.address.country || 'México',
                    postal_code: nominatimData.address.postcode || '',
                    confidence: nominatimData.importance || 0.5
                });
            }
        } catch (error) {
            // Error silencioso
        }
    }
    
    // 3. Si todo falla, usar Mapbox como último recurso
    if (results.length === 0) {
        try {
            const mapboxUrl = `https://api.mapbox.com/geocoding/v5/mapbox.places/${lng},${lat}.json?access_token=${mapboxgl.accessToken}`;
            const mapboxResponse = await fetch(mapboxUrl);
            const mapboxData = await mapboxResponse.json();
            
            if (mapboxData.features && mapboxData.features.length > 0) {
                const context = mapboxData.features[0].context || [];
                const placeFeature = mapboxData.features.find(f => f.place_type?.includes('place')) || 
                                   context.find(c => c.id.includes('place'));
                const addressFeature = mapboxData.features.find(f => f.place_type?.includes('address'));
                
                results.push({
                    api: 'Mapbox (Último recurso)',
                    locality: addressFeature?.text || placeFeature?.text || mapboxData.features[0].text || '',
                    street: addressFeature?.text || '',
                    municipality: placeFeature?.text || '',
                    state: context.find(c => c.id.includes('region'))?.text || '',
                    country: context.find(c => c.id.includes('country'))?.text || 'México',
                    postal_code: context.find(c => c.id.includes('postcode'))?.text || '',
                    confidence: 0.6
                });
            }
        } catch (error) {
            // Error silencioso
        }
    }

    return results;
}

// Función para elegir el mejor resultado combinando múltiples APIs
function getBestLocationResult(results) {
    if (results.length === 0) return null;
    
    // Combinar información de todas las APIs
    const combined = {
        locality: '',
        street: '',
        municipality: '',
        state: '',
        country: 'México',
        postal_code: '',
        locality_type: 'address'
    };
    
    // Priorizar resultados por confianza y completitud
    const sortedResults = results.sort((a, b) => {
        const scoreA = (a.municipality ? 2 : 0) + (a.locality ? 1 : 0) + (a.postal_code ? 1 : 0);
        const scoreB = (b.municipality ? 2 : 0) + (b.locality ? 1 : 0) + (b.postal_code ? 1 : 0);
        return scoreB - scoreA;
    });
    
    // Usar el mejor resultado como base
    const best = sortedResults[0];
    combined.locality = best.locality;
    combined.street = best.street;
    combined.municipality = best.municipality;
    combined.state = best.state;
    combined.country = best.country;
    combined.postal_code = best.postal_code;
    
    // Completar campos faltantes con otros resultados
    for (const result of sortedResults.slice(1)) {
        if (!combined.municipality && result.municipality) combined.municipality = result.municipality;
        if (!combined.postal_code && result.postal_code) combined.postal_code = result.postal_code;
        if (!combined.state && result.state) combined.state = result.state;
        if (!combined.locality && result.locality) combined.locality = result.locality;
    }
    
    // Aplicar corrección de comisarías conocidas
    const municipalityFromComisaria = getMunicipalityFromComisaria(combined.locality);
    if (municipalityFromComisaria) {
        combined.municipality = municipalityFromComisaria;
    }
    
    return combined;
}
function getRealLocality(features) {
    // Primero buscar en features principales por place_type
    for (const feat of features) {
        if (feat.place_type && feat.place_type.includes('place')) {
            return feat.text;
        }
    }
    
    // Luego buscar por ID que empiece con place.
    for (const feat of features) {
        if (feat.id && feat.id.startsWith('place.')) {
            return feat.text;
        }
        if (feat.context) {
            const ctx = feat.context.find(c => c.id && c.id.startsWith('place.'));
            if (ctx) return ctx.text;
        }
    }
    
    // Finalmente buscar por tipos específicos
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
                    
                    // Buscar municipio - primero en features principales, luego en context
                    let municipalityFeature = data.features.find(f => 
                        f.place_type && f.place_type.includes('place')
                    );
                    if (!municipalityFeature) {
                        municipalityFeature = context.find(c => c.id.includes('place'));
                    }
                    if (municipalityFeature) updatedMunicipality = municipalityFeature.text;
                    
                    const stateFeature = context.find(c => c.id.includes('region'));
                    if (stateFeature) updatedState = stateFeature.text;
                    const countryFeature = context.find(c => c.id.includes('country'));
                    if (countryFeature) updatedCountry = countryFeature.text;
                    updatedLocalityType = data.features[0].place_type ? data.features[0].place_type.join(', ') : location.locality_type;
                }
            } catch (error) {
                // Error silencioso
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

    // Funcionalidad para botones "Ver en mapa"
    document.querySelectorAll('.zoom-to-location').forEach(button => {
        button.addEventListener('click', function() {
            const lng = parseFloat(this.dataset.lng);
            const lat = parseFloat(this.dataset.lat);
            
            // Volar a la ubicación
            flyToLocation(map, lng, lat);
            
            // Resaltar temporalmente el marcador
            const targetMarker = savedMarkers.find(({location}) => 
                Math.abs(location.longitude - lng) < 0.0001 && 
                Math.abs(location.latitude - lat) < 0.0001
            );
            
            if (targetMarker) {
                // Abrir popup automáticamente
                setTimeout(() => {
                    targetMarker.marker.getPopup().addTo(map);
                }, 500);
                
                // Efecto visual temporal
                const element = targetMarker.marker.getElement();
                element.style.transform = 'scale(1.3)';
                element.style.transition = 'transform 0.3s ease';
                
                setTimeout(() => {
                    element.style.transform = 'scale(1)';
                }, 1000);
            }
        });
    });
});

// Función para confirmar eliminación igual que en roles
function confirmDelete(localidadId) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "¿Deseas eliminar esta localidad? Esta acción no se puede deshacer.",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Crear formulario tradicional para DELETE
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/localidades/${localidadId}`;

            // Token CSRF
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);

            // Método DELETE
            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';
            form.appendChild(methodField);

            // Agregar al DOM
            document.body.appendChild(form);

            // Enviar formulario
            form.submit();
        }
    });
}

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
