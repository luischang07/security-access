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

    @php
        $empleado = auth()->user()->empleado;
        $currentCadenaId = $empleado->cadena_id;
        $currentSucursalId = $empleado->sucursal_id;
    @endphp

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
                    <div
                        class="w-full md:w-96 lg:w-[28rem] flex flex-col border-r border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark overflow-hidden">
                        <div class="p-6 border-b border-border-light dark:border-border-dark">
                            <h1 class="text-2xl font-bold text-body-text dark:text-body-text-dark mb-4">
                                {{ __('pharmacy.orders.title') }}</h1>

                            <div class="space-y-2">
                                <label class="text-sm text-neutral-text dark:text-neutral-text-dark"
                                    for="statusFilter">{{ __('pharmacy.orders.filter_by_status') }}</label>
                                <select id="statusFilter"
                                    class="w-full rounded-lg border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark focus:border-primary focus:ring-primary/50 text-sm">
                                    <option value="all">{{ __('pharmacy.orders.all_orders') }}</option>
                                    <option value="confirmado">{{ __('pharmacy.orders.status.confirmed') }}</option>
                                    <option value="surtido">{{ __('pharmacy.orders.status.ready') }}</option>
                                    <option value="cancelado">{{ __('pharmacy.orders.status.cancelled') }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex-1 overflow-y-auto p-4 space-y-4" id="orders-list">
                            @forelse ($pedidos as $index => $pedido)
                                @php
                                    $estatusLower = strtolower($pedido->getEstatus());
                                    $sucursalPedido = $pedido->getSucursal();
                                    $esHostVisual =
                                        $sucursalPedido->getCadenaId() == $currentCadenaId &&
                                        $sucursalPedido->getSucursalId() == $currentSucursalId;
                                @endphp
                                <div onclick="selectOrder(this, {{ $index }})"
                                    class="order-card p-4 rounded-lg border border-transparent hover:bg-background-light dark:hover:bg-background-dark cursor-pointer transition-colors {{ $index === 0 ? 'bg-background-light dark:bg-background-dark' : '' }}"
                                    data-order-index="{{ $index }}"
                                    data-folio="{{ $pedido->getFolio() }}"
                                    data-estatus="{{ $estatusLower }}">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <h3 class="font-bold text-body-text dark:text-body-text-dark">
                                                Folio: {{ $pedido->getFolio() }}</h3>
                                            <p class="text-xs text-neutral-text dark:text-neutral-text-dark">
                                                {{ __('pharmacy.orders.order_number') }} #{{ $pedido->getFolio() }}</p>
                                        </div>

                                        @if ($esHostVisual)
                                            <span
                                                class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10">
                                                {{ __('pharmacy.orders.host_branch') }}
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center rounded-md bg-orange-50 px-2 py-1 text-xs font-medium text-orange-700 ring-1 ring-inset ring-orange-600/20">
                                                {{ __('pharmacy.orders.participant_branch') }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="mt-3 flex items-center justify-between">
                                        <span
                                            class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                            @if($estatusLower === 'confirmado') bg-secondary text-white
                                            @elseif($estatusLower === 'cancelado') bg-danger text-white
                                            @elseif($estatusLower === 'completado') bg-success text-white
                                            @else bg-neutral-200 text-neutral-text @endif">
                                            {{ $pedido->getEstatus() }}
                                        </span>
                                        <p class="text-xs text-neutral-text dark:text-neutral-text-dark">
                                            {{ $pedido->getFechaPedido()?->translatedFormat('d M Y H:i') ?? 'N/A' }}
                                        </p>
                                    </div>
                                </div>
                            @empty
                                <div class="p-4 text-center text-neutral-text dark:text-neutral-text-dark">
                                    {{ __('pharmacy.orders.no_orders') }}
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div class="flex-1 flex flex-col overflow-hidden bg-background-light dark:bg-background-dark">
                        <div class="flex-1 overflow-y-auto p-6 space-y-6">
                            @if ($pedidos->isNotEmpty())
                                <div id="order-details-container">
                                </div>
                            @else
                                <div
                                    class="rounded-xl border border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark p-6 text-center text-neutral-text dark:text-neutral-text-dark">
                                    {{ __('pharmacy.orders.no_orders') }}
                                </div>
                            @endif
                        </div>

                        <div
                            class="border-t border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark p-6">
                            <div class="flex flex-col gap-4">
                                <div id="price-summary"
                                    class="flex flex-col gap-2 text-sm text-neutral-text dark:text-neutral-text-dark">
                                    <div class="flex justify-between items-center">
                                        <span>{{ __('pharmacy.orders.subtotal') }}:</span>
                                        <span id="subtotal"
                                            class="font-medium text-body-text dark:text-body-text-dark">$0.00</span>
                                    </div>
                                    <div id="penalty-row" class="flex justify-between items-center hidden">
                                        <span>{{ __('pharmacy.orders.penalty') }}</span>
                                        <span id="serviceFee" class="font-medium text-danger">$0.00</span>
                                    </div>
                                    <div
                                        class="flex justify-between items-center pt-2 border-t border-border-light dark:border-border-dark">
                                        <span class="font-bold">{{ __('pharmacy.orders.estimated_total') }}:</span>
                                        <span id="estimatedTotal" class="font-bold text-primary text-lg">$0.00</span>
                                    </div>
                                </div>

                                @php
                                    $empleado = auth()->user()->empleado;
                                    $currentCadenaId = $empleado->cadena_id;
                                    $currentSucursalId = $empleado->sucursal_id;
                                    // Check if current user is from host branch (for the currently displayed order)
                                    $esHost = false;
                                    if ($pedidos->isNotEmpty()) {
                                        $primerPedido = $pedidos->first();
                                        $sucursalPedido = $primerPedido->getSucursal();
                                        $esHost = $sucursalPedido->getCadenaId() == $currentCadenaId && 
                                                  $sucursalPedido->getSucursalId() == $currentSucursalId;
                                    }
                                @endphp
                                @if($esHost)
                                    <div id="order-actions-container" class="flex items-center gap-2 w-full">
                                        <button id="cancelOrderBtn"
                                            class="px-4 py-2 rounded-lg bg-danger text-white text-sm font-medium hover:brightness-90 transition flex-1">
                                            {{ __('pharmacy.orders.cancel_button') }}
                                        </button>
                                        <button id="startPreparingBtn"
                                            class="px-6 py-2 rounded-lg bg-primary text-white text-sm font-medium hover:bg-primary/90 transition flex-1">
                                            {{ __('pharmacy.orders.mark_as_ready') }}
                                        </button>
                                    </div>
                                @else
                                    <div id="order-actions-container" class="hidden flex items-center gap-2 w-full">
                                        <button id="cancelOrderBtn"
                                            class="px-4 py-2 rounded-lg bg-danger text-white text-sm font-medium hover:brightness-90 transition flex-1">
                                            {{ __('pharmacy.orders.cancel_button') }}
                                        </button>
                                        <button id="startPreparingBtn"
                                            class="px-6 py-2 rounded-lg bg-primary text-white text-sm font-medium hover:bg-primary/90 transition flex-1">
                                            {{ __('pharmacy.orders.mark_as_ready') }}
                                        </button>
                                    </div>
                                    <div id="participant-message" class="text-center text-sm text-neutral-text dark:text-neutral-text-dark">
                                        <p class="italic">{{ __('pharmacy.orders.participant_message') }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmación de Cancelación -->
    <div id="cancelOrderModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" aria-hidden="true"></div>

        <!-- Modal Container -->
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative transform overflow-hidden rounded-2xl bg-card-light dark:bg-card-dark shadow-2xl transition-all w-full max-w-md">
                <!-- Modal Header -->
                <div class="bg-danger/10 dark:bg-danger/20 px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-danger/20">
                            <span class="material-symbols-outlined text-danger text-2xl">warning</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-body-text dark:text-body-text-dark" id="modal-title">
                                {{ __('pharmacy.orders.cancel_modal.title') }}
                            </h3>
                            <p class="text-sm text-neutral-text dark:text-neutral-text-dark">
                                {{ __('pharmacy.orders.cancel_modal.subtitle') }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Modal Body -->
                <div class="px-6 py-4">
                    <p class="text-sm text-neutral-text dark:text-neutral-text-dark mb-3">
                        {{ __('pharmacy.orders.cancel_modal.question') }} <span id="modalOrderFolio" class="font-bold text-body-text dark:text-body-text-dark"></span>?
                    </p>
                    <div class="rounded-lg border border-warning/40 bg-warning/10 px-4 py-3 text-sm text-warning dark:border-warning/30 dark:bg-warning/15">
                        <div class="flex gap-3">
                            <span class="material-symbols-outlined text-base mt-0.5 flex-shrink-0">info</span>
                            <div>
                                <p class="font-semibold">{{ __('pharmacy.orders.cancel_modal.important') }}:</p>
                                <ul class="text-xs mt-1 space-y-1 list-disc list-inside">
                                    <li>{{ __('pharmacy.orders.cancel_modal.inventory_return') }}</li>
                                    <li>{{ __('pharmacy.orders.cancel_modal.penalty_applied') }}</li>
                                    <li>{{ __('pharmacy.orders.cancel_modal.patient_notified') }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="bg-background-light dark:bg-background-dark px-6 py-4 flex gap-3 justify-end">
                    <button id="cancelModalBtn" type="button"
                        class="px-4 py-2 rounded-lg border border-border-light dark:border-border-dark text-neutral-text dark:text-neutral-text-dark text-sm font-medium hover:bg-card-light dark:hover:bg-card-dark transition">
                        {{ __('pharmacy.orders.cancel_modal.cancel_btn') }}
                    </button>
                    <button id="confirmCancelBtn" type="button"
                        class="px-4 py-2 rounded-lg bg-danger text-white text-sm font-medium hover:brightness-90 transition flex items-center gap-2">
                        <span class="material-symbols-outlined text-base">cancel</span>
                        {{ __('pharmacy.orders.cancel_modal.confirm_btn') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    @php
        $ordersData = [];

        foreach ($pedidos as $p) {
            $sucursalPedido = $p->getSucursal();
            $esHost =
                $sucursalPedido->getCadenaId() == $currentCadenaId &&
                $sucursalPedido->getSucursalId() == $currentSucursalId;

            $lineas_array = [];
            foreach ($p->getLineasPedidos() as $linea) {
                $detalles_array = [];
                foreach ($linea->getDetalles() as $detalle) {
                    if ($esHost) {
                        $detalles_array[] = [
                            'cantidad' => $detalle->getCantidadSurtida(),
                            'precio' => $detalle->getPrecio(),
                            'sucursal' => $detalle->getSucursal()->getNombre(),
                            'sucursal_id' => $detalle->getSucursal()->getSucursalId(),
                        ];
                        continue;
                    }

                    $sucursalDetalle = $detalle->getSucursal();
                    if (
                        $sucursalDetalle->getCadenaId() == $currentCadenaId &&
                        $sucursalDetalle->getSucursalId() == $currentSucursalId
                    ) {
                        $detalles_array[] = [
                            'cantidad' => $detalle->getCantidadSurtida(),
                            'precio' => $detalle->getPrecio(),
                            'sucursal' => $sucursalDetalle->getNombre(),
                            'sucursal_id' => $sucursalDetalle->getSucursalId(),
                        ];
                    }
                }

                if (!empty($detalles_array)) {
                    $lineas_array[] = [
                        'medicamento' => $linea->getMedicamento()->getNombre(),
                        'detalles' => $detalles_array,
                    ];
                }
            }

            $ordersData[] = [
                'folio' => $p->getFolio(),
                'sucursal_host_id' => $sucursalPedido->getSucursalId(),
                'fecha_pedido' => $p->getFechaPedido()?->format('d/m/Y H:i') ?? 'N/A',
                'estatus' => $p->getEstatus(),
                'lineas' => $lineas_array,
                'penalizacion' => $p->getMontoPenalizacion() ? (float) $p->getMontoPenalizacion() : 0,
                'es_host' => $esHost,
            ];
        }
    @endphp

    <script>
        const translations = {
            success: "{{ __('common.success') }}",
            error_canceling: "Error al cancelar",
            order_info: "{{ __('pharmacy.orders.order_info') }}",
            view_route: "{{ __('pharmacy.orders.view_route') }}",
            order_folio: "{{ __('pharmacy.orders.order_folio') }}",
            order_date: "{{ __('pharmacy.orders.order_date') }}",
            prescription_details: "{{ __('pharmacy.orders.prescription_details') }}",
            medication: "{{ __('pharmacy.orders.medication') }}",
            quantity: "{{ __('pharmacy.orders.quantity') }}",
            unit_price: "{{ __('pharmacy.orders.unit_price') }}",
            from_branch: "{{ __('pharmacy.orders.from_branch') }}",
            no_medications: "{{ __('pharmacy.orders.no_medications') }}",
            canceling: "{{ __('pharmacy.orders.canceling') }}",
            cancel_button: "{{ __('pharmacy.orders.cancel_button') }}",
            mark_as_ready: "{{ __('pharmacy.orders.mark_as_ready') }}",
            marked_as_ready: "{{ __('pharmacy.orders.marked_as_ready') }}",
            undo: "{{ __('pharmacy.orders.undo') }}",
            undo_success: "{{ __('pharmacy.orders.undo_success') }}"
        };

        const orders = @json($ordersData);
        const csrfToken = '{{ csrf_token() }}';
        const currentSucursalId = "{{ $currentSucursalId }}";
        let currentOrderIndex = 0;
        let errorMessage = null; 
        let successMessage = null;
        let undoTimer = null;
        let undoTimeRemaining = 0; 

        function selectOrder(element, index) {
            document.querySelectorAll('.order-card').forEach(card => {
                card.classList.remove('bg-background-light', 'dark:bg-background-dark');
                card.classList.add('border-transparent');
            });

            element.classList.add('bg-background-light', 'dark:bg-background-dark');

            currentOrderIndex = index;
            errorMessage = null; 
            successMessage = null; 
            renderOrderDetails();
        }

        function renderOrderDetails() {
            const order = orders[currentOrderIndex];
            const container = document.getElementById('order-details-container');

            let html = ``;

            // Mostrar alert de éxito si existe
            if (successMessage) {
                const showUndoButton = undoTimeRemaining > 0;
                html += `
                    <div class="rounded-lg border border-success/40 bg-success/10 px-4 py-3 text-sm text-success dark:border-success/30 dark:bg-success/15 mb-4">
                        <div class="flex items-center justify-between gap-3">
                            <div class="flex gap-3 flex-1">
                                <span class="material-symbols-outlined text-xl mt-0.5 flex-shrink-0">check_circle</span>
                                <div>
                                    <p class="font-semibold">${translations.success}</p>
                                    <p class="text-xs mt-1">${successMessage}</p>
                                </div>
                            </div>
                            ${showUndoButton ? `
                                <button id="undoButton" 
                                    class="px-4 py-2 rounded-lg bg-white/20 hover:bg-white/30 text-success font-medium transition flex items-center gap-2 border border-success/30">
                                    <span class="material-symbols-outlined text-base">undo</span>
                                    ${translations.undo} (<span id="undoCountdown">${undoTimeRemaining}</span>s)
                                </button>
                            ` : ''}
                        </div>
                    </div>
                `;
            }

            // Mostrar alert de error si existe
            if (errorMessage) {
                html += `
                    <div class="rounded-lg border border-danger/40 bg-danger/10 px-4 py-3 text-sm text-danger dark:border-danger/30 dark:bg-danger/15 mb-4">
                        <div class="flex gap-3">
                            <span class="material-symbols-outlined text-xl mt-0.5 flex-shrink-0">error</span>
                            <div>
                                <p class="font-semibold">${translations.error_canceling}</p>
                                <p class="text-xs mt-1">${errorMessage}</p>
                            </div>
                        </div>
                    </div>
                `;
            }

            html += `
                <div class="rounded-xl border border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-bold text-body-text dark:text-body-text-dark">
                            ${translations.order_info}</h2>
                        ${order.es_host ? `
                            <a href="/pharmacy/orders/${order.folio}/route" class="px-3 py-1.5 rounded-lg bg-primary/10 text-primary text-sm font-bold hover:bg-primary/20 transition flex items-center gap-2">
                                <span class="material-symbols-outlined text-base">map</span>
                                ${translations.view_route}
                            </a>
                        ` : ''}
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-neutral-text dark:text-neutral-text-dark">${translations.order_folio}</p>
                            <p class="font-medium text-body-text dark:text-body-text-dark">${order.folio}</p>
                        </div>
                        <div>
                            <p class="text-sm text-neutral-text dark:text-neutral-text-dark">${translations.order_date}</p>
                            <p class="font-medium text-body-text dark:text-body-text-dark">${order.fecha_pedido}</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-bold text-body-text dark:text-body-text-dark">
                            ${translations.prescription_details}</h2>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-border-light dark:divide-border-dark">
                            <thead>
                                <tr>
                                    <th class="py-3.5 px-6 text-left text-sm font-semibold text-body-text dark:text-body-text-dark">
                                        ${translations.medication}</th>
                                    <th class="px-3 py-3.5 text-left text-sm font-semibold text-body-text dark:text-body-text-dark">
                                        ${translations.quantity}</th>
                                    <th class="px-3 py-3.5 text-left text-sm font-semibold text-body-text dark:text-body-text-dark">
                                        ${translations.unit_price}</th>
                                    <th class="px-3 py-3.5 text-left text-sm font-semibold text-body-text dark:text-body-text-dark">
                                        ${translations.from_branch}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-border-light dark:divide-border-dark">
            `;

            let subtotal = 0;
            if (order.lineas && order.lineas.length > 0) {
                order.lineas.forEach(linea => {
                    if (linea.detalles && linea.detalles.length > 0) {
                        linea.detalles.forEach(detalle => {

                            const soyHost = order.es_host;
                            const esMiDetalle = detalle.sucursal_id == currentSucursalId;

                            if (soyHost || esMiDetalle) {
                                const lineTotal = (parseFloat(detalle.precio) || 0) * (parseInt(detalle.
                                    cantidad) || 0);
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
                            }
                        });
                    }
                });
            } else {
                html += `
                    <tr>
                        <td colspan="4" class="whitespace-nowrap py-4 px-6 text-sm text-center text-neutral-text dark:text-neutral-text-dark">
                            ${translations.no_medications}</td>
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

            const penalizacion = order.penalizacion || 0;
            updatePriceSummary(subtotal, penalizacion);
        }

        function updatePriceSummary(subtotal, penalizacion) {
            const subtotalEl = document.getElementById('subtotal');
            const serviceFeeEl = document.getElementById('serviceFee');
            const penaltyRowEl = document.getElementById('penalty-row');
            const estimatedEl = document.getElementById('estimatedTotal');
            const priceSummary = document.getElementById('price-summary');
            const cancelBtn = document.getElementById('cancelOrderBtn');
            const startBtn = document.getElementById('startPreparingBtn');
            const order = orders[currentOrderIndex];
            const status = order?.estatus ? order.estatus.toLowerCase() : '';

            if (subtotalEl && serviceFeeEl && estimatedEl) {
                const total = subtotal + penalizacion;
                subtotalEl.textContent = `$${subtotal.toFixed(2)}`;
                estimatedEl.textContent = `$${total.toFixed(2)}`;
                serviceFeeEl.textContent = `$${penalizacion.toFixed(2)}`;

                if (priceSummary) {
                    priceSummary.classList.toggle('hidden', subtotal === 0);
                }
                if (penaltyRowEl) {
                    if (penalizacion > 0) {
                        penaltyRowEl.classList.remove('hidden');
                        penaltyRowEl.classList.add('flex');
                    } else {
                        penaltyRowEl.classList.add('hidden');
                        penaltyRowEl.classList.remove('flex');
                    }
                }
            }

            if (cancelBtn && startBtn) {
                let cancelDisabled = false;
                let startDisabled = false;

                if (status === 'confirmado') {
                    cancelDisabled = true;
                    startDisabled = false;
                } else if (status === 'surtido') {
                    cancelDisabled = false;
                    startDisabled = true;
                } else if (status === 'cancelado') {
                    cancelDisabled = true;
                    startDisabled = true;
                } else {
                    cancelDisabled = false;
                    startDisabled = false;
                }

                cancelBtn.disabled = cancelDisabled;
                cancelBtn.classList.toggle('opacity-50', cancelDisabled);
                cancelBtn.classList.toggle('cursor-not-allowed', cancelDisabled);

                startBtn.disabled = startDisabled;
                startBtn.classList.toggle('opacity-50', startDisabled);
                startBtn.classList.toggle('cursor-not-allowed', startDisabled);
            }

            // Show/hide buttons container and participant message based on es_host
            const actionsContainer = document.getElementById('order-actions-container');
            const participantMessage = document.getElementById('participant-message');
            
            if (actionsContainer && participantMessage) {
                if (order.es_host) {
                    // Show buttons, hide message
                    actionsContainer.classList.remove('hidden');
                    participantMessage.classList.add('hidden');
                } else {
                    // Hide buttons, show message
                    actionsContainer.classList.add('hidden');
                    participantMessage.classList.remove('hidden');
                }
            }
        }

        function strtolower(str) {
            return typeof str === 'string' ? str.toLowerCase() : '';
        }

        function applyStatusFilter() {
            const filter = document.getElementById('statusFilter').value;
            const cards = document.querySelectorAll('.order-card');
            let firstVisibleIndex = null;

            cards.forEach(card => {
                const status = card.dataset.estatus;
                const matches = filter === 'all' || status === filter;
                card.style.display = matches ? '' : 'none';
                if (matches && firstVisibleIndex === null) {
                    firstVisibleIndex = parseInt(card.dataset.orderIndex, 10);
                }
            });

            if (firstVisibleIndex !== null) {
                const card = Array.from(cards).find(c => parseInt(c.dataset.orderIndex, 10) === firstVisibleIndex);
                if (card) {
                    selectOrder(card, firstVisibleIndex);
                }
            } else {
                const container = document.getElementById('order-details-container');
                if (container) {
                    container.innerHTML =
                        '<div class="p-4 text-center text-neutral-text dark:text-neutral-text-dark">No hay pedidos para este filtro.</div>';
                }
            }
        }

        function showCancelModal() {
            const order = orders[currentOrderIndex];
            if (!order) return;

            const modal = document.getElementById('cancelOrderModal');
            const folioSpan = document.getElementById('modalOrderFolio');
            
            if (folioSpan) {
                folioSpan.textContent = order.folio;
            }
            
            if (modal) {
                modal.classList.remove('hidden');
                // Prevent body scroll when modal is open
                document.body.style.overflow = 'hidden';
            }
        }

        function hideCancelModal() {
            const modal = document.getElementById('cancelOrderModal');
            if (modal) {
                modal.classList.add('hidden');
                // Restore body scroll
                document.body.style.overflow = '';
            }
        }

        async function cancelCurrentOrder() {
            const order = orders[currentOrderIndex];
            if (!order) {
                return;
            }

            // Hide modal
            hideCancelModal();

            const btn = document.getElementById('cancelOrderBtn');
            btn.disabled = true;
            btn.textContent = translations.canceling;

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
                    renderOrderDetails();
                    btn.disabled = false;
                    btn.textContent = translations.cancel_button;
                    return;
                }

                // Éxito: actualizar orden
                order.estatus = 'cancelado';
                errorMessage = null;
                successMessage = data.message;
                renderOrderDetails();
                updatePriceSummary(0, 0);

                // Actualizar el badge del card en la lista
                const orderCard = document.querySelector(`[data-folio="${order.folio}"]`);
                if (orderCard) {
                    // Find the second .inline-flex which is the status badge (first one is host/participant badge)
                    const badges = orderCard.querySelectorAll('.inline-flex');
                    if (badges.length >= 2) {
                        const statusBadge = badges[1]; // The status badge is the second one
                        statusBadge.className = 'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-danger text-white';
                        statusBadge.textContent = 'cancelado';
                    }
                }

                btn.textContent = translations.cancel_button;

            } catch (e) {
                errorMessage = 'Error en la solicitud: ' + e.message;
                renderOrderDetails();
                btn.disabled = false;
                btn.textContent = translations.cancel_button;
            }
        }

        async function marcarComoSurtido() {
            const order = orders[currentOrderIndex];
            if (!order) return;

            const btn = document.getElementById('startPreparingBtn');
            btn.disabled = true;
            btn.textContent = 'Marcando...';

            try {
                const res = await fetch(`/pharmacy/orders/mark-surtido/${order.folio}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                });

                if (!res.ok) {
                    const err = await res.json().catch(() => null);
                    throw new Error(err?.error || err?.message || 'No se pudo marcar como surtido');
                }

                // Update order status locally without reloading
                order.estatus = 'surtido';
                successMessage = translations.marked_as_ready;
                errorMessage = null;
                
                // Start undo timer (30 seconds)
                startUndoTimer(order.folio);
                
                renderOrderDetails();
                
                // Update the status badge in the order card
                const orderCard = document.querySelector(`[data-folio=\"${order.folio}\"]`);
                if (orderCard) {
                    const badges = orderCard.querySelectorAll('.inline-flex');
                    if (badges.length >= 2) {
                        const statusBadge = badges[1];
                        statusBadge.className = 'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-primary text-white';
                        statusBadge.textContent = 'surtido';
                    }
                }
                
                btn.disabled = false;
                btn.textContent = translations.mark_as_ready;
            } catch (e) {
                alert(e.message || 'No se pudo marcar como surtido. Intenta de nuevo.');
                btn.disabled = false;
                btn.textContent = translations.mark_as_ready;
            }
        }

        function startUndoTimer(folio) {
            // Clear any existing timer
            if (undoTimer) {
                clearInterval(undoTimer);
            }
            
            // Set initial time (30 seconds)
            undoTimeRemaining = 30;
            
            // Start countdown
            undoTimer = setInterval(() => {
                undoTimeRemaining--;
                
                // Update countdown display if button exists
                const countdown = document.getElementById('undoCountdown');
                if (countdown) {
                    countdown.textContent = undoTimeRemaining;
                }
                
                // When time runs out
                if (undoTimeRemaining <= 0) {
                    clearInterval(undoTimer);
                    undoTimer = null;
                    renderOrderDetails(); // Re-render to hide undo button
                }
            }, 1000);
        }

        async function deshacerSurtido() {
            const order = orders[currentOrderIndex];
            if (!order) return;

            const btn = document.getElementById('undoButton');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<span class="material-symbols-outlined text-base animate-spin">refresh</span> Deshaciendo...';
            }

            try {
                const res = await fetch(`/pharmacy/orders/undo-surtido/${order.folio}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                });

                if (!res.ok) {
                    const err = await res.json().catch(() => null);
                    throw new Error(err?.error || err?.message || 'No se pudo deshacer');
                }

                // Clear timer
                if (undoTimer) {
                    clearInterval(undoTimer);
                    undoTimer = null;
                }
                undoTimeRemaining = 0;

                // Update order status back to confirmed
                order.estatus = 'confirmado';
                successMessage = translations.undo_success;
                errorMessage = null;
                renderOrderDetails();

                // Update the status badge in the order card
                const orderCard = document.querySelector(`[data-folio=\"${order.folio}\"]`);
                if (orderCard) {
                    const badges = orderCard.querySelectorAll('.inline-flex');
                    if (badges.length >= 2) {
                        const statusBadge = badges[1];
                        statusBadge.className = 'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-secondary text-white';
                        statusBadge.textContent = 'confirmado';
                    }
                }
            } catch (e) {
                errorMessage = e.message || 'No se pudo deshacer. Intenta de nuevo.';
                renderOrderDetails();
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            renderOrderDetails();

            const firstCard = document.querySelector('.order-card');
            if (firstCard) {
                firstCard.classList.add('bg-background-light', 'dark:bg-background-dark');
            }

            // Only attach event listeners if buttons exist (host branches only)
            const cancelBtn = document.getElementById('cancelOrderBtn');
            if (cancelBtn) {
                cancelBtn.addEventListener('click', showCancelModal);
            }

            // Modal event listeners
            const cancelModalBtn = document.getElementById('cancelModalBtn');
            if (cancelModalBtn) {
                cancelModalBtn.addEventListener('click', hideCancelModal);
            }

            const confirmCancelBtn = document.getElementById('confirmCancelBtn');
            if (confirmCancelBtn) {
                confirmCancelBtn.addEventListener('click', cancelCurrentOrder);
            }

            // Close modal when clicking outside
            const modal = document.getElementById('cancelOrderModal');
            if (modal) {
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) {
                        hideCancelModal();
                    }
                });
            }
            
            const startBtn = document.getElementById('startPreparingBtn');
            if (startBtn) {
                startBtn.addEventListener('click', marcarComoSurtido);
            }

            const statusFilter = document.getElementById('statusFilter');
            if (statusFilter) {
                statusFilter.addEventListener('change', applyStatusFilter);
            }

            // Event delegation for dynamically created undo button
            document.addEventListener('click', function(e) {
                if (e.target && e.target.id === 'undoButton') {
                    deshacerSurtido();
                } else if (e.target && e.target.closest('#undoButton')) {
                    deshacerSurtido();
                }
            });

            applyStatusFilter();
        });
    </script>
</body>

</html>