<!DOCTYPE html>
<html class="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('prescription.upload_step1.title') }} - Te Acerco Salud</title>
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
                                    {{ __('prescription.upload_step1.dashboard') }}
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
                        <div class="flex flex-wrap justify-between gap-3 px-4">
                            <div class="flex flex-col gap-2">
                                <h1
                                    class="text-3xl lg:text-4xl font-black tracking-[-0.033em] text-body-text dark:text-body-text-dark">
                                    {{ __('prescription.upload_step1.heading') }}
                                </h1>
                                <p
                                    class="text-base font-normal leading-normal text-neutral-text dark:text-neutral-text-dark">
                                    {{ __('prescription.upload_step1.subtitle') }}
                                </p>
                            </div>
                        </div>

                        <!-- File Upload Area -->
                        <div class="flex flex-col p-4">
                            <div
                                class="flex flex-col items-center gap-6 rounded-xl border-2 border-dashed border-border-light dark:border-border-dark px-6 py-14 bg-card-light dark:bg-card-dark">
                                <div class="flex flex-col items-center gap-2 text-center">
                                    <div
                                        class="flex justify-center items-center size-16 bg-primary/10 rounded-full mb-2">
                                        <span class="material-symbols-outlined text-3xl text-primary">upload_file</span>
                                    </div>
                                    <p
                                        class="text-lg font-bold leading-tight tracking-[-0.015em] text-body-text dark:text-body-text-dark">
                                        {{ __('prescription.upload_step1.drag_drop') }}
                                    </p>
                                    <p
                                        class="text-sm font-normal leading-normal text-neutral-text dark:text-neutral-text-dark">
                                        {{ __('prescription.upload_step1.accepted_formats') }}
                                    </p>
                                </div>
                                <button
                                    class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-5 bg-body-text dark:bg-body-text-dark text-white dark:text-body-text text-sm font-bold leading-normal tracking-[0.015em] hover:bg-body-text/90 dark:hover:bg-body-text-dark/90 transition">
                                    <span class="truncate">{{ __('prescription.upload_step1.browse_files') }}</span>
                                </button>
                            </div>
                        </div>

                        <!-- Manual Entry Link -->
                        <p class="text-sm font-normal leading-normal pb-3 pt-1 px-4 text-center">
                            <a class="underline text-neutral-text dark:text-neutral-text-dark hover:text-primary transition"
                                href="#">
                                {{ __('prescription.upload_step1.or_manual') }}
                            </a>
                        </p>

                        <!-- Manual Entry Form -->
                        <div class="flex flex-col gap-6 p-4">
                            <!-- Section Header -->
                            <h2
                                class="text-[22px] font-bold leading-tight tracking-[-0.015em] px-0 pb-0 pt-0 text-body-text dark:text-body-text-dark">
                                {{ __('prescription.upload_step1.prescription_details') }}
                            </h2>

                            <!-- Patient and Doctor Info -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label
                                        class="block text-sm font-medium text-body-text dark:text-body-text-dark mb-1.5"
                                        for="patient-name">
                                        {{ __('prescription.upload_step1.patient_name') }}
                                    </label>
                                    <input
                                        class="w-full rounded-lg border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark focus:border-primary focus:ring-primary/50"
                                        id="patient-name"
                                        placeholder="{{ __('prescription.upload_step1.patient_name_placeholder') }}"
                                        type="text" />
                                </div>
                                <div>
                                    <label
                                        class="block text-sm font-medium text-body-text dark:text-body-text-dark mb-1.5"
                                        for="doctor-name">
                                        {{ __('prescription.upload_step1.doctor_name') }}
                                    </label>
                                    <input
                                        class="w-full rounded-lg border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark focus:border-primary focus:ring-primary/50"
                                        id="doctor-name"
                                        placeholder="{{ __('prescription.upload_step1.doctor_name_placeholder') }}"
                                        type="text" />
                                </div>
                            </div>

                            <!-- Medications Section -->
                            <div class="flex flex-col gap-4">
                                <h3 class="text-lg font-bold text-body-text dark:text-body-text-dark">
                                    {{ __('prescription.upload_step1.medications') }}
                                </h3>

                                <!-- Medication Row 1 -->
                                <div
                                    class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-end p-4 border border-border-light dark:border-border-dark rounded-lg bg-card-light dark:bg-card-dark">
                                    <div class="lg:col-span-5">
                                        <label
                                            class="block text-sm font-medium text-body-text dark:text-body-text-dark mb-1.5"
                                            for="medication-1">
                                            {{ __('prescription.upload_step1.medication_name') }}
                                        </label>
                                        <input
                                            class="w-full rounded-lg border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark focus:border-primary focus:ring-primary/50"
                                            id="medication-1"
                                            placeholder="{{ __('prescription.upload_step1.medication_name_placeholder') }}"
                                            type="text" />
                                    </div>
                                    <div class="lg:col-span-3">
                                        <label
                                            class="block text-sm font-medium text-body-text dark:text-body-text-dark mb-1.5"
                                            for="dosage-1">
                                            {{ __('prescription.upload_step1.dosage') }}
                                        </label>
                                        <input
                                            class="w-full rounded-lg border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark focus:border-primary focus:ring-primary/50"
                                            id="dosage-1"
                                            placeholder="{{ __('prescription.upload_step1.dosage_placeholder') }}"
                                            type="text" />
                                    </div>
                                    <div class="lg:col-span-3">
                                        <label
                                            class="block text-sm font-medium text-body-text dark:text-body-text-dark mb-1.5"
                                            for="quantity-1">
                                            {{ __('prescription.upload_step1.quantity') }}
                                        </label>
                                        <input
                                            class="w-full rounded-lg border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark focus:border-primary focus:ring-primary/50"
                                            id="quantity-1"
                                            placeholder="{{ __('prescription.upload_step1.quantity_placeholder') }}"
                                            type="number" />
                                    </div>
                                    <div class="lg:col-span-1">
                                        <button
                                            class="w-full flex items-center justify-center h-10 rounded-lg bg-transparent text-neutral-text dark:text-neutral-text-dark hover:bg-red-500/10 hover:text-red-500 transition">
                                            <span class="material-symbols-outlined text-xl">delete</span>
                                        </button>
                                    </div>
                                </div>

                                <!-- Add Medication Button -->
                                <button
                                    class="flex items-center gap-2 self-start rounded-lg h-10 px-4 bg-primary/10 dark:bg-primary/20 text-primary text-sm font-bold hover:bg-primary/20 dark:hover:bg-primary/30 transition">
                                    <span class="material-symbols-outlined text-xl">add</span>
                                    <span>{{ __('prescription.upload_step1.add_medication') }}</span>
                                </button>
                            </div>

                            <!-- Special Instructions -->
                            <div>
                                <label class="block text-sm font-medium text-body-text dark:text-body-text-dark mb-1.5"
                                    for="notes">
                                    {{ __('prescription.upload_step1.special_instructions') }}
                                </label>
                                <textarea
                                    class="w-full rounded-lg border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark focus:border-primary focus:ring-primary/50"
                                    id="notes" placeholder="{{ __('prescription.upload_step1.special_instructions_placeholder') }}"
                                    rows="4"></textarea>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end p-4 mt-4 border-t border-border-light dark:border-border-dark">
                            <button
                                class="flex w-full md:w-auto items-center justify-center gap-2 rounded-lg h-12 px-8 bg-primary text-white text-base font-bold tracking-wide disabled:bg-neutral-text disabled:cursor-not-allowed hover:bg-primary/90 transition"
                                disabled>
                                <span>{{ __('prescription.upload_step1.submit') }}</span>
                                <span class="material-symbols-outlined">arrow_forward</span>
                            </button>
                        </div>
                    </main>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
