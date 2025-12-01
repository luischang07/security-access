@extends('layouts.pharmacy')

@section('title', __('pharmacy.orders.route.page_title'))

@push('styles')
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
@endpush

@section('pharmacy-content')
    <div class="max-w-6xl mx-auto p-6 space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-body-text dark:text-body-text-dark">
                    {{ __('pharmacy.orders.route.title') }}
                </h1>
                <p class="text-sm text-neutral-text dark:text-neutral-text-dark mt-1">
                    {{ __('pharmacy.orders.order_folio') }}: #{{ $pedido->folio_pedido }}
                </p>
            </div>
            <a href="{{ route('pharmacy.orders') }}"
                class="px-4 py-2 rounded-lg bg-card-light dark:bg-card-dark border border-border-light dark:border-border-dark text-body-text dark:text-body-text-dark hover:bg-background-light dark:hover:bg-background-dark transition flex items-center gap-2">
                <span class="material-symbols-outlined text-base">arrow_back</span>
                {{ __('pharmacy.orders.route.back_to_orders') }}
            </a>
        </div>

        <!-- Order Summary Card -->
        <div class="rounded-xl border border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark p-6">
            <h2 class="text-xl font-bold text-body-text dark:text-body-text-dark mb-4">
                {{ __('pharmacy.orders.route.order_summary') }}
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <p class="text-sm text-neutral-text dark:text-neutral-text-dark">{{ __('pharmacy.orders.order_date') }}
                    </p>
                    <p class="font-medium text-body-text dark:text-body-text-dark">
                        {{ $pedido->fecha_pedido ? \Carbon\Carbon::parse($pedido->fecha_pedido)->translatedFormat('d M Y H:i') : 'N/A' }}
                    </p>
                </div>
                <div>
                    <p class="text-sm text-neutral-text dark:text-neutral-text-dark">
                        {{ __('pharmacy.orders.route.collection_date') }}</p>
                    <p class="font-medium text-body-text dark:text-body-text-dark">
                        {{ $pedido->fecha_recoleccion ? \Carbon\Carbon::parse($pedido->fecha_recoleccion)->translatedFormat('d M Y') : 'N/A' }}
                    </p>
                </div>
                <div>
                    <p class="text-sm text-neutral-text dark:text-neutral-text-dark">{{ __('pharmacy.orders.status_column') }}
                    </p>
                    <span
                        class="inline-flex items-center rounded-full 
                        @if (strtolower($pedido->estatus) === 'confirmado') bg-success
                        @elseif(strtolower($pedido->estatus) === 'surtido') bg-primary
                        @elseif(strtolower($pedido->estatus) === 'completado') bg-secondary
                        @else bg-neutral-text @endif
                        px-2.5 py-0.5 text-xs font-medium text-white">
                        {{ ucfirst($pedido->estatus) }}
                    </span>
                </div>
            </div>
        </div>

        @php
            $rutaOrdenada = $pedido->rutaRecoleccion->sortBy('orden_recoleccion');
            $totalParadas = $rutaOrdenada->count();
        @endphp

        <!-- Interactive Map -->
        @if ($totalParadas > 0)
            <div class="rounded-xl border border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark p-6">
                <h2 class="text-xl font-bold text-body-text dark:text-body-text-dark mb-4">
                    {{ __('pharmacy.orders.route.map_title') }}
                </h2>
                <div id="route-map" class="w-full h-96 rounded-lg overflow-hidden"></div>
            </div>
        @endif

        <!-- Route Details -->
        <div class="rounded-xl border border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-body-text dark:text-body-text-dark">
                    {{ __('pharmacy.orders.route.route_details') }}
                </h2>
                <div class="px-3 py-1.5 rounded-lg bg-primary/10 text-primary text-sm font-bold flex items-center gap-2">
                    <span class="material-symbols-outlined text-base">location_on</span>
                    {{ $totalParadas }}
                    {{ $totalParadas === 1 ? __('pharmacy.orders.route.stop') : __('pharmacy.orders.route.stops') }}
                </div>
            </div>

            @if ($totalParadas > 0)
                <div class="space-y-4">
                    @foreach ($rutaOrdenada as $index => $ruta)
                        @php
                            $sucursal = $ruta->sucursal;
                            $medicamentosEnSucursal = collect();
                            foreach ($pedido->lineasPedidos as $linea) {
                                foreach ($linea->detalles as $detalle) {
                                    if (
                                        $detalle->cadena_id === $sucursal->cadena_id &&
                                        $detalle->sucursal_id === $sucursal->sucursal_id
                                    ) {
                                        $medicamentosEnSucursal->push([
                                            'nombre' => $linea->medicamento->nombre,
                                            'cantidad' => $detalle->cantidad_surtida,
                                            'precio' => $detalle->precio_unitario,
                                        ]);
                                    }
                                }
                            }
                        @endphp

                        <div
                            class="border border-border-light dark:border-border-dark rounded-lg p-5 hover:bg-background-light dark:hover:bg-background-dark transition-colors">
                            <div class="flex items-start gap-4">
                                <div
                                    class="flex-shrink-0 w-12 h-12 rounded-full bg-primary flex items-center justify-center">
                                    <span class="text-white text-lg font-bold">{{ $ruta->orden_recoleccion }}</span>
                                </div>

                                <div class="flex-1">
                                    <div class="flex items-start justify-between mb-2">
                                        <div>
                                            <h3 class="text-lg font-bold text-body-text dark:text-body-text-dark">
                                                {{ $sucursal->nombre }}</h3>
                                            <p class="text-sm text-neutral-text dark:text-neutral-text-dark">
                                                {{ $sucursal->cadena->nombre ?? 'N/A' }}</p>
                                        </div>
                                    </div>

                                    <div class="flex items-start gap-2 mb-3">
                                        <span
                                            class="material-symbols-outlined text-neutral-text dark:text-neutral-text-dark text-lg mt-0.5">location_on</span>
                                        <div class="text-sm text-neutral-text dark:text-neutral-text-dark">
                                            <p>{{ $sucursal->calle }} {{ $sucursal->numero_ext }}@if ($sucursal->numero_int)
                                                    Int. {{ $sucursal->numero_int }}
                                                @endif
                                            </p>
                                            <p>{{ $sucursal->colonia }}, {{ $sucursal->ciudad }}</p>
                                        </div>
                                    </div>

                                    @if ($sucursal->contacto)
                                        <div class="flex items-center gap-2 mb-3">
                                            <span
                                                class="material-symbols-outlined text-neutral-text dark:text-neutral-text-dark text-lg">phone</span>
                                            <p class="text-sm text-neutral-text dark:text-neutral-text-dark">
                                                {{ $sucursal->contacto }}</p>
                                        </div>
                                    @endif

                                    @if ($medicamentosEnSucursal->isNotEmpty())
                                        <div class="mt-4 pt-4 border-t border-border-light dark:border-border-dark">
                                            <h4 class="text-sm font-bold text-body-text dark:text-body-text-dark mb-2">
                                                {{ __('pharmacy.orders.route.medications_to_collect') }}:</h4>
                                            <div class="space-y-1">
                                                @foreach ($medicamentosEnSucursal as $med)
                                                    <div class="flex items-center justify-between text-sm">
                                                        <span
                                                            class="text-neutral-text dark:text-neutral-text-dark">{{ $med['nombre'] }}</span>
                                                        <div class="flex items-center gap-4">
                                                            <span
                                                                class="text-body-text dark:text-body-text-dark font-medium">{{ __('pharmacy.orders.quantity') }}:
                                                                {{ $med['cantidad'] }}</span>
                                                            <span
                                                                class="text-body-text dark:text-body-text-dark font-medium">${{ number_format($med['precio'], 2) }}</span>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if (!$loop->last)
                            <div class="flex justify-center"><span
                                    class="material-symbols-outlined text-3xl text-neutral-text dark:text-neutral-text-dark">arrow_downward</span>
                            </div>
                        @endif
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-neutral-text dark:text-neutral-text-dark">
                    <span class="material-symbols-outlined text-5xl mb-2">route</span>
                    <p>{{ __('pharmacy.orders.route.no_route_info') }}</p>
                </div>
            @endif
        </div>

        <!-- Total Summary -->
        <div class="rounded-xl border border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark p-6">
            <h2 class="text-xl font-bold text-body-text dark:text-body-text-dark mb-4">
                {{ __('pharmacy.orders.route.cost_summary') }}</h2>
            <div class="flex justify-between items-center">
                <span
                    class="text-lg text-neutral-text dark:text-neutral-text-dark">{{ __('pharmacy.orders.route.total_order') }}:</span>
                <span class="text-2xl font-bold text-primary">${{ number_format($pedido->costo_total ?? 0, 2) }}</span>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    @if ($totalParadas > 0)
        @php
            $branchesData = $rutaOrdenada
                ->map(function ($ruta) {
                    return [
                        'lat' => floatval($ruta->sucursal->latitud),
                        'lng' => floatval($ruta->sucursal->longitud),
                        'nombre' => $ruta->sucursal->nombre,
                        'orden' => $ruta->orden_recoleccion,
                        'direccion' =>
                            $ruta->sucursal->calle .
                            ' ' .
                            $ruta->sucursal->numero_ext .
                            ', ' .
                            $ruta->sucursal->colonia,
                    ];
                })
                ->values();
        @endphp

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const map = L.map('route-map').setView([19.4326, -99.1332], 13);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                    maxZoom: 19
                }).addTo(map);

                const branches = @json($branchesData);
                const markers = [];

                branches.forEach((branch) => {
                    const divIcon = L.divIcon({
                        className: 'custom-marker',
                        html: '<div style="width:36px;height:36px;background-color:#137fec;border:3px solid white;border-radius:50%;display:flex;align-items:center;justify-content:center;color:white;font-weight:bold;font-size:16px;box-shadow:0 2px 8px rgba(0,0,0,0.3);">' +
                            branch.orden + '</div>',
                        iconSize: [36, 36],
                        iconAnchor: [18, 18],
                    });

                    const marker = L.marker([branch.lat, branch.lng], {
                        icon: divIcon
                    }).addTo(map);
                    marker.bindPopup(
                        '<div style="min-width:200px;"><h3 style="font-weight:bold;margin-bottom:4px;">' +
                        branch.nombre +
                        '</h3><p style="font-size:12px;color:#617589;margin:0;">' + branch.direccion +
                        '</p><p style="font-size:11px;color:#137fec;margin-top:4px;">Parada #' + branch
                        .orden + '</p></div>');
                    markers.push(marker);
                });

                const routeGeometry = @json($pedido->route_geometry);

                if (routeGeometry) {
                    try {
                        const decoded = decodePolyline(routeGeometry);
                        L.polyline(decoded, {
                            color: '#137fec',
                            weight: 4,
                            opacity: 0.7,
                            smoothFactor: 1
                        }).addTo(map);
                    } catch (e) {
                        console.error('Error decoding polyline:', e);
                    }
                }

                if (markers.length > 0) {
                    const group = L.featureGroup(markers);
                    map.fitBounds(group.getBounds().pad(0.1));
                }
            });

            function decodePolyline(encoded) {
                const coordinates = [];
                let index = 0,
                    lat = 0,
                    lng = 0;

                while (index < encoded.length) {
                    let b, shift = 0,
                        result = 0;
                    do {
                        b = encoded.charCodeAt(index++) - 63;
                        result |= (b & 0x1f) << shift;
                        shift += 5;
                    } while (b >= 0x20);
                    const dlat = ((result & 1) ? ~(result >> 1) : (result >> 1));
                    lat += dlat;

                    shift = 0;
                    result = 0;
                    do {
                        b = encoded.charCodeAt(index++) - 63;
                        result |= (b & 0x1f) << shift;
                        shift += 5;
                    } while (b >= 0x20);
                    const dlng = ((result & 1) ? ~(result >> 1) : (result >> 1));
                    lng += dlng;

                    coordinates.push([lat / 1e5, lng / 1e5]);
                }
                return coordinates;
            }
        </script>
    @endif
@endpush
