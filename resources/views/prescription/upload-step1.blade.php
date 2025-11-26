<!DOCTYPE html>
<html class="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
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
  @vite(['resources/js/patient/prescription-form.js'])
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
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
              <h2 class="text-lg font-bold leading-tight tracking-[-0.015em]">
                {{ __('prescription.upload_step1.brand_name') }}
              </h2>
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

          <main class="flex flex-col gap-6" x-data="prescriptionForm()">
            <!-- Page Heading -->
            <div class="flex flex-wrap justify-between gap-3 px-4">
              <div class="flex flex-col gap-2">
                <h1 class="text-3xl lg:text-4xl font-black tracking-[-0.033em] text-body-text dark:text-body-text-dark">
                  {{ __('prescription.upload_step1.page_heading') }}
                </h1>
                <p class="text-base font-normal leading-normal text-neutral-text dark:text-neutral-text-dark">
                  {{ __('prescription.upload_step1.page_subtitle') }}
                </p>
              </div>
            </div>
            <!-- Prescription Form -->
            <form id="prescription-form" method="POST" @submit="validateForm"
              action="{{ route('prescription.upload.step1.store') }}">
              @csrf

              <div class="mb-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div>
                    <label class="block text-sm font-medium text-body-text dark:text-body-text-dark mb-1.5"
                      for="cadena_id">
                      {{ __('prescription.upload_step1.select_chain') }}
                    </label>
                    <select
                      class="w-full rounded-lg border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark focus:border-primary focus:ring-primary/50"
                      id="cadena_id" name="cadena_id" x-model="cadenaId" required>
                      <option value="" selected disabled>
                        {{ __('prescription.upload_step1.select_option') }}
                      </option>
                      @foreach ($cadenas as $cadena)
                        <option value="{{ $cadena->cadena_id }}">{{ $cadena->nombre }}
                        </option>
                      @endforeach
                    </select>
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-body-text dark:text-body-text-dark mb-1.5"
                      for="sucursal_id">
                      {{ __('prescription.upload_step1.select_branch') }}
                    </label>
                    <select
                      class="w-full rounded-lg border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark focus:border-primary focus:ring-primary/50"
                      id="sucursal_id" name="sucursal_id" x-model="sucursalId"
                      :disabled="!cadenaId || loadingSucursales" required>
                      <option value="" selected disabled>
                        {{ __('prescription.upload_step1.select_option') }}
                      </option>
                      <template x-for="sucursal in sucursales" :key="sucursal.sucursal_id">
                        <option :value="sucursal.sucursal_id" x-text="sucursal.nombre || sucursal.sucursal_id"></option>
                      </template>
                    </select>
                  </div>
                </div>
              </div>
              <!-- Manual Entry Form -->
              <div class="flex flex-col gap-6 p-4">
                <!-- Section Header -->
                <h2
                  class="text-[22px] font-bold leading-tight tracking-[-0.015em] px-0 pb-0 pt-0 text-body-text dark:text-body-text-dark">
                  {{ __('prescription.upload_step1.prescription_details') }}
                </h2>

                <!-- Patient and Doctor Info -->


                <!-- Professional License -->
                <div>
                  <label class="block text-sm font-medium text-body-text dark:text-body-text-dark mb-1.5"
                    for="cedula_profesional">
                    {{ __('prescription.upload_step1.professional_license') }}
                  </label>
                  <input
                    class="w-full rounded-lg border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark focus:border-primary focus:ring-primary/50"
                    id="cedula_profesional" name="cedula_profesional"
                    placeholder="{{ __('prescription.upload_step1.professional_license_placeholder') }}" type="text"
                    x-model="professionalLicense" required />
                </div>

                <!-- Medications Section -->
                <div class="flex flex-col gap-4 border-t border-border-light dark:border-border-dark pt-6">
                  <h3 class="text-lg font-bold text-body-text dark:text-body-text-dark">
                    {{ __('prescription.upload_step1.medications') }}
                  </h3>

                  <!-- Add Medication Inputs -->
                  <div
                    class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end bg-gray-50 dark:bg-gray-800/50 p-4 rounded-lg">
                    <div class="md:col-span-8"
                      @medication-selected.window="medicationId = $event.detail.id; medicationName = $event.detail.name">
                      <label class="block text-sm font-medium text-body-text dark:text-body-text-dark mb-1.5">
                        {{ __('prescription.upload_step1.medication_name') }}
                      </label>
                      <!-- We bind x-model to 'medicationName' in the component via the input event -->
                      <x-medication-autocomplete @query-input="medicationName = $event.detail" />
                    </div>
                    <div class="md:col-span-4">
                      <label class="block text-sm font-medium text-body-text dark:text-body-text-dark mb-1.5">
                        {{ __('prescription.upload_step1.quantity') }}
                      </label>
                      <input type="number" x-model="medicationQuantity" min="1"
                        class="w-full rounded-lg border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark focus:border-primary focus:ring-primary/50"
                        placeholder="{{ __('prescription.upload_step1.quantity_placeholder') }}">
                    </div>
                    <div class="md:col-span-12 mt-2 flex justify-end">
                      <button type="button" @click="addMedication()"
                        class="w-full md:w-auto flex items-center justify-center gap-2 h-10 px-6 rounded-lg bg-primary text-white hover:bg-primary/90 transition font-medium">
                        <span class="material-symbols-outlined">add</span>
                        <span>{{ __('prescription.upload_step1.add_medication') }}</span>
                      </button>
                    </div>
                  </div>

                  <!-- Medications List Table -->
                  <div x-show="medicationsItems.length > 0" class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                      <thead>
                        <tr
                          class="border-b border-border-light dark:border-border-dark text-sm text-neutral-text dark:text-neutral-text-dark">
                          <th class="py-3 px-4">{{ __('prescription.upload_step1.medication_name') }}</th>
                          <th class="py-3 px-4">{{ __('prescription.upload_step1.quantity') }}</th>
                          <th class="py-3 px-4 text-center">Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        <template x-for="(item, index) in medicationsItems" :key="index">
                          <tr
                            class="border-b border-border-light dark:border-border-dark last:border-0 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition">
                            <td class="py-3 px-4 text-body-text dark:text-body-text-dark" x-text="item.name"></td>
                            <td class="py-3 px-4 text-body-text dark:text-body-text-dark">
                              <div class="flex items-center space-x-2">
                                <button type="button" @click="decrementQuantity(index)"
                                  class="size-8 flex items-center justify-center bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-full hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors"
                                  :disabled="item.quantity <= 1">
                                  <span class="material-symbols-outlined text-sm">remove</span>
                                </button>
                                <input type="number" :value="item.quantity"
                                  @input="updateQuantity(index, $event.target.value)" min="1"
                                  class="w-16 px-2 py-1 text-center border border-border-light dark:border-border-dark rounded focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-card-dark dark:text-body-text-dark" />
                                <button type="button" @click="incrementQuantity(index)"
                                  class="size-8 flex items-center justify-center bg-primary/10 text-primary rounded-full hover:bg-primary/20 transition-colors">
                                  <span class="material-symbols-outlined text-sm">add</span>
                                </button>
                              </div>
                            </td>
                            <td class="py-3 px-4 text-center">
                              <button type="button" @click="removeMedication(index)"
                                class="text-red-500 hover:text-red-700 transition">
                                <span class="material-symbols-outlined text-xl">delete</span>
                              </button>

                              <!-- Hidden Inputs for Submission -->
                              <input type="hidden" :name="`medications[${index}][name]`" :value="item.name">
                              <input type="hidden" :name="`medications[${index}][quantity]`" :value="item.quantity">
                              <input type="hidden" :name="`medications[${index}][medication_id]`" :value="item.id">
                            </td>
                          </tr>
                        </template>
                      </tbody>
                    </table>
                  </div>

                  <div x-show="medicationsItems.length === 0"
                    class="text-center py-6 text-neutral-text dark:text-neutral-text-dark text-sm bg-gray-50 dark:bg-gray-800/30 rounded-lg border border-dashed border-border-light dark:border-border-dark">
                    No medications added yet.
                  </div>
                </div>

                <!-- Special Instructions -->
                <div>
                  <label class="block text-sm font-medium text-body-text dark:text-body-text-dark mb-1.5" for="notes">
                    {{ __('prescription.upload_step1.special_instructions') }}
                  </label>
                  <textarea
                    class="w-full rounded-lg border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark focus:border-primary focus:ring-primary/50"
                    id="notes" name="special_instructions"
                    placeholder="{{ __('prescription.upload_step1.special_instructions_placeholder') }}"
                    rows="4"></textarea>
                </div>
              </div>

              <!-- Submit Button -->
              <div class="flex justify-end p-4 mt-4 border-t border-border-light dark:border-border-dark">
                <button type="submit" id="submit-button"
                  class="flex w-full md:w-auto items-center justify-center gap-2 rounded-lg h-12 px-8 bg-primary text-white text-base font-bold tracking-wide disabled:bg-neutral-text disabled:cursor-not-allowed hover:bg-primary/90 transition"
                  :disabled="medicationsItems.length === 0 || !professionalLicense">
                  <span>{{ __('prescription.upload_step1.submit') }}</span>
                  <span class="material-symbols-outlined">arrow_forward</span>
                </button>
              </div>
            </form>
          </main>
        </div>
      </div>
    </div>
  </div>
</body>

</html>