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

                            <!-- Search Bar -->
                            <div class="relative mb-4">
                                <span
                                    class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-neutral-text dark:text-neutral-text-dark">search</span>
                                <input type="text" placeholder="{{ __('pharmacy.orders.search_placeholder') }}"
                                    class="w-full pl-10 pr-4 py-2 rounded-lg border border-border-light dark:border-border-dark bg-background-light dark:bg-background-dark text-body-text dark:text-body-text-dark placeholder:text-neutral-text dark:placeholder:text-neutral-text-dark focus:ring-2 focus:ring-primary focus:border-transparent">
                            </div>

                            <!-- Status Tabs -->
                            <div class="flex gap-2 text-sm">
                                <button
                                    class="px-4 py-2 rounded-lg bg-primary text-white font-medium">{{ __('pharmacy.orders.status.new') }}
                                    (4)</button>
                                <button
                                    class="px-4 py-2 rounded-lg text-neutral-text dark:text-neutral-text-dark hover:bg-background-light dark:hover:bg-background-dark">{{ __('pharmacy.orders.status.in_progress') }}
                                    (2)</button>
                                <button
                                    class="px-4 py-2 rounded-lg text-neutral-text dark:text-neutral-text-dark hover:bg-background-light dark:hover:bg-background-dark">{{ __('pharmacy.orders.status.ready') }}
                                    (3)</button>
                            </div>
                        </div>

                        <!-- Order Cards List -->
                        <div class="flex-1 overflow-y-auto p-4 space-y-2">
                            @php
                                $orders = [
                                    [
                                        'name' => 'Carlos Rodriguez',
                                        'order' => '#67890',
                                        'icon' => 'storefront',
                                        'time' => '2m',
                                        'status' => 'new',
                                    ],
                                    [
                                        'name' => 'Maria Gonzalez',
                                        'order' => '#67889',
                                        'icon' => 'storefront',
                                        'time' => '10m',
                                        'status' => 'new',
                                    ],
                                    [
                                        'name' => 'Sofia Hernandez',
                                        'order' => '#67888',
                                        'icon' => 'local_shipping',
                                        'time' => '25m',
                                        'status' => 'new',
                                    ],
                                    [
                                        'name' => 'Javier Lopez',
                                        'order' => '#67887',
                                        'icon' => 'storefront',
                                        'time' => '1h',
                                        'status' => 'new',
                                    ],
                                ];
                            @endphp

                            @foreach ($orders as $order)
                                <div
                                    class="p-4 rounded-lg border border-transparent hover:bg-background-light dark:hover:bg-background-dark cursor-pointer">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <h3 class="font-bold text-body-text dark:text-body-text-dark">
                                                {{ $order['name'] }}</h3>
                                            <p class="text-xs text-neutral-text dark:text-neutral-text-dark">
                                                {{ __('pharmacy.orders.order_number') }} {{ $order['order'] }}</p>
                                        </div>
                                        <span
                                            class="material-symbols-outlined text-lg text-neutral-text dark:text-neutral-text-dark">{{ $order['icon'] }}</span>
                                    </div>
                                    <div class="mt-3 flex items-center justify-between">
                                        <span
                                            class="inline-flex items-center rounded-full bg-danger px-2.5 py-0.5 text-xs font-medium text-white">{{ __('pharmacy.orders.status.new') }}</span>
                                        <p class="text-xs text-neutral-text dark:text-neutral-text-dark">
                                            {{ __('pharmacy.orders.received_ago', ['time' => $order['time']]) }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Order Detail Panel -->
                    <div class="flex-1 flex flex-col overflow-hidden bg-background-light dark:bg-background-dark">
                        <div class="flex-1 overflow-y-auto p-6 space-y-6">

                            <!-- Patient Info Card -->
                            <div
                                class="rounded-xl border border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <h2 class="text-xl font-bold text-body-text dark:text-body-text-dark">
                                        {{ __('pharmacy.orders.patient_info') }}</h2>
                                    <div
                                        class="flex items-center gap-2 rounded-full bg-danger/10 px-3 py-1 text-xs font-medium text-danger">
                                        <span class="material-symbols-outlined text-base">warning</span>
                                        {{ __('pharmacy.orders.allergy_warning') }}: Penicillin
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                                    <div>
                                        <p class="text-sm text-neutral-text dark:text-neutral-text-dark">
                                            {{ __('pharmacy.orders.patient_name') }}</p>
                                        <p class="font-medium text-body-text dark:text-body-text-dark">Carlos Rodriguez
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-neutral-text dark:text-neutral-text-dark">
                                            {{ __('pharmacy.orders.date_of_birth') }}</p>
                                        <p class="font-medium text-body-text dark:text-body-text-dark">15/08/1985</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-neutral-text dark:text-neutral-text-dark">
                                            {{ __('pharmacy.orders.contact') }}</p>
                                        <p class="font-medium text-body-text dark:text-body-text-dark">(555) 123-4567
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Prescription Details -->
                            <div
                                class="rounded-xl border border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <h2 class="text-xl font-bold text-body-text dark:text-body-text-dark">
                                        {{ __('pharmacy.orders.prescription_details') }}</h2>
                                    <div class="flex gap-2">
                                        <button
                                            class="flex items-center gap-2 px-4 py-2 rounded-lg border border-border-light dark:border-border-dark text-sm font-medium text-body-text dark:text-body-text-dark hover:bg-background-light dark:hover:bg-background-dark">
                                            <span class="material-symbols-outlined text-lg">image</span>
                                            {{ __('pharmacy.orders.view_scan') }}
                                        </button>
                                        <button
                                            class="flex items-center gap-2 px-4 py-2 rounded-lg border border-border-light dark:border-border-dark text-sm font-medium text-body-text dark:text-body-text-dark hover:bg-background-light dark:hover:bg-background-dark">
                                            <span class="material-symbols-outlined text-lg">print</span>
                                            {{ __('pharmacy.orders.print_label') }}
                                        </button>
                                    </div>
                                </div>

                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-border-light dark:divide-border-dark">
                                        <thead>
                                            <tr>
                                                <th
                                                    class="py-3.5 px-6 text-left text-sm font-semibold text-body-text dark:text-body-text-dark">
                                                    {{ __('pharmacy.orders.medication') }}</th>
                                                <th
                                                    class="px-3 py-3.5 text-left text-sm font-semibold text-body-text dark:text-body-text-dark">
                                                    {{ __('pharmacy.orders.dosage') }}</th>
                                                <th
                                                    class="px-3 py-3.5 text-left text-sm font-semibold text-body-text dark:text-body-text-dark">
                                                    {{ __('pharmacy.orders.quantity') }}</th>
                                                <th
                                                    class="px-3 py-3.5 text-left text-sm font-semibold text-body-text dark:text-body-text-dark">
                                                    {{ __('pharmacy.orders.status_column') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-border-light dark:divide-border-dark">
                                            <tr>
                                                <td
                                                    class="whitespace-nowrap py-4 px-6 text-sm font-medium text-body-text dark:text-body-text-dark">
                                                    Amoxicillin</td>
                                                <td
                                                    class="whitespace-nowrap px-3 py-4 text-sm text-neutral-text dark:text-neutral-text-dark">
                                                    500mg</td>
                                                <td
                                                    class="whitespace-nowrap px-3 py-4 text-sm text-neutral-text dark:text-neutral-text-dark">
                                                    30 {{ __('prescription.upload_step2.capsules') }}</td>
                                                <td class="whitespace-nowrap px-3 py-4 text-sm">
                                                    <span
                                                        class="inline-flex items-center rounded-full bg-success/20 px-2 py-1 text-xs font-medium text-success">{{ __('pharmacy.inventory.stock_status.in_stock') }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td
                                                    class="whitespace-nowrap py-4 px-6 text-sm font-medium text-body-text dark:text-body-text-dark">
                                                    Ibuprofen</td>
                                                <td
                                                    class="whitespace-nowrap px-3 py-4 text-sm text-neutral-text dark:text-neutral-text-dark">
                                                    200mg</td>
                                                <td
                                                    class="whitespace-nowrap px-3 py-4 text-sm text-neutral-text dark:text-neutral-text-dark">
                                                    50 {{ __('prescription.upload_step2.tablets') }}</td>
                                                <td class="whitespace-nowrap px-3 py-4 text-sm">
                                                    <span
                                                        class="inline-flex items-center rounded-full bg-success/20 px-2 py-1 text-xs font-medium text-success">{{ __('pharmacy.inventory.stock_status.in_stock') }}</span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Footer Actions -->
                        <div
                            class="border-t border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark p-6 space-y-4">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-medium text-body-text dark:text-body-text-dark">
                                    {{ __('pharmacy.orders.update_status') }}</p>
                                <div class="flex items-center gap-2">
                                    <button
                                        class="px-4 py-2 rounded-lg border border-border-light dark:border-border-dark text-sm font-medium text-body-text dark:text-body-text-dark hover:bg-background-light dark:hover:bg-background-dark">
                                        {{ __('pharmacy.orders.acknowledge') }}
                                    </button>
                                    <button
                                        class="px-6 py-2 rounded-lg bg-primary text-white text-sm font-medium hover:bg-primary/90">
                                        {{ __('pharmacy.orders.start_preparing') }}
                                    </button>
                                </div>
                            </div>

                            <div class="relative">
                                <label for="patient-message"
                                    class="sr-only">{{ __('pharmacy.orders.message_patient') }}</label>
                                <input type="text" id="patient-message"
                                    placeholder="{{ __('pharmacy.orders.message_placeholder') }}"
                                    class="w-full h-12 pl-4 pr-28 rounded-lg border border-border-light dark:border-border-dark bg-background-light dark:bg-background-dark text-body-text dark:text-body-text-dark placeholder:text-neutral-text dark:placeholder:text-neutral-text-dark focus:ring-2 focus:ring-primary focus:border-transparent">
                                <button
                                    class="absolute right-1.5 top-1.5 h-9 px-4 rounded-md bg-primary text-white text-sm font-medium hover:bg-primary/90">
                                    {{ __('pharmacy.orders.send') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
