<!DOCTYPE html>
<html class="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('prescription.upload_step2.title') }} - Te Acerco Salud</title>
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
</head>

<body class="font-display bg-background-light dark:bg-background-dark text-body-text dark:text-body-text-dark">
    <div class="relative flex min-h-screen w-full flex-col group/design-root overflow-x-hidden">
        <div class="layout-container flex h-full grow flex-col">
            <div class="px-4 md:px-10 lg:px-40 flex flex-1 justify-center py-5">
                <div class="layout-content-container flex flex-col w-full max-w-4xl flex-1">

                    <!-- Top Navigation Bar -->
                    <header
                        class="flex items-center justify-between whitespace-nowrap border-b border-solid border-border-light dark:border-border-dark px-4 sm:px-10 py-3 bg-card-light dark:bg-card-dark rounded-xl mb-8">
                        <div class="flex items-center gap-4 text-body-text dark:text-body-text-dark">
                            <div class="size-6 text-primary">
                                <svg fill="none" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M4 4H17.3334V17.3334H30.6666V30.6666H44V44H4V4Z" fill="currentColor">
                                    </path>
                                </svg>
                            </div>
                            <h2 class="text-lg font-bold leading-tight tracking-[-0.015em]">Te Acerco Salud</h2>
                        </div>
                        <div class="flex flex-1 justify-end gap-4 sm:gap-8">
                            <div class="hidden sm:flex items-center gap-9">
                                <a class="text-sm font-medium leading-normal text-body-text dark:text-body-text-dark hover:text-primary transition"
                                    href="{{ route('patient.dashboard') }}">
                                    Dashboard
                                </a>
                            </div>
                            <button
                                class="flex max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 bg-background-light dark:bg-background-dark text-body-text dark:text-body-text-dark gap-2 text-sm font-bold leading-normal tracking-[0.015em] min-w-0 px-2.5 hover:bg-border-light dark:hover:bg-border-dark transition">
                                <span class="material-symbols-outlined text-xl">help</span>
                            </button>
                            <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10"
                                style='background-image: url("https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'User') }}&background=137fec&color=fff");'>
                            </div>
                        </div>
                    </header>

                    <main class="flex flex-col gap-6">
                        <!-- Page Heading -->
                        <div class="flex flex-col gap-2 px-4">
                            <h1
                                class="text-3xl lg:text-4xl font-black tracking-[-0.033em] text-body-text dark:text-body-text-dark">
                                {{ __('prescription.upload_step2.heading') }}
                            </h1>
                            <p
                                class="text-base font-normal leading-normal text-neutral-text dark:text-neutral-text-dark">
                                {{ __('prescription.upload_step2.subtitle') }}
                            </p>
                        </div>

                        <!-- Order Summary Card -->
                        <div class="flex flex-col gap-8 p-4 md:p-6 bg-card-light dark:bg-card-dark rounded-xl">

                            <!-- Pharmacy and Pickup Info -->
                            <div
                                class="grid grid-cols-1 md:grid-cols-2 gap-6 p-4 rounded-lg bg-background-light dark:bg-background-dark">
                                <div class="flex items-start gap-4">
                                    <span
                                        class="material-symbols-outlined text-2xl text-primary mt-1">local_pharmacy</span>
                                    <div class="flex flex-col">
                                        <h3 class="text-sm font-medium text-neutral-text dark:text-neutral-text-dark">
                                            {{ __('prescription.upload_step2.selected_pharmacy') }}
                                        </h3>
                                        <p class="text-base font-bold text-body-text dark:text-body-text-dark">
                                            Farmacia del Ahorro - Sucursal Centro
                                        </p>
                                        <p class="text-sm text-neutral-text dark:text-neutral-text-dark">
                                            Av. Siempre Viva 123, Springfield
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-4">
                                    <span class="material-symbols-outlined text-2xl text-primary mt-1">schedule</span>
                                    <div class="flex flex-col">
                                        <h3 class="text-sm font-medium text-neutral-text dark:text-neutral-text-dark">
                                            {{ __('prescription.upload_step2.estimated_pickup') }}
                                        </h3>
                                        <p class="text-base font-bold text-body-text dark:text-body-text-dark">
                                            Today, 2:30 PM - 3:00 PM
                                        </p>
                                        <p class="text-sm text-neutral-text dark:text-neutral-text-dark">
                                            {{ __('prescription.upload_step2.pickup_notification') }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Medications Table -->
                            <div class="flex flex-col">
                                <h3 class="text-lg font-bold text-body-text dark:text-body-text-dark mb-4">
                                    {{ __('prescription.upload_step2.prescribed_medications') }}
                                </h3>
                                <div class="flow-root">
                                    <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                                        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                                            <table
                                                class="min-w-full divide-y divide-border-light dark:divide-border-dark">
                                                <thead>
                                                    <tr>
                                                        <th class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-body-text dark:text-body-text-dark sm:pl-0"
                                                            scope="col">
                                                            {{ __('prescription.upload_step2.medication') }}
                                                        </th>
                                                        <th class="px-3 py-3.5 text-left text-sm font-semibold text-body-text dark:text-body-text-dark"
                                                            scope="col">
                                                            {{ __('prescription.upload_step2.quantity') }}
                                                        </th>
                                                        <th class="px-3 py-3.5 text-right text-sm font-semibold text-body-text dark:text-body-text-dark"
                                                            scope="col">
                                                            {{ __('prescription.upload_step2.price') }}
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-border-light dark:divide-border-dark">
                                                    <!-- Medication 1 -->
                                                    <tr>
                                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-0">
                                                            <div
                                                                class="font-medium text-body-text dark:text-body-text-dark">
                                                                Amoxicillin 500mg</div>
                                                            <div class="text-neutral-text dark:text-neutral-text-dark">
                                                                {{ __('prescription.upload_step2.capsules') }}</div>
                                                        </td>
                                                        <td
                                                            class="whitespace-nowrap px-3 py-4 text-sm text-neutral-text dark:text-neutral-text-dark">
                                                            30</td>
                                                        <td
                                                            class="whitespace-nowrap px-3 py-4 text-sm text-neutral-text dark:text-neutral-text-dark text-right">
                                                            $15.99</td>
                                                    </tr>
                                                    <!-- Medication 2 -->
                                                    <tr>
                                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-0">
                                                            <div
                                                                class="font-medium text-body-text dark:text-body-text-dark">
                                                                Ibuprofen 200mg</div>
                                                            <div class="text-neutral-text dark:text-neutral-text-dark">
                                                                {{ __('prescription.upload_step2.tablets') }}</div>
                                                        </td>
                                                        <td
                                                            class="whitespace-nowrap px-3 py-4 text-sm text-neutral-text dark:text-neutral-text-dark">
                                                            50</td>
                                                        <td
                                                            class="whitespace-nowrap px-3 py-4 text-sm text-neutral-text dark:text-neutral-text-dark text-right">
                                                            $8.50</td>
                                                    </tr>
                                                    <!-- Medication 3 -->
                                                    <tr>
                                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-0">
                                                            <div
                                                                class="font-medium text-body-text dark:text-body-text-dark">
                                                                Loratadine 10mg</div>
                                                            <div class="text-neutral-text dark:text-neutral-text-dark">
                                                                {{ __('prescription.upload_step2.tablets') }}</div>
                                                        </td>
                                                        <td
                                                            class="whitespace-nowrap px-3 py-4 text-sm text-neutral-text dark:text-neutral-text-dark">
                                                            20</td>
                                                        <td
                                                            class="whitespace-nowrap px-3 py-4 text-sm text-neutral-text dark:text-neutral-text-dark text-right">
                                                            $12.75</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Price Summary -->
                            <div
                                class="flex flex-col items-end gap-2 border-t border-border-light dark:border-border-dark pt-6">
                                <div class="flex justify-between w-full max-w-xs">
                                    <span
                                        class="text-sm text-neutral-text dark:text-neutral-text-dark">{{ __('prescription.upload_step2.subtotal') }}</span>
                                    <span
                                        class="text-sm font-medium text-body-text dark:text-body-text-dark">$37.24</span>
                                </div>
                                <div class="flex justify-between w-full max-w-xs">
                                    <span
                                        class="text-sm text-neutral-text dark:text-neutral-text-dark">{{ __('prescription.upload_step2.service_fee') }}</span>
                                    <span
                                        class="text-sm font-medium text-body-text dark:text-body-text-dark">$1.00</span>
                                </div>
                                <div
                                    class="flex justify-between w-full max-w-xs mt-2 pt-2 border-t border-dashed border-border-light dark:border-border-dark">
                                    <span
                                        class="text-lg font-bold text-body-text dark:text-body-text-dark">{{ __('prescription.upload_step2.estimated_total') }}</span>
                                    <span class="text-lg font-bold text-primary">$38.24</span>
                                </div>
                                <p
                                    class="text-xs text-neutral-text dark:text-neutral-text-dark mt-1 text-right max-w-xs">
                                    {{ __('prescription.upload_step2.price_disclaimer') }}
                                </p>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-col-reverse sm:flex-row justify-between items-center gap-4 p-4 mt-2">
                            <button
                                class="flex items-center justify-center gap-2 rounded-lg h-12 px-8 text-neutral-text dark:text-neutral-text-dark text-base font-bold tracking-wide hover:bg-background-light dark:hover:bg-background-dark transition">
                                <span class="material-symbols-outlined">arrow_back</span>
                                <span>{{ __('prescription.upload_step2.edit_prescription') }}</span>
                            </button>
                            <button
                                class="flex w-full sm:w-auto items-center justify-center gap-2 rounded-lg h-12 px-8 bg-primary text-white text-base font-bold tracking-wide hover:bg-primary/90 transition">
                                <span>{{ __('prescription.upload_step2.confirm_order') }}</span>
                                <span class="material-symbols-outlined">check_circle</span>
                            </button>
                        </div>
                    </main>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
