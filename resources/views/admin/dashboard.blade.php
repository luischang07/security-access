<!DOCTYPE html>
<html class="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('admin.dashboard.title') }} - Te Acerco Salud</title>
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

            @include('components.topbar', ['user' => auth()->user(), 'type' => 'admin'])

            <div class="flex flex-1">
                @include('components.sidebar', [
                    'user' => auth()->user(),
                    'type' => 'admin',
                    'currentRoute' => 'admin.dashboard',
                ])

                <main class="flex-1 p-4 sm:p-6 lg:p-10">
                    <div class="mx-auto max-w-7xl space-y-8">

                        <!-- Page Header -->
                        <div class="flex flex-wrap items-center justify-between gap-4">
                            <h1
                                class="text-4xl font-black leading-tight tracking-[-0.033em] text-body-text dark:text-body-text-dark">
                                {{ __('admin.dashboard.title') }}
                            </h1>
                        </div>

                        <!-- Stats Grid -->
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                            <div
                                class="flex flex-col gap-2 rounded-xl bg-card-light dark:bg-card-dark p-6 shadow-sm border border-border-light dark:border-border-dark">
                                <p class="text-base font-medium text-neutral-text dark:text-neutral-text-dark">
                                    {{ __('admin.dashboard.total_patients') }}</p>
                                <p class="text-4xl font-bold text-body-text dark:text-body-text-dark">1,428</p>
                                <p class="text-sm font-medium text-success flex items-center gap-1">
                                    <span class="material-symbols-outlined text-base">arrow_upward</span> +2.5%
                                </p>
                            </div>

                            <div
                                class="flex flex-col gap-2 rounded-xl bg-card-light dark:bg-card-dark p-6 shadow-sm border border-border-light dark:border-border-dark">
                                <p class="text-base font-medium text-neutral-text dark:text-neutral-text-dark">
                                    {{ __('admin.dashboard.active_pharmacies') }}</p>
                                <p class="text-4xl font-bold text-body-text dark:text-body-text-dark">86</p>
                                <p class="text-sm font-medium text-success flex items-center gap-1">
                                    <span class="material-symbols-outlined text-base">arrow_upward</span> +1.2%
                                </p>
                            </div>

                            <div
                                class="flex flex-col gap-2 rounded-xl bg-card-light dark:bg-card-dark p-6 shadow-sm border border-border-light dark:border-border-dark">
                                <p class="text-base font-medium text-neutral-text dark:text-neutral-text-dark">
                                    {{ __('admin.dashboard.prescriptions_today') }}</p>
                                <p class="text-4xl font-bold text-body-text dark:text-body-text-dark">152</p>
                                <p class="text-sm font-medium text-success flex items-center gap-1">
                                    <span class="material-symbols-outlined text-base">arrow_upward</span> +5.0%
                                </p>
                            </div>

                            <div
                                class="flex flex-col gap-2 rounded-xl bg-card-light dark:bg-card-dark p-6 shadow-sm border border-border-light dark:border-border-dark">
                                <p class="text-base font-medium text-neutral-text dark:text-neutral-text-dark">
                                    {{ __('admin.dashboard.avg_fulfillment') }}</p>
                                <p class="text-4xl font-bold text-body-text dark:text-body-text-dark">2.5h</p>
                                <p class="text-sm font-medium text-danger flex items-center gap-1">
                                    <span class="material-symbols-outlined text-base">arrow_downward</span> -3.0%
                                </p>
                            </div>
                        </div>

                        <!-- Main Content Grid -->
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                            <!-- Chart Card -->
                            <div
                                class="lg:col-span-2 flex flex-col gap-4 rounded-xl border border-border-light dark:border-border-dark p-6 bg-card-light dark:bg-card-dark shadow-sm">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <h2 class="text-xl font-bold text-body-text dark:text-body-text-dark">
                                            {{ __('admin.dashboard.new_registrations') }}</h2>
                                        <p class="text-sm text-neutral-text dark:text-neutral-text-dark">
                                            {{ __('admin.dashboard.last_30_days') }}</p>
                                    </div>
                                    <div class="flex items-baseline gap-2">
                                        <p class="text-2xl font-bold text-body-text dark:text-body-text-dark">180</p>
                                        <p class="text-sm font-medium text-success">+15%</p>
                                    </div>
                                </div>
                                <div
                                    class="flex min-h-[220px] flex-1 items-center justify-center bg-background-light dark:bg-background-dark rounded-lg">
                                    <svg fill="none" height="200" preserveAspectRatio="none" viewBox="0 0 500 200"
                                        width="100%" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M0 150 L50 120 L100 130 L150 90 L200 100 L250 70 L300 85 L350 60 L400 75 L450 50 L500 40"
                                            stroke="#137fec" stroke-width="3" fill="none" stroke-linecap="round" />
                                        <path
                                            d="M0 150 L50 120 L100 130 L150 90 L200 100 L250 70 L300 85 L350 60 L400 75 L450 50 L500 40 L500 200 L0 200 Z"
                                            fill="url(#gradient)" opacity="0.3" />
                                        <defs>
                                            <linearGradient id="gradient" x1="0" y1="0" x2="0"
                                                y2="1">
                                                <stop offset="0%" stop-color="#137fec" stop-opacity="0.4" />
                                                <stop offset="100%" stop-color="#137fec" stop-opacity="0" />
                                            </linearGradient>
                                        </defs>
                                    </svg>
                                </div>
                            </div>

                            <!-- Quick Actions Card -->
                            <div class="flex flex-col gap-4">
                                <div
                                    class="flex flex-col gap-4 rounded-xl border border-border-light dark:border-border-dark p-6 bg-card-light dark:bg-card-dark shadow-sm">
                                    <h3 class="text-lg font-bold text-body-text dark:text-body-text-dark">
                                        {{ __('admin.dashboard.quick_actions') }}</h3>
                                    <div class="flex flex-col gap-2">
                                        <a href="{{ route('admin.users') }}"
                                            class="flex items-center gap-3 p-3 rounded-lg hover:bg-background-light dark:hover:bg-background-dark transition">
                                            <span class="material-symbols-outlined text-primary">group</span>
                                            <p class="text-sm font-medium text-body-text dark:text-body-text-dark">
                                                {{ __('admin.dashboard.manage_users') }}</p>
                                        </a>
                                        <a href="{{ route('admin.pharmacies') }}"
                                            class="flex items-center gap-3 p-3 rounded-lg hover:bg-background-light dark:hover:bg-background-dark transition">
                                            <span class="material-symbols-outlined text-primary">storefront</span>
                                            <p class="text-sm font-medium text-body-text dark:text-body-text-dark">
                                                {{ __('admin.dashboard.manage_pharmacies') }}</p>
                                        </a>
                                        <a href="{{ route('admin.reports') }}"
                                            class="flex items-center gap-3 p-3 rounded-lg hover:bg-background-light dark:hover:bg-background-dark transition">
                                            <span class="material-symbols-outlined text-primary">assessment</span>
                                            <p class="text-sm font-medium text-body-text dark:text-body-text-dark">
                                                {{ __('admin.dashboard.view_reports') }}</p>
                                        </a>
                                        <a href="{{ route('admin.penalties') }}"
                                            class="flex items-center gap-3 p-3 rounded-lg hover:bg-background-light dark:hover:bg-background-dark transition">
                                            <span class="material-symbols-outlined text-primary">gavel</span>
                                            <p class="text-sm font-medium text-body-text dark:text-body-text-dark">
                                                {{ __('admin.dashboard.manage_penalties') }}</p>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Activity -->
                        <div
                            class="bg-card-light dark:bg-card-dark rounded-xl border border-border-light dark:border-border-dark shadow-sm">
                            <div
                                class="flex justify-between items-center p-6 border-b border-border-light dark:border-border-dark">
                                <h2 class="text-xl font-bold text-body-text dark:text-body-text-dark">
                                    {{ __('admin.dashboard.recent_activity') }}</h2>
                                <a href="#"
                                    class="text-sm font-bold text-primary hover:underline">{{ __('common.actions.view_all') }}</a>
                            </div>
                            <div class="p-6">
                                <div class="space-y-4">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <div
                                            class="flex items-start gap-4 p-4 rounded-lg border border-border-light dark:border-border-dark">
                                            <div
                                                class="h-10 w-10 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                                                <span class="material-symbols-outlined text-primary">
                                                    @if ($i == 1)
                                                        person_add
                                                    @elseif($i == 2)
                                                        storefront
                                                    @elseif($i == 3)
                                                        receipt_long
                                                    @elseif($i == 4)
                                                        warning
                                                    @else
                                                        settings
                                                    @endif
                                                </span>
                                            </div>
                                            <div class="flex-1">
                                                <h3 class="font-bold text-body-text dark:text-body-text-dark">
                                                    @if ($i == 1)
                                                        {{ __('admin.dashboard.activity.new_user') }}
                                                    @elseif($i == 2)
                                                        {{ __('admin.dashboard.activity.pharmacy_registered') }}
                                                    @elseif($i == 3)
                                                        {{ __('admin.dashboard.activity.order_completed') }}
                                                    @elseif($i == 4)
                                                        {{ __('admin.dashboard.activity.penalty_issued') }}
                                                    @else
                                                        {{ __('admin.dashboard.activity.system_update') }}
                                                    @endif
                                                </h3>
                                                <p class="text-sm text-neutral-text dark:text-neutral-text-dark">
                                                    {{ __('admin.dashboard.activity.time_ago', ['time' => $i * 5]) }}
                                                </p>
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
