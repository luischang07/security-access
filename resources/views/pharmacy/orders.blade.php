<!DOCTYPE html>
<html class="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('pharmacy.orders.title') }} - Te Acerco Salud</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200;300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200"
        rel="stylesheet">
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
        .material-symbols-outlined {
            font-variation-settings:
                'FILL' 0,
                'wght' 400,
                'GRAD' 0,
                'opsz' 24
        }
    </style>
</head>

<body class="font-display bg-background-light dark:bg-background-dark text-body-text dark:text-body-text-dark">
    <div class="relative flex h-screen w-full flex-col overflow-hidden">
        <div class="layout-container flex h-full grow flex-col">

            @include('components.topbar', ['user' => auth()->user(), 'type' => 'pharmacy'])

            <div class="flex flex-1 overflow-hidden">
                @include('components.sidebar', [
                    'user' => auth()->user(),
                    'type' => 'pharmacy',
                    'currentRoute' => 'pharmacy.orders',
                ])

                <div class="flex flex-1">
                    <!-- Order List Panel -->
                    <div
                        class="w-full md:w-96 lg:w-[28rem] flex flex-col border-r border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark overflow-hidden">
                        <div class="p-6 border-b border-border-light dark:border-border-dark">
                            <h1 class="text-2xl font-bold text-body-text dark:text-body-text-dark mb-4">
                                {{ __('pharmacy.orders.title') }}</h1>

                            <!-- Search bar removed -->

                            <!-- Status Tabs removed per request -->
                        </div>

                        <!-- Order Cards List -->
                        <div class="flex-1 overflow-y-auto p-4 space-y-4">
                            @php
                                $pedidosConfirmados = collect($pedidos)->filter(fn($p) => strtolower($p->getEstatus()) === 'confirmado');
                                $pedidosCancelados = collect($pedidos)->filter(fn($p) => strtolower($p->getEstatus()) === 'cancelado');
                            @endphp

                            <!-- Confirmados -->
                            @if($pedidosConfirmados->count() > 0)
                                <div>
                                    <h3 class="text-xs font-bold uppercase text-neutral-text dark:text-neutral-text-dark mb-2">Confirmados</h3>
                                    <div class="space-y-2">
                                        @foreach ($pedidosConfirmados as $index => $pedido)
                                            <div
                                                onclick="selectOrder(this, {{ $index }})"
                                                class="order-card p-4 rounded-lg border border-transparent hover:bg-background-light dark:hover:bg-background-dark cursor-pointer transition-colors {{ $index === 0 ? 'bg-background-light dark:bg-background-dark' : '' }}"
                                                data-order-index="{{ $index }}"
                                                data-folio="{{ $pedido->getFolio() }}"
                                                data-estatus="{{ strtolower($pedido->getEstatus()) }}">
                                                <div class="flex items-start justify-between">
                                                    <div>
                                                        <h3 class="font-bold text-body-text dark:text-body-text-dark">
                                                            Folio: {{ $pedido->getFolio() }}</h3>
                                                        <p class="text-xs text-neutral-text dark:text-neutral-text-dark">
                                                            {{ __('pharmacy.orders.order_number') }} #{{ $pedido->getFolio() }}</p>
                                                    </div>
                                                    <span
                                                        class="material-symbols-outlined text-lg text-neutral-text dark:text-neutral-text-dark">storefront</span>
                                                </div>
                                                <div class="mt-3 flex items-center justify-between">
                                                    <span
                                                        class="inline-flex items-center rounded-full bg-success px-2.5 py-0.5 text-xs font-medium text-white">{{ $pedido->getEstatus() }}</span>
                                                    <p class="text-xs text-neutral-text dark:text-neutral-text-dark">
                                                        {{ $pedido->getFechaPedido()?->translatedFormat('d M Y H:i') ?? 'N/A' }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Cancelados -->
                            @if($pedidosCancelados->count() > 0)
                                <div>
                                    <h3 class="text-xs font-bold uppercase text-neutral-text dark:text-neutral-text-dark mb-2">Cancelados</h3>
                                    <div class="space-y-2">
                                        @foreach ($pedidosCancelados as $index => $pedido)
                                            <div
                                                onclick="selectOrder(this, {{ $index }})"
                                                class="order-card p-4 rounded-lg border border-transparent hover:bg-background-light dark:hover:bg-background-dark cursor-pointer transition-colors opacity-60"
                                                data-order-index="{{ $index }}"
                                                data-folio="{{ $pedido->getFolio() }}"
                                                data-estatus="{{ strtolower($pedido->getEstatus()) }}">
                                                <div class="flex items-start justify-between">
                                                    <div>
                                                        <h3 class="font-bold text-body-text dark:text-body-text-dark">
                                                            Folio: {{ $pedido->getFolio() }}</h3>
                                                        <p class="text-xs text-neutral-text dark:text-neutral-text-dark">
                                                            {{ __('pharmacy.orders.order_number') }} #{{ $pedido->getFolio() }}</p>
                                                    </div>
                                                    <span
                                                        class="material-symbols-outlined text-lg text-neutral-text dark:text-neutral-text-dark">storefront</span>
                                                </div>
                                                <div class="mt-3 flex items-center justify-between">
                                                    <span
                                                        class="inline-flex items-center rounded-full bg-danger px-2.5 py-0.5 text-xs font-medium text-white">{{ $pedido->getEstatus() }}</span>
                                                    <p class="text-xs text-neutral-text dark:text-neutral-text-dark">
                                                        {{ $pedido->getFechaPedido()?->translatedFormat('d M Y H:i') ?? 'N/A' }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if($pedidosConfirmados->count() === 0 && $pedidosCancelados->count() === 0)
                                <div class="p-4 text-center text-neutral-text dark:text-neutral-text-dark">
                                    {{ __('pharmacy.orders.no_orders') }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Order Detail Panel -->
                    <div class="flex-1 flex flex-col overflow-hidden bg-background-light dark:bg-background-dark">
                        <div class="flex-1 overflow-y-auto p-6 space-y-6">
                            @if ($pedidos->isNotEmpty())
                                <div id="order-details-container">
                                    <!-- Detalles del pedido se mostrarán aquí con JavaScript -->
                                </div>
                            @else
                                <div class="rounded-xl border border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark p-6 text-center text-neutral-text dark:text-neutral-text-dark">
                                    {{ __('pharmacy.orders.no_orders') }}
                                </div>
                            @endif
                        </div>

                        <!-- Footer Actions -->
                        <div class="border-t border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark p-6">
                            <div class="flex flex-col gap-4">
                                <!-- Price Summary -->
                                <div id="price-summary" class="flex flex-col gap-2 text-sm text-neutral-text dark:text-neutral-text-dark">
                                    <div class="flex justify-between items-center">
                                        <span>Subtotal:</span>
                                        <span id="subtotal" class="font-medium text-body-text dark:text-body-text-dark">$0.00</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span>Tarifa de servicio:</span>
                                        <span id="serviceFee" class="font-medium text-body-text dark:text-body-text-dark">$1.00</span>
                                    </div>
                                    <div class="flex justify-between items-center pt-2 border-t border-border-light dark:border-border-dark">
                                        <span class="font-bold">Total estimado:</span>
                                        <span id="estimatedTotal" class="font-bold text-primary text-lg">$0.00</span>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="flex items-center gap-2 w-full">
                                    <button
                                        id="cancelOrderBtn"
                                        class="px-4 py-2 rounded-lg bg-danger text-white text-sm font-medium hover:brightness-90 transition flex-1">
                                        Cancelar
                                    </button>
                                    <button
                                        id="startPreparingBtn"
                                        class="px-6 py-2 rounded-lg bg-primary text-white text-sm font-medium hover:bg-primary/90 transition flex-1">
                                        {{ __('pharmacy.orders.start_preparing') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @php
        $ordersData = [];
        foreach ($pedidos as $p) {
            $lineas_array = [];
            foreach ($p->getLineasPedidos() as $linea) {
                $detalles_array = [];
                foreach ($linea->getDetalles() as $detalle) {
                    $detalles_array[] = [
                        'cantidad' => $detalle->getCantidadSurtida(),
                        'precio' => $detalle->getPrecio(),
                        'sucursal' => $detalle->getSucursal()->getNombre(),
                    ];
                }
                $lineas_array[] = [
                    'medicamento' => $linea->getMedicamento()->getNombre(),
                    'detalles' => $detalles_array,
                ];
            }
            $ordersData[] = [
                'folio' => $p->getFolio(),
                'fecha_pedido' => $p->getFechaPedido()?->format('d/m/Y H:i') ?? 'N/A',
                'estatus' => $p->getEstatus(),
                'lineas' => $lineas_array,
            ];
        }
    @endphp

    <script>
        // Data de los pedidos
        const orders = @json($ordersData);
        const csrfToken = '{{ csrf_token() }}';
        let currentOrderIndex = 0;
        const serviceFeeFixed = 1.00;
        let errorMessage = null; // Variable para almacenar mensaje de error

        function selectOrder(element, index) {
            // Remover selección anterior
            document.querySelectorAll('.order-card').forEach(card => {
                card.classList.remove('bg-background-light', 'dark:bg-background-dark');
                card.classList.add('border-transparent');
            });

            // Marcar el nuevo seleccionado
            element.classList.add('bg-background-light', 'dark:bg-background-dark');
            
            currentOrderIndex = index;
            errorMessage = null; // Limpiar error anterior
            renderOrderDetails();
        }

        function renderOrderDetails() {
            const order = orders[currentOrderIndex];
            const container = document.getElementById('order-details-container');

            let html = ``;

            // Mostrar alert de error si existe
            if (errorMessage) {
                html += `
                    <div class="rounded-lg border border-danger/40 bg-danger/10 px-4 py-3 text-sm text-danger dark:border-danger/30 dark:bg-danger/15 mb-4">
                        <div class="flex gap-3">
                            <span class="material-symbols-outlined text-xl mt-0.5 flex-shrink-0">error</span>
                            <div>
                                <p class="font-semibold">Error al cancelar</p>
                                <p class="text-xs mt-1">${errorMessage}</p>
                            </div>
                        </div>
                    </div>
                `;
            }

            html += `
                <!-- Patient Info Card -->
                <div class="rounded-xl border border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-bold text-body-text dark:text-body-text-dark">
                            Información del Pedido</h2>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-neutral-text dark:text-neutral-text-dark">Folio del Pedido</p>
                            <p class="font-medium text-body-text dark:text-body-text-dark">${order.folio}</p>
                        </div>
                        <div>
                            <p class="text-sm text-neutral-text dark:text-neutral-text-dark">Fecha del Pedido</p>
                            <p class="font-medium text-body-text dark:text-body-text-dark">${order.fecha_pedido}</p>
                        </div>
                    </div>
                </div>

                <!-- Prescription Details -->
                <div class="rounded-xl border border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-bold text-body-text dark:text-body-text-dark">
                            Detalles de la Receta</h2>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-border-light dark:divide-border-dark">
                            <thead>
                                <tr>
                                    <th class="py-3.5 px-6 text-left text-sm font-semibold text-body-text dark:text-body-text-dark">
                                        Medicamento</th>
                                    <th class="px-3 py-3.5 text-left text-sm font-semibold text-body-text dark:text-body-text-dark">
                                        Cantidad</th>
                                    <th class="px-3 py-3.5 text-left text-sm font-semibold text-body-text dark:text-body-text-dark">
                                        Precio Unitario</th>
                                    <th class="px-3 py-3.5 text-left text-sm font-semibold text-body-text dark:text-body-text-dark">
                                        Sucursal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-border-light dark:divide-border-dark">
            `;

            let subtotal = 0;
            if (order.lineas && order.lineas.length > 0) {
                order.lineas.forEach(linea => {
                    if (linea.detalles && linea.detalles.length > 0) {
                        linea.detalles.forEach(detalle => {
                            const lineTotal = (parseFloat(detalle.precio) || 0) * (parseInt(detalle.cantidad) || 0);
                            subtotal += lineTotal;
                            html += `
                                <tr>
                                    <td class="whitespace-nowrap py-4 px-6 text-sm font-medium text-body-text dark:text-body-text-dark">
                                        ${linea.medicamento}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-neutral-text dark:text-neutral-text-dark">
                                        ${detalle.cantidad}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-neutral-text dark:text-neutral-text-dark">
                                        $${parseFloat(detalle.precio).toFixed(2)}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-neutral-text dark:text-neutral-text-dark">
                                        ${detalle.sucursal}</td>
                                </tr>
                            `;
                        });
                    }
                });
            } else {
                html += `
                    <tr>
                        <td colspan="4" class="whitespace-nowrap py-4 px-6 text-sm text-center text-neutral-text dark:text-neutral-text-dark">
                            No hay medicamentos en este pedido</td>
                    </tr>
                `;
            }

            html += `
                            </tbody>
                        </table>
                    </div>
                </div>
            `;

            container.innerHTML = html;

            // Actualizar resumen de precios
            updatePriceSummary(subtotal);
        }

        function updatePriceSummary(subtotal) {
            const subtotalEl = document.getElementById('subtotal');
            const serviceFeeEl = document.getElementById('serviceFee');
            const estimatedEl = document.getElementById('estimatedTotal');
            const priceSummary = document.getElementById('price-summary');
            const cancelBtn = document.getElementById('cancelOrderBtn');
            const startBtn = document.getElementById('startPreparingBtn');
            const order = orders[currentOrderIndex];
            
            if (subtotalEl && serviceFeeEl && estimatedEl) {
                const total = subtotal + serviceFeeFixed;
                subtotalEl.textContent = `$${subtotal.toFixed(2)}`;
                serviceFeeEl.textContent = `$${serviceFeeFixed.toFixed(2)}`;
                estimatedEl.textContent = `$${total.toFixed(2)}`;
                
                // Mostrar resumen si hay líneas
                if (priceSummary) {
                    priceSummary.classList.toggle('hidden', subtotal === 0);
                }
            }

            // Deshabilitar botones si el pedido está cancelado
            const isDisabled = order && order.estatus && strtolower(order.estatus) === 'cancelado';
            if (cancelBtn) {
                cancelBtn.disabled = isDisabled;
                cancelBtn.classList.toggle('opacity-50', isDisabled);
                cancelBtn.classList.toggle('cursor-not-allowed', isDisabled);
            }
            if (startBtn) {
                startBtn.disabled = isDisabled;
                startBtn.classList.toggle('opacity-50', isDisabled);
                startBtn.classList.toggle('cursor-not-allowed', isDisabled);
            }
        }

        function strtolower(str) {
            return typeof str === 'string' ? str.toLowerCase() : '';
        }

        async function cancelCurrentOrder() {
            const order = orders[currentOrderIndex];
            if (!order) {
                return;
            }

            const btn = document.getElementById('cancelOrderBtn');
            btn.disabled = true;

            try {
                const res = await fetch(`/pharmacy/orders/cancel/${order.folio}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                });

                const data = await res.json().catch(() => null);

                if (!res.ok) {
                    errorMessage = data?.message || 'Error al cancelar el pedido';
                    renderOrderDetails(); // Re-render para mostrar el error
                    btn.disabled = false;
                    return;
                }

                // Éxito: actualizar orden
                order.estatus = 'Cancelado';
                errorMessage = null;
                renderOrderDetails();
                updatePriceSummary(0);

            } catch (e) {
                errorMessage = 'Error en la solicitud: ' + e.message;
                renderOrderDetails();
                btn.disabled = false;
            }
        }

        // Inicializar con el primer pedido
        document.addEventListener('DOMContentLoaded', function() {
            renderOrderDetails();
            
            // Marcar el primer pedido como seleccionado
            const firstCard = document.querySelector('.order-card');
            if (firstCard) {
                firstCard.classList.add('bg-background-light', 'dark:bg-background-dark');
            }

            // Agregar evento al botón cancelar
            const cancelBtn = document.getElementById('cancelOrderBtn');
            if (cancelBtn) {
                cancelBtn.addEventListener('click', cancelCurrentOrder);
            }
        });
    </script>
</body>

</html>
