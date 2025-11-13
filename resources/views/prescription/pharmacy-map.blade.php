<!DOCTYPE html>
<html class="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('prescription.pharmacy_map.title') }} - Te Acerco Salud</title>
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
                        "danger": "#ff6b6b",
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
    <div class="relative flex min-h-screen w-full flex-col group/design-root overflow-x-hidden">

        <!-- Top Navigation Bar -->
        <header
            class="flex items-center justify-between whitespace-nowrap border-b border-solid border-border-light dark:border-border-dark px-6 md:px-10 py-3 bg-card-light dark:bg-card-dark backdrop-blur-sm sticky top-0 z-50">
            <div class="flex items-center gap-4 text-body-text dark:text-body-text-dark">
                <div class="size-6 text-primary">
                    <svg fill="none" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 4H17.3334V17.3334H30.6666V30.6666H44V44H4V4Z" fill="currentColor"></path>
                    </svg>
                </div>
                <h2 class="text-lg font-bold leading-tight tracking-[-0.015em]">Te Acerco Salud</h2>
            </div>
            <nav class="hidden lg:flex flex-1 justify-center gap-8">
                <a class="text-body-text dark:text-body-text-dark text-sm font-medium leading-normal hover:text-primary transition"
                    href="{{ route('patient.dashboard') }}">{{ __('common.navbar.home') }}</a>
                <a class="text-body-text dark:text-body-text-dark text-sm font-medium leading-normal hover:text-primary transition"
                    href="{{ route('patient.orders') }}">{{ __('patient.orders.title') }}</a>
                <a class="text-primary text-sm font-bold leading-normal"
                    href="#">{{ __('prescription.pharmacy_map.title') }}</a>
                <a class="text-body-text dark:text-body-text-dark text-sm font-medium leading-normal hover:text-primary transition"
                    href="{{ route('patient.profile') }}">{{ __('patient.dashboard.sidebar.profile') }}</a>
            </nav>
            <div class="flex items-center gap-4">
                <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10"
                    style='background-image: url("https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'User') }}&background=137fec&color=fff");'>
                </div>
                <button class="lg:hidden text-body-text dark:text-body-text-dark">
                    <span class="material-symbols-outlined">menu</span>
                </button>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex flex-1 flex-col lg:flex-row">

            <!-- Left Panel: Search, Filters, and List -->
            <aside
                class="w-full lg:w-[420px] lg:min-w-[420px] flex flex-col bg-card-light dark:bg-card-dark lg:border-r border-border-light dark:border-border-dark p-4 sm:p-6 space-y-4">

                <!-- Page Heading -->
                <div class="flex flex-wrap justify-between gap-3">
                    <div class="flex flex-col gap-1">
                        <h1
                            class="text-body-text dark:text-body-text-dark text-3xl font-black leading-tight tracking-[-0.033em]">
                            {{ __('prescription.pharmacy_map.heading') }}
                        </h1>
                        <p class="text-neutral-text dark:text-neutral-text-dark text-base font-normal leading-normal">
                            {{ __('prescription.pharmacy_map.subtitle') }}
                        </p>
                    </div>
                </div>

                <!-- Search Bar -->
                <div class="relative flex items-center">
                    <label class="flex flex-col h-12 w-full">
                        <div class="flex w-full flex-1 items-stretch rounded-lg h-full">
                            <div
                                class="text-neutral-text dark:text-neutral-text-dark flex bg-background-light dark:bg-background-dark items-center justify-center pl-4 rounded-l-lg">
                                <span class="material-symbols-outlined text-xl">search</span>
                            </div>
                            <input
                                class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-r-lg text-body-text dark:text-body-text-dark focus:outline-0 focus:ring-2 focus:ring-primary/50 border-none bg-background-light dark:bg-background-dark h-full placeholder:text-neutral-text dark:placeholder:text-neutral-text-dark px-4 text-base font-normal leading-normal"
                                placeholder="{{ __('prescription.pharmacy_map.search_placeholder') }}" />
                        </div>
                    </label>
                    <button
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-neutral-text dark:text-neutral-text-dark hover:text-primary transition-colors">
                        <span class="material-symbols-outlined">my_location</span>
                    </button>
                </div>

                <!-- Filter Chips -->
                <div class="flex gap-2 py-2 overflow-x-auto">
                    <button
                        class="flex h-9 shrink-0 items-center justify-center gap-x-2 rounded-lg bg-background-light dark:bg-background-dark pl-3 pr-3 text-body-text dark:text-body-text-dark hover:bg-border-light dark:hover:bg-border-dark transition-colors">
                        <span class="material-symbols-outlined text-lg">swap_vert</span>
                        <p class="text-sm font-medium leading-normal">Sort by distance</p>
                    </button>
                    <button
                        class="flex h-9 shrink-0 items-center justify-center gap-x-2 rounded-lg bg-primary/20 pl-3 pr-3 text-primary font-bold">
                        <span class="material-symbols-outlined text-lg">schedule</span>
                        <p class="text-sm leading-normal">{{ __('prescription.pharmacy_map.filters.open_now') }}</p>
                    </button>
                    <button
                        class="flex h-9 shrink-0 items-center justify-center gap-x-2 rounded-lg bg-background-light dark:bg-background-dark pl-3 pr-3 text-body-text dark:text-body-text-dark hover:bg-border-light dark:hover:bg-border-dark transition-colors">
                        <span class="material-symbols-outlined text-lg">history</span>
                        <p class="text-sm font-medium leading-normal">
                            {{ __('prescription.pharmacy_map.filters.24_hours') }}</p>
                    </button>
                </div>

                <!-- Pharmacy List -->
                <div class="flex-1 overflow-y-auto space-y-3 pr-1">

                    <!-- Pharmacy Card 1 (Selected) -->
                    <div
                        class="p-4 rounded-xl border-2 border-primary bg-primary/10 dark:bg-primary/20 cursor-pointer transition-all hover:shadow-lg">
                        <div class="flex justify-between items-start gap-3">
                            <div>
                                <h3 class="font-bold text-body-text dark:text-body-text-dark">Farmacia del Ahorro</h3>
                                <p class="text-sm text-neutral-text dark:text-neutral-text-dark">Av. Insurgentes Sur
                                    123, Roma Nte.</p>
                                <p class="text-sm text-neutral-text dark:text-neutral-text-dark mt-1">1.2 km away</p>
                            </div>
                            <div class="text-sm font-semibold text-success bg-success/20 px-2 py-1 rounded-full">
                                {{ __('prescription.pharmacy_map.pharmacy_card.open') }}
                            </div>
                        </div>
                        <p class="text-sm text-neutral-text dark:text-neutral-text-dark mt-2">Closes at 9:00 PM</p>
                    </div>

                    <!-- Pharmacy Card 2 -->
                    <div
                        class="p-4 rounded-xl border border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark hover:border-primary/50 hover:bg-background-light dark:hover:bg-background-dark cursor-pointer transition-all">
                        <div class="flex justify-between items-start gap-3">
                            <div>
                                <h3 class="font-bold text-body-text dark:text-body-text-dark">Farmacias San Pablo</h3>
                                <p class="text-sm text-neutral-text dark:text-neutral-text-dark">Calle de Durango 200,
                                    Condesa</p>
                                <p class="text-sm text-neutral-text dark:text-neutral-text-dark mt-1">2.5 km away</p>
                            </div>
                            <div class="text-sm font-semibold text-success bg-success/20 px-2 py-1 rounded-full">
                                {{ __('prescription.pharmacy_map.pharmacy_card.open') }}
                            </div>
                        </div>
                        <p class="text-sm text-neutral-text dark:text-neutral-text-dark mt-2">Open 24 Hours</p>
                    </div>

                    <!-- Pharmacy Card 3 (Closed) -->
                    <div
                        class="p-4 rounded-xl border border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark hover:border-primary/50 hover:bg-background-light dark:hover:bg-background-dark cursor-pointer transition-all">
                        <div class="flex justify-between items-start gap-3">
                            <div>
                                <h3 class="font-bold text-body-text dark:text-body-text-dark">Farmacia Guadalajara</h3>
                                <p class="text-sm text-neutral-text dark:text-neutral-text-dark">Av. Revolución 550, San
                                    Pedro</p>
                                <p class="text-sm text-neutral-text dark:text-neutral-text-dark mt-1">3.1 km away</p>
                            </div>
                            <div class="text-sm font-semibold text-danger bg-danger/20 px-2 py-1 rounded-full">
                                {{ __('prescription.pharmacy_map.pharmacy_card.closed') }}
                            </div>
                        </div>
                        <p class="text-sm text-neutral-text dark:text-neutral-text-dark mt-2">Opens at 8:00 AM</p>
                    </div>

                    <!-- Pharmacy Card 4 -->
                    <div
                        class="p-4 rounded-xl border border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark hover:border-primary/50 hover:bg-background-light dark:hover:bg-background-dark cursor-pointer transition-all">
                        <div class="flex justify-between items-start gap-3">
                            <div>
                                <h3 class="font-bold text-body-text dark:text-body-text-dark">Farmacia Benavides</h3>
                                <p class="text-sm text-neutral-text dark:text-neutral-text-dark">Blvd. Miguel de
                                    Cervantes 789</p>
                                <p class="text-sm text-neutral-text dark:text-neutral-text-dark mt-1">4.2 km away</p>
                            </div>
                            <div class="text-sm font-semibold text-success bg-success/20 px-2 py-1 rounded-full">
                                {{ __('prescription.pharmacy_map.pharmacy_card.open') }}
                            </div>
                        </div>
                        <p class="text-sm text-neutral-text dark:text-neutral-text-dark mt-2">Closes at 10:00 PM</p>
                    </div>

                </div>
            </aside>

            <!-- Right Panel: Map and Detail Modal -->
            <div class="flex-1 relative h-[50vh] lg:h-auto">
                <!-- Map Placeholder -->
                <div class="w-full h-full bg-center bg-no-repeat bg-cover object-cover"
                    style='background-image: url("https://api.mapbox.com/styles/v1/mapbox/streets-v11/static/-99.1332,19.4326,12,0/1200x800@2x?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw");'>
                </div>

                <!-- Pharmacy Detail Modal (floating on map) -->
                <div
                    class="absolute bottom-0 left-0 right-0 p-4 sm:p-6 lg:m-6 lg:max-w-md lg:left-auto lg:bottom-auto lg:top-0">
                    <div class="bg-card-light dark:bg-card-dark rounded-xl shadow-2xl p-6 space-y-4">

                        <!-- Pharmacy Header -->
                        <div class="flex items-center gap-4">
                            <div class="h-12 w-12 bg-primary/10 rounded-lg flex items-center justify-center">
                                <span class="material-symbols-outlined text-2xl text-primary">local_pharmacy</span>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-body-text dark:text-body-text-dark">Farmacia del
                                    Ahorro</h2>
                                <p class="text-sm text-neutral-text dark:text-neutral-text-dark">Av. Insurgentes Sur
                                    123, Roma Nte.</p>
                            </div>
                        </div>

                        <!-- Operating Hours -->
                        <div class="border-t border-border-light dark:border-border-dark pt-4">
                            <h4 class="font-semibold text-body-text dark:text-body-text-dark mb-2">Operating Hours</h4>
                            <div class="text-sm text-neutral-text dark:text-neutral-text-dark space-y-1">
                                <div class="flex justify-between">
                                    <span class="font-medium text-body-text dark:text-body-text-dark">Today</span>
                                    <span>8:00 AM – 9:00 PM</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Mon - Fri</span>
                                    <span>8:00 AM – 9:00 PM</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Sat - Sun</span>
                                    <span>9:00 AM – 6:00 PM</span>
                                </div>
                            </div>
                        </div>

                        <!-- Contact -->
                        <div class="border-t border-border-light dark:border-border-dark pt-4">
                            <h4 class="font-semibold text-body-text dark:text-body-text-dark mb-2">Contact</h4>
                            <p class="text-sm text-neutral-text dark:text-neutral-text-dark">+52 55 1234 5678</p>
                        </div>

                        <!-- Stock Information -->
                        <div class="border-t border-border-light dark:border-border-dark pt-4">
                            <h4 class="font-semibold text-body-text dark:text-body-text-dark mb-2">Stock Information
                            </h4>
                            <div class="flex items-center gap-2 text-sm">
                                <span class="material-symbols-outlined text-success">check_circle</span>
                                <span
                                    class="text-body-text dark:text-body-text-dark">{{ __('prescription.pharmacy_map.pharmacy_card.in_stock') }}</span>
                            </div>
                        </div>

                        <!-- Select Button -->
                        <div class="pt-4">
                            <button
                                class="flex w-full min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-4 bg-primary text-white text-base font-bold leading-normal tracking-[0.015em] hover:bg-primary/90 transition-colors">
                                <span
                                    class="truncate">{{ __('prescription.pharmacy_map.pharmacy_card.select') }}</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>

</html>
