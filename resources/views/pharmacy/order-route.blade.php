<!DOCTYPE html>
<html class="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Route for Order #{{ $pedido->folio_pedido }} - Te Acerco Salud</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#137fec",
                        "secondary": "#2ECC71",
                        "accent": "#F39C12",
                        "background-light": "#f6f7f8",
                        "background-dark": "#101922",
                        "body-text": "#111418",
                        "body-text-dark": "#f0f2f4",
                        "card-light": "#ffffff",
                        "card-dark": "#1a2734",
                    },
                    fontFamily: {
                        "display": ["Manrope", "sans-serif"]
                    },
                },
            },
        }
    </script>
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24 }
        #map { height: 600px; width: 100%; z-index: 1; }
    </style>
</head>

<body class="font-display bg-background-light dark:bg-background-dark text-body-text dark:text-body-text-dark">
    <div class="relative flex h-auto min-h-screen w-full flex-col group/design-root overflow-x-hidden">
        <div class="layout-container flex h-full grow flex-col">

            @include('components.topbar', ['user' => auth()->user(), 'type' => 'pharmacy'])

            <div class="flex flex-1">
                @include('components.sidebar', [
                    'user' => auth()->user(),
                    'type' => 'pharmacy',
                    'currentRoute' => 'pharmacy.orders',
                ])

                <main class="flex-1 p-4 sm:p-6 lg:p-10">
                    <div class="mx-auto max-w-7xl space-y-8">

                        <!-- Header -->
                        <div class="flex flex-wrap items-center justify-between gap-4">
                            <div>
                                <h1 class="text-3xl font-black leading-tight tracking-[-0.033em]">
                                    Route for Order #{{ $pedido->folio_pedido }}
                                </h1>
                                <p class="text-neutral-500 dark:text-neutral-400">
                                    Status: <span class="font-bold uppercase">{{ $pedido->estatus }}</span>
                                </p>
                            </div>
                            <a href="{{ route('pharmacy.orders') }}" class="flex items-center gap-2 text-primary hover:underline font-bold">
                                <span class="material-symbols-outlined">arrow_back</span>
                                Back to Orders
                            </a>
                        </div>

                        <!-- Map Container -->
                        <div class="bg-card-light dark:bg-card-dark rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                            <div id="map" class="rounded-lg"></div>
                        </div>

                        <!-- Route Info -->
                        @if(!$pedido->route_geometry)
                            <div class="p-4 bg-yellow-100 text-yellow-800 rounded-lg">
                                <p class="font-bold">No route data available for this order.</p>
                                <p class="text-sm">This order might have been created before route tracking was enabled or no route was found.</p>
                            </div>
                        @endif

                    </div>
                </main>
            </div>
        </div>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var map = L.map('map').setView([19.4326, -99.1332], 13); // Default to Mexico City

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            var routeGeometry = "{{ $pedido->route_geometry }}";

            if (routeGeometry) {
                try {
                    // Decode polyline
                    var latlngs = decodePolyline(routeGeometry);
                    
                    if (latlngs.length > 0) {
                        var polyline = L.polyline(latlngs, {color: 'blue', weight: 5}).addTo(map);
                        
                        // Add markers for start and end
                        L.marker(latlngs[0]).addTo(map).bindPopup("Start (User)");
                        L.marker(latlngs[latlngs.length - 1]).addTo(map).bindPopup("End (Pharmacy)");
                        
                        // Fit bounds
                        map.fitBounds(polyline.getBounds(), {padding: [50, 50]});
                    }
                } catch (e) {
                    console.error("Error decoding route:", e);
                }
            }
        });

        // Simple decode function for OSRM/Google encoded polyline
        function decodePolyline(str, precision) {
            var index = 0,
                lat = 0,
                lng = 0,
                coordinates = [],
                shift = 0,
                result = 0,
                byte = null,
                latitude_change,
                longitude_change,
                factor = Math.pow(10, precision || 5);

            while (index < str.length) {
                byte = null;
                shift = 0;
                result = 0;

                do {
                    byte = str.charCodeAt(index++) - 63;
                    result |= (byte & 0x1f) << shift;
                    shift += 5;
                } while (byte >= 0x20);

                latitude_change = ((result & 1) ? ~(result >> 1) : (result >> 1));

                shift = result = 0;

                do {
                    byte = str.charCodeAt(index++) - 63;
                    result |= (byte & 0x1f) << shift;
                    shift += 5;
                } while (byte >= 0x20);

                longitude_change = ((result & 1) ? ~(result >> 1) : (result >> 1));

                lat += latitude_change;
                lng += longitude_change;

                coordinates.push([lat / factor, lng / factor]);
            }

            return coordinates;
        }
    </script>
</body>
</html>
