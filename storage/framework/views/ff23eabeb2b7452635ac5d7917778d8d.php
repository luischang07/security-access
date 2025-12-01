<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-wrap justify-between items-center gap-4 mb-8">
        <div class="flex flex-col gap-1">
            <h1 class="text-body-text dark:text-body-text-dark text-3xl font-black leading-tight tracking-[-0.033em]">
                <?php echo e(__('patient.orders.menu_title')); ?>

            </h1>
        </div>
    </div>
    <!-- Order History Section -->
    <section>
        <h2 class="text-body-text dark:text-body-text-dark text-xl font-bold leading-tight tracking-[-0.015em] mb-4">
            <?php echo e(__('patient.orders.order_history')); ?>

        </h2>

        <!-- Order List -->
        <div class="space-y-3">
            <?php
                // El controlador pasa una colección de objetos de dominio como $pedidos
                $pedidosCollection = isset($pedidos) ? $pedidos : collect();
            ?>

            <?php if($pedidosCollection && count($pedidosCollection) > 0): ?>
                <?php $__currentLoopData = $pedidosCollection; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pedido): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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

                    <div class="rounded-lg border border-border-light dark:border-border-dark shadow-sm bg-card-light dark:bg-card-dark p-4 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="flex-1 w-full">
                            <p class="text-sm text-neutral-text dark:text-neutral-text-dark">
                                <?php echo e(__('patient.orders.order_id')); ?>: <?php echo e($pedido->getFolio() ?? '-'); ?>

                            </p>
                            <p class="text-base font-bold text-body-text dark:text-body-text-dark">
                                <?php
                                    $lineas = $pedido->getLineasPedidos();
                                    $nombres = collect();
                                    foreach ($lineas as $linea) {
                                        $med = $linea->getMedicamento();
                                        if ($med) {
                                            $nombres->push($med->getNombre());
                                        }
                                    }
                                ?>
                                <?php echo e($nombres->count() ? $nombres->join(', ') : __('patient.orders.no_items')); ?>

                            </p>
                            <p class="text-sm text-neutral-text dark:text-neutral-text-dark">
                                <?php
                                    $fecha = $pedido->getFechaRecoleccion();
                                    if ($fecha instanceof \Carbon\Carbon) {
                                        echo $fecha->format('M d, Y');
                                    }
                                ?>
                            </p>
                        </div>
                        <div class="flex items-center justify-between w-full sm:w-auto gap-4">
                            <span class="<?php echo e($statusClass); ?>">
                                <?php if($estatus === 'collected'): ?>
                                    <?php echo e(__('patient.orders.status.collected')); ?>

                                <?php elseif($estatus === 'cancelled'): ?>
                                    <?php echo e(__('patient.orders.status.cancelled')); ?>

                                <?php else: ?>
                                    <?php echo e($estatus ?? __('patient.orders.status.unknown')); ?>

                                <?php endif; ?>
                            </span>
                            <a href="<?php echo e(route('patient.orders.show', $pedido->getFolio())); ?>" class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-9 px-4 bg-background-light dark:bg-background-dark text-body-text dark:text-body-text-dark text-sm font-medium leading-normal hover:bg-border-light dark:hover:bg-border-dark transition">
                                <span class="truncate"><?php echo e(__('patient.orders.view_details')); ?></span>
                            </a>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php else: ?>
                <div class="rounded-lg border border-border-light dark:border-border-dark shadow-sm bg-card-light dark:bg-card-dark p-6 text-center">
                    <p class="text-neutral-text dark:text-neutral-text-dark"><?php echo e(__('patient.orders.no_orders') ?? 'No hay pedidos para mostrar.'); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </section>
</div>
<?php /**PATH C:\xampp\htdocs\laravel\securityAccess\security-access\resources\views/patient/partials/orders-content.blade.php ENDPATH**/ ?>