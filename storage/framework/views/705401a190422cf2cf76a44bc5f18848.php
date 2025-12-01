<!DOCTYPE html>
<html class="light" lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo e(__('prescription.upload_step2.title')); ?> - Te Acerco Salud</title>
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
                                    href="<?php echo e(route('patient.dashboard')); ?>">
                                    Dashboard
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

                    <main class="flex flex-col gap-6">
                        <!-- Page Heading -->
                        <div class="flex flex-col gap-2 px-4">
                            <h1
                                class="text-3xl lg:text-4xl font-black tracking-[-0.033em] text-body-text dark:text-body-text-dark">
                                <?php echo e(__('prescription.upload_step2.heading')); ?>

                            </h1>
                            <p
                                class="text-base font-normal leading-normal text-neutral-text dark:text-neutral-text-dark">
                                <?php echo e(__('prescription.upload_step2.subtitle')); ?>

                            </p>
                        </div>
                        <?php if(method_exists($pedido, 'tieneFaltantes') && $pedido->tieneFaltantes()): ?>
                            <div class="mx-4 rounded-lg border border-warning/40 bg-warning/10 px-4 py-3 text-sm text-warning dark:border-warning/30 dark:bg-warning/15">
                                <div class="flex gap-3">
                                    <span class="material-symbols-outlined text-xl mt-0.5">warning</span>
                                    <div class="flex flex-col gap-1">
                                        <p class="font-semibold  ">Algunos medicamentos no están disponibles en sucursales cercanas.</p>
                                        <ul class="list-disc pl-5 space-y-1 text-body-text dark:text-body-text-dark">
                                            <?php $__currentLoopData = $pedido->getFaltantes(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $faltante): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <li>
                                                    <?php echo e($faltante->getMedicamento()->getNombre()); ?>

                                                    — solicitaste <?php echo e($faltante->getCantidad()); ?>, faltan <?php echo e($faltante->getCantidadFaltante()); ?>.
                                                </li>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

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
                                            <?php echo e(__('prescription.upload_step2.selected_pharmacy')); ?>

                                        </h3>
                                        <p class="text-base font-bold text-body-text dark:text-body-text-dark">
                                            <?php echo e($pedido->getSucursal()->getNombre()); ?>

                                        </p>
                                        <p class="text-sm text-neutral-text dark:text-neutral-text-dark">
                                            <?php echo e($pedido->getSucursal()->getDireccion()); ?>

                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-4">
                                    <span class="material-symbols-outlined text-2xl text-primary mt-1">schedule</span>
                                    <div class="flex flex-col">
                                        <h3 class="text-sm font-medium text-neutral-text dark:text-neutral-text-dark">
                                            <?php echo e(__('prescription.upload_step2.estimated_pickup')); ?>

                                        </h3>
                                        <p class="text-base font-bold text-body-text dark:text-body-text-dark">
                                            <?php
                                                $fechaReco = $pedido->getFechaRecoleccion();
                                            ?>
                                            <?php if($fechaReco instanceof \Carbon\Carbon): ?>
                                                   <?php echo e($fechaReco->translatedFormat('l, j \de F Y') . ' a las 12:00 pm'); ?>

                                            <?php elseif(is_string($fechaReco) && \Carbon\Carbon::canBeCreatedFromFormat($fechaReco)): ?>
                                                   <?php $dt = \Carbon\Carbon::parse($fechaReco); ?>
                                                   <?php echo e($dt->translatedFormat('l, j \de F Y') . ' a las ' . $dt->format('H:i')); ?>

                                            <?php elseif(is_string($fechaReco)): ?>
                                                <?php echo e($fechaReco); ?>

                                            <?php else: ?>
                                                <?php echo e(__('prescription.upload_step2.unknown_date') ?? '-'); ?>

                                            <?php endif; ?>
                                        </p>
                                        <p class="text-sm text-neutral-text dark:text-neutral-text-dark">
                                            <?php echo e(__('prescription.upload_step2.pickup_notification')); ?>

                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Medications Table -->
                            <div class="flex flex-col">
                                <h3 class="text-lg font-bold text-body-text dark:text-body-text-dark mb-4">
                                    <?php echo e(__('prescription.upload_step2.prescribed_medications')); ?>

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
                                                            <?php echo e(__('prescription.upload_step2.medication')); ?>

                                                        </th>
                                                        <th class="px-3 py-3.5 text-left text-sm font-semibold text-body-text dark:text-body-text-dark"
                                                            scope="col">
                                                            <?php echo e(__('prescription.upload_step2.quantity')); ?>

                                                        </th>
                                                        <th class="px-3 py-3.5 text-right text-sm font-semibold text-body-text dark:text-body-text-dark"
                                                            scope="col">
                                                            <?php echo e(__('prescription.upload_step2.price')); ?>

                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-border-light dark:divide-border-dark">
                                                    <?php
                                                        $lineas = $pedido->getLineasPedidos();
                                                        $lineasConDetalles = collect($lineas ?? [])->filter(function ($linea) {
                                                            $detalles = method_exists($linea, 'getDetalleLineaPedido')
                                                                ? $linea->getDetalleLineaPedido()
                                                                : collect();
                                                            return $detalles->count() > 0;
                                                        });
                                                        $subtotal = 0;
                                                        $serviceFee = 1.00; // tarifa de servicio fija
                                                    ?>
                                                         
                                                    <?php if($lineasConDetalles->count() > 0): ?>

                                                        <?php $__currentLoopData = $lineasConDetalles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $linea): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <?php
                                                                $detalles = method_exists($linea, 'getDetalleLineaPedido')
                                                                    ? $linea->getDetalleLineaPedido()
                                                                    : collect();

                                                                $lineTotal = 0;
                                                                $cantidadTotalSurtida = 0;
                                                            ?>

                                                            <?php $__currentLoopData = $detalles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detalle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <?php
                                                                    $lineTotal += $detalle->getPrecio() * $detalle->getCantidadSurtida();
                                                                    $cantidadTotalSurtida += $detalle->getCantidadSurtida();
                                                                ?>

                                                                <div class="font-medium text-body-text dark:text-body-text-dark">
                                                                    <?php echo e($detalle->getSucursal()->getCadenaId()); ?>

                                                                    <?php echo e($detalle->getSucursal()->getSucursalId()); ?>

                                                                </div>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                                            <?php
                                                                // 3. Sumar el total de estas líneas al subtotal global
                                                                $subtotal += $lineTotal;
                                                            ?>
                                                            <tr>
                                                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-0">
                                                                    <div class="font-medium text-body-text dark:text-body-text-dark">
                                                                        <?php echo e($linea->getMedicamento()->getNombre()); ?>

                                                                    </div>
                                                                    <div class="text-neutral-text dark:text-neutral-text-dark">
                                                                        <?php echo e(__('prescription.upload_step2.capsules')); ?></div>
                                                                </td>
                                                                <td class="whitespace-nowrap px-3 py-4 text-sm text-neutral-text dark:text-neutral-text-dark">
                                                                    <?php echo e($cantidadTotalSurtida); ?></td>
                                                                <td class="whitespace-nowrap px-3 py-4 text-sm text-neutral-text dark:text-neutral-text-dark text-right">
                                                                    <?php echo e('$' . number_format($lineTotal, 2)); ?></td>
                                                            </tr>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    <?php else: ?>
                                                        <tr>
                                                            <td colspan="3" class="py-4 text-sm text-neutral-text dark:text-neutral-text-dark">
                                                                <?php echo e(__('prescription.upload_step2.no_medications') ?? 'No hay medicamentos en el pedido.'); ?>

                                                            </td>
                                                        </tr>
                                                    <?php endif; ?>
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
                                    <span class="text-sm text-neutral-text dark:text-neutral-text-dark"><?php echo e(__('prescription.upload_step2.subtotal')); ?></span>
                                    <span class="text-sm font-medium text-body-text dark:text-body-text-dark"><?php echo e('$' . number_format($subtotal, 2)); ?></span>
                                </div>
                                <?php
                                    if($montoPenalizacion){
                                    $subtotal+=$montoPenalizacion;
                                    }
                                ?>
                                <div class="flex justify-between w-full max-w-xs">
                                    <span class="text-sm text-neutral-text dark:text-neutral-text-dark">Monto penalización </span>
                                    <span class="text-sm font-medium text-body-text dark:text-body-text-dark"><?php echo e('$' . number_format($montoPenalizacion, 2)); ?></span>
                                </div>
                                <div class="flex justify-between w-full max-w-xs mt-2 pt-2 border-t border-dashed border-border-light dark:border-border-dark">
                                    <span class="text-lg font-bold text-body-text dark:text-body-text-dark"><?php echo e(__('prescription.upload_step2.estimated_total')); ?></span>
                                    <span class="text-lg font-bold text-primary"><?php echo e('$' . number_format($subtotal, 2)); ?></span>
                                </div>
                                <p
                                    class="text-xs text-neutral-text dark:text-neutral-text-dark mt-1 text-right max-w-xs">
                                    <?php echo e(__('prescription.upload_step2.price_disclaimer')); ?>

                                </p>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <form action="/prescription/upload/step2" method="POST" class="flex flex-col-reverse sm:flex-row justify-between items-center gap-4 p-4 mt-2 w-full">
                            <?php echo csrf_field(); ?>
                            <a href="<?php echo e(route('prescription.upload.step1')); ?>"
                                class="flex items-center justify-center gap-2 rounded-lg h-12 px-8 text-neutral-text dark:text-neutral-text-dark text-base font-bold tracking-wide hover:bg-background-light dark:hover:bg-background-dark transition">
                                <span class="material-symbols-outlined">arrow_back</span>
                                <span><?php echo e(__('prescription.upload_step2.edit_prescription')); ?></span>
                            </a>
                            <button type="submit"
                                class="flex w-full sm:w-auto items-center justify-center gap-2 rounded-lg h-12 px-8 bg-primary text-white text-base font-bold tracking-wide hover:bg-primary/90 transition">
                                <span><?php echo e(__('prescription.upload_step2.confirm_order')); ?></span>
                                <span class="material-symbols-outlined">check_circle</span>
                            </button>
                        </form>
                    </main>
                </div>
            </div>
    </div>
  </div>
</body>
</html>

<script>
</script>
<?php /**PATH /Users/jesusarturo/Desktop/mvc/Te-Acerco-Salud/resources/views/prescription/upload-step2.blade.php ENDPATH**/ ?>