<!DOCTYPE html>
<html class="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('pharmacy.dashboard.title') }} - Te Acerco Salud</title>
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
                        "danger": "#D32F2F",
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
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
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
    <div class="relative flex h-auto min-h-screen w-full flex-col group/design-root overflow-x-hidden">
        <div class="layout-container flex h-full grow flex-col">

            @include('components.topbar', ['user' => auth()->user(), 'type' => 'pharmacy'])

            <div class="flex flex-1">
                @include('components.sidebar', [
                    'user' => auth()->user(),
                    'type' => 'pharmacy',
                    'currentRoute' => 'pharmacy.dashboard',
                ])

                <main class="flex-1 p-4 sm:p-6 lg:p-10">
                    <div class="mx-auto max-w-7xl space-y-8">

                        <!-- Page Header -->
                        <div class="flex flex-wrap items-center justify-between gap-4">
                            <h1
                                class="text-4xl font-black leading-tight tracking-[-0.033em] text-body-text dark:text-body-text-dark">
                                {{ __('pharmacy.dashboard.title') }}
                            </h1>
                            <div class="flex flex-wrap gap-3">
                                <button
                                    class="flex min-w-[84px] cursor-pointer items-center justify-center gap-2 overflow-hidden rounded-lg h-10 px-4 bg-primary text-white text-sm font-bold shadow-sm hover:bg-primary/90 transition">
                                    <span class="material-symbols-outlined text-base">add</span>
                                    <span class="truncate">{{ __('pharmacy.dashboard.new_order') }}</span>
                                </button>
                                <button
                                    class="flex min-w-[84px] cursor-pointer items-center justify-center gap-2 overflow-hidden rounded-lg h-10 px-4 bg-card-light dark:bg-card-dark text-body-text dark:text-body-text-dark border border-border-light dark:border-border-dark text-sm font-bold shadow-sm hover:bg-background-light dark:hover:bg-background-dark transition">
                                    <span class="material-symbols-outlined text-base">upload_file</span>
                                    <span class="truncate">{{ __('pharmacy.dashboard.export_report') }}</span>
                                </button>
                            </div>
                        </div>

                        <!-- Stats Grid -->
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                            <div
                                class="flex flex-col gap-2 rounded-xl bg-card-light dark:bg-card-dark p-6 shadow-sm border border-border-light dark:border-border-dark">
                                <div class="flex justify-between items-center">
                                    <p class="text-base font-medium text-neutral-text dark:text-neutral-text-dark">
                                        {{ __('pharmacy.dashboard.pending_orders') }}</p>
                                    <span class="material-symbols-outlined text-warning">pending_actions</span>
                                </div>
                                <p class="text-4xl font-bold text-body-text dark:text-body-text-dark">12</p>
                                <p class="text-sm font-medium text-secondary">+5% vs yesterday</p>
                            </div>

                            <div
                                class="flex flex-col gap-2 rounded-xl bg-card-light dark:bg-card-dark p-6 shadow-sm border border-border-light dark:border-border-dark">
                                <div class="flex justify-between items-center">
                                    <p class="text-base font-medium text-neutral-text dark:text-neutral-text-dark">
                                        {{ __('pharmacy.dashboard.completed_today') }}</p>
                                    <span class="material-symbols-outlined text-success">task_alt</span>
                                </div>
                                <p class="text-4xl font-bold text-body-text dark:text-body-text-dark">45</p>
                                <p class="text-sm font-medium text-success">+12% vs yesterday</p>
                            </div>

                            <div
                                class="flex flex-col gap-2 rounded-xl bg-card-light dark:bg-card-dark p-6 shadow-sm border border-border-light dark:border-border-dark">
                                <div class="flex justify-between items-center">
                                    <p class="text-base font-medium text-neutral-text dark:text-neutral-text-dark">
                                        {{ __('pharmacy.dashboard.revenue_today') }}</p>
                                    <span class="material-symbols-outlined text-primary">payments</span>
                                </div>
                                <p class="text-4xl font-bold text-body-text dark:text-body-text-dark">$3,240</p>
                                <p class="text-sm font-medium text-secondary">+8% vs yesterday</p>
                            </div>

                            <div
                                class="flex flex-col gap-2 rounded-xl bg-card-light dark:bg-card-dark p-6 shadow-sm border border-border-light dark:border-border-dark">
                                <div class="flex justify-between items-center">
                                    <p class="text-base font-medium text-neutral-text dark:text-neutral-text-dark">
                                        {{ __('pharmacy.dashboard.low_stock_items') }}</p>
                                    <span class="material-symbols-outlined text-danger">inventory</span>
                                </div>
                                <p class="text-4xl font-bold text-body-text dark:text-body-text-dark">8</p>
                                <p class="text-sm font-medium text-danger">
                                    {{ __('pharmacy.dashboard.requires_attention') }}</p>
                            </div>
                        </div>

                        <!-- Recent Orders -->
                        <div
                            class="bg-card-light dark:bg-card-dark rounded-xl border border-border-light dark:border-border-dark shadow-sm">
                            <div
                                class="flex justify-between items-center p-6 border-b border-border-light dark:border-border-dark">
                                <h2 class="text-xl font-bold text-body-text dark:text-body-text-dark">
                                    {{ __('pharmacy.dashboard.recent_orders') }}</h2>
                                <a href="{{ route('pharmacy.orders') }}"
                                    class="text-sm font-bold text-primary hover:underline">{{ __('common.actions.view_all') }}</a>
                            </div>
                            <div class="p-6">
                                <div class="space-y-4">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <div
                                            class="flex items-center justify-between p-4 rounded-lg border border-border-light dark:border-border-dark hover:bg-background-light dark:hover:bg-background-dark transition">
                                            <div class="flex items-center gap-4">
                                                <div
                                                    class="h-12 w-12 rounded-full bg-primary/10 flex items-center justify-center">
                                                    <span
                                                        class="material-symbols-outlined text-primary">receipt_long</span>
                                                </div>
                                                <div>
                                                    <h3 class="font-bold text-body-text dark:text-body-text-dark">Order
                                                        #TAS-{{ 1000 + $i }}</h3>
                                                    <p class="text-sm text-neutral-text dark:text-neutral-text-dark">
                                                        Patient {{ $i }} - {{ $i }} items</p>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <span
                                                    class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium
                                                @if ($i == 1) bg-warning/20 text-warning
                                                @elseif($i == 2) bg-success/20 text-success
                                                @else bg-primary/20 text-primary @endif">
                                                    @if ($i == 1)
                                                        {{ __('pharmacy.orders.status.pending') }}
                                                    @elseif($i == 2)
                                                        {{ __('pharmacy.orders.status.ready') }}
                                                    @else
                                                        {{ __('pharmacy.orders.status.processing') }}
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                    @endfor
                                </div>
                            </div>
                        </div>

                    </div>
                </main>
            </div>
        </div>
    </div>
</body>

</html>
