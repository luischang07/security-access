<!DOCTYPE html>
<html class="light" lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ruta de Recolección - Te Acerco Salud</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    
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
                        "success": "#28A745",
                        "warning": "#FFC107",
                        "danger": "#DC3545",
                        "neutral-text": "#617589",
                        "neutral-text-dark": "#90a4b8",
                        "body-text": "#111418",
                        "body-text-dark": "#f0f2f4",
                        "border-light": "#f0f2f4",
                        "border-dark": "#2a3b4c",
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
    </style>
</head>

<body class="font-display bg-background-light dark:bg-background-dark text-body-text dark:text-body-text-dark">
    <div class="relative flex h-screen w-full flex-col overflow-hidden">
        <div class="layout-container flex h-full grow flex-col">

            <?php echo $__env->make('components.topbar', ['user' => auth()->user(), 'type' => 'pharmacy'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <div class="flex flex-1 overflow-hidden">
                <?php echo $__env->make('components.sidebar', [
                    'user' => auth()->user(),
                    'type' => 'pharmacy',
                    'currentRoute' => 'pharmacy.orders',
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                <div class="flex-1 overflow-y-auto bg-background-light dark:bg-background-dark">
                    <div class="max-w-6xl mx-auto p-6 space-y-6">
                        <!-- Header -->
                        <div class="flex items-center justify-between">
                            <div>
                                <h1 class="text-3xl font-bold text-body-text dark:text-body-text-dark">
                                    Ruta de Recolección
                                </h1>
                                <p class="text-sm text-neutral-text dark:text-neutral-text-dark mt-1">
                                    Folio del Pedido: #<?php echo e($pedido->folio_pedido); ?>

                                </p>
                            </div>
                            <a href="<?php echo e(route('pharmacy.orders')); ?>"
                                class="px-4 py-2 rounded-lg bg-card-light dark:bg-card-dark border border-border-light dark:border-border-dark text-body-text dark:text-body-text-dark hover:bg-background-light dark:hover:bg-background-dark transition flex items-center gap-2">
                                <span class="material-symbols-outlined text-base">arrow_back</span>
                                Volver a Pedidos
                            </a>
                        </div>

                        <!-- Order Summary Card -->
                        <div class="rounded-xl border border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark p-6">
                            <h2 class="text-xl font-bold text-body-text dark:text-body-text-dark mb-4">
                                Resumen del Pedido
                            </h2>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <p class="text-sm text-neutral-text dark:text-neutral-text-dark">Fecha del Pedido</p>
                                    <p class="font-medium text-body-text dark:text-body-text-dark">
                                        <?php echo e($pedido->fecha_pedido ? \Carbon\Carbon::parse($pedido->fecha_pedido)->translatedFormat('d M Y H:i') : 'N/A'); ?>

                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-neutral-text dark:text-neutral-text-dark">Fecha de Recolección</p>
                                    <p class="font-medium text-body-text dark:text-body-text-dark">
                                        <?php echo e($pedido->fecha_recoleccion ? \Carbon\Carbon::parse($pedido->fecha_recoleccion)->translatedFormat('d M Y') : 'N/A'); ?>

                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-neutral-text dark:text-neutral-text-dark">Estatus</p>
                                    <span
                                        class="inline-flex items-center rounded-full 
                                        <?php if(strtolower($pedido->estatus) === 'confirmado'): ?> bg-success
                                        <?php elseif(strtolower($pedido->estatus) === 'surtido'): ?> bg-primary
                                        <?php elseif(strtolower($pedido->estatus) === 'completado'): ?> bg-secondary
                                        <?php else: ?> bg-neutral-text <?php endif; ?>
                                        px-2.5 py-0.5 text-xs font-medium text-white">
                                        <?php echo e(ucfirst($pedido->estatus)); ?>

                                    </span>
                                </div>
                            </div>
                        </div>

                        <?php
                            $rutaOrdenada = $pedido->rutaRecoleccion->sortBy('orden_recoleccion');
                            $totalParadas = $rutaOrdenada->count();
                        ?>

                        <!-- Interactive Map -->
                        <?php if($totalParadas > 0): ?>
                            <div class="rounded-xl border border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark p-6">
                                <h2 class="text-xl font-bold text-body-text dark:text-body-text-dark mb-4">
                                    Mapa de la Ruta
                                </h2>
                                <div id="route-map" class="w-full h-96 rounded-lg overflow-hidden"></div>
                            </div>
                        <?php endif; ?>

                        <!-- Route Details -->
                        <div class="rounded-xl border border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h2 class="text-xl font-bold text-body-text dark:text-body-text-dark">
                                    Detalles de la Ruta
                                </h2>
                                <div class="px-3 py-1.5 rounded-lg bg-primary/10 text-primary text-sm font-bold flex items-center gap-2">
                                    <span class="material-symbols-outlined text-base">location_on</span>
                                    <?php echo e($totalParadas); ?> <?php echo e($totalParadas === 1 ? 'Parada' : 'Paradas'); ?>

                                </div>
                            </div>

                            <?php if($totalParadas > 0): ?>
                                <div class="space-y-4">
                                    <?php $__currentLoopData = $rutaOrdenada; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $ruta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $sucursal = $ruta->sucursal;
                                            $medicamentosEnSucursal = collect();
                                            foreach ($pedido->lineasPedidos as $linea) {
                                                foreach ($linea->detalles as $detalle) {
                                                    if ($detalle->cadena_id === $sucursal->cadena_id && $detalle->sucursal_id === $sucursal->sucursal_id) {
                                                        $medicamentosEnSucursal->push([
                                                            'nombre' => $linea->medicamento->nombre,
                                                            'cantidad' => $detalle->cantidad_surtida,
                                                            'precio' => $detalle->precio,
                                                        ]);
                                                    }
                                                }
                                            }
                                        ?>

                                        <div class="border border-border-light dark:border-border-dark rounded-lg p-5 hover:bg-background-light dark:hover:bg-background-dark transition-colors">
                                            <div class="flex items-start gap-4">
                                                <div class="flex-shrink-0 w-12 h-12 rounded-full bg-primary flex items-center justify-center">
                                                    <span class="text-white text-lg font-bold"><?php echo e($ruta->orden_recoleccion); ?></span>
                                                </div>

                                                <div class="flex-1">
                                                    <div class="flex items-start justify-between mb-2">
                                                        <div>
                                                            <h3 class="text-lg font-bold text-body-text dark:text-body-text-dark"><?php echo e($sucursal->nombre); ?></h3>
                                                            <p class="text-sm text-neutral-text dark:text-neutral-text-dark"><?php echo e($sucursal->cadena->nombre ?? 'N/A'); ?></p>
                                                        </div>
                                                    </div>

                                                    <div class="flex items-start gap-2 mb-3">
                                                        <span class="material-symbols-outlined text-neutral-text dark:text-neutral-text-dark text-lg mt-0.5">location_on</span>
                                                        <div class="text-sm text-neutral-text dark:text-neutral-text-dark">
                                                            <p><?php echo e($sucursal->calle); ?> <?php echo e($sucursal->numero_ext); ?><?php if($sucursal->numero_int): ?> Int. <?php echo e($sucursal->numero_int); ?><?php endif; ?></p>
                                                            <p><?php echo e($sucursal->colonia); ?>, <?php echo e($sucursal->ciudad); ?></p>
                                                        </div>
                                                    </div>

                                                    <?php if($sucursal->contacto): ?>
                                                        <div class="flex items-center gap-2 mb-3">
                                                            <span class="material-symbols-outlined text-neutral-text dark:text-neutral-text-dark text-lg">phone</span>
                                                            <p class="text-sm text-neutral-text dark:text-neutral-text-dark"><?php echo e($sucursal->contacto); ?></p>
                                                        </div>
                                                    <?php endif; ?>

                                                    <?php if($medicamentosEnSucursal->isNotEmpty()): ?>
                                                        <div class="mt-4 pt-4 border-t border-border-light dark:border-border-dark">
                                                            <h4 class="text-sm font-bold text-body-text dark:text-body-text-dark mb-2">Medicamentos a Recoger:</h4>
                                                            <div class="space-y-1">
                                                                <?php $__currentLoopData = $medicamentosEnSucursal; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $med): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <div class="flex items-center justify-between text-sm">
                                                                        <span class="text-neutral-text dark:text-neutral-text-dark"><?php echo e($med['nombre']); ?></span>
                                                                        <div class="flex items-center gap-4">
                                                                            <span class="text-body-text dark:text-body-text-dark font-medium">Cantidad: <?php echo e($med['cantidad']); ?></span>
                                                                            <span class="text-body-text dark:text-body-text-dark font-medium">$<?php echo e(number_format($med['precio'], 2)); ?></span>
                                                                        </div>
                                                                    </div>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>

                                        <?php if(!$loop->last): ?>
                                            <div class="flex justify-center"><span class="material-symbols-outlined text-3xl text-neutral-text dark:text-neutral-text-dark">arrow_downward</span></div>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-8 text-neutral-text dark:text-neutral-text-dark">
                                    <span class="material-symbols-outlined text-5xl mb-2">route</span>
                                    <p>No hay información de ruta disponible para este pedido.</p>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Total Summary -->
                        <div class="rounded-xl border border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark p-6">
                            <h2 class="text-xl font-bold text-body-text dark:text-body-text-dark mb-4">Resumen del Costo</h2>
                            <div class="flex justify-between items-center">
                                <span class="text-lg text-neutral-text dark:text-neutral-text-dark">Total del Pedido:</span>
                                <span class="text-2xl font-bold text-primary">$<?php echo e(number_format($pedido->costo_total ?? 0, 2)); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <?php if($totalParadas > 0): ?>
        <?php
            $branchesData = $rutaOrdenada->map(function ($ruta) {
                return [
                    'lat' => floatval($ruta->sucursal->latitud),
                    'lng' => floatval($ruta->sucursal->longitud),
                    'nombre' => $ruta->sucursal->nombre,
                    'orden' => $ruta->orden_recoleccion,
                    'direccion' => $ruta->sucursal->calle . ' ' . $ruta->sucursal->numero_ext . ', ' . $ruta->sucursal->colonia,
                ];
            })->values();
        ?>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const map = L.map('route-map').setView([19.4326, -99.1332],13);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                    maxZoom: 19
                }).addTo(map);

                const branches = <?php echo json_encode($branchesData, 15, 512) ?>;
                const markers = [];

                branches.forEach((branch) => {
                    const divIcon = L.divIcon({
                        className: 'custom-marker',
                        html: '<div style="width:36px;height:36px;background-color:#137fec;border:3px solid white;border-radius:50%;display:flex;align-items:center;justify-content:center;color:white;font-weight:bold;font-size:16px;box-shadow:0 2px 8px rgba(0,0,0,0.3);">' + branch.orden + '</div>',
                        iconSize: [36, 36],
                        iconAnchor: [18, 18],
                    });

                    const marker = L.marker([branch.lat, branch.lng], { icon: divIcon }).addTo(map);
                    marker.bindPopup('<div style="min-width:200px;"><h3 style="font-weight:bold;margin-bottom:4px;">' + branch.nombre + '</h3><p style="font-size:12px;color:#617589;margin:0;">' + branch.direccion + '</p><p style="font-size:11px;color:#137fec;margin-top:4px;">Parada #' + branch.orden + '</p></div>');
                    markers.push(marker);
                });

                const routeGeometry = <?php echo json_encode($pedido->route_geometry, 15, 512) ?>;
                
                if (routeGeometry) {
                    try {
                        const decoded = decodePolyline(routeGeometry);
                        L.polyline(decoded, { color: '#137fec', weight: 4, opacity: 0.7, smoothFactor: 1 }).addTo(map);
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
                let index = 0, lat = 0, lng = 0;

                while (index < encoded.length) {
                    let b, shift = 0, result = 0;
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
    <?php endif; ?>
</body>

</html>
<?php /**PATH C:\xampp\htdocs\laravel\securityAccess\security-access\resources\views/pharmacy/order-route.blade.php ENDPATH**/ ?>