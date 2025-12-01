<?php $__env->startSection('title', __('patient.order_detail.title')); ?>

<?php $__env->startSection('spa-content'); ?>
<div class="max-w-7xl mx-auto">
    <!-- Success Banner -->
    <?php if(session('order_success')): ?>
        <div class="mb-6 rounded-lg border border-success/30 bg-success/10 text-success px-4 py-3 flex items-center gap-3">
            <span class="material-symbols-outlined text-xl">check_circle</span>
            <div class="flex flex-col">
                <strong class="text-sm">¡Pedido realizado con éxito!</strong>
                <span class="text-sm text-success/90">Te avisaremos cuando esté listo para recolección. A continuación los detalles de tu pedido.</span>
            </div>
        </div>
    <?php endif; ?>

    <!-- Header -->
    <div class="flex flex-wrap justify-between items-center gap-4 mb-8">
        <div class="flex flex-col gap-1">
            <h1 class="text-body-text dark:text-body-text-dark text-3xl font-black leading-tight tracking-[-0.033em]">
                <?php echo e(__('patient.order_detail.order')); ?> #<?php echo e($pedido->getFolio() ?? '-'); ?>

            </h1>
        </div>
        <?php
            $estatus = $pedido->getEstatus();
            $statusClass = 'inline-flex items-center rounded-full px-3 py-1 text-sm font-medium';
            if ($estatus === 'collected') {
                $statusClass .= ' bg-success/10 text-success';
            } elseif ($estatus === 'cancelled') {
                $statusClass .= ' bg-red-500/10 text-red-500';
            } else {
                $statusClass .= ' bg-neutral-100 text-neutral-text dark:text-neutral-text-dark';
            }
        ?>
        <span class="<?php echo e($statusClass); ?>">
            <?php if($estatus === 'collected'): ?>
                <?php echo e(__('patient.orders.status.collected')); ?>

            <?php elseif($estatus === 'cancelled'): ?>
                <?php echo e(__('patient.orders.status.cancelled')); ?>

            <?php else: ?>
                <?php echo e($estatus ?? __('patient.orders.status.unknown')); ?>

            <?php endif; ?>
        </span>
    </div>

    <!-- Order Info Grid -->
    <section class="mb-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Order Date -->
            <div class="rounded-lg border border-border-light dark:border-border-dark shadow-sm bg-card-light dark:bg-card-dark p-6">
                <div class="flex items-start gap-4">
                    <span class="material-symbols-outlined text-2xl text-primary mt-1">calendar_month</span>
                    <div class="flex flex-col">
                        <h3 class="text-sm font-medium text-neutral-text dark:text-neutral-text-dark">
                            <?php echo e(__('patient.order_detail.order_date')); ?>

                        </h3>
                        <p class="text-base font-bold text-body-text dark:text-body-text-dark mt-1">
                            <?php
                                $fechaPedido = $pedido->getFechaPedido();
                                if ($fechaPedido instanceof \Carbon\Carbon) {
                                    echo $fechaPedido->translatedFormat('l, j \de F Y');
                                }
                            ?>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Pickup Date -->
            <div class="rounded-lg border border-border-light dark:border-border-dark shadow-sm bg-card-light dark:bg-card-dark p-6">
                <div class="flex items-start gap-4">
                    <span class="material-symbols-outlined text-2xl text-primary mt-1">schedule</span>
                    <div class="flex flex-col">
                        <h3 class="text-sm font-medium text-neutral-text dark:text-neutral-text-dark">
                            <?php echo e(__('patient.order_detail.pickup_date')); ?>

                        </h3>
                        <p class="text-base font-bold text-body-text dark:text-body-text-dark mt-1">
                            <?php
                                $fechaReco = $pedido->getFechaRecoleccion();
                                if ($fechaReco instanceof \Carbon\Carbon) {
                                    echo $fechaReco->translatedFormat('l, j \de F Y') . ' alas ' . $fechaReco->format('H:i');
                                }
                            ?>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Pharmacy -->
            <div class="rounded-lg border border-border-light dark:border-border-dark shadow-sm bg-card-light dark:bg-card-dark p-6">
                <div class="flex items-start gap-4">
                    <span class="material-symbols-outlined text-2xl text-primary mt-1">local_pharmacy</span>
                    <div class="flex flex-col">
                        <h3 class="text-sm font-medium text-neutral-text dark:text-neutral-text-dark">
                            <?php echo e(__('patient.order_detail.pharmacy')); ?>

                        </h3>
                        <p class="text-base font-bold text-body-text dark:text-body-text-dark mt-1">
                            <?php echo e($pedido->getSucursal()?->getNombre() ?? '-'); ?>

                        </p>
                        <p class="text-sm text-neutral-text dark:text-neutral-text-dark mt-2">
                            <?php echo e($pedido->getSucursal()?->getDireccion() ?? '-'); ?>

                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Medications Table -->
    <section class="mb-8">
        <div class="rounded-lg border border-border-light dark:border-border-dark shadow-sm bg-card-light dark:bg-card-dark p-6">
            <h2 class="text-xl font-bold text-body-text dark:text-body-text-dark mb-4">
                <?php echo e(__('patient.order_detail.medications')); ?>

            </h2>
            <div class="flow-root">
                <div class="-mx-6 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                        <table class="min-w-full divide-y divide-border-light dark:divide-border-dark">
                            <thead>
                                <tr>
                                    <th class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-body-text dark:text-body-text-dark sm:pl-0"
                                        scope="col">
                                        <?php echo e(__('patient.order_detail.medication')); ?>

                                    </th>
                                    <th class="px-3 py-3.5 text-left text-sm font-semibold text-body-text dark:text-body-text-dark"
                                        scope="col">
                                        <?php echo e(__('patient.order_detail.quantity')); ?>

                                    </th>
                                    <th class="px-3 py-3.5 text-left text-sm font-semibold text-body-text dark:text-body-text-dark"
                                        scope="col">
                                        <?php echo e(__('patient.order_detail.unit_price')); ?>

                                    </th>
                                    <th class="px-3 py-3.5 text-right text-sm font-semibold text-body-text dark:text-body-text-dark"
                                        scope="col">
                                        <?php echo e(__('patient.order_detail.subtotal')); ?>

                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-border-light dark:divide-border-dark">
                                <?php
                                    $lineas = $pedido->getLineasPedidos();
                                    $totalGeneral = 0;
                                ?>

                                <?php if($lineas && count($lineas) > 0): ?>
                                    <?php $__currentLoopData = $lineas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $linea): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $detalles = $linea->getDetalles() ?: collect();
                                            $lineTotal = 0;
                                            foreach ($detalles as $detalle) {
                                                $precio = $detalle->getPrecio();
                                                $cantidad = $detalle->getCantidadSurtida();
                                                $lineTotal += $precio * $cantidad;
                                            }
                                            $totalGeneral += $lineTotal;
                                        ?>
                                        <tr>
                                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-0">
                                                <div class="font-medium text-body-text dark:text-body-text-dark">
                                                    <?php echo e($linea->getMedicamento()?->getNombre() ?? 'Desconocido'); ?>

                                                </div>
                                                <div class="text-sm text-neutral-text dark:text-neutral-text-dark">
                                                    <?php echo e($linea->getMedicamento()?->getUnidadMedida() ?? '-'); ?>

                                                </div>
                                            </td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-neutral-text dark:text-neutral-text-dark">
                                                <?php echo e($linea->getCantidad()); ?>

                                            </td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-neutral-text dark:text-neutral-text-dark">
                                                <?php
                                                    $precio = $detalles->first()?->getPrecio() ?? 0;
                                                ?>
                                                <?php echo e('$' . number_format($precio, 2)); ?>

                                            </td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-neutral-text dark:text-neutral-text-dark text-right font-medium">
                                                <?php echo e('$' . number_format($lineTotal, 2)); ?>

                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($pedido->getMontoPenalizacion() && $pedido->getMontoPenalizacion() > 0): ?>
                                    <tr>
                                        <td colspan="3" class="py-4 pl-4 pr-3 text-sm text-right font-medium text-red-600 sm:pl-0">
                                            Penalización 
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-right font-medium text-red-600">
                                            <?php echo e('$' . number_format($pedido->getMontoPenalizacion(), 2)); ?>

                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                    <tr class="border-t-2 border-border-light dark:border-border-dark bg-background-light dark:bg-background-dark">
                                        <td colspan="3" class="py-4 pl-4 pr-3 text-sm text-right font-bold text-body-text dark:text-body-text-dark sm:pl-0">
                                            <?php echo e(__('patient.order_detail.total')); ?>

                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-right font-bold text-primary">
                                            <?php echo e('$' . number_format($pedido->getCostoTotal(), 2)); ?>

                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="py-4 text-center text-sm text-neutral-text dark:text-neutral-text-dark">
                                            <?php echo e(__('patient.order_detail.no_medications') ?? 'No hay medicamentos en este pedido.'); ?>

                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Action Buttons -->
    <section class="flex justify-start items-center gap-4 mb-8">
        <a href="<?php echo e(route('patient.orders')); ?>" class="flex items-center justify-center gap-2 rounded-lg h-12 px-8 text-neutral-text dark:text-neutral-text-dark text-base font-bold tracking-wide hover:bg-background-light dark:hover:bg-background-dark transition">
            <span class="material-symbols-outlined">arrow_back</span>
            <span><?php echo e(__('patient.order_detail.back_to_list')); ?></span>
        </a>
    </section>
</div>
<?php $__env->stopSection(); ?>

<?php if(session('order_success')): ?>
<?php $__env->startPush('scripts'); ?>
<script>
  // Previene navegar hacia atrás inmediatamente después de confirmar
  history.pushState(null, '', location.href);
  window.addEventListener('popstate', function () {
    history.pushState(null, '', location.href);
  });
</script>
<?php $__env->stopPush(); ?>
<?php endif; ?>

<?php echo $__env->make('layouts.patient-spa', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/jesusarturo/Desktop/mvc/Te-Acerco-Salud/resources/views/patient/order-detail.blade.php ENDPATH**/ ?>