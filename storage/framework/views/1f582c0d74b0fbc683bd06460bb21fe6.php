<!DOCTYPE html>
<html class="light" lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
  <meta charset="utf-8">
  <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo e(__('prescription.upload_step1.title')); ?> - Te Acerco Salud</title>
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
  <?php echo app('Illuminate\Foundation\Vite')(['resources/js/patient/prescription-form.js']); ?>
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
                <?php echo e(__('prescription.upload_step1.brand_name')); ?>

              </h2>
            </div>
            <div class="flex flex-1 justify-end gap-4 sm:gap-8">
              <div class="hidden sm:flex items-center gap-9">
                <a class="text-sm font-medium leading-normal text-body-text dark:text-body-text-dark hover:text-primary transition"
                  href="<?php echo e(route('patient.dashboard')); ?>">
                  <?php echo e(__('prescription.upload_step1.dashboard')); ?>

                </a>
              </div>
              <button
                class="flex max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 bg-background-light dark:bg-background-dark text-body-text dark:text-body-text-dark gap-2 text-sm font-bold leading-normal tracking-[0.015em] min-w-0 px-2.5 hover:bg-border-light dark:hover:bg-border-dark transition">
                <span class="material-symbols-outlined text-xl">help</span>
              </button>
              <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10"
                style='background-image: url("https://ui-avatars.com/api/?name=<?php echo e(urlencode(auth()->user()->name ?? 'User')); ?>&background=137fec&color=fff");'>
              </div>
            </div>
          </header>

          <main class="flex flex-col gap-6" x-data="prescriptionForm()">
            <!-- Page Heading -->
            <div class="flex flex-wrap justify-between gap-3 px-4">
              <div class="flex flex-col gap-2">
                <h1 class="text-3xl lg:text-4xl font-black tracking-[-0.033em] text-body-text dark:text-body-text-dark">
                  <?php echo e(__('prescription.upload_step1.page_heading')); ?>

                </h1>
                <p class="text-base font-normal leading-normal text-neutral-text dark:text-neutral-text-dark">
                  <?php echo e(__('prescription.upload_step1.page_subtitle')); ?>

                </p>
              </div>
            </div>
            <?php if($errors->any()): ?>
              <div class="mx-4 rounded-lg border border-red-300/60 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-500/50 dark:bg-red-900/20 dark:text-red-100">
                <div class="flex gap-3">
                  <span class="material-symbols-outlined text-xl mt-0.5">error</span>
                  <div class="flex flex-col gap-1">
                    <p class="font-semibold">No pudimos confirmar el pedido. Intenta de nuevo.</p>
                    <ul class="list-disc pl-5 space-y-1">
                      <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                  </div>
                </div>
              </div>
            <?php endif; ?>
            <!-- Prescription Form -->
            <form id="prescription-form" method="POST" @submit="validateForm"
              action="<?php echo e(route('prescription.upload.step1.store')); ?>">
              <?php echo csrf_field(); ?>

              <div class="mb-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div>
                    <label class="block text-sm font-medium text-body-text dark:text-body-text-dark mb-1.5"
                      for="cadena_id">
                      <?php echo e(__('prescription.upload_step1.select_chain')); ?>

                    </label>
                    <select
                      class="w-full rounded-lg border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark focus:border-primary focus:ring-primary/50"
                      id="cadena_id" name="cadena_id" x-model="cadenaId" required>
                      <option value="" selected disabled>
                        <?php echo e(__('prescription.upload_step1.select_option')); ?>

                      </option>
                      <?php $__currentLoopData = $cadenas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cadena): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($cadena->cadena_id); ?>"><?php echo e($cadena->nombre); ?>

                        </option>
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-body-text dark:text-body-text-dark mb-1.5"
                      for="sucursal_id">
                      <?php echo e(__('prescription.upload_step1.select_branch')); ?>

                    </label>
                    <select
                      class="w-full rounded-lg border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark focus:border-primary focus:ring-primary/50"
                      id="sucursal_id" name="sucursal_id" x-model="sucursalId"
                      :disabled="!cadenaId || loadingSucursales" required>
                      <option value="" selected disabled>
                        <?php echo e(__('prescription.upload_step1.select_option')); ?>

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
                  <?php echo e(__('prescription.upload_step1.prescription_details')); ?>

                </h2>

                <!-- Patient and Doctor Info -->


                <!-- Professional License -->
                <div>
                  <label class="block text-sm font-medium text-body-text dark:text-body-text-dark mb-1.5"
                    for="cedula_profesional">
                    <?php echo e(__('prescription.upload_step1.professional_license')); ?>

                  </label>
                  <input
                    class="w-full rounded-lg border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark focus:border-primary focus:ring-primary/50"
                    id="cedula_profesional" name="cedula_profesional"
                    placeholder="<?php echo e(__('prescription.upload_step1.professional_license_placeholder')); ?>" type="text"
                    x-model="professionalLicense" required />
                </div>

                <!-- Medications Section -->
                <div class="flex flex-col gap-4 border-t border-border-light dark:border-border-dark pt-6">
                  <h3 class="text-lg font-bold text-body-text dark:text-body-text-dark">
                    <?php echo e(__('prescription.upload_step1.medications')); ?>

                  </h3>
                  <div class="p-4 border border-border-light dark:border-border-dark rounded-lg bg-card-light dark:bg-card-dark space-y-4">
                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-medium text-body-text dark:text-body-text-dark" for="medication-search">
                                Buscar medicamento
                            </label>
                            <div class="relative">
                                <input id="medication-search" type="text"
                                    class="w-full rounded-lg border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark focus:border-primary focus:ring-primary/50"
                                    placeholder="Ingresa el nombre del medicamento" autocomplete="off" />
                                <div id="medication-suggestions"
                                    class="absolute left-0 right-0 mt-1 z-20 hidden rounded-lg border border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark shadow-lg max-h-56 overflow-y-auto">
                                </div>
                            </div>
                            <p class="text-xs text-neutral-text dark:text-neutral-text-dark">
                                Escribe al menos 2 caracteres para buscar y selecciona un resultado de la lista.
                            </p>
                        </div>

                        <div id="selected-medication-panel"
                            class="hidden rounded-lg border border-dashed border-primary/40 bg-primary/5 dark:bg-primary/10 p-4 space-y-3">
                            <div class="flex items-center justify-between gap-3">
                                <div class="flex items-center gap-2 text-primary font-semibold">
                                    <span class="material-symbols-outlined text-xl">check_circle</span>
                                    <span>Elemento seleccionado</span>
                                </div>
                                <button type="button" id="clear-selected-medication"
                                    class="text-sm text-neutral-text dark:text-neutral-text-dark hover:text-red-500 transition">
                                    Cambiar selección
                                </button>
                            </div>
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:gap-4">
                                <div class="flex-1">
                                    <p class="text-base font-bold text-body-text dark:text-body-text-dark" id="selected-medication-name">
                                        --
                                    </p>
                                    <p class="text-xs text-neutral-text dark:text-neutral-text-dark" id="selected-medication-meta"></p>
                                </div>
                                <div class="sm:w-36">
                                    <label
                                        class="block text-sm font-medium text-body-text dark:text-body-text-dark mb-1.5"
                                        for="selected-quantity">
                                        Cantidad
                                    </label>
                                    <input id="selected-quantity" type="number" min="1" value="1"
                                        class="w-full rounded-lg border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark focus:border-primary focus:ring-primary/50" />
                                </div>
                                <button type="button" id="add-selected-medication"
                                    class="flex items-center justify-center gap-2 h-11 px-4 rounded-lg bg-primary text-white font-bold text-sm hover:bg-primary/90 transition">
                                    <span class="material-symbols-outlined text-xl">add_circle</span>
                                    <span>Agregar</span>
                                </button>
                            </div>
                        </div>

                        <div class="flex flex-col gap-3">
                            <div class="flex items-center justify-between">
                                <h4 class="text-base font-semibold text-body-text dark:text-body-text-dark">Medicamentos agregados</h4>
                                <span id="medications-count"
                                    class="rounded-full bg-background-light dark:bg-background-dark px-3 py-1 text-xs font-semibold text-neutral-text dark:text-neutral-text-dark">
                                    0 seleccionados
                                </span>
                            </div>
                            <div class="overflow-hidden rounded-lg border border-border-light dark:border-border-dark">
                                <table class="min-w-full divide-y divide-border-light dark:divide-border-dark text-sm">
                                    <thead class="bg-background-light/60 dark:bg-background-dark/60">
                                        <tr>
                                            <th class="px-4 py-3 text-left font-semibold text-body-text dark:text-body-text-dark">Medicamento</th>
                                            <th class="px-4 py-3 text-left font-semibold text-body-text dark:text-body-text-dark">Cantidad</th>
                                            <th class="px-4 py-3 text-right font-semibold text-body-text dark:text-body-text-dark"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="medications-table-body" class="divide-y divide-border-light dark:divide-border-dark">
                                        <tr id="medications-empty-state">
                                            <td colspan="3" class="px-4 py-4 text-neutral-text dark:text-neutral-text-dark text-center">
                                                Aún no has agregado medicamentos.
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                  <div id="medications-hidden-inputs" class="hidden"></div>
                </div>
              </div>

              <!-- Submit Button -->
              <div class="flex justify-end p-4 mt-4 border-t border-border-light dark:border-border-dark">
                <button type="submit" id="submit-button"
                  class="flex w-full md:w-auto items-center justify-center gap-2 rounded-lg h-12 px-8 bg-primary text-white text-base font-bold tracking-wide disabled:bg-neutral-text disabled:cursor-not-allowed hover:bg-primary/90 transition"
                  :disabled="medicationsItems.length === 0 || !professionalLicense">
                  <span><?php echo e(__('prescription.upload_step1.submit')); ?></span>
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

<?php
    $pedidoInicial = null;
    if (isset($pedido)) {
        $pedidoInicial = [
            'cadena_id' => optional($pedido->getSucursal())->getCadenaId(),
            'sucursal_id' => optional($pedido->getSucursal())->getSucursalId(),
            'cedula_profesional' => $pedido->getCedulaProfesional(),
            'medications' => [],
        ];

        $lineas = $pedido->getLineasPedidos() ?? collect();
        foreach ($lineas as $linea) {
            $pedidoInicial['medications'][] = [
                'id' => $linea->getMedicamentoId(),
                'name' => $linea->getMedicamento()->getNombre(),
                'quantity' => (int) $linea->getCantidad(),
            ];
        }
    }
?>

<script>
    window.initialPrescription = <?php echo json_encode($pedidoInicial, 15, 512) ?>;
    window.prescriptionTranslations = <?php echo json_encode([
        'select_option' => __('prescription.upload_step1.select_option'), ], 512) ?>;
    window.routes = {
        sucursalesByCadena: "<?php echo e(route('prescription.sucursales.by_cadena', ['cadena_id' => '%%CADENA%%'])); ?>",
        medicationsSearch: "<?php echo e(route('prescription.medications.search')); ?>",
        medicationsAdd: "<?php echo e(route('prescription.medications.add')); ?>",
        medicationsRemove: "<?php echo e(route('prescription.medications.remove')); ?>"
    };
    window.initialMedications = <?php echo json_encode(old('medications', []), 512) ?>;
</script>
<?php echo app('Illuminate\Foundation\Vite')(['resources/js/patient/prescription-upload.js']); ?>

</html>
<?php /**PATH /Users/jesusarturo/Desktop/mvc/Te-Acerco-Salud/resources/views/prescription/upload-step1.blade.php ENDPATH**/ ?>